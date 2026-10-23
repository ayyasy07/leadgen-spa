<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->format('Y-m-d'));
        $status    = $request->get('status', 'all');
        $search    = trim($request->get('search', ''));
        $tab       = $request->get('tab', 'financial');

        if (!in_array($tab, ['financial', 'occupancy', 'service'])) {
            $tab = 'financial';
        }

        $allowedStatus = ['paid', 'completed', 'no-show', 'cancelled'];
        if ($status !== 'all' && !in_array($status, $allowedStatus)) {
            $status = 'all';
        }

        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();

        /* ===== FINANCIAL (Pakai created_at buat filter range, tapi query revenue pakai paid_at) ===== */
        $financialQuery = Booking::query()
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status', ['pending', 'expired']);

        if ($status !== 'all') {
            $financialQuery->where('status', $status);
        }

        if ($search !== '') {
            $financialQuery->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
        }

        $financialBookings = $financialQuery
            ->orderByDesc('created_at')
            ->get();

        $totalBookings  = $financialBookings->count();
        
        // Revenue cuma ngitung yang ada paid_at-nya
        $netRevenue     = (int) $financialBookings->whereNotNull('paid_at')->sum('net_revenue');
        
        $noShowCount    = $financialBookings->where('status', 'no-show')->count();
        $noShowRate     = $totalBookings > 0 ? round(($noShowCount / $totalBookings) * 100, 1) : 0;
        $cancelledCount = $financialBookings->where('status', 'cancelled')->count();
        $cancelledRate  = $totalBookings > 0 ? round(($cancelledCount / $totalBookings) * 100, 1) : 0;

        $statusBreakdown = [
            'paid'      => $financialBookings->where('status', 'paid')->count(),
            'completed' => $financialBookings->where('status', 'completed')->count(),
            'no-show'   => $noShowCount,
            'cancelled' => $cancelledCount,
        ];

        $summary = [
            'net_revenue'    => $netRevenue,
            'total_bookings' => $totalBookings,
            'no_show_rate'   => $noShowRate,
            'no_show_count'  => $noShowCount,
            'cancelled'      => $cancelledCount,
            'cancelled_rate' => $cancelledRate,
        ];

        /* ===== AUTO-SCALE CHART GROUPING ===== */
        $daysDiff = $start->diffInDays($end) + 1;

        if ($daysDiff <= 31) {
            $groupBy = 'day';
        } elseif ($daysDiff <= 180) {
            $groupBy = 'week';
        } else {
            $groupBy = 'month';
        }

        $revenueChart = $this->buildRevenueChart(
            $start,
            $end,
            $groupBy,
            $financialBookings
        );

        $statusChart = [
            'labels' => ['Paid', 'Completed', 'No-Show', 'Cancelled'],
            'values' => [
                $statusBreakdown['paid'],
                $statusBreakdown['completed'],
                $statusBreakdown['no-show'],
                $statusBreakdown['cancelled'],
            ],
        ];

        /* ===== OPERATIONAL (Pake booking_date) ===== */
        $operationalBookings = Booking::query()
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->get();

        $occupancyRaw = $operationalBookings
            ->groupBy('booking_time')
            ->map(function ($group, $time) {
                $active = $group->whereIn('status', ['paid', 'completed', 'no-show']);
                return [
                    'time'    => $time,
                    'booked'  => $active->count(),
                    'slots'   => $active->sum(fn($b) => $b->countSlots()),
                    'revenue' => (int) $active->whereNotNull('paid_at')->sum('net_revenue'),
                ];
            })
            ->sortKeys()
            ->values();

        $busiestTime  = $occupancyRaw->sortByDesc('booked')->first();
        $quietestTime = $occupancyRaw->sortBy('booked')->first();

        $serviceRaw = [];
        foreach ($operationalBookings as $b) {
            if (!in_array($b->status, ['paid', 'completed', 'no-show'])) continue;

            foreach ($b->items ?? [] as $item) {
                $name = $item['name'] ?? 'Unknown';
                $qty  = (int) ($item['qty'] ?? 1);
                $unit = $item['unit'] ?? 'person';

                if (!isset($serviceRaw[$name])) {
                    $serviceRaw[$name] = ['name' => $name, 'qty' => 0, 'slots' => 0, 'revenue' => 0];
                }

                $serviceRaw[$name]['qty']     += $qty;
                $serviceRaw[$name]['slots']   += ($unit === 'couple') ? ($qty * 2) : $qty;
                $serviceRaw[$name]['revenue'] += (int) ($item['subtotal'] ?? ($item['price'] * $qty));
            }
        }

        $services = collect($serviceRaw)->sortByDesc('qty')->values();

        return view('pages.reports.index', [
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'status'            => $status,
            'search'            => $search,
            'tab'               => $tab,
            'groupBy'           => $groupBy,

            'bookings'          => $financialBookings,
            'summary'           => $summary,
            'statusBreakdown'   => $statusBreakdown,
            'revenueChart'      => $revenueChart,
            'statusChart'       => $statusChart,

            'occupancy'         => $occupancyRaw,
            'busiestTime'       => $busiestTime,
            'quietestTime'      => $quietestTime,
            'services'          => $services,

            'layout'            => 'side-menu',
        ]);
    }

    /* ============================================
       BUILD REVENUE CHART — auto-scale (Pake paid_at)
    ============================================ */
    private function buildRevenueChart(Carbon $start, Carbon $end, string $groupBy, $bookings): array
    {
        $labels = [];
        $values = [];

        $dailyMap = $bookings->whereNotNull('paid_at')
            ->groupBy(fn($b) => $b->paid_at->format('Y-m-d'))
            ->map(fn($group) => (int) $group->sum('net_revenue'))
            ->toArray();

        $cursor = $start->copy();

        if ($groupBy === 'day') {
            while ($cursor->lte($end)) {
                $key = $cursor->format('Y-m-d');
                $labels[] = $cursor->format('d M');
                $values[] = (int) ($dailyMap[$key] ?? 0);
                $cursor->addDay();
            }
        } elseif ($groupBy === 'week') {
            $weekNum = 1;
            while ($cursor->lte($end)) {
                $weekStart = $cursor->copy();
                $weekEnd   = $cursor->copy()->addDays(6);
                if ($weekEnd->gt($end)) $weekEnd = $end->copy();

                $sum = 0;
                $tmp = $weekStart->copy();
                while ($tmp->lte($weekEnd)) {
                    $key = $tmp->format('Y-m-d');
                    $sum += (int) ($dailyMap[$key] ?? 0);
                    $tmp->addDay();
                }

                $labels[] = 'Week ' . $weekNum . ' (' . $weekStart->format('d M') . ')';
                $values[] = $sum;

                $cursor->addDays(7);
                $weekNum++;
            }
        } else {
            $monthlyMap = [];
            foreach ($dailyMap as $date => $rev) {
                $mKey = Carbon::parse($date)->format('Y-m');
                if (!isset($monthlyMap[$mKey])) $monthlyMap[$mKey] = 0;
                $monthlyMap[$mKey] += $rev;
            }

            $cursor = $start->copy()->startOfMonth();
            $endM   = $end->copy()->startOfMonth();
            while ($cursor->lte($endM)) {
                $mKey = $cursor->format('Y-m');
                $labels[] = $cursor->format('M Y');
                $values[] = (int) ($monthlyMap[$mKey] ?? 0);
                $cursor->addMonth();
            }
        }

        return [
            'labels'  => $labels,
            'values'  => $values,
            'groupBy' => $groupBy,
        ];
    }

    public function financial(Request $request)
    {
        return redirect()->route('admin.reports.index', array_merge($request->query(), ['tab' => 'financial']));
    }

    public function operational(Request $request)
    {
        return redirect()->route('admin.reports.index', array_merge($request->query(), ['tab' => 'service']));
    }

    /* ============================================
       EXPORT CSV
    ============================================ */
    public function export(Request $request): StreamedResponse
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->format('Y-m-d'));
        $status    = $request->get('status', 'all');
        $search    = trim($request->get('search', ''));

        $allowedStatus = ['paid', 'completed', 'no-show', 'cancelled'];
        if ($status !== 'all' && !in_array($status, $allowedStatus)) {
            $status = 'all';
        }

        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();

        // Filter pake created_at
        $query = Booking::query()
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status', ['pending', 'expired']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
        }

        $bookings = $query->orderByDesc('created_at')->get();

        $filename = 'financial-report-' . $startDate . '-to-' . $endDate . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');

            fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fwrite($file, "sep=,\r\n");

            fputcsv($file, [
                'Booking Code', 'Customer Name', 'Phone', 'Email',
                'Booking Date', 'Booking Time', 'Items', 'Guests/Slots',
                'Total Price', 'Refund %', 'Net Revenue', 'Status', 'Booked At',
            ], ',');

            foreach ($bookings as $b) {
                $itemsStr = collect($b->items ?? [])->map(function ($it) {
                    return ($it['name'] ?? '-') . ' x' . ($it['qty'] ?? 1);
                })->implode(' | ');

                $phone = $b->customer_phone ?? '-';
                if ($phone !== '-' && !empty($phone)) {
                    $phone = '="' . $phone . '"';
                }

                fputcsv($file, [
                    $b->booking_code,
                    $b->customer_name,
                    $phone,
                    $b->customer_email ?? '-',
                    $b->booking_date ? $b->booking_date->format('Y-m-d') : '-',
                    $b->booking_time,
                    $itemsStr,
                    $b->countSlots(),
                    $b->total_price,
                    $b->refund_percent,
                    $b->net_revenue,
                    $b->status,
                    $b->created_at ? $b->created_at->format('Y-m-d H:i') : '-',
                ], ',');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
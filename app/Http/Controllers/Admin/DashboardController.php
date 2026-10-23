<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /* ===== DATE FILTER ===== */
        $range = $request->get('range', '30days');

        switch ($range) {
            case 'today':
                $start = Carbon::today();
                $end   = Carbon::today()->endOfDay();
                $label = 'Today';
                break;
            case '7days':
                $start = Carbon::today()->subDays(6)->startOfDay();
                $end   = Carbon::today()->endOfDay();
                $label = 'Last 7 Days';
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth()->startOfDay();
                $end   = Carbon::today()->endOfDay();
                $label = 'This Month';
                break;
            case '30days':
            default:
                $range = '30days';
                $start = Carbon::today()->subDays(29)->startOfDay();
                $end   = Carbon::today()->endOfDay();
                $label = 'Last 30 Days';
                break;
        }

        $startDate = $start->format('Y-m-d');
        $endDate   = $end->format('Y-m-d');
        $daysDiff  = $start->diffInDays($end) + 1;

        // Status yg valid buat itungan bisnis
        $validStatuses   = [Booking::STATUS_PAID, Booking::STATUS_COMPLETED, Booking::STATUS_NO_SHOW];
        
        // Status yg ada duitnya (termasuk cancelled yg udah pernah bayar)
        $revenueStatuses = [Booking::STATUS_PAID, Booking::STATUS_COMPLETED, Booking::STATUS_NO_SHOW, Booking::STATUS_CANCELLED];

        /* ===== STATS ===== */
        // Pake created_at buat ngitung aktivitas booking di range ini
        $rangeBookings = Booking::whereBetween('created_at', [$start, $end])
            ->whereIn('status', $validStatuses);

        $stats = [
            'bookings_range'  => (clone $rangeBookings)->count(),
            'bookings_today'  => Booking::whereDate('created_at', Carbon::today())
                                    ->whereIn('status', $validStatuses)
                                    ->count(),

            'cancelled_range' => Booking::whereBetween('created_at', [$start, $end])
                                    ->where('status', Booking::STATUS_CANCELLED)
                                    ->count(),

            'unread_messages' => ContactMessage::where('status', 'unread')->count(),
            'active_subs'     => NewsletterSubscriber::where('is_active', 1)->count(),

            'visitors_total'  => User::where('role', 'customer')->count(),
            'visitors_range'  => User::where('role', 'customer')
                                    ->whereBetween('created_at', [$start, $end])
                                    ->count(),
        ];

        /* ===== FINANCIAL ===== */
        // Revenue pake paid_at (kapan duit masuk)
        $netRange = (int) Booking::whereBetween('paid_at', [$start, $end])
            ->whereIn('status', $revenueStatuses)
            ->sum('net_revenue');
            
        $prevStart = $start->copy()->subDays($daysDiff);
        $prevEnd   = $start->copy()->subDay();

        $netPrev = (int) Booking::whereBetween('paid_at', [
                $prevStart->format('Y-m-d H:i:s'),
                $prevEnd->format('Y-m-d H:i:s'),
            ])
            ->whereIn('status', $revenueStatuses)
            ->sum('net_revenue');

        $revenueGrowth = $netPrev > 0
            ? round((($netRange - $netPrev) / $netPrev) * 100, 1)
            : ($netRange > 0 ? 100 : 0);

        $financial = [
            'net_revenue_range' => $netRange,
            'net_revenue_prev'  => $netPrev,
            'revenue_growth'    => $revenueGrowth,
        ];

        /* ===== CHART 1 — BOOKING TREND ===== */
        $chartLabels  = [];
        $chartBooking = [];

        $chartStart = $start->copy();
        if ($daysDiff > 30) {
            $chartStart = Carbon::today()->subDays(29)->startOfDay();
        }

        $cursor = $chartStart->copy();
        while ($cursor->lte($end)) {
            $d = $cursor->format('Y-m-d');
            $chartLabels[]  = $cursor->format('d M');
            $chartBooking[] = (int) Booking::whereDate('created_at', $d)
                ->whereIn('status', $validStatuses)
                ->count();
            $cursor->addDay();
        }

        /* ===== CHART 2 — REVENUE 6 BULAN ===== */
        $revenueLabels = [];
        $revenueValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $mStart = Carbon::now()->subMonths($i)->startOfMonth();
            $mEnd   = Carbon::now()->subMonths($i)->endOfMonth();

            $revenueLabels[] = $mStart->format('M');

            $revenueValues[] = (int) Booking::whereBetween('paid_at', [$mStart, $mEnd])
                ->whereIn('status', $revenueStatuses)
                ->sum('net_revenue');
        }

        $revenueChart = [
            'labels' => $revenueLabels,
            'values' => $revenueValues,
        ];

        /* ===== CHART 3 — VISITOR GROWTH ===== */
        $visitorLabels = [];
        $visitorValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $mStart = Carbon::now()->subMonths($i)->startOfMonth();
            $mEnd   = Carbon::now()->subMonths($i)->endOfMonth();

            $visitorLabels[] = $mStart->format('M');

            $visitorValues[] = (int) User::where('role', 'customer')
                ->whereBetween('created_at', [$mStart, $mEnd])
                ->count();
        }

        $visitorChart = [
            'labels' => $visitorLabels,
            'values' => $visitorValues,
        ];

        /* ===== CHART 4 — STATUS BREAKDOWN ===== */
        $baseQuery = Booking::whereBetween('created_at', [$start, $end]);
        
        $statusChart = [
            'labels' => ['Paid', 'Completed', 'Cancelled', 'No-Show', 'Pending'],
            'values' => [
                (int) (clone $baseQuery)->where('status', Booking::STATUS_PAID)->count(),
                (int) (clone $baseQuery)->where('status', Booking::STATUS_COMPLETED)->count(),
                (int) (clone $baseQuery)->where('status', Booking::STATUS_CANCELLED)->count(),
                (int) (clone $baseQuery)->where('status', Booking::STATUS_NO_SHOW)->count(),
                (int) (clone $baseQuery)->where('status', Booking::STATUS_PENDING)->count(),
            ],
        ];

        /* ===== WIDGET 1 — TOP TREATMENTS ===== */
        $rangeBookingCollection = (clone $rangeBookings)->get();

        $treatmentRaw = [];
        foreach ($rangeBookingCollection as $b) {
            foreach ($b->items ?? [] as $item) {
                $name = $item['name'] ?? 'Unknown';
                $qty  = (int) ($item['qty'] ?? 1);
                if (!isset($treatmentRaw[$name])) {
                    $treatmentRaw[$name] = ['name' => $name, 'qty' => 0, 'revenue' => 0];
                }
                $treatmentRaw[$name]['qty']     += $qty;
                $treatmentRaw[$name]['revenue'] += (int) ($item['subtotal'] ?? ($item['price'] * $qty));
            }
        }
        $topTreatments = collect($treatmentRaw)->sortByDesc('qty')->take(5)->values();

        /* ===== WIDGET 2 — RECENT BOOKINGS ===== */
        $recentBookings = Booking::whereBetween('created_at', [$start, $end])
            ->whereIn('status', [
                Booking::STATUS_PENDING,
                Booking::STATUS_PAID,
                Booking::STATUS_CANCELLED,
            ])
            ->latest()
            ->take(5)
            ->get();

        /* ===== WIDGET 3 — UNREAD MESSAGES ===== */
        $unreadMessages = ContactMessage::where('status', 'unread')
            ->latest()
            ->take(5)
            ->get();

        /* ===== WIDGET 4 — RECENT SUBSCRIBERS ===== */
        $recentSubscribers = NewsletterSubscriber::where('is_active', 1)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.dashboard', [
            'range'              => $range,
            'rangeLabel'         => $label,
            'startDate'          => $startDate,
            'endDate'            => $endDate,
            'stats'              => $stats,
            'financial'          => $financial,
            'chartLabels'        => $chartLabels,
            'chartBooking'       => $chartBooking,
            'revenueChart'       => $revenueChart,
            'visitorChart'       => $visitorChart,
            'statusChart'        => $statusChart,
            'topTreatments'      => $topTreatments,
            'recentBookings'     => $recentBookings,
            'unreadMessages'     => $unreadMessages,
            'recentSubscribers'  => $recentSubscribers,
            'layout'             => 'side-menu',
        ]);
    }

    public function export(Request $request)
    {
        $range = $request->get('range', '30days');
        switch ($range) {
            case 'today':     $start = Carbon::today(); $end = Carbon::today()->endOfDay(); $label = 'Today'; break;
            case '7days':     $start = Carbon::today()->subDays(6)->startOfDay(); $end = Carbon::today()->endOfDay(); $label = 'Last 7 Days'; break;
            case 'this_month':$start = Carbon::now()->startOfMonth()->startOfDay(); $end = Carbon::today()->endOfDay(); $label = 'This Month'; break;
            default: $range='30days'; $start = Carbon::today()->subDays(29)->startOfDay(); $end = Carbon::today()->endOfDay(); $label = 'Last 30 Days'; break;
        }

        $startDate = $start->format('Y-m-d');
        $endDate   = $end->format('Y-m-d');
        $ok        = [Booking::STATUS_PAID, Booking::STATUS_COMPLETED, Booking::STATUS_NO_SHOW];

        $summary = [
            'Generated'   => now()->format('d M Y H:i'),
            'Range'       => $label,
            'Period'      => "$startDate s/d $endDate",
            'Net Revenue' => (int) Booking::whereBetween('paid_at',[$start,$end])->whereIn('status',$ok)->sum('net_revenue'),
            'Bookings'    => Booking::whereBetween('created_at',[$start,$end])->whereIn('status',$ok)->count(),
            'Cancelled'   => Booking::whereBetween('created_at',[$start,$end])->where('status',Booking::STATUS_CANCELLED)->count(),
            'Unread Msg'  => ContactMessage::where('status','unread')->count(),
            'Subscribers' => NewsletterSubscriber::where('is_active',1)->count(),
        ];

        $financial = [];
        for ($i=5;$i>=0;$i--){
            $mS = Carbon::now()->subMonths($i)->startOfMonth();
            $mE = Carbon::now()->subMonths($i)->endOfMonth();
            $financial[] = [
                $mS->format('M Y'),
                (int) Booking::whereBetween('paid_at',[$mS,$mE])->whereIn('status',$ok)->sum('net_revenue'),
            ];
        }

        $bookings = Booking::whereBetween('created_at',[$start,$end])
            ->orderBy('created_at')->get()
            ->map(fn($b)=>[
                $b->booking_code, $b->customer_name, $b->customer_phone,
                $b->booking_date->format('Y-m-d'), $b->booking_time,
                count($b->items ?? []), $b->status, $b->total_price, $b->net_revenue,
            ])->all();

        $operational = Booking::whereBetween('created_at',[$start,$end])
            ->selectRaw('status, COUNT(*) as c')->groupBy('status')
            ->get()->map(fn($r)=>[$r->status, $r->c])->all();

        $subs = NewsletterSubscriber::whereBetween('created_at',[$start,$end])
            ->orderBy('created_at')->get()
            ->map(fn($s)=>[$s->email, $s->is_active?'Active':'Inactive', $s->created_at->format('d M Y')])->all();

        $msgs = ContactMessage::whereBetween('created_at',[$start,$end])
            ->orderBy('created_at')->get()
            ->map(fn($m)=>[$m->name, $m->email, $m->subject ?? '-', $m->status, $m->created_at->format('d M Y')])->all();

        return response()->json([
            'range'       => $range,
            'label'       => $label,
            'summary'     => $summary,
            'financial'   => $financial,
            'bookings'    => $bookings,
            'operational' => $operational,
            'subs'        => $subs,
            'msgs'        => $msgs,
        ]);
    }
}
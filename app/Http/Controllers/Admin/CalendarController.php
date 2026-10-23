<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Booking;
use App\Models\SpecialDate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Halaman kalender — bulan view.
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        try {
            $cursor = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Exception $e) {
            $cursor = now()->startOfMonth();
        }

        $start = $cursor->copy()->startOfMonth();
        $end   = $cursor->copy()->endOfMonth();

        $gridStart = $start->copy()->startOfWeek(Carbon::SUNDAY);
        $gridEnd   = $end->copy()->endOfWeek(Carbon::SATURDAY);

        $days = [];
        $c = $gridStart->copy();
        while ($c->lte($gridEnd)) {
            $dateStr = $c->format('Y-m-d');
            $isCurrentMonth = $c->month === $cursor->month;

            $bookingsCount = Booking::whereDate('booking_date', $dateStr)
                ->whereIn('status', ['pending', 'paid', 'completed', 'cancelled', 'no-show'])
                ->count();

            $agendaCount = Agenda::whereDate('date', $dateStr)->count();

            $specialDate = SpecialDate::findForDate($dateStr);

            $days[] = [
                'date'         => $dateStr,
                'day'          => (int) $c->format('j'),
                'is_today'     => $c->isToday(),
                'is_current'   => $isCurrentMonth,
                'bookings'     => $bookingsCount,
                'agendas'      => $agendaCount,
                'is_closed'    => $specialDate && $specialDate->type === 'closed',
                'is_custom'    => $specialDate && $specialDate->type === 'custom_hours',
                'special_id'   => $specialDate?->id,
                'special_note' => $specialDate?->notes,
            ];

            $c->addDay();
        }

        $specialDates = SpecialDate::inRange($start->format('Y-m-d'), $end->format('Y-m-d'));

        return view('pages.calendar.index', [
            'cursor'       => $cursor,
            'prevMonth'    => $cursor->copy()->subMonth()->format('Y-m'),
            'nextMonth'    => $cursor->copy()->addMonth()->format('Y-m'),
            'currentMonth' => $cursor->format('Y-m'),
            'days'         => $days,
            'specialDates' => $specialDates,
            'layout'       => 'side-menu',
        ]);
    }

    /**
     * Detail tanggal — buat slide-over.
     * Timeline gabungin: jam operasional + jam booking + jam agenda.
     */
    public function day(Request $request, $date)
    {
        try {
            $carbon = Carbon::parse($date);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date'], 422);
        }

        $dateStr = $carbon->format('Y-m-d');

        // ===== BOOKINGS =====
        $bookings = Booking::whereDate('booking_date', $dateStr)
            ->whereIn('status', ['pending', 'paid', 'completed', 'cancelled', 'no-show'])
            ->orderBy('booking_time')
            ->get()
            ->map(function ($b) {
                return [
                    'id'            => $b->id,
                    'booking_code'  => $b->booking_code,
                    'customer_name' => $b->customer_name,
                    'booking_time'  => substr($b->booking_time, 0, 5),
                    'status'        => $b->status,
                    'total_price'   => $b->total_price,
                    'show_url'      => route('admin.bookings.show', $b->id),
                ];
            });

        // ===== AGENDAS =====
        $agendas = Agenda::whereDate('date', $dateStr)
            ->orderByRaw('time IS NULL DESC')
            ->orderBy('time')
            ->get()
            ->map(function ($a) {
                return [
                    'id'          => $a->id,
                    'time'        => $a->time ? substr($a->time, 0, 5) : null,
                    'title'       => $a->title,
                    'description' => $a->description,
                ];
            });

        // ===== TIMELINE SLOTS =====
        // 1. Jam operasional (weekday/weekend atau custom hours, kosong kalau tutup)
        $hours = Booking::getOperatingHours($dateStr);

        // 2. Gabungin SEMUA jam yang perlu ditampilin:
        //    jam operasional + jam booking + jam agenda (biar agenda di luar jam ops tetep muncul)
        $allTimes = collect($hours)
            ->merge($bookings->pluck('booking_time'))
            ->merge($agendas->pluck('time')->filter())
            ->unique()
            ->sort()
            ->values()
            ->all();

        $slots = [];
        foreach ($allTimes as $time) {
            $slotBookings = $bookings->where('booking_time', $time)->values();
            $slotAgendas  = $agendas->where('time', $time)->values();

            $slots[] = [
                'time'     => $time,
                'bookings' => $slotBookings,
                'agendas'  => $slotAgendas,
            ];
        }

        // Agenda all-day (time == null)
        $allDayAgendas = $agendas->where('time', null)->values();

        $special = SpecialDate::findForDate($dateStr);

        return response()->json([
            'date'            => $dateStr,
            'date_label'      => $carbon->format('l, j F Y'),
            'is_today'        => $carbon->isToday(),
            'slots'           => $slots,
            'all_day_agendas' => $allDayAgendas,
            'is_closed'       => $special && $special->type === 'closed',
            'is_custom'       => $special && $special->type === 'custom_hours',
            'special'         => $special ? [
                'id'         => $special->id,
                'start_date' => $special->start_date->format('Y-m-d'),
                'end_date'   => $special->end_date?->format('Y-m-d'),
                'type'       => $special->type,
                'notes'      => $special->notes,
            ] : null,
        ]);
    }

    /**
     * Simpan special date (tutup / custom hours).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'type'       => 'required|in:closed,custom_hours',
            'open_time'  => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i|after:open_time',
            'notes'      => 'nullable|string|max:500',
        ], [
            'end_date.after_or_equal' => 'End date must be the same as or after the start date.',
            'close_time.after'        => 'Close time must be after open time.',
        ]);

        if ($validated['type'] === 'custom_hours') {
            if (empty($validated['open_time']) || empty($validated['close_time'])) {
                return back()->with('error', 'Open & close time are required for custom hours.');
            }
        }

        SpecialDate::create([
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'] ?? $validated['start_date'],
            'type'       => $validated['type'],
            'open_time'  => $validated['type'] === 'custom_hours' ? $validated['open_time'] : null,
            'close_time' => $validated['type'] === 'custom_hours' ? $validated['close_time'] : null,
            'notes'      => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Date has been set.');
    }

    /**
     * Hapus special date → buka lagi.
     */
    public function destroy($id)
    {
        $special = SpecialDate::findOrFail($id);
        $special->delete();

        return back()->with('success', 'Date has been reopened.');
    }

    /* =====================
       AGENDA CRUD
    ===================== */

    public function storeAgenda(Request $request)
    {
        $validated = $request->validate([
            'date'        => 'required|date',
            'time'        => 'nullable|date_format:H:i',
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
        ], [
            'time.date_format' => 'Time format must be HH:MM (e.g. 14:00).',
        ]);

        $agenda = Agenda::create([
            'date'        => $validated['date'],
            'time'        => $validated['time'] ?? null,
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'created_by'  => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'agenda' => $agenda]);
        }

        return back()->with('success', 'Agenda has been added.');
    }

    public function updateAgenda(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $validated = $request->validate([
            'date'        => 'required|date',
            'time'        => 'nullable|date_format:H:i',
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
        ]);

        $agenda->update([
            'date'        => $validated['date'],
            'time'        => $validated['time'] ?? null,
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'agenda' => $agenda]);
        }

        return back()->with('success', 'Agenda has been updated.');
    }

    public function deleteAgenda($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Agenda has been deleted.');
    }
}
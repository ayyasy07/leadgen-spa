<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $this->autoExpire();

        $filterStatus = $request->get('status', 'all');
        $filterSearch = $request->get('search', '');

        $query = Booking::query();

        if ($filterStatus !== 'all') {
            $query->where('status', $filterStatus);
        }

        if ($filterSearch) {
            $query->where(function ($q) use ($filterSearch) {
                $q->where('booking_code', 'like', "%{$filterSearch}%")
                  ->orWhere('customer_name', 'like', "%{$filterSearch}%")
                  ->orWhere('customer_phone', 'like', "%{$filterSearch}%");
            });
        }

        $bookings = $query->orderByDesc('created_at')->paginate(15);

        $counts = [
            'all'       => Booking::count(),
            'pending'   => Booking::where('status', Booking::STATUS_PENDING)->count(),
            'paid'      => Booking::where('status', Booking::STATUS_PAID)->count(),
            'completed' => Booking::where('status', Booking::STATUS_COMPLETED)->count(),
            'cancelled' => Booking::where('status', Booking::STATUS_CANCELLED)->count(),
            'no-show'   => Booking::where('status', Booking::STATUS_NO_SHOW)->count(),
            'expired'   => Booking::where('status', Booking::STATUS_EXPIRED)->count(),
        ];

        return view('pages.bookings-crud.index', [
            'bookings'     => $bookings,
            'counts'       => $counts,
            'filterStatus' => $filterStatus,
            'filterSearch' => $filterSearch,
            'layout'       => 'side-menu',
        ]);
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);

        return view('pages.bookings-crud.show', [
            'booking'           => $booking,
            'availableStatuses' => $this->getAvailableTransitions($booking),
            'canCancel'         => $this->canAdminCancel($booking),
            'isLocked'          => $this->isLocked($booking),
            'layout'            => 'side-menu',
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($this->isLocked($booking)) {
            return back()->with('error', 'Booking with status ' . strtoupper($booking->status) . ' is locked and cannot be changed.');
        }

        $request->validate([
            'status' => 'required|in:completed,cancelled,no-show',
        ]);

        $available = $this->getAvailableTransitions($booking);
        if (!in_array($request->status, $available)) {
            return back()->with('error', 'Invalid status transition.');
        }

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Status successfully changed to ' . strtoupper($request->status) . '.');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($this->isLocked($booking)) {
            return back()->with('error', 'Booking with status ' . strtoupper($booking->status) . ' is locked.');
        }

        if (!$this->canAdminCancel($booking)) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        return back()->with('success', 'Booking successfully cancelled.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if ($this->isLocked($booking)) {
            return back()->with('error', 'Booking with status ' . strtoupper($booking->status) . ' cannot be deleted.');
        }

        $booking->delete();

        return back()->with('success', 'Booking deleted.');
    }

    /* =====================
       HELPERS
    ===================== */

    private function isLocked(Booking $booking): bool
    {
        return in_array($booking->status, [
            Booking::STATUS_COMPLETED,
            Booking::STATUS_NO_SHOW,
            Booking::STATUS_CANCELLED,
            Booking::STATUS_EXPIRED,
        ]);
    }

    /**
     * Admin bisa cancel: pending (belum bayar) + paid (udah bayar, proses refund).
     */
    private function canAdminCancel(Booking $booking): bool
    {
        return in_array($booking->status, [
            Booking::STATUS_PENDING,
            Booking::STATUS_PAID,
        ]);
    }

    /**
     * Transisi status:
     * - pending → cuma bisa cancel (no-show/completed belom relevan)
     * - paid → completed / no-show (cancel via tombol terpisah)
     */
    private function getAvailableTransitions(Booking $booking): array
    {
        return match ($booking->status) {
            Booking::STATUS_PAID    => [Booking::STATUS_COMPLETED, Booking::STATUS_NO_SHOW],
            Booking::STATUS_PENDING => [],
            default                 => [],
        };
    }

    private function autoExpire(): void
    {
        $minutes = (float) \App\Models\Setting::get('booking_pending_timeout', 60);

        Booking::where('status', Booking::STATUS_PENDING)
            ->where('created_at', '<', now()->subMinutes($minutes))
            ->update(['status' => Booking::STATUS_EXPIRED]);
    }
}
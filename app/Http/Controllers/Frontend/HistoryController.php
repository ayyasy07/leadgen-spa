<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * List booking history for the logged-in user.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your history.');
        }

        $bookings = Booking::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.pages.history', compact('bookings'));
    }

    /**
     * Booking history detail.
     */
    public function show($code)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $booking = Booking::where('booking_code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Admin WhatsApp info
        $adminWhatsapp = Setting::get('social_whatsapp');

        if (empty($adminWhatsapp)) {
            $adminWhatsapp = Setting::get('contact_phone', '');
        }

        if ($adminWhatsapp && preg_match('/wa\.me\/([0-9]+)/', $adminWhatsapp, $m)) {
            $adminWhatsapp = $m[1];
        }

        return view('user.pages.history-detail', compact('booking', 'adminWhatsapp'));
    }

    /**
     * Booking proof page (print-friendly).
     */
    public function proof($code)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $booking = Booking::where('booking_code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Spa info from Setting
        $spaInfo = [
            'name'    => Setting::get('site_name', 'LeadGen Spa'),
            'address' => Setting::get('contact_address', ''),
            'phone'   => Setting::get('contact_phone', ''),
            'email'   => Setting::get('contact_email', ''),
        ];

        return view('user.pages.booking-proof', compact('booking', 'spaInfo'));
    }

    /**
     * Cancel booking from user side.
     */
    public function cancel(Request $request, $code)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $booking = Booking::where('booking_code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $validated = $request->validate([
            'reason_category' => 'required|string|in:changed_mind,sick,urgent,other',
            'reason_detail'   => 'nullable|string|max:500',
        ]);

        // English labels for the stored cancel reason
        $reasonMap = [
            'changed_mind' => 'Changed mind',
            'sick'         => 'Sick',
            'urgent'       => 'Urgent matter',
            'other'        => 'Other',
        ];

        $reasonText = $reasonMap[$validated['reason_category']];

        // User's free-text detail is kept as-is (any language)
        if ($validated['reason_category'] === 'other' && !empty($validated['reason_detail'])) {
            $reasonText .= ': ' . $validated['reason_detail'];
        }

        $booking->status        = Booking::STATUS_CANCELLED;
        $booking->cancel_reason = $reasonText;

        // Booking model's booted() auto-sets cancelled_at + auto-calculates refund_percent
        $booking->save();

        $refundPercent = (int) $booking->refund_percent;

        if ($refundPercent > 0) {
            return redirect()
                ->route('history.show', $booking->booking_code)
                ->with('success', "Booking successfully cancelled. You are entitled to a {$refundPercent}% refund. Please contact the admin within 24 hours to process the refund.");
        }

        return redirect()
            ->route('history.show', $booking->booking_code)
            ->with('success', 'Booking successfully cancelled.');
    }
}
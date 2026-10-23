<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\SpecialDate;
use App\Services\DuitkuService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in first to make a booking.');
        }

        $this->autoExpire();

        $today   = now()->format('Y-m-d');
        $maxDate = Booking::getMaxBookingDate();
        $slots   = $this->buildSlotsWithStatus($today);

        $closedDates = $this->getClosedDates($today, $maxDate);

        return view('user.pages.booking', [
            'slots'       => $slots,
            'today'       => $today,
            'maxDate'     => $maxDate,
            'closedDates' => $closedDates,
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in first to make a booking.');
        }

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:30',
            'booking_date'     => 'required|date|after_or_equal:today',
            'booking_time'     => 'required|string|max:10',
            'notes'            => 'nullable|string|max:150',
            'items'            => 'required|array|min:1',
            'items.*.name'     => 'required|string',
            'items.*.duration' => 'required|string',
            'items.*.unit'     => 'required|string|in:person,couple',
            'items.*.qty'      => 'required|integer|min:1',
            'items.*.price'    => 'required|integer|min:0',
            'payment_method'   => 'required|string|max:20',
            'payment_method_name' => 'nullable|string|max:100',
            'payment_fee'      => 'nullable|integer|min:0',
        ]);

        $maxDate = Booking::getMaxBookingDate();
        if ($validated['booking_date'] > $maxDate) {
            return back()->withInput()
                ->with('error', "Maximum booking date is {$maxDate}.");
        }

        if (SpecialDate::isClosed($validated['booking_date'])) {
            return back()->withInput()
                ->with('error', 'The selected date is closed. Please choose another date.');
        }

        if (Booking::isTimeLocked($validated['booking_date'], $validated['booking_time'])) {
            return back()->withInput()
                ->with('error', 'The selected slot has passed or is unavailable.');
        }

        if (Booking::isSlotDisabled($validated['booking_time'])) {
            return back()->withInput()
                ->with('error', 'The selected time slot is unavailable.');
        }

        if (Booking::hasUserConflict(
            Auth::id(),
            $validated['phone'],
            $validated['booking_date'],
            $validated['booking_time']
        )) {
            return back()->withInput()
                ->with('error', 'You already have a booking at this time. Please choose another time or check your booking history.');
        }

        $remaining = Booking::getRemainingSlots(
            $validated['booking_date'],
            $validated['booking_time']
        );

        $items = [];
        $totalPrice = 0;
        $totalSlots = 0;

        foreach ($validated['items'] as $item) {
            $unit = $item['unit'] ?? 'person';
            $qty  = ($unit === 'couple') ? 1 : (int) $item['qty'];

            $itemPrice = (int) $item['price'];
            $subtotal  = $itemPrice * $qty;

            $items[] = [
                'name'     => $item['name'],
                'duration' => $item['duration'],
                'unit'     => $unit,
                'qty'      => $qty,
                'price'    => $itemPrice,
                'subtotal' => $subtotal,
            ];

            $totalPrice += $subtotal;
            $totalSlots += ($unit === 'couple') ? ($qty * 2) : $qty;
        }

        if ($totalSlots > $remaining) {
            return back()->withInput()
                ->with('error', "Not enough slots. Remaining {$remaining} slot(s), you need {$totalSlots} slot(s).");
        }

        // ============================================
        // 1. CREATE BOOKING (status pending)
        // ============================================
        $booking = Booking::create([
            'booking_code'   => Booking::generateCode(),
            'user_id'        => Auth::id(),
            'customer_name'  => $validated['name'],
            'customer_phone' => $validated['phone'],
            'customer_email' => Auth::user()->email,
            'booking_date'   => $validated['booking_date'],
            'booking_time'   => $validated['booking_time'],
            'items'          => $items,
            'total_price'    => $totalPrice,
            'notes'          => $validated['notes'] ?? null,
            'status'         => Booking::STATUS_PENDING,
        ]);

        // ============================================
        // 2. CREATE DUITKU TRANSACTION
        // ============================================
        $paymentMethod     = $validated['payment_method'];
        $paymentMethodName = $validated['payment_method_name'] ?? $paymentMethod;
        $paymentFee        = (int) ($validated['payment_fee'] ?? 0);
        $paymentAmount     = $totalPrice + $paymentFee;

        // ========================================================
        // FIX DUITKU: QTY DIPAKE 1, HARGA DIPAKE SUBTOTAL
        // ========================================================
        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'name'     => $item['name'] . ' (' . $item['qty'] . 'x)',
                'price'    => (int) $item['subtotal'], 
                'quantity' => 1,
            ];
        }
        
        if ($paymentFee > 0) {
            $itemDetails[] = [
                'name'     => 'Biaya Layanan',
                'price'    => $paymentFee,
                'quantity' => 1,
            ];
        }

        $expiryMinutes = (int) Setting::get('booking_pending_timeout', 15);
        $returnUrl     = url('booking/success/' . $booking->booking_code);

        try {
            $duitku = new DuitkuService();

            $result = $duitku->createTransaction([
                'merchantOrderId' => $booking->booking_code,
                'paymentAmount'   => $paymentAmount,
                'paymentMethod'   => $paymentMethod,
                'productDetails'  => 'Booking ' . $booking->booking_code,
                'email'           => $booking->customer_email ?? '',
                'phoneNumber'     => $booking->customer_phone ?? '',
                'customerVaName'  => $booking->customer_name ?? 'Customer',
                'firstName'       => $booking->customer_name ?? 'Customer',
                'itemDetails'     => $itemDetails,
                'callbackUrl'     => config('duitku.callback_url'),
                'returnUrl'       => $returnUrl,
                'expiryPeriod'    => $expiryMinutes,
            ]);
        } catch (\Throwable $e) {
            Log::error('[Booking] Duitku exception', ['message' => $e->getMessage()]);

            $booking->forceDelete();

            return back()->withInput()
                ->with('error', 'Failed to reach the payment gateway. Please try again.');
        }

        if (!$result['success']) {
            $booking->forceDelete();

            return back()->withInput()
                ->with('error', $result['message'] ?? 'Failed to create the payment transaction.');
        }

        $data = $result['data'];

        // ============================================
        // 3. SAVE PAYMENT DATA
        // ============================================
        $booking->update([
            'payment_reference'   => $data['reference'] ?? null,
            'payment_method'      => $paymentMethod,
            'payment_method_name' => $paymentMethodName,
            'payment_url'         => $data['paymentUrl'] ?? null,
            'payment_fee'         => $paymentFee,
            'payment_amount'      => $paymentAmount,
            'payment_status'      => Booking::PAYMENT_PENDING,
            'expired_at'          => now()->addMinutes($expiryMinutes),
        ]);

        // ============================================
        // 4. REDIRECT TO DUITKU
        // ============================================
        if (!empty($data['paymentUrl'])) {
            return redirect()->away($data['paymentUrl']);
        }

        return redirect()
            ->route('booking.success', $booking->booking_code)
            ->with('error', 'Payment URL is not available. Please try paying from the history page.');
    }

    public function success($code)
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();

        if ($booking->user_id && $booking->user_id != Auth::id()) {
            abort(403, 'Access denied.');
        }

        return view('user.pages.booking-success', [
            'booking' => $booking,
        ]);
    }

    public function slots(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        return response()->json([
            'date'  => $date,
            'slots' => $this->buildSlotsWithStatus($date),
        ]);
    }

    /* =====================
       HELPERS
    ===================== */

    private function buildSlotsWithStatus(string $date): array
    {
        $hours = Booking::getOperatingHours($date);
        $result = [];

        if (empty($hours)) {
            return [];
        }

        foreach ($hours as $time) {
            $capacity  = Booking::getSlotCapacity($time);
            $remaining = Booking::getRemainingSlots($date, $time);

            $locked = Booking::isTimeLocked($date, $time);

            if ($locked) {
                $status = 'locked';
                $label  = 'Not available';
            } elseif ($capacity === 0 || $remaining === 0) {
                $status = 'full';
                $label  = 'Full';
            } elseif ($remaining <= 4) {
                $status = 'limited';
                $label  = "{$remaining} slots left";
            } else {
                $status = 'available';
                $label  = 'Available';
            }

            $result[] = [
                'time'      => $time,
                'capacity'  => $capacity,
                'remaining' => $remaining,
                'status'    => $status,
                'label'     => $label,
                'locked'    => $locked,
            ];
        }

        return $result;
    }

    private function autoExpire(): void
    {
        $minutes = (float) Setting::get('booking_pending_timeout', 15);

        Booking::where('status', Booking::STATUS_PENDING)
            ->where('created_at', '<', now()->subMinutes($minutes))
            ->update(['status' => Booking::STATUS_EXPIRED]);
    }

    private function getClosedDates(string $today, string $maxDate): array
    {
        $closed = [];
        $start  = Carbon::parse($today);
        $end    = Carbon::parse($maxDate);

        while ($start->lte($end)) {
            $dateStr = $start->format('Y-m-d');
            $hours   = Booking::getOperatingHours($dateStr);

            if (empty($hours)) {
                $closed[] = $dateStr;
            }

            $start->addDay();
        }

        return $closed;
    }
}
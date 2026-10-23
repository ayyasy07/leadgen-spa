<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use App\Services\DuitkuService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected DuitkuService $duitku;

    public function __construct(DuitkuService $duitku)
    {
        $this->duitku = $duitku;
    }

    public function methods(Request $request)
    {
        $amount = (int) $request->get('amount', 0);

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid amount.',
                'methods' => [],
            ]);
        }

        $methods = $this->duitku->getPaymentMethods($amount);

        $normalized = array_map(function ($m) {
            return [
                'code' => $m['paymentMethod'] ?? ($m['code'] ?? ''),
                'name' => $m['paymentName']  ?? ($m['name'] ?? ''),
                'fee'  => (int) ($m['totalFee'] ?? ($m['fee'] ?? 0)),
                'image' => $m['paymentImage'] ?? ($m['image'] ?? ''),
            ];
        }, $methods);

        return response()->json([
            'success' => true,
            'methods' => $normalized,
        ]);
    }

    public function create(Request $request, $code)
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();

        if ($booking->user_id && $booking->user_id != Auth::id()) {
            abort(403, 'Access denied.');
        }

        if (!$booking->canBePaid()) {
            return redirect()
                ->route('booking.success', $booking->booking_code)
                ->with('error', 'This booking can no longer be paid.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|max:20',
        ]);

        $paymentMethod = $validated['payment_method'];
        $fee = (int) $request->input('payment_fee', 0);
        $paymentAmount = (int) $booking->total_price + $fee;

        // ========================================================
        // FIX DUITKU: QTY DIPAKE 1, HARGA DIPAKE SUBTOTAL
        // ========================================================
        $itemDetails = [];
        foreach (($booking->items ?? []) as $item) {
            $itemDetails[] = [
                'name'     => ($item['name'] ?? 'Treatment') . ' (' . ($item['qty'] ?? 1) . 'x)',
                'price'    => (int) ($item['subtotal'] ?? (($item['price'] ?? 0) * ($item['qty'] ?? 1))),
                'quantity' => 1,
            ];
        }
        
        if ($fee > 0) {
            $itemDetails[] = [
                'name'     => 'Biaya Layanan (' . $request->input('payment_method_name', $paymentMethod) . ')',
                'price'    => $fee,
                'quantity' => 1,
            ];
        }

        $result = $this->duitku->createTransaction([
            'merchantOrderId' => $booking->booking_code,
            'paymentAmount'   => $paymentAmount,
            'paymentMethod'   => $paymentMethod,
            'productDetails'  => 'Booking ' . $booking->booking_code,
            'email'           => $booking->customer_email ?? (Auth::user()->email ?? ''),
            'phoneNumber'     => $booking->customer_phone ?? '',
            'customerVaName'  => $booking->customer_name ?? 'Customer',
            'firstName'       => $booking->customer_name ?? 'Customer',
            'itemDetails'     => $itemDetails,
            'callbackUrl'     => config('duitku.callback_url'),
            'returnUrl'       => route('booking.success', $booking->booking_code),
            'expiryPeriod'    => (int) Setting::get('booking_expiry_period', 60),
        ]);

        if (!$result['success']) {
            return back()->with('error', $result['message'] ?? 'Failed to create transaction.');
        }

        $data = $result['data'];

        $booking->update([
            'payment_reference'   => $data['reference'] ?? null,
            'payment_method'      => $paymentMethod,
            'payment_method_name' => $request->input('payment_method_name', $paymentMethod),
            'payment_url'         => $data['paymentUrl'] ?? null,
            'payment_fee'         => $fee,
            'payment_amount'      => $paymentAmount,
            'payment_status'      => Booking::PAYMENT_PENDING,
            'expired_at'          => now()->addMinutes((int) Setting::get('booking_expiry_period', 60)),
        ]);

        if (!empty($data['paymentUrl'])) {
            return redirect()->away($data['paymentUrl']);
        }

        return back()->with('error', 'Payment URL is not available. Please try again.');
    }

    public function callback(Request $request)
    {
        Log::info('[Duitku Callback] Received', $request->all());

        $merchantCode    = (string) $request->input('merchantCode', '');
        $amount          = (string) $request->input('amount', '');
        $merchantOrderId = (string) $request->input('merchantOrderId', '');
        $signature       = (string) $request->input('signature', '');
        $resultCode      = (string) $request->input('resultCode', '');
        $reference       = (string) $request->input('reference', '');

        $valid = $this->duitku->verifyCallbackSignature(
            $merchantCode, $amount, $merchantOrderId, $signature
        );

        if (!$valid) {
            Log::warning('[Duitku Callback] Invalid signature', $request->all());
            return response('BAD SIGNATURE', 400);
        }

        $booking = Booking::where('booking_code', $merchantOrderId)->first();
        if (!$booking) {
            Log::warning('[Duitku Callback] Booking not found', ['order' => $merchantOrderId]);
            return response('NOT FOUND', 404);
        }

        if ($booking->status === Booking::STATUS_PAID) {
            return response('SUCCESS', 200);
        }

        if ($resultCode === '00') {
            $booking->update([
                'status'         => Booking::STATUS_PAID,
                'payment_status' => Booking::PAYMENT_SUCCESS,
                'payment_reference' => $reference ?: $booking->payment_reference,
                'paid_at'        => now(),
            ]);
            Log::info('[Duitku Callback] Booking PAID', ['code' => $booking->booking_code]);
        } elseif ($resultCode === '02') {
            $booking->update([
                'status'         => Booking::STATUS_EXPIRED,
                'payment_status' => Booking::PAYMENT_EXPIRED,
            ]);
            Log::info('[Duitku Callback] Booking EXPIRED', ['code' => $booking->booking_code]);
        } else {
            $booking->update([
                'payment_status' => Booking::PAYMENT_FAILED,
            ]);
            Log::info('[Duitku Callback] Payment FAILED', ['code' => $booking->booking_code]);
        }

        return response('SUCCESS', 200);
    }

    public function status($code)
    {
        $booking = Booking::where('booking_code', $code)->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found.']);
        }

        return response()->json([
            'success' => true,
            'status'  => $booking->status,
            'label'   => $booking->status_label,
            'paid_at' => $booking->paid_at?->format('d M Y H:i'),
        ]);
    }
}
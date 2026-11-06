@extends('user.layouts.main')

@section('title', $booking->booking_code . ' — Booking History')
@section('footer_type', 'grid')

@push('styles')
<style>
    .page-title {
        padding: 46px 0 8px;
        max-width: 900px;
        margin: 0 auto;
        padding-left: 24px;
        padding-right: 24px;
    }

    .back-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(0, 0, 0, .08);
        margin-bottom: 22px;
        font-size: 14px;
        color: var(--ink);
        transition: .25s;
        text-decoration: none;
    }

    .back-btn:hover {
        background: var(--primary);
        color: #fff;
    }

    .page-title h1 {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .detail-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px 24px 90px;
    }

    .card {
        background: #fff;
        border-radius: 16px;
        padding: 32px 36px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, .04);
        border: 1px solid var(--line);
        margin-bottom: 20px;
    }

    .card h2 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--ink);
    }

    .code-big {
        font-family: monospace;
        font-size: 22px;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed var(--line);
        font-size: 14px;
    }

    .meta-row:last-child {
        border-bottom: none;
    }

    .meta-row .label {
        color: var(--gray);
        font-weight: 600;
    }

    .meta-row .value {
        color: var(--ink);
        font-weight: 600;
        text-align: right;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .status-badge i { font-size: 11px; }
    .status-badge.pending { background: rgba(245,158,11,.15); color: #d97706; }
    .status-badge.paid { background: rgba(34,197,94,.15); color: #16a34a; }
    .status-badge.completed { background: rgba(34,197,94,.15); color: #16a34a; }
    .status-badge.cancelled { background: rgba(239,68,68,.15); color: #dc2626; }
    .status-badge.no-show { background: rgba(100,116,139,.15); color: #64748b; }
    .status-badge.expired { background: rgba(100,116,139,.15); color: #64748b; }

    .items-list {
        margin-top: 10px;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px dashed var(--line);
        font-size: 14px;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-row .iname {
        color: var(--ink);
        font-weight: 700;
        display: block;
        margin-bottom: 4px;
    }

    .item-row .imeta {
        color: var(--gray);
        font-size: 12.5px;
    }

    .item-row .iprice {
        color: var(--primary);
        font-weight: 700;
    }

    .fee-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 13.5px;
        color: #d97706;
        font-weight: 700;
        border-top: 1px dashed var(--line);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid var(--line);
        font-size: 16px;
        font-weight: 700;
    }

    .total-row .amount {
        color: var(--primary);
        font-size: 20px;
    }

    .notes-box {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 14px 18px;
        border-radius: 8px;
        font-size: 13.5px;
        color: #92400e;
        line-height: 1.6;
    }

    /* ===== ALERT ===== */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.6;
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .alert.success {
        background: rgba(34, 197, 94, .1);
        border-left: 4px solid #16a34a;
        color: #15803d;
    }

    .alert.error {
        background: rgba(239, 68, 68, .1);
        border-left: 4px solid #dc2626;
        color: #b91c1c;
    }

    /* ===== PAYMENT INFO BOX ===== */
    .payment-info-box {
        background: linear-gradient(135deg, #fff8e6 0%, #fffbf0 100%);
        border: 1.5px solid rgba(245, 158, 11, .25);
        border-radius: 14px;
        padding: 18px 22px;
        margin-top: 10px;
    }
    .payment-info-box .pi-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }
    .payment-info-box .pi-head i {
        color: #d97706;
        font-size: 16px;
    }
    .payment-info-box .pi-head span {
        font-size: 14px;
        font-weight: 800;
        color: #7a4a08;
    }
    .payment-info-box .pi-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 13px;
        border-bottom: 1px dashed rgba(217, 119, 6, .2);
    }
    .payment-info-box .pi-row:last-child {
        border-bottom: none;
    }
    .payment-info-box .pi-row .pi-label {
        color: #8a5a06;
        font-weight: 600;
    }
    .payment-info-box .pi-row .pi-value {
        color: #7a4a08;
        font-weight: 800;
        font-family: monospace;
        text-align: right;
        word-break: break-all;
    }

    /* ===== PAY NOW BUTTON ===== */
    .btn-pay-now {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 22px;
        border-radius: 30px;
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: #fff;
        border: none;
        font-size: 13.5px;
        font-weight: 700;
        letter-spacing: .4px;
        cursor: pointer;
        transition: all .25s;
        text-decoration: none;
        box-shadow: 0 8px 20px -6px rgba(217, 119, 6, .5);
        margin-top: 14px;
    }
    .btn-pay-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 26px -6px rgba(217, 119, 6, .6);
    }

    /* ===== ACTION SECTIONS ===== */
    .action-section {
        border-top: 2px solid var(--line);
        padding-top: 24px;
        margin-top: 8px;
    }

    .action-section:first-child {
        border-top: none;
        padding-top: 0;
        margin-top: 0;
    }

    .action-section h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 8px;
    }

    .action-section p {
        font-size: 13px;
        color: var(--gray);
        margin-bottom: 16px;
        line-height: 1.6;
    }

    .action-section p strong {
        color: var(--ink);
        font-weight: 700;
    }

    /* ===== BUTTONS ===== */
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: #dc2626;
        border: 2px solid #dc2626;
        padding: 11px 22px;
        border-radius: 30px;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all .25s;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: #dc2626;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(220, 38, 38, .25);
    }

    .btn-wa {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #25d366;
        color: #fff;
        padding: 11px 22px;
        border-radius: 30px;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all .25s;
    }

    .btn-wa:hover {
        background: #1da851;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(37, 211, 102, .3);
    }

    .btn-wa-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: #25d366;
        border: 2px solid #25d366;
        padding: 11px 22px;
        border-radius: 30px;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all .25s;
    }

    .btn-wa-outline:hover {
        background: #25d366;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(37, 211, 102, .3);
    }

    /* ===== MODAL ===== */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(2px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 20px;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 32px;
        position: relative;
        animation: modalIn .25s ease;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(20px) scale(.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-box h3 {
        font-size: 20px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 8px;
    }

    .modal-box .subtitle {
        font-size: 13px;
        color: var(--gray);
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .consequence-box {
        background: rgba(245, 158, 11, .08);
        border-left: 4px solid #f59e0b;
        padding: 14px 18px;
        border-radius: 10px;
        font-size: 13px;
        color: #92400e;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .consequence-box.danger {
        background: rgba(239, 68, 68, .08);
        border-left-color: #dc2626;
        color: #b91c1c;
    }

    .consequence-box.success {
        background: rgba(34, 197, 94, .08);
        border-left-color: #16a34a;
        color: #15803d;
    }

    .consequence-box strong {
        font-weight: 800;
    }

    .wa-hint {
        text-align: center;
        padding: 12px;
        font-size: 12.5px;
        color: var(--gray);
        margin-top: 14px;
    }

    .wa-hint a {
        color: #25d366;
        font-weight: 700;
        text-decoration: none;
    }

    .wa-hint a:hover {
        text-decoration: underline;
    }

    .modal-field {
        margin-bottom: 16px;
    }

    .modal-field label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 8px;
    }

    .modal-field select,
    .modal-field textarea {
        width: 100%;
        border: 1.5px solid var(--line);
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 13.5px;
        font-family: inherit;
        background: #fff;
        color: var(--ink);
        transition: border-color .2s;
    }

    .modal-field select:focus,
    .modal-field textarea:focus {
        outline: none;
        border-color: var(--primary);
    }

    .modal-field textarea {
        resize: vertical;
        min-height: 80px;
    }

    .checkbox-field {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        color: var(--ink);
        margin-bottom: 20px;
        cursor: pointer;
        line-height: 1.5;
    }

    .checkbox-field input {
        margin-top: 3px;
        accent-color: var(--primary);
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn-secondary {
        padding: 11px 22px;
        border-radius: 30px;
        background: #f1f5f9;
        color: var(--ink);
        border: none;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .2s;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-confirm-cancel {
        padding: 11px 22px;
        border-radius: 30px;
        background: #dc2626;
        color: #fff;
        border: none;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-confirm-cancel:hover:not(:disabled) {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(220, 38, 38, .3);
    }

    .btn-confirm-cancel:disabled {
        opacity: .45;
        cursor: not-allowed;
    }

    @media (max-width: 600px) {
        .card { padding: 24px 20px; }
        .meta-row { flex-direction: column; gap: 4px; }
        .meta-row .value { text-align: left; }
        .modal-box { padding: 24px; }
    }
</style>
@endpush

@section('content')

    <div class="page-title">
        <a href="{{ route('history.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Booking Details</h1>
    </div>

    <div class="detail-wrap">

        {{-- ALERT --}}
        @if(session('success'))
            <div class="alert success">
                <i class="fas fa-check-circle" style="font-size:16px;margin-top:1px"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert error">
                <i class="fas fa-exclamation-circle" style="font-size:16px;margin-top:1px"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @php
            $statusIcon = [
                'pending'   => 'fa-clock',
                'paid'      => 'fa-check-circle',
                'completed' => 'fa-check-double',
                'cancelled' => 'fa-times-circle',
                'no-show'   => 'fa-user-slash',
                'expired'   => 'fa-hourglass-end',
            ][$booking->status] ?? 'fa-clock';

            // Cek apakah waktu treatment udah lewat / hari-H
            $treatmentTime = \Carbon\Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time);
            $isPast = $treatmentTime->isPast();
        @endphp

        {{-- BOOKING CODE --}}
        <div class="card">
            <h2>Booking Code</h2>
            <div class="code-big">{{ $booking->booking_code }}</div>
            <span class="status-badge {{ $booking->status }}">
                <i class="fas {{ $statusIcon }}"></i>
                {{ $booking->status_label }}
            </span>
        </div>

        {{-- PAY NOW (only if pending + can pay) --}}
        @if($booking->canBePaid())
            <div class="card" style="border: 1.5px solid rgba(217, 119, 6, .3); background: linear-gradient(135deg, #fffbf0 0%, #fff 100%);">
                <h2 style="color: #7a4a08;">
                    <i class="fas fa-clock" style="color: #d97706; margin-right: 8px;"></i>
                    Awaiting Payment
                </h2>
                <p style="font-size:13.5px;color:#8a5a06;line-height:1.6;margin-bottom:4px;">
                    Please complete your payment before
                    <strong>{{ $booking->expired_at ? $booking->expired_at->format('d M Y, H:i') : 'the time runs out' }}</strong>
                    so your booking does not expire.
                </p>
                <form action="{{ route('payment.create', $booking->booking_code) }}" method="POST" style="margin:0;">
                    @csrf
                    <input type="hidden" name="payment_method" value="{{ $booking->payment_method }}">
                    <input type="hidden" name="payment_method_name" value="{{ $booking->payment_method_name }}">
                    <input type="hidden" name="payment_fee" value="{{ $booking->payment_fee }}">
                    <button type="submit" class="btn-pay-now">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </button>
                </form>
            </div>
        @endif

        {{-- BOOKING INFO --}}
        <div class="card">
            <h2>Booking Information</h2>

            <div class="meta-row">
                <span class="label">Name</span>
                <span class="value">{{ $booking->customer_name }}</span>
            </div>
            <div class="meta-row">
                <span class="label">WhatsApp Number</span>
                <span class="value">{{ $booking->customer_phone }}</span>
            </div>
            <div class="meta-row">
                <span class="label">Booking Date</span>
                <span class="value">{{ $booking->booking_date->format('l, d F Y') }}</span>
            </div>
            <div class="meta-row">
                <span class="label">Time</span>
                <span class="value">{{ $booking->booking_time }}</span>
            </div>
            <div class="meta-row">
                <span class="label">Booked At</span>
                <span class="value">{{ $booking->created_at->format('d M Y, H:i') }}</span>
            </div>
            @if($booking->paid_at)
                <div class="meta-row">
                    <span class="label">Paid At</span>
                    <span class="value" style="color:#16a34a;font-weight:700;">
                        {{ $booking->paid_at->format('d M Y, H:i') }}
                    </span>
                </div>
            @endif

            @if($booking->status === 'cancelled')
                <div class="meta-row">
                    <span class="label">Cancelled At</span>
                    <span class="value">{{ $booking->cancelled_at ? $booking->cancelled_at->format('d M Y, H:i') : '-' }}</span>
                </div>
                @if($booking->cancel_reason)
                    <div class="meta-row">
                        <span class="label">Cancel Reason</span>
                        <span class="value">{{ $booking->cancel_reason }}</span>
                    </div>
                @endif
                @if($booking->refund_percent > 0)
                    <div class="meta-row">
                        <span class="label">Refund</span>
                        <span class="value" style="color:#16a34a">{{ $booking->refund_percent }}%</span>
                    </div>
                @endif
            @endif
        </div>

        {{-- PAYMENT INFO --}}
        @if($booking->payment_method)
            <div class="card">
                <h2>Payment Information</h2>
                <div class="payment-info-box">
                    <div class="pi-head">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ $booking->payment_method_name ?: $booking->payment_method }}</span>
                    </div>
                    @if($booking->payment_reference)
                        <div class="pi-row">
                            <span class="pi-label">Reference</span>
                            <span class="pi-value">{{ $booking->payment_reference }}</span>
                        </div>
                    @endif
                    <div class="pi-row">
                        <span class="pi-label">Subtotal</span>
                        <span class="pi-value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($booking->payment_fee > 0)
                        <div class="pi-row">
                            <span class="pi-label">Service Fee</span>
                            <span class="pi-value">Rp {{ number_format($booking->payment_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="pi-row">
                        <span class="pi-label"><strong>Total Amount</strong></span>
                        <span class="pi-value" style="font-size:15px;color:#7a4a08;">
                            Rp {{ number_format($booking->payment_amount ?: $booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                    @if($booking->payment_status)
                        <div class="pi-row">
                            <span class="pi-label">Status</span>
                            <span class="pi-value" style="text-transform:uppercase;">{{ $booking->payment_status }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ITEMS --}}
        <div class="card">
            <h2>Booking Items</h2>
            <div class="items-list">
                @foreach($booking->items as $item)
                    <div class="item-row">
                        <div>
                            <span class="iname">{{ $item['name'] }}</span>
                            <span class="imeta">
                                {{ $item['duration'] }} · {{ $item['qty'] }} {{ $item['unit'] === 'couple' ? 'package' : 'person' }}
                            </span>
                        </div>
                        <span class="iprice">
                            Rp {{ number_format($item['subtotal'] ?? ($item['price'] * $item['qty']), 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            @if($booking->payment_fee > 0)
                <div class="fee-row">
                    <span>Service Fee</span>
                    <span>Rp {{ number_format($booking->payment_fee, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="total-row">
                <span>Total</span>
                <span class="amount">Rp {{ number_format($booking->payment_amount ?: $booking->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- NOTES --}}
        @if($booking->notes)
            <div class="card">
                <h2>Notes</h2>
                <div class="notes-box">{{ $booking->notes }}</div>
            </div>
        @endif

        {{-- ACTION SECTIONS --}}
        @php
            $waNumber = '';
            if ($adminWhatsapp) {
                $waNumber = preg_replace('/[^0-9]/', '', $adminWhatsapp);
                if (substr($waNumber, 0, 1) === '0') $waNumber = '62' . substr($waNumber, 1);
                if (substr($waNumber, 0, 1) === '8') $waNumber = '62' . $waNumber;
            }
        @endphp

        {{-- SECTION: DOWNLOAD BOOKING PROOF --}}
        <div class="card">
            <div class="action-section">
                <h3>Booking Confirmation</h3>
                <p>Download or print your booking confirmation for your personal records.</p>
                <a href="{{ route('history.proof', $booking->booking_code) }}"
                   target="_blank" rel="noopener" class="btn-wa-outline" style="background:#fff;color:#3b82f6;border-color:#3b82f6">
                    <i class="fas fa-file-download"></i>
                    Download Booking Proof
                </a>
            </div>
        </div>

        {{-- SECTION: NEED HELP? --}}
        @if($booking->canBeCancelled() && $waNumber)
            @php
                $waMsgHelp = "Hello Admin, I need help with my booking *{$booking->booking_code}*.\n\nName: {$booking->customer_name}\nDate: " . $booking->booking_date->format('d M Y') . " {$booking->booking_time}";
            @endphp
            <div class="card">
                <div class="action-section">
                    <h3>Need Help?</h3>
                    <p>Experiencing issues with your booking? Contact the admin via WhatsApp for quick assistance.</p>
                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMsgHelp) }}"
                       target="_blank" rel="noopener" class="btn-wa-outline">
                        <i class="fab fa-whatsapp"></i>
                        Chat Admin via WhatsApp
                    </a>
                </div>
            </div>
        @endif

        {{-- SECTION: CANCEL BOOKING --}}
        @if($booking->canBeCancelled() && !$isPast)
            <div class="card">
                <div class="action-section">
                    <h3>Cancel Booking</h3>
                    @if($booking->status === 'pending')
                        <p>This booking has not been paid yet. If you cancel it, the slot will be released and there is no refund (since there was no payment).</p>
                    @else
                        <p>You can cancel this booking. Cancellation consequences depend on the treatment time and payment status.</p>
                    @endif
                    <button type="button" class="btn-cancel" id="openCancelModal">
                        <i class="fas fa-times-circle"></i>
                        Cancel Booking
                    </button>
                </div>
            </div>
        @endif

        {{-- SECTION: CLAIM REFUND (if cancelled + refund > 0) --}}
        @if($booking->status === 'cancelled' && $booking->refund_percent > 0)
            <div class="card" style="border:1.5px solid rgba(37,211,102,.3);background:linear-gradient(135deg,#f0fff6 0%,#fff 100%);">
                <div class="action-section">
                    <h3>Claim {{ $booking->refund_percent }}% Refund</h3>
                    <p>You are entitled to a refund of <strong>{{ $booking->refund_percent }}%</strong> of the total payment. Please confirm with the admin via WhatsApp to process the refund within <strong>24 hours</strong>.</p>

                    @if($waNumber)
                        @php
                            $waMsgRefund = "Hello Admin, I would like to claim a refund for booking *{$booking->booking_code}*.\n\nName: {$booking->customer_name}\nTotal: Rp " . number_format($booking->total_price, 0, ',', '.') . "\nRefund: {$booking->refund_percent}%";
                        @endphp
                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMsgRefund) }}"
                           target="_blank" rel="noopener" class="btn-wa">
                            <i class="fab fa-whatsapp"></i>
                            Confirm Refund via WhatsApp
                        </a>
                    @else
                        <p style="font-size:12.5px;color:#dc2626;margin-top:8px">
                            <i class="fas fa-exclamation-triangle"></i>
                            Admin WhatsApp number is not set. Please contact the admin through another channel.
                        </p>
                    @endif
                </div>
            </div>
        @endif

    </div>

    {{-- MODAL CANCEL --}}
    @if($booking->canBeCancelled() && !$isPast)
        @php
            $isPaid = $booking->status === 'paid';
            $hoursBefore = null;
            $refundInfo = null;

            if ($booking->booking_date && $booking->booking_time) {
                $treatmentAt = \Carbon\Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time);
                $hoursBefore = now()->diffInHours($treatmentAt, false);

                if ($isPaid) {
                    $refundInfo = ($hoursBefore >= 48) ? 'success' : 'danger';
                }
            }
        @endphp

        <div class="modal-overlay" id="cancelModal">
            <div class="modal-box">
                <h3>Cancel Booking?</h3>
                <div class="subtitle">Please read the following before confirming.</div>

                {{-- CONSEQUENCES --}}
                @if($booking->status === 'pending')
                    <div class="consequence-box">
                        This booking is <strong>not paid yet</strong>. After cancelling, the slot will be available for others. There is no refund because no payment has been made.
                    </div>
                @elseif($isPaid && $refundInfo === 'success')
                    <div class="consequence-box success">
                        Since the cancellation is made <strong>≥48 hours before treatment</strong>, you are entitled to a <strong>50% refund</strong>. The refund will be processed manually by the admin. After cancelling, you will be directed to confirm via WhatsApp.
                    </div>
                @elseif($isPaid && $refundInfo === 'danger')
                    <div class="consequence-box danger">
                        Since the cancellation is made <strong>less than 48 hours before treatment</strong>, <strong>the payment cannot be refunded</strong>. The slot will be available for others.
                    </div>
                @endif

                <form method="POST" action="{{ route('history.cancel', $booking->booking_code) }}" id="cancelForm">
                    @csrf

                    <div class="modal-field">
                        <label>Reason for Cancellation <span style="color:#dc2626">*</span></label>
                        <select name="reason_category" id="reasonCategory" required>
                            <option value="">-- Select reason --</option>
                            <option value="changed_mind">Changed Mind</option>
                            <option value="sick">Sick</option>
                            <option value="urgent">Urgent Matter</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="modal-field" id="detailField" style="display:none">
                        <label>Detail Reason</label>
                        <textarea name="reason_detail" placeholder="Write your reason in detail..."></textarea>
                    </div>

                    <label class="checkbox-field">
                        <input type="checkbox" id="confirmCheck" required>
                        <span>I confirm I want to cancel this booking and fully understand the consequences.</span>
                    </label>

                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" id="closeCancelModal">Back</button>
                        <button type="submit" class="btn-confirm-cancel" id="submitCancel" disabled>
                            Yes, Cancel
                        </button>
                    </div>
                </form>

                {{-- WA HINT --}}
                @if($waNumber)
                    @php
                        $waMsgHint = "Hello Admin, I need help with booking *{$booking->booking_code}* for the cancellation process.";
                    @endphp
                    <div class="wa-hint">
                        Need help? <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMsgHint) }}" target="_blank" rel="noopener">Chat Admin via WhatsApp</a>
                    </div>
                @endif
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal     = document.getElementById('cancelModal');
        var openBtn   = document.getElementById('openCancelModal');
        var closeBtn  = document.getElementById('closeCancelModal');
        var reasonSel = document.getElementById('reasonCategory');
        var detailBox = document.getElementById('detailField');
        var confirmCh = document.getElementById('confirmCheck');
        var submitBtn = document.getElementById('submitCancel');

        if (openBtn && modal) {
            openBtn.addEventListener('click', function () {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }

        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function () {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }

        if (reasonSel && detailBox) {
            reasonSel.addEventListener('change', function () {
                if (this.value === 'other') {
                    detailBox.style.display = 'block';
                } else {
                    detailBox.style.display = 'none';
                }
            });
        }

        if (confirmCh && submitBtn) {
            confirmCh.addEventListener('change', function () {
                submitBtn.disabled = !this.checked;
            });
        }
    });
</script>
@endpush
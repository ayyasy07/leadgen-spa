@extends('user.layouts.main')

@section('title', 'Booking Confirmed — LeadGen Spa')
@section('meta_description', 'Your booking has been confirmed. We will contact you shortly.')
@section('footer_type', 'grid')

@section('header')
    @include('user.partials.header-back', ['backUrl' => url('/')])
@endsection

@push('styles')
<style>
    .bs-scope {
        --ink: #2a2420;
        --text: #4a4440;
        --muted: #8a8482;
        --line: #ede8e3;
        --cream: #fbf7f2;
        --cream-dark: #f5ede4;
        color: var(--ink);
    }

    /* ===== HERO SUCCESS ===== */
    .bs-hero {
        text-align: center;
        padding: 50px 24px 30px;
        max-width: 720px;
        margin: 0 auto;
    }
    .bs-hero .check-circle {
        width: 84px; height: 84px;
        border-radius: 50%;
        background: rgba(34, 197, 94, .12);
        color: #16a34a;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 22px;
        font-size: 36px;
        animation: popIn .6s cubic-bezier(.4, 1.6, .6, 1);
    }
    @keyframes popIn {
        0% { transform: scale(0); opacity: 0; }
        60% { transform: scale(1.15); }
        100% { transform: scale(1); opacity: 1; }
    }
    .bs-hero h1 {
        font-size: 26px; font-weight: 800;
        margin-bottom: 10px; letter-spacing: -.3px;
    }
    .bs-hero p {
        color: var(--muted); font-size: 14px;
        line-height: 1.6; max-width: 480px; margin: 0 auto;
    }

    /* ===== STATUS BADGE ===== */
    .bs-status-wrap {
        display: flex; justify-content: center;
        margin-top: 18px;
    }
    .bs-status {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 7px 16px; border-radius: 99px;
        font-size: 12.5px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .4px;
    }
    .bs-status i { font-size: 12px; }
    .bs-status.pending   { background: #fff4dc; color: #9a6208; }
    .bs-status.paid      { background: #e6f6ec; color: #1b7541; }
    .bs-status.completed { background: #e6f6ec; color: #1b7541; }
    .bs-status.cancelled { background: #fdeaea; color: #b02a2a; }
    .bs-status.no-show   { background: #eef0f4; color: #555b68; }
    .bs-status.expired   { background: #fdeaea; color: #b02a2a; }

    /* ===== PENDING ALERT ===== */
    .bs-pending-alert {
        max-width: 720px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #fff8e6 0%, #fff4dc 100%);
        border: 1.5px solid rgba(245, 158, 11, .3);
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 6px 20px -8px rgba(245, 158, 11, .3);
    }
    .bs-pending-alert .icon-wrap {
        width: 48px; height: 48px; border-radius: 50%;
        background: rgba(245, 158, 11, .15);
        color: #d97706;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex: none;
    }
    .bs-pending-alert .pa-text {
        flex: 1; min-width: 0;
    }
    .bs-pending-alert .pa-title {
        font-size: 14px; font-weight: 800; color: #7a4a08;
        margin-bottom: 4px;
    }
    .bs-pending-alert .pa-desc {
        font-size: 12.5px; color: #8a5a06; line-height: 1.5;
    }
    .bs-pending-alert .pa-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 11px 22px; border-radius: 99px;
        background: #d97706; color: #fff;
        text-decoration: none; font-size: 12.5px; font-weight: 700;
        white-space: nowrap;
        transition: all .2s;
        box-shadow: 0 6px 14px -4px rgba(217, 119, 6, .5);
    }
    .bs-pending-alert .pa-btn:hover {
        background: #b45309;
        transform: translateY(-1px);
        box-shadow: 0 10px 20px -4px rgba(217, 119, 6, .6);
    }

    /* ===== MAIN GRID ===== */
    .bs-wrap {
        max-width: 1080px; margin: 0 auto;
        padding: 20px 24px 80px;
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 22px;
        align-items: start;
    }

    /* ===== CARD ===== */
    .bs-card {
        background: #fff;
        border-radius: 20px;
        padding: 28px 30px;
        box-shadow: 0 6px 24px -12px rgba(30, 20, 15, .08);
        border: 1px solid var(--line);
    }
    .bs-card + .bs-card { margin-top: 18px; }

    .bs-card-head {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 22px;
    }
    .bs-icon {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(247, 108, 108, .1);
        color: var(--primary, #f76c6c);
        font-size: 14px; flex: none;
    }
    .bs-card-head h3 {
        font-size: 15px; font-weight: 700;
        margin: 0; color: var(--ink);
        letter-spacing: -.01em;
    }
    .bs-card-head .count {
        margin-left: auto;
        font-size: 12px; color: var(--muted);
        font-weight: 500;
    }

    /* ===== BOOKING CODE ===== */
    .bs-code {
        text-align: center;
        padding: 20px 16px;
        background: var(--cream);
        border-radius: 14px;
    }
    .bs-code-label {
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1.2px;
        color: var(--muted); margin-bottom: 8px;
    }
    .bs-code-value {
        font-family: 'Courier New', monospace;
        font-size: 24px; font-weight: 700;
        color: var(--primary, #f76c6c);
        letter-spacing: .5px;
        word-break: break-all;
    }

    /* ===== META ROWS ===== */
    .bs-meta-row {
        display: flex; align-items: flex-start;
        gap: 14px; padding: 12px 0;
        border-bottom: 1px solid var(--line);
    }
    .bs-meta-row:last-child { border-bottom: none; padding-bottom: 0; }
    .bs-meta-row:first-child { padding-top: 0; }
    .bs-meta-label {
        font-size: 12.5px; color: var(--muted);
        width: 92px; flex: none; padding-top: 1px;
    }
    .bs-meta-value {
        font-size: 13.5px; color: var(--ink);
        font-weight: 500; flex: 1; min-width: 0;
        word-break: break-word;
    }
    .bs-meta-value a { color: var(--primary, #f76c6c); text-decoration: none; }
    .bs-meta-value a:hover { text-decoration: underline; }

    /* ===== ITEMS TABLE ===== */
    .bs-table-wrap { overflow-x: auto; margin: 0 -30px; padding: 0 30px; }
    .bs-table {
        width: 100%; border-collapse: collapse; min-width: 480px;
    }
    .bs-table thead th {
        font-size: 11.5px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
        color: var(--muted);
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid var(--line);
    }
    .bs-table tbody td {
        padding: 14px 12px; vertical-align: middle;
        border-bottom: 1px solid var(--line);
        font-size: 13.5px; color: var(--text);
    }
    .bs-table tbody tr:last-child td { border-bottom: none; }
    .bs-table .item-name { font-weight: 600; color: var(--ink); display: block; margin-bottom: 2px; }
    .bs-table .item-dur  { font-size: 11.5px; color: var(--muted); }
    .bs-table .right     { text-align: right; }
    .bs-table .center    { text-align: center; }
    .bs-table tfoot td {
        padding: 16px 12px;
        font-size: 13.5px; font-weight: 600; color: var(--ink);
        background: var(--cream);
    }
    .bs-table tfoot td:first-child { border-bottom-left-radius: 12px; }
    .bs-table tfoot td:last-child  { border-bottom-right-radius: 12px; }
    .bs-table tfoot td.grand {
        font-size: 18px; font-weight: 800;
        color: var(--primary, #f76c6c);
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    .bs-table tfoot tr.fee-row td {
        font-size: 12.5px;
        color: #d97706;
        background: #fffbf0;
        font-weight: 700;
        padding: 10px 12px;
    }
    .bs-table tfoot tr.total-row td {
        background: var(--cream-dark);
        font-weight: 800;
        padding: 14px 12px;
    }

    /* ===== NOTES ===== */
    .bs-notes {
        background: #fff8e6;
        border-radius: 14px;
        padding: 16px 18px;
        font-size: 13px; color: #7a4a08;
        line-height: 1.6;
        white-space: pre-line;
    }

    /* ===== NOTICE ===== */
    .bs-notice {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 14px 16px;
        border-radius: 14px;
        font-size: 13px; line-height: 1.55;
    }
    .bs-notice.info   { background: rgba(59, 130, 246, .08); color: #1e40af; }
    .bs-notice.warn   { background: rgba(245, 158, 11, .1);  color: #8a5a06; }
    .bs-notice.success { background: rgba(34, 197, 94, .1); color: #15803d; }
    .bs-notice.danger { background: rgba(239, 68, 68, .1); color: #b91c1c; }
    .bs-notice .ic {
        width: 26px; height: 26px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, .6);
        font-size: 12px; flex: none;
    }
    .bs-notice strong { display: block; margin-bottom: 3px; font-weight: 700; }

    /* ===== PAY NOW BUTTON ===== */
    .bs-pay-btn {
        display: flex; align-items: center; justify-content: center;
        gap: 9px;
        padding: 16px 20px;
        border-radius: 30px;
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: #fff !important;
        text-decoration: none;
        font-size: 14px; font-weight: 700;
        margin-top: 16px;
        transition: all .2s;
        box-shadow: 0 8px 20px -4px rgba(217, 119, 6, .5);
        border: none; cursor: pointer; width: 100%;
    }
    .bs-pay-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 26px -4px rgba(217, 119, 6, .6);
    }
    .bs-pay-btn i { font-size: 15px; }

    /* ===== WHATSAPP BUTTON ===== */
    .bs-wa-btn {
        display: flex; align-items: center; justify-content: center;
        gap: 9px;
        padding: 14px 20px;
        border-radius: 30px;
        background: #25D366;
        color: #fff !important;
        text-decoration: none;
        font-size: 13.5px; font-weight: 700;
        margin-top: 16px;
        transition: all .2s;
        box-shadow: 0 6px 16px -4px rgba(37, 211, 102, .4);
    }
    .bs-wa-btn:hover {
        background: #1faa59;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px -4px rgba(37, 211, 102, .5);
    }
    .bs-wa-btn i { font-size: 16px; }

    /* ===== ACTION BUTTONS ===== */
    .bs-actions {
        max-width: 1080px; margin: 0 auto;
        padding: 0 24px 60px;
        display: flex; gap: 12px;
        justify-content: center; flex-wrap: wrap;
    }
    .bs-btn {
        display: inline-flex; align-items: center; gap: 9px;
        padding: 14px 30px;
        border-radius: 30px;
        font-size: 13px; font-weight: 700;
        text-decoration: none;
        transition: all .2s;
        border: 1.5px solid transparent;
        letter-spacing: .2px;
    }
    .bs-btn.primary {
        background: var(--primary, #f76c6c);
        color: #fff;
        box-shadow: 0 8px 20px -4px rgba(247, 108, 108, .4);
    }
    .bs-btn.primary:hover {
        background: var(--primary-dark, #e85555);
        transform: translateY(-2px);
        box-shadow: 0 12px 26px -4px rgba(247, 108, 108, .5);
    }
    .bs-btn.outline {
        background: #fff;
        color: var(--ink);
        border-color: var(--line);
    }
    .bs-btn.outline:hover {
        border-color: var(--primary, #f76c6c);
        color: var(--primary, #f76c6c);
        transform: translateY(-2px);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        .bs-wrap {
            grid-template-columns: 1fr;
            padding: 16px 20px 60px;
        }
        .bs-hero { padding: 40px 20px 24px; }
        .bs-hero h1 { font-size: 22px; }
        .bs-card { padding: 22px 22px; }
        .bs-meta-row { flex-direction: column; gap: 4px; }
        .bs-meta-label { width: auto; }
        .bs-table-wrap { margin: 0 -22px; padding: 0 22px; }
        .bs-table { min-width: 560px; }
        .bs-actions { padding: 0 20px 50px; }
        .bs-pending-alert { flex-direction: column; text-align: center; }
        .bs-pending-alert .pa-btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

<div class="bs-scope">

    @php
        $statusIcon = [
            'pending'   => 'fa-clock',
            'paid'      => 'fa-check-circle',
            'completed' => 'fa-check-double',
            'cancelled' => 'fa-times-circle',
            'no-show'   => 'fa-user-slash',
            'expired'   => 'fa-hourglass-end',
        ][$booking->status] ?? 'fa-clock';
    @endphp

    {{-- HERO --}}
    <div class="bs-hero">
        <div class="check-circle" style="@if($booking->status === 'cancelled' || $booking->status === 'expired') background: rgba(239,68,68,.12); color: #dc2626; @elseif($booking->status === 'no-show') background: rgba(100,116,139,.12); color: #64748b; @elseif($booking->status === 'pending') background: rgba(245,158,11,.12); color: #d97706; @endif">
            <i class="fas {{ $statusIcon }}"></i>
        </div>
        <h1>
            @if($booking->status === 'pending')
                Booking Received!
            @elseif($booking->status === 'paid')
                Payment Successful!
            @elseif($booking->status === 'completed')
                Thank You!
            @elseif($booking->status === 'cancelled')
                Booking Cancelled
            @elseif($booking->status === 'expired')
                Booking Expired
            @elseif($booking->status === 'no-show')
                Booking No-Show
            @else
                Booking Status
            @endif
        </h1>
        <p>
            @if($booking->status === 'pending')
                Thank you, <strong>{{ $booking->customer_name }}</strong>. Your booking is waiting for payment. Please complete the payment to secure your slot.
            @elseif($booking->status === 'paid')
                Thank you, <strong>{{ $booking->customer_name }}</strong>. Your payment has been received. We look forward to seeing you!
            @elseif($booking->status === 'completed')
                Thank you, <strong>{{ $booking->customer_name }}</strong>. We hope you enjoyed your treatment.
            @elseif($booking->status === 'cancelled')
                This booking has been cancelled. If you have any questions, please contact us.
            @elseif($booking->status === 'expired')
                This booking has expired because payment was not completed in time.
            @elseif($booking->status === 'no-show')
                This booking was marked as no-show. Please contact us if you have any questions.
            @endif
        </p>

        <div class="bs-status-wrap">
            <span class="bs-status {{ $booking->status }}">
                <i class="fas {{ $statusIcon }}"></i>
                {{ $booking->status_label }}
            </span>
        </div>
    </div>

    {{-- PENDING ALERT (kalo status pending) --}}
    @if($booking->status === 'pending' && $booking->canBePaid())
        <div class="bs-pending-alert">
            <div class="icon-wrap">
                <i class="fas fa-clock"></i>
            </div>
            <div class="pa-text">
                <div class="pa-title">Awaiting Payment</div>
                <div class="pa-desc">
                    Please complete your payment before
                    <strong>{{ $booking->expired_at ? $booking->expired_at->format('d M Y, H:i') : 'the time runs out' }}</strong>
                    so your booking does not expire.
                </div>
            </div>
            <form action="{{ route('payment.create', $booking->booking_code) }}" method="POST" style="margin:0;">
                @csrf
                <input type="hidden" name="payment_method" value="{{ $booking->payment_method }}">
                <input type="hidden" name="payment_method_name" value="{{ $booking->payment_method_name }}">
                <input type="hidden" name="payment_fee" value="{{ $booking->payment_fee }}">
                <button type="submit" class="pa-btn">
                    <i class="fas fa-credit-card"></i> Pay Now
                </button>
            </form>
        </div>
    @endif

    {{-- MAIN GRID --}}
    <div class="bs-wrap">

        {{-- LEFT COLUMN --}}
        <div>

            {{-- BOOKING CODE --}}
            <div class="bs-card">
                <div class="bs-card-head">
                    <div class="bs-icon"><i class="fas fa-hashtag"></i></div>
                    <h3>Booking Code</h3>
                </div>
                <div class="bs-code">
                    <div class="bs-code-label">Save this code for reference</div>
                    <div class="bs-code-value">{{ $booking->booking_code }}</div>
                </div>
            </div>

            {{-- BOOKED ITEMS --}}
            <div class="bs-card">
                <div class="bs-card-head">
                    <div class="bs-icon"><i class="fas fa-spa"></i></div>
                    <h3>Booked Items</h3>
                    <span class="count">{{ count($booking->items ?? []) }} item{{ count($booking->items ?? []) > 1 ? 's' : '' }}</span>
                </div>
                <div class="bs-table-wrap">
                    <table class="bs-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="center" style="width:60px">Qty</th>
                                <th class="right" style="width:130px">Price</th>
                                <th class="right" style="width:130px">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($booking->items ?? [] as $item)
                                <tr>
                                    <td>
                                        <span class="item-name">{{ $item['name'] }}</span>
                                        <span class="item-dur">{{ $item['duration'] ?? '-' }}</span>
                                    </td>
                                    <td class="center">{{ $item['qty'] }}</td>
                                    <td class="right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="right" style="font-weight: 700; color: var(--ink)">
                                        Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="center" style="padding: 28px; color: var(--muted)">
                                        No items found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            @if($booking->payment_fee > 0)
                                <tr class="fee-row">
                                    <td colspan="3" class="right">Service Fee</td>
                                    <td class="right">Rp {{ number_format($booking->payment_fee, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr class="total-row">
                                <td colspan="3" class="right">Total</td>
                                <td class="right grand" style="white-space:nowrap;">
                                    Rp {{ number_format($booking->payment_amount ?: $booking->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- NOTES --}}
            @if($booking->notes)
                <div class="bs-card">
                    <div class="bs-card-head">
                        <div class="bs-icon" style="background: #fff4dc; color: #b7791f">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <h3>Your Notes</h3>
                    </div>
                    <div class="bs-notes">{{ $booking->notes }}</div>
                </div>
            @endif

        </div>

        {{-- RIGHT COLUMN --}}
        <div>

            {{-- CUSTOMER INFO --}}
            <div class="bs-card">
                <div class="bs-card-head">
                    <div class="bs-icon"><i class="fas fa-user"></i></div>
                    <h3>Customer</h3>
                </div>
                <div class="bs-meta-row">
                    <span class="bs-meta-label">Name</span>
                    <span class="bs-meta-value">{{ $booking->customer_name }}</span>
                </div>
                <div class="bs-meta-row">
                    <span class="bs-meta-label">Phone</span>
                    <span class="bs-meta-value">{{ $booking->customer_phone }}</span>
                </div>
                <div class="bs-meta-row">
                    <span class="bs-meta-label">Email</span>
                    <span class="bs-meta-value">
                        @if($booking->customer_email)
                            <a href="mailto:{{ $booking->customer_email }}">{{ $booking->customer_email }}</a>
                        @else
                            -
                        @endif
                    </span>
                </div>

                <a href="{{ $booking->whatsapp_url }}" target="_blank" rel="noopener" class="bs-wa-btn">
                    <i class="fab fa-whatsapp"></i>
                    Chat via WhatsApp
                </a>
            </div>

            {{-- SCHEDULE --}}
            <div class="bs-card">
                <div class="bs-card-head">
                    <div class="bs-icon"><i class="fas fa-calendar-check"></i></div>
                    <h3>Schedule</h3>
                </div>
                <div class="bs-meta-row">
                    <span class="bs-meta-label">Date</span>
                    <span class="bs-meta-value">{{ $booking->booking_date->format('l, d F Y') }}</span>
                </div>
                <div class="bs-meta-row">
                    <span class="bs-meta-label">Time</span>
                    <span class="bs-meta-value">{{ $booking->booking_time }}</span>
                </div>
                <div class="bs-meta-row">
                    <span class="bs-meta-label">Booked At</span>
                    <span class="bs-meta-value">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if($booking->paid_at)
                    <div class="bs-meta-row">
                        <span class="bs-meta-label">Paid At</span>
                        <span class="bs-meta-value" style="color:#16a34a;font-weight:700;">
                            {{ $booking->paid_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- PAYMENT NOTICE / NEXT STEP --}}
            <div class="bs-card">
                <div class="bs-card-head">
                    <div class="bs-icon"><i class="fas fa-info-circle"></i></div>
                    <h3>What's Next?</h3>
                </div>

                @if($booking->status === 'pending')
                    <div class="bs-notice warn">
                        <div class="ic"><i class="fas fa-clock"></i></div>
                        <div>
                            <strong>Awaiting Payment</strong>
                            Please complete your payment. The booking will automatically expire if not paid within the time limit.
                        </div>
                    </div>
                    @if($booking->canBePaid())
                        <form action="{{ route('payment.create', $booking->booking_code) }}" method="POST" style="margin:0;">
                            @csrf
                            <input type="hidden" name="payment_method" value="{{ $booking->payment_method }}">
                            <input type="hidden" name="payment_method_name" value="{{ $booking->payment_method_name }}">
                            <input type="hidden" name="payment_fee" value="{{ $booking->payment_fee }}">
                            <button type="submit" class="bs-pay-btn">
                                <i class="fas fa-credit-card"></i> Pay Now
                            </button>
                        </form>
                    @endif
                @elseif($booking->status === 'paid')
                    <div class="bs-notice success">
                        <div class="ic"><i class="fas fa-check"></i></div>
                        <div>
                            <strong>You're All Set!</strong>
                            Your payment has been received. Please arrive 10 minutes early. See you soon!
                        </div>
                    </div>
                @elseif($booking->status === 'completed')
                    <div class="bs-notice info">
                        <div class="ic"><i class="fas fa-heart"></i></div>
                        <div>
                            <strong>Thank You!</strong>
                            We hope you enjoyed your treatment. See you again soon.
                        </div>
                    </div>
                @elseif($booking->status === 'cancelled' || $booking->status === 'expired')
                    <div class="bs-notice danger">
                        <div class="ic"><i class="fas fa-times"></i></div>
                        <div>
                            <strong>Booking {{ $booking->status_label }}</strong>
                            This booking is no longer active. Please make a new booking or contact us.
                        </div>
                    </div>
                @elseif($booking->status === 'no-show')
                    <div class="bs-notice warn">
                        <div class="ic"><i class="fas fa-user-slash"></i></div>
                        <div>
                            <strong>No-Show</strong>
                            This booking was marked as no-show. Contact us if you have any questions.
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="bs-actions">
        <a href="{{ url('history') }}" class="bs-btn primary">
            <i class="fas fa-list-ul"></i> View My Bookings
        </a>
        <a href="{{ url('/') }}" class="bs-btn outline">
            <i class="fas fa-home"></i> Back to Home
        </a>
    </div>

</div>

@endsection

@push('scripts')
@if($booking->status === 'pending')
<script>
    // Auto-poll status tiap 5 detik — kalo udah paid, reload halaman
    let pollCount = 0;
    const maxPoll = 120; // 10 menit (120 * 5s)

    const pollInterval = setInterval(async () => {
        pollCount++;
        if (pollCount > maxPoll) {
            clearInterval(pollInterval);
            return;
        }

        try {
            const res = await fetch('{{ route('payment.status', $booking->booking_code) }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.success && data.status !== 'pending') {
                clearInterval(pollInterval);
                window.location.reload();
            }
        } catch (e) {
            // silent
        }
    }, 5000);
</script>
@endif
@endpush
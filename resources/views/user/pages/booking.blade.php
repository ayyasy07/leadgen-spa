@extends('user.layouts.main')

@section('title', 'Book a Treatment — LeadGen Spa')
@section('meta_description', 'Complete your details to confirm your treatment booking.')
@section('footer_type', 'grid')

@section('header')
    @include('user.partials.header-back', ['backUrl' => url('treatments')])
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* ================= PAGE TITLE ================= */
    .page-title {
        padding: 40px 0 10px;
        max-width: 1180px;
        margin: 0 auto;
        padding-left: 24px; padding-right: 24px;
    }
    .page-title .eyebrow {
        font-size: 11px; font-weight: 700; letter-spacing: 1.8px;
        text-transform: uppercase; color: var(--primary);
        margin-bottom: 10px; display: flex; align-items: center; gap: 8px;
    }
    .page-title .eyebrow::before {
        content: ''; width: 16px; height: 2px; background: var(--primary); border-radius: 2px;
    }
    .page-title h1 { font-size: 28px; font-weight: 800; margin-bottom: 8px; letter-spacing: -.3px; }
    .page-title p { color: var(--gray); font-size: 13.5px; }

    /* ================= LAYOUT ================= */
    .booking-wrap {
        max-width: 1180px; margin: 0 auto;
        padding: 22px 24px 100px;
        display: grid; grid-template-columns: 1.5fr 1fr;
        gap: 28px; align-items: start;
    }
    .card {
        background: #fff; border-radius: 20px;
        padding: 38px 40px 42px;
        box-shadow: 0 8px 30px -10px rgba(30,20,15,.08);
        border: 1px solid rgba(0,0,0,.03);
    }

    .card h2 {
        font-size: 16px; font-weight: 700; margin-bottom: 26px;
        display: flex; align-items: center; gap: 12px;
        color: var(--ink);
    }
    .card h2.section-gap { margin-top: 38px; }
    .h-badge {
        width: 32px; height: 32px; border-radius: 10px; flex: none;
        display: flex; align-items: center; justify-content: center;
        background: rgba(247,108,108,.1); color: var(--primary);
        font-size: 13px;
    }

    /* ================= FIELDS ================= */
    .field { margin-bottom: 20px; }
    .field label {
        display: block; font-size: 12px; font-weight: 700;
        color: var(--ink); margin-bottom: 9px;
        letter-spacing: .2px;
    }
    .input-pill {
        display: flex; align-items: center; gap: 11px;
        border: 1.5px solid var(--line); border-radius: 30px;
        padding: 13px 19px; background: #fff;
        transition: border-color .2s, box-shadow .2s;
    }
    .input-pill:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(247,108,108,.09);
    }
    .input-pill i.left-icon { color: var(--primary); opacity: .5; font-size: 13px; flex: none; }
    .input-pill:focus-within i.left-icon { opacity: 1; }
    .input-pill input, .input-pill select {
        flex: 1; border: none; outline: none; background: transparent;
        font-size: 13.5px; font-family: 'Montserrat', sans-serif;
        color: var(--ink); width: 100%;
        -webkit-appearance: none; -moz-appearance: none; appearance: none;
        cursor: pointer;
    }
    .input-pill input[readonly] { cursor: pointer; }
    .input-pill input::placeholder { color: #b8b0ac; }
    .input-pill.select-pill i.chevron {
        color: #8a8482; font-size: 11px; flex: none; pointer-events: none;
    }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .textarea-pill {
        display: flex; align-items: flex-start; gap: 11px;
        border: 1.5px solid var(--line); border-radius: 20px;
        padding: 15px 19px; background: #fff;
        transition: border-color .2s, box-shadow .2s;
    }
    .textarea-pill:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(247,108,108,.09);
    }
    .textarea-pill i { color: var(--primary); opacity: .5; font-size: 13px; margin-top: 3px; flex: none; }
    .textarea-pill textarea {
        flex: 1; border: none; outline: none; resize: none;
        min-height: 70px; font-size: 13.5px;
        font-family: 'Montserrat', sans-serif;
        color: var(--ink); background: transparent;
    }
    .char-count { text-align: right; font-size: 11px; color: #b8b0ac; margin-top: 6px; }

    /* ================= PAYMENT METHODS ================= */
    .pm-loading, .pm-empty {
        text-align: center;
        padding: 20px;
        color: var(--gray);
        font-size: 13px;
    }
    .pm-loading i { color: var(--primary); margin-right: 8px; }
    .pm-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .pm-item {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 14px;
        border: 1.5px solid var(--line);
        border-radius: 12px;
        cursor: pointer;
        transition: all .2s;
        background: #fff;
    }
    .pm-item:hover {
        border-color: var(--primary);
        background: rgba(247,108,108,.03);
    }
    .pm-item.selected {
        border-color: var(--primary);
        background: rgba(247,108,108,.06);
        box-shadow: 0 0 0 3px rgba(247,108,108,.08);
    }
    .pm-item input[type="radio"] {
        accent-color: var(--primary);
        flex: none;
    }
    .pm-item .pm-info {
        flex: 1; min-width: 0;
    }
    .pm-item .pm-name {
        font-size: 12.5px; font-weight: 700; color: var(--ink);
        margin-bottom: 2px;
        display: block;
    }
    .pm-item .pm-fee {
        font-size: 11px; color: var(--gray);
    }
    .pm-item .pm-fee.free { color: #16a34a; font-weight: 700; }

    /* ================= SUMMARY ================= */
    .summary-card-sticky { position: sticky; top: 24px; }

    .summary-empty {
        color: var(--gray); font-size: 13.5px;
        text-align: center; padding: 6px 0 2px;
    }
    .summary-item {
        padding: 18px 0;
        border-bottom: 1px solid var(--line);
    }
    .summary-item:first-child { padding-top: 4px; }
    .summary-item:last-of-type { border-bottom: none; }

    .summary-item .s-top-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
    .summary-item .s-name { font-size: 13.5px; font-weight: 700; color: var(--ink); margin-bottom: 3px; }
    .summary-item .s-meta { font-size: 11.5px; color: var(--gray); }
    .summary-item .s-price { font-size: 13px; font-weight: 700; color: var(--ink); white-space: nowrap; }

    .summary-item .s-qty-wrap {
        display: flex; align-items: center; justify-content: space-between;
        margin-top: 12px;
        padding: 6px 6px 6px 12px;
        background: var(--bg-cream);
        border-radius: 12px;
    }
    .summary-item .s-qty-label {
        font-size: 10.5px; color: var(--gray); font-weight: 700;
        text-transform: uppercase; letter-spacing: .4px;
    }
    .summary-item .s-qty-control {
        display: flex; align-items: center; gap: 8px;
    }
    .summary-item .s-qty-control button {
        width: 25px; height: 25px; border-radius: 50%;
        border: none; background: #fff; color: var(--ink);
        font-size: 12px; font-weight: 700;
        cursor: pointer; transition: .2s;
        display: flex; align-items: center; justify-content: center;
        line-height: 1;
        box-shadow: 0 1px 2px rgba(0,0,0,.06);
    }
    .summary-item .s-qty-control button:hover:not(:disabled) {
        background: var(--primary); color: #fff;
    }
    .summary-item .s-qty-control button:disabled {
        opacity: .3; cursor: not-allowed; background: #eee;
    }
    .summary-item .s-qty-value {
        min-width: 20px; text-align: center;
        font-size: 12.5px; font-weight: 700; color: var(--ink);
    }

    /* fee row + total */
    .summary-row {
        display: flex; justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        font-size: 13px;
        color: var(--gray);
        border-bottom: 1px dashed var(--line);
    }
    .summary-row .label { font-weight: 600; }
    .summary-row .value { font-weight: 700; color: var(--ink); }
    .summary-row.fee .value { color: #d97706; }
    .summary-row.fee .value.free { color: #16a34a; }

    .summary-total {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 18px; padding: 17px 20px;
        background: var(--bg-cream);
        border-radius: 14px;
        font-size: 13.5px; font-weight: 700; color: var(--ink);
    }
    .summary-total span.amount { color: var(--primary); font-size: 19px; }

    /* ================= ALERTS ================= */
    .alert {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 14px 16px; border-radius: 12px;
        margin-bottom: 20px; font-size: 12.5px; line-height: 1.55;
    }
    .alert .alert-ic {
        width: 24px; height: 24px; border-radius: 50%; flex: none;
        display: flex; align-items: center; justify-content: center; font-size: 11px;
        margin-top: 1px;
    }
    .alert-danger {
        background: rgba(231,76,60,.06);
        color: #a83226;
    }
    .alert-danger .alert-ic { background: rgba(231,76,60,.14); color: #e74c3c; }
    .alert-danger ul { list-style: disc; padding-left: 18px; margin: 0; }

    .alert-warning {
        background: rgba(245,158,11,.07);
        color: #8a5a06;
    }
    .alert-warning .alert-ic { background: rgba(245,158,11,.16); color: #d97706; }

    /* ================= SLOT INFO ================= */
    .slot-info {
        font-size: 12px;
        margin-top: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        display: none;
        align-items: center; gap: 8px;
        font-weight: 600;
    }
    .slot-info.ok {
        background: rgba(34,197,94,.08);
        color: #16a34a;
    }
    .slot-info.warn {
        background: rgba(239,68,68,.08);
        color: #dc2626;
    }

    /* ================= SUBMIT BUTTON ================= */
    .btn-confirm {
        width: 100%; color: #fff;
        background: var(--primary);
        border: none; padding: 16px; border-radius: 30px;
        font-size: 13.5px; font-weight: 700; letter-spacing: .3px;
        cursor: pointer; transition: all .25s ease;
        margin-top: 20px; font-family: 'Montserrat', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 9px;
    }
    .btn-confirm i { transition: transform .2s ease; }
    .btn-confirm:hover:not(:disabled) {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(247,108,108,.3);
    }
    .btn-confirm:hover:not(:disabled) i { transform: translateX(3px); }
    .btn-confirm:disabled {
        background: #d9d3ce; cursor: not-allowed; transform: none; box-shadow: none;
    }

    /* ================= EMPTY CART ================= */
    .empty-cart-cta {
        text-align: center;
        padding: 18px 10px 6px;
    }
    .empty-cart-cta .ec-icon {
        width: 48px; height: 48px; border-radius: 50%;
        background: var(--bg-cream); color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; margin: 0 auto 14px;
    }
    .empty-cart-cta a {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 26px;
        background: var(--primary);
        color: #fff;
        border-radius: 30px;
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 700;
        transition: .25s;
    }
    .empty-cart-cta a:hover { background: var(--primary-dark); transform: translateY(-1px); }

    /* =====================================================
       FLATPICKR
    ===================================================== */
    .flatpickr-calendar {
        width: 288px !important;
        max-width: calc(100vw - 24px);
        padding: 14px 12px 12px !important;
        background: #fff !important;
        border: 1px solid var(--line) !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 30px -6px rgba(30,20,15,.14) !important;
        font-family: 'Montserrat', sans-serif !important;
        z-index: 999999 !important;
    }
    .flatpickr-calendar.open {
        z-index: 999999 !important;
        animation: fpFade .15s ease !important;
    }
    @keyframes fpFade {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: none; }
    }
    .flatpickr-calendar.arrowTop::before,
    .flatpickr-calendar.arrowTop::after,
    .flatpickr-calendar.arrowBottom::before,
    .flatpickr-calendar.arrowBottom::after {
        display: none !important;
    }
    .flatpickr-wrapper { position: relative; z-index: 999999; }

    .flatpickr-innerContainer,
    .flatpickr-rContainer,
    .flatpickr-days {
        width: 100% !important;
        min-width: 0 !important;
    }

    .flatpickr-months {
        display: flex !important;
        align-items: center;
        gap: 2px;
        padding: 0 2px 10px !important;
    }
    .flatpickr-months .flatpickr-month {
        order: 0;
        flex: 1;
        height: 30px;
        overflow: visible;
        text-align: left;
        color: var(--ink);
        fill: var(--ink);
    }
    .flatpickr-current-month {
        position: static !important;
        transform: none !important;
        width: 100% !important;
        height: 30px;
        padding: 0 !important;
        display: flex !important;
        align-items: center;
        justify-content: flex-start;
        gap: 5px;
        font-size: 14px !important;
        font-weight: 700 !important;
        line-height: 1;
        color: var(--ink) !important;
    }
    .flatpickr-current-month span.cur-month {
        margin: 0 !important;
        font-weight: 700;
        color: var(--ink);
    }
    .flatpickr-current-month span.cur-month:hover { background: transparent; }
    .flatpickr-current-month .numInputWrapper { width: auto !important; }
    .flatpickr-current-month .numInputWrapper:hover { background: transparent; }
    .flatpickr-current-month input.cur-year {
        width: 4ch !important;
        padding: 0 !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        color: var(--gray) !important;
        pointer-events: none;
    }
    .numInputWrapper span { display: none !important; }

    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        position: static !important;
        width: 28px; height: 28px;
        padding: 0 !important;
        border-radius: 8px;
        display: flex !important;
        align-items: center; justify-content: center;
        flex: none;
        background: transparent;
        color: #8a8482 !important;
        fill: #8a8482 !important;
        font-size: 10px;
        transition: background .15s, color .15s;
    }
    .flatpickr-months .flatpickr-prev-month { order: 1; }
    .flatpickr-months .flatpickr-next-month { order: 2; }
    .flatpickr-months .flatpickr-prev-month svg,
    .flatpickr-months .flatpickr-next-month svg { width: 11px; height: 11px; }
    .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-months .flatpickr-next-month:hover {
        background: var(--bg-cream);
        color: var(--ink) !important;
        fill: var(--ink) !important;
    }
    .flatpickr-months .flatpickr-prev-month:hover svg,
    .flatpickr-months .flatpickr-next-month:hover svg { fill: var(--ink) !important; }
    .flatpickr-months .flatpickr-prev-month.flatpickr-disabled,
    .flatpickr-months .flatpickr-next-month.flatpickr-disabled {
        display: flex !important;
        visibility: hidden;
    }

    .flatpickr-weekdays {
        width: 100%;
        height: 26px;
        margin: 0 0 2px;
    }
    .flatpickr-weekdaycontainer {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr);
        width: 100%;
    }
    span.flatpickr-weekday {
        text-align: center;
        line-height: 26px;
        font-size: 10.5px !important;
        font-weight: 600 !important;
        letter-spacing: .4px;
        text-transform: uppercase;
        color: #a8a09b !important;
    }

    .dayContainer {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr);
        justify-items: center;
        gap: 2px 0;
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        padding: 0;
    }
    .flatpickr-day {
        flex: none;
        position: relative;
        width: 34px;
        max-width: 34px !important;
        height: 34px !important;
        line-height: 34px !important;
        margin: 0 !important;
        border: none !important;
        border-radius: 8px !important;
        background: transparent;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: var(--ink) !important;
        transition: background .12s, color .12s;
    }
    .flatpickr-day:hover,
    .flatpickr-day:focus {
        background: rgba(247,108,108,.1) !important;
        color: var(--primary) !important;
        border: none !important;
    }

    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
        color: #a8a09b !important;
        background: transparent;
    }
    .flatpickr-day.prevMonthDay:hover,
    .flatpickr-day.nextMonthDay:hover {
        background: rgba(247,108,108,.1) !important;
        color: var(--primary) !important;
    }

    .flatpickr-day.today {
        background: transparent !important;
        color: var(--primary) !important;
        font-weight: 700 !important;
    }
    .flatpickr-day.today::after {
        content: '';
        position: absolute;
        bottom: 4px; left: 50%;
        width: 3px; height: 3px; margin-left: -1.5px;
        border-radius: 50%;
        background: var(--primary);
    }
    .flatpickr-day.today:hover { background: rgba(247,108,108,.1) !important; }

    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.flatpickr-disabled:hover,
    .flatpickr-day.notAllowed,
    .flatpickr-day.notAllowed:hover,
    .flatpickr-day.prevMonthDay.flatpickr-disabled,
    .flatpickr-day.nextMonthDay.flatpickr-disabled {
        background: transparent !important;
        color: #d9d2cd !important;
        cursor: not-allowed !important;
    }

    .flatpickr-day.flatpickr-disabled.closed-date,
    .flatpickr-day.flatpickr-disabled.closed-date:hover {
        background: rgba(239,68,68,.08) !important;
        color: #e5484d !important;
        text-decoration: line-through;
        opacity: 1 !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.selected:hover,
    .flatpickr-day.selected:focus,
    .flatpickr-day.selected.prevMonthDay,
    .flatpickr-day.selected.nextMonthDay {
        background: var(--primary) !important;
        color: #fff !important;
        font-weight: 600 !important;
        border: none !important;
        box-shadow: none !important;
    }
    .flatpickr-day.selected.today::after { background: #fff; }

    .closed-legend {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 10px;
        padding: 5px 11px;
        border-radius: 8px;
        background: rgba(239,68,68,.06);
        font-size: 11.5px;
        color: #b3392f;
    }
    .closed-legend .dot {
        width: 8px;
        height: 8px;
        border-radius: 2px;
        background: rgba(239,68,68,.3);
        flex: none;
    }

    @media (max-width: 900px) {
        .booking-wrap { grid-template-columns: 1fr; }
        .row-2 { grid-template-columns: 1fr; }
        .summary-card-sticky { position: static; }
        .card { padding: 30px 24px 34px; }
        .pm-list { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

    <div class="page-title">
        <div class="eyebrow">One step away</div>
        <h1>Book a Treatment</h1>
        <p>Complete the details below to confirm your booking.</p>
    </div>

    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
        @csrf

        <div class="booking-wrap">

            {{-- LEFT: FORM --}}
            <div class="card">
                <h2><span class="h-badge"><i class="fas fa-user"></i></span> Your Details</h2>

                @if(session('error'))
                    <div class="alert alert-warning">
                        <span class="alert-ic"><i class="fas fa-exclamation-circle"></i></span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <span class="alert-ic"><i class="fas fa-triangle-exclamation"></i></span>
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="field">
                    <label for="name">Full Name</label>
                    <div class="input-pill">
                        <i class="fas fa-user left-icon"></i>
                        <input type="text" id="name" name="name"
                               value="{{ old('name', auth()->user()->name ?? '') }}"
                               placeholder="Your name" required>
                    </div>
                </div>

                <div class="field">
                    <label for="phone">WhatsApp Number</label>
                    <div class="input-pill">
                        <i class="fas fa-phone left-icon"></i>
                        <input type="tel" id="phone" name="phone"
                               value="{{ old('phone', auth()->user()->phone ?? '') }}"
                               placeholder="e.g. 08xx xxxx xxxx" required>
                    </div>
                </div>

                <h2 class="section-gap"><span class="h-badge"><i class="fas fa-calendar-days"></i></span> Booking Schedule</h2>

                <div class="row-2">
                    <div class="field">
                        <label for="booking_date">Date</label>
                        <div class="input-pill">
                            <i class="fas fa-calendar-days left-icon"></i>
                            <input type="text" id="booking_date" name="booking_date"
                                   value="{{ old('booking_date', $today) }}"
                                   placeholder="Select a date"
                                   readonly required>
                        </div>
                        @if(!empty($closedDates))
                            <div class="closed-legend">
                                <span class="dot"></span>
                                <span>Red-letter <strong>Day</strong> = Closed / Day Off</span>
                            </div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="booking_time">Time</label>
                        <div class="input-pill select-pill">
                            <i class="fas fa-clock left-icon"></i>
                            <select id="booking_time" name="booking_time" required>
                                <option value="" disabled selected>Select a time</option>
                                @foreach($slots as $slot)
                                    @php
                                        $disabled = in_array($slot['status'], ['full', 'locked']);
                                    @endphp
                                    <option value="{{ $slot['time'] }}"
                                            data-status="{{ $slot['status'] }}"
                                            data-remaining="{{ $slot['remaining'] }}"
                                            {{ $disabled ? 'disabled' : '' }}
                                            {{ old('booking_time') == $slot['time'] ? 'selected' : '' }}>
                                        {{ $slot['time'] }} — {{ $slot['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div id="slotInfo" class="slot-info"></div>
                    </div>
                </div>

                <div class="field">
                    <label for="notes">Notes (Optional)</label>
                    <div class="textarea-pill">
                        <i class="fa-regular fa-comment-dots"></i>
                        <textarea id="notes" name="notes" maxlength="150"
                                  placeholder="e.g. request a female therapist">{{ old('notes') }}</textarea>
                    </div>
                    <div class="char-count"><span id="charCount">0</span>/150</div>
                </div>

                {{-- ============================================
                     PAYMENT METHOD
                ============================================ --}}
                <h2 class="section-gap"><span class="h-badge"><i class="fas fa-credit-card"></i></span> Select Payment Method</h2>

                <div id="pmContainer">
                    <div class="pm-loading" id="pmLoading">
                        <i class="fas fa-spinner fa-spin"></i> Loading payment methods...
                    </div>
                </div>

                {{-- Hidden inputs buat submit payment method --}}
                <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="">
                <input type="hidden" name="payment_method_name" id="selectedPaymentMethodName" value="">
                <input type="hidden" name="payment_fee" id="selectedPaymentFee" value="0">

                <div id="hiddenItems"></div>
            </div>

            {{-- RIGHT: SUMMARY + BUTTON --}}
            <div class="card summary-card-sticky">
                <h2><span class="h-badge"><i class="fas fa-receipt"></i></span> Order Summary</h2>
                <div id="summaryContent">
                    <p class="summary-empty">Your cart is empty.</p>
                </div>

                {{-- Fee row + Total --}}
                <div id="feeRow" class="summary-row fee" style="display:none;">
                    <span class="label">Service Fee</span>
                    <span class="value" id="feeValue">Rp 0</span>
                </div>

                <div class="summary-total">
                    <span>Total</span>
                    <span class="amount" id="summaryTotal">Rp 0</span>
                </div>

                <button type="submit" form="bookingForm" class="btn-confirm" id="btnSubmit">
                    Confirm Booking <i class="fas fa-arrow-right"></i>
                </button>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // ============================================
    // CHAR COUNTER
    // ============================================
    const notes = document.getElementById('notes');
    const charCount = document.getElementById('charCount');
    if (notes) {
        notes.addEventListener('input', () => {
            charCount.textContent = notes.value.length;
        });
    }

    // ============================================
    // FORMAT RUPIAH
    // ============================================
    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    // ============================================
    // LOAD CART
    // ============================================
    let cart = [];
    try { cart = JSON.parse(localStorage.getItem('spaCart')) || []; } catch (e) { cart = []; }

    // ============================================
    // SLOT COUNT
    // ============================================
    function countSlotsNeeded() {
        let total = 0;
        cart.forEach(item => {
            const unit = item.unit || 'person';
            const qty = (unit === 'couple') ? 1 : (parseInt(item.qty) || 1);
            total += (unit === 'couple') ? (qty * 2) : qty;
        });
        return total;
    }

    function saveCart() {
        localStorage.setItem('spaCart', JSON.stringify(cart));
    }

    // ============================================
    // RENDER SUMMARY
    // ============================================
    let subtotal = 0;

    function renderSummary() {
        const summaryContent = document.getElementById('summaryContent');
        const summaryTotal = document.getElementById('summaryTotal');
        const hiddenItems = document.getElementById('hiddenItems');

        if (cart.length === 0) {
            summaryContent.innerHTML = `
                <p class="summary-empty">Your cart is empty.</p>
                <div class="empty-cart-cta">
                    <div class="ec-icon"><i class="fas fa-spa"></i></div>
                    <a href="{{ url('treatments') }}"><i class="fas fa-arrow-left"></i> Browse Treatments</a>
                </div>
            `;
            summaryTotal.textContent = 'Rp 0';
            hiddenItems.innerHTML = '';
            subtotal = 0;
            updateFee();
            return;
        }

        subtotal = 0;
        summaryContent.innerHTML = '';
        hiddenItems.innerHTML = '';

        cart.forEach((item, index) => {
            const unit = item.unit || 'person';
            const qty = (unit === 'couple') ? 1 : (parseInt(item.qty) || 1);
            const lineTotal = item.price * qty;
            subtotal += lineTotal;

            const unitLabel = (unit === 'couple') ? 'package' : 'person';
            const canDecrease = qty >= 1;
            const canIncrease = qty < 20;

            const qtyControl = (unit === 'person') ? `
                <div class="s-qty-wrap">
                    <span class="s-qty-label">Guests</span>
                    <div class="s-qty-control">
                        <button type="button" onclick="changeQty(${index}, -1)" ${canDecrease ? '' : 'disabled'}>
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="s-qty-value">${qty}</span>
                        <button type="button" onclick="changeQty(${index}, 1)" ${canIncrease ? '' : 'disabled'}>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            ` : '';

            summaryContent.innerHTML += `
                <div class="summary-item">
                    <div class="s-top-row">
                        <div>
                            <div class="s-name">${item.name}</div>
                            <div class="s-meta">${item.duration} • ${formatRupiah(item.price)} / ${unitLabel}</div>
                        </div>
                        <div class="s-price">${formatRupiah(lineTotal)}</div>
                    </div>
                    ${qtyControl}
                </div>
            `;

            hiddenItems.innerHTML += `
                <input type="hidden" name="items[${index}][name]" value="${item.name}">
                <input type="hidden" name="items[${index}][duration]" value="${item.duration}">
                <input type="hidden" name="items[${index}][unit]" value="${unit}">
                <input type="hidden" name="items[${index}][qty]" value="${qty}">
                <input type="hidden" name="items[${index}][price]" value="${item.price}">
            `;
        });

        updateFee();
        validateSlot();
    }

    // ============================================
    // FEE / TOTAL
    // ============================================
    let currentFee = 0;

    function updateFee() {
        const feeRow = document.getElementById('feeRow');
        const feeValue = document.getElementById('feeValue');
        const summaryTotal = document.getElementById('summaryTotal');

        const total = subtotal + currentFee;

        if (currentFee > 0) {
            feeRow.style.display = 'flex';
            feeValue.textContent = formatRupiah(currentFee);
            feeValue.classList.remove('free');
        } else {
            feeRow.style.display = 'none';
            feeValue.textContent = 'Rp 0';
        }

        summaryTotal.textContent = formatRupiah(total);
    }

    // ============================================
    // CHANGE QTY
    // ============================================
    function changeQty(index, delta) {
        if (!cart[index]) return;
        const item = cart[index];
        const currentQty = parseInt(item.qty) || 1;
        const newQty = currentQty + delta;

        if (newQty > 20) return;

        if (newQty <= 0) {
            cart.splice(index, 1);
            saveCart();
            renderSummary();
            loadPaymentMethods();
            return;
        }

        cart[index].qty = newQty;
        saveCart();
        renderSummary();
        loadPaymentMethods();
    }

    // ============================================
    // SLOT VALIDATION
    // ============================================
    const slotSelect = document.getElementById('booking_time');
    const slotInfo = document.getElementById('slotInfo');
    const btnSubmit = document.getElementById('btnSubmit');
    const dateInput = document.getElementById('booking_date');

    function validateSlot() {
        const opt = slotSelect.options[slotSelect.selectedIndex];
        if (!opt || !opt.value) {
            slotInfo.style.display = 'none';
            if (btnSubmit) btnSubmit.disabled = false;
            return;
        }

        const status = opt.dataset.status;
        const remaining = parseInt(opt.dataset.remaining) || 0;
        const needed = countSlotsNeeded();

        slotInfo.style.display = 'flex';

        if (status === 'locked') {
            slotInfo.className = 'slot-info warn';
            slotInfo.innerHTML = `<i class="fas fa-exclamation-circle"></i> Slot is not available.`;
            btnSubmit.disabled = true;
            return;
        }

        if (needed > remaining) {
            slotInfo.className = 'slot-info warn';
            slotInfo.innerHTML = `<i class="fas fa-exclamation-circle"></i> Not enough slots. ${remaining} slot(s) left.`;
            btnSubmit.disabled = true;
        } else {
            slotInfo.className = 'slot-info ok';
            slotInfo.innerHTML = `<i class="fas fa-check-circle"></i> Slot is available. ${remaining} slot(s) left.`;
            btnSubmit.disabled = false;
        }
    }

    if (slotSelect) {
        slotSelect.addEventListener('change', validateSlot);
    }

    // ============================================
    // FETCH SLOTS BY DATE
    // ============================================
    function rebuildSlotOptions(slots) {
        const currentVal = slotSelect.value;
        let optionsHtml = '<option value="" disabled selected>Select a time</option>';

        slots.forEach(function (slot) {
            const disabled = (slot.status === 'full' || slot.status === 'locked');
            const selected = (currentVal === slot.time) ? 'selected' : '';
            optionsHtml += `<option value="${slot.time}"
                                      data-status="${slot.status}"
                                      data-remaining="${slot.remaining}"
                                      ${disabled ? 'disabled' : ''}
                                      ${selected}>
                                ${slot.time} — ${slot.label}
                            </option>`;
        });

        slotSelect.innerHTML = optionsHtml;
        slotInfo.style.display = 'none';
        if (btnSubmit) btnSubmit.disabled = false;
        validateSlot();
    }

    async function loadSlotsByDate(date) {
        try {
            const res = await fetch(`{{ route('booking.slots') }}?date=${encodeURIComponent(date)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            rebuildSlotOptions(data.slots || []);
        } catch (e) {
            console.error('Failed to load slots:', e);
        }
    }

    // ============================================
    // FLATPICKR
    // ============================================
    const closedDates = @json($closedDates ?? []);

    if (typeof flatpickr !== 'undefined') {
        flatpickr('#booking_date', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            minDate: '{{ $today }}',
            maxDate: '{{ $maxDate }}',
            defaultDate: '{{ old('booking_date', $today) }}',
            disable: closedDates,
            disableMobile: true,
            monthSelectorType: 'static',
            prevArrow: '<i class="fas fa-chevron-left"></i>',
            nextArrow: '<i class="fas fa-chevron-right"></i>',
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const dateStr = fp.formatDate(dayElem.dateObj, 'Y-m-d');
                if (closedDates.includes(dateStr)) {
                    dayElem.classList.add('closed-date');
                }
            },
            onChange: function (selectedDates, dateStr) {
                if (dateStr) loadSlotsByDate(dateStr);
            }
        });
    } else {
        console.warn('Flatpickr not loaded — falling back to native date input');
        if (dateInput) {
            dateInput.addEventListener('change', function () {
                if (this.value) loadSlotsByDate(this.value);
            });
        }
    }

    // ============================================
    // LOAD PAYMENT METHODS
    // ============================================
    let paymentMethodsLoaded = false;

    async function loadPaymentMethods() {
        const container = document.getElementById('pmContainer');
        const loading = document.getElementById('pmLoading');

        if (subtotal <= 0) {
            container.innerHTML = `<div class="pm-empty">Select a treatment first to see payment methods.</div>`;
            return;
        }

        container.innerHTML = `<div class="pm-loading"><i class="fas fa-spinner fa-spin"></i> Loading payment methods...</div>`;

        try {
            const res = await fetch(`{{ route('payment.methods') }}?amount=${subtotal}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (!data.success || !data.methods || data.methods.length === 0) {
                container.innerHTML = `<div class="pm-empty"><i class="fas fa-exclamation-circle"></i> Failed to load payment methods. Please refresh the page.</div>`;
                return;
            }

            let html = '<div class="pm-list">';
            data.methods.forEach((m, i) => {
                const feeText = m.fee > 0 ? formatRupiah(m.fee) : 'Free';
                const feeClass = m.fee > 0 ? '' : 'free';
                html += `
                    <label class="pm-item" data-code="${m.code}" data-name="${m.name}" data-fee="${m.fee}">
                        <input type="radio" name="pm_choice" value="${m.code}" ${i === 0 ? '' : ''}>
                        <div class="pm-info">
                            <span class="pm-name">${m.name}</span>
                            <span class="pm-fee ${feeClass}">Fee: ${feeText}</span>
                        </div>
                    </label>
                `;
            });
            html += '</div>';
            container.innerHTML = html;

            // Bind click
            container.querySelectorAll('.pm-item').forEach(el => {
                el.addEventListener('click', function () {
                    container.querySelectorAll('.pm-item').forEach(x => x.classList.remove('selected'));
                    this.classList.add('selected');

                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;

                    const code = this.dataset.code;
                    const name = this.dataset.name;
                    const fee = parseInt(this.dataset.fee) || 0;

                    document.getElementById('selectedPaymentMethod').value = code;
                    document.getElementById('selectedPaymentMethodName').value = name;
                    document.getElementById('selectedPaymentFee').value = fee;

                    currentFee = fee;
                    updateFee();
                });
            });

            paymentMethodsLoaded = true;
        } catch (e) {
            console.error('Load payment methods error:', e);
            container.innerHTML = `<div class="pm-empty"><i class="fas fa-exclamation-circle"></i> Error loading payment methods.</div>`;
        }
    }

    // ============================================
    // INIT
    // ============================================
    renderSummary();
    if (cart.length > 0) {
        loadPaymentMethods();
    }

    // ============================================
    // SUBMIT
    // ============================================
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm && btnSubmit) {
        bookingForm.addEventListener('submit', function (e) {
            if (cart.length === 0) {
                e.preventDefault();
                alert('Your cart is empty! Please select a treatment first.');
                return;
            }
            if (btnSubmit.disabled) {
                e.preventDefault();
                return;
            }

            // Validasi: harus pilih metode bayar
            const selectedMethod = document.getElementById('selectedPaymentMethod').value;
            if (!selectedMethod) {
                e.preventDefault();
                alert('Please select a payment method first.');
                return;
            }

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        });
    }
</script>
@endpush
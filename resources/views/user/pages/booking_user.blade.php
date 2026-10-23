@extends('user.layouts.main')

@section('title', 'Book a Treatment — LeadGen Spa')
@section('meta_description', 'Complete your details to confirm your treatment booking.')
@section('footer_type', 'grid')

@push('styles')
<style>
    /* ================= PAGE TITLE ================= */
    .page-title {
        padding: 46px 0 10px;
        max-width: 1180px;
        margin: 0 auto;
        padding-left: 24px; padding-right: 24px;
        position: relative;
    }
    .back-btn {
        width: 42px; height: 42px; border-radius: 50%;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 18px rgba(0,0,0,.06);
        margin-bottom: 24px; font-size: 14px;
        color: var(--ink); transition: .25s; text-decoration: none;
    }
    .back-btn:hover { background: var(--primary); color: #fff; transform: translateX(-2px); }

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

    /* ---- section heading with icon badge ---- */
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
        position: relative;
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
    }
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

    /* ================= SUBMIT BUTTON ================= */
    .btn-confirm {
        width: 100%; color: #fff;
        background: var(--primary);
        border: none; padding: 16px; border-radius: 30px;
        font-size: 13.5px; font-weight: 700; letter-spacing: .3px;
        cursor: pointer; transition: all .25s ease;
        margin-top: 12px; font-family: 'Montserrat', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 9px;
    }
    .btn-confirm i { transition: transform .2s ease; }
    .btn-confirm:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(247,108,108,.3);
    }
    .btn-confirm:hover i { transform: translateX(3px); }
    .btn-confirm:disabled {
        background: #d9d3ce; cursor: not-allowed; transform: none; box-shadow: none;
    }

    /* ================= SUMMARY (right column) ================= */
    .summary-card-sticky { position: sticky; top: 24px; }

    .summary-empty {
        color: var(--gray); font-size: 13.5px;
        text-align: center; padding: 6px 0 2px;
    }

    /* clean list style, no repeated boxes/icons */
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
        font-size: 11.5px;
        margin-top: 10px;
        padding: 10px 14px;
        border-radius: 12px;
        display: none;
        align-items: center; gap: 8px;
    }
    .slot-info.ok {
        background: rgba(34,197,94,.08);
        color: #16a34a;
    }
    .slot-info.warn {
        background: rgba(239,68,68,.08);
        color: #dc2626;
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

    @media (max-width: 900px) {
        .booking-wrap { grid-template-columns: 1fr; }
        .row-2 { grid-template-columns: 1fr; }
        .summary-card-sticky { position: static; }
        .card { padding: 30px 24px 34px; }
    }
</style>
@endpush

@section('content')

    <div class="page-title">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="eyebrow">One step away</div>
        <h1>Book a Treatment</h1>
        <p>Complete the details below to confirm your booking.</p>
    </div>

    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
        @csrf

        <div class="booking-wrap">

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
                            <input type="date" id="booking_date" name="booking_date"
                                   value="{{ old('booking_date', $today) }}"
                                   min="{{ $today }}" required>
                        </div>
                    </div>

                    <div class="field">
                        <label for="booking_time">Time</label>
                        <div class="input-pill select-pill">
                            <i class="fas fa-clock left-icon"></i>
                            <select id="booking_time" name="booking_time" required>
                                <option value="" disabled selected>Select a time</option>
                                @foreach($slots as $slot)
                                    @php $disabled = in_array($slot['status'], ['full', 'disabled']); @endphp
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

                <div id="hiddenItems"></div>

                <button type="submit" class="btn-confirm" id="btnSubmit">
                    Confirm Booking <i class="fas fa-arrow-right"></i>
                </button>
            </div>

            <div class="card summary-card-sticky">
                <h2><span class="h-badge"><i class="fas fa-receipt"></i></span> Order Summary</h2>
                <div id="summaryContent">
                    <p class="summary-empty">Your cart is empty.</p>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span class="amount" id="summaryTotal">Rp 0</span>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
<script>
    // ===== CHAR COUNTER =====
    const notes = document.getElementById('notes');
    const charCount = document.getElementById('charCount');
    if (notes) {
        notes.addEventListener('input', () => {
            charCount.textContent = notes.value.length;
        });
    }

    // ===== FORMAT RUPIAH =====
    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    // ===== LOAD CART =====
    let cart = [];
    try { cart = JSON.parse(localStorage.getItem('spaCart')) || []; } catch (e) { cart = []; }

    // ===== COUNT SLOT NEEDED =====
    function countSlotsNeeded() {
        let total = 0;
        cart.forEach(item => {
            const unit = item.unit || 'person';
            const qty = (unit === 'couple') ? 1 : (parseInt(item.qty) || 1);
            total += (unit === 'couple') ? (qty * 2) : qty;
        });
        return total;
    }

    // ===== SAVE CART =====
    function saveCart() {
        localStorage.setItem('spaCart', JSON.stringify(cart));
    }

    // ===== RENDER SUMMARY =====
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
            return;
        }

        let total = 0;
        summaryContent.innerHTML = '';
        hiddenItems.innerHTML = '';

        cart.forEach((item, index) => {
            const unit = item.unit || 'person';
            const qty = (unit === 'couple') ? 1 : (parseInt(item.qty) || 1);
            const lineTotal = item.price * qty;
            total += lineTotal;

            const unitLabel = (unit === 'couple') ? 'package' : 'person';
            const canDecrease = qty > 1;
            const canIncrease = qty < 20;

            // Qty control — only shown for 'person' unit
            const qtyControl = (unit === 'person') ? `
                <div class="s-qty-wrap">
                    <span class="s-qty-label">Guests</span>
                    <div class="s-qty-control">
                        <button type="button"
                                onclick="changeQty(${index}, -1)"
                                ${canDecrease ? '' : 'disabled'}>
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="s-qty-value">${qty}</span>
                        <button type="button"
                                onclick="changeQty(${index}, 1)"
                                ${canIncrease ? '' : 'disabled'}>
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

        summaryTotal.textContent = formatRupiah(total);

        // Re-validate slot if one is already selected
        validateSlot();
    }

    // ===== CHANGE QTY =====
    function changeQty(index, delta) {
        if (!cart[index]) return;

        const item = cart[index];
        const currentQty = parseInt(item.qty) || 1;
        const newQty = currentQty + delta;

        if (newQty < 1 || newQty > 20) return;

        cart[index].qty = newQty;
        saveCart();
        renderSummary();
    }

    // ===== SLOT VALIDATION =====
    const slotSelect = document.getElementById('booking_time');
    const slotInfo = document.getElementById('slotInfo');
    const btnSubmit = document.getElementById('btnSubmit');

    function validateSlot() {
        const opt = slotSelect.options[slotSelect.selectedIndex];
        if (!opt || !opt.value) {
            slotInfo.style.display = 'none';
            if (btnSubmit) btnSubmit.disabled = false;
            return;
        }

        const remaining = parseInt(opt.dataset.remaining) || 0;
        const needed = countSlotsNeeded();

        slotInfo.style.display = 'flex';

        if (needed > remaining) {
            slotInfo.className = 'slot-info warn';
            slotInfo.innerHTML = `<i class="fas fa-exclamation-circle"></i> Not enough slots. Need <b>${needed}</b>, only <b>${remaining}</b> left. Reduce guests or pick another time.`;
            btnSubmit.disabled = true;
        } else {
            slotInfo.className = 'slot-info ok';
            slotInfo.innerHTML = `<i class="fas fa-check-circle"></i> Slots available. Need <b>${needed}</b>, <b>${remaining}</b> remaining.`;
            btnSubmit.disabled = false;
        }
    }

    if (slotSelect) {
        slotSelect.addEventListener('change', validateSlot);
    }

    // ===== INIT =====
    renderSummary();

    // ===== SUBMIT =====
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

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = 'Processing...';

            setTimeout(() => {
                localStorage.removeItem('spaCart');
            }, 1500);
        });
    }
</script>
@endpush

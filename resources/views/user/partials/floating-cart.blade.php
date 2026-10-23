{{-- FLOATING CART — cuma di Treatments & Packages --}}
<div class="floating-cart" id="floatingCart" onclick="toggleCartDropdown(event)">
    <i class="fas fa-shopping-bag"></i>
    <span class="badge" id="cartBadge">0</span>
</div>

<div class="cart-dropdown" id="cartDropdown" onclick="event.stopPropagation()">
    <div class="cart-header">
        <h4>Shopping Cart</h4>
        <button type="button" class="cart-close" onclick="closeCartDropdown()" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="cart-body" id="cartItemsList"></div>
    <div class="cart-total" id="cartTotal">Total: <span>Rp 0</span></div>
    <a href="{{ url('booking') }}" class="btn-booking-cart">
        Continue Booking <i class="fas fa-arrow-right"></i>
    </a>
</div>

<style>
    /* ===== FLOATING BUTTON ===== */
    .floating-cart {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 9999;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(247, 108, 108, 0.4);
        transition: all 0.3s ease;
    }
    .floating-cart:hover {
        background: var(--primary-dark);
        transform: scale(1.08);
    }

    /* ===== BADGE — coral-maroon pill ===== */
    .floating-cart .badge {
        position: absolute;
        top: -6px;
        right: -6px;
        min-width: 22px;
        height: 22px;
        padding: 0 7px;
        border-radius: 11px;
        background: linear-gradient(135deg, #f76c6c 0%, #a3141f 100%);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        line-height: 22px;
        text-align: center;
        border: 2px solid #fff;
        box-sizing: border-box;
        box-shadow: 0 4px 10px rgba(247, 108, 108, 0.5);
        letter-spacing: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .floating-cart .badge:empty,
    .floating-cart .badge[data-count="0"] {
        display: none;
    }

    /* ===== DROPDOWN ===== */
    .cart-dropdown {
        display: none;
        position: fixed;
        bottom: 100px;
        right: 25px;
        width: 380px;
        max-width: calc(100vw - 40px);
        max-height: 70vh;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        flex-direction: column;
        overflow: hidden;
    }
    .cart-dropdown.show {
        display: flex;
    }

    /* HEADER */
    .cart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px 14px;
        border-bottom: 1px solid var(--line, #e7e2df);
        flex: none;
    }
    .cart-header h4 {
        font-size: 15px;
        font-weight: 800;
        margin: 0;
        color: var(--ink);
    }
    .cart-close {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: var(--gray);
        font-size: 14px;
        cursor: pointer;
        transition: .2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cart-close:hover {
        background: rgba(239, 68, 68, .1);
        color: #ef4444;
    }

    /* BODY (scrollable) */
    .cart-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px 22px;
        max-height: 45vh;
    }

    /* ITEM */
    .cart-dropdown .cart-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px dashed var(--line, #e7e2df);
    }
    .cart-dropdown .cart-item:last-child {
        border-bottom: none;
    }
    .cart-dropdown .cart-item img {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        object-fit: cover;
        flex: none;
        background: #f8fafc;
    }
    .cart-item-info { flex: 1; min-width: 0; }
    .cart-item-info h5 {
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 3px;
        line-height: 1.3;
        color: var(--ink);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .cart-item-info p {
        font-size: 11.5px;
        color: var(--gray);
        margin: 0 0 5px;
        line-height: 1.4;
    }
    .cart-item-info .price {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--primary-dark);
    }

    /* QTY CONTROL */
    .cart-qty-control {
        display: flex;
        align-items: center;
        gap: 0;
        background: var(--bg-cream, #f4f1ef);
        border-radius: 30px;
        padding: 3px;
        flex: none;
    }
    .cart-qty-control button {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: var(--gray);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
        line-height: 1;
        padding: 0;
    }
    .cart-qty-control button:hover {
        background: #fff;
        color: var(--primary-dark);
    }
    .cart-qty-control span {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink);
        min-width: 20px;
        text-align: center;
    }

    /* REMOVE */
    .remove-item {
        flex: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #e74c3c;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
    }
    .remove-item:hover {
        background: rgba(231, 76, 60, 0.1);
    }

    /* TOTAL */
    .cart-total {
        font-size: 15px;
        font-weight: 800;
        padding: 14px 22px;
        border-top: 1px solid var(--line, #e7e2df);
        text-align: right;
        flex: none;
        background: #fbfcfe;
    }
    .cart-total span { color: var(--primary-dark); }

    /* BUTTON */
    .btn-booking-cart {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--primary);
        color: #fff;
        padding: 14px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s ease;
        flex: none;
        letter-spacing: .5px;
    }
    .btn-booking-cart:hover {
        background: var(--primary-dark);
    }

    .cart-empty-msg {
        font-size: 13px;
        color: #888;
        text-align: center;
        padding: 30px 0;
    }

    @media (max-width: 600px) {
        .cart-dropdown { right: 20px; left: 20px; width: auto; }
    }
</style>
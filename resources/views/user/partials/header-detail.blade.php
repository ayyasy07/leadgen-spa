<header class="header-detail">
    <div class="container header-inner">
        <div class="logo-wrapper">
            {{-- Tombol Back --}}
            <a href="{{ $backUrl ?? url('/') }}" class="back-btn" title="Back">
                <i class="fas fa-arrow-left"></i>
            </a>

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="logo">
                <i class="fas fa-spa"></i> LeadGen
            </a>
        </div>

        <div class="nav-center">
            <nav>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ url('/#about') }}">About</a></li>

                    <li class="nav-dropdown">
                        <a href="{{ url('/#treatments') }}">Treatments <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 4px;"></i></a>
                        <div class="nav-dropdown-content">
                            <a href="{{ url('treatments') }}">All Treatments</a>
                            <a href="{{ url('packages') }}">Special Packages</a>
                        </div>
                    </li>

                    <li><a href="{{ url('/#gallery') }}">Gallery</a></li>
                    <li><a href="{{ url('blog') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
                    <li><a href="{{ url('contact') }}">Contact</a></li>
                </ul>
            </nav>
        </div>

        <div class="header-icons">
            {{-- CART ICON (KHUSUS HEADER DETAIL) --}}
            <div class="header-cart-wrap">
                <div class="header-cart" onclick="toggleCartDropdown(event)">
                    <i class="fas fa-shopping-cart"></i>
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
            </div>

            {{-- HAMBURGER DROPDOWN --}}
            <div class="dropdown">
                <a href="#" class="dropbtn"><i class="fas fa-bars"></i></a>
                <div class="dropdown-content" id="dropdownMenu">

                    @guest
                        {{-- Belum login --}}
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register.index') }}">Register</a>
                    @endguest

                    @auth
                        @if(auth()->user()->isAdmin())
                            {{-- Admin --}}
                            <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
                            <a href="{{ route('admin.logout') }}" id="logoutBtn">Logout</a>
                        @else
                            {{-- Customer --}}
                            <a href="{{ url('profile') }}">Profile</a>
                            <a href="{{ url('history') }}">History</a>
                            <a href="{{ route('logout') }}" id="logoutBtn">Logout</a>
                        @endif
                    @endauth

                </div>
            </div>
        </div>
    </div>
</header>

<style>

    /* CSS CART UNTUK HEADER DETAIL */
    .header-cart-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        margin-right: 8px;
    }

    .header-cart {
        position: relative;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #332f2e;
        font-size: 17px;
        transition: color .2s, background .2s;
    }

    .header-cart:hover {
        color: var(--primary-dark);
        background: rgba(0, 0, 0, .03);
    }

    .header-cart .badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        border-radius: 99px;
        background: var(--primary);
        color: #fff;
        font-size: 9.5px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--bg-cream);
        pointer-events: none;
    }

    .cart-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 320px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
        display: none;
        flex-direction: column;
        z-index: 9999;
        overflow: hidden;
    }

    .cart-dropdown.active {
        display: flex;
    }

    .cart-header {
        padding: 16px 18px;
        border-bottom: 1px solid var(--line);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
    }

    .cart-close {
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
        color: var(--gray);
    }

    .cart-close:hover {
        color: var(--primary);
    }

    .cart-body {
        max-height: 250px;
        overflow-y: auto;
        padding: 10px 18px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-info strong {
        display: block;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .cart-item-info span {
        font-size: 11px;
        color: var(--gray);
    }

    .cart-item-remove {
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        font-size: 14px;
    }

    .cart-empty {
        text-align: center;
        padding: 20px 0;
        color: var(--gray);
        font-size: 13px;
    }

    .cart-total {
        padding: 16px 18px;
        border-top: 1px solid var(--line);
        font-weight: 700;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-booking-cart {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 18px 18px;
        padding: 12px;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        transition: .25s;
    }

    .btn-booking-cart:hover {
        background: var(--primary-dark);
    }

</style>
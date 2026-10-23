<header>
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="logo">
            <i class="fas fa-spa"></i> LeadGen
        </a>

        <div class="nav-center">
            <nav>
                <ul>
                    <li>
                        <a href="{{ route('home') }}"
                           class="{{ request()->routeIs('home') ? 'active' : '' }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}"
                           class="{{ request()->routeIs('about') ? 'active' : '' }}">
                            About
                        </a>
                    </li>

                    <li class="nav-dropdown">
                        <a href="{{ route('treatments.index') }}"
                           class="{{ request()->routeIs('treatments.*') ? 'active' : '' }}">
                            Treatments
                            <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 4px;"></i>
                        </a>
                        <div class="nav-dropdown-content">
                            <a href="{{ route('treatments.index') }}">All Treatments</a>
                            <a href="{{ route('packages.index') }}">Special Packages</a>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('gallery.index') }}"
                           class="{{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                            Gallery
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.index') }}"
                           class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">
                            Blog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact.index') }}"
                           class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">
                            Contact
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="header-icons">
            @php
                $routeName = \Route::currentRouteName();

                $hideBookNowRoutes = [
                    'treatments.index', 'treatments.show', 'packages.index',
                    'booking.index', 'booking.store', 'booking.slots',
                    'booking.success', 'payment.create',
                ];
                $hideBookNow = in_array($routeName, $hideBookNowRoutes);

                $showCartInHeader = in_array($routeName, [
                    'treatments.index', 'treatments.show', 'packages.index',
                ]);

                $pendingCount = 0;
                if (auth()->check()) {
                    $pendingCount = \App\Models\Booking::where('user_id', auth()->id())
                        ->where('status', \App\Models\Booking::STATUS_PENDING)
                        ->where(function ($q) {
                            $q->whereNull('expired_at')
                              ->orWhere('expired_at', '>', now());
                        })
                        ->count();
                }
            @endphp

            @unless($hideBookNow)
                <a href="{{ url('booking') }}" class="btn-book-now-header">
                    Book Now
                </a>
            @endunless

            @if($showCartInHeader)
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
            @endif

            {{-- Dropdown hamburger --}}
            <div class="dropdown">
                <a href="#" class="dropbtn">
                    <i class="fas fa-bars"></i>
                    @auth
                        @if($pendingCount > 0)
                            <span class="pending-badge">{{ $pendingCount }}</span>
                        @endif
                    @endauth
                </a>
                <div class="dropdown-content" id="dropdownMenu">

                    @guest
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register.index') }}">Register</a>
                    @endguest

                    @auth
                        <a href="{{ url('history') }}" class="dropdown-history-link">
                            <span>History</span>
                            @if($pendingCount > 0)
                                <span class="dropdown-pending-count">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a href="{{ url('profile') }}">Profile</a>
                        <a href="{{ route('logout') }}" id="logoutBtn">Logout</a>
                    @endauth

                </div>
            </div>
        </div>
    </div>
</header>

<style>
    /* ============================================
       HAMBURGER ICON + BADGE (konsisten sama cart)
    ============================================ */
    .dropdown {
        position: relative;
        display: inline-flex;
        align-items: center;
    }
    .dropdown .dropbtn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: #332f2e;
        font-size: 16px;
        line-height: 1;
        padding: 0;
        text-decoration: none;
        transition: color .2s, background .2s;
    }
    .dropdown .dropbtn:hover {
        color: var(--primary-dark);
        background: rgba(0, 0, 0, .03);
    }
    .dropdown .dropbtn > i.fa-bars,
    .dropdown .dropbtn > i.fas {
        font-size: 16px;
        line-height: 1;
        display: block;
        color: inherit;
    }

    /* Badge merah — pojok kanan atas icon, TIDAK nutupin icon */
    .dropdown .pending-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        border-radius: 99px;
        background: #ef4444;
        color: #fff;
        font-size: 9.5px;
        font-weight: 800;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--bg-cream, #f4f1ef);
        box-sizing: content-box;
        pointer-events: none;
        z-index: 2;
    }

    /* ============================================
       LINK HISTORY DI DROPDOWN
    ============================================ */
    .dropdown-content .dropdown-history-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .dropdown-content .dropdown-pending-count {
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 99px;
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: none;
    }
</style>
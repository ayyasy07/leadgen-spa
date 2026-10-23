<header class="header-back">
    <div class="container header-back-inner">
        <a href="{{ $backUrl ?? url('treatments') }}" class="header-back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="header-back-right">
            <a href="{{ route('home') }}" class="header-back-logo">
                <i class="fas fa-spa"></i> LeadGen
            </a>

            <div class="dropdown">
                <a href="#" class="dropbtn" onclick="toggleHB(event)"><i class="fas fa-bars"></i></a>
                <div class="dropdown-content" id="hbDropdown">
                    @guest
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register.index') }}">Register</a>
                    @endguest
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
                            <a href="{{ route('admin.logout') }}">Logout</a>
                        @else
                            <a href="{{ url('profile') }}">Profile</a>
                            <a href="{{ url('history') }}">History</a>
                            <a href="{{ route('logout') }}">Logout</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    .header-back {
        background: var(--bg-cream, #f4f1ef);
        border-bottom: 1px solid var(--line, #e7e2df);
        padding: 18px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    .header-back-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .header-back-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(163, 20, 31, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: var(--maroon, #a3141f);
        transition: .3s;
        font-size: 16px;
    }
    .header-back-btn:hover {
        background: var(--maroon, #a3141f);
        color: #fff;
        transform: translateX(-2px);
    }
    .header-back-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .header-back-logo {
        font-size: 22px;
        font-weight: 700;
        color: var(--maroon, #a3141f);
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .header-back-logo i {
        font-size: 20px;
        color: var(--maroon, #a3141f);
    }
    .header-back .dropbtn {
        color: #332f2e;
        font-size: 16px;
        text-decoration: none;
    }
    .header-back .dropbtn:hover {
        color: var(--primary-dark, #ea5252);
    }
</style>

<script>
    function toggleHB(e) {
        e.preventDefault();
        e.stopPropagation();
        var dd = document.getElementById('hbDropdown');
        if (!dd) return;
        dd.classList.toggle('show');
    }

    // Tutup dropdown kalo klik di luar — attach SEKALI aja
    document.addEventListener('click', function(e) {
        var dd = document.getElementById('hbDropdown');
        if (!dd) return;
        if (!e.target.closest('.header-back .dropdown')) {
            dd.classList.remove('show');
        }
    });
</script>
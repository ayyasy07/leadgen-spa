@extends('../layout/main')

@section('head')
    @yield('subhead')
@endsection

@section('content')
    @include('../layout/components/mobile-menu')
    @include('../layout/components/top-bar')
    <div class="wrapper">
        <div class="wrapper-box">
            <!-- BEGIN: Side Menu -->
            <nav class="side-nav" id="customSideNav">
                <ul>
                    @foreach ($side_menu as $menuKey => $menu)
                        @if ($menu == 'devider')
                            <li class="side-nav__devider my-6"></li>
                        @else
                            <li>
                                <a href="{{ isset($menu['route_name']) ? route($menu['route_name'], $menu['params']) : 'javascript:;' }}"
                                   class="{{ $first_level_active_index == $menuKey ? 'side-menu side-menu--active' : 'side-menu' }}"
                                   @if(isset($menu['sub_menu'])) data-toggle="submenu" @endif>
                                    <div class="side-menu__icon">
                                        <i data-feather="{{ $menu['icon'] }}"></i>
                                    </div>
                                    <div class="side-menu__title">
                                        {{ $menu['title'] }}
                                        @if (isset($menu['sub_menu']))
                                            <div class="side-menu__sub-icon {{ $first_level_active_index == $menuKey ? 'transform rotate-180' : '' }}">
                                                <i data-feather="chevron-down"></i>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                @if (isset($menu['sub_menu']))
                                    <ul class="{{ $first_level_active_index == $menuKey ? 'side-menu__sub-open' : '' }}">
                                        @foreach ($menu['sub_menu'] as $subMenuKey => $subMenu)
                                            <li>
                                                <a href="{{ isset($subMenu['route_name']) ? route($subMenu['route_name'], $subMenu['params']) : 'javascript:;' }}"
                                                   class="{{ $second_level_active_index == $subMenuKey ? 'side-menu side-menu--active' : 'side-menu' }}">
                                                    <div class="side-menu__icon">
                                                        <i data-feather="{{ $subMenu['icon'] ?? 'activity' }}"></i>
                                                    </div>
                                                    <div class="side-menu__title">
                                                        {{ $subMenu['title'] }}
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            <div class="content">
                @yield('subcontent')
            </div>
            <!-- END: Content -->
        </div>
    </div>
@endsection

@push('style')
<style>
    /* ===== SMOOTH SUB-MENU ANIMATION ===== */
    .side-nav .side-menu__sub {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition:
            max-height .35s cubic-bezier(.4,0,.2,1),
            opacity .25s ease,
            padding .3s ease;
        padding-top: 0;
        padding-bottom: 0;
    }

    .side-nav .side-menu__sub.side-menu__sub-open {
        max-height: 500px;
        opacity: 1;
        padding-top: 4px;
        padding-bottom: 4px;
    }

    /* ===== CHEVRON ROTATION ===== */
    .side-menu__sub-icon {
        transition: transform .3s cubic-bezier(.4,0,.2,1);
    }

    .side-menu__sub-icon.transform.rotate-180 {
        transform: rotate(180deg);
    }

    /* ===== HOVER & ACTIVE SMOOTH ===== */
    .side-nav .side-menu {
        transition: background-color .2s ease, color .2s ease, padding .2s ease;
    }

    .side-nav .side-menu .side-menu__icon,
    .side-nav .side-menu .side-menu__title {
        transition: color .2s ease, opacity .2s ease;
    }

    /* ===== PREVENT FLICKER ===== */
    .side-nav .side-menu__sub li {
        will-change: opacity;
    }
</style>
@endpush

@push('script')
<script>
(function () {
    'use strict';

    function renderFeather() {
        if (typeof feather !== 'undefined' && feather.replace) {
            try { feather.replace({ 'stroke-width': 1.5 }); } catch (e) {}
        }
    }

    function initSubmenu() {
        var links = document.querySelectorAll('#customSideNav [data-toggle="submenu"]');
        if (!links.length) return;

        links.forEach(function (link) {
            if (link.dataset.bound === '1') return;
            link.dataset.bound = '1';

            link.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var li = this.closest('li');
                if (!li) return;

                var submenu = li.querySelector('.side-menu__sub');
                var icon = this.querySelector('.side-menu__sub-icon');
                if (!submenu) return;

                var isOpen = submenu.classList.contains('side-menu__sub-open');

                // Tutup semua submenu lain (accordion behavior)
                document.querySelectorAll('#customSideNav .side-menu__sub.side-menu__sub-open').forEach(function (el) {
                    if (el !== submenu) {
                        el.classList.remove('side-menu__sub-open');
                        var otherIcon = el.parentElement.querySelector('.side-menu__sub-icon');
                        if (otherIcon) otherIcon.classList.remove('rotate-180', 'transform');
                    }
                });

                if (isOpen) {
                    submenu.classList.remove('side-menu__sub-open');
                    if (icon) icon.classList.remove('rotate-180', 'transform');
                } else {
                    submenu.classList.add('side-menu__sub-open');
                    if (icon) icon.classList.add('rotate-180', 'transform');
                }

                setTimeout(renderFeather, 350);
            }, false);
        });
    }

    function initTippy() {
        if (typeof tippy === 'undefined') return false;

        var els = document.querySelectorAll('.side-nav .side-menu');
        if (!els.length) return false;

        els.forEach(function (el) {
            if (el._tippy) el._tippy.destroy();
            el._tippy = null;

            var t = el.querySelector('.side-menu__title');
            if (!t) return;

            var c = t.cloneNode(true);
            var s = c.querySelector('.side-menu__sub-icon');
            if (s) s.remove();

            var content = c.textContent.trim();
            if (!content) return;

            tippy(el, {
                content: content,
                arrow: tippy.roundArrow,
                animation: 'shift-away',
                placement: 'right',
                theme: 'dark',
                delay: [100, 0],
                duration: [200, 150]
            });
        });

        return true;
    }

    function toggleTippy() {
        document.querySelectorAll('.side-nav .side-menu').forEach(function (el) {
            if (!el._tippy) return;
            el._tippy.enable();
        });
    }

    function init() {
        initSubmenu();

        if (!initTippy()) {
            var r = 0, i = setInterval(function () {
                r++;
                if (initTippy() || r > 20) clearInterval(i);
            }, 200);
        }
        toggleTippy();
        renderFeather();
    }

    setTimeout(init, 100);
    setTimeout(init, 300);
    setTimeout(renderFeather, 500);
    setTimeout(renderFeather, 1000);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { setTimeout(init, 100); });
    }

    window.addEventListener('load', function () {
        setTimeout(function () {
            initSubmenu();
            renderFeather();
        }, 200);
    });
})();
</script>
@endpush
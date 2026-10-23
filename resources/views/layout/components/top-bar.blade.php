<!-- BEGIN: Top Bar -->
<style>
    /* ===== Spacing ===== */
    .top-bar-boxed{
        margin-top: 0 !important;
        padding-top: 14px;
        padding-bottom: 14px;
        height: auto !important;
        min-height: 70px;
    }

    /* ===== Account / profile icon ===== */
    .np-account{position:relative}
    .np-account-btn{
        width:38px;height:38px;border-radius:9999px;
        display:flex;align-items:center;justify-content:center;
        background:rgba(255,255,255,.14);
        border:1px solid rgba(255,255,255,.22);
        color:#fff;cursor:pointer;
        transition:background .15s ease, transform .15s ease;
    }
    .np-account-btn:hover{background:rgba(255,255,255,.24)}
    .np-account-btn:active{transform:scale(.96)}
    .np-account-btn svg,
    .np-account-btn i{width:17px;height:17px;font-size:15px}

    .np-account-menu{
        position:absolute;top:calc(100% + 10px);right:0;
        width:230px;background:#fff;border-radius:12px;
        box-shadow:0 15px 40px rgba(15,23,42,.18);
        border:1px solid rgba(148,163,184,.18);
        padding:6px;z-index:60;
        opacity:0;visibility:hidden;transform:translateY(-6px);
        transition:opacity .16s ease, transform .16s ease, visibility .16s;
    }
    .np-account.open .np-account-menu{opacity:1;visibility:visible;transform:translateY(0)}

    .np-account-user{padding:10px 12px 12px;border-bottom:1px solid rgba(148,163,184,.16);margin-bottom:6px}
    .np-account-user .nm{font-size:13.5px;font-weight:600;color:#1e293b}
    .np-account-user .em{font-size:11.5px;color:#94a3b8;margin-top:2px;word-break:break-all}

    .np-account-item{
        display:flex;align-items:center;gap:10px;
        padding:9px 12px;border-radius:8px;
        font-size:13px;font-weight:500;color:#475569;
        text-decoration:none;transition:background .15s ease,color .15s ease;
    }
    .np-account-item:hover{background:#f1f5f9;color:#1e293b}
    .np-account-item svg{width:15px;height:15px;flex:none}
    .np-account-item.danger{color:#ef4444}
    .np-account-item.danger:hover{background:rgba(239,68,68,.08);color:#ef4444}
    .np-account-sep{height:1px;background:rgba(148,163,184,.16);margin:6px 0}

    /* ===== Notification ===== */
    .np-notif{position:relative}
    .np-notif-btn{
        width:38px;height:38px;border-radius:9999px;
        display:flex;align-items:center;justify-content:center;
        color:rgba(255,255,255,.85);cursor:pointer;
        transition:background .15s ease;
        position:relative;
    }
    .np-notif-btn:hover{background:rgba(255,255,255,.12)}
    .np-notif-btn svg{width:18px;height:18px}

    /* Badge merah */
    .np-notif-badge{
        position:absolute;top:2px;right:2px;
        min-width:17px;height:17px;padding:0 4px;
        border-radius:9999px;
        background:#ef4444;color:#fff;
        font-size:10px;font-weight:700;line-height:17px;
        text-align:center;
        box-shadow:0 0 0 2px rgba(15,23,42,.35);
        display:none;
    }
    .np-notif-badge.show{display:inline-block}

    .np-notif-menu{
        position:absolute;top:calc(100% + 10px);right:0;
        width:340px;max-width:calc(100vw - 30px);
        background:#fff;border-radius:12px;
        box-shadow:0 15px 40px rgba(15,23,42,.18);
        border:1px solid rgba(148,163,184,.18);
        z-index:60;overflow:hidden;
        opacity:0;visibility:hidden;transform:translateY(-6px);
        transition:opacity .16s ease, transform .16s ease, visibility .16s;
    }
    .np-notif.open .np-notif-menu{opacity:1;visibility:visible;transform:translateY(0)}

    .np-notif-head{
        display:flex;align-items:center;justify-content:space-between;
        padding:12px 14px;
        border-bottom:1px solid rgba(148,163,184,.16);
    }
    .np-notif-head .ttl{font-size:13px;font-weight:700;color:#1e293b}
    .np-notif-close{
        width:26px;height:26px;border-radius:7px;
        display:flex;align-items:center;justify-content:center;
        color:#94a3b8;cursor:pointer;background:transparent;border:none;
        transition:background .15s,color .15s;
    }
    .np-notif-close:hover{background:#f1f5f9;color:#475569}
    .np-notif-close svg{width:15px;height:15px}

    .np-notif-list{
        max-height:360px;overflow-y:auto;
        padding:6px;
    }
    .np-notif-item{
        display:flex;align-items:flex-start;gap:11px;
        padding:10px 11px;border-radius:9px;
        text-decoration:none;cursor:pointer;
        transition:background .15s ease;
        position:relative;
    }
    .np-notif-item:hover{background:#f8fafc}
    .np-notif-item .ic{
        width:34px;height:34px;border-radius:9px;flex:none;
        display:flex;align-items:center;justify-content:center;
    }
    .np-notif-item .ic svg{width:16px;height:16px}
    .np-notif-item .ic.tone-primary{background:rgba(59,130,246,.12);color:#2563eb}
    .np-notif-item .ic.tone-danger{background:rgba(239,68,68,.12);color:#dc2626}
    .np-notif-item .ic.tone-warning{background:rgba(245,158,11,.14);color:#d97706}
    .np-notif-item .ic.tone-success{background:rgba(34,197,94,.12);color:#16a34a}

    .np-notif-item .body{flex:1;min-width:0}
    .np-notif-item .tt{
        font-size:12.5px;font-weight:600;color:#1e293b;
        line-height:1.35;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
        overflow:hidden;
    }
    .np-notif-item .st{
        font-size:11.5px;color:#94a3b8;margin-top:2px;
        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .np-notif-item .dot{
        width:8px;height:8px;border-radius:50%;
        background:#3b82f6;flex:none;margin-top:6px;
    }

    .np-notif-empty{
        padding:30px 16px;
        text-align:center;font-size:12.5px;color:#94a3b8;
        display:flex;flex-direction:column;align-items:center;gap:8px;
    }
    .np-notif-empty svg{width:26px;height:26px;opacity:.5}

    .np-notif-foot{
        padding:10px 14px;border-top:1px solid rgba(148,163,184,.16);
        text-align:center;
    }
    .np-notif-foot a{
        font-size:12px;font-weight:600;color:#3b82f6;
        text-decoration:none;
    }
    .np-notif-foot a:hover{text-decoration:underline}

    /* ===== Toast Notif ===== */
    .np-toast-wrap{
        position:fixed;bottom:20px;right:20px;
        z-index:9999;
        display:flex;flex-direction:column;gap:10px;
        pointer-events:none;
        max-width:380px;
    }
    .np-toast{
        pointer-events:auto;
        display:flex;align-items:flex-start;gap:11px;
        padding:12px 14px;border-radius:11px;
        box-shadow:0 10px 30px rgba(15,23,42,.15);
        border:1px solid transparent;
        font-size:12.5px;
        opacity:0;transform:translateX(20px);
        transition:opacity .25s ease, transform .25s ease;
        cursor:pointer;
    }
    .np-toast.show{opacity:1;transform:translateX(0)}
    .np-toast .ic{flex:none;margin-top:1px}
    .np-toast .ic svg{width:17px;height:17px}
    .np-toast .body{flex:1;min-width:0}
    .np-toast .tt{font-weight:700;line-height:1.35}
    .np-toast .st{font-size:11.5px;opacity:.8;margin-top:2px}
    .np-toast .cls{
        flex:none;background:transparent;border:none;cursor:pointer;
        color:currentColor;opacity:.6;padding:0;margin-left:4px;
        transition:opacity .15s;
    }
    .np-toast .cls:hover{opacity:1}
    .np-toast .cls svg{width:14px;height:14px}

    .np-toast.tone-primary{background:rgba(59,130,246,.10);color:#1d4ed8;border-color:rgba(59,130,246,.28)}
    .np-toast.tone-danger{background:rgba(239,68,68,.09);color:#b91c1c;border-color:rgba(239,68,68,.28)}
    .np-toast.tone-warning{background:rgba(245,158,11,.11);color:#b45309;border-color:rgba(245,158,11,.28)}
    .np-toast.tone-success{background:rgba(34,197,94,.10);color:#15803d;border-color:rgba(34,197,94,.28)}

    /* ===== Dark Mode ===== */
    .dark .np-notif-menu{background:rgb(var(--color-darkmode-600));border-color:rgb(var(--color-darkmode-400))}
    .dark .np-notif-head{border-bottom-color:rgb(var(--color-darkmode-400)/.6)}
    .dark .np-notif-head .ttl{color:rgb(var(--color-slate-200))}
    .dark .np-notif-close:hover{background:rgb(var(--color-darkmode-700));color:rgb(var(--color-slate-300))}
    .dark .np-notif-item:hover{background:rgb(var(--color-darkmode-700))}
    .dark .np-notif-item .tt{color:rgb(var(--color-slate-200))}
    .dark .np-notif-item .st{color:rgb(var(--color-slate-400))}
    .dark .np-notif-empty{color:rgb(var(--color-slate-400))}
    .dark .np-notif-foot{border-top-color:rgb(var(--color-darkmode-400)/.6)}
    .dark .np-notif-foot a{color:rgb(var(--color-primary))}
    
    /* ===== Search ===== */
    .np-search{position:relative}
    .np-search input{
        width:240px;height:38px;
        border-radius:9999px;
        border:1px solid rgba(255,255,255,.22);
        background:rgba(255,255,255,.12);
        color:#fff;font-size:13px;
        padding:0 16px 0 40px;outline:none;
        transition:width .2s ease, background .15s ease, border-color .15s ease;
    }
    .np-search input::placeholder{color:rgba(255,255,255,.6)}
    .np-search input:focus{width:300px;background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.4)}
    .np-search .np-search-icon{
        position:absolute;left:14px;top:50%;transform:translateY(-50%);
        width:16px;height:16px;color:rgba(255,255,255,.7);pointer-events:none;
    }

    .np-search-result{
        position:absolute;top:calc(100% + 10px);right:0;
        width:380px;max-height:440px;overflow-y:auto;
        background:#fff;border-radius:12px;
        box-shadow:0 15px 40px rgba(15,23,42,.18);
        border:1px solid rgba(148,163,184,.18);
        padding:14px;z-index:60;display:none;
    }
    .np-search-result.show{display:block}

    .np-result-item{
        display:flex;align-items:center;gap:12px;
        padding:9px 10px;border-radius:9px;text-decoration:none;
        transition:background .15s ease;
    }
    .np-result-item:hover{background:#f1f5f9}
    .np-result-item .ic{
        width:32px;height:32px;border-radius:8px;flex:none;
        display:flex;align-items:center;justify-content:center;
        background:rgba(59,130,246,.1);color:#3b82f6;font-size:14px;
        overflow:hidden;
    }
    .np-result-item .ic svg{width:15px;height:15px}
    .np-result-item .np-result-content{min-width:0;flex:1}
    .np-result-item .tt{font-size:13px;font-weight:600;color:#1e293b;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .np-result-item .st{font-size:11.5px;color:#94a3b8;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .np-result-empty{font-size:12.5px;color:#94a3b8;padding:6px 2px}

    /* ===== Group Header ===== */
    .np-search-group { margin-bottom: 14px; }
    .np-search-group:last-child { margin-bottom: 0; }
    .np-search-group__title {
        display: flex; align-items: center; gap: 6px;
        font-size: 10.5px; font-weight: 700;
        letter-spacing: .6px; text-transform: uppercase;
        color: #94a3b8;
        padding: 4px 10px 8px;
        border-bottom: 1px dashed rgba(148,163,184,.18);
        margin-bottom: 4px;
    }
    .np-search-group__title svg { width: 12px; height: 12px; }
    .np-search-group__count {
        margin-left: auto;
        font-size: 10px; font-weight: 700;
        background: rgba(148,163,184,.15);
        color: #64748b;
        padding: 2px 7px;
        border-radius: 999px;
        letter-spacing: 0;
    }

    @media (max-width: 767px){
        .np-search input{width:170px}
        .np-search input:focus{width:200px}
        .np-search-result{width:300px}
    }
</style>

<div class="top-bar-boxed z-[51] relative border-b border-white/[0.08] -mx-3 sm:-mx-8 px-3 sm:px-8 mb-10">
    <div class="h-full flex items-center">

        <!-- BEGIN: Logo -->
        <a href="{{ route('admin.dashboard') }}" class="-intro-x hidden md:flex items-center">
            <img alt="Icewall" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
            <span class="text-white text-lg ml-3">Icewall</span>
        </a>
        <!-- END: Logo -->

        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto flex items-center">
            <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @php
                        $routeName = request()->route()?->getName() ?? '';
                        $parts = explode('.', $routeName);
                        $label = 'Dashboard';
                        if (count($parts) >= 2 && $parts[0] === 'admin') {
                            $label = ucfirst(str_replace('-', ' ', $parts[1]));
                        }
                    @endphp
                    {{ $breadcrumb ?? $label }}
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Search -->
        <div class="intro-x np-search mr-3 sm:mr-4 hidden sm:block">
            <i data-feather="search" class="np-search-icon"></i>
            <input type="text"
                   id="adminGlobalSearch"
                   placeholder="Cari treatments, blog, gallery..."
                   autocomplete="off">

            <div class="np-search-result" id="searchResultBox">
                <div id="searchResultItems"></div>
            </div>
        </div>
        <!-- END: Search -->

        <!-- BEGIN: Notifications -->
        <div class="intro-x np-notif mr-2 sm:mr-3" id="npNotif">
            <div class="np-notif-btn" id="npNotifBtn" role="button" aria-expanded="false">
                <i data-feather="bell"></i>
                <span class="np-notif-badge" id="npNotifBadge"></span>
            </div>

            <div class="np-notif-menu">
                <div class="np-notif-head">
                    <span class="ttl">Notifications</span>
                    <button type="button" class="np-notif-close" id="npNotifClose" title="Tutup">
                        <i data-feather="minus"></i>
                    </button>
                </div>

                <div class="np-notif-list" id="npNotifList">
                    <div class="np-notif-empty">
                        <i data-feather="bell-off"></i>
                        <span>Memuat notifikasi...</span>
                    </div>
                </div>

                <div class="np-notif-foot">
                    <a href="{{ route('admin.dashboard') }}">Lihat semua →</a>
                </div>
            </div>
        </div>
        <!-- END: Notifications -->

        <!-- BEGIN: Toast Notif Wrap -->
        <div class="np-toast-wrap" id="npToastWrap"></div>
        <!-- END: Toast Notif Wrap -->

        <!-- BEGIN: Account Menu -->
        <div class="intro-x np-account" id="npAccount">
            <div class="np-account-btn" id="npAccountBtn" role="button" aria-expanded="false" title="Akun">
                <i data-feather="user"></i>
            </div>

            <div class="np-account-menu">
                <div class="np-account-user">
                    <div class="nm">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="em">{{ auth()->user()->email ?? '' }}</div>
                </div>

                <a href="{{ route('admin.dashboard') }}" class="np-account-item">
                    <i data-feather="home"></i> Dashboard
                </a>
                <a href="{{ route('admin.settings.index') }}" class="np-account-item">
                    <i data-feather="settings"></i> Settings
                </a>
                <a href="{{ route('home') }}" target="_blank" class="np-account-item">
                    <i data-feather="external-link"></i> Lihat Website
                </a>

                <div class="np-account-sep"></div>

                <a href="{{ route('admin.logout') }}" class="np-account-item danger">
                    <i data-feather="log-out"></i> Logout
                </a>
            </div>
        </div>
        <!-- END: Account Menu -->

    </div>
</div>
<!-- END: Top Bar -->

<script>
(function () {
    'use strict';

    // ============================================================
    // GLOBAL SEARCH — pakai event delegation, ga usah attach manual
    // ============================================================
    var SEARCH_URL = @json(route('admin.global-search'));

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function parseResponse(text) {
        var clean = text;
        if (clean.indexOf('<pre') !== -1) {
            var m = clean.match(/<pre[^>]*>([\s\S]*?)<\/pre>/i);
            if (m) clean = m[1];
        }
        clean = clean
            .replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'")
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&amp;/g, '&');
        return JSON.parse(clean.trim());
    }

    function renderResults(data, keyword) {
        var resultItems = document.getElementById('searchResultItems');
        var resultBox   = document.getElementById('searchResultBox');
        if (!resultItems || !resultBox) return;

        var groups = (data && data.groups) || [];

        if (groups.length === 0) {
            resultItems.innerHTML = '<div class="np-result-empty">Tidak ada hasil untuk "' + escapeHtml(keyword) + '".</div>';
            resultBox.classList.add('show');
            return;
        }

        var html = '';
        groups.forEach(function (group) {
            html += '<div class="np-search-group">';
            html += '  <div class="np-search-group__title">';
            html += '    <i data-feather="' + escapeHtml(group.icon) + '"></i>';
            html += '    <span>' + escapeHtml(group.label) + '</span>';
            html += '    <span class="np-search-group__count">' + group.items.length + '</span>';
            html += '  </div>';
            group.items.forEach(function (item) {
                html += '<a href="' + escapeHtml(item.url) + '" class="np-result-item">';
                html += '  <div class="ic"><i data-feather="chevron-right"></i></div>';
                html += '  <div class="np-result-content">';
                html += '    <div class="tt">' + escapeHtml(item.title) + '</div>';
                if (item.subtitle) {
                    html += '    <div class="st">' + escapeHtml(item.subtitle) + '</div>';
                }
                html += '  </div>';
                html += '</a>';
            });
            html += '</div>';
        });

        resultItems.innerHTML = html;
        resultBox.classList.add('show');
        try { if (window.feather) window.feather.replace(); } catch (e) {}
    }

    function showLoading() {
        var resultItems = document.getElementById('searchResultItems');
        var resultBox   = document.getElementById('searchResultBox');
        if (!resultItems || !resultBox) return;
        resultItems.innerHTML = '<div class="np-result-empty">Mencari...</div>';
        resultBox.classList.add('show');
    }

    // Debounce timer
    var searchTimer = null;

    // Event delegation — biar selalu ke-attach walau DOM di-replace
    document.addEventListener('input', function (e) {
        if (!e.target || e.target.id !== 'adminGlobalSearch') return;

        clearTimeout(searchTimer);
        var keyword = e.target.value.trim();

        if (keyword.length < 2) {
            var box = document.getElementById('searchResultBox');
            if (box) box.classList.remove('show');
            return;
        }

        showLoading();

        searchTimer = setTimeout(function () {
            var url = SEARCH_URL + '?q=' + encodeURIComponent(keyword);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.text();
            })
            .then(function (text) {
                try {
                    var data = parseResponse(text);
                    renderResults(data, keyword);
                } catch (err) {
                    console.error('[Search] Parse gagal:', err, text.slice(0, 200));
                    var resultItems = document.getElementById('searchResultItems');
                    if (resultItems) {
                        resultItems.innerHTML = '<div class="np-result-empty" style="color:#ef4444">Response tidak valid.</div>';
                    }
                }
            })
            .catch(function (err) {
                console.error('[Search] Error:', err);
                var resultItems = document.getElementById('searchResultItems');
                if (resultItems) {
                    resultItems.innerHTML = '<div class="np-result-empty" style="color:#ef4444">Gagal: ' + escapeHtml(err.message) + '</div>';
                }
            });
        }, 300);
    }, true);

    // Close on outside click
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.np-search')) {
            var box = document.getElementById('searchResultBox');
            if (box) box.classList.remove('show');
        }

        // Account & notif dropdown close
        if (!e.target.closest('.np-account') && !e.target.closest('.np-notif')) {
            document.querySelectorAll('.np-account.open, .np-notif.open').forEach(function (el) {
                el.classList.remove('open');
            });
        }
    }, true);

    // Escape close
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var box = document.getElementById('searchResultBox');
            if (box) box.classList.remove('show');
            document.querySelectorAll('.np-account.open, .np-notif.open').forEach(function (el) {
                el.classList.remove('open');
            });
        }
    });

    // Account & Notif toggle — pakai delegation juga
    document.addEventListener('click', function (e) {
        var accBtn = e.target.closest('#npAccountBtn');
        if (accBtn) {
            e.preventDefault();
            e.stopPropagation();
            var wrap = document.getElementById('npAccount');
            document.querySelectorAll('.np-notif.open').forEach(function (el) { el.classList.remove('open'); });
            if (wrap) wrap.classList.toggle('open');
            return;
        }

        var notifBtn = e.target.closest('#npNotifBtn');
        if (notifBtn) {
            e.preventDefault();
            e.stopPropagation();
            var wrap2 = document.getElementById('npNotif');
            document.querySelectorAll('.np-account.open').forEach(function (el) { el.classList.remove('open'); });
            if (wrap2) wrap2.classList.toggle('open');
            return;
        }
    }, true);

    // Feather replace on load
    function replaceFeather() {
        try { if (window.feather) window.feather.replace(); } catch (e) {}
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', replaceFeather);
    } else {
        replaceFeather();
    }
    setTimeout(replaceFeather, 200);
    setTimeout(replaceFeather, 800);

        // ============================================================
    // NOTIFICATION BELL — polling 30s + toast + localStorage read
    // ============================================================
    (function initNotifBell() {
        var NOTIF_URL    = @json(route('admin.notifications.index'));
        var LS_READ      = 'spa_notif_read_ids';
        var LS_LASTPOLL  = 'spa_notif_last_poll';
        var POLL_MS      = 15000;
        var SHOW_MAX     = 10;   // yang tampil di dropdown
        var TOAST_MAX    = 3;    // toast per polling

        var badgeEl  = document.getElementById('npNotifBadge');
        var listEl   = document.getElementById('npNotifList');
        var toastWrap= document.getElementById('npToastWrap');
        var closeBtn = document.getElementById('npNotifClose');
        var wrapEl   = document.getElementById('npNotif');

        if (!badgeEl || !listEl || !toastWrap) return;

        // ---------- localStorage helpers ----------
        function getReadIds() {
            try {
                var raw = localStorage.getItem(LS_READ);
                if (!raw) return [];
                var arr = JSON.parse(raw);
                return Array.isArray(arr) ? arr : [];
            } catch (e) {
                return [];
            }
        }
        function saveReadIds(arr) {
            try { localStorage.setItem(LS_READ, JSON.stringify(arr)); } catch (e) {}
        }
        function markRead(id) {
            var ids = getReadIds();
            if (ids.indexOf(id) === -1) {
                ids.push(id);
                saveReadIds(ids);
            }
        }
        function getLastPoll() {
            try { return localStorage.getItem(LS_LASTPOLL) || ''; } catch (e) { return ''; }
        }
        function setLastPoll(iso) {
            try { localStorage.setItem(LS_LASTPOLL, iso); } catch (e) {}
        }

        // ---------- state ----------
        var allItems = [];   // semua dari server (30)
        var visible  = [];   // 10 teratas yang belum di-read

        // ---------- compute visible ----------
        function computeVisible() {
            var readIds = getReadIds();
            visible = allItems.filter(function (it) {
                return readIds.indexOf(it.id) === -1;
            }).slice(0, SHOW_MAX);
        }

        // ---------- render badge ----------
        function renderBadge() {
            var n = visible.length;
            if (n <= 0) {
                badgeEl.classList.remove('show');
                badgeEl.textContent = '';
            } else {
                badgeEl.classList.add('show');
                badgeEl.textContent = n > 9 ? '9+' : String(n);
            }
        }

        // ---------- render dropdown list ----------
        function renderList() {
            if (visible.length === 0) {
                listEl.innerHTML =
                    '<div class="np-notif-empty">' +
                    '  <i data-feather="bell-off"></i>' +
                    '  <span>Belum ada notifikasi</span>' +
                    '</div>';
                replaceFeatherLocal();
                return;
            }

            var html = '';
            visible.forEach(function (it) {
                html += '<a href="' + escapeAttr(it.url) + '" class="np-notif-item" data-notif-id="' + escapeAttr(it.id) + '">';
                html += '  <div class="ic tone-' + escapeAttr(it.tone) + '"><i data-feather="' + escapeAttr(it.icon) + '"></i></div>';
                html += '  <div class="body">';
                html += '    <div class="tt">' + escapeHtml(it.title) + '</div>';
                html += '    <div class="st">' + escapeHtml(it.subtitle) + (it.time_ago ? ' · ' + escapeHtml(it.time_ago) : '') + '</div>';
                html += '  </div>';
                html += '  <div class="dot"></div>';
                html += '</a>';
            });
            listEl.innerHTML = html;
            replaceFeatherLocal();
        }

        // ---------- util escapers ----------
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
        function escapeAttr(str) {
            return escapeHtml(str);
        }
        function replaceFeatherLocal() {
            try { if (window.feather) window.feather.replace(); } catch (e) {}
        }

        // ---------- toast ----------
        function showToast(item) {
            var el = document.createElement('div');
            el.className = 'np-toast tone-' + (item.tone || 'primary');
            el.innerHTML =
                '<div class="ic"><i data-feather="' + escapeAttr(item.icon || 'bell') + '"></i></div>' +
                '<div class="body">' +
                '  <div class="tt">' + escapeHtml(item.title) + '</div>' +
                '  <div class="st">' + escapeHtml(item.subtitle || '') + '</div>' +
                '</div>' +
                '<button type="button" class="cls" title="Tutup"><i data-feather="x"></i></button>';

            // klik toast -> buka detail (mark read) + tutup toast
            el.addEventListener('click', function (e) {
                if (e.target.closest('.cls')) {
                    e.stopPropagation();
                    removeToast(el);
                    return;
                }
                markRead(item.id);
                refreshUI();
                window.location.href = item.url;
            });

            toastWrap.appendChild(el);
            replaceFeatherLocal();

            requestAnimationFrame(function () {
                requestAnimationFrame(function () { el.classList.add('show'); });
            });

            // auto-dismiss 8 detik
            var t = setTimeout(function () { removeToast(el); }, 8000);
            el.addEventListener('mouseenter', function () { clearTimeout(t); });
            el.addEventListener('mouseleave', function () {
                t = setTimeout(function () { removeToast(el); }, 4000);
            });
        }

        function removeToast(el) {
            el.classList.remove('show');
            setTimeout(function () {
                if (el.parentNode) el.parentNode.removeChild(el);
            }, 250);
        }

        // ---------- detect new items -> toast ----------
        function detectNewAndToast() {
            var lastPoll = getLastPoll();
            var readIds  = getReadIds();

            var fresh = allItems.filter(function (it) {
                if (readIds.indexOf(it.id) !== -1) return false;
                if (!lastPoll) return false; // first load, jangan toast semua
                return it.created_at > lastPoll;
            });

            // munculin max 3, dari yang paling lama dulu biar urut
            fresh = fresh.slice(0, TOAST_MAX).reverse();

            fresh.forEach(function (it, i) {
                setTimeout(function () { showToast(it); }, i * 350);
            });

            // update last_poll ke waktu notif terbaru (atau now)
            if (allItems.length > 0) {
                setLastPoll(allItems[0].created_at);
            } else {
                setLastPoll(new Date().toISOString());
            }
        }

        // ---------- fetch ----------
        function fetchNotif(isFirst) {
            fetch(NOTIF_URL, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                allItems = (data && data.items) || [];
                computeVisible();
                renderBadge();
                renderList();

                if (!isFirst) {
                    detectNewAndToast();
                } else {
                    // first load: cuma set last_poll tanpa toast
                    if (allItems.length > 0) setLastPoll(allItems[0].created_at);
                    else setLastPoll(new Date().toISOString());
                }
            })
            .catch(function (err) {
                // silent fail
                console.warn('[Notif] fetch error:', err.message);
            });
        }

        // ---------- refresh UI ----------
        function refreshUI() {
            computeVisible();
            renderBadge();
            renderList();
        }

        // ---------- mark read on click item ----------
        listEl.addEventListener('click', function (e) {
            var item = e.target.closest('.np-notif-item');
            if (!item) return;
            var id = item.getAttribute('data-notif-id');
            if (!id) return;
            markRead(id);
            // biarkan navigasi jalan, tapi update UI dulu
            refreshUI();
        }, true);

        // ---------- tombol tutup dropdown ----------
        if (closeBtn) {
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (wrapEl) wrapEl.classList.remove('open');
            });
        }

        // ---------- polling ----------
        var pollTimer = null;
        function startPoll() {
            stopPoll();
            pollTimer = setInterval(function () {
                if (document.hidden) return;
                fetchNotif(false);
            }, POLL_MS);
        }
        function stopPoll() {
            if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
        }

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                fetchNotif(false);
            }
        });

        // ---------- init ----------
        fetchNotif(true);
        startPoll();
    })();
})();
</script>
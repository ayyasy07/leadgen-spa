@extends('../layout/' . $layout)

@section('subhead')
    <title>Dashboard - LeadGen Admin</title>
@endsection

@section('subcontent')

<style>
    /* =====================================================
       DASHBOARD — modern, compact, theme-aware
       Warna aksen ikut tema (--color-primary) + dark mode
    ===================================================== */
    .rp-scope {
        --p: rgb(var(--color-primary));
        --p-soft: rgb(var(--color-primary) / .09);
        --p-tint: rgb(var(--color-primary) / .18);

        --ink: rgb(var(--color-slate-800));
        --text: rgb(var(--color-slate-600));
        --muted: rgb(var(--color-slate-500));
        --line: rgb(var(--color-slate-200));
        --card: #fff;
        --soft: rgb(var(--color-slate-100));
        --shadow: 0 6px 16px rgba(15, 23, 42, .08);

        --green: #15803d;
        --green-bg: rgba(34, 197, 94, .12);
        --orange: #b45309;
        --orange-bg: rgba(245, 158, 11, .14);
        --purple: #7c3aed;
        --purple-bg: rgba(139, 92, 246, .12);
        --red: #b91c1c;
        --red-bg: rgba(220, 38, 38, .10);
        --gray: #475569;
        --gray-bg: rgba(100, 116, 139, .14);

        color: var(--ink);
    }

    .dark .rp-scope {
        --ink: rgb(var(--color-slate-200));
        --text: rgb(var(--color-slate-300));
        --muted: rgb(var(--color-slate-400));
        --line: rgb(var(--color-darkmode-400));
        --card: rgb(var(--color-darkmode-600));
        --soft: rgb(var(--color-darkmode-700));
        --shadow: 0 6px 16px rgba(0, 0, 0, .25);

        --green: #4ade80;
        --green-bg: rgba(74, 222, 128, .13);
        --orange: #fbbf24;
        --orange-bg: rgba(251, 191, 36, .15);
        --purple: #c4b5fd;
        --purple-bg: rgba(196, 181, 253, .15);
        --red: #fca5a5;
        --red-bg: rgba(248, 113, 113, .14);
        --gray: #cbd5e1;
        --gray-bg: rgba(148, 163, 184, .16);
    }

    .rp-scope a {
        text-decoration: none;
    }

    /* ===== ANIMASI ===== */
    @keyframes rise {
        from {
            opacity: 0;
            transform: translateY(10px)
        }

        to {
            opacity: 1;
            transform: none
        }
    }

    .anim {
        animation: rise .5s cubic-bezier(.22, .8, .3, 1) backwards;
        animation-delay: calc(var(--i, 0) * 60ms);
    }

    @media (prefers-reduced-motion:reduce) {
        .anim {
            animation: none;
        }

        .rp-scope * {
            transition: none !important;
        }
    }

    /* ===== HEADER ===== */
    .rp-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 24px;
        position: relative;
        z-index: 40;
        /* menu filter selalu di atas kartu di bawahnya */
    }

    .rp-head h1 {
        font-size: 24px;
        font-weight: 400;
        margin: 0;
        letter-spacing: -.02em;
        line-height: 1.15;
        color: var(--muted);
    }

    .rp-head h1 b {
        font-weight: 700;
        color: var(--ink);
    }

    .rp-head .sub {
        font-size: 12.5px;
        margin-top: 4px;
        color: var(--muted);
    }

    .head-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .range-dropdown {
        position: relative;
    }

    .range-dropdown summary {
        list-style: none;
        outline: none;
        cursor: pointer;
    }

    .range-dropdown summary::-webkit-details-marker {
        display: none;
    }

    .range-select {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 16px;
        border-radius: 99px;
        background: var(--card);
        border: 1px solid var(--line);
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        transition: border-color .15s, color .15s;
    }

    .range-select:hover,
    .range-dropdown[open] .range-select {
        border-color: var(--p);
        color: var(--p);
    }

    .range-select svg {
        width: 14px;
        height: 14px;
    }

    .range-menu {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 14px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, .12);
        padding: 6px;
        min-width: 190px;
        z-index: 60;
    }

    .range-menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text);
    }

    .range-menu a:hover {
        background: var(--p-soft);
        color: var(--p);
    }

    .range-menu a.active {
        background: var(--p-soft);
        color: var(--p);
        font-weight: 700;
    }

    .range-menu a svg {
        width: 14px;
        height: 14px;
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 20px;
        border-radius: 99px;
        background: var(--p);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        transition: filter .15s;
    }

    .btn-export:hover {
        filter: brightness(.92);
        color: #fff;
    }

    .btn-export svg {
        width: 14px;
        height: 14px;
    }

    /* ===== GRID LAYOUTS ===== */
    .row-top {
        display: grid;
        grid-template-columns: 1fr 2.2fr;
        gap: 16px;
        margin-top: 16px;
    }

    .row-2 {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 16px;
        margin-top: 16px;
    }

    .row-2-eq {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 16px;
    }

    /* ===== NET REVENUE CARD — layer bertumpuk keluar kanan & bawah ===== */
    .nr-wrap {
        position: relative;
        min-width: 0;
        display: flex;
        flex-direction: column;
        padding: 0 16px 16px 0;
        /* ruang buat layer yang nongol */
    }

    .nr-wrap::before,
    .nr-wrap::after {
        content: '';
        position: absolute;
        border-radius: 22px;
        pointer-events: none;
        transition: transform .3s cubic-bezier(.22, .8, .3, 1);
    }

    /* layer paling belakang (paling jauh) */
    .nr-wrap::before {
        top: 16px;
        left: 16px;
        right: 0;
        bottom: 0;
        background: rgb(var(--color-primary) / .24);
    }

    /* layer tengah */
    .nr-wrap::after {
        top: 8px;
        left: 8px;
        right: 8px;
        bottom: 8px;
        background: rgb(var(--color-primary) / .5);
    }

    .nr-wrap:hover::after {
        transform: translate(2px, 2px);
    }

    .nr-wrap:hover::before {
        transform: translate(4px, 4px);
    }

    .nr-card {
        position: relative;
        z-index: 1;
        flex: 1;
        background: linear-gradient(135deg, rgb(var(--color-primary)), rgb(var(--color-primary) / .8));
        border-radius: 22px;
        padding: 20px 22px;
        color: #fff;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .2);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 26px;
        min-height: 150px;
        min-width: 0;
    }

    .nr-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .nr-lbl {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        opacity: .95;
        min-width: 0;
    }

    .nr-ic {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .2);
    }

    .nr-ic svg {
        width: 15px;
        height: 15px;
    }

    .nr-go {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .2);
        color: #fff;
        transition: background .18s, color .18s, transform .18s;
    }

    .nr-go svg {
        width: 16px;
        height: 16px;
    }

    .nr-go:hover {
        background: #fff;
        color: var(--p);
        transform: rotate(45deg);
    }

    .nr-bottom {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .nr-value {
        font-size: 34px;
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }

    .nr-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 11px;
        border-radius: 99px;
        background: rgba(255, 255, 255, .22);
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* ===== STATISTICS — satu kartu, 3 kolom + divider ===== */
    .stats-wrap {
        min-width: 0;
        display: flex;
        padding-bottom: 16px;
    }

    /* tinggi sejajar kartu Net Revenue */
    .stats-card {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        min-width: 0;
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 22px;
        overflow: hidden;
    }

    .stat {
        padding: 22px 28px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        transition: background .2s;
    }

    .stat+.stat {
        border-left: 1px solid var(--line);
    }

    .stat:hover {
        background: var(--p-soft);
    }

    .stat .lbl {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--muted);
    }

    .stat .lbl svg {
        width: 13px;
        height: 13px;
        color: var(--p);
        flex: none;
    }

    .stat .val {
        font-size: 36px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1;
        letter-spacing: -.04em;
        margin-top: 12px;
        font-variant-numeric: tabular-nums;
        transition: color .2s, transform .25s cubic-bezier(.22, .8, .3, 1);
    }

    .stat:hover .val {
        color: var(--p);
        transform: translateX(3px);
    }

    .stat .cap {
        font-size: 12.5px;
        color: var(--muted);
        margin-top: 8px;
    }

    /* ===== CARD ===== */
    .card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 20px 22px;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .ch {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .ch h3 {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        color: var(--ink);
        letter-spacing: -.01em;
    }

    .ch .sub {
        font-size: 11.5px;
        margin-top: 3px;
        color: var(--muted);
    }

    .tag {
        display: inline-flex;
        align-items: center;
        height: 26px;
        padding: 0 11px;
        border-radius: 99px;
        background: var(--soft);
        font-size: 11.5px;
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
    }

    /* area grafik mengisi sisa tinggi kartu -> tidak ada ruang kosong */
    .rp-canvas {
        position: relative;
        flex: 1;
        min-height: 230px;
    }

    .rp-canvas canvas {
        position: absolute;
        inset: 0;
        width: 100% !important;
        height: 100% !important;
    }

    /* ===== WIDGET ===== */
    .widget {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 20px 22px;
        min-width: 0;
    }

    .widget .head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .widget h3 {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        color: var(--ink);
        letter-spacing: -.01em;
    }

    .widget .head .sub {
        font-size: 11.5px;
        margin-top: 3px;
        color: var(--muted);
    }

    .widget .head .see-all {
        font-size: 12px;
        font-weight: 600;
        color: var(--p);
    }

    .widget .head .see-all:hover {
        text-decoration: underline;
    }

    /* ===== TREATMENT PROGRESS BAR ===== */
    .trt-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .trt-item {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .trt-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .trt-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
        min-width: 0;
    }

    .trt-qty {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
        white-space: nowrap;
        flex: none;
    }

    .trt-bar {
        height: 8px;
        background: var(--p-soft);
        border-radius: 99px;
        overflow: hidden;
    }

    .trt-bar>span {
        display: block;
        height: 100%;
        background: var(--p);
        border-radius: 99px;
        transform-origin: left;
        animation: grow .9s cubic-bezier(.22, .8, .3, 1) both;
    }

    @keyframes grow {
        from {
            transform: scaleX(0)
        }

        to {
            transform: scaleX(1)
        }
    }

    .trt-rev {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--green);
        white-space: nowrap;
    }

    /* ===== LIST ITEM ===== */
    .list-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 0;
        border-top: 1px solid var(--line);
    }

    .list-item:first-child {
        border-top: none;
        padding-top: 4px;
    }

    .list-item:last-child {
        padding-bottom: 4px;
    }

    .list-item .avt {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12.5px;
    }

    .avt.blue {
        background: var(--p-soft);
        color: var(--p);
    }

    .avt.orange {
        background: var(--orange-bg);
        color: var(--orange);
    }

    .avt.green {
        background: var(--green-bg);
        color: var(--green);
    }

    .list-item .info {
        flex: 1;
        min-width: 0;
    }

    .list-item .info .name {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .list-item .info .meta {
        font-size: 11.5px;
        margin-top: 2px;
        color: var(--muted);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .list-item .go {
        flex: none;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--p);
        height: 28px;
        padding: 0 13px;
        display: inline-flex;
        align-items: center;
        border-radius: 99px;
        border: 1px solid var(--line);
        transition: background .15s, color .15s, border-color .15s;
    }

    .list-item .go:hover {
        background: var(--p);
        border-color: var(--p);
        color: #fff;
    }

    .list-item .row-eye {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        flex: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: var(--muted);
        border: 1px solid var(--line);
        transition: background .15s, color .15s, border-color .15s;
    }

    .list-item .row-eye svg {
        width: 13px;
        height: 13px;
    }

    .list-item .row-eye:hover {
        background: var(--p);
        color: #fff;
        border-color: var(--p);
    }

    /* Status pill */
    .st-pill {
        display: inline-flex;
        align-items: center;
        flex: none;
        white-space: nowrap;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: capitalize;
    }

    .st-pill.unread,
    .st-pill.confirmed {
        background: var(--p-soft);
        color: var(--p);
    }

    .st-pill.new,
    .st-pill.completed {
        background: var(--green-bg);
        color: var(--green);
    }

    .st-pill.pending {
        background: var(--orange-bg);
        color: var(--orange);
    }

    .st-pill.cancelled {
        background: var(--red-bg);
        color: var(--red);
    }

    .st-pill.no-show {
        background: var(--gray-bg);
        color: var(--gray);
    }

    .rp-empty {
        text-align: center;
        padding: 28px 16px;
        color: var(--muted);
        font-size: 12.5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .rp-empty svg {
        width: 28px;
        height: 28px;
        opacity: .5;
    }

    /* ===== QUICK LINKS ===== */
    .ql-wrap {
        margin-top: 16px;
    }

    .ql-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
    }

    .ql-item {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color .2s, background .2s;
    }

    .ql-item:hover {
        border-color: var(--p);
        background: var(--p-soft);
    }

    .ql-item .ic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: none;
        background: var(--p-soft);
        color: var(--p);
        transition: background .2s, color .2s;
    }

    .ql-item:hover .ic {
        background: var(--p);
        color: #fff;
    }

    .ql-item .ic svg {
        width: 16px;
        height: 16px;
    }

    .ql-item .info {
        flex: 1;
        min-width: 0;
    }

    .ql-item .title {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink);
    }

    .ql-item .desc {
        font-size: 11px;
        margin-top: 2px;
        color: var(--muted);
    }

    /* ===== DONUT ===== */
    .donut-wrap {
        position: relative;
        flex: 1;
        min-height: 210px;
    }

    .donut-wrap canvas {
        position: absolute;
        inset: 0;
        width: 100% !important;
        height: 100% !important;
    }

    .donut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
    }

    .donut-center b {
        font-size: 30px;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: -.04em;
        line-height: 1;
        display: block;
    }

    .donut-center span {
        font-size: 11.5px;
        color: var(--muted);
        margin-top: 4px;
        display: block;
    }

    .donut-legend {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 18px;
        margin-top: 16px;
        font-size: 12px;
    }

    .donut-legend .lg {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text);
        min-width: 0;
    }

    .donut-legend .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex: none;
    }

    .donut-legend .ct {
        font-weight: 700;
        color: var(--ink);
        margin-left: auto;
        font-variant-numeric: tabular-nums;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width:1200px) {
        .row-top {
            grid-template-columns: 1fr;
        }

        .row-2,
        .row-2-eq {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width:900px) {
        .stats-card {
            grid-template-columns: 1fr;
        }

        .stat+.stat {
            border-left: none;
            border-top: 1px solid var(--line);
        }
    }

    @media (max-width:1200px) {
        .ql-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width:768px) {
        .ql-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width:560px) {
        .rp-head h1 {
            font-size: 20px;
        }

        .ql-grid {
            grid-template-columns: 1fr;
        }

        .nr-value {
            font-size: 28px;
        }

        .stat .val {
            font-size: 30px;
        }
    }
</style>

<div class="rp-scope">

    {{-- HEADER — welcome --}}
    <div class="rp-head anim" style="--i:0">
        <div>
            <h1>Welcome back, <b>{{ auth()->user()->name ?? 'Admin' }}</b></h1>
            <div class="sub">Business overview — <strong>{{ $rangeLabel }}</strong></div>
        </div>
        <div class="head-right">
            <details class="range-dropdown">
                <summary>
                    <span class="range-select">
                        <i data-feather="calendar"></i>
                        {{ $rangeLabel }}
                        <i data-feather="chevron-down"></i>
                    </span>
                </summary>
                <div class="range-menu">
                    <a href="{{ route('admin.dashboard', ['range' => 'today']) }}" class="{{ $range === 'today' ? 'active' : '' }}">
                        <i data-feather="sun"></i> Today
                    </a>
                    <a href="{{ route('admin.dashboard', ['range' => '7days']) }}" class="{{ $range === '7days' ? 'active' : '' }}">
                        <i data-feather="calendar"></i> Last 7 Days
                    </a>
                    <a href="{{ route('admin.dashboard', ['range' => '30days']) }}" class="{{ $range === '30days' ? 'active' : '' }}">
                        <i data-feather="calendar"></i> Last 30 Days
                    </a>
                    <a href="{{ route('admin.dashboard', ['range' => 'this_month']) }}" class="{{ $range === 'this_month' ? 'active' : '' }}">
                        <i data-feather="calendar"></i> This Month
                    </a>
                </div>
            </details>

            <button type="button" id="btnExportXlsx" class="btn-export">
                <i data-feather="download"></i> Export XLSX
            </button>
        </div>
    </div>

    {{-- ROW TOP: NET REVENUE + STATISTICS --}}
    <div class="row-top">
        {{-- NET REVENUE CARD --}}
        <div class="nr-wrap anim" style="--i:1">
            <div class="nr-card">
                <div class="nr-top">
                    <div class="nr-lbl">
                        <span class="nr-ic"><i data-feather="trending-up"></i></span>
                        <span>Net Revenue — {{ $rangeLabel }}</span>
                    </div>
                    <a href="{{ route('admin.reports.index', ['tab' => 'financial']) }}" class="nr-go" title="View Reports">
                        <i data-feather="arrow-up-right"></i>
                    </a>
                </div>
                <div class="nr-bottom">
                    <div class="nr-value" data-count="{{ $financial['net_revenue_range'] }}" data-prefix="Rp ">Rp {{ number_format($financial['net_revenue_range'], 0, ',', '.') }}</div>
                    @if($financial['revenue_growth'] != 0)
                        <div class="nr-badge">
                            {{ $financial['revenue_growth'] > 0 ? '↑' : '↓' }}
                            {{ abs($financial['revenue_growth']) }}% vs prev
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- STATISTICS: satu kartu, 3 kolom --}}
        <div class="stats-wrap anim" style="--i:2">
            <div class="stats-card">
                <div class="stat">
                    <div class="lbl"><i data-feather="calendar"></i> Total Bookings</div>
                    <div class="val" data-count="{{ $stats['bookings_range'] }}">{{ number_format($stats['bookings_range'], 0, ',', '.') }}</div>
                    <div class="cap">{{ $stats['bookings_today'] }} today</div>
                </div>
                <div class="stat">
                    <div class="lbl"><i data-feather="eye"></i> Website Visitors</div>
                    <div class="val" data-count="{{ $stats['visitors_total'] }}">{{ number_format($stats['visitors_total'], 0, ',', '.') }}</div>
                    <div class="cap">+{{ $stats['visitors_range'] }} new in {{ $rangeLabel }}</div>
                </div>
                <div class="stat">
                    <div class="lbl"><i data-feather="mail"></i> Unread Messages</div>
                    <div class="val" data-count="{{ $stats['unread_messages'] }}">{{ number_format($stats['unread_messages'], 0, ',', '.') }}</div>
                    <div class="cap">{{ $stats['active_subs'] }} subscribers</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW: NET REVENUE CHART + VISITOR --}}
    <div class="row-2">
        <div class="card anim" style="--i:5">
            <div class="ch">
                <div>
                    <h3>Revenue Report</h3>
                    <div class="sub">Net revenue — last 6 months</div>
                </div>
                <span class="tag">Rp {{ number_format(array_sum($revenueChart['values']), 0, ',', '.') }}</span>
            </div>
            <div class="rp-canvas"><canvas id="revenueChart"></canvas></div>
        </div>

        <div class="card anim" style="--i:6">
            <div class="ch">
                <div>
                    <h3>Visitors</h3>
                    <div class="sub">Last 6 months</div>
                </div>
                <span class="tag">{{ number_format($stats['visitors_total'], 0, ',', '.') }}</span>
            </div>
            <div class="rp-canvas"><canvas id="visitorChart"></canvas></div>
        </div>
    </div>

    {{-- ROW: BOOKING TREND + DONUT CHART (status) --}}
    <div class="row-2">
        <div class="card anim" style="--i:7">
            <div class="ch">
                <div>
                    <h3>Bookings Trend</h3>
                    <div class="sub">{{ $rangeLabel }}</div>
                </div>
            </div>
            <div class="rp-canvas"><canvas id="bookingChart"></canvas></div>
        </div>

        <div class="card anim" style="--i:8">
            <div class="ch">
                <div>
                    <h3>Booking Status</h3>
                    <div class="sub">Distribution</div>
                </div>
            </div>
            <div class="donut-wrap">
                <canvas id="statusChart"></canvas>
                <div class="donut-center">
                    <b>{{ number_format(array_sum($statusChart['values']), 0, ',', '.') }}</b>
                    <span>total</span>
                </div>
            </div>
            <div class="donut-legend">
                <div class="lg"><span class="dot" style="background:var(--p)"></span> Confirmed <span class="ct">{{ $statusChart['values'][0] }}</span></div>
                <div class="lg"><span class="dot" style="background:#7fd1a6"></span> Completed <span class="ct">{{ $statusChart['values'][1] }}</span></div>
                <div class="lg"><span class="dot" style="background:#f4a6a3"></span> Cancelled <span class="ct">{{ $statusChart['values'][2] }}</span></div>
                <div class="lg"><span class="dot" style="background:#cdd5e2"></span> No-Show <span class="ct">{{ $statusChart['values'][3] }}</span></div>
            </div>
        </div>
    </div>

    {{-- ROW: RECENT BOOKINGS + TOP TREATMENTS --}}
    <div class="row-2">
        <div class="widget anim" style="--i:9">
            <div class="head">
                <div>
                    <h3>Recent Bookings</h3>
                    <div class="sub">Latest 5</div>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="see-all">See All →</a>
            </div>
            @if($recentBookings->count())
                <div>
                    @foreach($recentBookings as $b)
                        <div class="list-item">
                            <div class="avt blue">
                                {{ strtoupper(substr($b->customer_name, 0, 1)) }}
                            </div>
                            <div class="info">
                                <div class="name">{{ $b->customer_name }}</div>
                                <div class="meta">{{ $b->booking_code }} · {{ $b->created_at->diffForHumans() }}</div>
                            </div>
                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="row-eye" title="View detail">
                                <i data-feather="eye"></i>
                            </a>
                            <span class="st-pill {{ $b->status }}">{{ $b->status }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rp-empty">
                    <i data-feather="inbox"></i>
                    <div>No bookings yet.</div>
                </div>
            @endif
        </div>

        <div class="widget anim" style="--i:10">
            <div class="head">
                <div>
                    <h3>Top Treatments</h3>
                    <div class="sub">{{ $rangeLabel }}</div>
                </div>
                <a href="{{ route('admin.reports.index', ['tab' => 'service']) }}" class="see-all">See All →</a>
            </div>
            @if($topTreatments->count())
                @php $maxQty = $topTreatments->max('qty') ?: 1; @endphp
                <div class="trt-list">
                    @foreach($topTreatments as $t)
                        <div class="trt-item">
                            <div class="trt-head">
                                <div class="trt-name">{{ $t['name'] }}</div>
                                <div class="trt-qty">{{ $t['qty'] }}×</div>
                            </div>
                            <div class="trt-bar">
                                <span style="width: {{ ($t['qty'] / $maxQty) * 100 }}%"></span>
                            </div>
                            <div class="trt-rev">Rp {{ number_format($t['revenue'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rp-empty">
                    <i data-feather="package"></i>
                    <div>No treatment data yet.</div>
                </div>
            @endif
        </div>
    </div>

    {{-- ROW: UNREAD MESSAGES + NEW SUBSCRIBERS --}}
    <div class="row-2-eq">
        <div class="widget anim" style="--i:11">
            <div class="head">
                <div>
                    <h3>Unread Messages</h3>
                    <div class="sub">Needs attention</div>
                </div>
                <a href="{{ route('admin.contact-messages.index') }}" class="see-all">See All →</a>
            </div>
            @if($unreadMessages->count())
                <div>
                    @foreach($unreadMessages as $m)
                        <div class="list-item">
                            <div class="avt orange">
                                {{ strtoupper(substr($m->name, 0, 1)) }}
                            </div>
                            <div class="info">
                                <div class="name">{{ $m->name }}</div>
                                <div class="meta">{{ $m->subject ?: '(No Subject)' }}</div>
                            </div>
                            <a href="{{ route('admin.contact-messages.show', $m) }}" class="row-eye" title="Read message">
                                <i data-feather="eye"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rp-empty">
                    <i data-feather="mail"></i>
                    <div>No unread messages.</div>
                </div>
            @endif
        </div>

        <div class="widget anim" style="--i:12">
            <div class="head">
                <div>
                    <h3>New Subscribers</h3>
                    <div class="sub">{{ $stats['active_subs'] }} active</div>
                </div>
                <a href="{{ route('admin.newsletters.index') }}" class="see-all">See All →</a>
            </div>
            @if($recentSubscribers->count())
                <div>
                    @foreach($recentSubscribers as $s)
                        <div class="list-item">
                            <div class="avt green">
                                {{ strtoupper(substr($s->email, 0, 1)) }}
                            </div>
                            <div class="info">
                                <div class="name">{{ $s->email }}</div>
                                <div class="meta">{{ $s->subscribed_at ? $s->subscribed_at->diffForHumans() : '-' }}</div>
                            </div>
                            <a href="{{ route('admin.newsletters.index') }}" class="row-eye" title="View subscriber">
                                <i data-feather="eye"></i>
                            </a>
                            <span class="st-pill new">Active</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rp-empty">
                    <i data-feather="user-plus"></i>
                    <div>No subscribers yet.</div>
                </div>
            @endif
        </div>
    </div>

    {{-- QUICK LINKS — 5 card --}}
    <div class="ql-wrap anim" style="--i:13">
        <div class="ql-grid">
            <a href="{{ route('admin.bookings.index') }}" class="ql-item">
                <div class="ic"><i data-feather="calendar"></i></div>
                <div class="info"><div class="title">Bookings</div><div class="desc">Manage bookings</div></div>
            </a>
            <a href="{{ route('admin.treatments.index') }}" class="ql-item">
                <div class="ic"><i data-feather="scissors"></i></div>
                <div class="info"><div class="title">Treatments</div><div class="desc">Manage treatments</div></div>
            </a>
            <a href="{{ route('admin.packages.index') }}" class="ql-item">
                <div class="ic"><i data-feather="package"></i></div>
                <div class="info"><div class="title">Packages</div><div class="desc">Manage packages</div></div>
            </a>
            <a href="{{ route('admin.reports.index', ['tab' => 'financial']) }}" class="ql-item">
                <div class="ic"><i data-feather="bar-chart-2"></i></div>
                <div class="info"><div class="title">Reports</div><div class="desc">Business reports</div></div>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="ql-item">
                <div class="ic"><i data-feather="settings"></i></div>
                <div class="info"><div class="title">Settings</div><div class="desc">Configuration</div></div>
            </a>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.feather) feather.replace();
        setTimeout(function () { if (window.feather) feather.replace(); }, 100);
        setTimeout(function () { if (window.feather) feather.replace(); }, 500);

        // =============================================
        // EXPORT XLSX — pake SheetJS
        // =============================================
        var btnX = document.getElementById('btnExportXlsx');
        if (btnX && typeof XLSX !== 'undefined') {
            btnX.addEventListener('click', function(e) {
                e.preventDefault();
                var originalHtml = btnX.innerHTML;
                btnX.disabled = true;
                btnX.innerHTML = 'Loading...';

                fetch('{{ route('admin.dashboard.export', ['range' => $range]) }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    var wb = XLSX.utils.book_new();

                    var s1 = [['Dashboard Export'], ['']];
                    Object.keys(d.summary).forEach(function(k) { s1.push([k, d.summary[k]]); });
                    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s1), 'Summary');

                    var s2 = [['Month', 'Net Revenue (Rp)']];
                    d.financial.forEach(function(r) { s2.push(r); });
                    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s2), 'Financial');

                    var s3 = [['Code', 'Customer', 'Phone', 'Date', 'Time', 'Items', 'Status', 'Total', 'Net']];
                    d.bookings.forEach(function(r) { s3.push(r); });
                    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s3), 'Bookings');

                    var s4 = [['Status', 'Count']];
                    d.operational.forEach(function(r) { s4.push(r); });
                    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s4), 'Operational');

                    var s5 = [['Email', 'Status', 'Joined']];
                    d.subs.forEach(function(r) { s5.push(r); });
                    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s5), 'Newsletter');

                    var s6 = [['Name', 'Email', 'Subject', 'Status', 'Date']];
                    d.msgs.forEach(function(r) { s6.push(r); });
                    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s6), 'Messages');

                    XLSX.writeFile(wb, 'dashboard-' + d.range + '-' + new Date().toISOString().slice(0,10) + '.xlsx');

                    btnX.disabled = false;
                    btnX.innerHTML = originalHtml;
                    if (window.feather) feather.replace();
                })
                .catch(function(err) {
                    console.error(err);
                    alert('Export gagal.');
                    btnX.disabled = false;
                    btnX.innerHTML = originalHtml;
                    if (window.feather) feather.replace();
                });
            });
        }

        var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ===== COUNT-UP ANGKA ===== */
        if (!reduceMotion) {
            var nf = new Intl.NumberFormat('id-ID');
            document.querySelectorAll('[data-count]').forEach(function (el, idx) {
                var target = parseFloat(el.getAttribute('data-count'));
                if (!isFinite(target) || target <= 0) return;
                var prefix = el.getAttribute('data-prefix') || '';
                var dur = 1000, start = null;
                el.textContent = prefix + '0';
                function step(ts) {
                    if (start === null) start = ts;
                    var t = Math.min((ts - start) / dur, 1);
                    var e = 1 - Math.pow(1 - t, 3);
                    el.textContent = prefix + nf.format(Math.round(target * e));
                    if (t < 1) requestAnimationFrame(step);
                }
                setTimeout(function () { requestAnimationFrame(step); }, 150 + idx * 60);
            });
        }

        /* ===== COLOR HELPERS ===== */
        function getPrimaryHex() {
            var raw = getComputedStyle(document.documentElement)
                .getPropertyValue('--color-primary').trim();
            var parts = raw.split(/[\s,]+/).map(Number);
            if (parts.length >= 3) {
                return '#' + parts.slice(0, 3).map(function (n) {
                    return ('0' + Math.round(n).toString(16)).slice(-2);
                }).join('');
            }
            return '#2563eb';
        }
        function hexToRgba(hex, a) {
            var n = parseInt(hex.slice(1), 16);
            return 'rgba(' + ((n >> 16) & 255) + ',' + ((n >> 8) & 255) + ',' + (n & 255) + ',' + a + ')';
        }
        function mixWhite(hex, t) {
            var n = parseInt(hex.slice(1), 16);
            var c = [(n >> 16) & 255, (n >> 8) & 255, n & 255].map(function (v) {
                return ('0' + Math.round(v + (255 - v) * t).toString(16)).slice(-2);
            });
            return '#' + c.join('');
        }
        var P = getPrimaryHex();
        var isDark = document.documentElement.classList.contains('dark');

        function getSlate(n, alpha) {
            var raw = getComputedStyle(document.documentElement)
                .getPropertyValue('--color-slate-' + n).trim();
            var parts = raw.split(/[\s,]+/).map(Number);
            if (parts.length >= 3) {
                return 'rgba(' + parts[0] + ',' + parts[1] + ',' + parts[2] + ',' + (alpha || 1) + ')';
            }
            return alpha ? 'rgba(148,163,184,' + alpha + ')' : '#94a3b8';
        }
        var GRID = getSlate(isDark ? 600 : 200, isDark ? .35 : .7);
        var TICK = getSlate(500);
        var TIP  = '#1e293b';

        var firstCard = document.querySelector('.rp-scope .card');
        var CARD_BG = firstCard ? getComputedStyle(firstCard).backgroundColor : '#fff';

        function rupiahShort(v) {
            if (v >= 1000000) return (v / 1000000).toFixed(1) + 'jt';
            if (v >= 1000) return (v / 1000).toFixed(0) + 'rb';
            return v;
        }
        function isLast(ctx) { return ctx.dataIndex === ctx.dataset.data.length - 1; }

        /* ===== CHART 1: REVENUE — line + area ===== */
        var canvasRev = document.getElementById('revenueChart');
        if (canvasRev && typeof Chart !== 'undefined') {
            new Chart(canvasRev.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($revenueChart['labels'] ?? []),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenueChart['values'] ?? []),
                        borderColor: P,
                        backgroundColor: function (ctx) {
                            var chart = ctx.chart, area = chart.chartArea;
                            if (!area) return 'transparent';
                            var g = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
                            g.addColorStop(0, hexToRgba(P, .34));
                            g.addColorStop(1, hexToRgba(P, 0));
                            return g;
                        },
                        fill: true,
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: CARD_BG,
                        pointBorderColor: P,
                        pointBorderWidth: 2.5,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    animation: { duration: 1200, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: TIP,
                            padding: 11, cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: function (ctx) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: TICK, font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: GRID, drawBorder: false },
                            ticks: { color: TICK, font: { size: 11 }, callback: rupiahShort }
                        }
                    }
                }
            });
        }

        /* ===== CHART 2: VISITOR — bar lembut, bulan terakhir disorot ===== */
        var canvasVisit = document.getElementById('visitorChart');
        if (canvasVisit && typeof Chart !== 'undefined') {
            var visitData = @json($visitorChart);
            new Chart(canvasVisit.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: visitData.labels,
                    datasets: [{
                        label: 'Visitors',
                        data: visitData.values,
                        backgroundColor: function (ctx) {
                            return isLast(ctx) ? P : hexToRgba(P, isDark ? .4 : .22);
                        },
                        hoverBackgroundColor: P,
                        borderRadius: 10,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.75,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: TIP,
                            padding: 10, cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: function (ctx) { return ctx.parsed.y + ' visitors'; }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: TICK, font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: GRID, drawBorder: false },
                            ticks: { color: TICK, font: { size: 11 }, precision: 0 }
                        }
                    }
                }
            });
        }

        /* ===== CHART 3: BOOKING TREND — line + area ===== */
        var canvasBook = document.getElementById('bookingChart');
        if (canvasBook && typeof Chart !== 'undefined') {
            new Chart(canvasBook.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Bookings',
                        data: @json($chartBooking),
                        borderColor: P,
                        backgroundColor: function (ctx) {
                            var chart = ctx.chart, area = chart.chartArea;
                            if (!area) return 'transparent';
                            var g = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
                            g.addColorStop(0, hexToRgba(P, isDark ? .35 : .22));
                            g.addColorStop(1, hexToRgba(P, 0));
                            return g;
                        },
                        fill: true,
                        borderWidth: 2.8,
                        tension: 0.4,
                        pointBackgroundColor: CARD_BG,
                        pointBorderColor: P,
                        pointBorderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    animation: { duration: 1000, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: TIP,
                            padding: 11, cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: function (ctx) { return ctx.parsed.y + ' bookings'; }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: TICK, font: { size: 11 }, maxRotation: 0 }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: GRID, drawBorder: false },
                            ticks: { color: TICK, font: { size: 11 }, precision: 0 }
                        }
                    }
                }
            });
        }

        /* ===== CHART 4: DONUT — warna soft, segmen terbesar menonjol ===== */
        var canvasStatus = document.getElementById('statusChart');
        if (canvasStatus && typeof Chart !== 'undefined') {
            var statusData = @json($statusChart);
            var DONUT = [mixWhite(P, .3), '#7fd1a6', '#f4a6a3', '#cdd5e2'];

            document.querySelectorAll('.donut-legend .dot').forEach(function (d, i) {
                if (DONUT[i]) d.style.background = DONUT[i];
            });

            var vals = statusData.values.map(Number);
            var maxV = Math.max.apply(null, vals.concat([0]));
            new Chart(canvasStatus.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.values,
                        backgroundColor: DONUT,
                        borderWidth: 4,
                        borderColor: CARD_BG,
                        borderRadius: 10,
                        offset: vals.map(function (v) { return (v === maxV && v > 0) ? 12 : 0; }),
                        hoverOffset: 12,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    layout: { padding: 16 },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1200,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: TIP,
                            padding: 10, cornerRadius: 10,
                            callbacks: {
                                label: function (ctx) {
                                    var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                                    var pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                    return ' ' + ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        /* ===== CLOSE DROPDOWN ON OUTSIDE CLICK ===== */
        document.addEventListener('click', function (e) {
            document.querySelectorAll('.range-dropdown[open]').forEach(function (dd) {
                if (!dd.contains(e.target)) dd.removeAttribute('open');
            });
        });
    });
</script>
@endpush
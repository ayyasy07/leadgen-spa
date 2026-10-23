@extends('../layout/' . $layout)

@section('subhead')
    <title>Reports - LeadGen Admin</title>
@endsection

@section('subcontent')

<style>
    /* =====================================================
       REPORTS — layout laporan (ringkasan → grafik → rincian)
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
        --head: rgb(var(--color-slate-50));
        --seg: rgb(var(--color-slate-200) / .7);

        --green: #15803d;
        --green-bg: rgba(34, 197, 94, .12);
        --gray: #475569;
        --gray-bg: rgba(100, 116, 139, .14);
        --red: #b91c1c;
        --red-bg: rgba(220, 38, 38, .10);

        color: var(--ink);
    }

    .dark .rp-scope {
        --ink: rgb(var(--color-slate-200));
        --text: rgb(var(--color-slate-300));
        --muted: rgb(var(--color-slate-400));
        --line: rgb(var(--color-darkmode-400));
        --card: rgb(var(--color-darkmode-600));
        --soft: rgb(var(--color-darkmode-700));
        --head: rgb(var(--color-darkmode-700));
        --seg: rgb(var(--color-darkmode-700));

        --green: #4ade80;
        --green-bg: rgba(74, 222, 128, .13);
        --gray: #cbd5e1;
        --gray-bg: rgba(148, 163, 184, .16);
        --red: #fca5a5;
        --red-bg: rgba(248, 113, 113, .14);
    }

    .rp-scope a {
        text-decoration: none;
    }

    .rp-scope input[type="date"] {
        color-scheme: light;
    }

    .dark .rp-scope input[type="date"] {
        color-scheme: dark;
    }

    /* ===== HEADER ===== */
    .rp-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 26px;
    }

    .rp-head h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -.03em;
        line-height: 1.1;
        color: var(--ink);
    }

    .rp-head h1 span {
        font-weight: 400;
        color: var(--muted);
    }

    .rp-head .sub {
        font-size: 13px;
        margin-top: 6px;
        color: var(--muted);
    }

    .head-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .period {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 42px;
        padding: 0 16px;
        border-radius: 99px;
        background: var(--card);
        border: 1px solid var(--line);
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .period svg {
        width: 14px;
        height: 14px;
        color: var(--muted);
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 42px;
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

    /* ===== TOOLBAR: TABS + PRESETS (SEJAJAR) ===== */
    .rp-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .rp-tabs {
        display: inline-flex;
        gap: 2px;
        background: var(--seg);
        border-radius: 99px;
        padding: 4px;
    }

    .rp-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        height: 38px;
        padding: 0 18px;
        border-radius: 99px;
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
        transition: background .15s, color .15s;
    }

    .rp-tab:hover {
        color: var(--ink);
    }

    .rp-tab.active {
        background: var(--card);
        color: var(--p);
        box-shadow: 0 1px 3px rgba(15, 23, 42, .1);
    }

    .rp-tab svg {
        width: 14px;
        height: 14px;
    }

    /* ===== PRESET BUTTONS ===== */
    .rp-presets {
        display: inline-flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .rp-preset {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 38px;
        padding: 0 16px;
        border-radius: 99px;
        border: 1px solid var(--line);
        background: var(--card);
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text);
        cursor: pointer;
        transition: all .15s;
    }

    .rp-preset:hover {
        border-color: var(--p);
        color: var(--p);
        background: var(--p-soft);
    }

    .rp-preset.active {
        background: var(--p);
        color: #fff;
        border-color: var(--p);
    }

    .rp-preset svg {
        width: 13px;
        height: 13px;
    }

    /* ===== FILTER ===== */
    .rp-filter {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 22px;
        padding: 16px 20px;
        margin-top: 14px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }

    .rp-filter .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .rp-filter .field label {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
    }

    .rp-filter .field input,
    .rp-filter .field select {
        height: 42px;
        border-radius: 12px;
        border: 1px solid var(--line);
        padding: 0 14px;
        font-size: 13.5px;
        background: var(--soft);
        color: var(--ink);
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }

    .rp-filter .field input::placeholder {
        color: var(--muted);
    }

    .rp-filter .field input:focus,
    .rp-filter .field select:focus {
        border-color: var(--p);
        background: var(--card);
        box-shadow: 0 0 0 3px var(--p-soft);
    }

    .rp-filter .field.grow {
        flex: 1;
        min-width: 200px;
    }

    .rp-filter .field.grow input {
        width: 100%;
    }

    .rp-filter .actions {
        display: flex;
        gap: 8px;
    }

    .rp-filter .btn-apply {
        height: 42px;
        padding: 0 20px;
        border-radius: 99px;
        border: none;
        cursor: pointer;
        background: var(--ink);
        color: var(--card);
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: opacity .15s;
    }

    .rp-filter .btn-apply:hover {
        opacity: .85;
    }

    .rp-filter .btn-reset {
        height: 42px;
        padding: 0 18px;
        border-radius: 99px;
        border: 1px solid var(--line);
        background: transparent;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: border-color .15s, color .15s;
    }

    .rp-filter .btn-reset:hover {
        border-color: var(--p);
        color: var(--p);
    }

    /* ===== PANEL ===== */
    .rp-panel {
        display: none;
        margin-top: 14px;
    }

    .rp-panel.active {
        display: block;
    }

    /* ===== CARD ===== */
    .card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 22px 24px;
        min-width: 0;
    }

    .ch {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .ch h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        color: var(--ink);
        letter-spacing: -.01em;
    }

    .ch .sub {
        font-size: 12px;
        margin-top: 3px;
        color: var(--muted);
    }

    .tag {
        display: inline-flex;
        align-items: center;
        height: 28px;
        padding: 0 12px;
        border-radius: 99px;
        background: var(--soft);
        font-size: 11.5px;
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
    }

    .rp-canvas {
        position: relative;
        height: 280px;
    }

    /* ===== KPI STRIP ===== */
    .kpi {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        padding: 0;
        overflow: hidden;
    }

    .kpi-cell {
        padding: 22px 26px;
        border-left: 1px solid var(--line);
        min-width: 0;
    }

    .kpi-cell:first-child {
        border-left: none;
    }

    .kpi-lbl {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
    }

    .kpi-ico {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--p-soft);
        color: var(--p);
    }

    .kpi-ico svg {
        width: 13px;
        height: 13px;
    }

    .kpi-val {
        margin-top: 14px;
        font-size: 29px;
        font-weight: 700;
        line-height: 1.1;
        letter-spacing: -.035em;
        color: var(--ink);
    }

    .kpi-val.sm {
        font-size: 24px;
        letter-spacing: -.03em;
    }

    .kpi-val.accent {
        color: var(--p);
    }

    .kpi-sub {
        font-size: 12px;
        margin-top: 8px;
        color: var(--muted);
        line-height: 1.45;
    }

    /* ===== ROW GRIDS ===== */
    .row-2 {
        display: grid;
        grid-template-columns: 8fr 4fr;
        gap: 14px;
        margin-top: 14px;
    }

    .row-svc {
        display: grid;
        grid-template-columns: 5fr 7fr;
        gap: 14px;
        margin-top: 14px;
        align-items: start;
    }

    /* status card */
    .donut-wrap {
        position: relative;
        height: 190px;
        max-width: 230px;
        margin: 0 auto 18px;
    }

    .donut-center {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .donut-center b {
        font-size: 27px;
        font-weight: 700;
        letter-spacing: -.03em;
        color: var(--ink);
        line-height: 1;
    }

    .donut-center span {
        font-size: 11.5px;
        color: var(--muted);
        margin-top: 4px;
    }

    .lg-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-top: 1px solid var(--line);
        font-size: 13.5px;
    }

    .lg-row .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex: none;
    }

    .lg-row .nm {
        flex: 1;
        color: var(--text);
    }

    .lg-row .ct {
        font-weight: 700;
        color: var(--ink);
        font-variant-numeric: tabular-nums;
    }

    .dot.confirmed {
        background: var(--p);
    }

    .dot.completed {
        background: #2f9e62;
    }

    .dot.no-show {
        background: #94a3b8;
    }

    .dot.cancelled {
        background: #d9534f;
    }

    /* ===== TABLE ===== */
    .rp-table {
        margin-top: 14px;
        padding: 0;
        overflow: hidden;
    }

    .row-svc .rp-table {
        margin-top: 0;
    }

    .rp-table .ch {
        padding: 22px 24px 0;
    }

    .tbl-scroll {
        overflow-x: auto;
        margin-top: 6px;
    }

    .rp-table table {
        width: 100%;
        border-collapse: collapse;
        min-width: 640px;
    }

    .rp-table.compact table {
        min-width: 520px;
    }

    .rp-table thead th {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        padding: 13px 18px;
        text-align: left;
        white-space: nowrap;
        background: var(--head);
        border-top: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
    }

    .rp-table tbody td {
        padding: 14px 18px;
        vertical-align: middle;
        border-bottom: 1px solid var(--line);
        font-size: 13.5px;
        color: var(--text);
    }

    .rp-table tbody tr:last-child td {
        border-bottom: none;
    }

    .rp-table tbody tr:hover {
        background: var(--head);
    }

    .rp-table .strong {
        font-weight: 600;
        color: var(--ink);
    }

    .rp-table .muted {
        font-size: 12px;
        color: var(--muted);
        margin-top: 1px;
    }

    .rp-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-weight: 700;
        font-size: 12.5px;
        color: var(--p);
    }

    .rp-items {
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 12.5px;
        color: var(--muted);
    }

    .rp-badge {
        display: inline-block;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 11px;
        border-radius: 99px;
        text-transform: capitalize;
    }

    .rp-badge.confirmed {
        background: var(--p-soft);
        color: var(--p);
    }

    .rp-badge.completed {
        background: var(--green-bg);
        color: var(--green);
    }

    .rp-badge.no-show {
        background: var(--gray-bg);
        color: var(--gray);
    }

    .rp-badge.cancelled {
        background: var(--red-bg);
        color: var(--red);
    }

    .rp-time {
        display: inline-flex;
        align-items: center;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-weight: 700;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 8px;
        background: var(--soft);
        color: var(--ink);
    }

    .rp-bar {
        height: 8px;
        background: var(--p-soft);
        border-radius: 999px;
        overflow: hidden;
        min-width: 90px;
        flex: 1;
    }

    .rp-bar>span {
        display: block;
        height: 100%;
        background: var(--p);
        border-radius: 999px;
        transition: width .6s cubic-bezier(.4, 0, .2, 1);
    }

    .share {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .share em {
        font-style: normal;
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        min-width: 40px;
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    .money-ok {
        color: var(--green);
        font-weight: 600;
    }

    .money-bad {
        color: var(--red);
        font-size: 11.5px;
        font-weight: 600;
    }

    .pill-view {
        display: inline-flex;
        align-items: center;
        height: 28px;
        padding: 0 14px;
        border-radius: 99px;
        border: 1px solid var(--line);
        font-size: 12px;
        font-weight: 600;
        color: var(--p);
        transition: background .15s, color .15s, border-color .15s;
    }

    .pill-view:hover {
        background: var(--p);
        border-color: var(--p);
        color: #fff;
    }

    /* ===== EMPTY STATE (FIXED — CENTERED) ===== */
    .rp-empty {
        text-align: center;
        padding: 56px 20px;
        color: var(--muted);
        font-size: 13.5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .rp-empty svg {
        width: 38px;
        height: 38px;
        opacity: .5;
    }

    .rp-empty .empty-text {
        font-size: 13.5px;
        color: var(--muted);
    }

    .card.empty-card {
        margin-top: 14px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width:1200px) {

        .row-2,
        .row-svc {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width:1024px) {
        .kpi {
            grid-template-columns: repeat(2, 1fr);
        }

        .kpi-cell:nth-child(odd) {
            border-left: none;
        }

        .kpi-cell:nth-child(n+3) {
            border-top: 1px solid var(--line);
        }
    }

    @media (max-width:768px) {
        .rp-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .rp-tabs,
        .rp-presets {
            width: 100%;
            overflow-x: auto;
        }
    }

    @media (max-width:560px) {
        .kpi {
            grid-template-columns: 1fr;
        }

        .kpi-cell {
            border-left: none;
        }

        .kpi-cell:nth-child(n+2) {
            border-top: 1px solid var(--line);
        }

        .rp-head h1 {
            font-size: 24px;
        }

        .rp-tab {
            padding: 0 13px;
            font-size: 12.5px;
        }
    }
</style>

<div class="rp-scope">

    {{-- HEADER --}}
    <div class="rp-head">
        <div>
            <h1>Reports <span>& Analytics</span></h1>
            <div class="sub">Financial & Transaction + Operational Analytics — global filter below.</div>
        </div>
        <div class="head-right">
            <span class="period"><i data-feather="calendar"></i> {{ $startDate }} → {{ $endDate }}</span>
            <a href="{{ route('admin.reports.export', [
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                    'status'     => $status,
                    'search'     => $search,
                ]) }}"
               target="_blank" rel="noopener" class="btn-export">
                <i data-feather="download"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- TOOLBAR: TABS + PRESETS (SEJAJAR) --}}
    <div class="rp-toolbar">
        {{-- TABS --}}
        <div class="rp-tabs">
            <button type="button" class="rp-tab {{ $tab === 'financial' ? 'active' : '' }}" data-tab="financial">
                <i data-feather="dollar-sign"></i> Financial
            </button>
            <button type="button" class="rp-tab {{ $tab === 'occupancy' ? 'active' : '' }}" data-tab="occupancy">
                <i data-feather="clock"></i> Occupancy
            </button>
            <button type="button" class="rp-tab {{ $tab === 'service' ? 'active' : '' }}" data-tab="service">
                <i data-feather="package"></i> Service
            </button>
        </div>

        {{-- PRESETS --}}
        @php
            $today = now()->format('Y-m-d');
            $isDaily   = $startDate === $today && $endDate === $today;
            $isWeekly  = $startDate === now()->subDays(6)->format('Y-m-d') && $endDate === $today;
            $isMonthly = $startDate === now()->startOfMonth()->format('Y-m-d') && $endDate === $today;
            $isYearly  = $startDate === now()->startOfYear()->format('Y-m-d') && $endDate === $today;
        @endphp
        <div class="rp-presets">
            <button type="button" class="rp-preset {{ $isDaily ? 'active' : '' }}"
                    data-start="{{ $today }}" data-end="{{ $today }}">
                <i data-feather="sun"></i> Daily
            </button>
            <button type="button" class="rp-preset {{ $isWeekly ? 'active' : '' }}"
                    data-start="{{ now()->subDays(6)->format('Y-m-d') }}" data-end="{{ $today }}">
                <i data-feather="calendar"></i> Weekly
            </button>
            <button type="button" class="rp-preset {{ $isMonthly ? 'active' : '' }}"
                    data-start="{{ now()->startOfMonth()->format('Y-m-d') }}" data-end="{{ $today }}">
                <i data-feather="calendar"></i> Monthly
            </button>
            <button type="button" class="rp-preset {{ $isYearly ? 'active' : '' }}"
                    data-start="{{ now()->startOfYear()->format('Y-m-d') }}" data-end="{{ $today }}">
                <i data-feather="calendar"></i> Yearly
            </button>
        </div>
    </div>

    {{-- FILTER GLOBAL --}}
    <form method="GET" action="{{ route('admin.reports.index') }}" class="rp-filter" id="reportFilterForm">
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div class="field">
            <label>Start Date</label>
            <input type="date" name="start_date" id="inputStart" value="{{ $startDate }}" required>
        </div>
        <div class="field">
            <label>End Date</label>
            <input type="date" name="end_date" id="inputEnd" value="{{ $endDate }}" required>
        </div>
        <div class="field">
            <label>Status <span style="font-weight:400;opacity:.7">(Financial only)</span></label>
            <select name="status">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="no-show"   {{ $status === 'no-show'   ? 'selected' : '' }}>No-Show</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="field grow">
            <label>Search</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="Booking code / customer name...">
        </div>
        <div class="actions">
            <button type="submit" class="btn-apply">
                <i data-feather="filter" style="width:14px;height:14px"></i> Apply
            </button>
            <a href="{{ route('admin.reports.index') }}" class="btn-reset">
                <i data-feather="rotate-ccw" style="width:14px;height:14px"></i> Reset
            </a>
        </div>
    </form>

    {{-- ============================================
         TAB 1: FINANCIAL
    ============================================ --}}
    <div class="rp-panel {{ $tab === 'financial' ? 'active' : '' }}" id="panel-financial">

        <div class="card kpi">
            <div class="kpi-cell">
                <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="trending-up"></i></span> Net Revenue</div>
                <div class="kpi-val sm accent">Rp {{ number_format($summary['net_revenue'], 0, ',', '.') }}</div>
                <div class="kpi-sub">Paid + Completed + No-Show + Cancelled</div>
            </div>
            <div class="kpi-cell">
                <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="calendar"></i></span> Total Bookings</div>
                <div class="kpi-val">{{ number_format($summary['total_bookings'], 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $startDate }} → {{ $endDate }}</div>
            </div>
            <div class="kpi-cell">
                <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="alert-triangle"></i></span> No-Show Rate</div>
                <div class="kpi-val">{{ $summary['no_show_rate'] }}%</div>
                <div class="kpi-sub">{{ $summary['no_show_count'] }} of {{ $summary['total_bookings'] }} bookings</div>
            </div>
            <div class="kpi-cell">
                <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="x-circle"></i></span> Cancelled Rate</div>
                <div class="kpi-val">{{ $summary['cancelled_rate'] }}%</div>
                <div class="kpi-sub">{{ $summary['cancelled'] }} of {{ $summary['total_bookings'] }} bookings</div>
            </div>
        </div>

        <div class="row-2">
            <div class="card">
                <div class="ch">
                    <div>
                        <h3>Net Revenue Trend</h3>
                        <div class="sub">
                            @if($groupBy === 'day') Daily revenue in range.
                            @elseif($groupBy === 'week') Weekly revenue in range.
                            @else Monthly revenue in range.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="rp-canvas"><canvas id="chart-revenue"></canvas></div>
            </div>

            <div class="card">
                <div class="ch">
                    <div>
                        <h3>Status Distribution</h3>
                        <div class="sub">Transaction breakdown.</div>
                    </div>
                </div>
                <div class="donut-wrap">
                    <canvas id="chart-status"></canvas>
                    <div class="donut-center">
                        <b>{{ number_format($summary['total_bookings'], 0, ',', '.') }}</b>
                        <span>bookings</span>
                    </div>
                </div>
                <div>
                    <div class="lg-row"><i class="dot confirmed"></i><span class="nm">Paid</span><span class="ct">{{ $statusBreakdown['paid'] }}</span></div>
                    <div class="lg-row"><i class="dot completed"></i><span class="nm">Completed</span><span class="ct">{{ $statusBreakdown['completed'] }}</span></div>
                    <div class="lg-row"><i class="dot no-show"></i><span class="nm">No-Show</span><span class="ct">{{ $statusBreakdown['no-show'] }}</span></div>
                    <div class="lg-row"><i class="dot cancelled"></i><span class="nm">Cancelled</span><span class="ct">{{ $statusBreakdown['cancelled'] }}</span></div>
                </div>
            </div>
        </div>

        @if($bookings->count())
            <div class="card rp-table">
                <div class="ch">
                    <div>
                        <h3>Transaction Details</h3>
                        <div class="sub">All bookings matching the filter — exportable to CSV.</div>
                    </div>
                    <span class="tag">{{ $bookings->count() }} transactions</span>
                </div>
                <div class="tbl-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Items</th>
                                <th style="text-align:center">Slots</th>
                                <th style="text-align:right">Total</th>
                                <th style="text-align:right">Net</th>
                                <th style="text-align:center">Status</th>
                                <th style="text-align:center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                                @php
                                    $itemsStr = collect($b->items ?? [])
                                        ->map(fn($it) => ($it['name'] ?? '-') . ' ×' . ($it['qty'] ?? 1))
                                        ->implode(', ');
                                    $isRefund50 = $b->status === 'cancelled' && (int) $b->refund_percent === 50;
                                @endphp
                                <tr>
                                    <td><span class="rp-code">{{ $b->booking_code }}</span></td>
                                    <td>
                                        <div class="strong">{{ $b->customer_name }}</div>
                                        <div class="muted">{{ $b->customer_phone }}</div>
                                    </td>
                                    <td style="white-space:nowrap">{{ $b->booking_date ? $b->booking_date->format('d M Y') : '-' }}</td>
                                    <td><span class="rp-time">{{ $b->booking_time }}</span></td>
                                    <td class="rp-items">{{ $itemsStr ?: '-' }}</td>
                                    <td class="strong" style="text-align:center">{{ $b->countSlots() }}</td>
                                    <td class="strong" style="text-align:right;white-space:nowrap">
                                        Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align:right;white-space:nowrap">
                                        <div class="money-ok">Rp {{ number_format($b->net_revenue, 0, ',', '.') }}</div>
                                        @if($isRefund50)
                                            <div class="money-bad">(Refund 50%)</div>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        <span class="rp-badge {{ $b->status }}">{{ $b->status_label }}</span>
                                    </td>
                                    <td style="text-align:center">
                                        <a href="{{ route('admin.bookings.show', $b->id) }}" class="pill-view">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card empty-card">
                <div class="rp-empty">
                    <i data-feather="inbox"></i>
                    <div class="empty-text">No transactions in this date range.</div>
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================
         TAB 2: OCCUPANCY
    ============================================ --}}
    <div class="rp-panel {{ $tab === 'occupancy' ? 'active' : '' }}" id="panel-occupancy">

        @if($occupancy->count())
            <div class="card kpi">
                <div class="kpi-cell">
                    <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="trending-up"></i></span> Busiest Hour</div>
                    <div class="kpi-val accent">{{ $busiestTime['time'] ?? '-' }}</div>
                    <div class="kpi-sub">{{ $busiestTime['booked'] ?? 0 }} bookings • {{ $busiestTime['slots'] ?? 0 }} slots</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="trending-down"></i></span> Quietest Hour</div>
                    <div class="kpi-val">{{ $quietestTime['time'] ?? '-' }}</div>
                    <div class="kpi-sub">{{ $quietestTime['booked'] ?? 0 }} bookings • {{ $quietestTime['slots'] ?? 0 }} slots</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="calendar"></i></span> Total Bookings</div>
                    <div class="kpi-val">{{ number_format($occupancy->sum('booked'), 0, ',', '.') }}</div>
                    <div class="kpi-sub">{{ number_format($occupancy->sum('slots'), 0, ',', '.') }} slots used</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="dollar-sign"></i></span> Total Revenue</div>
                    <div class="kpi-val sm">Rp {{ number_format($occupancy->sum('revenue'), 0, ',', '.') }}</div>
                    <div class="kpi-sub">across all operating hours</div>
                </div>
            </div>

            <div class="card" style="margin-top:14px">
                <div class="ch">
                    <div>
                        <h3>Booking Distribution by Hour</h3>
                        <div class="sub">Bookings vs slots per hour — for staffing & schedule planning.</div>
                    </div>
                </div>
                <div class="rp-canvas"><canvas id="chart-occupancy"></canvas></div>
            </div>

            <div class="card rp-table">
                <div class="ch">
                    <div>
                        <h3>Hourly Breakdown</h3>
                        <div class="sub">Bookings, slots, and revenue per hour.</div>
                    </div>
                    <span class="tag">{{ $occupancy->count() }} hours</span>
                </div>
                <div class="tbl-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:120px">Hour</th>
                                <th style="width:120px;text-align:center">Bookings</th>
                                <th style="width:120px;text-align:center">Slots</th>
                                <th style="width:220px">Visual</th>
                                <th style="text-align:right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxSlots = $occupancy->max('slots') ?: 1; @endphp
                            @foreach($occupancy as $row)
                                <tr>
                                    <td><span class="rp-time">{{ $row['time'] }}</span></td>
                                    <td class="strong" style="text-align:center">{{ $row['booked'] }}</td>
                                    <td class="strong" style="text-align:center">{{ $row['slots'] }}</td>
                                    <td>
                                        <div class="rp-bar">
                                            <span style="width: {{ $row['slots'] > 0 ? ($row['slots'] / $maxSlots) * 100 : 0 }}%"></span>
                                        </div>
                                    </td>
                                    <td class="money-ok" style="text-align:right">
                                        Rp {{ number_format($row['revenue'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card empty-card" style="margin-top:0">
                <div class="rp-empty">
                    <i data-feather="clock"></i>
                    <div class="empty-text">No occupancy data in this date range.</div>
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================
         TAB 3: SERVICE
    ============================================ --}}
    <div class="rp-panel {{ $tab === 'service' ? 'active' : '' }}" id="panel-service">

        @if($services->count())
            @php
                $totalRev = $services->sum('revenue') ?: 1;
            @endphp

            <div class="card kpi">
                <div class="kpi-cell">
                    <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="package"></i></span> Treatment Types</div>
                    <div class="kpi-val">{{ $services->count() }}</div>
                    <div class="kpi-sub">sold in date range</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="shopping-bag"></i></span> Total Qty</div>
                    <div class="kpi-val">{{ number_format($services->sum('qty'), 0, ',', '.') }}</div>
                    <div class="kpi-sub">all treatments combined</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="clock"></i></span> Total Slots</div>
                    <div class="kpi-val">{{ number_format($services->sum('slots'), 0, ',', '.') }}</div>
                    <div class="kpi-sub">slots used</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-lbl"><span class="kpi-ico"><i data-feather="dollar-sign"></i></span> Total Revenue</div>
                    <div class="kpi-val sm accent">Rp {{ number_format($services->sum('revenue'), 0, ',', '.') }}</div>
                    <div class="kpi-sub">all treatments combined</div>
                </div>
            </div>

            <div class="row-svc">
                <div class="card">
                    <div class="ch">
                        <div>
                            <h3>Revenue per Treatment</h3>
                            <div class="sub">Top 8 treatments.</div>
                        </div>
                    </div>
                    <div class="rp-canvas" style="height:360px"><canvas id="chart-service"></canvas></div>
                </div>

                <div class="card rp-table compact">
                    <div class="ch">
                        <div>
                            <h3>Best-Selling Treatments</h3>
                            <div class="sub">For stock & service evaluation.</div>
                        </div>
                    </div>
                    <div class="tbl-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:50px;text-align:center">#</th>
                                    <th>Service / Treatment</th>
                                    <th style="text-align:center">Qty</th>
                                    <th style="text-align:center">Slots</th>
                                    <th style="text-align:right">Revenue</th>
                                    <th style="width:170px">Contribution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $i => $s)
                                    @php $pct = $s['revenue'] > 0 ? ($s['revenue'] / $totalRev) * 100 : 0; @endphp
                                    <tr>
                                        <td style="text-align:center;color:var(--muted)">{{ $i + 1 }}</td>
                                        <td class="strong">{{ $s['name'] }}</td>
                                        <td class="strong" style="text-align:center">{{ $s['qty'] }}</td>
                                        <td class="strong" style="text-align:center">{{ $s['slots'] }}</td>
                                        <td class="money-ok" style="text-align:right;white-space:nowrap">
                                            Rp {{ number_format($s['revenue'], 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <div class="share">
                                                <div class="rp-bar"><span style="width: {{ $pct }}%"></span></div>
                                                <em>{{ number_format($pct, 1) }}%</em>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card empty-card" style="margin-top:0">
                <div class="rp-empty">
                    <i data-feather="package"></i>
                    <div class="empty-text">No services sold in this date range.</div>
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.feather) feather.replace();
        setTimeout(function () { if (window.feather) feather.replace(); }, 100);
        setTimeout(function () { if (window.feather) feather.replace(); }, 500);

        /* ============================================
           COLOR HELPERS
        ============================================ */
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
        function lighten(hex, amt) {
            var n = parseInt(hex.slice(1), 16);
            var r = Math.min(255, ((n >> 16) & 255) + amt);
            var g = Math.min(255, ((n >> 8) & 255) + amt);
            var b = Math.min(255, (n & 255) + amt);
            return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
        }
        var P = getPrimaryHex();
        var P_LIGHT = lighten(P, 60);
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

        /* ============================================
           PRESET BUTTONS
        ============================================ */
        var presets = document.querySelectorAll('.rp-preset');
        var inputStart = document.getElementById('inputStart');
        var inputEnd   = document.getElementById('inputEnd');
        var filterForm = document.getElementById('reportFilterForm');

        presets.forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (inputStart) inputStart.value = this.dataset.start;
                if (inputEnd)   inputEnd.value   = this.dataset.end;
                if (filterForm) filterForm.submit();
            });
        });

        /* ============================================
           TABS
        ============================================ */
        var tabs = document.querySelectorAll('.rp-tab');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var target = this.dataset.tab;
                if (filterForm) {
                    var hiddenTab = filterForm.querySelector('input[name="tab"]');
                    if (hiddenTab) hiddenTab.value = target;
                    filterForm.submit();
                }
            });
        });

        /* ============================================
           CHART 1: REVENUE — smooth line + area
        ============================================ */
        var canvasRev = document.getElementById('chart-revenue');
        if (canvasRev && typeof Chart !== 'undefined') {
            var revData = @json($revenueChart);

            new Chart(canvasRev.getContext('2d'), {
                type: 'line',
                data: {
                    labels: revData.labels,
                    datasets: [{
                        label: 'Net Revenue',
                        data: revData.values,
                        borderColor: P,
                        backgroundColor: function (ctx) {
                            var chart = ctx.chart, area = chart.chartArea;
                            if (!area) return 'transparent';
                            var g = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
                            g.addColorStop(0, hexToRgba(P, isDark ? .38 : .25));
                            g.addColorStop(1, hexToRgba(P, 0));
                            return g;
                        },
                        fill: true,
                        borderWidth: 2.8,
                        tension: 0.4,
                        pointBackgroundColor: CARD_BG,
                        pointBorderColor: P,
                        pointBorderWidth: 2.5,
                        pointRadius: 0,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 3,
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
                            titleColor: 'rgba(255,255,255,.75)',
                            bodyColor: '#fff',
                            titleFont: { size: 11, weight: '500' },
                            bodyFont: { size: 13, weight: '700' },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                            caretSize: 6,
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
                            ticks: { color: TICK, font: { size: 11 }, maxRotation: 0 }
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

        /* ============================================
           CHART 2: STATUS — doughnut
        ============================================ */
        var canvasStatus = document.getElementById('chart-status');
        if (canvasStatus && typeof Chart !== 'undefined') {
            var statusData = @json($statusChart);
            new Chart(canvasStatus.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.values,
                        backgroundColor: [P, '#2f9e62', '#94a3b8', '#d9534f'],
                        borderWidth: 3,
                        borderColor: CARD_BG,
                        hoverOffset: 10,
                        hoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '74%',
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
                            padding: 10,
                            cornerRadius: 10,
                            displayColors: true,
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

        /* ============================================
           CHART 3: OCCUPANCY — grouped horizontal bar
           Bar 1 = Bookings (primary solid)
           Bar 2 = Slots    (primary lighten)
           Revenue → tooltip only (biar ga duplikat Financial)
        ============================================ */
        var canvasOcc = document.getElementById('chart-occupancy');
        if (canvasOcc && typeof Chart !== 'undefined') {
            var occData = @json($occupancy);
            if (occData.length > 0) {

                // Slot color = primary di-lighten (auto nyesuain theme + dark mode)
                var P_SOFT = lighten(P, isDark ? 45 : 75);

                new Chart(canvasOcc.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: occData.map(function (r) { return r.time; }),
                        datasets: [
                            {
                                label: 'Bookings',
                                data: occData.map(function (r) { return r.booked; }),
                                backgroundColor: P,
                                hoverBackgroundColor: P,
                                borderRadius: 4,
                                borderSkipped: false,
                                barPercentage: 0.85,
                                categoryPercentage: 0.7,
                            },
                            {
                                label: 'Slots',
                                data: occData.map(function (r) { return r.slots; }),
                                backgroundColor: P_SOFT,
                                hoverBackgroundColor: lighten(P, isDark ? 60 : 90),
                                borderRadius: 4,
                                borderSkipped: false,
                                barPercentage: 0.85,
                                categoryPercentage: 0.7,
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        animation: { duration: 1100, easing: 'easeOutQuart' },
                        layout: {
                            padding: { right: 20, left: 0, top: 6, bottom: 6 }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    color: getSlate(isDark ? 300 : 600),
                                    font: { size: 11.5, weight: '600' },
                                    boxWidth: 10,
                                    boxHeight: 10,
                                    usePointStyle: false,
                                    padding: 16,
                                }
                            },
                            tooltip: {
                                backgroundColor: TIP,
                                titleColor: 'rgba(255,255,255,.75)',
                                bodyColor: '#fff',
                                titleFont: { size: 11, weight: '500' },
                                bodyFont: { size: 12.5, weight: '600' },
                                padding: 12,
                                cornerRadius: 12,
                                displayColors: true,
                                boxWidth: 8,
                                boxHeight: 8,
                                boxPadding: 4,
                                callbacks: {
                                    label: function (ctx) {
                                        if (ctx.dataset.label === 'Bookings') {
                                            return ' Bookings: ' + ctx.parsed.x;
                                        }
                                        return ' Slots: ' + ctx.parsed.x;
                                    },
                                    afterBody: function (items) {
                                        var i = items[0].dataIndex;
                                        var rev = occData[i].revenue || 0;
                                        return '\nRevenue: Rp ' + new Intl.NumberFormat('id-ID').format(rev);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                stacked: false,
                                grid: { color: GRID, drawBorder: false },
                                ticks: {
                                    color: TICK,
                                    font: { size: 11 },
                                    precision: 0,
                                    stepSize: 1,
                                },
                                title: {
                                    display: false
                                }
                            },
                            y: {
                                stacked: false,
                                grid: { display: false, drawBorder: false },
                                ticks: {
                                    color: getSlate(isDark ? 300 : 600),
                                    font: { size: 11.5, weight: '600' }
                                }
                            }
                        }
                    }
                });
            }
        }

        /* ============================================
           CHART 4: SERVICE — horizontal bar
           Fix: kalo cuma 1 bar, kasih barThickness biar ga "kekotak"
        ============================================ */
        var canvasSvc = document.getElementById('chart-service');
        if (canvasSvc && typeof Chart !== 'undefined') {
            var svcAll = @json($services->values());
            var svcData = svcAll.slice(0, 8);

            if (svcData.length > 0) {
                // Kalo cuma 1 data, kasih warna gradient biar ga flat
                var svcColors;
                if (svcData.length === 1) {
                    svcColors = [hexToRgba(P, .85)];
                } else {
                    svcColors = svcData.map(function (_, i) {
                        var ratio = i / (svcData.length - 1);
                        var light = Math.round(ratio * 70);
                        return lighten(P, light);
                    });
                }

                // Plugin: data label di ujung bar
                var dataLabelPlugin = {
                    id: 'dataLabel',
                    afterDatasetsDraw: function (chart) {
                        var ctx = chart.ctx;
                        var meta = chart.getDatasetMeta(0);
                        var dataset = chart.data.datasets[0];

                        ctx.save();
                        ctx.font = '600 11px Montserrat, sans-serif';
                        ctx.fillStyle = getSlate(isDark ? 300 : 600);
                        ctx.textAlign = 'left';
                        ctx.textBaseline = 'middle';

                        meta.data.forEach(function (bar, i) {
                            var value = dataset.data[i];
                            if (!value) return;
                            var label = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            var x = bar.x + 10;
                            var y = bar.y;
                            ctx.fillText(label, x, y);
                        });
                        ctx.restore();
                    }
                };

                new Chart(canvasSvc.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: svcData.map(function (s) { return s.name; }),
                        datasets: [{
                            label: 'Revenue',
                            data: svcData.map(function (s) { return s.revenue; }),
                            backgroundColor: svcColors,
                            hoverBackgroundColor: P,
                            borderRadius: 8,
                            borderSkipped: false,
                            // Kalo cuma 1 data, pake barThickness fixed
                            barThickness: svcData.length === 1 ? 48 : undefined,
                            maxBarThickness: 56,
                            barPercentage: 0.7,
                            categoryPercentage: 0.85,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: { right: 100, top: 10, bottom: 10 }
                        },
                        animation: { duration: 1200, easing: 'easeOutQuart' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: TIP,
                                padding: 11,
                                cornerRadius: 10,
                                displayColors: false,
                                titleFont: { size: 12, weight: '600' },
                                bodyFont: { size: 12 },
                                callbacks: {
                                    label: function (ctx) {
                                        return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.x);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: GRID, drawBorder: false },
                                ticks: { color: TICK, font: { size: 11 }, callback: rupiahShort }
                            },
                            y: {
                                grid: { display: false, drawBorder: false },
                                ticks: { color: getSlate(isDark ? 300 : 600), font: { size: 11.5, weight: '600' } }
                            }
                        }
                    },
                    plugins: [dataLabelPlugin]
                });
            }
        }
    });
</script>
@endpush
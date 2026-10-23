@extends('../layout/' . $layout)

@section('subhead')
    <title>Calendar - Icewall</title>
@endsection

@section('subcontent')

@php
    $today = now()->format('Y-m-d');
@endphp

<style>

    /* =====================================================
        CALENDAR — modern panel, theme-aware
        ===================================================== */
    .cal-scope {
        --p: rgb(var(--color-primary));
        --p-soft: rgb(var(--color-primary) / .09);
        --p-tint: rgb(var(--color-primary) / .18);

        --ink: rgb(var(--color-slate-800));
        --text: rgb(var(--color-slate-600));
        --muted: rgb(var(--color-slate-500));
        --line: rgb(var(--color-slate-200));
        --card: #fff;
        --soft: rgb(var(--color-slate-100));

        --panel: linear-gradient(150deg, rgb(var(--color-primary) / .22), rgb(var(--color-primary) / .07));
        --panel-line: rgb(var(--color-primary) / .16);
        --band: rgb(var(--color-primary) / .14);

        --red: #b91c1c;
        --red-bg: rgba(239, 68, 68, .12);
        --amber: #b45309;
        --amber-bg: rgba(245, 158, 11, .16);
        --green: #15803d;
        --green-bg: rgba(34, 197, 94, .13);
        --violet: #6d28d9;
        --violet-bg: rgba(139, 92, 246, .13);

        color: var(--ink);
    }

    .dark .cal-scope {
        --ink: rgb(var(--color-slate-200));
        --text: rgb(var(--color-slate-300));
        --muted: rgb(var(--color-slate-400));
        --line: rgb(var(--color-darkmode-400));
        --card: rgb(var(--color-darkmode-600));
        --soft: rgb(var(--color-darkmode-700));

        --panel: linear-gradient(150deg, rgb(var(--color-primary) / .2), rgb(var(--color-primary) / .05));
        --panel-line: rgb(var(--color-primary) / .22);
        --band: rgb(var(--color-primary) / .13);

        --red: #fca5a5;
        --red-bg: rgba(248, 113, 113, .15);
        --amber: #fbbf24;
        --amber-bg: rgba(251, 191, 36, .16);
        --green: #4ade80;
        --green-bg: rgba(74, 222, 128, .14);
        --violet: #c4b5fd;
        --violet-bg: rgba(167, 139, 250, .16);
    }

    .cal-scope a {
        text-decoration: none;
    }

    .cal-scope button:focus-visible,
    .cal-scope a:focus-visible,
    .cal-scope .form-control:focus-visible {
        outline: 2px solid var(--p);
        outline-offset: 2px;
    }

    @keyframes calRise {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    @keyframes calFade {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .cal-scope *,
        .so-panel,
        .so-backdrop,
        .ag-modal-box {
            animation: none !important;
            transition: none !important;
        }
    }

    .cal-panel {
        margin-top: 22px;
        background: var(--panel);
        border: 1px solid var(--panel-line);
        border-radius: 28px;
        padding: 26px;
        animation: calRise .5s cubic-bezier(.22, .8, .3, 1) both;
    }

    .cal-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 22px;
    }

    .cal-head h1 {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -.03em;
        line-height: 1.1;
        color: var(--ink);
    }

    .cal-head .sub {
        font-size: 12.5px;
        color: var(--muted);
        margin-top: 6px;
    }

    .cal-stats {
        display: flex;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
    }

    .cal-stats .cs>span {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--muted);
    }

    .cal-stats .cs b {
        display: block;
        margin-top: 3px;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -.03em;
        line-height: 1;
        color: var(--ink);
        font-variant-numeric: tabular-nums;
    }

    .cal-stats .cs b em {
        font-style: normal;
    }

    .cal-stats .cs b small {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        letter-spacing: 0;
    }

    .cal-stats .cs-sep {
        width: 1px;
        align-self: stretch;
        background: var(--panel-line);
    }

    .cal-scope .alert {
        margin-bottom: 18px;
    }

    .cal-layout {
        display: grid;
        grid-template-columns: 300px minmax(0, 1fr);
        gap: 18px;
        align-items: stretch;
    }

    .cal-side {
        display: flex;
        flex-direction: column;
        gap: 16px;
        min-width: 0;
    }

    /* ===== MINI CALENDAR ===== */
    .mini {
        background: linear-gradient(145deg, rgb(var(--color-primary)), rgb(var(--color-primary) / .8));
        color: #fff;
        border-radius: 22px;
        padding: 18px 16px 16px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .22);
        animation: calRise .5s .08s cubic-bezier(.22, .8, .3, 1) both;
    }

    .mini-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding: 0 2px;
    }

    .mini-head b {
        font-size: 14.5px;
        font-weight: 700;
        letter-spacing: -.01em;
    }

    .mini-nav {
        display: flex;
        gap: 6px;
    }

    .mini-nav a {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .2);
        color: #fff;
        transition: background .15s, color .15s;
    }

    .mini-nav a:hover {
        background: #fff;
        color: var(--p);
    }

    .mini-nav a svg {
        width: 14px;
        height: 14px;
    }

    .mini-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
        text-align: center;
    }

    .mini-grid .mh {
        font-size: 10.5px;
        font-weight: 700;
        opacity: .7;
        padding: 4px 0 6px;
    }

    .mini-grid .mc {
        position: relative;
        aspect-ratio: 1;
        border: none;
        background: transparent;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        font-family: inherit;
        border-radius: 50%;
        cursor: pointer;
        padding: 0;
        transition: background .15s, transform .15s, color .15s;
    }

    .mini-grid .mc:hover {
        background: rgba(255, 255, 255, .24);
        transform: scale(1.08);
    }

    .mini-grid .mc.dim {
        opacity: .4;
    }

    .mini-grid .mc.closed {
        background: rgba(239, 68, 68, .5);
    }

    .mini-grid .mc.today {
        background: #fff;
        color: var(--p);
        font-weight: 800;
    }

    .mini-grid .mc.sel {
        box-shadow: 0 0 0 2px #fff;
    }

    .mini-grid .mc.has::after,
    .mini-grid .mc.ag::before {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        width: 4px;
        height: 4px;
        margin-left: -2px;
        border-radius: 50%;
    }

    .mini-grid .mc.has::after {
        background: #fff;
    }

    .mini-grid .mc.ag::before {
        background: #ddd6fe;
    }

    .mini-grid .mc.has.ag::after {
        margin-left: -6px;
    }

    .mini-grid .mc.has.ag::before {
        margin-left: 2px;
    }

    .mini-grid .mc.today.has::after {
        background: var(--p);
    }

    .mini-grid .mc.today.ag::before {
        background: #8b5cf6;
    }

    /* ===== SIDE CARD ===== */
    .side-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 16px 18px;
        animation: calRise .5s cubic-bezier(.22, .8, .3, 1) both;
    }

    .side-card.d1 {
        animation-delay: .14s;
    }

    .side-card.d2 {
        animation-delay: .2s;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .side-card h3 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 12px;
        color: var(--ink);
        letter-spacing: -.01em;
    }

    .side-card h3 .pill {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 99px;
        background: var(--p-soft);
        color: var(--p);
    }

    .lg-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 0;
        border-top: 1px solid var(--line);
        font-size: 12.5px;
        color: var(--text);
    }

    .lg-row:first-of-type {
        border-top: none;
        padding-top: 2px;
    }

    .lg-row .dot {
        width: 12px;
        height: 12px;
        border-radius: 4px;
        flex: none;
    }

    .lg-row .dot.primary {
        background: var(--p);
    }

    .lg-row .dot.danger {
        background: rgba(239, 68, 68, .55);
    }

    .lg-row .dot.warning {
        background: rgba(245, 158, 11, .6);
    }

    .lg-row .dot.violet {
        background: rgba(139, 92, 246, .65);
    }

    .lg-row .ct {
        margin-left: auto;
        font-weight: 800;
        color: var(--ink);
        font-variant-numeric: tabular-nums;
    }

    .sd-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-height: 300px;
        overflow-y: auto;
    }

    .sd-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 12px;
        background: var(--soft);
        transition: background .15s;
    }

    .sd-item:hover {
        background: var(--p-soft);
    }

    .sd-item .sd-date {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--ink);
    }

    .sd-item .sd-note {
        font-size: 11px;
        color: var(--muted);
        margin-top: 3px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 150px;
    }

    .sd-item .sd-info {
        min-width: 0;
    }

    .sd-del {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        flex: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid var(--line);
        color: var(--muted);
        cursor: pointer;
        transition: background .15s, color .15s, border-color .15s;
    }

    .sd-del svg {
        width: 13px;
        height: 13px;
    }

    .sd-del:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
    }

    .sd-empty {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 18px 8px;
        font-size: 12px;
        color: var(--muted);
        border: 1px dashed var(--line);
        border-radius: 14px;
    }

    .so-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 6px;
        vertical-align: middle;
    }

    .so-badge svg {
        width: 13px;
        height: 13px;
    }

    .so-badge.closed {
        background: var(--red-bg);
        color: var(--red);
    }

    .so-badge.open {
        background: var(--green-bg);
        color: var(--green);
    }

    .so-badge.custom {
        background: var(--amber-bg);
        color: var(--amber);
    }

    .so-badge.past {
        background: rgba(148, 163, 184, .18);
        color: var(--muted);
    }

    /* ===== KALENDER UTAMA ===== */
    .cal-main {
        background: var(--band);
        border-radius: 24px;
        padding: 6px;
        min-width: 0;
        display: flex;
        flex-direction: column;
        animation: calRise .5s .1s cubic-bezier(.22, .8, .3, 1) both;
    }

    .cal-main-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        padding: 12px 16px 14px;
    }

    .cal-main-head h2 {
        font-size: 16px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -.02em;
        color: var(--ink);
    }

    .cal-main-head .mh-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 32px;
        padding: 0 15px;
        border-radius: 99px;
        background: var(--p);
        color: #fff;
        border: none;
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
        font-family: inherit;
        transition: filter .15s, transform .15s, box-shadow .15s;
    }

    .btn-add:hover {
        filter: brightness(.92);
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgb(var(--color-primary) / .3);
    }

    .btn-add svg {
        width: 14px;
        height: 14px;
    }

    .btn-today {
        height: 32px;
        padding: 0 15px;
        border-radius: 99px;
        border: 1px solid var(--panel-line);
        background: var(--card);
        font-size: 12px;
        font-weight: 700;
        font-family: inherit;
        color: var(--text);
        cursor: pointer;
        transition: all .15s;
    }

    .btn-today:hover {
        border-color: var(--p);
        color: var(--p);
    }

    .btn-today.off {
        opacity: .45;
        pointer-events: none;
    }

    .cal-nav {
        display: flex;
        gap: 6px;
    }

    .cal-nav a {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--card);
        border: 1px solid var(--panel-line);
        color: var(--text);
        transition: all .15s;
    }

    .cal-nav a:hover {
        background: var(--p);
        border-color: var(--p);
        color: #fff;
    }

    .cal-nav a svg {
        width: 15px;
        height: 15px;
    }

    .cal-wrap {
        background: var(--card);
        border-radius: 18px;
        overflow: hidden;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .cal-grid-head {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        border-bottom: 1px solid var(--line);
    }

    .cal-grid-head div {
        padding: 14px 8px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--muted);
    }

    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-auto-rows: minmax(108px, 1fr);
        flex: 1;
    }

    .cal-day {
        position: relative;
        padding: 9px 9px 10px;
        border-right: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
        cursor: pointer;
        background: var(--card);
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
        transition: background .18s, box-shadow .18s;
        animation: calFade .45s ease both;
        animation-delay: calc(var(--n, 0) * 9ms + 150ms);
    }

    .cal-day:nth-child(7n) {
        border-right: none;
    }

    .cal-day:nth-last-child(-n+7) {
        border-bottom: none;
    }

    .cal-day:hover {
        background: var(--p-soft);
    }

    .cal-day.dim {
        opacity: .38;
    }

    .cal-day.sel {
        background: var(--p-soft);
        box-shadow: inset 0 0 0 2px var(--p);
    }

    .cal-day.past {
        background: rgba(148, 163, 184, .04);
    }

    .cal-day.past:hover {
        background: rgba(148, 163, 184, .1);
    }

    .cal-day.past .num {
        color: var(--muted);
    }

    .cal-day .num {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        flex: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--ink);
        transition: transform .2s cubic-bezier(.22, .8, .3, 1);
    }

    .cal-day:hover .num {
        transform: scale(1.12);
    }

    .cal-day.today .num {
        background: var(--p);
        color: #fff;
        font-weight: 800;
    }

    .cal-day.closed {
        background: repeating-linear-gradient(135deg, rgba(239, 68, 68, .07) 0 8px, transparent 8px 16px), var(--card);
    }

    .cal-day.closed:hover {
        background: var(--red-bg);
    }

    .cal-day.closed .num {
        color: var(--red);
        font-weight: 700;
    }

    .cal-day.closed.today .num {
        background: var(--p);
        color: #fff;
    }

    .cal-day .evs {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: auto;
        min-width: 0;
    }

    .ev {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 10.5px;
        font-weight: 700;
        line-height: 1.2;
        transition: transform .18s;
        min-width: 0;
    }

    .cal-day:hover .ev {
        transform: translateX(2px);
    }

    .ev-book {
        background: var(--p);
        color: #fff;
    }

    .ev-book b {
        font-weight: 800;
    }

    .ev-closed {
        background: var(--red-bg);
        color: var(--red);
        font-size: 10px;
        align-items: flex-start;
        padding: 5px 8px;
        line-height: 1.35;
    }

    .ev-closed b {
        font-weight: 800;
    }

    .ev-closed span {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
    }

    .ev-agenda {
        background: var(--violet-bg);
        color: var(--violet);
    }

    .ev-agenda b {
        font-weight: 800;
    }

    .cal-day .note {
        font-size: 10px;
        color: var(--muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* =====================================================
        SLIDE-OVER
        ===================================================== */
    .so-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .5);
        backdrop-filter: blur(2px);
        z-index: 9990;
        opacity: 0;
        visibility: hidden;
        transition: opacity .25s, visibility .25s;
    }

    .so-backdrop.open {
        opacity: 1;
        visibility: visible;
    }

    .so-panel {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 480px;
        max-width: 100vw;
        background: var(--card);
        border-left: 1px solid var(--line);
        border-radius: 28px 0 0 28px;
        z-index: 9991;
        transform: translateX(105%);
        transition: transform .32s cubic-bezier(.22, .8, .3, 1);
        display: flex;
        flex-direction: column;
        box-shadow: -18px 0 50px rgba(15, 23, 42, .22);
        color: var(--ink);
        overflow: hidden;
    }

    .so-panel.open {
        transform: translateX(0);
    }

    .so-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 24px 24px 20px;
        background: linear-gradient(var(--band), var(--band)), var(--card);
        border-bottom: 1px solid var(--panel-line);
    }

    .so-eyebrow {
        display: block;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: var(--p);
        margin-bottom: 6px;
    }

    .so-head h3 {
        font-size: 19px;
        font-weight: 800;
        margin: 0;
        color: var(--ink);
        letter-spacing: -.02em;
        line-height: 1.25;
    }

    .so-head .close {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        flex: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--card);
        border: 1px solid var(--panel-line);
        cursor: pointer;
        color: var(--muted);
        transition: background .15s, color .15s, transform .25s;
    }

    .so-head .close svg {
        width: 16px;
        height: 16px;
    }

    .so-head .close:hover {
        background: var(--p);
        border-color: var(--p);
        color: #fff;
        transform: rotate(90deg);
    }

    .so-body {
        flex: 1;
        overflow-y: auto;
        padding: 22px 24px 28px;
        background: var(--card);
    }

    .so-section {
        margin-bottom: 26px;
    }

    .so-section:last-child {
        margin-bottom: 0;
    }

    .so-section h4 {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--muted);
        margin: 0 0 12px;
    }

    .so-body>.so-section:first-child {
        background: var(--soft);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 18px;
    }

    .so-body .so-badge {
        font-size: 12px;
        padding: 6px 13px;
        margin-left: 0;
    }

    .so-note {
        font-size: 12px;
        color: var(--muted);
        margin-top: 8px;
        line-height: 1.5;
    }

    .so-mt {
        margin-top: 14px;
    }

    .so-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .so-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 16px;
        border-radius: 14px;
        border: none;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .15s;
    }

    .so-btn svg {
        width: 15px;
        height: 15px;
    }

    .so-btn.primary {
        background: var(--p);
        color: #fff;
    }

    .so-btn.primary:hover {
        filter: brightness(.92);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgb(var(--color-primary) / .3);
    }

    .so-btn.danger {
        background: #dc2626;
        color: #fff;
    }

    .so-btn.danger:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(220, 38, 38, .28);
    }

    .so-btn.ghost {
        background: transparent;
        border: 1px solid var(--line);
        color: var(--text);
    }

    .so-btn.ghost:hover {
        border-color: var(--p);
        color: var(--p);
        background: var(--p-soft);
    }

    .so-btn[disabled] {
        opacity: .4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .so-timeline {
        display: flex;
        flex-direction: column;
    }

    .so-slot {
        display: grid;
        grid-template-columns: 55px 1fr;
        gap: 10px;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px dashed var(--line);
    }

    .so-slot:last-child {
        border-bottom: none;
    }

    .so-slot .jam {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink);
        padding-top: 9px;
        font-variant-numeric: tabular-nums;
    }

    .so-slot .items {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .so-empty-slot {
        font-size: 11.5px;
        color: var(--muted);
        padding: 9px 12px;
        font-style: italic;
    }

    .so-booking {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 11px;
        background: var(--p-soft);
        border: 1px solid rgb(var(--color-primary) / .2);
        text-decoration: none;
        color: inherit;
        transition: transform .15s, background .15s;
    }

    .so-booking:hover {
        transform: translateX(3px);
        background: rgb(var(--color-primary) / .14);
    }

    .so-booking .info {
        flex: 1;
        min-width: 0;
    }

    .so-booking .name {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .so-booking .meta {
        font-size: 10.5px;
        color: var(--muted);
        margin-top: 2px;
    }

    .so-booking .st {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: capitalize;
        flex: none;
    }

    .so-booking .st.paid {
        background: var(--p);
        color: #fff;
    }

    .so-booking .st.completed {
        background: #16a34a;
        color: #fff;
    }

    .so-booking .st.cancelled {
        background: #dc2626;
        color: #fff;
    }

    .so-booking .st.pending {
        background: #d97706;
        color: #fff;
    }

    .so-booking .st.no-show {
        background: #64748b;
        color: #fff;
    }

    .so-agenda {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 11px;
        background: var(--violet-bg);
        border: 1px solid rgba(139, 92, 246, .3);
        margin-bottom: 6px;
    }

    .so-slot .items .so-agenda {
        margin-bottom: 0;
    }

    .so-agenda>svg {
        width: 14px;
        height: 14px;
        color: var(--violet);
        flex: none;
    }

    .so-agenda .info {
        flex: 1;
        min-width: 0;
    }

    .so-agenda .ttl {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--ink);
    }

    .so-agenda .desc {
        font-size: 11px;
        color: var(--muted);
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .so-agenda .acts {
        display: flex;
        gap: 4px;
        flex: none;
    }

    .so-agenda .acts button {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        background: rgba(120, 120, 140, .16);
        color: var(--text);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .15s;
    }

    .so-agenda .acts button svg {
        width: 13px;
        height: 13px;
    }

    .so-agenda .acts button:hover {
        background: var(--p);
        color: #fff;
    }

    .so-agenda .acts button.danger:hover {
        background: #dc2626;
    }

    /* =====================================================
        MODAL — Agenda & Libur
        ===================================================== */
    .ag-modal {
        position: fixed;
        inset: 0;
        z-index: 10010;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, .6);
        backdrop-filter: blur(3px);
        padding: 20px;
    }

    .ag-modal.open {
        display: flex;
    }

    .ag-modal-box {
        background: var(--card);
        border-radius: 24px;
        width: 100%;
        max-width: 480px;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .38);
        overflow: hidden;
        animation: agPop .26s cubic-bezier(.22, .8, .3, 1);
        color: var(--ink);
        border: 1px solid var(--line);
    }

    @keyframes agPop {
        from {
            opacity: 0;
            transform: scale(.96) translateY(8px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .ag-modal-head {
        padding: 20px 24px 18px;
        background: linear-gradient(var(--band), var(--band)), var(--card);
        border-bottom: 1px solid var(--panel-line);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .ag-eyebrow {
        display: block;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: var(--p);
        margin-bottom: 5px;
    }

    .ag-modal-head h3 {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
        color: var(--ink);
        letter-spacing: -.02em;
    }

    .ag-modal-head .close {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        flex: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--card);
        border: 1px solid var(--panel-line);
        cursor: pointer;
        color: var(--muted);
        transition: background .15s, color .15s, transform .25s;
    }

    .ag-modal-head .close svg {
        width: 15px;
        height: 15px;
    }

    .ag-modal-head .close:hover {
        background: var(--p);
        border-color: var(--p);
        color: #fff;
        transform: rotate(90deg);
    }

    .ag-modal-body {
        padding: 22px 24px 8px;
        overflow-y: auto;
        background: var(--card);
    }

    .ag-modal-foot {
        padding: 16px 24px 20px;
        background: var(--card);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .ag-field {
        margin-bottom: 16px;
    }

    .ag-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .ag-field label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 7px;
    }

    .ag-field label em {
        font-style: normal;
        font-weight: 500;
        color: var(--muted);
    }

    .ag-field input,
    .ag-field textarea {
        width: 100%;
        padding: 11px 14px;
        border-radius: 12px;
        border: 1px solid var(--line);
        background: var(--card);
        font-size: 13px;
        color: var(--ink);
        outline: none;
        font-family: inherit;
        transition: border-color .15s, box-shadow .15s;
    }

    .ag-field input::placeholder,
    .ag-field textarea::placeholder {
        color: var(--muted);
        opacity: .7;
    }

    .ag-field input:focus,
    .ag-field textarea:focus {
        border-color: var(--p);
        box-shadow: 0 0 0 3px var(--p-soft);
    }

    .dark .ag-field input,
    .dark .ag-field textarea {
        color-scheme: dark;
    }

    .ag-field textarea {
        resize: vertical;
        min-height: 78px;
    }

    .ag-btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all .15s;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .ag-btn svg {
        width: 14px;
        height: 14px;
    }

    .ag-btn-primary {
        background: var(--p);
        color: #fff;
    }

    .ag-btn-primary:hover {
        filter: brightness(.92);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgb(var(--color-primary) / .3);
    }

    .ag-btn-outline {
        background: transparent;
        border: 1px solid var(--line);
        color: var(--text);
    }

    .ag-btn-outline:hover {
        border-color: var(--p);
        color: var(--p);
    }

    @media (max-width: 1100px) {
        .cal-layout {
            grid-template-columns: 1fr;
        }

        .cal-side {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .cal-panel {
            padding: 16px;
            border-radius: 22px;
        }

        .cal-head h1 {
            font-size: 23px;
        }

        .cal-stats {
            gap: 14px;
        }

        .cal-stats .cs b {
            font-size: 21px;
        }

        .cal-grid {
            grid-auto-rows: minmax(80px, 1fr);
        }

        .cal-day {
            padding: 6px 5px 6px;
            gap: 4px;
        }

        .cal-day .num {
            width: 22px;
            height: 22px;
            font-size: 11px;
        }

        .ev {
            padding: 3px 5px;
            font-size: 9.5px;
        }

        .ev .lb {
            display: none;
        }

        .ev-closed {
            font-size: 8px;
            letter-spacing: 0;
            padding: 3px 3px;
        }

        .cal-day .note {
            display: none;
        }

        .so-panel {
            width: 100vw;
            border-radius: 0;
        }

        .so-slot {
            grid-template-columns: 45px 1fr;
            gap: 8px;
        }
    }

    @media (max-width: 480px) {
        .ag-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .ag-modal-body,
        .ag-modal-head,
        .ag-modal-foot {
            padding-left: 18px;
            padding-right: 18px;
        }
    }

</style>

@php
    $curDays     = collect($days)->where('is_current', true);
    $totalBook   = $curDays->sum('bookings');
    $totalAgenda = $curDays->sum('agendas');
    $closedCount = $curDays->where('is_closed', true)->count();
    $bookedDays  = $curDays->where('bookings', '>', 0)->count();
    $agendaDays  = $curDays->where('agendas', '>', 0)->count();
    $busiest     = $curDays->sortByDesc('bookings')->first();
@endphp

<div class="cal-scope">
    <div class="cal-panel">

        {{-- HEADER --}}
        <div class="cal-head">
            <div>
                <h1>Calendar</h1>
                <div class="sub">Bookings, Schedule, and Holiday Dates</div>
            </div>
            <div class="cal-stats">
                <div class="cs">
                    <span>Bookings this month</span>
                    <b><em data-count="{{ $totalBook }}">{{ $totalBook }}</em></b>
                </div>
                <div class="cs-sep"></div>
                <div class="cs">
                    <span>Agendas this month</span>
                    <b><em data-count="{{ $totalAgenda }}">{{ $totalAgenda }}</em></b>
                </div>
                <div class="cs-sep"></div>
                <div class="cs">
                    <span>Holiday Dates</span>
                    <b><em data-count="{{ $closedCount }}">{{ $closedCount }}</em> <small>dates</small></b>
                </div>
                <div class="cs-sep"></div>
                <div class="cs">
                    <span>Busiest Day</span>
                    <b>
                        @if($busiest && $busiest['bookings'] > 0)
                            {{ \Carbon\Carbon::parse($busiest['date'])->format('j M') }}
                        @else
                            —
                        @endif
                    </b>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-soft-success">
                <i data-feather="check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-soft-danger">
                <i data-feather="alert-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="cal-layout">

            {{-- ===== KOLOM KIRI ===== --}}
            <div class="cal-side">

                {{-- Mini calendar --}}
                <div class="mini">
                    <div class="mini-head">
                        <b>{{ $cursor->format('F Y') }}</b>
                        <div class="mini-nav">
                            <a href="{{ route('admin.calendar.index', ['month' => $prevMonth]) }}"><i data-feather="chevron-left"></i></a>
                            <a href="{{ route('admin.calendar.index', ['month' => $nextMonth]) }}"><i data-feather="chevron-right"></i></a>
                        </div>
                    </div>
                    <div class="mini-grid">
                        @foreach(['S','M','T','W','T','F','S'] as $w)
                            <span class="mh">{{ $w }}</span>
                        @endforeach
                        @foreach($days as $d)
                            <button type="button"
                                    class="mc {{ !$d['is_current'] ? 'dim' : '' }} {{ $d['is_today'] ? 'today' : '' }} {{ $d['is_closed'] ? 'closed' : '' }} {{ $d['bookings'] > 0 ? 'has' : '' }} {{ $d['agendas'] > 0 ? 'ag' : '' }}"
                                    data-date="{{ $d['date'] }}"
                                    @if($d['special_note']) title="{{ $d['special_note'] }}" @endif
                                    onclick="openDay('{{ $d['date'] }}')">{{ $d['day'] }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="side-card d1">
                    <h3>Description <span class="pill">{{ $cursor->format('M') }}</span></h3>
                    <div class="lg-row"><span class="dot primary"></span> Has bookings <span class="ct">{{ $bookedDays }}</span></div>
                    <div class="lg-row"><span class="dot violet"></span> Has agenda <span class="ct">{{ $agendaDays }}</span></div>
                    <div class="lg-row"><span class="dot danger"></span> Holiday <span class="ct">{{ $closedCount }}</span></div>
                </div>

                {{-- List special dates --}}
                <div class="side-card d2">
                    <h3>Holiday Dates This Month <span class="pill">{{ $specialDates->count() }}</span></h3>
                    @if($specialDates->count())
                        <div class="sd-list">
                            @foreach($specialDates as $sd)
                                <div class="sd-item">
                                    <div class="sd-info">
                                        <div class="sd-date">
                                            {{ $sd->start_date->format('d M') }}
                                            @if($sd->end_date && $sd->end_date->ne($sd->start_date))
                                                – {{ $sd->end_date->format('d M') }}
                                            @endif
                                            <span class="so-badge closed">Holiday</span>
                                        </div>
                                        @if($sd->notes)
                                            <div class="sd-note">{{ $sd->notes }}</div>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.calendar.destroy', $sd->id) }}" method="POST"
                                          onsubmit="return confirm('Reopen this date?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="sd-del" title="Reopen this date">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="sd-empty">No holiday dates available.</div>
                    @endif
                </div>

            </div>

            {{-- ===== KALENDER UTAMA ===== --}}
            <div class="cal-main">
                <div class="cal-main-head">
                    <h2>{{ $cursor->format('F Y') }}</h2>
                    <div class="mh-right">
                        <button type="button" class="btn-add" onclick="openLiburModal()">
                            <i data-feather="lock"></i> Close / Holiday
                        </button>
                        <button type="button" class="btn-today" id="btnToday" onclick="openToday()">Today</button>
                        <div class="cal-nav">
                            <a href="{{ route('admin.calendar.index', ['month' => $prevMonth]) }}">
                                <i data-feather="chevron-left"></i>
                            </a>
                            <a href="{{ route('admin.calendar.index', ['month' => $nextMonth]) }}">
                                <i data-feather="chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="cal-wrap">
                    <div class="cal-grid-head">
                        <div>Sun</div><div>Mon</div><div>Tue</div>
                        <div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                    </div>
                    <div class="cal-grid">
                        @foreach($days as $d)
                            @php
                                $isPast = $d['date'] < $today;
                            @endphp
                            <div class="cal-day {{ !$d['is_current'] ? 'dim' : '' }} {{ $d['is_today'] ? 'today' : '' }} {{ $d['is_closed'] ? 'closed' : '' }} {{ $isPast ? 'past' : '' }}"
                                 style="--n: {{ $loop->index }}"
                                 data-date="{{ $d['date'] }}"
                                 onclick="openDay('{{ $d['date'] }}')">
                                <div class="num">{{ $d['day'] }}</div>

                                <div class="evs">
                                    @if($d['is_closed'])
                                        <div class="ev ev-closed" title="{{ $d['special_note'] ?? 'Day Off' }}">
                                            <i data-feather="lock" style="width:11px;height:11px;flex:none;margin-top:1px;"></i>
                                            <span>{{ $d['special_note'] ?? 'Day Off' }}</span>
                                        </div>
                                    @endif

                                    @if($d['bookings'] > 0)
                                        <div class="ev ev-book"><b>{{ $d['bookings'] }}</b> <span class="lb">booking</span></div>
                                    @endif

                                    @if($d['agendas'] > 0)
                                        <div class="ev ev-agenda"><b>{{ $d['agendas'] }}</b> <span class="lb">schedule</span></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SLIDE-OVER — detail tanggal --}}
<div class="cal-scope" id="soRoot">
    <div class="so-backdrop" id="soBackdrop" onclick="closeDay()"></div>
    <div class="so-panel" id="soPanel" role="dialog" aria-modal="true" aria-labelledby="soDate">
        <div class="so-head">
            <div>
                <span class="so-eyebrow">Date Details</span>
                <h3 id="soDate">—</h3>
            </div>
            <button type="button" class="close" onclick="closeDay()" aria-label="Close">
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="so-body" id="soBody">
            <div class="so-empty-slot">Loading...</div>
        </div>
    </div>
</div>

{{-- MODAL LIBUR --}}
<div class="cal-scope" id="liburRoot">
    <div class="ag-modal" id="liburModal" onclick="if(event.target===this) closeLiburModal()">
        <div class="ag-modal-box" role="dialog" aria-modal="true" aria-labelledby="liburTitle">
            <div class="ag-modal-head">
                <div>
                    <span class="ag-eyebrow">Close / Holiday</span>
                    <h3 id="liburTitle">Set Holiday Date</h3>
                </div>
                <button type="button" class="close" onclick="closeLiburModal()" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.calendar.store') }}">
                @csrf
                <input type="hidden" name="type" value="closed">
                <div class="ag-modal-body">
                    <div class="ag-row">
                        <div class="ag-field">
                            <label>Start <span style="color:#dc2626">*</span></label>
                            <input type="date" name="start_date" id="liburStart" required
                                   min="{{ $today }}">
                        </div>
                        <div class="ag-field">
                            <label>End <em>(optional)</em></label>
                            <input type="date" name="end_date" id="liburEnd"
                                   min="{{ $today }}">
                        </div>
                    </div>
                    <div class="ag-field" style="margin-top:-6px">
                        <span style="font-size:11px;color:var(--muted)">Leave "End" empty if it's only one day off.</span>
                    </div>
                    <div class="ag-field">
                        <label>Reason / Explanation <em>(optional)</em></label>
                        <textarea name="notes" maxlength="500" placeholder="e.g. Nyepi Holiday, Maintenance, National Holiday..."></textarea>
                    </div>
                </div>
                <div class="ag-modal-foot">
                    <button type="button" class="ag-btn ag-btn-outline" onclick="closeLiburModal()">Cancel</button>
                    <button type="submit" class="ag-btn ag-btn-primary">
                        <i data-feather="lock"></i> Closing Date
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL AGENDA --}}
<div class="cal-scope" id="agRoot">
    <div class="ag-modal" id="agModal" onclick="if(event.target===this) closeAgendaModal()">
        <div class="ag-modal-box" role="dialog" aria-modal="true" aria-labelledby="agModalTitle">
            <div class="ag-modal-head">
                <div>
                    <span class="ag-eyebrow">Schedule</span>
                    <h3 id="agModalTitle">Add Schedule</h3>
                </div>
                <button type="button" class="close" onclick="closeAgendaModal()" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="agForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="agMethod" value="POST">
                <div class="ag-modal-body">
                    <div class="ag-field">
                        <label>Title <span style="color:#dc2626">*</span></label>
                        <input type="text" name="title" id="agTitle" maxlength="150" placeholder="e.g. Vendor meeting" required>
                    </div>
                    <div class="ag-row">
                        <div class="ag-field">
                            <label>Date <span style="color:#dc2626">*</span></label>
                            <input type="date" name="date" id="agDate" required
                                   min="{{ $today }}">
                        </div>
                        <div class="ag-field">
                            <label>Time <em>(optional)</em></label>
                            <input type="time" name="time" id="agTime">
                        </div>
                    </div>
                    <div class="ag-field" style="margin-top:-6px">
                        <span style="font-size:11px;color:var(--muted)">Leave the time empty if it's an all-day event.</span>
                    </div>
                    <div class="ag-field">
                        <label>Description <em>(optional)</em></label>
                        <textarea name="description" id="agDesc" maxlength="1000" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="ag-modal-foot">
                    <button type="button" class="ag-btn ag-btn-outline" onclick="closeAgendaModal()">Cancel</button>
                    <button type="submit" class="ag-btn ag-btn-primary" id="agSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var DAY_URL_TPL    = @json(route('admin.calendar.day', ['date' => '__DATE__']));
    var STORE_URL      = @json(route('admin.calendar.store'));
    var AGENDA_STORE   = @json(route('admin.calendar.agenda.store'));
    var AGENDA_UPDATE  = @json(route('admin.calendar.agenda.update', ['id' => '__ID__']));
    var AGENDA_DELETE  = @json(route('admin.calendar.agenda.delete', ['id' => '__ID__']));
    var CSRF           = @json(csrf_token());
    var TODAY          = @json($today);

    var currentDayData = null;

    // ===== HELPER: cek tanggal kelewat =====
    function isPastDate(dateStr) {
        return dateStr < TODAY;
    }

    // ===== MOUNT KE <body> =====
    function mountRoots() {
        ['soRoot', 'agRoot', 'liburRoot'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el && el.parentNode !== document.body) document.body.appendChild(el);
        });
    }
    mountRoots();

    // ===== SLIDE-OVER =====
    function openDay(date) {
        markSelected(date);
        var url = DAY_URL_TPL.replace('__DATE__', date);
        document.getElementById('soBackdrop').classList.add('open');
        document.getElementById('soPanel').classList.add('open');
        document.getElementById('soDate').textContent = 'Loading...';
        document.getElementById('soBody').innerHTML = '<div class="so-empty-slot">Loading...</div>';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                currentDayData = data;
                renderDay(data);
            })
            .catch(function () {
                document.getElementById('soBody').innerHTML = '<div class="so-empty-slot" style="color:#dc2626">Failed to load.</div>';
            });
    }

    function closeDay() {
        document.getElementById('soBackdrop').classList.remove('open');
        document.getElementById('soPanel').classList.remove('open');
        clearSelected();
        currentDayData = null;
    }

    function escHtml(s) {
        if (s === null || s === undefined) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function renderDay(data) {
        document.getElementById('soDate').textContent = data.date_label;

        var past = isPastDate(data.date);
        var html = '';

        // ===== STATUS =====
        html += '<div class="so-section">';
        html += '<h4>Status</h4>';

        if (data.is_closed) {
            html += '<span class="so-badge closed"><i data-feather="lock"></i> Holiday / Closed</span>';
            if (data.special && data.special.notes) {
                html += '<div class="so-note"><strong>Reason:</strong> ' + escHtml(data.special.notes) + '</div>';
            }
            if (data.special && data.special.end_date && data.special.end_date !== data.special.start_date) {
                html += '<div class="so-note">Range: ' + escHtml(data.special.start_date) + ' to ' + escHtml(data.special.end_date) + '</div>';
            }

            if (past) {
                html += '<div class="so-mt"><button type="button" class="so-btn ghost" disabled><i data-feather="lock"></i> The date has passed</button></div>';
            } else {
                html += '<div class="so-actions so-mt">';
                html += '<form method="POST" action="/admin/calendar/' + data.special.id + '" style="flex:1">';
                html += '<input type="hidden" name="_token" value="' + CSRF + '">';
                html += '<input type="hidden" name="_method" value="DELETE">';
                html += '<button type="submit" class="so-btn primary"><i data-feather="unlock"></i> Reopen</button>';
                html += '</form>';
                html += '</div>';
            }
        } else {
            html += '<span class="so-badge open"><i data-feather="check-circle"></i> Open Normal</span>';

            if (past) {
                html += '<div class="so-note">The date has passed — cannot be changed.</div>';
            } else {
                html += '<div class="so-mt">';
                html += '<button type="button" class="so-btn danger" onclick="openLiburModal(\'' + data.date + '\')">';
                html += '<i data-feather="lock"></i> Close / Holiday</button>';
                html += '</div>';
            }
        }

        html += '</div>';

        // ===== ALL DAY AGENDA =====
        if (data.all_day_agendas && data.all_day_agendas.length > 0) {
            html += '<div class="so-section">';
            html += '<h4>All-day Agenda</h4>';
            data.all_day_agendas.forEach(function (a) {
                html += renderAgendaItem(a, past);
            });
            html += '</div>';
        }

        // ===== TIMELINE PER JAM =====
        html += '<div class="so-section">';
        html += '<h4>Schedule</h4>';

        if (!data.slots || data.slots.length === 0) {
            html += '<div class="so-empty-slot">The date is a holiday.</div>';
        } else {
            html += '<div class="so-timeline">';
            data.slots.forEach(function (slot) {
                var hasItems = (slot.bookings && slot.bookings.length > 0) || (slot.agendas && slot.agendas.length > 0);
                html += '<div class="so-slot">';
                html += '<div class="jam">' + slot.time + '</div>';
                html += '<div class="items">';
                if (!hasItems) {
                    html += '<div class="so-empty-slot">Empty</div>';
                } else {
                    slot.bookings.forEach(function (b) {
                        html += '<a href="' + b.show_url + '" class="so-booking">';
                        html += '<div class="info">';
                        html += '<div class="name">' + escHtml(b.customer_name) + '</div>';
                        html += '<div class="meta">' + escHtml(b.booking_code) + '</div>';
                        html += '</div>';
                        html += '<span class="st ' + escHtml(b.status) + '">' + escHtml(b.status) + '</span>';
                        html += '</a>';
                    });
                    slot.agendas.forEach(function (a) {
                        html += renderAgendaItem(a, past);
                    });
                }
                html += '</div>';
                html += '</div>';
            });
            html += '</div>';
        }
        html += '</div>';

        // Tombol tambah agenda — cuma kalo bukan past
        if (!past) {
            html += '<div class="so-mt">';
            html += '<button type="button" class="so-btn ghost" onclick="openAgendaModal(\'' + data.date + '\')">';
            html += '<i data-feather="plus"></i> Add Agenda for This Date</button>';
            html += '</div>';
        }

        document.getElementById('soBody').innerHTML = html;
        if (window.feather) feather.replace();
    }

    function renderAgendaItem(a, past) {
        var h = '<div class="so-agenda">';
        h += '<i data-feather="clock"></i>';
        h += '<div class="info">';
        h += '<div class="ttl">' + escHtml(a.title) + '</div>';
        if (a.description) h += '<div class="desc">' + escHtml(a.description) + '</div>';
        h += '</div>';

        // Kalo bukan past → tampil tombol edit/hapus
        if (!past) {
            h += '<div class="acts">';
            h += '<button type="button" onclick="editAgenda(' + a.id + ')" title="Edit"><i data-feather="edit-2"></i></button>';
            h += '<button type="button" class="danger" onclick="deleteAgenda(' + a.id + ')" title="Delete"><i data-feather="trash-2"></i></button>';
            h += '</div>';
        }

        h += '</div>';
        return h;
    }

    // ===== MODAL LIBUR =====
    var liburModal = document.getElementById('liburModal');
    var liburStart = document.getElementById('liburStart');
    var liburEnd   = document.getElementById('liburEnd');

    function openLiburModal(date) {
        // Blokir kalo past
        if (date && isPastDate(date)) {
            alert('The date has passed — cannot be changed.');
            return;
        }

        var startVal = date || TODAY;
        liburStart.value = startVal;
        liburStart.min = TODAY;
        liburEnd.value = '';
        liburEnd.min = startVal;

        liburModal.classList.add('open');
        setTimeout(function () { liburStart.focus(); }, 100);
    }

    function closeLiburModal() {
        liburModal.classList.remove('open');
    }

    // Sync: pas start date diubah → min end date = start date
    if (liburStart && liburEnd) {
        liburStart.addEventListener('change', function () {
            var val = this.value || TODAY;
            liburEnd.min = val;
            if (liburEnd.value && liburEnd.value < val) {
                liburEnd.value = val;
            }
        });
    }

    // ===== MODAL AGENDA =====
    var agModal = document.getElementById('agModal');
    var agForm  = document.getElementById('agForm');
    var agMethod = document.getElementById('agMethod');
    var agTitle  = document.getElementById('agTitle');
    var agDate   = document.getElementById('agDate');
    var agTime   = document.getElementById('agTime');
    var agDesc   = document.getElementById('agDesc');
    var agModalTitle = document.getElementById('agModalTitle');
    var agSubmitBtn  = document.getElementById('agSubmitBtn');

    function openAgendaModal(date) {
        // Blokir kalo past
        if (date && isPastDate(date)) {
            alert('The date has passed — cannot add agenda.');
            return;
        }

        agModalTitle.textContent = 'Add Schedule';
        agForm.action = AGENDA_STORE;
        agMethod.value = 'POST';
        agTitle.value = '';
        agDate.value = date || TODAY;
        agDate.min = TODAY;
        agTime.value = '';
        agDesc.value = '';
        agSubmitBtn.textContent = 'Save';
        agModal.classList.add('open');
        setTimeout(function () { agTitle.focus(); }, 100);
    }

    function editAgenda(id) {
        if (!currentDayData) return;

        // Blokir kalo past
        if (isPastDate(currentDayData.date)) {
            alert('The date has passed — schedule cannot be edited.');
            return;
        }

        var found = null;
        if (currentDayData.all_day_agendas) {
            currentDayData.all_day_agendas.forEach(function (a) { if (a.id === id) found = a; });
        }
        if (!found && currentDayData.slots) {
            currentDayData.slots.forEach(function (s) {
                s.agendas.forEach(function (a) { if (a.id === id) found = a; });
            });
        }
        if (!found) return;

        agModalTitle.textContent = 'Edit Schedule';
        agForm.action = AGENDA_UPDATE.replace('__ID__', id);
        agMethod.value = 'PUT';
        agTitle.value = found.title || '';
        agDate.value = currentDayData.date;
        agDate.min = TODAY;
        agTime.value = found.time || '';
        agDesc.value = found.description || '';
        agSubmitBtn.textContent = 'Update';
        agModal.classList.add('open');
        setTimeout(function () { agTitle.focus(); }, 100);
    }

    function closeAgendaModal() {
        agModal.classList.remove('open');
    }

    function deleteAgenda(id) {
        if (!currentDayData) return;

        // Blokir kalo past
        if (isPastDate(currentDayData.date)) {
            alert('The date has passed — schedule cannot be deleted.');
            return;
        }

        if (!confirm('Delete this schedule?')) return;
        var url = AGENDA_DELETE.replace('__ID__', id);
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function () {
            if (currentDayData) openDay(currentDayData.date);
            location.reload();
        })
        .catch(function () { alert('Failed to delete schedule.'); });
    }

    // ===== SELECTION =====
    function markSelected(date) {
        document.querySelectorAll('.cal-scope [data-date]').forEach(function (el) {
            el.classList.toggle('sel', el.getAttribute('data-date') === date);
        });
    }
    function clearSelected() {
        document.querySelectorAll('.cal-scope .sel').forEach(function (el) { el.classList.remove('sel'); });
    }

    function openToday() {
        var el = document.querySelector('.cal-day.today');
        if (el) openDay(el.getAttribute('data-date'));
    }

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (liburModal.classList.contains('open')) closeLiburModal();
        else if (agModal.classList.contains('open')) closeAgendaModal();
        else closeDay();
    });

    document.addEventListener('DOMContentLoaded', function () {
        mountRoots();
        if (window.feather) feather.replace();

        var btn = document.getElementById('btnToday');
        if (btn && !document.querySelector('.cal-day.today')) btn.classList.add('off');

        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!reduce) {
            document.querySelectorAll('.cal-scope [data-count]').forEach(function (el, idx) {
                var target = parseFloat(el.getAttribute('data-count'));
                if (!isFinite(target) || target <= 0) return;
                var dur = 900, start = null;
                el.textContent = '0';
                function step(ts) {
                    if (start === null) start = ts;
                    var t = Math.min((ts - start) / dur, 1);
                    el.textContent = Math.round(target * (1 - Math.pow(1 - t, 3)));
                    if (t < 1) requestAnimationFrame(step);
                }
                setTimeout(function () { requestAnimationFrame(step); }, 200 + idx * 80);
            });
        }
    });
</script>
@endpush
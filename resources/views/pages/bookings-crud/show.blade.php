@extends('../layout/' . $layout)

@section('subhead')
    <title>Booking Detail - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* =====================================================
           BOOKING DETAIL — clean outline style
        ===================================================== */
        .bk-scope a {
            text-decoration: none
        }

        /* ===== TOOLBAR ===== */
        .page-toolbar {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            background: #fff;
            border: 1px solid rgb(var(--color-slate-200));
            border-radius: 20px;
            padding: 12px 14px 12px 12px;
            position: sticky;
            top: 12px;
            z-index: 30;
        }

        .dark .page-toolbar {
            background: rgb(var(--color-darkmode-600));
            border-color: rgb(var(--color-darkmode-400));
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgb(var(--color-slate-200));
            color: rgb(var(--color-slate-800));
            transition: background .15s, color .15s, border-color .15s;
        }

        .dark .back-btn {
            border-color: rgb(var(--color-darkmode-400));
            color: rgb(var(--color-slate-200));
        }

        .back-btn:hover {
            background: rgb(var(--color-primary));
            border-color: rgb(var(--color-primary));
            color: #fff;
        }

        .back-btn svg {
            width: 17px;
            height: 17px
        }

        .toolbar-title {
            font-size: 16px;
            font-weight: 600;
            color: rgb(var(--color-slate-800));
            letter-spacing: -.01em;
        }

        .dark .toolbar-title {
            color: rgb(var(--color-slate-200))
        }

        .toolbar-sub {
            font-size: 12.5px;
            color: rgb(var(--color-slate-500));
            margin-top: 1px;
        }

        .dark .toolbar-sub {
            color: rgb(var(--color-slate-400))
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
            margin-left: auto;
            flex-wrap: wrap;
            align-items: center
        }

        .quick-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            height: 38px;
            padding: 0 16px;
            border-radius: 99px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            background: #fff;
            transition: background .15s, color .15s;
        }

        .dark .quick-btn {
            background: rgb(var(--color-darkmode-600))
        }

        .quick-btn svg {
            width: 14px;
            height: 14px
        }

        .quick-btn.cancel {
            color: #c2352f;
            border: 1px solid #f3cfcd;
        }

        .dark .quick-btn.cancel {
            border-color: rgba(194, 53, 47, .4);
        }

        .quick-btn.cancel:hover {
            background: #c2352f;
            border-color: #c2352f;
            color: #fff;
        }

        /* ===== STATUS BADGE ===== */
        .bk-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 38px;
            padding: 0 16px;
            border-radius: 99px;
            font-size: 13px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .bk-status svg {
            width: 14px;
            height: 14px
        }

        .bk-status.pending {
            color: #b45309;
            background: rgba(245, 158, 11, .12)
        }

        .bk-status.paid {
            color: rgb(var(--color-primary));
            background: rgb(var(--color-primary) / .12)
        }

        .bk-status.completed {
            color: #15803d;
            background: rgba(34, 197, 94, .12)
        }

        .bk-status.cancelled {
            color: #b91c1c;
            background: rgba(220, 38, 38, .12)
        }

        .bk-status.no-show {
            color: #64748b;
            background: rgba(100, 116, 139, .15)
        }

        .bk-status.expired {
            color: #dc2626;
            background: rgba(220, 38, 38, .12)
        }

        .dark .bk-status.pending { color: #fbbf24 }
        .dark .bk-status.completed { color: #4ade80 }
        .dark .bk-status.cancelled { color: #fca5a5 }
        .dark .bk-status.no-show { color: #cbd5e1 }
        .dark .bk-status.expired { color: #fca5a5 }

        /* ===== CARD ===== */
        .bk-card {
            background: #fff;
            border: 1px solid rgb(var(--color-slate-200));
            border-radius: 24px;
            min-width: 0;
            overflow: hidden;
        }

        .dark .bk-card {
            background: rgb(var(--color-darkmode-600));
            border-color: rgb(var(--color-darkmode-400));
        }

        .bk-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 24px 6px
        }

        .bk-card-head h3 {
            font-size: 17px;
            font-weight: 500;
            margin: 0;
            color: rgb(var(--color-slate-800));
            letter-spacing: -.01em;
        }

        .dark .bk-card-head h3 {
            color: rgb(var(--color-slate-200))
        }

        .bk-card-head .count {
            margin-left: auto;
            font-size: 12.5px;
            color: rgb(var(--color-slate-500));
        }

        .dark .bk-card-head .count {
            color: rgb(var(--color-slate-400))
        }

        .icon-badge {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgb(var(--color-primary) / .12);
            color: rgb(var(--color-primary));
        }

        .icon-badge svg {
            width: 16px;
            height: 16px
        }

        .bk-card-body {
            padding: 14px 24px 24px;
            min-width: 0
        }

        /* ===== BOOKING CODE ===== */
        .bk-code-big {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 26px;
            font-weight: 600;
            color: rgb(var(--color-primary));
            letter-spacing: .02em;
            word-break: break-all;
        }

        /* ===== META ROWS ===== */
        .meta-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px solid rgb(var(--color-slate-200));
        }

        .dark .meta-row {
            border-bottom-color: rgb(var(--color-darkmode-400))
        }

        .meta-row:first-child { padding-top: 4px }
        .meta-row:last-child { border-bottom: none; padding-bottom: 0 }

        .meta-label {
            font-size: 13px;
            color: rgb(var(--color-slate-500));
            width: 92px;
            flex: none;
            padding-top: 1px;
        }

        .dark .meta-label {
            color: rgb(var(--color-slate-400))
        }

        .meta-value {
            font-size: 14px;
            color: rgb(var(--color-slate-800));
            font-weight: 500;
            flex: 1;
            min-width: 0;
            word-break: break-word;
        }

        .dark .meta-value {
            color: rgb(var(--color-slate-200))
        }

        .meta-value a {
            color: rgb(var(--color-primary))
        }

        .meta-value a:hover { text-decoration: underline }

        /* ===== PHONE + WHATSAPP ===== */
        .phone-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px 14px;
            flex-wrap: wrap;
        }

        .phone-number {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 14px;
            font-weight: 600;
            color: rgb(var(--color-slate-800));
            letter-spacing: .01em;
        }

        .dark .phone-number {
            color: rgb(var(--color-slate-200))
        }

        .wa-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 38px;
            padding: 0 16px 0 12px;
            border-radius: 99px;
            flex: none;
            font-size: 13px;
            font-weight: 500;
            color: #0c7a3c;
            background: #e8f8ee;
            border: 1px solid #c9eed9;
            transition: background .18s, color .18s, border-color .18s;
        }

        .dark .wa-btn {
            color: #4ade80;
            background: rgba(34, 197, 94, .15);
            border-color: rgba(34, 197, 94, .3);
        }

        .wa-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
            flex: none
        }

        .wa-btn:hover {
            background: #1faa59;
            border-color: #1faa59;
            color: #fff
        }

        /* ===== ITEMS TABLE ===== */
        .table-wrap { overflow-x: auto }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            min-width: 0;
        }

        .items-table thead th {
            font-size: 12.5px;
            font-weight: 500;
            color: rgb(var(--color-slate-500));
            padding: 12px 24px;
            text-align: left;
            border-bottom: 1px solid rgb(var(--color-slate-200));
            white-space: nowrap;
        }

        .dark .items-table thead th {
            color: rgb(var(--color-slate-400));
            border-bottom-color: rgb(var(--color-darkmode-400));
        }

        .items-table col.col-item { width: auto }
        .items-table col.col-qty { width: 70px }
        .items-table col.col-price { width: 150px }
        .items-table col.col-subtotal { width: 170px }

        .items-table tbody td {
            padding: 16px 24px;
            vertical-align: middle;
            border-bottom: 1px solid rgb(var(--color-slate-200));
            font-size: 14px;
            color: rgb(var(--color-slate-600));
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .items-table tbody td {
            border-bottom-color: rgb(var(--color-darkmode-400));
            color: rgb(var(--color-slate-300));
        }

        .items-table tbody tr:last-child td { border-bottom: none }

        .items-table .item-name {
            font-weight: 500;
            color: rgb(var(--color-slate-800));
            display: block;
            margin-bottom: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .items-table .item-name {
            color: rgb(var(--color-slate-200))
        }

        .items-table .item-duration {
            font-size: 12px;
            color: rgb(var(--color-slate-500));
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .items-table .item-duration {
            color: rgb(var(--color-slate-400))
        }

        .items-table .center { text-align: center }
        .items-table .right { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums }

        .items-table tbody td.td-subtotal {
            font-weight: 600;
            color: rgb(var(--color-slate-800));
        }

        .dark .items-table tbody td.td-subtotal { color: #f1f5f9 }

        .items-table tfoot td {
            padding: 14px 24px;
            font-size: 14px;
            font-weight: 500;
            color: rgb(var(--color-slate-800));
            background: rgb(var(--color-primary) / .08);
            border: none;
            white-space: nowrap;
            vertical-align: middle;
        }

        .dark .items-table tfoot td {
            color: rgb(var(--color-slate-200));
            background: rgb(var(--color-primary) / .15);
        }

        .items-table tfoot tr:first-child td { padding-top: 18px }
        .items-table tfoot tr:last-child td { padding-bottom: 18px }

        .items-table tfoot td.tfoot-label {
            text-align: right;
            font-weight: 600;
            padding-right: 16px;
            white-space: nowrap;
        }

        .dark .items-table tfoot td.tfoot-label { color: #e2e8f0 }

        .items-table tfoot td.tfoot-grand {
            text-align: right;
            padding-left: 16px;
            font-size: 18px;
            font-weight: 700;
            color: rgb(var(--color-primary));
            letter-spacing: -.01em;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .dark .items-table tfoot td.tfoot-grand { color: #fff }

        .items-table tfoot tr.cancel-row td {
            background: rgba(220, 38, 38, .06);
            font-size: 13px;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .dark .items-table tfoot tr.cancel-row td {
            background: rgba(220, 38, 38, .12);
        }

        .items-table tfoot tr.cancel-row td.tfoot-label {
            color: #8f2a25;
            font-weight: 600;
        }

        .dark .items-table tfoot tr.cancel-row td.tfoot-label {
            color: #fca5a5;
        }

        .items-table tfoot tr.cancel-row td.tfoot-refund {
            text-align: right;
            padding-left: 16px;
            font-size: 15px;
            font-weight: 700;
            color: #b91c1c;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .dark .items-table tfoot tr.cancel-row td.tfoot-refund {
            color: #fca5a5;
        }

        .items-table tfoot tr.cancel-row.refund-amount td.tfoot-refund {
            font-size: 14px;
            font-weight: 600;
        }

        .items-table tfoot tr.net-row td.tfoot-label {
            font-weight: 700;
        }

        .dark .items-table tfoot tr.net-row td.tfoot-label { color: #e2e8f0 }

        .items-table tfoot tr.net-row td.tfoot-refund {
            color: rgb(var(--color-primary));
            font-size: 15px;
            font-weight: 700;
        }

        .dark .items-table tfoot tr.net-row td.tfoot-refund { color: #fff }

        /* ===== NOTES ===== */
        .notes-box {
            background: #fff8e6;
            border-radius: 16px;
            padding: 16px 18px;
            font-size: 13.5px;
            color: #7a4a08;
            line-height: 1.65;
            white-space: pre-line;
        }

        .dark .notes-box {
            background: rgba(245, 158, 11, .1);
            color: #fbbf24;
        }

        /* ===== NOTICES ===== */
        .lock-notice,
        .info-notice,
        .success-notice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            border-radius: 18px;
            font-size: 13px;
            line-height: 1.6;
        }

        .lock-notice {
            background: rgba(220, 38, 38, .08);
            color: #8f2a25;
        }

        .dark .lock-notice {
            background: rgba(220, 38, 38, .15);
            color: #fca5a5;
        }

        .info-notice {
            background: rgb(var(--color-primary) / .08);
            color: rgb(var(--color-primary));
        }

        .dark .info-notice {
            background: rgb(var(--color-primary) / .15)
        }

        .success-notice {
            background: rgba(34, 197, 94, .1);
            color: #15803d;
        }

        .dark .success-notice {
            background: rgba(34, 197, 94, .15);
            color: #4ade80;
        }

        .lock-ic,
        .info-ic,
        .success-ic {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .dark .lock-ic,
        .dark .info-ic,
        .dark .success-ic {
            background: rgb(var(--color-darkmode-700));
        }

        .lock-ic { color: #c2352f }
        .info-ic { color: rgb(var(--color-primary)) }
        .success-ic { color: #16a34a }

        .lock-ic svg,
        .info-ic svg,
        .success-ic svg {
            width: 14px;
            height: 14px
        }

        .lock-notice strong:first-child,
        .info-notice strong:first-child,
        .success-notice strong:first-child {
            display: block;
            margin-bottom: 2px;
            font-weight: 600;
            font-size: 13.5px;
        }

        /* ===== FORM ===== */
        .bk-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: rgb(var(--color-slate-800));
            margin-bottom: 8px;
        }

        .dark .bk-label {
            color: rgb(var(--color-slate-200))
        }

        .bk-select {
            border-radius: 14px !important;
            height: 46px;
            border: 1px solid rgb(var(--color-slate-200)) !important;
            padding: 0 14px;
            background: #fff;
            color: rgb(var(--color-slate-800));
        }

        .dark .bk-select {
            border-color: rgb(var(--color-darkmode-400)) !important;
            background: rgb(var(--color-darkmode-700));
            color: rgb(var(--color-slate-200));
        }

        .bk-select:focus {
            border-color: rgb(var(--color-primary)) !important;
            outline: none;
            box-shadow: 0 0 0 3px rgb(var(--color-primary) / .12);
        }

        .bk-hint {
            font-size: 12px;
            color: rgb(var(--color-slate-500));
            margin-top: 10px;
            line-height: 1.7;
        }

        .dark .bk-hint {
            color: rgb(var(--color-slate-400))
        }

        .bk-hint strong {
            color: rgb(var(--color-slate-700));
            font-weight: 600;
        }

        .dark .bk-hint strong {
            color: rgb(var(--color-slate-300))
        }

        .btn-save {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            height: 46px;
            border-radius: 99px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: rgb(var(--color-primary));
            transition: filter .15s;
        }

        .btn-save:hover { filter: brightness(1.1) }

        .btn-save svg {
            width: 16px;
            height: 16px
        }

        /* ===== PAYMENT INFO ===== */
        .pay-info {
            background: linear-gradient(135deg, rgba(247, 108, 108, .06) 0%, rgba(247, 108, 108, .02) 100%);
            border: 1px solid rgba(247, 108, 108, .2);
            border-radius: 16px;
            padding: 18px 20px;
        }

        .pay-info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding: 9px 0;
            font-size: 13px;
            border-bottom: 1px dashed rgba(247, 108, 108, .2);
        }

        .pay-info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .pay-info-row:first-child { padding-top: 0 }

        .pay-info-row .pi-label {
            color: rgb(var(--color-slate-500));
            font-weight: 500;
            flex: none;
        }

        .dark .pay-info-row .pi-label { color: rgb(var(--color-slate-400)) }

        .pay-info-row .pi-value {
            color: rgb(var(--color-slate-800));
            font-weight: 600;
            text-align: right;
            word-break: break-all;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 12.5px;
        }

        .dark .pay-info-row .pi-value { color: rgb(var(--color-slate-200)) }

        .pay-info-row .pi-value.amount {
            font-family: inherit;
            font-size: 14px;
            color: rgb(var(--color-primary));
        }

        /* ===== SOFT ALERT ===== */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.55;
            border: 1px solid transparent;
        }

        .alert svg {
            width: 16px;
            height: 16px;
            flex: none;
        }

        .alert-soft-success {
            background: rgb(var(--color-success) / .12);
            color: rgb(var(--color-success));
            border-color: rgb(var(--color-success) / .25);
        }

        .alert-soft-danger {
            background: rgb(var(--color-danger) / .1);
            color: rgb(var(--color-danger));
            border-color: rgb(var(--color-danger) / .25);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width:640px) {
            .toolbar-actions { margin-left: 0; width: 100% }
            .meta-row { flex-direction: column; gap: 4px }
            .meta-label { width: auto }
            .bk-card-head, .bk-card-body { padding-left: 18px; padding-right: 18px }
            .items-table thead th, .items-table tbody td, .items-table tfoot td { padding-left: 14px; padding-right: 14px }
            .items-table col.col-price { width: 110px }
            .items-table col.col-subtotal { width: 130px }
            .items-table tfoot td.tfoot-grand { font-size: 15px }
            .items-table tfoot tr.cancel-row td.tfoot-refund { font-size: 13px }
        }
    </style>

    <div class="bk-scope">

        {{-- TOOLBAR --}}
        <div class="intro-y page-toolbar mt-8">
            <a href="{{ route('admin.bookings.index') }}" class="back-btn">
                <i data-feather="arrow-left"></i>
            </a>
            <div>
                <div class="toolbar-title">Booking Detail</div>
                <div class="toolbar-sub">{{ $booking->booking_code }} — {{ $booking->customer_name }}</div>
            </div>
            <div class="toolbar-actions">
                @if($canCancel)
                    <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="quick-btn cancel" onclick="return confirm('Cancel this booking? The slot will be released.')">
                            <i data-feather="x"></i> Cancel Booking
                        </button>
                    </form>
                @endif

                @php
                    $statusIcon = [
                        'pending'   => 'clock',
                        'paid'      => 'check-circle',
                        'completed' => 'check-square',
                        'cancelled' => 'x-circle',
                        'no-show'   => 'user-x',
                        'expired'   => 'alert-circle',
                    ][$booking->status] ?? 'clock';
                @endphp
                <span class="bk-status {{ $booking->status }}">
                    <i data-feather="{{ $statusIcon }}"></i>
                    {{ $booking->status_label }}
                </span>
            </div>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="intro-y mt-5">
                <div class="alert alert-soft-success">
                    <i data-feather="check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="intro-y mt-5">
                <div class="alert alert-soft-danger">
                    <i data-feather="alert-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-12 gap-6 mt-5">

            {{-- LEFT: ITEMS + NOTES + PAYMENT --}}
            <div class="intro-y col-span-12 lg:col-span-7">

                {{-- BOOKING CODE --}}
                <div class="bk-card">
                    <div class="bk-card-head">
                        <div class="icon-badge"><i data-feather="hash"></i></div>
                        <h3>Booking Code</h3>
                    </div>
                    <div class="bk-card-body">
                        <div class="bk-code-big">{{ $booking->booking_code }}</div>
                    </div>
                </div>

                {{-- ITEMS --}}
                <div class="bk-card mt-5">
                    <div class="bk-card-head" style="padding-bottom:14px">
                        <div class="icon-badge"><i data-feather="shopping-bag"></i></div>
                        <h3>Booked Items</h3>
                        <span class="count">{{ count($booking->items ?? []) }} items</span>
                    </div>
                    <div class="table-wrap">
                        <table class="items-table">
                            <colgroup>
                                <col class="col-item">
                                <col class="col-qty">
                                <col class="col-price">
                                <col class="col-subtotal">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="center">Qty</th>
                                    <th class="right">Price</th>
                                    <th class="right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($booking->items ?? [] as $item)
                                    <tr>
                                        <td>
                                            <span class="item-name">{{ $item['name'] }}</span>
                                            <span class="item-duration">{{ $item['duration'] ?? '-' }}</span>
                                        </td>
                                        <td class="center">{{ $item['qty'] }}</td>
                                        <td class="right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="right td-subtotal">
                                            Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="center" style="padding:32px;color:rgb(var(--color-slate-400));">No items</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                {{-- SUBTOTAL --}}
                                <tr>
                                    <td colspan="3" class="tfoot-label">Subtotal</td>
                                    <td class="tfoot-grand">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- FEE --}}
                                @if($booking->payment_fee > 0)
                                    <tr>
                                        <td colspan="3" class="tfoot-label" style="font-size:13px;font-weight:500;">
                                            Service Fee
                                        </td>
                                        <td class="tfoot-grand" style="font-size:14px;color:#b45309;">
                                            Rp {{ number_format($booking->payment_fee, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif

                                {{-- TOTAL BAYAR --}}
                                @if($booking->payment_amount > 0)
                                    <tr>
                                        <td colspan="3" class="tfoot-label" style="font-weight:700;">Total Amount</td>
                                        <td class="tfoot-grand" style="font-size:20px;">
                                            Rp {{ number_format($booking->payment_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif

                                {{-- CANCELLATION ROWS --}}
                                @if($booking->status === 'cancelled')
                                    <tr class="cancel-row">
                                        <td colspan="3" class="tfoot-label">
                                            Refund
                                            @if((int) $booking->refund_percent > 0)
                                                <span style="color:#15803d;">({{ $booking->refund_percent }}%)</span>
                                            @else
                                                <span style="color:#b91c1c;">(No Refund)</span>
                                            @endif
                                        </td>
                                        <td class="tfoot-refund refund-amount">
                                            @if((int) $booking->refund_percent > 0)
                                                - Rp {{ number_format((int) round($booking->total_price * $booking->refund_percent / 100), 0, ',', '.') }}
                                            @else
                                                Rp 0
                                            @endif
                                        </td>
                                    </tr>

                                    <tr class="cancel-row net-row">
                                        <td colspan="3" class="tfoot-label">Net Revenue</td>
                                        <td class="tfoot-refund">
                                            Rp {{ number_format($booking->net_revenue, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- PAYMENT INFO --}}
                @if($booking->payment_method)
                    <div class="bk-card mt-5">
                        <div class="bk-card-head">
                            <div class="icon-badge"><i data-feather="credit-card"></i></div>
                            <h3>Payment Information</h3>
                        </div>
                        <div class="bk-card-body">
                            <div class="pay-info">
                                <div class="pay-info-row">
                                    <span class="pi-label">Method</span>
                                    <span class="pi-value">{{ $booking->payment_method_name ?: $booking->payment_method }}</span>
                                </div>
                                @if($booking->payment_method)
                                    <div class="pay-info-row">
                                        <span class="pi-label">Code</span>
                                        <span class="pi-value">{{ $booking->payment_method }}</span>
                                    </div>
                                @endif
                                @if($booking->payment_reference)
                                    <div class="pay-info-row">
                                        <span class="pi-label">Reference</span>
                                        <span class="pi-value">{{ $booking->payment_reference }}</span>
                                    </div>
                                @endif
                                @if($booking->payment_status)
                                    <div class="pay-info-row">
                                        <span class="pi-label">Status</span>
                                        <span class="pi-value" style="text-transform:uppercase;">{{ $booking->payment_status }}</span>
                                    </div>
                                @endif
                                @if($booking->paid_at)
                                    <div class="pay-info-row">
                                        <span class="pi-label">Paid At</span>
                                        <span class="pi-value" style="color:#16a34a;">{{ $booking->paid_at->format('d M Y, H:i') }}</span>
                                    </div>
                                @endif
                                @if($booking->expired_at && $booking->status === 'pending')
                                    <div class="pay-info-row">
                                        <span class="pi-label">Expires At</span>
                                        <span class="pi-value" style="color:#b45309;">{{ $booking->expired_at->format('d M Y, H:i') }}</span>
                                    </div>
                                @endif
                                <div class="pay-info-row">
                                    <span class="pi-label">Total Amount</span>
                                    <span class="pi-value amount">Rp {{ number_format($booking->payment_amount ?: $booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- NOTES --}}
                @if($booking->notes)
                    <div class="bk-card mt-5">
                        <div class="bk-card-head">
                            <div class="icon-badge" style="background:rgba(245,158,11,.15);color:#b45309">
                                <i data-feather="message-circle"></i>
                            </div>
                            <h3>Notes</h3>
                        </div>
                        <div class="bk-card-body">
                            <div class="notes-box">{{ $booking->notes }}</div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- RIGHT --}}
            <div class="intro-y col-span-12 lg:col-span-5">

                {{-- CUSTOMER --}}
                <div class="bk-card">
                    <div class="bk-card-head">
                        <div class="icon-badge"><i data-feather="user"></i></div>
                        <h3>Customer</h3>
                    </div>
                    <div class="bk-card-body">
                        <div class="meta-row">
                            <span class="meta-label">Name</span>
                            <span class="meta-value">{{ $booking->customer_name }}</span>
                        </div>

                        <div class="meta-row">
                            <span class="meta-label">Phone</span>
                            <span class="meta-value">
                                <div class="phone-row">
                                    <span class="phone-number">{{ $booking->customer_phone }}</span>
                                    <a href="{{ $booking->whatsapp_url }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="wa-btn">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35zM12.05 21.8h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.44-9.88 9.89-9.88 2.64 0 5.12 1.03 6.99 2.9a9.82 9.82 0 0 1 2.89 6.99c0 5.45-4.44 9.88-9.88 9.88zM20.52 3.45A11.82 11.82 0 0 0 12.05 0C5.5 0 .16 5.34.16 11.89c0 2.1.55 4.14 1.59 5.95L.06 24l6.3-1.65a11.88 11.88 0 0 0 5.68 1.45h.01c6.55 0 11.89-5.34 11.89-11.89 0-3.18-1.24-6.16-3.48-8.41z"/></svg>
                                        Chat on WhatsApp
                                    </a>
                                </div>
                            </span>
                        </div>

                        <div class="meta-row">
                            <span class="meta-label">Email</span>
                            <span class="meta-value">
                                @if($booking->customer_email)
                                    <a href="mailto:{{ $booking->customer_email }}">{{ $booking->customer_email }}</a>
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- SCHEDULE --}}
                <div class="bk-card mt-5">
                    <div class="bk-card-head">
                        <div class="icon-badge"><i data-feather="calendar"></i></div>
                        <h3>Schedule</h3>
                    </div>
                    <div class="bk-card-body">
                        <div class="meta-row">
                            <span class="meta-label">Date</span>
                            <span class="meta-value">{{ $booking->booking_date->format('l, d F Y') }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Time</span>
                            <span class="meta-value">{{ $booking->booking_time }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Booked At</span>
                            <span class="meta-value">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if($booking->paid_at)
                            <div class="meta-row">
                                <span class="meta-label">Paid At</span>
                                <span class="meta-value" style="color:#16a34a;font-weight:700;">
                                    {{ $booking->paid_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- UPDATE STATUS --}}
                <div class="bk-card mt-5">
                    <div class="bk-card-head">
                        <div class="icon-badge"><i data-feather="refresh-cw"></i></div>
                        <h3>Update Status</h3>
                    </div>
                    <div class="bk-card-body">

                        @if($isLocked)
                            <div class="lock-notice">
                                <div class="lock-ic"><i data-feather="lock"></i></div>
                                <div>
                                    <strong>Locked Status</strong>
                                    Bookings with status <strong>{{ strtoupper($booking->status_label) }}</strong> can no longer be changed. This status is final.
                                </div>
                            </div>

                        @elseif($booking->status === 'pending')
                            <div class="info-notice">
                                <div class="info-ic"><i data-feather="clock"></i></div>
                                <div>
                                    <strong>Awaiting Payment</strong>
                                    The status will automatically change to <strong>Paid</strong> once payment succeeds, or <strong>Expired</strong> if the time limit is exceeded. Admin cannot change it manually.
                                </div>
                            </div>

                        @elseif($booking->status === 'paid')
                            <div class="success-notice" style="margin-bottom:16px;">
                                <div class="success-ic"><i data-feather="check-circle"></i></div>
                                <div>
                                    <strong>Payment Successful</strong>
                                    The booking has been paid. Please change the status to <strong>Completed</strong> once the customer arrives, or <strong>No-Show</strong> if they do not show up.
                                </div>
                            </div>

                            <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-4">
                                    <label class="bk-label">Change Status To</label>
                                    <select name="status" class="form-control w-full bk-select" required>
                                        <option value="" selected disabled>— Select new status —</option>
                                        @foreach($availableStatuses as $st)
                                            <option value="{{ $st }}">
                                                {{ ucfirst($st) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="bk-hint">
                                        <strong>Completed</strong> = customer has arrived &amp; treatment is finished.<br>
                                        <strong>No-Show</strong> = customer did not show up without notice.
                                    </div>
                                </div>

                                <button type="submit" class="btn-save"
                                        onclick="return confirm('Change booking status? This action cannot be undone.')">
                                    <i data-feather="save"></i> Save Status
                                </button>
                            </form>
                        @endif

                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);
        });
    </script>
@endsection
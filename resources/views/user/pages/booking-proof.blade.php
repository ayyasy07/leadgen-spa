<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt — {{ $booking->booking_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #f76c6c;
            --primary-dark: #d94a4a;
            --ink: #2a2420;
            --text: #4a4440;
            --muted: #8a8482;
            --line: #ede8e3;
            --cream: #fbf7f2;
            --cream-dark: #f5ede4;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f9;
            color: var(--ink);
            line-height: 1.5;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
        }

        .proof-container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
            overflow: hidden;
            border: 1px solid var(--line);
        }

        /* ===== HEADER ===== */
        .proof-header {
            background: var(--primary);
            color: #fff;
            padding: 22px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .proof-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .proof-header-icon {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .2);
            display: flex; align-items: center; justify-content: center;
            flex: none;
        }
        .proof-header-icon svg { width: 20px; height: 20px; stroke: #fff; fill: none; stroke-width: 2; }
        .proof-header h1 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: .3px;
            line-height: 1.2;
        }
        .proof-header .subtitle {
            font-size: 11.5px;
            opacity: .9;
            margin-top: 2px;
        }
        .proof-header-right {
            text-align: right;
            font-size: 11.5px;
            opacity: .9;
        }
        .proof-header-right .brand {
            font-size: 14px;
            font-weight: 700;
            opacity: 1;
        }

        /* ===== BODY ===== */
        .proof-body { padding: 26px 28px; }

        .section { margin-bottom: 20px; }
        .section:last-child { margin-bottom: 0; }

        .section-title {
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1.5px solid var(--line);
        }

        /* ===== INFO GRID ===== */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 22px;
        }
        .info-grid .full { grid-column: span 2; }

        .info-item .label {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 2px;
        }
        .info-item .value {
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
            word-break: break-word;
        }
        .info-item .value.code {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            color: var(--primary);
            letter-spacing: .8px;
        }

        /* ===== STATUS BADGE ===== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10.5px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .status-badge svg { width: 11px; height: 11px; }
        .status-badge.pending   { background: rgba(245, 158, 11, .15); color: #d97706; }
        .status-badge.paid { background: rgba(59, 130, 246, .15); color: #2563eb; }
        .status-badge.completed { background: rgba(34, 197, 94, .15); color: #16a34a; }
        .status-badge.cancelled { background: rgba(239, 68, 68, .15); color: #dc2626; }
        .status-badge.no-show   { background: rgba(100, 116, 139, .15); color: #64748b; }
        .status-badge.expired   { background: rgba(100, 116, 139, .15); color: #64748b; }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        thead th {
            background: var(--cream);
            text-align: left;
            padding: 9px 12px;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: var(--muted);
            border-bottom: 1.5px solid var(--line);
        }
        tbody td {
            padding: 10px 12px;
            border-bottom: 1px dashed var(--line);
            color: var(--text);
        }
        tbody tr:last-child td { border-bottom: none; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .item-name {
            font-weight: 700;
            color: var(--ink);
            display: block;
            margin-bottom: 1px;
            font-size: 12.5px;
        }
        .item-meta {
            font-size: 10.5px;
            color: var(--muted);
        }

        /* ===== TOTAL ===== */
        .total-box {
            background: var(--cream);
            border-radius: 8px;
            padding: 14px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            border: 1px solid var(--line);
        }
        .total-box .label {
            font-size: 11.5px;
            font-weight: 800;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .total-box .amount {
            font-size: 19px;
            font-weight: 800;
            color: var(--primary);
        }

        /* ===== NOTES ===== */
        .notes-box {
            background: #fef3c7;
            border-left: 3px solid #f59e0b;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 12px;
            color: #92400e;
            line-height: 1.55;
            white-space: pre-line;
        }

        /* ===== FOOTER ===== */
        .proof-footer {
            background: var(--cream);
            padding: 16px 28px;
            font-size: 10.5px;
            color: var(--muted);
            text-align: center;
            border-top: 1px solid var(--line);
            line-height: 1.6;
        }
        .proof-footer .line-1 { margin-bottom: 4px; }
        .proof-footer .line-2 { opacity: .8; }

        /* ===== ACTION BUTTONS ===== */
        .actions {
            max-width: 700px;
            margin: 18px auto 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 11px 22px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all .18s;
            font-family: inherit;
        }
        .btn svg { width: 14px; height: 14px; }
        .btn-print {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(247, 108, 108, .3);
        }
        .btn-print:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(247, 108, 108, .4);
        }
        .btn-back {
            background: #fff;
            color: var(--ink);
            border: 1.5px solid var(--line);
        }
        .btn-back:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ===== PRINT STYLE ===== */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .proof-container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
                border: none;
            }
            .actions {
                display: none !important;
            }
            .proof-header,
            .status-badge,
            .total-box,
            .notes-box,
            thead th,
            .proof-footer {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @media (max-width: 600px) {
            .proof-body { padding: 20px 18px; }
            .proof-header { padding: 18px 20px; }
            .info-grid { grid-template-columns: 1fr; }
            .info-grid .full { grid-column: span 1; }
            .proof-footer { padding: 14px 20px; }
        }
    </style>
</head>
<body>

    <div class="proof-container">

        {{-- HEADER --}}
        <div class="proof-header">
            <div class="proof-header-left">
                <div class="proof-header-icon">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l2.09 6.26L20 9.27l-4.91 4.09L16.18 20 12 16.77 7.82 20l1.09-6.64L4 9.27l5.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div>
                    <h1>Booking Receipt</h1>
                    <div class="subtitle">{{ $spaInfo['name'] }}</div>
                </div>
            </div>
            <div class="proof-header-right">
                <div class="brand">{{ $spaInfo['name'] }}</div>
                <div>Issued {{ now()->format('d M Y, H:i') }}</div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="proof-body">

            {{-- SECTION 1: BOOKING INFO --}}
            <div class="section">
                <div class="section-title">Booking Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">Booking Code</div>
                        <div class="value code">{{ $booking->booking_code }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Status</div>
                        <div class="value">
                            @php
                                $statusIcons = [
                                    'pending'   => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                                    'paid' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
                                    'completed' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
                                    'cancelled' => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',
                                    'no-show'   => '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/>',
                                    'expired'   => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                                ];
                                $icon = $statusIcons[$booking->status] ?? $statusIcons['pending'];
                            @endphp
                            <span class="status-badge {{ $booking->status }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $icon !!}
                                </svg>
                                {{ $booking->status }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">Booking Date</div>
                        <div class="value">{{ $booking->booking_date->format('l, d F Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Time</div>
                        <div class="value">{{ $booking->booking_time }}</div>
                    </div>
                    <div class="info-item full">
                        <div class="label">Created At</div>
                        <div class="value">{{ $booking->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: CUSTOMER --}}
            <div class="section">
                <div class="section-title">Customer Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">Name</div>
                        <div class="value">{{ $booking->customer_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">WhatsApp</div>
                        <div class="value">{{ $booking->customer_phone }}</div>
                    </div>
                    @if($booking->customer_email)
                        <div class="info-item full">
                            <div class="label">Email</div>
                            <div class="value">{{ $booking->customer_email }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- SECTION 3: SERVICES --}}
            <div class="section">
                <div class="section-title">Booked Services</div>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-center" style="width:60px">Qty</th>
                            <th class="text-right" style="width:130px">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->items as $item)
                            <tr>
                                <td>
                                    <span class="item-name">{{ $item['name'] }}</span>
                                    <span class="item-meta">
                                        {{ $item['duration'] ?? '' }}
                                        @if(!empty($item['duration'])) · @endif
                                        {{ ucfirst($item['unit'] ?? 'person') }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $item['qty'] }}</td>
                                <td class="text-right">
                                    Rp {{ number_format($item['subtotal'] ?? ($item['price'] * $item['qty']), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-box">
                    <span class="label">Total</span>
                    <span class="amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- SECTION 4: NOTES --}}
            @if($booking->notes)
                <div class="section">
                    <div class="section-title">Notes</div>
                    <div class="notes-box">{{ $booking->notes }}</div>
                </div>
            @endif

            {{-- SECTION 5: CANCELLATION --}}
            @if($booking->status === 'cancelled')
                <div class="section">
                    <div class="section-title">Cancellation Information</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label">Cancelled At</div>
                            <div class="value">{{ $booking->cancelled_at ? $booking->cancelled_at->format('d M Y, H:i') : '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Refund</div>
                            <div class="value">{{ $booking->refund_percent }}%</div>
                        </div>
                        @if($booking->cancel_reason)
                            <div class="info-item full">
                                <div class="label">Reason</div>
                                <div class="value">{{ $booking->cancel_reason }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- FOOTER --}}
        <div class="proof-footer">
            <div class="line-1">
                @if($spaInfo['address']){{ $spaInfo['address'] }}@endif
                @if($spaInfo['phone']) · Tel: {{ $spaInfo['phone'] }}@endif
                @if($spaInfo['email']) · {{ $spaInfo['email'] }}@endif
            </div>
            <div class="line-2">
                Thank you for choosing {{ $spaInfo['name'] }}.
            </div>
        </div>

    </div>

    {{-- ACTIONS --}}
    <div class="actions">
        <a href="{{ route('history.show', $booking->booking_code) }}" class="btn btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back
        </a>
        <button type="button" class="btn btn-print" onclick="window.print()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"/>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <rect x="6" y="14" width="12" height="8"/>
            </svg>
            Print / Save PDF
        </button>
    </div>

</body>
</html>
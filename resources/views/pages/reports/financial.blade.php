@extends('../layout/' . $layout)

@section('subhead')
    <title>Financial & Transaksi - Reports</title>
@endsection

@section('subcontent')

    <style>
        /* ===== SOFT CARD ===== */
        .rp-card {
            border-radius: 16px;
            padding: 20px 22px;
            border: 1px solid rgba(148, 163, 184, .12);
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .rp-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
        }

        .rp-card .rp-icon {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: none;
            margin-bottom: 14px;
        }

        .rp-card .rp-label {
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
            opacity: .8;
        }

        .rp-card .rp-value {
            font-size: 24px;
            font-weight: 800;
            line-height: 1.15;
            margin-top: 6px;
            color: #1e293b;
        }

        .rp-card .rp-sub {
            font-size: 11.5px;
            margin-top: 6px;
            opacity: .75;
        }

        .rp-card .rp-badge {
            position: absolute;
            top: 18px;
            right: 20px;
            font-size: 10.5px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 99px;
            background: rgba(255, 255, 255, .65);
            backdrop-filter: blur(4px);
        }

        /* Card variants — soft colors */
        .rp-card.blue {
            background: linear-gradient(135deg, rgba(59, 130, 246, .14), rgba(59, 130, 246, .04));
        }

        .rp-card.blue .rp-icon {
            background: rgba(59, 130, 246, .2);
            color: #2563eb;
        }

        .rp-card.blue .rp-badge {
            color: #2563eb;
        }

        .rp-card.green {
            background: linear-gradient(135deg, rgba(34, 197, 94, .14), rgba(34, 197, 94, .04));
        }

        .rp-card.green .rp-icon {
            background: rgba(34, 197, 94, .2);
            color: #15803d;
        }

        .rp-card.green .rp-badge {
            color: #15803d;
        }

        .rp-card.orange {
            background: linear-gradient(135deg, rgba(245, 158, 11, .14), rgba(245, 158, 11, .04));
        }

        .rp-card.orange .rp-icon {
            background: rgba(245, 158, 11, .2);
            color: #b45309;
        }

        .rp-card.orange .rp-badge {
            color: #b45309;
        }

        .rp-card.red {
            background: linear-gradient(135deg, rgba(239, 68, 68, .14), rgba(239, 68, 68, .04));
        }

        .rp-card.red .rp-icon {
            background: rgba(239, 68, 68, .2);
            color: #b91c1c;
        }

        .rp-card.red .rp-badge {
            color: #b91c1c;
        }

        /* Status pill */
        .rp-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 99px;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
        }

        .rp-pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(15, 23, 42, .08);
        }

        .rp-pill b {
            font-weight: 800;
            margin-left: 1px;
        }

        /* Table row */
        .rp-table-card {
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, .12);
            overflow: hidden;
        }

        .rp-table-card table {
            width: 100%;
            border-collapse: collapse
        }

        .rp-table-card thead th {
            font-size: 11px;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid rgba(148, 163, 184, .18);
            background: transparent;
            white-space: nowrap;
        }

        .rp-table-card tbody td {
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px dashed rgba(148, 163, 184, .16);
            font-size: 13px;
            color: #334155;
        }

        .rp-table-card tbody tr:last-child td {
            border-bottom: none
        }

        .rp-table-card tbody tr:hover {
            background: rgba(59, 130, 246, .02)
        }

        /* Chart wrap */
        .rp-chart-card {
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, .12);
            padding: 20px 22px;
        }
    </style>

    {{-- PAGE TITLE --}}
    <h2 class="intro-y text-lg font-medium mt-10">Financial & Transaksi</h2>

    <div class="grid grid-cols-12 gap-5 mt-5">

        {{-- TOOLBAR --}}
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center gap-2">
            <div class="hidden md:block text-slate-500 text-sm">
                Net revenue, status pembayaran, dan daftar transaksi.
            </div>

            <div class="mx-auto"></div>

            <a href="{{ route('admin.reports.export', [
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                    'status'     => $status,
                    'search'     => $search
                ]) }}"
               target="_blank" rel="noopener"
               class="btn btn-primary shadow-md">
                <i data-feather="download" class="w-4 h-4 mr-1"></i> Export CSV
            </a>
        </div>

        {{-- FILTER --}}
        <div class="intro-y col-span-12">
            <form method="GET" action="{{ route('admin.reports.financial') }}"
                  class="box p-5 rounded-2xl flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="form-control box w-40" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="form-control box w-40" required>
                </div>
                <div class="flex-1" style="min-width:220px;">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Search</label>
                    <div class="relative text-slate-500">
                        <input type="text" name="search" value="{{ $search }}"
                               class="form-control box w-full pr-10"
                               placeholder="Kode booking / nama customer...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                    </div>
                </div>
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary shadow-md">
                        <i data-feather="filter" class="w-4 h-4 mr-1"></i> Terapkan
                    </button>
                    <a href="{{ route('admin.reports.financial') }}" class="btn btn-outline-secondary">
                        <i data-feather="rotate-ccw" class="w-4 h-4 mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- 4 CARD STATS — SOFT --}}
        <div class="intro-y col-span-12 grid grid-cols-12 gap-4">
            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                <div class="rp-card green">
                    <div class="rp-icon"><i data-feather="trending-up" class="w-5 h-5"></i></div>
                    <div class="rp-label">Net Revenue</div>
                    <div class="rp-value text-success">Rp {{ number_format($summary['net_revenue'], 0, ',', '.') }}</div>
                    <div class="rp-sub">Confirmed + Completed + No-Show + Cancel</div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                <div class="rp-card blue">
                    <div class="rp-icon"><i data-feather="calendar" class="w-5 h-5"></i></div>
                    <div class="rp-label">Total Bookings</div>
                    <div class="rp-value">{{ number_format($summary['total_bookings'], 0, ',', '.') }}</div>
                    <div class="rp-sub">{{ $startDate }} → {{ $endDate }}</div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                <div class="rp-card red">
                    <div class="rp-icon"><i data-feather="alert-triangle" class="w-5 h-5"></i></div>
                    <div class="rp-label">No-Show Rate</div>
                    <div class="rp-value text-danger">{{ $summary['no_show_rate'] }}%</div>
                    <div class="rp-sub">{{ $summary['no_show_count'] }} dari {{ $summary['total_bookings'] }} booking</div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                <div class="rp-card orange">
                    <div class="rp-icon"><i data-feather="x-circle" class="w-5 h-5"></i></div>
                    <div class="rp-label">Cancelled Rate</div>
                    <div class="rp-value" style="color:#b45309;">{{ $summary['cancelled_rate'] }}%</div>
                    <div class="rp-sub">{{ $summary['cancelled'] }} dari {{ $summary['total_bookings'] }} booking</div>
                </div>
            </div>
        </div>

        {{-- CHARTS --}}
        <div class="intro-y col-span-12 grid grid-cols-12 gap-5">
            <div class="col-span-12 lg:col-span-8">
                <div class="rp-chart-card" style="background:#fff;">
                    <div class="mb-4">
                        <h4 class="font-semibold text-sm">Tren Net Revenue</h4>
                        <div class="text-slate-500 text-xs mt-0.5">Pendapatan bersih harian di rentang tanggal.</div>
                    </div>
                    <div id="chart-revenue"></div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-4">
                <div class="rp-chart-card" style="background:#fff;">
                    <div class="mb-4">
                        <h4 class="font-semibold text-sm">Proporsi Status</h4>
                        <div class="text-slate-500 text-xs mt-0.5">Distribusi transaksi.</div>
                    </div>
                    <div id="chart-status"></div>
                </div>
            </div>
        </div>

        {{-- STATUS FILTER — pill row --}}
        <div class="intro-y col-span-12 box p-4 rounded-2xl flex flex-wrap items-center gap-2">
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wide mr-2">
                Filter Status
            </span>

            @php
                $statusFilters = [
                    'all'       => ['label' => 'Semua',     'bg' => 'rgba(148,163,184,.12)', 'text' => '#475569', 'icon' => 'list'],
                    'confirmed' => ['label' => 'Confirmed', 'bg' => 'rgba(59,130,246,.12)',  'text' => '#2563eb', 'icon' => 'check-circle'],
                    'completed' => ['label' => 'Completed', 'bg' => 'rgba(34,197,94,.12)',   'text' => '#15803d', 'icon' => 'check-square'],
                    'no-show'   => ['label' => 'No-Show',   'bg' => 'rgba(100,116,139,.12)', 'text' => '#475569', 'icon' => 'user-x'],
                    'cancelled' => ['label' => 'Cancelled', 'bg' => 'rgba(220,38,38,.12)',   'text' => '#b91c1c', 'icon' => 'x-circle'],
                ];
            @endphp

            @foreach($statusFilters as $key => $s)
                @php
                    $count = $key === 'all'
                        ? $summary['total_bookings']
                        : ($statusBreakdown[$key] ?? 0);
                    $isActive = $status === $key;
                @endphp
                <a href="{{ route('admin.reports.financial', array_merge(request()->query(), ['status' => $key])) }}"
                   class="rp-pill"
                   style="
                     background: {{ $isActive ? $s['text'] : $s['bg'] }};
                     color: {{ $isActive ? '#fff' : $s['text'] }};
                   ">
                    <i data-feather="{{ $s['icon'] }}" style="width:12px;height:12px;"></i>
                    {{ $s['label'] }}
                    <b>{{ $count }}</b>
                </a>
            @endforeach
        </div>

        {{-- TABLE TRANSAKSI --}}
        @if($bookings->count())
            <div class="intro-y col-span-12 rp-table-card" style="background:#fff;">
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">CODE</th>
                                <th class="whitespace-nowrap">CUSTOMER</th>
                                <th class="whitespace-nowrap">DATE</th>
                                <th class="whitespace-nowrap">TIME</th>
                                <th class="whitespace-nowrap">ITEMS</th>
                                <th class="text-center whitespace-nowrap">SLOTS</th>
                                <th class="text-right whitespace-nowrap">TOTAL</th>
                                <th class="text-right whitespace-nowrap">NET</th>
                                <th class="text-center whitespace-nowrap">STATUS</th>
                                <th class="text-center whitespace-nowrap">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                                @php
                                    $itemsStr = collect($b->items ?? [])
                                        ->map(fn($it) => ($it['name'] ?? '-') . ' ×' . ($it['qty'] ?? 1))
                                        ->implode(', ');
                                    $isRefund50 = $b->status === 'cancelled' && (int) $b->refund_percent === 50;

                                    $badgeStyle = [
                                        'confirmed' => ['bg' => 'rgba(59,130,246,.12)',  'text' => '#2563eb'],
                                        'completed' => ['bg' => 'rgba(34,197,94,.12)',   'text' => '#15803d'],
                                        'no-show'   => ['bg' => 'rgba(100,116,139,.12)', 'text' => '#475569'],
                                        'cancelled' => ['bg' => 'rgba(220,38,38,.12)',   'text' => '#b91c1c'],
                                    ][$b->status] ?? ['bg' => 'rgba(148,163,184,.12)', 'text' => '#64748b'];
                                @endphp
                                <tr>
                                    <td>
                                        <span class="font-mono font-bold text-xs" style="color: var(--color-primary, #3b82f6);">
                                            {{ $b->booking_code }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-medium whitespace-nowrap text-sm">{{ $b->customer_name }}</div>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            {{ $b->customer_phone }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap text-sm">
                                        {{ $b->booking_date ? $b->booking_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span class="inline-flex items-center font-mono font-bold text-xs"
                                              style="padding:3px 10px;background:rgba(59,130,246,.08);border-radius:6px;color:#1e293b;">
                                            {{ $b->booking_time }}
                                        </span>
                                    </td>
                                    <td style="max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12.5px;color:#64748b;">
                                        {{ $itemsStr ?: '-' }}
                                    </td>
                                    <td class="text-center font-bold whitespace-nowrap">{{ $b->countSlots() }}</td>
                                    <td class="text-right font-medium whitespace-nowrap text-sm">
                                        Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-right whitespace-nowrap">
                                        <div class="font-bold text-success text-sm">
                                            Rp {{ number_format($b->net_revenue, 0, ',', '.') }}
                                        </div>
                                        @if($isRefund50)
                                            <div class="text-xs font-semibold mt-0.5" style="color:#dc2626;">(Refund 50%)</div>
                                        @endif
                                    </td>
                                    <td class="text-center whitespace-nowrap">
                                        <span style="
                                            display:inline-block;
                                            font-size:11px;
                                            font-weight:700;
                                            padding:3px 10px;
                                            border-radius:99px;
                                            text-transform:capitalize;
                                            background:{{ $badgeStyle['bg'] }};
                                            color:{{ $badgeStyle['text'] }};
                                        ">
                                            {{ $b->status }}
                                        </span>
                                    </td>
                                    <td class="text-center whitespace-nowrap">
                                        <a href="{{ route('admin.bookings.show', $b->id) }}"
                                           class="inline-flex items-center gap-1 text-xs font-semibold"
                                           style="color: var(--color-primary, #3b82f6); padding:4px 10px; border-radius:8px;"
                                           onmouseover="this.style.background='rgba(59,130,246,.1)'"
                                           onmouseout="this.style.background='transparent'">
                                            <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="intro-y col-span-12">
                <div class="box p-10 rounded-2xl text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                         style="background:rgba(148,163,184,.1);">
                        <i data-feather="inbox" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">Belum ada transaksi</h3>
                    <p class="text-slate-500 text-sm mb-0">
                        @if($search !== '')
                            Ga ada booking yang cocok dengan pencarian "<b>{{ $search }}</b>".
                        @else
                            Belum ada transaksi di rentang tanggal ini.
                        @endif
                    </p>
                </div>
            </div>
        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);

            // Detect primary color from CSS variable
            var primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--color-primary').trim() || '#3b82f6';

            // ============================================
            // CHART: REVENUE (AREA)
            // ============================================
            var revenueData = @json($revenueChart);

            if (document.querySelector('#chart-revenue') && typeof ApexCharts !== 'undefined') {
                new ApexCharts(document.querySelector('#chart-revenue'), {
                    series: [{ name: 'Net Revenue', data: revenueData.values }],
                    chart: {
                        type: 'area',
                        height: 280,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3, colors: [primaryColor] },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.02,
                            stops: [0, 100]
                        }
                    },
                    colors: [primaryColor],
                    xaxis: {
                        categories: revenueData.labels,
                        labels: { style: { colors: '#94a3b8', fontSize: '11px' } },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#94a3b8', fontSize: '11px' },
                            formatter: function (val) {
                                if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                                if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                                return 'Rp ' + val;
                            }
                        }
                    },
                    grid: {
                        borderColor: 'rgba(148,163,184,.12)',
                        strokeDashArray: 4,
                        xaxis: { lines: { show: false } },
                        yaxis: { lines: { show: true } }
                    },
                    tooltip: {
                        theme: 'light',
                        y: { formatter: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
                    },
                    markers: { size: 0, hover: { size: 6 } }
                }).render();
            }

            // ============================================
            // CHART: STATUS (DONUT)
            // ============================================
            var statusData = @json($statusChart);

            if (document.querySelector('#chart-status') && typeof ApexCharts !== 'undefined') {
                new ApexCharts(document.querySelector('#chart-status'), {
                    series: statusData.values,
                    labels: statusData.labels,
                    chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
                    colors: ['#3b82f6', '#16a34a', '#64748b', '#dc2626'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '72%',
                                labels: {
                                    show: true,
                                    name: { fontSize: '12px', color: '#94a3b8' },
                                    value: {
                                        fontSize: '22px',
                                        fontWeight: 700,
                                        color: '#1e293b',
                                        formatter: v => parseInt(v).toLocaleString('id-ID')
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '12px',
                                        color: '#94a3b8',
                                        formatter: w => w.globals.seriesTotals.reduce((a,b) => a+b, 0).toLocaleString('id-ID')
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                    legend: {
                        position: 'bottom',
                        fontSize: '12px',
                        markers: { width: 8, height: 8, radius: 4 },
                        itemMargin: { horizontal: 8, vertical: 4 },
                        labels: { colors: '#475569' }
                    },
                    stroke: { width: 2, colors: ['#fff'] },
                    tooltip: { theme: 'light', y: { formatter: v => v + ' booking' } }
                }).render();
            }
        });
    </script>

@endsection
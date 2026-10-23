@extends('../layout/' . $layout)

@section('subhead')
    <title>Operational Analytics - Reports</title>
@endsection

@section('subcontent')

<style>
    .rp-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 38px
    }

    .rp-head h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0
    }

    .rp-head .meta {
        font-size: 12.5px;
        color: #94a3b8
    }

    /* ===== FILTER BAR ===== */
    .rp-filter {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
        align-items: flex-end;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .18);
        border-radius: 14px;
        padding: 16px 20px
    }

    .rp-filter label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        display: block;
        margin-bottom: 6px
    }

    .rp-filter .form-control {
        border-radius: 10px !important;
        border-color: rgba(148, 163, 184, .35) !important;
        font-size: 13.5px;
        padding: 9px 14px;
        height: 42px
    }

    .rp-filter .field {
        flex: 1 1 180px;
        min-width: 0
    }

    .rp-filter .actions {
        display: flex;
        gap: 8px;
        align-items: center
    }

    /* ===== CARDS ===== */
    .rp-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-top: 20px
    }

    .rp-card {
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .18);
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        transition: box-shadow .2s, transform .2s;
        position: relative;
        overflow: hidden
    }

    .rp-card:hover {
        box-shadow: 0 4px 12px rgba(15, 23, 42, .06);
        transform: translateY(-1px)
    }

    .rp-card .bg-icon {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 80px;
        color: rgba(59, 130, 246, .06);
        pointer-events: none;
        line-height: 1
    }

    .rp-card .label {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px
    }

    .rp-card .value {
        font-size: 22px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2
    }

    .rp-card .value.blue {
        color: #3b82f6
    }

    .rp-card .value.orange {
        color: #f59e0b
    }

    .rp-card .value.purple {
        color: #8b5cf6
    }

    .rp-card .value.green {
        color: #16a34a
    }

    .rp-card .sub {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
        position: relative;
        z-index: 1
    }

    .rp-card .value,
    .rp-card .label {
        position: relative;
        z-index: 1
    }

    /* ===== CHART ===== */
    .rp-chart-card {
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .18);
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        margin-top: 14px
    }

    .rp-chart-card .head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px
    }

    .rp-chart-card .head h4 {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin: 0
    }

    .rp-chart-card .head .desc {
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 1px
    }

    /* ===== SUB-SECTION ===== */
    .rp-sub {
        margin-top: 26px
    }

    .rp-sub-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px
    }

    .rp-sub-head .icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: none;
        font-size: 14px
    }

    .rp-sub-head .icon.occ {
        background: rgba(59, 130, 246, .1);
        color: #3b82f6
    }

    .rp-sub-head .icon.svc {
        background: rgba(139, 92, 246, .1);
        color: #8b5cf6
    }

    .rp-sub-head h3 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0
    }

    .rp-sub-head .desc {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 1px
    }

    /* ===== TABLE ===== */
    .rp-table-card {
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .18);
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        overflow: hidden;
        margin-top: 14px
    }

    .rp-table-card table {
        width: 100%;
        border-collapse: collapse
    }

    .rp-table-card thead th {
        font-size: 11.5px;
        letter-spacing: .4px;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 600;
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid rgba(148, 163, 184, .18);
        background: #fbfcfe;
        white-space: nowrap
    }

    .rp-table-card tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px dashed rgba(148, 163, 184, .22);
        font-size: 13px;
        color: #334155
    }

    .rp-table-card tbody tr:last-child td {
        border-bottom: none
    }

    .rp-table-card tbody tr:hover {
        background: #fbfcfe
    }

    .rp-time-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: monospace;
        font-weight: 700;
        font-size: 12.5px;
        color: #1e293b;
        padding: 3px 10px;
        background: rgba(59, 130, 246, .08);
        border-radius: 6px
    }

    .rp-bar {
        height: 8px;
        background: rgba(59, 130, 246, .12);
        border-radius: 999px;
        overflow: hidden;
        min-width: 100px
    }

    .rp-bar>span {
        display: block;
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        border-radius: 999px;
        transition: width .6s cubic-bezier(.4, 0, .2, 1)
    }

    /* ===== RANK BADGE ===== */
    .rp-rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 800
    }

    .rp-rank.r1 {
        background: rgba(245, 158, 11, .15);
        color: #b45309
    }

    .rp-rank.r2 {
        background: rgba(148, 163, 184, .2);
        color: #475569
    }

    .rp-rank.r3 {
        background: rgba(180, 83, 9, .12);
        color: #92400e
    }

    .rp-rank.rx {
        background: rgba(148, 163, 184, .1);
        color: #94a3b8
    }

    .rp-empty {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
        font-size: 13.5px;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .18);
        border-radius: 14px;
        margin-top: 14px
    }

    .rp-empty .icon {
        font-size: 32px;
        color: #cbd5e1;
        margin-bottom: 10px
    }

    @media (max-width: 1023px) {
        .rp-cards {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .rp-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- HEADER --}}
<div class="intro-y rp-head">
    <div>
        <h2>Operational Analytics</h2>
        <div class="meta">Occupancy (jam ramai/sepi) + Service (treatment laris).</div>
    </div>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('admin.reports.operational') }}" class="intro-y rp-filter">
    <div class="field">
        <label>Start Date</label>
        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" required>
    </div>
    <div class="field">
        <label>End Date</label>
        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" required>
    </div>
    <div class="actions">
        <button type="submit" class="btn btn-primary shadow-md">
            <i data-feather="filter" class="w-4 h-4 mr-1 inline"></i>
            Terapkan
        </button>
        <a href="{{ route('admin.reports.operational') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

{{-- ===== OCCUPANCY ===== --}}
<div class="rp-sub">
    <div class="rp-sub-head">
        <div class="icon occ"><i data-feather="clock"></i></div>
        <div>
            <h3>Occupancy — Jam Ramai vs Sepi</h3>
            <div class="desc">Buat atur staf & jadwal operasional.</div>
        </div>
    </div>

    @if($occupancy->count())
        <div class="rp-cards" style="margin-bottom:14px">
            <div class="rp-card">
                <div class="bg-icon"><i data-feather="trending-up"></i></div>
                <div class="label"><i data-feather="trending-up" style="width:11px;height:11px"></i> Jam Paling Rame</div>
                <div class="value blue">{{ $busiestTime['time'] ?? '-' }}</div>
                <div class="sub">{{ $busiestTime['booked'] ?? 0 }} booking • {{ $busiestTime['slots'] ?? 0 }} slot</div>
            </div>
            <div class="rp-card">
                <div class="bg-icon"><i data-feather="trending-down"></i></div>
                <div class="label"><i data-feather="trending-down" style="width:11px;height:11px"></i> Jam Paling Sepi</div>
                <div class="value orange">{{ $quietestTime['time'] ?? '-' }}</div>
                <div class="sub">{{ $quietestTime['booked'] ?? 0 }} booking • {{ $quietestTime['slots'] ?? 0 }} slot</div>
            </div>
        </div>

        {{-- OCCUPANCY CHART (BAR) --}}
        <div class="rp-chart-card">
            <div class="head">
                <div>
                    <h4>Distribusi Booking per Jam</h4>
                    <div class="desc">Visualisasi jam paling sibuk.</div>
                </div>
            </div>
            <div id="chart-occupancy"></div>
        </div>

        <div class="rp-table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width:120px">Jam</th>
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
                            <td>
                                <span class="rp-time-badge">
                                    <i data-feather="clock" style="width:11px;height:11px"></i>
                                    {{ $row['time'] }}
                                </span>
                            </td>
                            <td style="text-align:center;font-weight:700">{{ $row['booked'] }}</td>
                            <td style="text-align:center;font-weight:700">{{ $row['slots'] }}</td>
                            <td>
                                <div class="rp-bar">
                                    <span style="width: {{ $row['slots'] > 0 ? ($row['slots'] / $maxSlots) * 100 : 0 }}%"></span>
                                </div>
                            </td>
                            <td style="text-align:right;font-weight:700;color:#16a34a">
                                Rp {{ number_format($row['revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <div class="icon"><i data-feather="clock" style="width:36px;height:36px"></i></div>
            Belum ada data occupancy di rentang tanggal ini.
        </div>
    @endif
</div>

{{-- ===== SERVICE ===== --}}
<div class="rp-sub">
    <div class="rp-sub-head">
        <div class="icon svc"><i data-feather="package"></i></div>
        <div>
            <h3>Service — Treatment Paling Laris</h3>
            <div class="desc">Buat evaluasi stok & performa layanan.</div>
        </div>
    </div>

    @if($services->count())
        {{-- SERVICE CHART (HORIZONTAL BAR) --}}
        <div class="rp-chart-card" style="margin-bottom:14px">
            <div class="head">
                <div>
                    <h4>Top Treatment Terlaris</h4>
                    <div class="desc">Berdasarkan qty terjual.</div>
                </div>
            </div>
            <div id="chart-service"></div>
        </div>

        <div class="rp-table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width:60px;text-align:center">Rank</th>
                        <th>Service / Treatment</th>
                        <th style="width:120px;text-align:center">Qty Terjual</th>
                        <th style="width:120px;text-align:center">Slots</th>
                        <th style="text-align:right;width:180px">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $i => $s)
                        <tr>
                            <td style="text-align:center">
                                @php
                                    $rankClass = $i === 0 ? 'r1' : ($i === 1 ? 'r2' : ($i === 2 ? 'r3' : 'rx'));
                                @endphp
                                <span class="rp-rank {{ $rankClass }}">{{ $i + 1 }}</span>
                            </td>
                            <td style="font-weight:600;color:#1e293b">{{ $s['name'] }}</td>
                            <td style="text-align:center;font-weight:700">{{ $s['qty'] }}</td>
                            <td style="text-align:center;font-weight:700">{{ $s['slots'] }}</td>
                            <td style="text-align:right;font-weight:700;color:#16a34a">
                                Rp {{ number_format($s['revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <div class="icon"><i data-feather="package" style="width:36px;height:36px"></i></div>
            Belum ada service terjual di rentang tanggal ini.
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.feather) feather.replace();

        // ============================================
        // CHART: OCCUPANCY (BAR)
        // ============================================
        @if($occupancy->count())
            var occData = @json($occupancy);

            if (document.querySelector('#chart-occupancy') && typeof ApexCharts !== 'undefined') {
                var occChart = new ApexCharts(document.querySelector('#chart-occupancy'), {
                    series: [{
                        name: 'Bookings',
                        data: occData.map(function (r) { return r.booked; })
                    }, {
                        name: 'Slots',
                        data: occData.map(function (r) { return r.slots; })
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        fontFamily: 'inherit',
                        toolbar: { show: false },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 600
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '55%',
                            horizontal: false
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: true, width: 2, colors: ['transparent'] },
                    colors: ['#3b82f6', '#8b5cf6'],
                    xaxis: {
                        categories: occData.map(function (r) { return r.time; }),
                        labels: {
                            style: { colors: '#94a3b8', fontSize: '11px' }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#94a3b8', fontSize: '11px' }
                        }
                    },
                    grid: {
                        borderColor: 'rgba(148,163,184,.15)',
                        strokeDashArray: 4,
                        xaxis: { lines: { show: false } },
                        yaxis: { lines: { show: true } }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '12px',
                        markers: { width: 8, height: 8, radius: 4 },
                        labels: { colors: '#475569' }
                    },
                    tooltip: {
                        theme: 'light',
                        y: {
                            formatter: function (val) {
                                return val + ' unit';
                            }
                        }
                    }
                });
                occChart.render();
            }
        @endif

        // ============================================
        // CHART: SERVICE (HORIZONTAL BAR)
        // ============================================
        @if($services->count())
            var svcData = @json($services);

            if (document.querySelector('#chart-service') && typeof ApexCharts !== 'undefined') {
                // Ambil top 5 aja biar rapi
                var top5 = svcData.slice(0, 5);

                var svcChart = new ApexCharts(document.querySelector('#chart-service'), {
                    series: [{
                        name: 'Qty Terjual',
                        data: top5.map(function (s) { return s.qty; })
                    }],
                    chart: {
                        type: 'bar',
                        height: Math.max(200, top5.length * 50),
                        fontFamily: 'inherit',
                        toolbar: { show: false },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 600
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            horizontal: true,
                            barHeight: '60%',
                            distributed: true
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '11px',
                            fontWeight: 700,
                            colors: ['#fff']
                        }
                    },
                    colors: ['#3b82f6', '#8b5cf6', '#a855f7', '#ec4899', '#f59e0b'],
                    xaxis: {
                        categories: top5.map(function (s) { return s.name; }),
                        labels: {
                            style: { colors: '#94a3b8', fontSize: '11px' }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#475569', fontSize: '12px', fontWeight: 600 }
                        }
                    },
                    grid: {
                        borderColor: 'rgba(148,163,184,.15)',
                        strokeDashArray: 4
                    },
                    legend: { show: false },
                    tooltip: {
                        theme: 'light',
                        y: {
                            formatter: function (val) {
                                return val + ' unit terjual';
                            }
                        }
                    }
                });
                svcChart.render();
            }
        @endif

        setTimeout(function () { if (window.feather) feather.replace(); }, 100);
    });
</script>

@endsection
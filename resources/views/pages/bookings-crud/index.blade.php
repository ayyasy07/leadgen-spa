@extends('../layout/' . $layout)

@section('subhead')
    <title>Bookings - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* Soft alert */
        .soft-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .soft-alert svg {
            width: 16px;
            height: 16px;
            flex: none;
        }

        .soft-alert-success {
            background: rgba(34, 197, 94, .12);
            color: #15803d;
            border-color: rgba(34, 197, 94, .25);
        }

        .soft-alert-success svg {
            color: #16a34a;
        }

        .soft-alert-danger {
            background: rgba(239, 68, 68, .1);
            color: #b91c1c;
            border-color: rgba(239, 68, 68, .25);
        }

        .soft-alert-danger svg {
            color: #ef4444;
        }

        /* Tabs */
        .bk-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            color: rgb(var(--color-slate-500));
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: all .15s;
            cursor: pointer;
            white-space: nowrap;
        }

        .bk-tab:hover {
            color: rgb(var(--color-primary));
        }

        .bk-tab.active {
            color: rgb(var(--color-primary));
            border-bottom-color: rgb(var(--color-primary));
        }

        .bk-tab .count {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgb(var(--color-slate-200));
            color: rgb(var(--color-slate-600));
        }

        .dark .bk-tab .count {
            background: rgb(var(--color-darkmode-600));
            color: rgb(var(--color-slate-400));
        }

        .bk-tab.active .count {
            background: rgb(var(--color-primary) / .12);
            color: rgb(var(--color-primary));
        }

        /* Table — horizontal scroll */
        .table-scroll {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            border-radius: 14px;
        }

        .table-scroll table {
            width: 100%;
            min-width: 1200px;
            border-collapse: collapse;
        }

        .table-scroll table th {
            white-space: nowrap;
        }

        .table-scroll table td {
            white-space: nowrap;
        }

        /* Row locked */
        .table-report tbody tr.row-locked {
            background: rgb(var(--color-danger) / .04);
        }

        /* Pagination */
        .page-item.active .page-link {
            background: rgb(var(--color-primary)) !important;
            border-color: rgb(var(--color-primary)) !important;
            color: #fff !important;
        }
    </style>

    {{-- PAGE TITLE --}}
    <h2 class="intro-y text-lg font-medium mt-10">Bookings</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- STATUS TABS --}}
        <div class="intro-y col-span-12">
            <div class="flex flex-wrap gap-1 border-b border-slate-200/60 dark:border-darkmode-400">
                @php
                    $tabs = [
                        'all'       => ['label' => 'All',       'icon' => 'list'],
                        'pending'   => ['label' => 'Pending',   'icon' => 'clock'],
                        'paid'      => ['label' => 'Paid',      'icon' => 'check-circle'],
                        'completed' => ['label' => 'Completed', 'icon' => 'check-square'],
                        'cancelled' => ['label' => 'Cancelled', 'icon' => 'x-circle'],
                        'no-show'   => ['label' => 'No-Show',   'icon' => 'user-x'],
                        'expired'   => ['label' => 'Expired',   'icon' => 'alert-circle'],
                    ];
                @endphp
                @foreach($tabs as $key => $tab)
                    <a href="{{ route('admin.bookings.index', array_filter(['status' => $key === 'all' ? null : $key, 'search' => $filterSearch])) }}"
                       class="bk-tab {{ $filterStatus === $key ? 'active' : '' }}">
                        <i data-feather="{{ $tab['icon'] }}" style="width:14px;height:14px"></i>
                        {{ $tab['label'] }}
                        <span class="count">{{ $counts[$key] ?? 0 }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- SEARCH --}}
        <div class="intro-y col-span-12">
            <form method="GET" action="{{ route('admin.bookings.index') }}"
                  class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="status" value="{{ $filterStatus }}">

                <div class="relative text-slate-500" style="flex:1;min-width:220px;max-width:400px;">
                    <input type="text" name="search" value="{{ $filterSearch }}"
                           class="form-control box w-full pr-10"
                           placeholder="Search booking code, name, or phone...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>

                <button type="submit" class="btn btn-primary shadow-md">
                    <i data-feather="search" class="w-4 h-4 mr-1"></i> Search
                </button>

                @if($filterSearch)
                    <a href="{{ route('admin.bookings.index', ['status' => $filterStatus !== 'all' ? $filterStatus : null]) }}"
                       class="btn btn-outline-secondary">
                        <i data-feather="x" class="w-4 h-4 mr-1"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="intro-y col-span-12">
                <div class="soft-alert soft-alert-success">
                    <i data-feather="check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="intro-y col-span-12">
                <div class="soft-alert soft-alert-danger">
                    <i data-feather="alert-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- EMPTY STATE --}}
        @if($bookings->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="calendar" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No bookings yet</h3>
                    <p class="text-slate-500 text-sm mb-0">Bookings will appear here once customers make a booking.</p>
                </div>
            </div>
        @else

            {{-- DATA LIST --}}
            <div class="intro-y col-span-12">
                <div class="table-scroll">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">CODE</th>
                                <th class="whitespace-nowrap">CUSTOMER</th>
                                <th class="whitespace-nowrap">SCHEDULE</th>
                                <th class="whitespace-nowrap">ITEMS</th>
                                <th class="whitespace-nowrap">BOOKED AT</th>
                                <th class="text-right whitespace-nowrap">TOTAL</th>
                                <th class="text-right whitespace-nowrap">NET REVENUE</th>
                                <th class="text-center whitespace-nowrap">STATUS</th>
                                <th class="text-center whitespace-nowrap">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                                @php
                                    $isLocked  = in_array($b->status, ['completed', 'no-show', 'cancelled', 'expired']);
                                    $canCancel = in_array($b->status, ['pending', 'paid']);

                                    $statusStyle = [
                                    'pending'   => ['bg' => 'rgba(245,158,11,.12)',  'text' => '#b45309', 'icon' => 'clock'],
                                    'paid'      => ['bg' => 'rgb(var(--color-primary) / .12)', 'text' => 'rgb(var(--color-primary))', 'icon' => 'check-circle'],
                                    'completed' => ['bg' => 'rgba(34,197,94,.12)',   'text' => '#15803d', 'icon' => 'check-square'],
                                    'cancelled' => ['bg' => 'rgba(239,68,68,.12)',   'text' => '#b91c1c', 'icon' => 'x-circle'],
                                    'no-show'   => ['bg' => 'rgba(100,116,139,.15)','text' => '#64748b', 'icon' => 'user-x'],
                                    'expired'   => ['bg' => 'rgba(220,38,38,.12)',  'text' => '#dc2626', 'icon' => 'lock'],
                                ][$b->status] ?? ['bg' => 'rgba(148,163,184,.12)', 'text' => '#64748b', 'icon' => 'clock'];
                                @endphp
                                <tr class="intro-x {{ $isLocked ? 'row-locked' : '' }}">

                                    {{-- CODE --}}
                                    <td>
                                        <span class="font-mono font-bold text-xs text-primary">
                                            {{ $b->booking_code }}
                                        </span>
                                    </td>

                                    {{-- CUSTOMER --}}
                                    <td>
                                        <div class="font-medium whitespace-nowrap">
                                            {{ $b->customer_name }}
                                        </div>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            {{ $b->customer_phone }}
                                        </div>
                                    </td>

                                    {{-- SCHEDULE --}}
                                    <td>
                                        <div class="font-medium text-slate-800 dark:text-slate-200 text-sm whitespace-nowrap">
                                            {{ $b->booking_date->format('d M Y') }}
                                        </div>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            <i data-feather="clock" style="width:11px;height:11px;display:inline-block;vertical-align:-1px;"></i>
                                            {{ $b->booking_time }}
                                        </div>
                                    </td>

                                    {{-- ITEMS --}}
                                    <td>
                                        <div class="text-sm">
                                            <span class="font-medium">{{ count($b->items ?? []) }} items</span>
                                        </div>
                                        @if(!empty($b->items))
                                            <div class="text-slate-500 text-xs mt-0.5" style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                @foreach(array_slice($b->items, 0, 2) as $it)
                                                    {{ $it['name'] }} (x{{ $it['qty'] }})@if(!$loop->last), @endif
                                                @endforeach
                                                @if(count($b->items) > 2) ... @endif
                                            </div>
                                        @endif
                                    </td>

                                    {{-- BOOKED AT --}}
                                    <td>
                                        <div class="text-slate-600 dark:text-slate-400 text-sm whitespace-nowrap">
                                            {{ $b->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-slate-400 text-xs whitespace-nowrap mt-0.5">
                                            {{ $b->created_at->diffForHumans() }}
                                        </div>
                                    </td>

                                    {{-- TOTAL --}}
                                    <td class="text-right">
                                        <span class="font-medium whitespace-nowrap">
                                            Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    {{-- NET REVENUE --}}
                                    <td class="text-right">
                                        <span class="font-medium whitespace-nowrap"
                                              style="color: {{ $b->net_revenue > 0 ? '#16a34a' : '#94a3b8' }};">
                                            Rp {{ number_format($b->net_revenue, 0, ',', '.') }}
                                        </span>
                                        @if($b->refund_percent > 0)
                                            <div class="text-xs whitespace-nowrap mt-0.5" style="color:#b45309;font-weight:600;">
                                                <i data-feather="corner-up-left" style="width:10px;height:10px;display:inline-block;vertical-align:-1px"></i>
                                                Refund {{ $b->refund_percent }}%
                                            </div>
                                        @endif
                                    </td>

                                    {{-- STATUS — inline style --}}
                                    <td class="text-center">
                                        <span class="inline-flex items-center whitespace-nowrap"
                                              style="
                                                padding: 4px 10px;
                                                border-radius: 999px;
                                                font-size: 11.5px;
                                                font-weight: 700;
                                                text-transform: uppercase;
                                                letter-spacing: .3px;
                                                background: {{ $statusStyle['bg'] }};
                                                color: {{ $statusStyle['text'] }};
                                              ">
                                            <i data-feather="{{ $statusStyle['icon'] }}" style="width:12px;height:12px;margin-right:5px;"></i>
                                            {{ $b->status_label }}
                                        </span>
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a class="flex items-center text-primary hover:underline whitespace-nowrap"
                                               href="{{ route('admin.bookings.show', $b->id) }}">
                                                <i data-feather="eye" class="w-4 h-4 mr-1"></i> View
                                            </a>

                                            @if($canCancel)
                                                <form action="{{ route('admin.bookings.cancel', $b->id) }}" method="POST"
                                                      onsubmit="return confirm('Cancel this booking? The slot will be released.')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="flex items-center text-danger hover:underline whitespace-nowrap">
                                                        <i data-feather="x" class="w-4 h-4 mr-1"></i> Cancel
                                                    </button>
                                                </form>
                                            @else
                                                <span class="flex items-center text-slate-400 whitespace-nowrap" title="Status locked">
                                                    <i data-feather="lock" class="w-4 h-4 mr-1"></i> Locked
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $bookings->firstItem() }}</span>
                    to <span class="font-medium">{{ $bookings->lastItem() }}</span>
                    of <span class="font-medium">{{ $bookings->total() }}</span> bookings
                </div>
                <div class="w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                    @if($bookings->hasPages())
                        {{ $bookings->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>

        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);
        });
    </script>
@endsection
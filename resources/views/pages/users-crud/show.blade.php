@extends('../layout/' . $layout)

@section('subhead')
    <title>User Detail - Icewall</title>
@endsection

@section('subcontent')

    <style>
        .uc-avatar{
            width:80px;height:80px;border-radius:50%;
            background:linear-gradient(135deg,rgb(var(--color-primary)),rgb(var(--color-primary) / .7));
            color:#fff;font-weight:700;font-size:28px;
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 8px 20px rgb(var(--color-primary) / .25);
        }
        .meta-row{
            display:flex;align-items:flex-start;gap:12px;
            padding:12px 0;
            border-bottom:1px dashed rgb(var(--color-slate-200) / .7);
        }
        .dark .meta-row{border-bottom-color:rgb(var(--color-darkmode-400) / .7)}
        .meta-row:last-child{border-bottom:none}
        .meta-row .meta-label{
            font-size:12px;font-weight:600;
            color:rgb(var(--color-slate-500));
            min-width:110px;text-transform:uppercase;letter-spacing:.3px;
            padding-top:2px;
        }
        .dark .meta-row .meta-label{color:rgb(var(--color-slate-400))}
        .meta-row .meta-value{
            font-size:13.5px;
            color:rgb(var(--color-slate-800));
            font-weight:500;flex:1;
        }
        .dark .meta-row .meta-value{color:rgb(var(--color-slate-200))}
        .meta-row .meta-value a{color:rgb(var(--color-primary));text-decoration:none}
        .meta-row .meta-value a:hover{text-decoration:underline}

        .role-badge{
            display:inline-flex;align-items:center;gap:6px;
            font-size:11.5px;font-weight:700;
            padding:4px 12px;border-radius:999px;
            text-transform:uppercase;letter-spacing:.3px;
        }
        .role-badge.admin{color:#7c3aed;background:rgba(124,58,237,.12)}
        .role-badge.customer{color:rgb(var(--color-primary));background:rgb(var(--color-primary) / .12)}

        .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
        .stat-card{
            background:rgb(var(--color-slate-50));
            border:1px solid rgb(var(--color-slate-200) / .7);
            border-radius:12px;padding:16px 18px;
            text-align:center;min-width:0;
        }
        .dark .stat-card{background:rgb(var(--color-darkmode-700));border-color:rgb(var(--color-darkmode-400))}
        .stat-value{
            font-size:22px;font-weight:800;
            color:rgb(var(--color-slate-800));margin-bottom:2px;
        }
        .dark .stat-value{color:rgb(var(--color-slate-200))}
        .stat-label{
            font-size:11px;font-weight:600;
            color:rgb(var(--color-slate-500));
            text-transform:uppercase;letter-spacing:.3px;
        }
        .dark .stat-label{color:rgb(var(--color-slate-400))}
        .stat-card.pending .stat-value{color:#b45309}
        .stat-card.paid .stat-value{color:rgb(var(--color-primary))}
        .stat-card.completed .stat-value{color:#15803d}
        .stat-card.cancelled .stat-value{color:#b91c1c}
        .stat-card.revenue{
            background:rgba(34,197,94,.06);
            border-color:rgba(34,197,94,.2);
        }
        .dark .stat-card.revenue{background:rgba(34,197,94,.1)}
        .stat-card.revenue .stat-value{color:#16a34a;font-size:18px}
        @media (max-width:640px){.stat-grid{grid-template-columns:repeat(2,1fr)}}

        .bk-table{width:100%;border-collapse:collapse}
        .bk-table thead th{
            font-size:11.5px;letter-spacing:.4px;text-transform:uppercase;
            color:rgb(var(--color-slate-500));font-weight:600;
            padding:12px 16px;text-align:center;
            border-bottom:1px solid rgb(var(--color-slate-200));
            background:rgb(var(--color-slate-50));
        }
        .dark .bk-table thead th{
            color:rgb(var(--color-slate-400));
            border-bottom-color:rgb(var(--color-darkmode-400));
            background:rgb(var(--color-darkmode-700));
        }
        .bk-table tbody td{
            padding:14px 16px;vertical-align:middle;
            border-bottom:1px dashed rgb(var(--color-slate-200) / .7);
            font-size:13px;
            color:rgb(var(--color-slate-600));
            text-align:center;
        }
        .dark .bk-table tbody td{
            border-bottom-color:rgb(var(--color-darkmode-400) / .7);
            color:rgb(var(--color-slate-300));
        }
        .bk-table tbody tr:last-child td{border-bottom:none}
        .bk-table tbody tr:hover{background:rgb(var(--color-slate-50))}
        .dark .bk-table tbody tr:hover{background:rgb(var(--color-darkmode-700))}

        .bk-code{
            font-family:monospace;font-weight:700;font-size:12.5px;
            color:rgb(var(--color-primary));text-decoration:none;
        }
        .bk-code:hover{text-decoration:underline}

        .bk-status{
            display:inline-flex;align-items:center;gap:6px;
            font-size:11px;font-weight:700;
            padding:3px 10px;border-radius:999px;
            text-transform:uppercase;letter-spacing:.3px;
        }
        .bk-status.pending{color:#b45309;background:rgba(245,158,11,.12)}
        .bk-status.paid{color:rgb(var(--color-primary));background:rgb(var(--color-primary) / .12)}
        .bk-status.completed{color:#15803d;background:rgba(34,197,94,.12)}
        .bk-status.cancelled{color:#b91c1c;background:rgba(239,68,68,.12)}
        .bk-status.no-show{color:#64748b;background:rgba(100,116,139,.15)}
        .bk-status.expired{color:#dc2626;background:rgba(220,38,38,.12)}

        .bk-empty{
            text-align:center;padding:48px 20px;
            color:rgb(var(--color-slate-500));font-size:13px;
        }
        .dark .bk-empty{color:rgb(var(--color-slate-400))}
        .bk-empty svg{width:48px;height:48px;color:rgb(var(--color-slate-300));margin:0 auto 12px;display:block}
    </style>

    {{-- TOOLBAR --}}
    <div class="intro-y flex flex-wrap sm:flex-nowrap items-center gap-3 mt-8">
        <a href="{{ route('admin.users.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-lg font-medium text-slate-800 dark:text-slate-200">User Detail</h2>
            <div class="text-slate-500 text-xs mt-0.5">{{ $user->name }} — {{ $user->email }}</div>
        </div>
        <div class="flex items-center gap-2 sm:ml-auto mt-3 sm:mt-0">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary shadow-md">
                <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="intro-y mt-5">
            <div class="alert alert-soft-success flex items-center">
                <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- LEFT: PROFILE --}}
        <div class="intro-y col-span-12 lg:col-span-4">
            <div class="box">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                        <i data-feather="user" class="w-5 h-5"></i>
                    </div>
                    <h2 class="font-medium text-base">Profile</h2>
                </div>
                <div class="p-5">
                    <div class="text-center mb-5">
                        <div class="uc-avatar mx-auto">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h3 class="text-base font-medium mt-3 text-slate-800 dark:text-slate-200">{{ $user->name }}</h3>
                        <div class="mt-2">
                            <span class="role-badge {{ $user->role }}">
                                <i data-feather="{{ $user->role === 'admin' ? 'shield' : 'user' }}" style="width:11px;height:11px"></i>
                                {{ $user->role }}
                            </span>
                        </div>
                    </div>

                    <div class="meta-row">
                        <span class="meta-label">Email</span>
                        <span class="meta-value">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Phone</span>
                        <span class="meta-value">{{ $user->phone ?: '-' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Joined</span>
                        <span class="meta-value">{{ $user->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Updated</span>
                        <span class="meta-value">{{ $user->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="intro-y col-span-12 lg:col-span-8">

            {{-- STATS --}}
            <div class="box">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                        <i data-feather="bar-chart-2" class="w-5 h-5"></i>
                    </div>
                    <h2 class="font-medium text-base">Booking Statistics</h2>
                </div>
                <div class="p-5">
                    <div class="stat-grid">
                        <div class="stat-card">
                            <div class="stat-value">{{ $bookingStats['total'] }}</div>
                            <div class="stat-label">Total</div>
                        </div>
                        <div class="stat-card pending">
                            <div class="stat-value">{{ $bookingStats['pending'] }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-card paid">
                            <div class="stat-value">{{ $bookingStats['paid'] }}</div>
                            <div class="stat-label">Paid</div>
                        </div>
                        <div class="stat-card completed">
                            <div class="stat-value">{{ $bookingStats['completed'] }}</div>
                            <div class="stat-label">Completed</div>
                        </div>
                    </div>
                    <div class="stat-grid mt-3" style="grid-template-columns: repeat(2, 1fr);">
                        <div class="stat-card cancelled">
                            <div class="stat-value">{{ $bookingStats['cancelled'] + $bookingStats['no-show'] }}</div>
                            <div class="stat-label">Cancelled / No-Show</div>
                        </div>
                        <div class="stat-card revenue">
                            <div class="stat-value">Rp {{ number_format($bookingStats['revenue'], 0, ',', '.') }}</div>
                            <div class="stat-label">Total Revenue</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BOOKING HISTORY --}}
            <div class="box mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                        <i data-feather="calendar" class="w-5 h-5"></i>
                    </div>
                    <h2 class="font-medium text-base mr-auto">Booking History</h2>
                    <span class="text-slate-500 text-xs">{{ $bookings->count() }} booking(s)</span>
                </div>

                <div style="padding:0">
                    @if($bookings->count())
                        <div class="overflow-x-auto">
                            <table class="bk-table">
                                <thead>
                                    <tr>
                                        <th style="width:160px;">Code</th>
                                        <th style="width:170px;">Schedule</th>
                                        <th>Items</th>
                                        <th style="width:130px;">Total</th>
                                        <th style="width:130px;">Status</th>
                                        <th style="width:80px;">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $b)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $b->id) }}" class="bk-code">
                                                    {{ $b->booking_code }}
                                                </a>
                                            </td>
                                            <td>
                                                <div style="font-weight:600;color:rgb(var(--color-slate-800));font-size:13px;">
                                                    {{ $b->booking_date->format('d M Y') }}
                                                </div>
                                                <div style="font-size:12px;color:rgb(var(--color-slate-400));margin-top:2px;">
                                                    {{ $b->booking_time }}
                                                </div>
                                            </td>
                                            <td>
                                                <span style="font-size:12.5px;color:rgb(var(--color-slate-500));">
                                                    {{ count($b->items ?? []) }} items
                                                </span>
                                            </td>
                                            <td style="font-weight:700;color:rgb(var(--color-slate-800));">
                                                Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="bk-status {{ $b->status }}">
                                                    {{ $b->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $b->id) }}" class="text-primary">
                                                    <i data-feather="eye" style="width:14px;height:14px"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bk-empty">
                            <i data-feather="calendar"></i>
                            No bookings yet.
                        </div>
                    @endif
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
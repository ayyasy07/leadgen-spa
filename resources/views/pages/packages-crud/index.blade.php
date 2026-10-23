@extends('../layout/' . $layout)

@section('subhead')
    <title>Packages - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* ===== Soft alert ===== */
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
        .soft-alert svg { width: 16px; height: 16px; flex: none; }
        .soft-alert-success { background: rgba(34, 197, 94, .12); color: #15803d; border-color: rgba(34, 197, 94, .25); }
        .soft-alert-success svg { color: #16a34a; }
        .soft-alert-danger { background: rgba(239, 68, 68, .1); color: #b91c1c; border-color: rgba(239, 68, 68, .25); }
        .soft-alert-danger svg { color: #ef4444; }
        .soft-alert-warning { background: rgba(245, 158, 11, .12); color: #92400e; border-color: rgba(245, 158, 11, .25); }
        .soft-alert-warning svg { color: #f59e0b; }
    </style>

    {{-- PAGE TITLE --}}
    <h2 class="intro-y text-lg font-medium mt-10">Packages</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- TOOLBAR --}}
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">

            {{-- FILTER UNIT + FEATURED --}}
            <div class="flex items-center gap-2 flex-wrap">

                {{-- TAB UNIT --}}
                <div class="inline-flex items-center gap-1.5 p-1 rounded-full bg-slate-100 dark:bg-darkmode-700">
                    <a href="{{ route('admin.packages.index', array_filter(['featured' => $filter === 'featured' ? 1 : null])) }}"
                       class="btn {{ $unit === 'all' ? 'btn-primary shadow-md' : 'btn-ghost' }} !rounded-full !px-4 !py-1.5 !text-xs !font-semibold">
                        <i data-feather="list" class="w-3.5 h-3.5 mr-1"></i>
                        All
                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $unit === 'all' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                            {{ $countsAll }}
                        </span>
                    </a>

                    <a href="{{ route('admin.packages.index', array_filter(['unit' => 'person', 'featured' => $filter === 'featured' ? 1 : null])) }}"
                       class="btn {{ $unit === 'person' ? 'btn-primary shadow-md' : 'btn-ghost' }} !rounded-full !px-4 !py-1.5 !text-xs !font-semibold">
                        <i data-feather="user" class="w-3.5 h-3.5 mr-1"></i>
                        Person
                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $unit === 'person' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                            {{ $countsPerUnit['person'] ?? 0 }}
                        </span>
                    </a>

                    <a href="{{ route('admin.packages.index', array_filter(['unit' => 'couple', 'featured' => $filter === 'featured' ? 1 : null])) }}"
                       class="btn {{ $unit === 'couple' ? 'btn-primary shadow-md' : 'btn-ghost' }} !rounded-full !px-4 !py-1.5 !text-xs !font-semibold">
                        <i data-feather="users" class="w-3.5 h-3.5 mr-1"></i>
                        Couple
                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $unit === 'couple' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                            {{ $countsPerUnit['couple'] ?? 0 }}
                        </span>
                    </a>
                </div>

                {{-- TOMBOL FEATURED --}}
                <a href="{{ route('admin.packages.index', array_filter(['unit' => $unit !== 'all' ? $unit : null, 'featured' => $filter === 'featured' ? null : 1])) }}"
                   class="btn {{ $filter === 'featured' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                    <i data-feather="star" class="w-4 h-4 mr-1"></i>
                    Featured
                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $filter === 'featured' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                        {{ $featuredCurrentUnit }}
                    </span>
                </a>

            </div>

            {{-- TOTAL --}}
            <div class="hidden md:block mx-auto text-slate-500">
                Total: <span class="font-medium">{{ $countsAll }}</span> packages
            </div>

            {{-- ADD BUTTON --}}
            <a href="{{ route('admin.packages.create') }}" class="btn btn-primary shadow-md">
                <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Package
            </a>
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

        {{-- EMPTY --}}
        @if($packages->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="package" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">
                        @if($unit !== 'all' || $filter === 'featured')
                            No packages match this filter
                        @else
                            No packages yet
                        @endif
                    </h3>
                    <p class="text-slate-500 text-sm mb-5">
                        @if($unit !== 'all' || $filter === 'featured')
                            Try changing or resetting the filter.
                        @else
                            Start by creating your first bundling package.
                        @endif
                    </p>
                    @if($unit === 'all' && $filter !== 'featured')
                        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
                            <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Package
                        </a>
                    @else
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
                            <i data-feather="rotate-ccw" class="w-4 h-4 mr-1"></i> Reset Filter
                        </a>
                    @endif
                </div>
            </div>
        @else

            {{-- TABLE --}}
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">IMAGE</th>
                            <th class="whitespace-nowrap">NAME</th>
                            <th class="whitespace-nowrap">INCLUDED ITEMS</th>
                            <th class="text-center whitespace-nowrap">PRICE</th>
                            <th class="text-center whitespace-nowrap">STATUS</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $p)
                            <tr class="intro-x">
                                <td class="w-40">
                                    @if($p->image)
                                        <div class="w-16 h-16 image-fit zoom-in">
                                            <img src="{{ asset('storage/'.$p->image) }}"
                                                 alt="{{ $p->name }}"
                                                 class="tooltip rounded"
                                                 title="{{ $p->name }}">
                                        </div>
                                    @else
                                        <div class="w-16 h-16 rounded bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center">
                                            <i data-feather="image" class="w-5 h-5 text-slate-400"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.packages.edit', $p) }}"
                                        class="font-medium whitespace-nowrap hover:text-primary">
                                            {{ $p->name }}
                                            @if($p->is_featured)
                                                <i data-feather="star"
                                                class="w-3.5 h-3.5 ml-1 inline-block"
                                                style="fill:#f59e0b;color:#f59e0b;vertical-align:middle;"
                                                title="Featured on Homepage"></i>
                                            @endif
                                            @if($p->is_popular)
                                                <span class="inline-flex items-center ml-1.5"
                                                    style="padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; background: rgba(247,108,108,.12); color: rgb(var(--color-primary)); vertical-align: middle;">
                                                    <i data-feather="award" style="width:10px;height:10px;margin-right:3px;"></i>
                                                    Popular
                                                </span>
                                            @endif
                                        </a>
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                        {{ ucfirst($p->unit) }}
                                    </div>
                                </td>

                                <td>
                                    @if($p->items->count())
                                        <div class="flex flex-wrap gap-1 mb-1.5">
                                            @foreach($p->items as $item)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                                      style="background: {{ $item->is_custom ? 'rgba(34,197,94,.12)' : 'rgb(var(--color-primary) / .1)' }}; color: {{ $item->is_custom ? '#15803d' : 'rgb(var(--color-primary))' }};">
                                                    {{ $item->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <div class="text-slate-500 text-xs whitespace-nowrap">
                                            {{ $p->items->count() }} items
                                            <span class="text-slate-400 mx-1">•</span>
                                            {{ $p->items->sum('duration') }} Mins
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="text-center whitespace-nowrap">
                                    <span class="font-medium text-slate-800 dark:text-slate-200">
                                        Rp {{ number_format($p->price, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="w-40">
                                    <div class="flex items-center justify-center {{ $p->is_active ? 'text-success' : 'text-danger' }}">
                                        <i data-feather="{{ $p->is_active ? 'check-circle' : 'x-circle' }}" class="w-4 h-4 mr-2"></i>
                                        {{ $p->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </td>

                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{ route('admin.packages.edit', $p) }}">
                                            <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.packages.destroy', $p) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete package {{ $p->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="flex items-center text-danger">
                                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block mx-auto text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $packages->count() }}</span> package(s)
                    @if($unit !== 'all' || $filter === 'featured')
                        <span class="text-slate-400 ml-1">
                            ({{ $unit === 'all' ? 'All' : ucfirst($unit) }}{{ $filter === 'featured' ? ' • Featured' : '' }})
                        </span>
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
@extends('../layout/' . $layout)

@section('subhead')
    <title>Treatments - Icewall</title>
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

    /* ===== KATEGORI DROPDOWN ===== */
    .kategori-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 220px;
        background: #fff;
        border: 1px solid rgb(var(--color-slate-200));
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .12);
        padding: 6px;
        z-index: 9999;
        max-height: 400px;
        overflow-y: auto;
    }
    .dark .kategori-dropdown {
        background: rgb(var(--color-darkmode-600));
        border-color: rgb(var(--color-darkmode-400));
    }
    .kategori-dropdown.show { display: block; }

    .kategori-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: rgb(var(--color-slate-700));
        text-decoration: none;
        transition: all .15s;
        cursor: pointer;
    }
    .dark .kategori-item { color: rgb(var(--color-slate-300)); }
    .kategori-item:hover {
        background: rgb(var(--color-slate-100));
        color: rgb(var(--color-primary));
    }
    .dark .kategori-item:hover {
        background: rgb(var(--color-darkmode-700));
    }
    .kategori-item.active {
        background: rgb(var(--color-primary) / .1);
        color: rgb(var(--color-primary));
        font-weight: 700;
    }
    .kategori-item svg {
        opacity: 0;
        color: rgb(var(--color-primary));
        flex: none;
    }
    .kategori-item.active svg { opacity: 1; }
    .kategori-item .count {
        margin-left: auto;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgb(var(--color-slate-200));
        color: rgb(var(--color-slate-600));
    }
    .kategori-item.active .count {
        background: rgb(var(--color-primary) / .15);
        color: rgb(var(--color-primary));
    }
    .dark .kategori-item .count {
        background: rgb(var(--color-darkmode-700));
        color: rgb(var(--color-slate-400));
    }
    .kategori-divider {
        height: 1px;
        background: rgb(var(--color-slate-200));
        margin: 6px 4px;
    }
    .dark .kategori-divider {
        background: rgb(var(--color-darkmode-500));
    }
</style>

{{-- PAGE TITLE --}}
<h2 class="intro-y text-lg font-medium mt-10">Treatments</h2>

<div class="grid grid-cols-12 gap-6 mt-5">

    {{-- TOOLBAR --}}
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">

        {{-- FILTER --}}
        <div class="flex items-center gap-2">

            {{-- DROPDOWN KATEGORI --}}
            <div class="dropdown-kategori" style="position:relative;">
                <button type="button"
                        class="btn btn-primary shadow-md"
                        onclick="toggleKategoriDropdown(event)">
                    <i data-feather="list" class="w-4 h-4 mr-1"></i>
                    {{ $categoryLabel === 'Category' ? 'All Categories' : $categoryLabel }}
                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs bg-white/20">
                        {{ $category === 'uncategorized' ? $uncategorizedCount : ($category !== 'all' ? ($countsPerCategory[$category] ?? 0) : $countsAll) }}
                    </span>
                    <i data-feather="chevron-down" class="w-3 h-3 ml-1"></i>
                </button>

                <div class="kategori-dropdown" id="kategoriDropdown">
                    {{-- All --}}
                    <a href="{{ route('admin.treatments.index', array_filter(['featured' => $filter === 'featured' ? 1 : null])) }}"
                       class="kategori-item {{ $category === 'all' ? 'active' : '' }}">
                        <i data-feather="check" class="w-3.5 h-3.5"></i>
                        All
                        <span class="count">{{ $countsAll }}</span>
                    </a>

                    {{-- Kategori dinamis --}}
                    @foreach($categories as $cat)
                        @php $catCount = $countsPerCategory[$cat] ?? 0; @endphp
                        @if($catCount > 0)
                            <a href="{{ route('admin.treatments.index', array_filter(['featured' => $filter === 'featured' ? 1 : null, 'category' => $cat])) }}"
                               class="kategori-item {{ $category === $cat ? 'active' : '' }}">
                                <i data-feather="check" class="w-3.5 h-3.5"></i>
                                {{ $cat }}
                                <span class="count">{{ $catCount }}</span>
                            </a>
                        @endif
                    @endforeach

                    {{-- No Category --}}
                    @if($uncategorizedCount > 0)
                        <div class="kategori-divider"></div>
                        <a href="{{ route('admin.treatments.index', array_filter(['featured' => $filter === 'featured' ? 1 : null, 'category' => 'uncategorized'])) }}"
                           class="kategori-item {{ $category === 'uncategorized' ? 'active' : '' }}">
                            <i data-feather="check" class="w-3.5 h-3.5"></i>
                            No Category
                            <span class="count">{{ $uncategorizedCount }}</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- TOMBOL FEATURED --}}
            <a href="{{ route('admin.treatments.index', array_filter(['featured' => $filter === 'featured' ? null : 1, 'category' => $category !== 'all' ? $category : null])) }}"
               class="btn {{ $filter === 'featured' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                <i data-feather="star" class="w-4 h-4 mr-1"></i>
                Featured
                <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $filter === 'featured' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                    {{ $featuredCurrentCategory }}
                </span>
            </a>

        </div>

        {{-- TOTAL --}}
        <div class="hidden md:block text-slate-500 text-sm ml-2">
            Total: <span class="font-medium">{{ $countsAll }}</span> treatments
        </div>

        <div class="mx-auto"></div>

        {{-- ADD BUTTON --}}
        <a href="{{ route('admin.treatments.create') }}" class="btn btn-primary shadow-md">
            <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Treatment
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
    @if($treatments->isEmpty())
        <div class="intro-y col-span-12">
            <div class="box p-10 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                    <i data-feather="package" class="w-7 h-7 text-slate-400"></i>
                </div>
                <h3 class="font-medium text-base mb-1">
                    @if($category !== 'all' || $filter === 'featured')
                        No treatments match this filter
                    @else
                        No treatments yet
                    @endif
                </h3>
                <p class="text-slate-500 text-sm mb-5">
                    @if($category !== 'all' || $filter === 'featured')
                        Try changing or resetting the filter.
                    @else
                        Start by adding your first treatment.
                    @endif
                </p>
                @if($category === 'all' && $filter !== 'featured')
                    <a href="{{ route('admin.treatments.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Treatment
                    </a>
                @else
                    <a href="{{ route('admin.treatments.index') }}" class="btn btn-outline-secondary">
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
                        <th class="whitespace-nowrap">CATEGORY</th>
                        <th class="text-center whitespace-nowrap">DURATIONS</th>
                        <th class="text-center whitespace-nowrap">PRICE</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($treatments as $t)
                        <tr class="intro-x">
                            <td class="w-40">
                                @if($t->image)
                                    <div class="w-16 h-16 image-fit zoom-in">
                                        <img src="{{ asset('storage/'.$t->image) }}"
                                             alt="{{ $t->name }}"
                                             class="tooltip rounded"
                                             title="{{ $t->name }}">
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center">
                                        <i data-feather="image" class="w-5 h-5 text-slate-400"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.treatments.edit', $t) }}"
                                   class="font-medium whitespace-nowrap hover:text-primary">
                                    {{ $t->name }}
                                    @if($t->is_featured)
                                        <i data-feather="star"
                                           class="w-3.5 h-3.5 ml-1 inline-block"
                                           style="fill:#f59e0b;color:#f59e0b;vertical-align:middle;"
                                           title="Featured on Homepage"></i>
                                    @endif
                                </a>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                    {{ ucfirst($t->unit) }}
                                </div>
                            </td>

                            <td>
                                @if($t->category)
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                        {{ $t->category }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-slate-100 dark:bg-darkmode-700 text-slate-400 whitespace-nowrap">
                                        No Category
                                    </span>
                                @endif
                            </td>

                            <td class="text-center whitespace-nowrap">
                                @if($t->prices->count())
                                    <span class="font-medium">{{ $t->prices->min('duration') }}–{{ $t->prices->max('duration') }}</span>
                                    <span class="text-slate-500 text-xs"> Mins</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <td class="text-center whitespace-nowrap">
                                @if($t->prices->count())
                                    @php
                                        $minP = $t->prices->min('price');
                                        $maxP = $t->prices->max('price');
                                    @endphp
                                    @if($minP == $maxP)
                                        <span class="font-medium">Rp {{ number_format($minP, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-500 text-xs">Rp</span>
                                        <span class="font-medium">{{ number_format($minP, 0, ',', '.') }}</span>
                                        <span class="text-slate-400">–</span>
                                        <span class="font-medium">{{ number_format($maxP, 0, ',', '.') }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <td class="w-40">
                                <div class="flex items-center justify-center {{ $t->is_active ? 'text-success' : 'text-danger' }}">
                                    <i data-feather="{{ $t->is_active ? 'check-circle' : 'x-circle' }}" class="w-4 h-4 mr-2"></i>
                                    {{ $t->is_active ? 'Active' : 'Inactive' }}
                                </div>
                            </td>

                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3" href="{{ route('admin.treatments.edit', $t) }}">
                                        <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.treatments.destroy', $t) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete treatment {{ $t->name }}?')">
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
                Showing <span class="font-medium">{{ $treatments->count() }}</span> treatment(s)
                @if($category !== 'all' || $filter === 'featured')
                    <span class="text-slate-400 ml-1">
                        ({{ $categoryLabel === 'Category' ? 'All' : $categoryLabel }}{{ $filter === 'featured' ? ' • Featured' : '' }})
                    </span>
                @endif
            </div>
        </div>

    @endif

</div>

<script>
    // =============================================
    // KATEGORI DROPDOWN — toggle + close
    // =============================================
    function toggleKategoriDropdown(e) {
        e.preventDefault();
        e.stopPropagation();
        var dd = document.getElementById('kategoriDropdown');
        if (!dd) return;
        dd.classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-kategori')) {
            var dd = document.getElementById('kategoriDropdown');
            if (dd) dd.classList.remove('show');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var dd = document.getElementById('kategoriDropdown');
            if (dd) dd.classList.remove('show');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (window.feather) feather.replace();
        setTimeout(() => { if (window.feather) feather.replace(); }, 100);
        setTimeout(() => { if (window.feather) feather.replace(); }, 500);
    });
</script>
@endsection
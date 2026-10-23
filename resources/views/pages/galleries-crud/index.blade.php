@extends('../layout/' . $layout)

@section('subhead')
    <title>Galleries - Icewall</title>
@endsection

@section('subcontent')

<style>
    /* Soft alert */
    .soft-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 13px 18px; border-radius: 10px;
        font-size: 13px; font-weight: 500; line-height: 1.5;
        border: 1px solid transparent;
    }
    .soft-alert svg { width: 16px; height: 16px; flex: none; }
    .soft-alert-success { background: rgba(34, 197, 94, .12); color: #15803d; border-color: rgba(34, 197, 94, .25); }
    .soft-alert-success svg { color: #16a34a; }
    .soft-alert-danger { background: rgba(239, 68, 68, .1); color: #b91c1c; border-color: rgba(239, 68, 68, .25); }
    .soft-alert-danger svg { color: #ef4444; }

    /* ===== CARD ===== */
    .gal-card {
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .18);
        border-radius: 12px; overflow: hidden;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        position: relative; cursor: grab;
        display: flex; flex-direction: column;
    }
    .dark .gal-card {
        background: rgb(var(--color-darkmode-600));
        border-color: rgba(148, 163, 184, .12);
    }
    .gal-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15, 23, 42, .08); }
    .gal-card:active { cursor: grabbing; }
    .gal-card.selected { border-color: rgb(var(--color-primary)); box-shadow: 0 0 0 2px rgb(var(--color-primary) / .25); }
    .gal-card.sortable-ghost { opacity: .35; }
    .gal-card.sortable-drag { transform: rotate(2deg) scale(1.03); box-shadow: 0 16px 40px rgba(15, 23, 42, .2); cursor: grabbing; }

    .gal-thumb { position: relative; width: 100%; aspect-ratio: 4/3; overflow: hidden; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); }
    .gal-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; pointer-events: none; }
    .gal-card:hover .gal-thumb img { transform: scale(1.05); }

    .gal-checkbox {
        position: absolute; top: 8px; left: 8px;
        width: 22px; height: 22px; border-radius: 6px;
        background: rgba(255, 255, 255, .9);
        border: 2px solid rgba(255, 255, 255, .95);
        display: none;
        align-items: center; justify-content: center;
        cursor: pointer; z-index: 3;
        transition: all .15s ease;
        backdrop-filter: blur(6px);
        pointer-events: auto;
    }
    body.select-mode .gal-checkbox { display: flex; }
    .gal-checkbox:hover { transform: scale(1.1); }
    .gal-checkbox.checked { background: rgb(var(--color-primary)); border-color: rgb(var(--color-primary)); }
    .gal-checkbox svg { width: 13px; height: 13px; color: #fff; opacity: 0; transition: opacity .15s; }
    .gal-checkbox.checked svg { opacity: 1; }

    .gal-status-badge {
        position: absolute; top: 8px; right: 8px;
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 9.5px; font-weight: 700; padding: 3px 7px;
        border-radius: 999px; text-transform: uppercase; letter-spacing: .3px;
        backdrop-filter: blur(6px); z-index: 2;
    }
    .gal-status-badge svg { width: 9px; height: 9px; }
    .gal-status-badge.active { background: rgba(34, 197, 94, .95); color: #fff; }
    .gal-status-badge.inactive { background: rgba(239, 68, 68, .95); color: #fff; }

    .gal-body { padding: 12px 14px 14px; pointer-events: none; display: flex; flex-direction: column; gap: 8px; flex: 1; }

    .gal-title-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }
    .gal-title {
        font-size: 13.5px; font-weight: 600; color: #1e293b; margin: 0;
        line-height: 1.35;
        flex: 1;
        min-width: 0;
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .dark .gal-title { color: rgb(var(--color-slate-200)); }

    .gal-star-icon {
        flex: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #f59e0b;
        margin-top: 2px;
    }
    .gal-star-icon svg {
        width: 14px;
        height: 14px;
        fill: #f59e0b;
        stroke: #f59e0b;
    }

    .gal-meta-row { display: flex; align-items: center; flex-wrap: wrap; gap: 5px 8px; font-size: 11px; color: #94a3b8; }
    .gal-cat-badge {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 10px; font-weight: 600; padding: 2px 7px;
        border-radius: 999px; background: rgba(59, 130, 246, .1); color: #3b82f6; line-height: 1.4;
    }
    .gal-cat-badge svg { width: 9px; height: 9px; }
    .gal-cat-badge.none { background: rgba(148, 163, 184, .12); color: #94a3b8; }
    .gal-date { display: inline-flex; align-items: center; gap: 3px; }
    .gal-date svg { width: 10px; height: 10px; }

    .gal-actions {
        display: flex; align-items: center; gap: 6px;
        padding: 10px 14px; border-top: 1px solid rgba(148, 163, 184, .12);
        position: relative; z-index: 2;
    }
    .gal-actions a, .gal-actions button {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 5px; font-size: 11.5px; font-weight: 600; padding: 6px 10px;
        border-radius: 7px; background: none; border: none; cursor: pointer;
        text-decoration: none; transition: all .15s ease; flex: 1;
    }
    .gal-actions a { color: #3b82f6; background: rgba(59, 130, 246, .08); }
    .gal-actions a:hover { background: rgba(59, 130, 246, .16); }
    .gal-actions button { color: #ef4444; background: rgba(239, 68, 68, .08); }
    .gal-actions button:hover { background: rgba(239, 68, 68, .16); }
    .gal-actions svg { width: 12px; height: 12px; }

    .gal-toast {
        position: fixed; bottom: 24px; left: 50%;
        transform: translateX(-50%) translateY(10px);
        background: #1e293b; color: #fff; padding: 12px 18px;
        border-radius: 10px; font-size: 13px; font-weight: 600;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .3);
        opacity: 0;
        transition: opacity .25s ease, transform .25s ease;
        z-index: 999999; pointer-events: none;
    }
    .gal-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    .gal-toast.error { background: #ef4444; }

    .gal-fab {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: rgb(var(--color-primary));
        color: #fff;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 24px rgb(var(--color-primary) / .35);
        transition: all .25s cubic-bezier(.22, .8, .3, 1);
        z-index: 999999;
    }
    .gal-fab:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 14px 30px rgb(var(--color-primary) / .45);
    }
    .gal-fab:active { transform: translateY(-1px) scale(1); }
    .gal-fab svg { width: 22px; height: 22px; }
    .gal-fab.active {
        background: #1e293b;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .35);
    }
    .gal-fab.active:hover {
        box-shadow: 0 14px 30px rgba(15, 23, 42, .45);
    }

    .bulk-bar {
        position: fixed;
        top: 50%;
        right: 20px;
        transform: translateY(-50%) translateX(120%);
        z-index: 999999;
        background: #1e293b;
        color: #fff;
        border-radius: 16px;
        padding: 12px 8px;
        box-shadow: 0 20px 60px rgba(15, 23, 42, .35);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        opacity: 0;
        pointer-events: none;
        transition: transform .3s cubic-bezier(.22, .8, .3, 1), opacity .25s ease;
    }
    .bulk-bar.show {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
        pointer-events: auto;
    }
    .bulk-bar .count-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 30px;
        padding: 0 8px;
        border-radius: 999px;
        background: rgb(var(--color-primary));
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 4px;
        font-variant-numeric: tabular-nums;
    }
    .bulk-bar .divider {
        width: 24px;
        height: 1px;
        background: rgba(255, 255, 255, .15);
        margin: 2px 0;
    }
    .bulk-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all .15s ease;
        background: rgba(255, 255, 255, .1);
        color: #fff;
        padding: 0;
    }
    .bulk-btn:hover {
        background: rgba(255, 255, 255, .2);
        transform: scale(1.06);
    }
    .bulk-btn svg { width: 16px; height: 16px; }
    .bulk-btn.star { background: rgba(245, 158, 11, .25); color: #fbbf24; }
    .bulk-btn.star:hover { background: rgba(245, 158, 11, .4); }
    .bulk-btn.unstar { background: rgba(100, 116, 139, .3); color: #cbd5e1; }
    .bulk-btn.unstar:hover { background: rgba(100, 116, 139, .5); }
    .bulk-btn.success { background: rgba(34, 197, 94, .2); color: #86efac; }
    .bulk-btn.success:hover { background: rgba(34, 197, 94, .35); }
    .bulk-btn.warning { background: rgba(245, 158, 11, .2); color: #fcd34d; }
    .bulk-btn.warning:hover { background: rgba(245, 158, 11, .35); }
    .bulk-btn.danger { background: rgba(239, 68, 68, .2); color: #fca5a5; }
    .bulk-btn.danger:hover { background: rgba(239, 68, 68, .35); }

    .bulk-btn::before {
        content: attr(data-tip);
        position: absolute;
        right: calc(100% + 10px);
        top: 50%;
        transform: translateY(-50%) translateX(4px);
        background: #0f172a;
        color: #fff;
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 7px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity .15s ease, transform .15s ease;
        z-index: 1;
    }
    .bulk-btn::after {
        content: '';
        position: absolute;
        right: calc(100% + 4px);
        top: 50%;
        transform: translateY(-50%) translateX(4px);
        border: 5px solid transparent;
        border-left-color: #0f172a;
        opacity: 0;
        pointer-events: none;
        transition: opacity .15s ease, transform .15s ease;
        z-index: 1;
    }
    .bulk-btn:hover::before,
    .bulk-btn:hover::after {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }

    .select-all-bar {
        display: none;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: rgb(var(--color-slate-100));
        border-radius: 10px;
        font-size: 13px;
        color: rgb(var(--color-slate-600));
    }
    body.select-mode .select-all-bar { display: flex; }
    .dark .select-all-bar { background: rgb(var(--color-darkmode-700)); color: rgb(var(--color-slate-300)); }
    .select-all-bar label {
        display: flex; align-items: center; gap: 8px; cursor: pointer;
        font-weight: 600;
    }
    .select-all-bar input[type="checkbox"] {
        width: 16px; height: 16px; cursor: pointer;
        accent-color: rgb(var(--color-primary));
    }

    /* Pagination */
    .page-item.active .page-link {
        background: rgb(var(--color-primary)) !important;
        border-color: rgb(var(--color-primary)) !important;
        color: #fff !important;
    }
    /* ===== KATEGORI DROPDOWN ===== */
    .kategori-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 200px;
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

    <h2 class="intro-y text-lg font-medium mt-10">Galleries</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

{{-- TOOLBAR --}}
<div class="intro-y col-span-12">
    <div class="flex flex-wrap items-center gap-3">

        {{-- FILTER BUTTONS --}}
        <div class="flex items-center gap-2">

            {{-- TOMBOL KATEGORI (dinamis) --}}
                <div class="dropdown-kategori" style="position:relative;">
                    <button type="button"
                            class="btn btn-primary shadow-md"
                            onclick="toggleKategoriDropdown(event)">
                        <i data-feather="list" class="w-4 h-4 mr-1"></i>
                        {{ $categoryLabel }}
                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs bg-white/20">
                            {{ $category === 'uncategorized' ? $uncategorizedCount : ($category !== 'all' ? ($categories->firstWhere('slug', $category)?->galleries_count ?? 0) : $countsAll) }}
                        </span>
                        <i data-feather="chevron-down" class="w-3 h-3 ml-1"></i>
                    </button>

                    <div class="kategori-dropdown" id="kategoriDropdown">
                        {{-- All --}}
                        <a href="{{ route('admin.galleries.index', array_filter(['filter' => $filter !== 'all' ? $filter : null])) }}"
                        class="kategori-item {{ $category === 'all' ? 'active' : '' }}">
                            <i data-feather="check" class="w-3.5 h-3.5"></i>
                            All
                            <span class="count">{{ $countsAll }}</span>
                        </a>

                        {{-- Kategori dinamis — cuma yang count > 0 --}}
                        @foreach($categories as $cat)
                            @if($cat->galleries_count > 0)
                                <a href="{{ route('admin.galleries.index', array_filter(['filter' => $filter !== 'all' ? $filter : null, 'category' => $cat->slug])) }}"
                                class="kategori-item {{ $category === $cat->slug ? 'active' : '' }}">
                                    <i data-feather="check" class="w-3.5 h-3.5"></i>
                                    {{ $cat->name }}
                                    <span class="count">{{ $cat->galleries_count }}</span>
                                </a>
                            @endif
                        @endforeach

                        {{-- No Category — cuma kalo ada --}}
                        @if($uncategorizedCount > 0)
                            <div class="kategori-divider"></div>
                            <a href="{{ route('admin.galleries.index', array_filter(['filter' => $filter !== 'all' ? $filter : null, 'category' => 'uncategorized'])) }}"
                            class="kategori-item {{ $category === 'uncategorized' ? 'active' : '' }}">
                                <i data-feather="check" class="w-3.5 h-3.5"></i>
                                No Category
                                <span class="count">{{ $uncategorizedCount }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- TOMBOL FEATURED (count dinamis sesuai kategori) --}}
                <a href="{{ route('admin.galleries.index', array_filter(['filter' => $filter === 'featured' ? null : 'featured', 'category' => $category !== 'all' ? $category : null])) }}"
                class="btn {{ $filter === 'featured' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                    <i data-feather="star" class="w-4 h-4 mr-1"></i>
                    Featured
                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $filter === 'featured' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                        {{ $featuredCurrentCategory }}
                    </span>
                </a>

            </div>

            <div class="hidden md:block text-slate-500 text-sm ml-2">
                Total: <span class="font-medium">{{ $countsAll }}</span> photos
                <span class="text-slate-400 mx-2">•</span>
                <span class="text-slate-400 text-xs">Drag cards to reorder</span>
            </div>
            <div class="mx-auto"></div>
            <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary shadow-md">
                <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Photo
            </a>
        </div>
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
        @if($galleries->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="image" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No gallery photos yet</h3>
                    <p class="text-slate-500 text-sm mb-5">Start adding photos to display on the website gallery page.</p>
                    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add First Photo
                    </a>
                </div>
            </div>
        @else

            {{-- SELECT ALL BAR --}}
            <div class="intro-y col-span-12" style="margin-bottom:-6px;">
                <div class="select-all-bar" style="margin-bottom:0;">
                    <label>
                        <input type="checkbox" id="selectAllCheckbox">
                        <span>Select all</span>
                    </label>
                    <span class="text-slate-400">•</span>
                    <span class="text-xs">Click the checkbox on a card to select a photo</span>
                </div>
            </div>

            {{-- GRID --}}
            <div class="intro-y col-span-12"
                 id="galSortable"
                 data-reorder-url="{{ route('admin.galleries.reorder') }}"
                 style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;">
                @foreach($galleries as $g)
                    <div class="gal-card" data-id="{{ $g->id }}">
                        <div class="gal-thumb">
                            <img src="{{ asset('storage/'.$g->image) }}"
                                 alt="{{ $g->title ?? 'Gallery image' }}"
                                 loading="lazy">

                            <div class="gal-checkbox" data-id="{{ $g->id }}" onclick="toggleSelect(event, {{ $g->id }})">
                                <i data-feather="check"></i>
                            </div>

                            <span class="gal-status-badge {{ $g->is_active ? 'active' : 'inactive' }}">
                                <i data-feather="{{ $g->is_active ? 'check' : 'x' }}"></i>
                                {{ $g->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="gal-body">
                            <div class="gal-title-row">
                                <h3 class="gal-title">{{ $g->title ?? 'Untitled' }}</h3>
                                @if($g->is_featured)
                                    <span class="gal-star-icon" title="Featured">
                                        <i data-feather="star"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="gal-meta-row">
                                @if($g->category)
                                    <span class="gal-cat-badge">
                                        <i data-feather="tag"></i> {{ $g->category->name }}
                                    </span>
                                @else
                                    <span class="gal-cat-badge none">
                                        <i data-feather="tag"></i> No Category
                                    </span>
                                @endif
                                <span class="gal-date">
                                    <i data-feather="calendar"></i>
                                    {{ $g->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="gal-actions">
                            <a href="{{ route('admin.galleries.edit', $g) }}">
                                <i data-feather="edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.galleries.destroy', $g) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this photo?')"
                                  style="flex:1;display:flex;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width:100%;">
                                    <i data-feather="trash-2"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        {{-- FOOTER --}}
        @if($galleries->hasPages())
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block text-slate-500 text-sm">
                    Showing
                    <span class="font-medium">{{ $galleries->firstItem() }}</span>
                    –
                    <span class="font-medium">{{ $galleries->lastItem() }}</span>
                    of
                    <span class="font-medium">{{ $galleries->total() }}</span> photos
                    @if($category !== 'all' || $filter !== 'all')
                        <span class="text-slate-400 ml-1">
                            ({{ $categoryLabel }}{{ $filter === 'featured' ? ' • Featured' : '' }})
                        </span>
                    @endif
                </div>
                <div class="w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                    {{ $galleries->links() }}
                </div>
            </div>
        @else
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block mx-auto text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $galleries->total() }}</span> photo(s)
                    @if($category !== 'all' || $filter !== 'all')
                        <span class="text-slate-400 ml-1">
                            ({{ $categoryLabel }}{{ $filter === 'featured' ? ' • Featured' : '' }})
                        </span>
                    @endif
                </div>
            </div>
        @endif

        @endif

    </div>

    {{-- FAB --}}
    @if($galleries->isNotEmpty())
        <button type="button" class="gal-fab" id="galFab" title="Select / Cancel">
            <i data-feather="check-square"></i>
        </button>
    @endif

    {{-- BULK BAR --}}
    <div class="bulk-bar" id="bulkBar">
        <div class="count-pill" id="bulkCount">0</div>

        <button type="button" class="bulk-btn star" data-tip="Set Featured" onclick="bulkAction('featured')">
            <i data-feather="star"></i>
        </button>
        <button type="button" class="bulk-btn unstar" data-tip="Unset Featured" onclick="bulkAction('unfeatured')">
            <i data-feather="star"></i>
        </button>

        <div class="divider"></div>

        <button type="button" class="bulk-btn success" data-tip="Set Active" onclick="bulkAction('active')">
            <i data-feather="check"></i>
        </button>
        <button type="button" class="bulk-btn warning" data-tip="Set Inactive" onclick="bulkAction('inactive')">
            <i data-feather="x"></i>
        </button>

        <div class="divider"></div>

        <button type="button" class="bulk-btn danger" data-tip="Delete" onclick="bulkAction('delete')">
            <i data-feather="trash-2"></i>
        </button>
    </div>

    <form method="POST" action="{{ route('admin.galleries.bulk') }}" id="bulkForm" style="display:none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <div id="bulkIds"></div>
    </form>

    <div class="gal-toast" id="galToast">Order saved</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
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

    // =============================================
    // MAIN — FAB, BULK BAR, SELECT, DRAG
    // =============================================
    (function () {
        'use strict';

        // =============================================
        // MOUNT FAB & BULK BAR KE <body>
        // Biar fixed-nya beneran — ga ketutup parent/overflow
        // =============================================
        function mountFloating() {
            var fab   = document.getElementById('galFab');
            var bulk  = document.getElementById('bulkBar');
            var toast = document.getElementById('galToast');
            var form  = document.getElementById('bulkForm');

            if (fab && fab.parentNode !== document.body)   document.body.appendChild(fab);
            if (bulk && bulk.parentNode !== document.body) document.body.appendChild(bulk);
            if (toast && toast.parentNode !== document.body) document.body.appendChild(toast);
            if (form && form.parentNode !== document.body) document.body.appendChild(form);
        }

        mountFloating();
        document.addEventListener('DOMContentLoaded', mountFloating);
        setTimeout(mountFloating, 100);
        setTimeout(mountFloating, 500);

        var selectedIds = new Set();
        var selectMode  = false;

        var galFab = document.getElementById('galFab');

        function updateBulkBar() {
            var bar   = document.getElementById('bulkBar');
            var count = document.getElementById('bulkCount');

            if (selectedIds.size > 0) {
                count.textContent = selectedIds.size;
                bar.classList.add('show');
            } else {
                bar.classList.remove('show');
            }

            var allCb = document.getElementById('selectAllCheckbox');
            if (allCb) {
                var total = document.querySelectorAll('.gal-card').length;
                allCb.checked = (selectedIds.size === total && total > 0);
                allCb.indeterminate = (selectedIds.size > 0 && selectedIds.size < total);
            }
        }

        function toggleSelect(event, id) {
            event.stopPropagation();

            var cb = document.querySelector('.gal-checkbox[data-id="' + id + '"]');
            var card = cb.closest('.gal-card');

            if (selectedIds.has(id)) {
                selectedIds.delete(id);
                cb.classList.remove('checked');
                card.classList.remove('selected');
            } else {
                selectedIds.add(id);
                cb.classList.add('checked');
                card.classList.add('selected');
            }

            updateBulkBar();
        }

        function clearSelection() {
            selectedIds.clear();
            document.querySelectorAll('.gal-checkbox').forEach(function (cb) {
                cb.classList.remove('checked');
            });
            document.querySelectorAll('.gal-card').forEach(function (c) {
                c.classList.remove('selected');
            });
            updateBulkBar();
        }

        function exitSelectMode() {
            selectMode = false;
            document.body.classList.remove('select-mode');
            if (galFab) galFab.classList.remove('active');
            clearSelection();
        }

        if (galFab) {
            galFab.addEventListener('click', function () {
                selectMode = !selectMode;
                document.body.classList.toggle('select-mode', selectMode);

                if (selectMode) {
                    galFab.classList.add('active');
                } else {
                    exitSelectMode();
                }
            });
        }

        function bulkAction(action) {
            if (selectedIds.size === 0) return;

            var confirmMsg = {
                'featured':   'Set Featured for ' + selectedIds.size + ' photo(s)?',
                'unfeatured': 'Unset Featured for ' + selectedIds.size + ' photo(s)?',
                'active':     'Set Active for ' + selectedIds.size + ' photo(s)?',
                'inactive':   'Set Inactive for ' + selectedIds.size + ' photo(s)?',
                'delete':     'DELETE ' + selectedIds.size + ' photo(s)? This cannot be undone!'
            }[action];

            if (!confirm(confirmMsg)) return;

            var form = document.getElementById('bulkForm');
            document.getElementById('bulkAction').value = action;

            var idsContainer = document.getElementById('bulkIds');
            idsContainer.innerHTML = '';
            selectedIds.forEach(function (id) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                idsContainer.appendChild(input);
            });

            form.submit();
        }

        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'selectAllCheckbox') {
                var checked = e.target.checked;
                document.querySelectorAll('.gal-card').forEach(function (card) {
                    var id = parseInt(card.getAttribute('data-id'), 10);
                    var cb = card.querySelector('.gal-checkbox');

                    if (checked) {
                        selectedIds.add(id);
                        cb.classList.add('checked');
                        card.classList.add('selected');
                    } else {
                        selectedIds.delete(id);
                        cb.classList.remove('checked');
                        card.classList.remove('selected');
                    }
                });
                updateBulkBar();
            }
        });

        window.toggleSelect   = toggleSelect;
        window.clearSelection = clearSelection;
        window.bulkAction     = bulkAction;

        function featherReplace() {
            if (window.feather) window.feather.replace();
        }

        function initSortable() {
            var grid = document.getElementById('galSortable');
            if (!grid) return;
            if (typeof Sortable === 'undefined') return;

            var url   = grid.getAttribute('data-reorder-url');
            var toast = document.getElementById('galToast');

            function showToast(msg, isError) {
                if (!toast) return;
                toast.textContent = msg;
                toast.classList.toggle('error', !!isError);
                toast.classList.add('show');
                setTimeout(function () { toast.classList.remove('show'); }, 2000);
            }

            Sortable.create(grid, {
                animation: 200,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                handle: '.gal-card',
                filter: 'a, button, form, input, .gal-checkbox',
                preventOnFilter: false,
                disabled: false,
                onEnd: function () {
                    var order = Array.from(grid.querySelectorAll('.gal-card'))
                        .map(function (el) { return parseInt(el.getAttribute('data-id'), 10); });

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ order: order })
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        showToast(data.success ? 'Order saved' : 'Failed to save order', !data.success);
                    })
                    .catch(function () { showToast('Failed to save order', true); });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            mountFloating();
            featherReplace();
            setTimeout(featherReplace, 100);
            setTimeout(featherReplace, 500);

            if (typeof Sortable === 'undefined') {
                var r = 0, i = setInterval(function () {
                    r++;
                    if (typeof Sortable !== 'undefined' || r > 20) { clearInterval(i); initSortable(); }
                }, 100);
            } else {
                initSortable();
            }
        });
    })();
</script>
@endsection
@extends('../layout/' . $layout)

@section('subhead')
    <title>Blogs - Icewall</title>
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
    .soft-alert svg { width: 16px; height: 16px; flex: none; }
    .soft-alert-success { background: rgba(34, 197, 94, .12); color: #15803d; border-color: rgba(34, 197, 94, .25); }
    .soft-alert-success svg { color: #16a34a; }
    .soft-alert-danger { background: rgba(239, 68, 68, .1); color: #b91c1c; border-color: rgba(239, 68, 68, .25); }
    .soft-alert-danger svg { color: #ef4444; }

    /* Drag handle */
    .drag-handle {
        cursor: grab;
        color: #cbd5e1;
        transition: color .15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .drag-handle:hover { color: #3b82f6; }
    .drag-handle:active { cursor: grabbing; }

    /* Sortable state */
    .sortable-ghost { opacity: .35; background: #e2e8f0 !important; }
    .sortable-drag { background: #fff !important; box-shadow: 0 16px 40px rgba(15, 23, 42, .2); }

    /* Toast */
    .blg-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #1e293b;
        color: #fff;
        padding: 12px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .3);
        opacity: 0;
        transform: translateY(10px);
        transition: opacity .25s ease, transform .25s ease;
        z-index: 9999;
        pointer-events: none;
    }
    .blg-toast.show { opacity: 1; transform: translateY(0); }
    .blg-toast.error { background: #ef4444; }

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

<h2 class="intro-y text-lg font-medium mt-10">Blog Articles</h2>

<div class="grid grid-cols-12 gap-6 mt-5">

    {{-- TOOLBAR --}}
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">

        {{-- FILTER KATEGORI + STATUS --}}
        <div class="flex items-center gap-2 flex-wrap">

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
                    <a href="{{ route('admin.blogs.index', array_filter(['status' => $status !== 'all' ? $status : null])) }}"
                       class="kategori-item {{ $category === 'all' ? 'active' : '' }}">
                        <i data-feather="check" class="w-3.5 h-3.5"></i>
                        All
                        <span class="count">{{ $countsAll }}</span>
                    </a>

                    {{-- Kategori dinamis — cuma yang count > 0 --}}
                    @foreach($categories as $cat)
                        @if($cat->blogs_count > 0)
                            <a href="{{ route('admin.blogs.index', array_filter(['status' => $status !== 'all' ? $status : null, 'category' => $cat->slug])) }}"
                               class="kategori-item {{ $category === $cat->slug ? 'active' : '' }}">
                                <i data-feather="check" class="w-3.5 h-3.5"></i>
                                {{ $cat->name }}
                                <span class="count">{{ $cat->blogs_count }}</span>
                            </a>
                        @endif
                    @endforeach

                    {{-- No Category --}}
                    @if($uncategorizedCount > 0)
                        <div class="kategori-divider"></div>
                        <a href="{{ route('admin.blogs.index', array_filter(['status' => $status !== 'all' ? $status : null, 'category' => 'uncategorized'])) }}"
                           class="kategori-item {{ $category === 'uncategorized' ? 'active' : '' }}">
                            <i data-feather="check" class="w-3.5 h-3.5"></i>
                            No Category
                            <span class="count">{{ $uncategorizedCount }}</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- TOMBOL PUBLISHED --}}
            <a href="{{ route('admin.blogs.index', array_filter(['category' => $category !== 'all' ? $category : null, 'status' => $status === 'published' ? null : 'published'])) }}"
               class="btn {{ $status === 'published' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                <i data-feather="check-circle" class="w-4 h-4 mr-1"></i>
                Published
                <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $status === 'published' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                    {{ $publishedCount }}
                </span>
            </a>

            {{-- TOMBOL DRAFT --}}
            <a href="{{ route('admin.blogs.index', array_filter(['category' => $category !== 'all' ? $category : null, 'status' => $status === 'draft' ? null : 'draft'])) }}"
               class="btn {{ $status === 'draft' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                <i data-feather="edit-2" class="w-4 h-4 mr-1"></i>
                Draft
                <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $status === 'draft' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                    {{ $draftCount }}
                </span>
            </a>

        </div>

        <div class="hidden md:block text-slate-500 ml-2">
            Total: <span class="font-medium">{{ $countsAll }}</span> articles
            <span class="text-slate-400 mx-2">•</span>
            <span class="text-slate-400 text-xs">Drag row to reorder</span>
        </div>

        <div class="mx-auto"></div>

        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary shadow-md">
            <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Article
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

    {{-- EMPTY STATE --}}
    @if($blogs->isEmpty())
        <div class="intro-y col-span-12">
            <div class="box p-10 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i data-feather="file-text" class="w-7 h-7 text-slate-400"></i>
                </div>
                <h3 class="font-medium text-base mb-1">
                    @if($category !== 'all' || $status !== 'all')
                        No articles match this filter
                    @else
                        No articles yet
                    @endif
                </h3>
                <p class="text-slate-500 text-sm mb-5">
                    @if($category !== 'all' || $status !== 'all')
                        Try changing or resetting the filter.
                    @else
                        Start writing your first article to be published on the blog website.
                    @endif
                </p>
                @if($category === 'all' && $status === 'all')
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add First Article
                    </a>
                @else
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="rotate-ccw" class="w-4 h-4 mr-1"></i> Reset Filter
                    </a>
                @endif
            </div>
        </div>
    @else

        {{-- DATA LIST --}}
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap" style="width:40px;"></th>
                        <th class="whitespace-nowrap">IMAGE</th>
                        <th class="whitespace-nowrap">ARTICLE</th>
                        <th class="whitespace-nowrap">CATEGORY</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                        <th class="text-center whitespace-nowrap w-56">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="blgSortable" data-reorder-url="{{ route('admin.blogs.reorder') }}">
                    @foreach($blogs as $b)
                        <tr class="intro-x" data-id="{{ $b->id }}">

                            {{-- DRAG HANDLE --}}
                            <td class="w-10">
                                <i data-feather="menu" class="drag-handle w-4 h-4"></i>
                            </td>

                            {{-- IMAGE --}}
                            <td class="w-40">
                                @if($b->image)
                                    <div class="w-16 h-16 image-fit zoom-in">
                                        <img src="{{ asset('storage/'.$b->image) }}"
                                             alt="{{ $b->title }}"
                                             class="tooltip rounded"
                                             title="{{ $b->title }}">
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded bg-slate-100 flex items-center justify-center">
                                        <i data-feather="image" class="w-5 h-5 text-slate-400"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- TITLE --}}
                            <td>
                                <a href="{{ route('admin.blogs.edit', $b) }}"
                                   class="font-medium hover:text-primary"
                                   style="max-width:340px;display:block;line-height:1.4;">
                                    {{ $b->title }}
                                </a>
                                <div class="text-slate-500 text-xs mt-0.5"
                                     style="font-family:monospace;max-width:340px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $b->slug }}
                                </div>
                            </td>

                            {{-- CATEGORY --}}
                            <td class="whitespace-nowrap">
                                @if($b->category)
                                    <span class="inline-flex items-center"
                                          style="padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; background: rgba(59,130,246,.08); color: #3b82f6;">
                                        <i data-feather="tag" style="width:11px;height:11px;margin-right:5px;"></i>
                                        {{ $b->category->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center"
                                          style="padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; background: rgba(148,163,184,.15); color: #94a3b8;">
                                        No Category
                                    </span>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td class="text-center whitespace-nowrap">
                                @if($b->is_published)
                                    <span class="inline-flex items-center"
                                          style="padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; background: rgba(34,197,94,.1); color: #16a34a;">
                                        <i data-feather="check-circle" style="width:12px;height:12px;margin-right:5px;"></i>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center"
                                          style="padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; background: rgba(245,158,11,.1); color: #f59e0b;">
                                        <i data-feather="edit-2" style="width:12px;height:12px;margin-right:5px;"></i>
                                        Draft
                                    </span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="table-report__action">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3" href="{{ route('admin.blogs.edit', $b) }}">
                                        <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.blogs.destroy', $b) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete article {{ $b->title }}?')">
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
                Displaying <span class="font-medium">{{ $blogs->count() }}</span> article(s)
                @if($category !== 'all' || $status !== 'all')
                    <span class="text-slate-400 ml-1">
                        ({{ $categoryLabel === 'Category' ? 'All' : $categoryLabel }}{{ $status !== 'all' ? ' • ' . ucfirst($status) : '' }})
                    </span>
                @endif
            </div>
        </div>

    @endif

</div>

{{-- Toast --}}
<div class="blg-toast" id="blgToast">Order saved</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    // =============================================
    // KATEGORI DROPDOWN
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
    // SORTABLE
    // =============================================
    (function () {
        'use strict';

        function featherReplace() {
            if (window.feather) window.feather.replace();
        }

        function initSortable() {
            var tbody = document.getElementById('blgSortable');
            if (!tbody) return;
            if (typeof Sortable === 'undefined') return;

            var url   = tbody.getAttribute('data-reorder-url');
            var toast = document.getElementById('blgToast');

            function showToast(msg, isError) {
                if (!toast) return;
                toast.textContent = msg;
                toast.classList.toggle('error', !!isError);
                toast.classList.add('show');
                setTimeout(function () { toast.classList.remove('show'); }, 2000);
            }

            Sortable.create(tbody, {
                animation: 200,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                handle: '.drag-handle',
                filter: 'a, button, form, input',
                preventOnFilter: false,
                onEnd: function () {
                    var order = Array.from(tbody.querySelectorAll('tr'))
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
@extends('../layout/' . $layout)

@section('subhead')
    <title>Therapists - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* Sortable state */
        .sortable-ghost {
            opacity: .35;
            background: rgb(var(--color-slate-200)) !important;
        }

        .sortable-drag {
            background: rgb(var(--color-darkmode-600)) !important;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .2);
        }

        /* Drag handle */
        .drag-handle {
            cursor: grab;
            color: rgb(var(--color-slate-400));
            transition: color .15s ease;
        }

        .drag-handle:hover {
            color: rgb(var(--color-primary));
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        /* Toast */
        .thp-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: rgb(var(--color-slate-800));
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

        .thp-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .thp-toast.error {
            background: #ef4444;
        }
    </style>

    <h2 class="intro-y text-lg font-medium mt-10">Therapists</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- TOOLBAR + FILTER TABS --}}
        <div class="intro-y col-span-12">
            <div class="flex flex-wrap items-center gap-3">

                {{-- FILTER TABS --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.therapists.index', ['filter' => 'all']) }}"
                       class="btn {{ $filter === 'all' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                        <i data-feather="list" class="w-4 h-4 mr-1"></i>
                        All
                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $filter === 'all' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                            {{ $counts['all'] }}
                        </span>
                    </a>

                    <a href="{{ route('admin.therapists.index', ['filter' => 'featured']) }}"
                       class="btn {{ $filter === 'featured' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                        <i data-feather="star" class="w-4 h-4 mr-1"></i>
                        Featured
                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $filter === 'featured' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                            {{ $counts['featured'] }}
                        </span>
                    </a>
                </div>

                {{-- INFO DRAG --}}
                <div class="hidden md:block text-slate-500 text-sm ml-2">
                    Total: <span class="font-medium">{{ $therapists->count() }}</span> therapists
                    <span class="text-slate-400 mx-2">•</span>
                    <span class="text-slate-400 text-xs">Drag rows to reorder</span>
                </div>

                <div class="mx-auto"></div>

                <a href="{{ route('admin.therapists.create') }}" class="btn btn-primary shadow-md">
                    <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Therapist
                </a>
            </div>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="intro-y col-span-12">
                <div class="alert alert-soft-success flex items-center">
                    <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- EMPTY STATE --}}
        @if($therapists->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="users" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No therapists yet</h3>
                    <p class="text-slate-500 text-sm mb-5">Add your first therapist to display on the About page.</p>
                    <a href="{{ route('admin.therapists.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add First Therapist
                    </a>
                </div>
            </div>
        @else

            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap" style="width:40px;"></th>
                            <th class="whitespace-nowrap">PHOTO</th>
                            <th class="whitespace-nowrap">NAME</th>
                            <th class="whitespace-nowrap">ROLE</th>
                            <th class="whitespace-nowrap">BIO</th>
                            <th class="text-center whitespace-nowrap">STATUS</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="thpSortable" data-reorder-url="{{ route('admin.therapists.reorder') }}">
                        @foreach($therapists as $t)
                            <tr class="intro-x" data-id="{{ $t->id }}">
                                {{-- DRAG HANDLE --}}
                                <td class="w-10">
                                    <i data-feather="menu" class="drag-handle w-4 h-4"></i>
                                </td>

                                {{-- PHOTO --}}
                                <td class="w-40">
                                    @if($t->photo)
                                        <div class="w-16 h-16 image-fit zoom-in">
                                            <img src="{{ asset('storage/'.$t->photo) }}"
                                                 alt="{{ $t->name }}"
                                                 class="tooltip rounded"
                                                 title="{{ $t->name }}">
                                        </div>
                                    @else
                                        <div class="w-16 h-16 rounded bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center">
                                            <i data-feather="user" class="w-5 h-5 text-slate-400"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- NAME --}}
                                <td>
                                    <a href="{{ route('admin.therapists.edit', $t) }}"
                                       class="font-medium whitespace-nowrap hover:text-primary">
                                        {{ $t->name }}
                                    </a>
                                    @if($t->show_on_about)
                                        <i data-feather="star"
                                           class="w-3.5 h-3.5 inline-block ml-1.5 align-middle"
                                           style="fill: #f59e0b; stroke: #f59e0b;"
                                           title="Shown on About"></i>
                                    @endif
                                </td>

                                {{-- ROLE --}}
                                <td>
                                    @if($t->role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap"
                                              style="background: rgb(var(--color-primary) / .1); color: rgb(var(--color-primary));">
                                            {{ $t->role }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                {{-- BIO --}}
                                <td>
                                    @if($t->bio)
                                        <div class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed" style="max-width:320px;">
                                            {{ Str::limit($t->bio, 100) }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="w-40">
                                    <div class="flex items-center justify-center {{ $t->is_active ? 'text-success' : 'text-danger' }}">
                                        <i data-feather="{{ $t->is_active ? 'check-circle' : 'x-circle' }}" class="w-4 h-4 mr-2"></i>
                                        {{ $t->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </td>

                                {{-- ACTIONS --}}
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3 text-primary" href="{{ route('admin.therapists.edit', $t) }}">
                                            <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.therapists.destroy', $t) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete therapist {{ $t->name }}?')">
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

            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block mx-auto text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $therapists->count() }}</span> therapist(s)
                </div>
            </div>

        @endif

    </div>

    <div class="thp-toast" id="thpToast">Order saved</div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        (function () {
            'use strict';

            function initSortable() {
                var tbody = document.getElementById('thpSortable');
                if (!tbody) return;
                if (typeof Sortable === 'undefined') return;

                var url   = tbody.getAttribute('data-reorder-url');
                var toast = document.getElementById('thpToast');

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

            function initFeather() {
                if (window.feather) window.feather.replace();
            }

            document.addEventListener('DOMContentLoaded', function () {
                initFeather();
                setTimeout(initFeather, 100);
                setTimeout(initFeather, 500);

                if (typeof Sortable === 'undefined') {
                    var r = 0, i = setInterval(function () {
                        r++;
                        if (typeof Sortable !== 'undefined' || r > 20) {
                            clearInterval(i);
                            initSortable();
                        }
                    }, 100);
                } else {
                    initSortable();
                }
            });
        })();
    </script>
@endsection
@extends('../layout/' . $layout)

@section('subhead')
    <title>Hero Sliders - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* Card */
        .hs-card{
            background:#fff;
            border:1px solid rgb(var(--color-slate-200));
            border-radius:14px;
            overflow:hidden;
            transition:transform .2s ease, box-shadow .2s ease;
            position:relative;cursor:grab;
        }
        .dark .hs-card{background:rgb(var(--color-darkmode-600));border-color:rgb(var(--color-darkmode-400))}
        .hs-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(15,23,42,.08)}
        .hs-card:active{cursor:grabbing}

        /* Drag & Drop */
        .hs-card.sortable-ghost{opacity:.35;background:rgb(var(--color-slate-200))}
        .hs-card.sortable-drag{transform:rotate(2deg) scale(1.03);box-shadow:0 16px 40px rgba(15,23,42,.2);cursor:grabbing}

        /* Thumbnail */
        .hs-thumb{
            position:relative;width:100%;aspect-ratio:16/9;
            overflow:hidden;background:rgb(var(--color-slate-100));
        }
        .dark .hs-thumb{background:rgb(var(--color-darkmode-700))}
        .hs-thumb img{
            width:100%;height:100%;object-fit:cover;
            pointer-events:none;transition:transform .3s ease;
        }
        .hs-card:hover .hs-thumb img{transform:scale(1.05)}

        .hs-thumb-overlay{
            position:absolute;inset:0;
            background:linear-gradient(180deg, rgba(0,0,0,.15) 0%, rgba(0,0,0,.75) 100%);
            display:flex;flex-direction:column;justify-content:flex-end;
            padding:16px 18px;z-index:1;pointer-events:none;
        }

        .hs-eyebrow{
            font-size:10.5px;font-weight:700;
            letter-spacing:1.5px;text-transform:uppercase;
            color:#f76c6c;margin-bottom:6px;
        }

        .hs-title{
            font-size:15px;font-weight:800;color:#fff;
            line-height:1.3;text-transform:uppercase;
        }
        .hs-title span{color:#f76c6c;}

        /* Order badge — biru primary */
        .hs-order-badge{
            position:absolute;top:12px;left:12px;
            display:inline-flex;align-items:center;gap:5px;
            font-size:11px;font-weight:700;
            padding:5px 11px;border-radius:999px;
            background:rgb(var(--color-primary));
            color:#fff;backdrop-filter:blur(4px);
            z-index:2;box-shadow:0 2px 6px rgb(var(--color-primary) / .3);
        }
        .hs-order-badge svg{width:11px;height:11px}

        /* Status badge */
        .hs-status-badge{
            position:absolute;top:12px;right:12px;
            display:inline-flex;align-items:center;gap:5px;
            font-size:10.5px;font-weight:700;
            padding:5px 10px;border-radius:999px;
            text-transform:uppercase;letter-spacing:.3px;
            backdrop-filter:blur(4px);z-index:2;
        }
        .hs-status-badge svg{width:11px;height:11px}
        .hs-status-badge.active{background:rgba(34,197,94,.95);color:#fff;box-shadow:0 2px 6px rgba(34,197,94,.3)}
        .hs-status-badge.inactive{background:rgba(239,68,68,.95);color:#fff;box-shadow:0 2px 6px rgba(239,68,68,.3)}

        /* Actions */
        .hs-actions{
            display:flex;align-items:center;gap:10px;
            padding:14px 18px;
            border-top:1px solid rgb(var(--color-slate-200) / .8);
            position:relative;z-index:2;
        }
        .dark .hs-actions{border-top-color:rgb(var(--color-darkmode-400) / .8)}
        .hs-actions a,.hs-actions button{
            display:inline-flex;align-items:center;justify-content:center;
            gap:7px;font-size:12.5px;font-weight:600;
            padding:8px 14px;border-radius:8px;
            background:none;border:none;cursor:pointer;
            text-decoration:none;transition:all .15s ease;flex:1;
        }
        .hs-actions a{color:rgb(var(--color-primary));background:rgb(var(--color-primary) / .08)}
        .hs-actions a:hover{background:rgb(var(--color-primary) / .16)}
        .hs-actions button{color:#ef4444;background:rgba(239,68,68,.08)}
        .hs-actions button:hover{background:rgba(239,68,68,.16)}
        .hs-actions svg{width:14px;height:14px}

        /* Toast */
        .hs-toast{
            position:fixed;bottom:24px;right:24px;
            background:rgb(var(--color-slate-800));color:#fff;
            padding:12px 18px;border-radius:10px;
            font-size:13px;font-weight:600;
            box-shadow:0 10px 30px rgba(15,23,42,.3);
            opacity:0;transform:translateY(10px);
            transition:opacity .25s ease, transform .25s ease;
            z-index:9999;pointer-events:none;
        }
        .hs-toast.show{opacity:1;transform:translateY(0)}
        .hs-toast.error{background:#ef4444}
    </style>

    <h2 class="intro-y text-lg font-medium mt-10">Hero Sliders</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- TOOLBAR --}}
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <div class="hidden md:block text-slate-500">
                Total: <span class="font-medium">{{ $sliders->count() }}</span> slides
                <span class="text-slate-400 mx-2">•</span>
                <span class="text-slate-400 text-xs">Drag cards to reorder</span>
            </div>
            <div class="mx-auto"></div>
            <a href="{{ route('admin.hero-sliders.create') }}" class="btn btn-primary shadow-md">
                <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Slide
            </a>
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
        @if(session('error'))
            <div class="intro-y col-span-12">
                <div class="alert alert-soft-danger flex items-center">
                    <i data-feather="alert-circle" class="w-4 h-4 mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- EMPTY --}}
        @if($sliders->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="image" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No hero slides yet</h3>
                    <p class="text-slate-500 text-sm mb-5">Add your first slide to display in the Home hero section.</p>
                    <a href="{{ route('admin.hero-sliders.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add First Slide
                    </a>
                </div>
            </div>
        @else

            {{-- GRID --}}
            <div class="intro-y col-span-12"
                 id="hsSortable"
                 data-reorder-url="{{ route('admin.hero-sliders.reorder') }}"
                 style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
                @foreach($sliders as $s)
                    @php
                        $titleLines = $s->title
                            ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $s->title))))
                            : [];
                        $titleHtml = '';
                        if (count($titleLines) > 0) {
                            $firstLine = array_shift($titleLines);
                            $titleHtml = '<span>' . e($firstLine) . '</span>';
                            foreach ($titleLines as $line) {
                                $titleHtml .= '<br>' . e($line);
                            }
                        }
                    @endphp

                    <div class="hs-card" data-id="{{ $s->id }}">
                        <div class="hs-thumb">
                            @if($s->image)
                                <img src="{{ asset('storage/'.$s->image) }}" alt="{{ $s->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700">
                                    <i data-feather="image" class="w-8 h-8 text-slate-300"></i>
                                </div>
                            @endif

                            <div class="hs-thumb-overlay">
                                @if($s->eyebrow)
                                    <div class="hs-eyebrow">{{ $s->eyebrow }}</div>
                                @endif
                                <div class="hs-title">{!! $titleHtml !!}</div>
                            </div>

                            <div class="hs-order-badge">
                                <i data-feather="hash"></i>
                                {{ $loop->iteration }}
                            </div>

                            <div class="hs-status-badge {{ $s->is_active ? 'active' : 'inactive' }}">
                                <i data-feather="{{ $s->is_active ? 'check' : 'x' }}"></i>
                                {{ $s->is_active ? 'Active' : 'Inactive' }}
                            </div>
                        </div>

                        <div class="hs-actions">
                            <a href="{{ route('admin.hero-sliders.edit', $s) }}">
                                <i data-feather="edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.hero-sliders.destroy', $s) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this slide?')"
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

            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block mx-auto text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $sliders->count() }}</span> slide(s)
                </div>
            </div>

        @endif

    </div>

    <div class="hs-toast" id="hsToast">Order saved</div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        (function () {
            'use strict';

            function featherReplace() {
                if (window.feather) window.feather.replace();
            }

            function initSortable() {
                var grid = document.getElementById('hsSortable');
                if (!grid) return;
                if (typeof Sortable === 'undefined') return;

                var url   = grid.getAttribute('data-reorder-url');
                var toast = document.getElementById('hsToast');

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
                    handle: '.hs-card',
                    filter: 'a, button, form, input',
                    preventOnFilter: false,
                    onEnd: function () {
                        var order = Array.from(grid.querySelectorAll('.hs-card'))
                            .map(function (el) { return parseInt(el.getAttribute('data-id'), 10); });

                        grid.querySelectorAll('.hs-card').forEach(function (el, i) {
                            var badge = el.querySelector('.hs-order-badge');
                            if (badge) {
                                var svg = badge.querySelector('svg');
                                if (svg) {
                                    badge.innerHTML = '';
                                    badge.appendChild(svg);
                                    badge.appendChild(document.createTextNode(' ' + (i + 1)));
                                } else {
                                    badge.textContent = '#' + (i + 1);
                                }
                            }
                        });

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
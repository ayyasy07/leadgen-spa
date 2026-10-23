@extends('../layout/' . $layout)

@section('subhead')
    <title>Newsletter Subscribers - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* Bulk bar */
       .nl-bulk-bar {
           display: none;
           align-items: center;
           gap: 12px;
           padding: 12px 18px;
           border-radius: 10px;
           margin-top: 16px;
           font-size: 13px;
           background: rgba(245, 158, 11, .12);
           border: 1px solid rgba(245, 158, 11, .3);
           color: #b45309;
       }

       .dark .nl-bulk-bar {
           background: rgba(245, 158, 11, .15);
           color: #fbbf24;
           border-color: rgba(245, 158, 11, .35);
       }

       .nl-bulk-bar.show {
           display: flex
       }

       .nl-bulk-bar .count {
           font-weight: 700
       }

       .nl-bulk-bar button {
           margin-left: auto;
           background: #ef4444;
           color: #fff;
           border: none;
           padding: 8px 16px;
           border-radius: 8px;
           font-size: 12.5px;
           font-weight: 600;
           cursor: pointer;
           display: inline-flex;
           align-items: center;
           gap: 6px;
           transition: .2s;
       }

       .nl-bulk-bar button:hover {
           background: #dc2626
       }

       .nl-bulk-bar button svg {
           width: 13px;
           height: 13px
       }

       /* Row selected */
       .table-report tbody tr.selected {
           background: rgb(var(--color-primary) / .04);
       }
    </style>

    <h2 class="intro-y text-lg font-medium mt-10">Newsletter Subscribers</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- TOOLBAR --}}
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <div class="hidden md:block text-slate-500">
                Total: <span class="font-medium">{{ $subscribers->count() }}</span> subscribers
            </div>

            <div class="mx-auto"></div>

            <a href="{{ route('admin.newsletters.compose') }}" class="btn btn-primary shadow-md">
                <i data-feather="send" class="w-4 h-4 mr-1"></i> Compose Newsletter
            </a>
            <a href="{{ route('admin.newsletters.export') }}" class="btn btn-outline-secondary shadow-sm">
                <i data-feather="download" class="w-4 h-4 mr-1"></i> Export CSV
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
        @if(session('warning'))
            <div class="intro-y col-span-12">
                <div class="alert alert-soft-warning flex items-center">
                    <i data-feather="alert-triangle" class="w-4 h-4 mr-2"></i>
                    {{ session('warning') }}
                </div>
            </div>
        @endif

        {{-- FILTER --}}
        <div class="intro-y col-span-12">
            <form method="GET" action="{{ route('admin.newsletters.index') }}"
                  class="flex flex-wrap items-center gap-2">
                <select name="status" class="form-select box w-40">
                    <option value="all" {{ $filterStatus == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="active" {{ $filterStatus == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $filterStatus == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <div class="relative text-slate-500" style="flex:1;min-width:220px;max-width:400px;">
                    <input type="text" name="search" value="{{ $filterSearch }}"
                           class="form-control box w-full pr-10"
                           placeholder="Search email...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>

                <button type="submit" class="btn btn-primary shadow-md">
                    <i data-feather="filter" class="w-4 h-4 mr-1"></i> Filter
                </button>

                @if($filterStatus !== 'all' || $filterSearch)
                    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="x" class="w-4 h-4 mr-1"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- BULK ACTION BAR --}}
        <div class="intro-y col-span-12 nl-bulk-bar" id="bulkBar">
            <i data-feather="check-square" style="width:16px;height:16px"></i>
            <span><span class="count" id="bulkCount">0</span> subscriber(s) selected</span>
            <button type="button" id="bulkDeleteBtn">
                <i data-feather="trash-2"></i> Delete Selected
            </button>
        </div>

        {{-- EMPTY STATE --}}
        @if($subscribers->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="mail" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No subscribers yet</h3>
                    <p class="text-slate-500 text-sm mb-0">Subscribers will appear here once users subscribe from the Home page.</p>
                </div>
            </div>
        @else

            <form id="bulkDeleteForm" action="{{ route('admin.newsletters.bulk-delete') }}" method="POST" style="display:none">
                @csrf
                @method('DELETE')
                <div id="bulkDeleteInputs"></div>
            </form>

            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="text-center whitespace-nowrap" style="width:50px;">
                                <input type="checkbox" id="selectAll"
                                       class="form-check-input"
                                       style="accent-color:rgb(var(--color-primary));width:16px;height:16px;cursor:pointer;">
                            </th>
                            <th class="whitespace-nowrap">EMAIL</th>
                            <th class="whitespace-nowrap">SUBSCRIBED AT</th>
                            <th class="text-center whitespace-nowrap">STATUS</th>
                            <th class="text-center whitespace-nowrap w-56">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscribers as $s)
                            @php
                                $statusStyle = $s->is_active
                                    ? ['bg' => 'rgba(34,197,94,.12)', 'text' => '#15803d', 'icon' => 'check-circle', 'label' => 'Active']
                                    : ['bg' => 'rgba(239,68,68,.12)', 'text' => '#b91c1c', 'icon' => 'x-circle',   'label' => 'Inactive'];
                            @endphp
                            <tr class="intro-x" data-id="{{ $s->id }}">

                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input row-check"
                                           data-id="{{ $s->id }}"
                                           style="accent-color:rgb(var(--color-primary));width:16px;height:16px;cursor:pointer;">
                                </td>

                                <td>
                                    <div class="flex items-center gap-2">
                                        <i data-feather="mail" class="w-4 h-4 text-slate-400"></i>
                                        <span class="font-medium whitespace-nowrap text-slate-800 dark:text-slate-200">{{ $s->email }}</span>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap">
                                    @if($s->subscribed_at)
                                        <div class="text-slate-600 dark:text-slate-400 text-sm">
                                            {{ $s->subscribed_at->format('d M Y') }}
                                        </div>
                                        <div class="text-slate-400 text-xs mt-0.5">
                                            {{ $s->subscribed_at->format('H:i') }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="text-center whitespace-nowrap">
                                    <span class="inline-flex items-center"
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
                                        {{ $statusStyle['label'] }}
                                    </span>
                                </td>

                                <td class="table-report__action">
                                    <div class="flex justify-center items-center">
                                        <form action="{{ route('admin.newsletters.toggle', $s) }}" method="POST" class="mr-3">
                                            @csrf
                                            @method('PATCH')
                                            <button class="flex items-center text-primary hover:underline">
                                                <i data-feather="{{ $s->is_active ? 'toggle-right' : 'toggle-left' }}" class="w-4 h-4 mr-1"></i>
                                                {{ $s->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.newsletters.destroy', $s) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete subscriber {{ $s->email }}?')">
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
                    Showing <span class="font-medium">{{ $subscribers->count() }}</span> subscriber(s)
                </div>
            </div>

        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);

            const selectAll = document.getElementById('selectAll');
            const rowChecks = document.querySelectorAll('.row-check');
            const bulkBar = document.getElementById('bulkBar');
            const bulkCount = document.getElementById('bulkCount');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const bulkDeleteInputs = document.getElementById('bulkDeleteInputs');

            function updateBulkBar() {
                const selected = document.querySelectorAll('.row-check:checked');
                const count = selected.length;
                bulkCount.textContent = count;

                if (count > 0) bulkBar.classList.add('show');
                else bulkBar.classList.remove('show');

                rowChecks.forEach(cb => {
                    const tr = cb.closest('tr');
                    if (tr) tr.classList.toggle('selected', cb.checked);
                });
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rowChecks.forEach(cb => cb.checked = this.checked);
                    updateBulkBar();
                });
            }

            rowChecks.forEach(cb => {
                cb.addEventListener('change', function () {
                    const allChecked = Array.from(rowChecks).every(c => c.checked);
                    const anyChecked = Array.from(rowChecks).some(c => c.checked);
                    if (selectAll) {
                        selectAll.checked = allChecked;
                        selectAll.indeterminate = !allChecked && anyChecked;
                    }
                    updateBulkBar();
                });
            });

            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function () {
                    const selected = document.querySelectorAll('.row-check:checked');
                    const count = selected.length;
                    if (count === 0) return;
                    if (!confirm('Delete ' + count + ' selected subscriber(s)?')) return;

                    bulkDeleteInputs.innerHTML = '';
                    selected.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.dataset.id;
                        bulkDeleteInputs.appendChild(input);
                    });

                    bulkDeleteForm.submit();
                });
            }
        });
    </script>
@endsection
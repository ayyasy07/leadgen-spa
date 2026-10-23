@extends('../layout/' . $layout)

@section('subhead')
    <title>Contact Messages - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* Floating bulk bar */
        .bulk-bar {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: rgb(var(--color-slate-800));
            color: #fff;
            padding: 12px 16px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, .3);
            z-index: 9999;
            opacity: 0;
            transition: all .3s cubic-bezier(.22, .8, .3, 1);
            pointer-events: none;
        }
        .bulk-bar.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }
        .bulk-bar .bulk-count {
            font-size: 13px;
            font-weight: 700;
            background: rgba(255,255,255,.15);
            padding: 5px 12px;
            border-radius: 999px;
        }
        .bulk-bar .bulk-actions {
            display: flex;
            gap: 6px;
        }
        .bulk-bar button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .15s;
        }
        .bulk-bar button svg {
            width: 14px;
            height: 14px;
        }
        .bulk-bar .btn-bulk-primary {
            background: rgb(var(--color-primary));
            color: #fff;
        }
        .bulk-bar .btn-bulk-primary:hover {
            filter: brightness(1.15);
        }
        .bulk-bar .btn-bulk-danger {
            background: rgba(239,68,68,.9);
            color: #fff;
        }
        .bulk-bar .btn-bulk-danger:hover {
            background: #ef4444;
        }
        .bulk-bar .btn-bulk-ghost {
            background: rgba(255,255,255,.1);
            color: #fff;
        }
        .bulk-bar .btn-bulk-ghost:hover {
            background: rgba(255,255,255,.2);
        }

        /* Checkbox column */
        .col-check {
            width: 40px;
            text-align: center;
            display: none;
        }
        body.select-mode .col-check {
            display: table-cell;
        }

        .msg-check {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: rgb(var(--color-primary));
        }
        .msg-check:disabled {
            cursor: not-allowed;
            opacity: .35;
        }

        /* Tooltip sederhana */
        .msg-check:disabled:hover {
            cursor: not-allowed;
        }

        /* Row highlight kalau unread */
        tr.row-unread {
            background: rgb(var(--color-primary) / .03);
        }
    </style>

    {{-- PAGE TITLE --}}
    <h2 class="intro-y text-lg font-medium mt-10">Contact Messages</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- TOOLBAR --}}
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center gap-3">

            {{-- TAB --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.contact-messages.index', ['tab' => 'inbox']) }}"
                   class="btn {{ $tab === 'inbox' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                    <i data-feather="inbox" class="w-4 h-4 mr-1"></i>
                    Inbox
                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $tab === 'inbox' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                        {{ $counts['inbox'] }}
                    </span>
                </a>
                <a href="{{ route('admin.contact-messages.index', ['tab' => 'archived']) }}"
                   class="btn {{ $tab === 'archived' ? 'btn-primary shadow-md' : 'btn-outline-secondary' }}">
                    <i data-feather="archive" class="w-4 h-4 mr-1"></i>
                    Archived
                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-xs {{ $tab === 'archived' ? 'bg-white/20' : 'bg-slate-200 dark:bg-darkmode-600' }}">
                        {{ $counts['archived'] }}
                    </span>
                </a>
            </div>

            {{-- SEARCH --}}
            <form method="GET" action="{{ route('admin.contact-messages.index') }}"
                  class="flex items-center gap-2 ml-auto">
                <input type="hidden" name="tab" value="{{ $tab }}">

                @if($tab === 'archived')
                    <select name="status" class="form-select box w-36" onchange="this.form.submit()">
                        <option value="all"     {{ $status === 'all'     ? 'selected' : '' }}>All</option>
                        <option value="read"    {{ $status === 'read'    ? 'selected' : '' }}>Read</option>
                        <option value="replied" {{ $status === 'replied' ? 'selected' : '' }}>Replied</option>
                    </select>
                @endif

                <div class="relative" style="min-width:220px;max-width:340px;">
                    <input type="text" name="search" value="{{ $search }}"
                           class="form-control box w-full pr-10"
                           placeholder="Search name, email, or subject...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-400" data-feather="search"></i>
                </div>

                @if($search !== '')
                    <a href="{{ route('admin.contact-messages.index', ['tab' => $tab]) }}"
                       class="btn btn-outline-secondary">
                        <i data-feather="x" class="w-4 h-4"></i>
                    </a>
                @endif

                <button type="button" id="selectToggle" class="btn btn-outline-secondary">
                    <i data-feather="check-square" class="w-4 h-4 mr-1"></i>
                    <span id="selectToggleText">Select</span>
                </button>
            </form>
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
        @if($messages->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="{{ $tab === 'archived' ? 'archive' : 'inbox' }}" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">
                        {{ $tab === 'archived' ? 'No archived messages yet' : 'Inbox is empty' }}
                    </h3>
                    <p class="text-slate-500 text-sm mb-0">
                        {{ $tab === 'archived' ? 'Messages you archive will appear here.' : 'All messages have been handled. Nice!' }}
                    </p>
                </div>
            </div>
        @else

            <form id="bulkForm" method="POST" action="{{ route('admin.contact-messages.bulk') }}" class="col-span-12">
                @csrf
                <input type="hidden" name="action" id="bulkAction" value="">

                <div class="intro-y overflow-auto lg:overflow-visible">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="col-check">
                                    <input type="checkbox" id="selectAll" class="msg-check">
                                </th>
                                <th class="whitespace-nowrap">FROM</th>
                                <th class="whitespace-nowrap">SUBJECT & MESSAGE</th>
                                <th class="text-center whitespace-nowrap">RECEIVED</th>
                                <th class="text-center whitespace-nowrap">STATUS</th>
                                <th class="text-center whitespace-nowrap w-56">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $m)
                                @php
                                    $statusStyle = [
                                        'unread'  => ['bg' => 'rgb(var(--color-primary) / .12)', 'text' => 'rgb(var(--color-primary))', 'icon' => 'mail'],
                                        'read'    => ['bg' => 'rgba(245,158,11,.12)',           'text' => '#b45309', 'icon' => 'eye'],
                                        'replied' => ['bg' => 'rgba(34,197,94,.12)',            'text' => '#15803d', 'icon' => 'check-circle'],
                                    ][$m->status] ?? ['bg' => 'rgba(148,163,184,.15)', 'text' => '#64748b', 'icon' => 'mail'];

                                    $canSelect = $m->canBeArchived() || $m->isArchived();
                                @endphp
                                <tr class="intro-x {{ $m->status === 'unread' ? 'row-unread' : '' }}">

                                    <td class="col-check">
                                        <input type="checkbox"
                                               name="ids[]"
                                               value="{{ $m->id }}"
                                               class="msg-check row-check"
                                               data-can-select="{{ $canSelect ? '1' : '0' }}"
                                               {{ $canSelect ? '' : 'disabled' }}
                                               title="{{ $canSelect ? '' : 'Read it before archiving' }}">
                                    </td>

                                    <td>
                                        <div class="font-medium whitespace-nowrap">{{ $m->name }}</div>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $m->email }}</div>
                                    </td>

                                    <td>
                                        <div class="font-medium text-slate-800 dark:text-slate-200">
                                            {{ $m->subject ?: '(No Subject)' }}
                                            @if($m->isArchived())
                                                <span class="ml-1.5 text-xs text-slate-400 font-normal">• archived</span>
                                            @endif
                                        </div>
                                        <div class="text-slate-500 text-xs mt-0.5"
                                             style="max-width:420px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $m->message }}
                                        </div>
                                    </td>

                                    <td class="text-center whitespace-nowrap">
                                        <div class="text-slate-600 dark:text-slate-400 text-sm">{{ $m->created_at->format('d M Y') }}</div>
                                        <div class="text-slate-400 text-xs mt-0.5">{{ $m->created_at->format('H:i') }}</div>
                                    </td>

                                    <td class="text-center whitespace-nowrap">
                                        <span class="inline-flex items-center"
                                              style="padding:4px 10px;border-radius:999px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.3px;background:{{ $statusStyle['bg'] }};color:{{ $statusStyle['text'] }};">
                                            <i data-feather="{{ $statusStyle['icon'] }}" style="width:12px;height:12px;margin-right:5px;"></i>
                                            {{ $m->status }}
                                        </span>
                                    </td>

                                    <td class="table-report__action">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3" href="{{ route('admin.contact-messages.show', $m) }}">
                                                <i data-feather="eye" class="w-4 h-4 mr-1"></i> View
                                            </a>
                                            <form action="{{ route('admin.contact-messages.destroy', $m) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete message from {{ $m->name }}?')">
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
            </form>

            {{-- FOOTER --}}
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block mx-auto text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $messages->count() }}</span> messages
                </div>
            </div>

        @endif
    </div>

    {{-- FLOATING BULK BAR --}}
    <div class="bulk-bar" id="bulkBar">
        <span class="bulk-count" id="bulkCount">0 selected</span>
        <div class="bulk-actions">
            @if($tab === 'archived')
                <button type="button" class="btn-bulk-primary" data-action="unarchive">
                    <i data-feather="corner-up-left"></i> Unarchive
                </button>
            @else
                <button type="button" class="btn-bulk-primary" data-action="archive">
                    <i data-feather="archive"></i> Archive
                </button>
            @endif

            <button type="button" class="btn-bulk-danger" data-action="delete">
                <i data-feather="trash-2"></i> Delete
            </button>

            <button type="button" class="btn-bulk-ghost" id="bulkCancel">
                <i data-feather="x"></i> Cancel
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);

            const selectToggle     = document.getElementById('selectToggle');
            const selectToggleText = document.getElementById('selectToggleText');
            const bulkBar          = document.getElementById('bulkBar');
            const bulkCount        = document.getElementById('bulkCount');
            const selectAll        = document.getElementById('selectAll');
            const rowChecks        = () => document.querySelectorAll('.row-check:not(:disabled)');
            const bulkForm         = document.getElementById('bulkForm');
            const bulkActionInput  = document.getElementById('bulkAction');

            let selectMode = false;

            function updateBulkBar() {
                const checked = document.querySelectorAll('.row-check:checked');
                bulkCount.textContent = checked.length + ' selected';
                if (checked.length > 0) {
                    bulkBar.classList.add('show');
                } else {
                    bulkBar.classList.remove('show');
                }

                if (selectAll) {
                    const all = rowChecks();
                    const allChecked = all.length > 0 && Array.from(all).every(c => c.checked);
                    selectAll.checked = allChecked;
                    selectAll.indeterminate = checked.length > 0 && !allChecked;
                }
            }

            if (selectToggle) {
                selectToggle.addEventListener('click', function () {
                    selectMode = !selectMode;
                    document.body.classList.toggle('select-mode', selectMode);
                    selectToggleText.textContent = selectMode ? 'Cancel' : 'Select';
                    selectToggle.classList.toggle('btn-primary', selectMode);
                    selectToggle.classList.toggle('btn-outline-secondary', !selectMode);

                    if (!selectMode) {
                        document.querySelectorAll('.row-check').forEach(c => c.checked = false);
                        if (selectAll) { selectAll.checked = false; selectAll.indeterminate = false; }
                        updateBulkBar();
                    }
                });
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rowChecks().forEach(c => c.checked = this.checked);
                    updateBulkBar();
                });
            }

            document.querySelectorAll('.row-check').forEach(cb => {
                cb.addEventListener('change', updateBulkBar);
            });

            // Bulk action buttons
            document.querySelectorAll('#bulkBar [data-action]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const action = this.dataset.action;
                    const checked = document.querySelectorAll('.row-check:checked');
                    if (checked.length === 0) return;

                    let confirmMsg = '';
                    if (action === 'archive')   confirmMsg = `Archive ${checked.length} message(s)?`;
                    if (action === 'unarchive') confirmMsg = `Unarchive ${checked.length} message(s)?`;
                    if (action === 'delete')    confirmMsg = `Delete ${checked.length} message(s)? This cannot be undone!`;

                    if (!confirm(confirmMsg)) return;

                    bulkActionInput.value = action;
                    bulkForm.submit();
                });
            });

            // Cancel bulk
            const bulkCancel = document.getElementById('bulkCancel');
            if (bulkCancel) {
                bulkCancel.addEventListener('click', function () {
                    if (selectToggle) selectToggle.click();
                });
            }
        });
    </script>
@endsection
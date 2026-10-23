@extends('../layout/' . $layout)

@section('subhead')
    <title>Message Detail - Icewall</title>
@endsection

@section('subcontent')

    <style>
        .cmd-card {
            background: #fff;
            border: 1px solid rgb(var(--color-slate-200));
            border-radius: 14px;
            min-width: 0;
        }

        .dark .cmd-card {
            background: rgb(var(--color-darkmode-600));
            border-color: rgb(var(--color-darkmode-400));
        }

        .cmd-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 22px;
            border-bottom: 1px solid rgb(var(--color-slate-200));
        }

        .dark .cmd-card-head {
            border-bottom-color: rgb(var(--color-darkmode-400))
        }

        .cmd-card-head .icon-badge {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgb(var(--color-primary) / .12);
            color: rgb(var(--color-primary));
            flex: none;
        }

        .cmd-card-head .icon-badge svg {
            width: 17px;
            height: 17px
        }

        .cmd-card-body {
            padding: 22px;
            min-width: 0
        }

        .cmd-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: rgb(var(--color-slate-600));
            margin-bottom: 7px;
        }

        .dark .cmd-label {
            color: rgb(var(--color-slate-300))
        }

        .cmd-hint {
            font-weight: 400;
            color: rgb(var(--color-slate-500));
            font-size: 11.5px
        }

        .cmd-field {
            margin-bottom: 18px
        }

        .cmd-field:last-child {
            margin-bottom: 0
        }

        .cmd-card .form-control {
            border-radius: 10px !important;
            border-color: rgb(var(--color-slate-300)) !important;
            font-size: 13.5px;
            padding: 10px 14px;
            background: #fff;
            color: rgb(var(--color-slate-800));
        }

        .dark .cmd-card .form-control {
            border-color: rgb(var(--color-darkmode-400)) !important;
            background: rgb(var(--color-darkmode-700));
            color: rgb(var(--color-slate-200));
        }

        .cmd-card .form-control:focus {
            border-color: rgb(var(--color-primary)) !important;
            box-shadow: 0 0 0 3px rgb(var(--color-primary) / .12) !important;
        }

        .cmd-card textarea.form-control {
            line-height: 1.6;
            resize: vertical;
            min-height: 180px
        }

        .cmd-card .form-control:disabled {
            background: rgb(var(--color-slate-100));
            cursor: not-allowed;
            opacity: .7
        }

        .dark .cmd-card .form-control:disabled {
            background: rgb(var(--color-darkmode-700))
        }

        .page-toolbar {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            background: #fff;
            border: 1px solid rgb(var(--color-slate-200));
            border-radius: 14px;
            padding: 14px 20px;
            position: sticky;
            top: 12px;
            z-index: 30;
        }

        .dark .page-toolbar {
            background: rgb(var(--color-darkmode-600));
            border-color: rgb(var(--color-darkmode-400));
        }

        .page-toolbar .back-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgb(var(--color-slate-100));
            color: rgb(var(--color-slate-600));
            text-decoration: none;
            transition: background .15s;
        }

        .dark .page-toolbar .back-btn {
            background: rgb(var(--color-darkmode-700));
            color: rgb(var(--color-slate-300));
        }

        .page-toolbar .back-btn:hover {
            background: rgb(var(--color-slate-200))
        }

        .dark .page-toolbar .back-btn:hover {
            background: rgb(var(--color-darkmode-500))
        }

        .page-toolbar .back-btn svg {
            width: 17px;
            height: 17px
        }

        .page-toolbar .toolbar-title {
            font-size: 15.5px;
            font-weight: 600;
            color: rgb(var(--color-slate-800));
        }

        .dark .page-toolbar .toolbar-title {
            color: rgb(var(--color-slate-200))
        }

        .page-toolbar .toolbar-sub {
            font-size: 12px;
            color: rgb(var(--color-slate-500));
            margin-top: 1px;
        }

        .dark .page-toolbar .toolbar-sub {
            color: rgb(var(--color-slate-400))
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
            margin-left: auto;
            flex-wrap: wrap
        }

        .btn-primary-save {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            background: rgb(var(--color-primary));
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgb(var(--color-primary) / .3);
            transition: all .15s;
        }

        .btn-primary-save:hover:not(:disabled) {
            filter: brightness(1.1);
            transform: translateY(-1px)
        }

        .btn-primary-save:disabled {
            opacity: .5;
            cursor: not-allowed;
            box-shadow: none;
            transform: none
        }

        .btn-primary-save svg {
            width: 15px;
            height: 15px
        }

        .btn-warning {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #b45309;
            background: rgba(245, 158, 11, .12);
            border: none;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-warning:hover {
            background: rgba(245, 158, 11, .2)
        }

        .btn-warning svg {
            width: 15px;
            height: 15px
        }

        .meta-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px dashed rgb(var(--color-slate-200));
        }

        .dark .meta-row {
            border-bottom-color: rgb(var(--color-darkmode-400))
        }

        .meta-row:last-child {
            border-bottom: none
        }

        .meta-row .meta-label {
            font-size: 12px;
            font-weight: 600;
            color: rgb(var(--color-slate-500));
            min-width: 80px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .dark .meta-row .meta-label {
            color: rgb(var(--color-slate-400))
        }

        .meta-row .meta-value {
            font-size: 13.5px;
            color: rgb(var(--color-slate-800));
            font-weight: 500;
            word-break: break-all;
        }

        .dark .meta-row .meta-value {
            color: rgb(var(--color-slate-200))
        }

        .meta-row .meta-value a {
            color: rgb(var(--color-primary));
            text-decoration: none
        }

        .meta-row .meta-value a:hover {
            text-decoration: underline
        }

        .msg-bubble {
            background: rgb(var(--color-slate-100));
            border: 1px solid rgb(var(--color-slate-200));
            border-radius: 12px;
            padding: 18px 20px;
            font-size: 14px;
            color: rgb(var(--color-slate-700));
            line-height: 1.7;
            white-space: pre-line;
        }

        .dark .msg-bubble {
            background: rgb(var(--color-darkmode-700));
            border-color: rgb(var(--color-darkmode-400));
            color: rgb(var(--color-slate-300));
        }

        .msg-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .msg-status.unread {
            color: rgb(var(--color-primary));
            background: rgb(var(--color-primary) / .12)
        }

        .msg-status.read {
            color: #b45309;
            background: rgba(245, 158, 11, .12)
        }

        .msg-status.replied {
            color: #15803d;
            background: rgba(34, 197, 94, .12)
        }

        .msg-status.archived {
            color: #64748b;
            background: rgba(148, 163, 184, .15)
        }

        .msg-status svg {
            width: 12px;
            height: 12px
        }

        .reply-box {
            background: rgba(34, 197, 94, .06);
            border: 1px solid rgba(34, 197, 94, .2);
            border-radius: 12px;
            padding: 18px 20px;
            font-size: 14px;
            color: rgb(var(--color-slate-700));
            line-height: 1.7;
            white-space: pre-line;
        }

        .dark .reply-box {
            background: rgba(34, 197, 94, .1);
            color: rgb(var(--color-slate-300));
        }

        .smtp-warning {
            background: rgba(245, 158, 11, .08);
            border-left: 3px solid #f59e0b;
            color: #92400e;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 12.5px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .dark .smtp-warning {
            color: #fbbf24;
            background: rgba(245, 158, 11, .1)
        }

        .smtp-warning strong {
            color: #78350f
        }

        .dark .smtp-warning strong {
            color: #fcd34d
        }

        .smtp-warning code {
            background: rgba(245, 158, 11, .15);
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 11.5px;
        }
    </style>

    {{-- TOOLBAR --}}
    <div class="intro-y page-toolbar mt-8">
        <a href="{{ route('admin.contact-messages.index') }}" class="back-btn">
            <i data-feather="arrow-left"></i>
        </a>
        <div>
            <div class="toolbar-title">Message Detail</div>
            <div class="toolbar-sub">From {{ $message->name }} — {{ $message->email }}</div>
        </div>
        <div class="toolbar-actions">
            @if($message->isArchived())
                {{-- UNARCHIVE --}}
                <form action="{{ route('admin.contact-messages.archive', $message) }}" method="POST" style="display:inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-warning" style="color:#15803d;background:rgba(34,197,94,.12)">
                        <i data-feather="corner-up-left"></i> Unarchive
                    </button>
                </form>
            @elseif($message->canBeArchived())
                {{-- ARCHIVE --}}
                <form action="{{ route('admin.contact-messages.archive', $message) }}" method="POST" style="display:inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-warning">
                        <i data-feather="archive"></i> Archive
                    </button>
                </form>
            @else
                {{-- UNREAD — DISABLED --}}
                <button type="button" class="btn-warning" disabled
                        title="Read it before archiving"
                        style="opacity:.5;cursor:not-allowed">
                    <i data-feather="archive"></i> Archive
                </button>
            @endif
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="intro-y mt-5">
            <div class="alert alert-soft-success flex items-center">
                <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="intro-y mt-5">
            <div class="alert alert-soft-danger flex items-center">
                <i data-feather="alert-circle" class="w-4 h-4 mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- LEFT: MESSAGE + REPLY HISTORY --}}
        <div class="intro-y col-span-12 lg:col-span-7">
            <div class="cmd-card">
                <div class="cmd-card-head">
                    <div class="icon-badge"><i data-feather="mail"></i></div>
                    <h3 class="font-medium text-base mr-auto">Message</h3>
                    <span class="msg-status {{ $message->status }}">
                        <i data-feather="{{ 
                            $message->status === 'unread' ? 'mail' :
                            ($message->status === 'read' ? 'eye' :
                            ($message->status === 'replied' ? 'check-circle' : 'archive'))
                        }}"></i>
                        {{ $message->status }}
                    </span>
                </div>
                <div class="cmd-card-body">

                    <div class="cmd-field">
                        <label class="cmd-label">Subject</label>
                        <div style="font-size:14px;color:rgb(var(--color-slate-800));font-weight:600">
                            {{ $message->subject ?: '(No Subject)' }}
                        </div>
                    </div>

                    <div class="cmd-field">
                        <label class="cmd-label">Message</label>
                        <div class="msg-bubble">{{ $message->message }}</div>
                    </div>

                </div>
            </div>

            @if($message->reply)
                <div class="cmd-card mt-5">
                    <div class="cmd-card-head">
                        <div class="icon-badge" style="background:rgba(34,197,94,.12);color:#15803d">
                            <i data-feather="corner-up-left"></i>
                        </div>
                        <h3 class="font-medium text-base mr-auto">Reply History</h3>
                        <span class="cmd-hint">
                            {{ $message->replied_at ? $message->replied_at->format('d M Y, H:i') : '' }}
                        </span>
                    </div>
                    <div class="cmd-card-body">
                        <div class="reply-box">{{ $message->reply }}</div>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT: INFO + REPLY FORM --}}
        <div class="intro-y col-span-12 lg:col-span-5">

            {{-- SENDER INFO --}}
            <div class="cmd-card">
                <div class="cmd-card-head">
                    <div class="icon-badge"><i data-feather="user"></i></div>
                    <h3 class="font-medium text-base">Sender Info</h3>
                </div>
                <div class="cmd-card-body">

                    <div class="meta-row">
                        <span class="meta-label">Name</span>
                        <span class="meta-value">{{ $message->name }}</span>
                    </div>

                    <div class="meta-row">
                        <span class="meta-label">Email</span>
                        <span class="meta-value">
                            <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                        </span>
                    </div>

                    <div class="meta-row">
                        <span class="meta-label">Received</span>
                        <span class="meta-value">{{ $message->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    @if($message->read_at)
                        <div class="meta-row">
                            <span class="meta-label">Read At</span>
                            <span class="meta-value">{{ $message->read_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif

                    @if($message->replied_at)
                        <div class="meta-row">
                            <span class="meta-label">Replied</span>
                            <span class="meta-value">{{ $message->replied_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif

                </div>
            </div>

            {{-- REPLY FORM — DISABLED SAMPAI SMTP READY --}}
            <div class="cmd-card mt-5">
                <div class="cmd-card-head">
                    <div class="icon-badge" style="background:rgba(148,163,184,.15);color:#64748b">
                        <i data-feather="send"></i>
                    </div>
                    <h3 class="font-medium text-base mr-auto">Reply via Email</h3>
                    <span class="cmd-hint" style="color:#b45309;font-weight:600">
                        <i data-feather="alert-circle" style="width:11px;height:11px;display:inline;vertical-align:-1px"></i>
                        SMTP Off
                    </span>
                </div>
                <div class="cmd-card-body">

                    <div class="smtp-warning">
                        <strong>Reply feature is not active yet.</strong><br>
                        Set up SMTP first to send replies to <strong>{{ $message->email }}</strong>.
                        Once SMTP is ready, the <code>Send Reply</code> button will be enabled automatically.
                    </div>

                    <form onsubmit="return false;">
                        @csrf

                        <div class="cmd-field">
                            <label class="cmd-label">To</label>
                            <input type="text" value="{{ $message->email }}" class="form-control w-full" readonly disabled>
                        </div>

                        <div class="cmd-field">
                            <label class="cmd-label">Subject</label>
                            <input type="text" value="Re: {{ $message->subject ?: 'Your Message' }}" class="form-control w-full" readonly disabled>
                        </div>

                        <div class="cmd-field">
                            <label class="cmd-label">Reply Message</label>
                            <textarea class="form-control w-full" placeholder="Reply feature is not active yet — set up SMTP first..." disabled></textarea>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn-primary-save" disabled>
                                <i data-feather="send"></i> Send Reply
                            </button>
                        </div>
                    </form>

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
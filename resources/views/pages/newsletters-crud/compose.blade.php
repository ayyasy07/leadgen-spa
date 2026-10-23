@extends('../layout/' . $layout)

@section('subhead')
    <title>Compose Newsletter - Icewall</title>
@endsection

@section('subcontent')

    <style>
        .cp-recipient-box {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 8px
        }

        .cp-radio-card {
            flex: 1;
            min-width: 200px;
            border: 1.5px solid rgb(var(--color-slate-300));
            border-radius: 12px;
            padding: 14px 16px;
            cursor: pointer;
            transition: .2s;
            background: #fff;
        }

        .dark .cp-radio-card {
            background: rgb(var(--color-darkmode-700));
            border-color: rgb(var(--color-darkmode-400));
        }

        .cp-radio-card:hover {
            border-color: rgb(var(--color-primary));
            background: rgb(var(--color-primary) / .03);
        }

        .cp-radio-card input {
            display: none
        }

        .cp-radio-card.selected {
            border-color: rgb(var(--color-primary));
            background: rgb(var(--color-primary) / .04);
        }

        .cp-radio-inner {
            display: flex;
            align-items: flex-start;
            gap: 10px
        }

        .cp-radio-inner .dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid rgb(var(--color-slate-400));
            flex: none;
            margin-top: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cp-radio-card.selected .dot {
            border-color: rgb(var(--color-primary))
        }

        .cp-radio-card.selected .dot::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgb(var(--color-primary));
        }

        .cp-radio-inner .text strong {
            font-size: 13.5px;
            font-weight: 700;
            color: rgb(var(--color-slate-800));
            display: block;
            margin-bottom: 2px;
        }

        .dark .cp-radio-inner .text strong {
            color: rgb(var(--color-slate-200))
        }

        .cp-radio-inner .text span {
            font-size: 12px;
            color: rgb(var(--color-slate-500))
        }

        .dark .cp-radio-inner .text span {
            color: rgb(var(--color-slate-400))
        }
    </style>

    {{-- TOOLBAR --}}
    <div class="intro-y flex flex-wrap sm:flex-nowrap items-center gap-3 mt-8">
        <a href="{{ route('admin.newsletters.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-lg font-medium text-slate-800 dark:text-slate-200">Compose Newsletter</h2>
            <div class="text-slate-500 text-xs mt-0.5">Send an email to registered subscribers.</div>
        </div>
        <div class="flex items-center gap-2 sm:ml-auto mt-3 sm:mt-0">
            <a href="{{ route('admin.newsletters.index') }}" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit" form="composeForm" class="btn btn-primary shadow-md"
                    disabled
                    title="SMTP is not configured yet. The send feature will be active after SMTP setup.">
                <i data-feather="send" class="w-4 h-4 mr-1"></i> Send Newsletter
            </button>
        </div>
    </div>

    {{-- INFO ALERT --}}
    <div class="intro-y mt-5">
        <div class="alert alert-soft-primary flex items-start">
            <i data-feather="info" class="w-4 h-4 mr-2 mt-0.5"></i>
            <div>
                <strong>Send feature is not active yet.</strong>
                SMTP is not configured. The <b>Send</b> button will be active after SMTP setup.
                For now — you can fill in this form, save it as a draft, and send it later.
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <form id="composeForm" action="{{ route('admin.newsletters.send') }}" method="POST" class="mt-5">
        @csrf

        <div class="grid grid-cols-12 gap-6">

            {{-- LEFT: SUBJECT + RECIPIENT --}}
            <div class="intro-y col-span-12 lg:col-span-5">

                <div class="box p-5">
                    <div class="mb-4">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control w-full"
                               value="{{ old('subject') }}"
                               placeholder="e.g. Year-End Promo - 30% Off"
                               required>
                    </div>

                    <div>
                        <label class="form-label">
                            Recipients <span class="text-danger">*</span>
                            <span class="text-slate-500 font-normal text-xs">— choose who will receive the email</span>
                        </label>

                        <div class="cp-recipient-box">
                            <label class="cp-radio-card selected" data-value="active">
                                <input type="radio" name="recipient" value="active" checked>
                                <div class="cp-radio-inner">
                                    <div class="dot"></div>
                                    <div class="text">
                                        <strong>Active Subscribers</strong>
                                        <span>Send to active subscribers only</span>
                                    </div>
                                </div>
                            </label>

                            <label class="cp-radio-card" data-value="all">
                                <input type="radio" name="recipient" value="all">
                                <div class="cp-radio-inner">
                                    <div class="dot"></div>
                                    <div class="text">
                                        <strong>All Subscribers</strong>
                                        <span>Send to all (including inactive)</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT: BODY --}}
            <div class="intro-y col-span-12 lg:col-span-7">

                <div class="box p-5">
                    <label class="form-label">Email Body <span class="text-danger">*</span></label>
                    <textarea name="body" class="form-control w-full"
                              style="min-height:380px;line-height:1.6;resize:vertical;font-family:inherit;"
                              placeholder="Write the email body here...">{{ old('body') }}</textarea>
                </div>

            </div>

        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);

            const radioCards = document.querySelectorAll('.cp-radio-card');
            radioCards.forEach(card => {
                card.addEventListener('click', function () {
                    radioCards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    const input = this.querySelector('input');
                    if (input) input.checked = true;
                });
            });
        });
    </script>
@endsection
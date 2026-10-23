@extends('../layout/' . $layout)

@section('subhead')
    <title>Settings - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* ===== Soft alert ===== */
        .soft-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 13px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.5;
            border: 1px solid transparent;
            margin-bottom: 16px;
        }

        .soft-alert svg {
            width: 16px;
            height: 16px;
            flex: none;
            margin-top: 1px;
        }

        .soft-alert-success {
            background: rgba(34, 197, 94, .12);
            color: #15803d;
            border-color: rgba(34, 197, 94, .25);
        }

        .soft-alert-success svg {
            color: #16a34a;
        }

        .soft-alert-danger {
            background: rgba(239, 68, 68, .1);
            color: #b91c1c;
            border-color: rgba(239, 68, 68, .25);
        }

        .soft-alert-danger svg {
            color: #ef4444;
        }

        .soft-alert ul {
            list-style: disc;
            padding-left: 20px;
            margin-top: 6px;
        }

        /* ===== Color scheme swatch — compact grid ===== */
        .cs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 10px;
        }

        .cs-item {
            position: relative;
            border: 2px solid rgb(var(--color-slate-200));
            border-radius: 10px;
            padding: 10px 8px;
            cursor: pointer;
            transition: all .18s;
            background: #fff;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .dark .cs-item {
            background: #1e293b;
            border-color: rgb(var(--color-darkmode-400));
        }

        .cs-item:hover {
            border-color: rgb(var(--color-primary));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, .08);
        }

        .cs-item.active {
            border-color: rgb(var(--color-primary));
            background: rgb(var(--color-primary)/.06);
        }

        .cs-item.active::after {
            content: "";
            position: absolute;
            top: 6px;
            right: 6px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: rgb(var(--color-primary));
            box-shadow: inset 0 0 0 2px #fff;
        }

        .dark .cs-item.active::after {
            box-shadow: inset 0 0 0 2px #1e293b;
        }

        .cs-swatch {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            margin: 0 auto 6px;
            box-shadow: inset 0 -2px 4px rgba(0, 0, 0, .15);
        }

        .cs-label {
            font-size: 10.5px;
            font-weight: 600;
            color: rgb(var(--color-slate-700));
            line-height: 1.25;
        }

        .dark .cs-label {
            color: rgb(var(--color-slate-300));
        }

        .cs-item.active .cs-label {
            color: rgb(var(--color-primary));
        }

        /* ===== Flatpickr cursor ===== */
        .flatpickr-input {
            cursor: pointer;
        }
    </style>

    {{-- PAGE TITLE --}}
    <h2 class="intro-y text-lg font-medium mt-10">Settings</h2>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="intro-y mt-5">
            <div class="soft-alert soft-alert-success">
                <i data-feather="check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="intro-y mt-5">
            <div class="soft-alert soft-alert-danger">
                <i data-feather="alert-circle"></i>
                <div>
                    <strong>There are errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm" class="mt-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-12 gap-6">

            {{-- LEFT COLUMN --}}
            <div class="intro-y col-span-12 lg:col-span-7">

                {{-- APPEARANCE --}}
                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <i data-feather="palette" class="w-5 h-5 text-primary mr-3"></i>
                        <div>
                            <h2 class="font-medium text-base">Appearance</h2>
                            <div class="text-slate-500 text-xs mt-0.5">Change theme color & light/dark mode.</div>
                        </div>
                    </div>
                    <div class="p-5">

                        @php
                            $schemes = [
                                'default' => ['label' => 'Blue',    'color' => '#1e40af'],
                                'theme-1' => ['label' => 'Emerald', 'color' => '#065f46'],
                                'theme-2' => ['label' => 'Navy',    'color' => '#1e3a8a'],
                                'theme-3' => ['label' => 'Cyan',    'color' => '#155e75'],
                                'theme-4' => ['label' => 'Indigo',  'color' => '#3730a3'],
                            ];
                            $currentScheme = session('color_scheme', 'default');
                        @endphp

                        {{-- COLOR SCHEME --}}
                        <div class="mb-6">
                            <label class="form-label mb-3">Color Scheme</label>
                            <div class="cs-grid">
                                @foreach($schemes as $key => $scheme)
                                    <a href="{{ route('admin.color-scheme-switcher', ['color_scheme' => $key]) }}"
                                       class="cs-item {{ $currentScheme === $key ? 'active' : '' }}">
                                        <div class="cs-swatch" style="background: {{ $scheme['color'] }};"></div>
                                        <div class="cs-label">{{ $scheme['label'] }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- DARK MODE — pake Icewall form-check form-switch --}}
                        @php $isDark = session('dark_mode', false); @endphp
                        <div>
                            <label class="form-label mb-2">Dark Mode</label>
                            <div class="form-check form-switch">
                                <input id="dark-mode-toggle"
                                       type="checkbox"
                                       class="form-check-input"
                                       {{ $isDark ? 'checked' : '' }}>
                                <label class="form-check-label ml-3" for="dark-mode-toggle">
                                    {{ $isDark ? 'Dark mode active' : 'Light mode active' }}
                                </label>
                            </div>
                            <div class="text-slate-500 text-xs mt-2">
                                Changes are saved automatically.
                            </div>
                        </div>
                        {{-- Hidden link trigger (dipanggil via JS) --}}
                        <a href="{{ route('admin.dark-mode-switcher') }}" id="dark-mode-trigger" class="hidden"></a>

                    </div>
                </div>

                {{-- CONTACT --}}
                <div class="box mt-5">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <i data-feather="map-pin" class="w-5 h-5 text-primary mr-3"></i>
                        <h2 class="font-medium text-base">Contact Information</h2>
                    </div>
                    <div class="p-5">

                        <div class="mb-4">
                            <label class="form-label">Address</label>
                            <textarea name="contact_address" class="form-control w-full" rows="2"
                                      placeholder="e.g. Jl. Sudirman No. 1, Jakarta">{{ old('contact_address', $settings['contact_address']) }}</textarea>
                        </div>

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="contact_phone"
                                       value="{{ old('contact_phone', $settings['contact_phone']) }}"
                                       class="form-control w-full" placeholder="+62 812 3456 7890">
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="contact_email"
                                       value="{{ old('contact_email', $settings['contact_email']) }}"
                                       class="form-control w-full" placeholder="hello@leadgenspa.com">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label">
                                Map Location
                                <span class="text-slate-500 font-normal text-xs">(address or Google Maps URL)</span>
                            </label>
                            <textarea name="map_embed_url" class="form-control w-full" rows="2"
                                      placeholder="e.g. Jl. Sudirman No. 1, Jakarta">{{ old('map_embed_url', $settings['map_embed_url']) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- WORKING HOURS --}}
                <div class="box mt-5">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <i data-feather="clock" class="w-5 h-5 text-primary mr-3"></i>
                        <h2 class="font-medium text-base">Working Hours</h2>
                    </div>
                    <div class="p-5">

                        <div class="grid grid-cols-12 gap-4 mb-4">
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">Weekday Label</label>
                                <input type="text" name="weekday_label"
                                       value="{{ old('weekday_label', $settings['weekday_label']) }}"
                                       class="form-control w-full" placeholder="e.g. Mon - Fri">
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">Open</label>
                                <input type="text" name="weekday_open"
                                       value="{{ old('weekday_open', $settings['weekday_open']) }}"
                                       class="form-control w-full timepicker" placeholder="10:00">
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">Close</label>
                                <input type="text" name="weekday_close"
                                       value="{{ old('weekday_close', $settings['weekday_close']) }}"
                                       class="form-control w-full timepicker" placeholder="19:00">
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">Weekend Label</label>
                                <input type="text" name="weekend_label"
                                       value="{{ old('weekend_label', $settings['weekend_label']) }}"
                                       class="form-control w-full" placeholder="e.g. Sat - Sun">
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">Open</label>
                                <input type="text" name="weekend_open"
                                       value="{{ old('weekend_open', $settings['weekend_open']) }}"
                                       class="form-control w-full timepicker" placeholder="10:00">
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">Close</label>
                                <input type="text" name="weekend_close"
                                       value="{{ old('weekend_close', $settings['weekend_close']) }}"
                                       class="form-control w-full timepicker" placeholder="20:00">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- BOOKING SLOTS --}}
                <div class="box mt-5">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <i data-feather="users" class="w-5 h-5 text-primary mr-3"></i>
                        <h2 class="font-medium text-base">Slot & Capacity</h2>
                    </div>
                    <div class="p-5">

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">
                                    Default Capacity
                                    <span class="text-slate-500 font-normal text-xs">(guest / slot)</span>
                                </label>
                                <input type="number" name="booking_default_capacity"
                                    value="{{ old('booking_default_capacity', $settings['booking_default_capacity']) }}"
                                    class="form-control w-full" min="1" max="100" step="1">
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">
                                    Pending Timeout
                                    <span class="text-slate-500 font-normal text-xs">(minutes)</span>
                                </label>
                                <input type="number" name="booking_pending_timeout" id="pendingTimeoutInput"
                                    value="{{ old('booking_pending_timeout', $settings['booking_pending_timeout']) }}"
                                    class="form-control w-full" min="1" max="4320" step="1">
                                <div class="text-primary text-xs font-medium mt-1" id="pendingTimeoutHint"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-4 mt-4">
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">
                                    Buffer Window
                                    <span class="text-slate-500 font-normal text-xs">(minutes)</span>
                                </label>
                                <input type="number" name="booking_buffer_minutes" id="bufferMinutesInput"
                                    value="{{ old('booking_buffer_minutes', $settings['booking_buffer_minutes'] ?? '0') }}"
                                    class="form-control w-full" min="0" max="120" step="1">
                                <div class="text-primary text-xs font-medium mt-1" id="bufferMinutesHint"></div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">
                                    Max Advance
                                    <span class="text-slate-500 font-normal text-xs">(months)</span>
                                </label>
                                <input type="number" name="booking_max_advance_months" id="maxAdvanceInput"
                                    value="{{ old('booking_max_advance_months', $settings['booking_max_advance_months']) }}"
                                    class="form-control w-full" min="1" max="12" step="1">
                                <div class="text-primary text-xs font-medium mt-1" id="maxAdvanceHint"></div>
                            </div>
                        </div>

                        {{-- SLOT OVERRIDES --}}
                        <div class="mt-6">
                            <label class="form-label">
                                Slot Override Per Hour
                                <span class="text-slate-500 font-normal text-xs">(leave empty to use default capacity)</span>
                            </label>

                            <div id="slotOverrideWrapper">
                                @php
                                    $overrides = $settings['booking_slot_overrides'] ?? [];
                                @endphp
                                @if(count($overrides) === 0)
                                    <div class="text-center py-5 text-slate-400 text-xs bg-slate-100 dark:bg-darkmode-700 rounded-lg border border-dashed border-slate-300 dark:border-darkmode-400"
                                         id="slotEmptyMsg">
                                        No overrides yet. All slots use default capacity.
                                    </div>
                                @else
                                    @foreach($overrides as $time => $cap)
                                        <div class="flex gap-3 mb-2 items-center p-3 rounded-lg bg-slate-100 dark:bg-darkmode-700 border border-slate-200 dark:border-darkmode-400 slot-override-row">
                                            <select name="booking_slot_overrides[{{ $loop->index }}][time]"
                                                    class="form-control slot-time-input" style="max-width:110px;">
                                                @foreach($timeSlots as $slotTime)
                                                    <option value="{{ $slotTime }}" {{ $slotTime === $time ? 'selected' : '' }}>
                                                        {{ $slotTime }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="number"
                                                name="booking_slot_overrides[{{ $loop->index }}][capacity]"
                                                value="{{ $cap }}"
                                                class="form-control slot-cap-input"
                                                min="0" max="100" style="max-width:100px;">
                                            <span class="text-xs text-slate-500 flex-1">
                                                @if($cap == 0)
                                                    <i class="fas fa-ban"></i> Disabled
                                                @else
                                                    {{ $cap }} guest(s)
                                                @endif
                                            </span>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="removeSlotOverride(this)">
                                                ×
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 mt-3">
                                <select id="newSlotTime" class="form-control" style="max-width:130px;">
                                    <option value="">-- Select Time --</option>
                                    @foreach($timeSlots as $slotTime)
                                        <option value="{{ $slotTime }}">{{ $slotTime }}</option>
                                    @endforeach
                                </select>
                                <input type="number" id="newSlotCap"
                                    class="form-control" placeholder="4"
                                    min="0" max="100" style="max-width:110px;">
                                <button type="button" class="btn btn-outline-primary" onclick="addSlotOverride()">
                                    <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add
                                </button>
                            </div>

                            <div class="mt-3 text-xs text-slate-500 bg-slate-100 dark:bg-darkmode-700 border-l-4 border-primary px-3 py-2 rounded">
                                <strong>Example:</strong>
                                <code class="bg-primary/10 text-primary px-1.5 py-0.5 rounded text-[11px] mx-0.5">10:00</code> → <code class="bg-primary/10 text-primary px-1.5 py-0.5 rounded text-[11px]">10</code> = 10 guests,
                                <code class="bg-primary/10 text-primary px-1.5 py-0.5 rounded text-[11px] mx-0.5">18:00</code> → <code class="bg-primary/10 text-primary px-1.5 py-0.5 rounded text-[11px]">0</code> = closed
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN — SOCIAL + SAVE --}}
            <div class="intro-y col-span-12 lg:col-span-5">

                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <i data-feather="share-2" class="w-5 h-5 text-primary mr-3"></i>
                        <h2 class="font-medium text-base">Social Media Links</h2>
                    </div>
                    <div class="p-5">

                        <div class="mb-4">
                            <label class="form-label">Instagram</label>
                            <input type="url" name="social_instagram"
                                   value="{{ old('social_instagram', $settings['social_instagram']) }}"
                                   class="form-control w-full" placeholder="https://instagram.com/username">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">WhatsApp</label>
                            <input type="url" name="social_whatsapp"
                                   value="{{ old('social_whatsapp', $settings['social_whatsapp']) }}"
                                   class="form-control w-full" placeholder="https://wa.me/6281234567890">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Facebook</label>
                            <input type="url" name="social_facebook"
                                   value="{{ old('social_facebook', $settings['social_facebook']) }}"
                                   class="form-control w-full" placeholder="https://facebook.com/username">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Twitter / X</label>
                            <input type="url" name="social_twitter"
                                   value="{{ old('social_twitter', $settings['social_twitter']) }}"
                                   class="form-control w-full" placeholder="https://twitter.com/username">
                        </div>

                        <div class="mb-0">
                            <label class="form-label">LinkedIn</label>
                            <input type="url" name="social_linkedin"
                                   value="{{ old('social_linkedin', $settings['social_linkedin']) }}"
                                   class="form-control w-full" placeholder="https://linkedin.com/company/username">
                        </div>

                    </div>
                </div>

                {{-- SAVE BUTTON — sticky --}}
                <div class="box p-5 mt-5">
                    <button type="submit" class="btn btn-primary w-full">
                        <i data-feather="save" class="w-4 h-4 mr-1"></i> Save Changes
                    </button>
                </div>

            </div>

        </div>
    </form>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();

            // Flatpickr timepicker
            if (window.flatpickr) {
                flatpickr('.timepicker', {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: 'H:i',
                    time_24hr: true,
                    minuteIncrement: 30,
                    allowInput: true,
                });
            }

            // Dark mode toggle
            var dmToggle = document.getElementById('dark-mode-toggle');
            var dmTrigger = document.getElementById('dark-mode-trigger');
            if (dmToggle && dmTrigger) {
                dmToggle.addEventListener('change', function () {
                    dmTrigger.click();
                });
            }

            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);
        });

        let slotIndex = {{ count($settings['booking_slot_overrides'] ?? []) }};

        function addSlotOverride() {
            const timeSelect = document.getElementById('newSlotTime');
            const capInput   = document.getElementById('newSlotCap');
            const time       = timeSelect.value;
            const cap        = capInput.value.trim();

            if (!time) { alert('Select a time slot first.'); return; }
            if (cap === '' || parseInt(cap) < 0) { alert('Capacity must be >= 0'); return; }

            const wrapper = document.getElementById('slotOverrideWrapper');
            const emptyMsg = document.getElementById('slotEmptyMsg');
            if (emptyMsg) emptyMsg.remove();

            const capNum   = parseInt(cap);
            const capLabel = capNum === 0
                ? '<i class="fas fa-ban"></i> Disabled'
                : capNum + ' guest(s)';

            const slots = @json($timeSlots ?? []);
            let optionsHtml = '';
            slots.forEach(function (t) {
                optionsHtml += `<option value="${t}" ${t === time ? 'selected' : ''}>${t}</option>`;
            });

            const row = document.createElement('div');
            row.className = 'flex gap-3 mb-2 items-center p-3 rounded-lg bg-slate-100 dark:bg-darkmode-700 border border-slate-200 dark:border-darkmode-400 slot-override-row';
            row.innerHTML = `
                <select name="booking_slot_overrides[${slotIndex}][time]" class="form-control" style="max-width:110px;">${optionsHtml}</select>
                <input type="number" name="booking_slot_overrides[${slotIndex}][capacity]" value="${capNum}" class="form-control" min="0" max="100" style="max-width:100px;">
                <span class="text-xs text-slate-500 flex-1">${capLabel}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSlotOverride(this)">×</button>
            `;
            wrapper.appendChild(row);

            timeSelect.value = '';
            capInput.value = '';
            slotIndex++;
        }

        function removeSlotOverride(btn) {
            const row = btn.closest('.slot-override-row');
            if (!row) return;
            if (!confirm('Delete this slot override?')) return;
            row.remove();

            const wrapper = document.getElementById('slotOverrideWrapper');
            if (wrapper.children.length === 0) {
                const div = document.createElement('div');
                div.className = 'text-center py-5 text-slate-400 text-xs bg-slate-100 dark:bg-darkmode-700 rounded-lg border border-dashed border-slate-300 dark:border-darkmode-400';
                div.id = 'slotEmptyMsg';
                div.textContent = 'No overrides yet. All slots use default capacity.';
                wrapper.appendChild(div);
            }
        }

        // Live hints
        document.addEventListener('DOMContentLoaded', function () {
            const pendingInput = document.getElementById('pendingTimeoutInput');
            const pendingHint  = document.getElementById('pendingTimeoutHint');
            const bufferInput  = document.getElementById('bufferMinutesInput');
            const bufferHint   = document.getElementById('bufferMinutesHint');
            const maxInput     = document.getElementById('maxAdvanceInput');
            const maxHint      = document.getElementById('maxAdvanceHint');

            function updatePendingHint() {
                const val = parseInt(pendingInput.value) || 0;
                if (pendingHint) pendingHint.textContent = `= ${val} min`;
            }
            function updateBufferHint() {
                let val = parseInt(bufferInput.value);
                if (isNaN(val)) val = 0;
                if (val < 0) { val = 0; bufferInput.value = 0; }
                if (val > 120) { val = 120; bufferInput.value = 120; }
                bufferHint.textContent = val > 0 ? `Earliest slot = now + ${val} min` : 'No buffer — customers can book last-minute';
            }
            function updateMaxHint() {
                let val = parseInt(maxInput.value);
                if (isNaN(val)) val = 1;
                if (val < 1) { val = 1; maxInput.value = 1; }
                if (val > 12) { val = 12; maxInput.value = 12; }
                const today = new Date();
                const target = new Date(today.getFullYear(), today.getMonth() + val, today.getDate());
                const formatted = target.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                if (maxHint) maxHint.textContent = `Booking up to ${formatted}`;
            }

            if (pendingInput) { pendingInput.addEventListener('input', updatePendingHint); updatePendingHint(); }
            if (bufferInput) { bufferInput.addEventListener('input', updateBufferHint); updateBufferHint(); }
            if (maxInput) { maxInput.addEventListener('input', updateMaxHint); updateMaxHint(); }
        });
    </script>
@endsection
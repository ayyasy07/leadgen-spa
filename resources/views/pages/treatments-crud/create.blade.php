@extends('../layout/' . $layout)

@section('subhead')
    <title>Add Treatment - Icewall</title>
@endsection

@section('subcontent')

    <style>
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

        .img-dropzone {
            border: 2px dashed rgb(var(--color-slate-300));
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            transition: all .2s;
            background: rgb(var(--color-slate-50));
            cursor: pointer;
            position: relative;
            min-height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .dark .img-dropzone {
            border-color: rgb(var(--color-darkmode-400));
            background: rgb(var(--color-darkmode-700));
        }

        .img-dropzone:hover {
            border-color: rgb(var(--color-primary));
            background: rgb(var(--color-primary)/.04);
        }

        .img-dropzone img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .img-dropzone .inner {
            position: relative;
            z-index: 1;
            color: rgb(var(--color-slate-500));
            font-size: 12px;
        }

        .img-dropzone.has-file .inner {
            display: none;
        }
    </style>

    {{-- PAGE TITLE --}}
    <div class="intro-y flex items-center mt-10">
        <h2 class="text-lg font-medium mr-auto">Add Treatment</h2>
        <a href="{{ route('admin.treatments.index') }}" class="btn btn-outline-secondary">
            <i data-feather="arrow-left" class="w-4 h-4 mr-1"></i> Back
        </a>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 lg:col-span-8">

            {{-- ALERTS --}}
            @if ($errors->any())
                <div class="soft-alert soft-alert-danger">
                    <i data-feather="alert-circle"></i>
                    <div>
                        <strong>There are errors:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- FORM --}}
            <div class="box p-5">
                <form id="treatmentForm"
                      action="{{ route('admin.treatments.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- ============ BASIC INFO ============ --}}
                    <div class="mb-4">
                        <label class="form-label">Treatment Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name') }}"
                               class="form-control w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Slug
                            <span class="text-slate-500 font-normal text-xs">(auto from name, editable)</span>
                        </label>
                        <input type="text" name="slug" id="slug"
                               value="{{ old('slug') }}"
                               class="form-control w-full"
                               placeholder="auto from name">
                    </div>

                    <div class="grid grid-cols-12 gap-3 mb-4">
                        <div class="col-span-12 sm:col-span-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select w-full" required>
                                <option value="">-- Select --</option>
                                @foreach([
                                    'body-massage'=>'Body & Massage',
                                    'face-scalp'=>'Face & Scalp',
                                    'body-care'=>'Body Care',
                                    'grooming'=>'Grooming',
                                ] as $k=>$v)
                                    <option value="{{ $k }}" {{ old('category')==$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="form-label">Unit</label>
                            <select name="unit" class="form-select w-full">
                                <option value="person" {{ old('unit')=='person'?'selected':'' }}>Person</option>
                                <option value="couple" {{ old('unit')=='couple'?'selected':'' }}>Couple</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control w-full"
                                  rows="4">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-4">
                        <div class="col-span-12 sm:col-span-6">
                            <label class="form-label">Active Status</label>
                                <div class="form-check form-switch mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" class="form-check-input"
                                    id="isActiveSwitch" value="1"
                                    {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label ml-2" for="isActiveSwitch">Active</label>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="form-label">Featured (Show on Homepage)</label>
                            <div class="form-check form-switch mt-1">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" class="form-check-input"
                                    id="isFeaturedSwitch"
                                    value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label ml-2" for="isFeaturedSwitch">Featured</label>
                            </div>
                            <p class="text-slate-500 text-xs mt-1">Shown in the "Spa Treatments" section on the homepage.</p>
                        </div>
                    </div>

                    {{-- ============ IMAGES ============ --}}
                    <div class="border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5">
                        <h3 class="font-medium text-base mb-1">Treatment Images</h3>
                        <p class="text-slate-500 text-xs mb-4">
                            <strong>All three images are required.</strong> The main image is shown in the list & detail.
                        </p>

                        <div class="grid grid-cols-12 gap-4">
                            {{-- IMAGE 1 --}}
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">
                                    Main Image <span class="text-danger">*</span>
                                </label>
                                <label for="imageInput1" class="img-dropzone" id="dropzone1">
                                    <input type="file" name="image" id="imageInput1"
                                           accept="image/*" class="hidden"
                                           onchange="previewImage(event, 1)" required>
                                    <div class="inner">
                                        <i data-feather="image" class="w-7 h-7 mx-auto mb-1"></i>
                                        <p>Click to upload</p>
                                    </div>
                                </label>
                            </div>

                            {{-- IMAGE 2 --}}
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">
                                    Image 2 <span class="text-danger">*</span>
                                </label>
                                <label for="imageInput2" class="img-dropzone" id="dropzone2">
                                    <input type="file" name="image_2" id="imageInput2"
                                           accept="image/*" class="hidden"
                                           onchange="previewImage(event, 2)" required>
                                    <div class="inner">
                                        <i data-feather="image" class="w-7 h-7 mx-auto mb-1"></i>
                                        <p>Click to upload</p>
                                    </div>
                                </label>
                            </div>

                            {{-- IMAGE 3 --}}
                            <div class="col-span-12 sm:col-span-4">
                                <label class="form-label">
                                    Image 3 <span class="text-danger">*</span>
                                </label>
                                <label for="imageInput3" class="img-dropzone" id="dropzone3">
                                    <input type="file" name="image_3" id="imageInput3"
                                           accept="image/*" class="hidden"
                                           onchange="previewImage(event, 3)" required>
                                    <div class="inner">
                                        <i data-feather="image" class="w-7 h-7 mx-auto mb-1"></i>
                                        <p>Click to upload</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ============ SEO ============ --}}
                    <div class="border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5">
                        <h3 class="font-medium text-base mb-1">SEO Meta</h3>
                        <p class="text-slate-500 text-xs mb-4">Optional. Auto-filled from name if empty.</p>

                        <div class="mb-4">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title"
                                   value="{{ old('meta_title') }}"
                                   class="form-control w-full"
                                   maxlength="255">
                        </div>

                        <div class="mb-0">
                            <label class="form-label">
                                Meta Description
                                <span class="text-slate-500 font-normal text-xs">(max 500)</span>
                            </label>
                            <textarea name="meta_description" class="form-control w-full"
                                      rows="3" maxlength="500">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>

                    {{-- ============ DURATIONS & PRICES ============ --}}
                    <div class="border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5">
                        <h3 class="font-medium text-base mb-1">Durations & Prices</h3>
                        <p class="text-slate-500 text-xs mb-4">Set the duration (minutes) & price for this treatment.</p>

                        <div id="price-wrapper">
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 mb-2 price-row">
                                <div class="input-group flex-1">
                                    <input type="number" name="durations[]" class="form-control" placeholder="60" required>
                                    <div class="input-group-text">Mins</div>
                                </div>
                                <div class="input-group flex-1">
                                    <div class="input-group-text">Rp</div>
                                    <input type="number" name="prices[]" class="form-control" placeholder="150000" required>
                                </div>
                                <button type="button" class="btn btn-outline-danger w-10 remove-row">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <button type="button" id="add-row" class="btn btn-outline-secondary mt-2">
                            <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Duration
                        </button>
                    </div>

                </form>
            </div>

        </div>

        {{-- RIGHT: ACTION CARD --}}
        <div class="intro-y col-span-12 lg:col-span-4">
            <div class="box p-5 sticky top-12">
                <h3 class="font-medium text-base mb-3">Actions</h3>
                <p class="text-slate-500 text-xs mb-4">Make sure all data is correct before saving.</p>

                <div class="flex flex-col gap-2">
                    <button type="submit" form="treatmentForm" class="btn btn-primary w-full" id="btnSave">
                        <i data-feather="save" class="w-4 h-4 mr-1"></i> Save Treatment
                    </button>
                    <a href="{{ route('admin.treatments.index') }}" class="btn btn-outline-secondary w-full">
                        <i data-feather="x" class="w-4 h-4 mr-1"></i> Cancel
                    </a>
                </div>

                <div class="border-t border-slate-200/60 dark:border-darkmode-400 mt-5 pt-4">
                    <div class="text-slate-500 text-xs space-y-2">
                        <p><strong class="text-slate-700 dark:text-slate-300">Tips:</strong></p>
                        <ul class="list-disc pl-4 space-y-1">
                            <li>Slug auto-generates from name</li>
                            <li>Main image is shown in list & detail</li>
                            <li>Featured = shown on homepage</li>
                            <li>You can add multiple durations + prices</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // ===== IMAGE PREVIEW =====
        function previewImage(event, idx) {
            const input = event.target;
            const dropzone = document.getElementById('dropzone' + idx);
            if (!input.files || !input.files[0]) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                // Hapus img lama kalo ada
                const old = dropzone.querySelector('img');
                if (old) old.remove();

                const img = document.createElement('img');
                img.src = e.target.result;
                dropzone.appendChild(img);
                dropzone.classList.add('has-file');
            };
            reader.readAsDataURL(input.files[0]);
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();

            // ===== SLUG AUTO =====
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            let slugManual = {{ old('slug') ? 'true' : 'false' }};

            slugInput.addEventListener('input', function () { slugManual = true; });

            nameInput.addEventListener('input', function () {
                if (slugManual) return;
                slugInput.value = nameInput.value
                    .toLowerCase().trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            });

            // ===== PRICE ROWS =====
            const wrapper = document.getElementById('price-wrapper');
            const addBtn = document.getElementById('add-row');

            addBtn.addEventListener('click', function () {
                const row = document.createElement('div');
                row.className = 'flex flex-wrap sm:flex-nowrap items-center gap-2 mb-2 price-row';
                row.innerHTML = `
                    <div class="input-group flex-1">
                        <input type="number" name="durations[]" class="form-control" placeholder="60" required>
                        <div class="input-group-text">Mins</div>
                    </div>
                    <div class="input-group flex-1">
                        <div class="input-group-text">Rp</div>
                        <input type="number" name="prices[]" class="form-control" placeholder="150000" required>
                    </div>
                    <button type="button" class="btn btn-outline-danger w-10 remove-row">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                `;
                wrapper.appendChild(row);
                if (window.feather) feather.replace();
            });

            wrapper.addEventListener('click', function (e) {
                const btn = e.target.closest('.remove-row');
                if (!btn) return;
                if (wrapper.querySelectorAll('.price-row').length > 1) {
                    btn.closest('.price-row').remove();
                }
            });

            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
        });
    </script>
@endsection
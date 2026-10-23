@extends('../layout/' . $layout)

@section('subhead')
    <title>Add Hero Slide - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* Dropzone */
        .dropzone {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed rgb(var(--color-slate-300));
            border-radius: 12px;
            padding: 24px 16px;
            cursor: pointer;
            transition: all .2s ease;
            background: rgb(var(--color-slate-50));
            text-align: center;
            position: relative;
            overflow: hidden;
            aspect-ratio: 16/9;
            width: 100%;
        }

        .dark .dropzone {
            background: rgb(var(--color-darkmode-700));
            border-color: rgb(var(--color-darkmode-400))
        }

        .dropzone:hover {
            border-color: rgb(var(--color-primary));
            background: rgb(var(--color-primary) / .03)
        }

        .dropzone.has-file {
            border-style: solid;
            border-color: rgb(var(--color-primary) / .35);
            padding: 0
        }

        .dropzone-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            background: rgb(var(--color-primary) / .1);
            color: rgb(var(--color-primary));
        }

        .dropzone-icon svg {
            width: 24px;
            height: 24px
        }

        .dropzone-title {
            font-size: 13px;
            font-weight: 600;
            color: rgb(var(--color-slate-700));
            margin-bottom: 3px;
        }

        .dark .dropzone-title {
            color: rgb(var(--color-slate-300))
        }

        .dropzone-hint {
            font-size: 11.5px;
            color: rgb(var(--color-slate-500))
        }

        .dark .dropzone-hint {
            color: rgb(var(--color-slate-400))
        }

        .dropzone img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .hidden-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden
        }

        /* Preview */
        .preview-box {
            border: 1px solid rgb(var(--color-slate-200));
            border-radius: 12px;
            padding: 14px;
            background: rgb(var(--color-slate-50));
            margin-top: 14px;
        }

        .dark .preview-box {
            background: rgb(var(--color-darkmode-700));
            border-color: rgb(var(--color-darkmode-400))
        }

        .preview-box .label {
            font-size: 11px;
            font-weight: 600;
            color: rgb(var(--color-slate-500));
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 10px;
            display: block;
        }

        .dark .preview-box .label {
            color: rgb(var(--color-slate-400))
        }

        .preview-box .preview-content {
            position: relative;
            aspect-ratio: 16/9;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
        }

        .preview-box .preview-content img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .55;
        }

        .preview-box .preview-content::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            z-index: 1;
        }

        .preview-box .preview-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 6% 8%;
            z-index: 2;
            text-align: left;
        }

        .preview-box .prev-eyebrow {
            font-size: clamp(8px, 1vw, 11px);
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #ccc;
            margin-bottom: clamp(4px, 1vw, 12px);
        }

        .preview-box .prev-title {
            font-size: clamp(18px, 4.5vw, 42px);
            font-weight: 800;
            color: #fff;
            line-height: 1.05;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }

        .preview-box .prev-title .highlight {
            color: #f76c6c
        }
    </style>

    {{-- TOOLBAR --}}
    <div class="intro-y flex flex-wrap sm:flex-nowrap items-center gap-3 mt-8">
        <a href="{{ route('admin.hero-sliders.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-lg font-medium text-slate-800 dark:text-slate-200">Add Hero Slide</h2>
            <div class="text-slate-500 text-xs mt-0.5">Add a new slide for the Home hero section.</div>
        </div>
        <div class="flex items-center gap-2 sm:ml-auto mt-3 sm:mt-0">
            <a href="{{ route('admin.hero-sliders.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" form="sliderForm" class="btn btn-primary shadow-md">
                <i data-feather="check" class="w-4 h-4 mr-1"></i> Save Slide
            </button>
        </div>
    </div>

    <form action="{{ route('admin.hero-sliders.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="sliderForm"
          class="mt-5">

        @csrf

        <div class="grid grid-cols-12 gap-6">

            {{-- LEFT: DETAILS --}}
            <div class="intro-y col-span-12 lg:col-span-7">
                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="edit-3" class="w-5 h-5"></i>
                        </div>
                        <h2 class="font-medium text-base">Slide Details</h2>
                    </div>
                    <div class="p-5">

                        @if ($errors->any())
                            <div class="alert alert-soft-danger flex items-start mb-4">
                                <i data-feather="alert-circle" class="w-4 h-4 mr-2 mt-0.5"></i>
                                <ul class="list-disc pl-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label">
                                Eyebrow / Top Tagline
                                <span class="text-slate-500 font-normal text-xs">(optional)</span>
                            </label>
                            <input type="text" name="eyebrow" value="{{ old('eyebrow') }}"
                                   class="form-control w-full"
                                   placeholder="e.g. ENJOY THE SOOTHING EXPERIENCE">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <textarea name="title" class="form-control w-full" rows="4" maxlength="500"
                                      placeholder="SPA SALON&#10;RELAX AND&#10;REFRESH" required>{{ old('title') }}</textarea>
                            <div class="text-slate-500 text-xs mt-1">
                                Write multi-line. The first line is automatically highlighted in coral.
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                       class="form-control w-full" min="0">
                                <div class="text-slate-500 text-xs mt-1">Smaller numbers appear first.</div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-1">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" class="form-check-input"
                                           id="isActiveSwitch" value="1" checked>
                                    <label class="form-check-label ml-2" for="isActiveSwitch">
                                        Active (shown on slider)
                                    </label>
                                </div>
                                <div class="text-slate-500 text-xs mt-1">Non-active slides are saved as drafts.</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT: IMAGE + PREVIEW --}}
            <div class="intro-y col-span-12 lg:col-span-5">
                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="image" class="w-5 h-5"></i>
                        </div>
                        <h2 class="font-medium text-base">Slide Image</h2>
                    </div>
                    <div class="p-5">

                        <label for="imageInput" class="dropzone" id="dropzoneLabel">
                            <input type="file" name="image" id="imageInput"
                                   accept="image/*" class="hidden-file-input" required>

                            <div id="dropzonePlaceholder"
                                 class="flex flex-col items-center justify-center w-full">
                                <div class="dropzone-icon"><i data-feather="upload-cloud"></i></div>
                                <div class="dropzone-title">Upload slide image</div>
                                <div class="dropzone-hint">PNG, JPG, WEBP. Max 4MB.</div>
                            </div>
                        </label>

                        <div class="mt-3">
                            <button type="button" class="btn btn-primary w-full" id="btnPickPhoto">
                                <i data-feather="image" class="w-4 h-4 mr-1"></i> Choose Image
                            </button>
                        </div>

                        {{-- LIVE PREVIEW --}}
                        <div class="preview-box">
                            <span class="label">Live Preview</span>
                            <div class="preview-content">
                                <img id="previewImg" src="" alt="" style="display:none">
                                <div class="preview-overlay">
                                    <div class="prev-eyebrow" id="prevEyebrow"></div>
                                    <div class="prev-title" id="prevTitle"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();

            const photoInput    = document.getElementById('imageInput');
            const dropzoneLabel = document.getElementById('dropzoneLabel');
            const placeholder   = document.getElementById('dropzonePlaceholder');
            const btnPick       = document.getElementById('btnPickPhoto');

            const eyebrowInput  = document.querySelector('input[name="eyebrow"]');
            const titleInput    = document.querySelector('textarea[name="title"]');
            const previewImg    = document.getElementById('previewImg');
            const prevEyebrow   = document.getElementById('prevEyebrow');
            const prevTitle     = document.getElementById('prevTitle');

            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function renderPreviewTitle(text) {
                if (!text) { prevTitle.innerHTML = ''; return; }
                const lines = text.split('\n');
                let html = '';
                lines.forEach(function (line, idx) {
                    const cls = (idx === 0) ? 'highlight' : '';
                    html += `<span class="${cls}">${escapeHtml(line)}</span>`;
                    if (idx < lines.length - 1) html += '<br>';
                });
                prevTitle.innerHTML = html;
            }

            function updatePreview() {
                prevEyebrow.textContent = eyebrowInput.value || '';
                renderPreviewTitle(titleInput.value || '');
            }

            eyebrowInput.addEventListener('input', updatePreview);
            titleInput.addEventListener('input', updatePreview);
            updatePreview();

            if (btnPick) {
                btnPick.addEventListener('click', function () {
                    photoInput.click();
                });
            }

            photoInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (e) {
                    dropzoneLabel.classList.add('has-file');
                    placeholder.style.display = 'none';
                    const existingImg = dropzoneLabel.querySelector('img');
                    if (existingImg) existingImg.remove();
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'preview';
                    dropzoneLabel.appendChild(img);

                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });

            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);
        });
    </script>
@endsection
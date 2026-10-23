@extends('../layout/' . $layout)

@section('subhead')
    <title>Add Therapist - Icewall</title>
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
            aspect-ratio: 3/4;
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
            margin-bottom: 3px
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
    </style>

    {{-- TOOLBAR --}}
    <div class="intro-y flex flex-wrap sm:flex-nowrap items-center gap-3 mt-8">
        <a href="{{ route('admin.therapists.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-lg font-medium text-slate-800 dark:text-slate-200">Add Therapist</h2>
            <div class="text-slate-500 text-xs mt-0.5">Add a new therapist to display on the About page.</div>
        </div>
        <div class="flex items-center gap-2 sm:ml-auto mt-3 sm:mt-0">
            <a href="{{ route('admin.therapists.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" form="therapistForm" class="btn btn-primary shadow-md">
                <i data-feather="check" class="w-4 h-4 mr-1"></i> Save Therapist
            </button>
        </div>
    </div>

    <form action="{{ route('admin.therapists.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="therapistForm"
          class="mt-5">

        @csrf

        <div class="grid grid-cols-12 gap-6">

            {{-- LEFT: DETAILS --}}
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="edit-3" class="w-5 h-5"></i>
                        </div>
                        <h2 class="font-medium text-base">Therapist Details</h2>
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
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="thpName" value="{{ old('name') }}"
                                   class="form-control w-full"
                                   placeholder="e.g. Sarah Jenkins" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Slug <span class="text-slate-500 font-normal text-xs">(auto from name, editable)</span></label>
                            <input type="text" name="slug" id="thpSlug" value="{{ old('slug') }}"
                                   class="form-control w-full"
                                   placeholder="auto from name">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Role / Position <span class="text-danger">*</span></label>
                            <input type="text" name="role" value="{{ old('role') }}"
                                class="form-control w-full"
                                placeholder="e.g. Lead Aesthetician" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Bio <span class="text-slate-500 font-normal text-xs">(optional)</span></label>
                            <textarea name="bio" class="form-control w-full" rows="4" maxlength="1000"
                                      placeholder="Short description about the therapist...">{{ old('bio') }}</textarea>
                        </div>

                                                <div class="mb-4">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" class="form-check-input"
                                       id="isActiveSwitch" value="1" checked>
                                <label class="form-check-label ml-2" for="isActiveSwitch">
                                    Active
                                </label>
                            </div>
                            <div class="text-slate-500 text-xs mt-2">
                                If turned off, the therapist is <strong>completely hidden</strong> from the website (About, etc).
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Show on About</label>
                            <div class="form-check form-switch mt-1">
                                <input type="hidden" name="show_on_about" value="0">
                                <input type="checkbox" name="show_on_about" class="form-check-input"
                                       id="showOnAboutSwitch" value="1" checked>
                                <label class="form-check-label ml-2" for="showOnAboutSwitch">
                                    Show on About page
                                </label>
                            </div>
                            <div class="text-slate-500 text-xs mt-2">
                                If turned off, the therapist stays active but <strong>won't appear on About</strong>. Good for interns / new therapists.
                            </div>
                        </div>

                    </div>
                </div>

                {{-- SEO CARD --}}
                <div class="box mt-5">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="search" class="w-5 h-5"></i>
                        </div>
                        <h2 class="font-medium text-base">SEO</h2>
                    </div>
                    <div class="p-5">
                        <div class="mb-4">
                            <label class="form-label">
                                Meta Title
                                <span class="text-slate-500 font-normal text-xs">(empty = auto from name)</span>
                            </label>
                            <input type="text" name="meta_title"
                                   class="form-control w-full"
                                   value="{{ old('meta_title') }}"
                                   maxlength="255">
                        </div>
                        <div>
                            <label class="form-label">Meta Description <span class="text-slate-500 font-normal text-xs">(max 500)</span></label>
                            <textarea name="meta_description"
                                      class="form-control w-full"
                                      rows="3"
                                      maxlength="500">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: PHOTO --}}
            <div class="intro-y col-span-12 lg:col-span-4">
                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="user" class="w-5 h-5"></i>
                        </div>
                        <h2 class="font-medium text-base">Photo</h2>
                    </div>
                    <div class="p-5">

                        <label for="photoInput" class="dropzone" id="dropzoneLabel">
                            <input type="file" name="photo" id="photoInput"
                                   accept="image/*" class="hidden-file-input">

                            <div id="dropzonePlaceholder"
                                 class="flex flex-col items-center justify-center w-full">
                                <div class="dropzone-icon"><i data-feather="upload-cloud"></i></div>
                                <div class="dropzone-title">Upload photo</div>
                                <div class="dropzone-hint">PNG, JPG, WEBP. Max 4MB.</div>
                            </div>
                        </label>

                        <div class="mt-3">
                            <button type="button" class="btn btn-primary w-full" id="btnPickPhoto">
                                <i data-feather="image" class="w-4 h-4 mr-1"></i> Choose Image
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();

            // Auto-fill slug
            const thpNameInput = document.getElementById('thpName');
            const thpSlugInput = document.getElementById('thpSlug');
            let thpSlugManual  = {{ old('slug') ? 'true' : 'false' }};

            thpSlugInput.addEventListener('input', function () {
                thpSlugManual = true;
            });

            thpNameInput.addEventListener('input', function () {
                if (thpSlugManual) return;
                thpSlugInput.value = thpNameInput.value
                    .toLowerCase().trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            });

            // Photo dropzone
            const photoInput    = document.getElementById('photoInput');
            const dropzoneLabel = document.getElementById('dropzoneLabel');
            const placeholder   = document.getElementById('dropzonePlaceholder');
            const btnPick       = document.getElementById('btnPickPhoto');

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
                };
                reader.readAsDataURL(file);
            });

            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);
        });
    </script>
@endsection
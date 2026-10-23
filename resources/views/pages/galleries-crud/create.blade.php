@extends('../layout/' . $layout)

@section('subhead')
    <title>Add Photo - Icewall</title>
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
            border-radius: 14px;
            padding: 32px 20px;
            cursor: pointer;
            transition: all .2s ease;
            background: rgb(var(--color-slate-50));
            text-align: center;
            min-height: 240px;
            position: relative;
            overflow: hidden;
        }

        .dark .dropzone {
            background: rgb(var(--color-darkmode-700));
            border-color: rgb(var(--color-darkmode-400));
        }

        .dropzone:hover {
            border-color: rgb(var(--color-primary));
            background: rgb(var(--color-primary) / .03);
        }

        .dropzone.has-file {
            border-style: solid;
            border-color: rgb(var(--color-primary) / .35);
            padding: 0;
            min-height: 0;
            aspect-ratio: 4/3;
        }

        .dropzone-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            background: rgb(var(--color-primary) / .1);
            color: rgb(var(--color-primary));
        }

        .dropzone-icon svg {
            width: 26px;
            height: 26px
        }

        .dropzone-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: rgb(var(--color-slate-700));
        }

        .dark .dropzone-title {
            color: rgb(var(--color-slate-300))
        }

        .dropzone-hint {
            font-size: 12px;
            color: rgb(var(--color-slate-500));
        }

        .dark .dropzone-hint {
            color: rgb(var(--color-slate-400))
        }

        .dropzone img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hidden-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden;
        }
    </style>

    {{-- TOOLBAR --}}
    <div class="intro-y flex flex-wrap sm:flex-nowrap items-center gap-3 mt-8">
        <a href="{{ route('admin.galleries.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-lg font-medium text-slate-800 dark:text-slate-200">Add Photo</h2>
            <div class="text-slate-500 text-xs mt-0.5">Add a new photo to the website gallery.</div>
        </div>
        <div class="flex items-center gap-2 sm:ml-auto mt-3 sm:mt-0">
            <a href="{{ route('admin.galleries.index') }}"
               class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit"
                    form="galleryForm"
                    class="btn btn-primary shadow-md">
                <i data-feather="check" class="w-4 h-4 mr-1"></i> Save Photo
            </button>
        </div>
    </div>

    <form action="{{ route('admin.galleries.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="galleryForm"
          class="mt-5">

        @csrf

        <div class="grid grid-cols-12 gap-6">

            {{-- LEFT: IMAGE --}}
            <div class="intro-y col-span-12 lg:col-span-7">
                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="image" class="w-5 h-5"></i>
                        </div>
                        <h2 class="font-medium text-base text-slate-800 dark:text-slate-200">Photo</h2>
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

                        <label for="imageInput" class="dropzone" id="dropzoneLabel">
                            <input type="file" name="image" id="imageInput"
                                   accept="image/*" class="hidden-file-input" required>

                            <div id="dropzonePlaceholder" class="flex flex-col items-center justify-center w-full">
                                <div class="dropzone-icon">
                                    <i data-feather="upload-cloud"></i>
                                </div>
                                <div class="dropzone-title">Click to upload photo</div>
                                <div class="dropzone-hint">PNG, JPG, WEBP. Max 4MB.</div>
                            </div>
                        </label>

                    </div>
                </div>
            </div>

            {{-- RIGHT: DETAILS --}}
            <div class="intro-y col-span-12 lg:col-span-5">
                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="settings" class="w-5 h-5"></i>
                        </div>
                        <h2 class="font-medium text-base text-slate-800 dark:text-slate-200">Details</h2>
                    </div>
                    <div class="p-5">

                        <div class="mb-4">
                            <label class="form-label">
                                Title
                                <span class="text-slate-500 font-normal text-xs">(optional)</span>
                            </label>
                            <input type="text" name="title"
                                   value="{{ old('title') }}"
                                   class="form-control w-full"
                                   placeholder="e.g. Spa Ambiance">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select w-full" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-slate-500 text-xs mt-2">
                                No matching category?
                                <a href="{{ route('admin.gallery-categories.create') }}" class="text-primary font-semibold">Add new category</a>.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Sort Order
                                <span class="text-slate-500 font-normal text-xs">(smaller numbers appear first)</span>
                            </label>
                            <input type="number" name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   class="form-control w-full"
                                   min="0">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active"
                                       class="form-check-input"
                                       id="isActiveSwitch"
                                       value="1" checked>
                                <label class="form-check-label ml-2" for="isActiveSwitch">
                                    Active (shown on /gallery)
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Featured</label>
                            <div class="form-check form-switch mt-1">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured"
                                       class="form-check-input"
                                       id="isFeaturedSwitch"
                                       value="1">
                                <label class="form-check-label ml-2" for="isFeaturedSwitch">
                                    Featured (shown on homepage)
                                </label>
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

            const imageInput    = document.getElementById('imageInput');
            const dropzoneLabel = document.getElementById('dropzoneLabel');
            const placeholder   = document.getElementById('dropzonePlaceholder');

            imageInput.addEventListener('change', function () {
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
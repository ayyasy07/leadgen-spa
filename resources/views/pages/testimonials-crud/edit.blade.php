@extends('../layout/' . $layout)

@section('subhead')
    <title>Edit Testimonial - Icewall</title>
@endsection

@section('subcontent')

    <style>
        .tstm-card {
            background: #fff;
            border: 1px solid rgba(148, 163, 184, .18);
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
            min-width: 0
        }

        .tstm-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(148, 163, 184, .16)
        }

        .tstm-card-head .icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(59, 130, 246, .1);
            flex: none
        }

        .icon-badge svg {
            width: 18px;
            height: 18px;
            color: #3b82f6;
            stroke-width: 2px
        }

        .tstm-card-body {
            padding: 24px;
            min-width: 0
        }

        .tstm-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px
        }

        .tstm-hint {
            font-weight: 400;
            color: #94a3b8;
            font-size: 12px
        }

        .tstm-field {
            margin-bottom: 22px
        }

        .tstm-card .form-control {
            border-radius: 10px !important;
            border-color: rgba(148, 163, 184, .35) !important;
            font-size: 13.5px
        }

        .tstm-card .form-control:focus {
            border-color: rgba(59, 130, 246, .6) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12) !important
        }

        .star-picker {
            display: flex;
            gap: 6px;
            align-items: center
        }

        .star-picker button {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #e2e8f0;
            transition: all .15s ease;
        }

        .star-picker button svg {
            width: 28px;
            height: 28px;
            fill: currentColor;
            stroke: currentColor;
        }

        .star-picker button.active {
            color: #fbbf24
        }

        .star-picker button:hover {
            transform: scale(1.1)
        }

        .avatar-preview {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 32px;
            color: #fff;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            overflow: hidden;
            flex: none;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, .1);
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-row {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #3b82f6;
            background: rgba(59, 130, 246, .08);
            border: none;
            cursor: pointer;
            transition: background .15s ease;
        }

        .upload-btn:hover {
            background: rgba(59, 130, 246, .16)
        }

        .upload-btn svg {
            width: 15px;
            height: 15px
        }

        .hidden-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden
        }

        .page-toolbar {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            background: #fff;
            border: 1px solid rgba(148, 163, 184, .18);
            border-radius: 14px;
            padding: 16px 20px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
            position: sticky;
            top: 12px;
            z-index: 30
        }

        .page-toolbar .back-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none
        }

        .page-toolbar .back-btn svg {
            width: 17px;
            height: 17px
        }

        .page-toolbar .toolbar-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b
        }

        .page-toolbar .toolbar-sub {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
            margin-left: auto
        }

        .btn-ghost-cancel {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            background: #f1f5f9;
            border: none;
            cursor: pointer;
            text-decoration: none
        }

        .btn-ghost-cancel:hover {
            background: #e2e8f0
        }

        .btn-primary-save {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            background: #3b82f6;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(59, 130, 246, .3)
        }

        .btn-primary-save:hover {
            background: #2563eb;
            transform: translateY(-1px)
        }

        .btn-primary-save svg {
            width: 15px;
            height: 15px
        }

        .current-photo {
            margin-top: 14px;
            padding: 12px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px dashed rgba(148, 163, 184, .35);
        }

        .current-photo .label {
            font-size: 11.5px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 8px;
            display: block;
        }

        .current-photo img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .08);
        }
    </style>

    {{-- TOOLBAR --}}
    <div class="intro-y page-toolbar mt-8">
        <a href="{{ route('admin.testimonials.index') }}" class="back-btn">
            <i data-feather="arrow-left"></i>
        </a>
        <div>
            <div class="toolbar-title">Edit Testimonial</div>
            <div class="toolbar-sub">Edit an existing testimonial.</div>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('admin.testimonials.index') }}" class="btn-ghost-cancel">Cancel</a>
            <button type="submit" form="testimonialForm" class="btn-primary-save">
                <i data-feather="check"></i> Update Testimonial
            </button>
        </div>
    </div>

    {{-- FORM --}}
    <form action="{{ route('admin.testimonials.update', $testimonial) }}"
          method="POST"
          enctype="multipart/form-data"
          id="testimonialForm">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-12 gap-6 mt-5">

            {{-- LEFT: FORM FIELDS --}}
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="tstm-card">

                    <div class="tstm-card-head">
                        <div class="icon-badge"><i data-feather="edit-3"></i></div>
                        <h3 class="font-medium text-base">Testimonial Information</h3>
                    </div>

                    <div class="tstm-card-body">

                        {{-- ERROR --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-5">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- NAME --}}
                        <div class="tstm-field">
                            <label class="tstm-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   value="{{ old('name', $testimonial->name) }}"
                                   class="form-control w-full"
                                   required>
                        </div>

                        {{-- ROLE --}}
                        <div class="tstm-field">
                            <label class="tstm-label">
                                Role / Description <span class="tstm-hint">(optional)</span>
                            </label>
                            <input type="text" name="role"
                                   value="{{ old('role', $testimonial->role) }}"
                                   class="form-control w-full">
                        </div>

                        {{-- RATING --}}
                        <div class="tstm-field">
                            <label class="tstm-label">Rating <span class="text-danger">*</span></label>
                            <div class="star-picker" id="starPicker">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" data-value="{{ $i }}" class="{{ old('rating', $testimonial->rating) >= $i ? 'active' : '' }}">
                                        <i data-feather="star"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', $testimonial->rating) }}">
                        </div>

                        {{-- MESSAGE --}}
                        <div class="tstm-field">
                            <label class="tstm-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message"
                                      class="form-control w-full"
                                      rows="5"
                                      required>{{ old('message', $testimonial->message) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT: PHOTO + STATUS --}}
            <div class="intro-y col-span-12 lg:col-span-4">
                <div class="tstm-card">

                    <div class="tstm-card-head">
                        <div class="icon-badge"><i data-feather="image"></i></div>
                        <h3 class="font-medium text-base">Photo & Status</h3>
                    </div>

                    <div class="tstm-card-body">

                        {{-- PHOTO --}}
                        <div class="tstm-field">
                            <label class="tstm-label">Customer Photo <span class="tstm-hint">(optional)</span></label>
                            <div class="upload-row">
                                <div class="avatar-preview" id="avatarPreview">
                                    @if($testimonial->photo)
                                        <img src="{{ asset('storage/'.$testimonial->photo) }}" alt="{{ $testimonial->name }}">
                                    @else
                                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <label for="photoInput" class="upload-btn">
                                        <i data-feather="upload"></i> Change Photo
                                    </label>
                                    <input type="file" name="photo" id="photoInput"
                                           accept="image/*" class="hidden-file-input">
                                    <div class="tstm-hint mt-2">PNG, JPG. Max 2MB.</div>
                                </div>
                            </div>

                            @if($testimonial->photo)
                                <div class="current-photo">
                                    <span class="label">Current Photo</span>
                                    <img src="{{ asset('storage/'.$testimonial->photo) }}"
                                         alt="{{ $testimonial->name }}">
                                </div>
                            @endif
                        </div>

                        {{-- IS ACTIVE --}}
                        <div class="tstm-field" style="margin-bottom:0">
                            <label class="tstm-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" name="is_active"
                                       class="form-check-input"
                                       id="isActiveSwitch"
                                       {{ $testimonial->is_active ? 'checked' : '' }}>
                                <label class="form-check-label ml-2" for="isActiveSwitch">
                                    Active (shown on website)
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

            // Star Picker
            const starPicker = document.getElementById('starPicker');
            const ratingInput = document.getElementById('ratingInput');
            const starBtns = starPicker.querySelectorAll('button');

            starBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = parseInt(btn.dataset.value);
                    ratingInput.value = val;
                    starBtns.forEach(b => {
                        b.classList.toggle('active', parseInt(b.dataset.value) <= val);
                    });
                });
            });

            // Photo Preview
            const photoInput = document.getElementById('photoInput');
            const avatarPreview = document.getElementById('avatarPreview');

            photoInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (e) {
                    avatarPreview.innerHTML = `<img src="${e.target.result}" alt="preview">`;
                };
                reader.readAsDataURL(file);
            });

            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
        });
    </script>
@endsection
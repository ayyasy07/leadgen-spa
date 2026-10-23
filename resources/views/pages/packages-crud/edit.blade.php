@extends('../layout/' . $layout)

@section('subhead')
    <title>Edit Package - Icewall</title>
@endsection

@section('subcontent')

    <style>
        .pkgb-card {
            background: #fff;
            border: 1px solid rgba(148, 163, 184, .18);
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
            min-width: 0
        }

        .pkgb-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(148, 163, 184, .16)
        }

        .pkgb-card-head .icon-badge {
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
            color: var(--primary, #3b82f6);
            stroke-width: 2px
        }

        .pkgb-card-body {
            padding: 24px;
            min-width: 0
        }

        .pkgb-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px
        }

        .pkgb-hint {
            font-weight: 400;
            color: #94a3b8;
            font-size: 12px
        }

        .pkgb-field {
            margin-bottom: 22px
        }

        .pkgb-card .form-control {
            border-radius: 10px !important;
            border-color: rgba(148, 163, 184, .35) !important;
            font-size: 13.5px
        }

        .pkgb-card .form-control:focus {
            border-color: rgba(59, 130, 246, .6) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12) !important
        }

        .duration-preset {
            display: flex;
            gap: 8px;
            flex-wrap: wrap
        }

        .duration-chip {
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid transparent;
            cursor: pointer;
            transition: .15s ease
        }

        .duration-chip:hover {
            background: #e2e8f0
        }

        .duration-chip.active {
            background: rgba(59, 130, 246, .12);
            color: #3b82f6;
            border-color: rgba(59, 130, 246, .35)
        }

        .stat-tile {
            background: #f8fafc;
            border: 1px solid rgba(148, 163, 184, .14);
            border-radius: 12px;
            padding: 14px 12px;
            min-height: 76px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden
        }

        .stat-tile .stat-label {
            font-size: 11.5px;
            color: #94a3b8;
            margin-bottom: 6px;
            line-height: 1.2
        }

        .stat-tile .stat-value {
            font-size: 14.5px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .summary-group {
            border: 1px solid rgba(59, 130, 246, .15);
            border-radius: 12px;
            overflow: hidden
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 13px 18px;
            font-size: 13px
        }

        .summary-row+.summary-row {
            border-top: 1px dashed rgba(148, 163, 184, .25)
        }

        .summary-row .label {
            color: #64748b
        }

        .summary-row .value {
            font-weight: 700;
            color: #1e293b
        }

        .summary-row.highlight {
            background: linear-gradient(135deg, rgba(59, 130, 246, .06), rgba(59, 130, 246, .02));
            padding: 16px 18px
        }

        .summary-row.highlight .savings-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px
        }

        .summary-row.highlight .savings-value {
            font-size: 18px;
            font-weight: 700;
            color: #16a34a;
            line-height: 1.2
        }

        .discount-value {
            font-size: 13px;
            font-weight: 600;
            color: #16a34a;
            background: rgba(34, 197, 94, .12);
            padding: 4px 10px;
            border-radius: 999px;
            display: inline-block;
            white-space: nowrap
        }

        #treatmentCatalog {
            border-radius: 12px !important;
            border: 1px solid rgba(148, 163, 184, .22) !important;
            max-height: 360px;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin
        }

        #treatmentCatalog::-webkit-scrollbar {
            width: 6px
        }

        #treatmentCatalog::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, .4);
            border-radius: 999px
        }

        #treatmentCatalog::-webkit-scrollbar-track {
            background: transparent
        }

        .treatment-row {
            transition: background .15s ease
        }

        .treatment-row:hover {
            background: #f8fafc
        }

        .treatment-row.is-disabled {
            opacity: .35;
            pointer-events: none
        }

        .treatment-row .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 4px;
            flex: none
        }

        .variant-badge {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .3px;
            text-transform: uppercase;
            background: rgba(59, 130, 246, .1);
            color: #3b82f6;
            padding: 3px 10px;
            border-radius: 999px;
            flex: none
        }

        .variant-select.form-control-sm {
            border-radius: 8px !important;
            font-size: 13px !important;
            padding-top: 7px !important;
            padding-bottom: 7px !important;
            margin-top: 8px
        }

        .selected-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 18px;
            margin-top: 18px;
            border-top: 1px dashed rgba(148, 163, 184, .35)
        }

        .selected-head-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 13px
        }

        .selected-count-pill {
            font-size: 11.5px;
            font-weight: 700;
            background: rgba(59, 130, 246, .1);
            color: #3b82f6;
            padding: 2px 8px;
            border-radius: 999px
        }

        #selectedTreatmentsList {
            border: 1px dashed rgba(148, 163, 184, .35);
            border-radius: 12px;
            margin-top: 12px;
            max-height: 300px;
            overflow-y: auto;
            scrollbar-width: thin
        }

        #selectedTreatmentsList::-webkit-scrollbar {
            width: 6px
        }

        #selectedTreatmentsList::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, .4);
            border-radius: 999px
        }

        .selected-item-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-bottom: 1px dashed rgba(148, 163, 184, .22)
        }

        .selected-item-row:last-child {
            border-bottom: none
        }

        .selected-item-name {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .selected-item-input-wrap {
            position: relative;
            flex: none
        }

        .selected-item-input {
            width: 84px;
            padding: 6px 26px 6px 8px;
            font-size: 12.5px;
            border: 1px solid rgba(148, 163, 184, .35);
            border-radius: 8px;
            text-align: center;
        }

        .selected-item-input:focus {
            outline: none;
            border-color: rgba(59, 130, 246, .6);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
        }

        .spin {
            position: absolute;
            right: 2px;
            top: 1px;
            bottom: 1px;
            width: 20px;
            display: flex;
            flex-direction: column;
        }

        .spin button {
            flex: 1;
            border: 0;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
            font-size: 10px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spin button:hover {
            color: #3b82f6
        }

        .selected-item-price {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            min-width: 110px;
            text-align: right;
            flex: none
        }

        .selected-item-price small {
            display: block;
            font-size: 10.5px;
            font-weight: 500;
            color: #94a3b8;
            margin-top: 2px
        }

        .remove-selected {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(239, 68, 68, .08);
            color: #ef4444 !important;
            transition: background .15s ease;
            flex: none;
            border: none;
            cursor: pointer
        }

        .remove-selected:hover {
            background: rgba(239, 68, 68, .2)
        }

        .remove-selected svg {
            width: 15px;
            height: 15px
        }

        .custom-step-wrap {
            margin-top: 14px;
            padding: 16px;
            border: 1px dashed rgba(148, 163, 184, .35);
            border-radius: 12px;
            background: #fbfcfe;
            display: none
        }

        .custom-step-wrap.is-open {
            display: block
        }

        .custom-step-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr) minmax(0, 1fr) auto;
            gap: 10px;
            align-items: center
        }

        .custom-step-field {
            min-width: 0
        }

        .custom-step-field .form-control {
            width: 100%
        }

        .custom-step-grid .btn {
            white-space: nowrap
        }

        .custom-step-field .input-group {
            display: flex;
            align-items: stretch;
            height: 42px;
            border: 1px solid rgba(148, 163, 184, .35);
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .custom-step-field .input-group:focus-within {
            border-color: rgba(59, 130, 246, .6);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .custom-step-field .input-group>* {
            border-radius: 0 !important;
            height: 100% !important;
            box-sizing: border-box;
        }

        .custom-step-field .input-group .form-control {
            border: 0 !important;
            box-shadow: none !important;
            flex: 1;
            min-width: 0;
            background: #fff;
        }

        .custom-step-field .input-group .form-control:focus {
            box-shadow: none !important;
            border: 0 !important;
        }

        .custom-step-field .input-group-text {
            background: #f8fafc;
            color: #64748b;
            font-size: 12.5px;
            font-weight: 600;
            padding: 0 12px;
            display: flex;
            align-items: center;
            white-space: nowrap;
            flex: none;
            border: 0;
        }

        .custom-step-field .input-group-text:last-child {
            border-left: 1px solid rgba(148, 163, 184, .3) !important;
        }

        .custom-step-field .input-group-text:first-child {
            border-right: 1px solid rgba(148, 163, 184, .3) !important;
        }

        @media(max-width:640px) {
            .custom-step-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px
            }

            .custom-step-grid .btn {
                grid-column: span 2
            }
        }

        .custom-step-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 600;
            color: #3b82f6;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 0
        }

        .custom-step-btn:hover {
            text-decoration: underline
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 0
        }

        .search-wrap .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #94a3b8;
            pointer-events: none
        }

        .search-wrap input.form-control {
            padding-left: 38px;
            border-radius: 10px
        }

        .category-wrap {
            position: relative;
            flex: none;
            width: 100%;
            max-width: 220px;
        }

        .category-wrap svg.cat-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #94a3b8;
            pointer-events: none;
            stroke-width: 2;
        }

        .category-wrap select.form-control {
            padding-left: 40px;
            padding-right: 36px;
            border-radius: 10px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }

        .category-wrap .cat-chevron {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #94a3b8;
            pointer-events: none;
            stroke-width: 2;
        }

        .media-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 16px;
            align-items: center;
            overflow: hidden;
            width: 100%;
            min-width: 0;
        }

        .dropzone {
            display: flex;
            align-items: center;
            gap: 14px;
            border: 1.5px dashed rgba(148, 163, 184, .45);
            border-radius: 12px;
            padding: 14px 16px;
            cursor: pointer;
            transition: border-color .2s ease, background .2s ease;
            background: #fbfcfe;
            min-width: 0;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
        }

        .dropzone:hover {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, .03)
        }

        .dropzone.has-file {
            border-style: solid;
            border-color: rgba(59, 130, 246, .35);
            background: rgba(59, 130, 246, .03)
        }

        .dropzone-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            flex: none;
            background: rgba(59, 130, 246, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden
        }

        .dropzone-icon svg {
            width: 20px;
            height: 20px;
            color: #3b82f6
        }

        .dropzone-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px
        }

        .dropzone-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
            flex: 1;
            overflow: hidden
        }

        .dropzone-title {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .dropzone-hint {
            font-size: 11.5px;
            color: #94a3b8;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .hidden-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden
        }

        .media-col-status {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            flex: none
        }

        .media-col-status .form-switch {
            margin: 0
        }

        .media-col-status .status-caption {
            font-size: 11px;
            color: #94a3b8;
            white-space: nowrap
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
            color: #475569
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

        .btn-primary-save:hover:not(:disabled) {
            background: #2563eb;
            transform: translateY(-1px)
        }

        .btn-primary-save:disabled {
            background: #cbd5e1;
            box-shadow: none;
            cursor: not-allowed
        }

        .btn-primary-save svg {
            width: 15px;
            height: 15px
        }
    </style>

    <div class="intro-y page-toolbar mt-8">
        <a href="{{ route('admin.packages.index') }}" class="back-btn"><i data-feather="arrow-left"></i></a>
        <div>
            <div class="toolbar-title">Edit Package</div>
            <div class="toolbar-sub">Edit an existing bundling package.</div>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('admin.packages.index') }}" class="btn-ghost-cancel">Cancel</a>
            <button type="submit" form="packageForm" id="saveBtn" class="btn-primary-save" disabled>
                <i data-feather="check"></i> Update Package
            </button>
        </div>
    </div>

    <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data" id="packageForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-12 gap-6 mt-5">

            <div class="intro-y col-span-12" style="grid-column: span 5;">
                <div class="pkgb-card">
                    <div class="pkgb-card-head">
                        <div class="icon-badge"><i data-feather="package"></i></div>
                        <h3 class="font-medium text-base">Package Details</h3>
                    </div>
                    <div class="pkgb-card-body">

                        <div class="pkgb-field">
                            <label class="pkgb-label">Package Name</label>
                            <input type="text" name="name" id="pkgName" class="form-control w-full"
                                   value="{{ old('name', $package->name) }}" required>
                        </div>

                        <div class="pkgb-field">
                            <label class="pkgb-label">Slug <span class="pkgb-hint">(URL: /package/{{ $package->slug }})</span></label>
                            <input type="text" name="slug" id="pkgSlug" class="form-control w-full"
                                   value="{{ old('slug', $package->slug) }}">
                        </div>

                        <div class="pkgb-field">
                            <label class="pkgb-label">Unit</label>
                            <select name="unit" class="form-control w-full">
                                <option value="person" {{ $package->unit == 'person' ? 'selected' : '' }}>Person</option>
                                <option value="couple" {{ $package->unit == 'couple' ? 'selected' : '' }}>Couple</option>
                            </select>
                        </div>

                        <div class="pkgb-field">
                            <label class="pkgb-label">Target Duration <span class="pkgb-hint">(total package processing time)</span></label>
                            <div class="duration-preset" id="durationPreset">
                                @foreach([90,120,150,180] as $preset)
                                    <button type="button"
                                            class="duration-chip {{ $package->duration == $preset ? 'active' : '' }}"
                                            data-value="{{ $preset }}">{{ $preset }}m</button>
                                @endforeach
                                <button type="button"
                                        class="duration-chip {{ !in_array($package->duration, [90,120,150,180]) ? 'active' : '' }}"
                                        data-value="custom">Custom</button>
                            </div>
                            <input type="number" name="duration" id="packageDuration"
                                   class="form-control w-full mt-3"
                                   value="{{ old('duration', $package->duration) }}" required>
                        </div>

                        <div class="pkgb-field">
                            <label class="pkgb-label">Package Price <span class="pkgb-hint">(Rp)</span></label>
                            <input type="number" name="price" id="packagePrice"
                                   class="form-control w-full"
                                   value="{{ old('price', $package->price) }}" required>
                        </div>

                        <div class="pkgb-field">
                            <label class="pkgb-label">Tagline / Short Description</label>
                            <textarea name="description" class="form-control w-full"
                                      rows="2" maxlength="160">{{ old('description', $package->description) }}</textarea>
                        </div>

                        <div class="pkgb-field">
                            <label class="pkgb-label">Package Image</label>
                            <div class="media-row">
                                <label for="imageInput" class="dropzone {{ $package->image ? 'has-file' : '' }}" id="dropzoneLabel">
                                    <div class="dropzone-icon" id="dropzoneIcon">
                                        @if($package->image)
                                            <img src="{{ asset('storage/'.$package->image) }}" alt="preview">
                                        @else
                                            <i data-feather="image"></i>
                                        @endif
                                    </div>
                                    <div class="dropzone-text">
                                        <span class="dropzone-title" id="dropzoneTitle">
                                            {{ $package->image ? basename($package->image) : 'Click to upload image' }}
                                        </span>
                                        <span class="dropzone-hint" id="dropzoneHint">
                                            {{ $package->image ? 'Click to change image' : 'PNG or JPG, max 2MB' }}
                                        </span>
                                    </div>
                                </label>
                                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden-file-input">

                                <div class="media-col-status">
                                    <div class="form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" class="form-check-input"
                                            value="1"
                                            {{ $package->is_active ? 'checked' : '' }}>
                                    </div>
                                    <span class="status-caption">Active</span>

                                    <div class="form-switch" style="margin-top: 8px;">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox" name="is_featured" class="form-check-input"
                                            value="1"
                                            {{ $package->is_featured ? 'checked' : '' }}>
                                    </div>
                                    <span class="status-caption">Featured</span>

                                    <div class="form-switch" style="margin-top: 8px;">
                                        <input type="hidden" name="is_popular" value="0">
                                        <input type="checkbox" name="is_popular" class="form-check-input"
                                            value="1"
                                            {{ $package->is_popular ? 'checked' : '' }}>
                                    </div>
                                    <span class="status-caption">Popular</span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-slate-300/70 my-7"></div>

                        <div class="flex items-center mb-5">
                            <div class="icon-badge" style="background:rgba(59,130,246,.1)">
                                <i data-feather="calculator"></i>
                            </div>
                            <h3 class="font-medium text-base ml-3">Auto Summary</h3>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="stat-tile">
                                <div class="stat-label">Original Price</div>
                                <div class="stat-value" id="totalValue">Rp 0</div>
                            </div>
                            <div class="stat-tile">
                                <div class="stat-label">Duration</div>
                                <div class="stat-value" id="totalDuration">0 / 0 Mins</div>
                            </div>
                            <div class="stat-tile">
                                <div class="stat-label">Items</div>
                                <div class="stat-value" id="totalTreatments">0</div>
                            </div>
                        </div>

                        <div class="summary-group">
                            <div class="summary-row">
                                <span class="label">Package Price</span>
                                <strong class="value" id="packagePriceView">Rp 0</strong>
                            </div>
                            <div class="summary-row highlight">
                                <div>
                                    <div class="savings-label">Customer Savings</div>
                                    <div class="savings-value" id="savings">Rp 0</div>
                                </div>
                                <div class="text-right">
                                    <div class="savings-label">Discount</div>
                                    <span id="savingsPercent" class="discount-value">0% OFF</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ============ SEO CARD ============ --}}
                <div class="pkgb-card mt-5">
                    <div class="pkgb-card-head">
                        <div class="icon-badge"><i data-feather="search"></i></div>
                        <h3 class="font-medium text-base">SEO</h3>
                    </div>
                    <div class="pkgb-card-body">
                        <div class="pkgb-field">
                            <label class="pkgb-label">
                                Meta Title
                                <span class="pkgb-hint">(empty = auto from name)</span>
                            </label>
                            <input type="text" name="meta_title"
                                   class="form-control w-full"
                                   value="{{ old('meta_title', $package->meta_title) }}"
                                   maxlength="255">
                        </div>
                        <div class="pkgb-field" style="margin-bottom:0">
                            <label class="pkgb-label">Meta Description</label>
                            <textarea name="meta_description"
                                      class="form-control w-full"
                                      rows="3"
                                      maxlength="500">{{ old('meta_description', $package->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="intro-y col-span-12" style="grid-column: span 7;">
                <div class="pkgb-card">
                    <div class="pkgb-card-head">
                        <div class="icon-badge"><i data-feather="check-square"></i></div>
                        <h3 class="font-medium text-base mr-auto">Treatment Selector</h3>
                        <span class="text-xs text-slate-400">Choose treatments to bundle</span>
                    </div>

                    <div class="pkgb-card-body">

                        <div class="flex flex-col sm:flex-row gap-3 mb-5">
                            <div class="search-wrap">
                                <i data-feather="search" class="search-icon"></i>
                                <input type="text" id="treatmentSearch" class="form-control w-full" placeholder="Search treatment name...">
                            </div>

                            <div class="category-wrap">
                                <i data-feather="filter" class="cat-icon"></i>
                                <select id="categoryFilter" class="form-control">
                                    <option value="all">All Categories</option>
                                    @foreach($treatments->pluck('category')->filter()->unique() as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="cat-chevron"></i>
                            </div>
                        </div>

                        <div id="treatmentCatalog">
                            @foreach($treatments as $t)
                                @php
                                    $variants = $t->prices;
                                    $hasVariants = $variants->count() > 1;
                                    $defaultVariant = $variants->sortBy('price')->first();

                                    $existingItem = $package->items->firstWhere('treatment_id', $t->id);
                                @endphp

                                <label class="flex items-start gap-3 px-4 py-4 cursor-pointer treatment-row"
                                       data-name="{{ strtolower($t->name) }}"
                                       data-category="{{ $t->category }}">

                                    <input type="checkbox"
                                           class="form-check-input treatment-checkbox"
                                           data-id="{{ $t->id }}"
                                           data-name="{{ $t->name }}"
                                           data-duration="{{ $existingItem->duration ?? ($defaultVariant->duration ?? 0) }}"
                                           data-price="{{ $existingItem->price ?? ($defaultVariant->price ?? 0) }}"
                                           {{ $existingItem ? 'checked' : '' }}>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="font-medium text-sm truncate">{{ $t->name }}</div>
                                            @if($hasVariants)
                                                <span class="variant-badge">{{ $variants->count() }} variants</span>
                                            @endif
                                        </div>

                                        @if($hasVariants)
                                            <select class="form-control form-control-sm w-full variant-select">
                                                @foreach($variants as $v)
                                                    <option data-duration="{{ $v->duration }}"
                                                            data-price="{{ $v->price }}"
                                                            {{ (int)$v->duration === (int)($existingItem->duration ?? -1)
                                                                && (int)$v->price === (int)($existingItem->price ?? -1) ? 'selected' : '' }}>
                                                        {{ $v->duration }} Mins — Rp {{ number_format($v->price, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div class="text-slate-500 text-xs mt-1.5">
                                                {{ $defaultVariant->duration ?? 0 }} Mins • Rp {{ number_format($defaultVariant->price ?? 0, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <button type="button" class="custom-step-btn" id="toggleCustomStep">
                                <i data-feather="plus-circle" style="width:14px;height:14px"></i>
                                Add facility / custom step
                            </button>

                            <div class="custom-step-wrap" id="customStepWrap">
                                <div class="custom-step-grid">
                                    <div class="custom-step-field">
                                        <input type="text" id="csName" class="form-control" placeholder="facility name (e.g., Sauna)">
                                    </div>
                                    <div class="custom-step-field">
                                        <div class="input-group">
                                            <input type="text" inputmode="numeric" pattern="[0-9]*"
                                                   id="csDuration" class="form-control numeric-input" placeholder="15">
                                            <div class="input-group-text">Mins</div>
                                        </div>
                                    </div>
                                    <div class="custom-step-field">
                                        <div class="input-group">
                                            <div class="input-group-text">Rp</div>
                                            <input type="text" inputmode="numeric" pattern="[0-9]*"
                                                   id="csPrice" class="form-control numeric-input" placeholder="0">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="csAdd">Add</button>
                                </div>
                                <div class="text-xs text-slate-400 mt-2">
                                    Leave price empty (0) if the facility is only a complement.
                                </div>
                            </div>
                        </div>

                        <div class="selected-head">
                            <div class="selected-head-title">
                                <span>Selected Items</span>
                                <span class="selected-count-pill" id="selectedCountLabel">0 items</span>
                            </div>
                            <span class="text-xs text-slate-400">Minimum 2 items required to save</span>
                        </div>

                        <div id="selectedTreatmentsList">
                            <p class="text-slate-400 text-xs px-3 py-5 text-center" id="emptySelectedNote">
                                No items selected yet. Please select treatments from the list above or add custom steps.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div id="hiddenItems"></div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();

            // ===== Auto-fill slug dari name (cuma kalo slug kosong) =====
            const pkgNameInput = document.getElementById('pkgName');
            const pkgSlugInput = document.getElementById('pkgSlug');

            pkgNameInput.addEventListener('input', function () {
                if (pkgSlugInput.value.trim() !== '') return;
                pkgSlugInput.value = pkgNameInput.value
                    .toLowerCase().trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            });

            const checkboxes        = document.querySelectorAll('.treatment-checkbox');
            const packagePriceInput = document.getElementById('packagePrice');
            const packageDuration   = document.getElementById('packageDuration');
            const selectedList      = document.getElementById('selectedTreatmentsList');
            const emptySelectedNote = document.getElementById('emptySelectedNote');
            const selectedCountLabel= document.getElementById('selectedCountLabel');
            const totalTreatments   = document.getElementById('totalTreatments');
            const totalDurationEl   = document.getElementById('totalDuration');
            const totalValue        = document.getElementById('totalValue');
            const packagePriceView  = document.getElementById('packagePriceView');
            const savings           = document.getElementById('savings');
            const savingsPercent    = document.getElementById('savingsPercent');
            const saveBtn           = document.getElementById('saveBtn');
            const hiddenItems       = document.getElementById('hiddenItems');

            const durationPreset    = document.getElementById('durationPreset');
            const presetChips       = durationPreset.querySelectorAll('.duration-chip');

            let customItems = [];
            @foreach($package->items as $item)
                @if($item->is_custom || !$item->treatment_id)
                    customItems.push({
                        id: 'db-{{ $item->id }}',
                        treatment_id: null,
                        name: @json($item->name),
                        duration: {{ (int) $item->duration }},
                        price: {{ (int) $item->price }},
                        is_custom: true,
                    });
                @endif
            @endforeach

            function formatRp(n){ return 'Rp ' + Math.round(n).toLocaleString('id-ID'); }

            function getSelectedCatalog(){
                const out = [];
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        out.push({
                            id: 'cat-' + cb.dataset.id,
                            treatment_id: cb.dataset.id,
                            name: cb.dataset.name,
                            duration: parseInt(cb.dataset.duration || 0),
                            price: parseInt(cb.dataset.price || 0),
                            is_custom: false,
                        });
                    }
                });
                return out;
            }

            function allItems(){ return [...getSelectedCatalog(), ...customItems]; }

            function getLimit(){ return parseInt(packageDuration.value || 0); }
            function getTotalDuration(){ return allItems().reduce((a,b)=> a+b.duration, 0); }

            function updateDurationLimit(){
                const limit = getLimit();
                const totalDur = getTotalDuration();

                document.querySelectorAll('.treatment-row').forEach(row => {
                    const cb = row.querySelector('.treatment-checkbox');
                    const dur = parseInt(cb.dataset.duration || 0);
                    const wouldOverflow = limit > 0 && (totalDur + dur > limit) && !cb.checked;
                    row.classList.toggle('is-disabled', wouldOverflow);
                    cb.disabled = wouldOverflow;
                });
            }

            function renderSelectedList(){
                const items = allItems();
                selectedList.querySelectorAll('.selected-item-row').forEach(el => el.remove());

                items.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'selected-item-row';
                    row.innerHTML = `
                        <div class="selected-item-name" title="${item.name}">${item.name}${item.is_custom ? ' <span style="color:#3b82f6;font-size:10px">• custom</span>' : ''}</div>

                        <div class="selected-item-input-wrap">
                            <input type="text" inputmode="numeric" pattern="[0-9]*"
                                   class="selected-item-input js-dur"
                                   value="${item.duration}"
                                   data-id="${item.id}">
                            <div class="spin">
                                <button type="button" data-spin="1" data-id="${item.id}">▲</button>
                                <button type="button" data-spin="-1" data-id="${item.id}">▼</button>
                            </div>
                        </div>

                        <div class="selected-item-price">${formatRp(item.price)}<small>${item.duration} Mins</small></div>
                        <button type="button" class="remove-selected" data-id="${item.id}"><i data-feather="x"></i></button>
                    `;
                    selectedList.appendChild(row);
                });

                if (window.feather) feather.replace();
            }

            function pushHiddenItems(){
                hiddenItems.innerHTML = '';
                allItems().forEach((item, i) => {
                    const t = document.createElement('input');
                    t.type = 'hidden'; t.name = `items[${i}][treatment_id]`; t.value = item.treatment_id ?? ''; hiddenItems.appendChild(t);
                    const n = document.createElement('input');
                    n.type = 'hidden'; n.name = `items[${i}][name]`; n.value = item.name; hiddenItems.appendChild(n);
                    const d = document.createElement('input');
                    d.type = 'hidden'; d.name = `items[${i}][duration]`; d.value = item.duration; hiddenItems.appendChild(d);
                    const p = document.createElement('input');
                    p.type = 'hidden'; p.name = `items[${i}][price]`; p.value = item.price; hiddenItems.appendChild(p);
                    const c = document.createElement('input');
                    c.type = 'hidden'; c.name = `items[${i}][is_custom]`; c.value = item.is_custom ? 1 : 0; hiddenItems.appendChild(c);
                });
            }

            function updateSummary(){
                const items = allItems();
                const totalDur = items.reduce((a,b)=> a+b.duration, 0);
                const totalV   = items.reduce((a,b)=> a+b.price, 0);
                const limit    = getLimit();

                totalTreatments.textContent = items.length;
                totalDurationEl.textContent = totalDur + ' / ' + (limit || 0) + ' Mins';
                totalValue.textContent = formatRp(totalV);

                const pkgPrice = parseFloat(packagePriceInput.value || 0);
                const saving = Math.max(totalV - pkgPrice, 0);
                const pct = totalV > 0 ? Math.max(0, Math.round((saving / totalV) * 100)) : 0;

                packagePriceView.textContent = formatRp(pkgPrice);
                savings.textContent = formatRp(saving);
                savingsPercent.textContent = pct + '% OFF';

                selectedCountLabel.textContent = items.length + ' items';
                emptySelectedNote.style.display = items.length === 0 ? 'block' : 'none';

                saveBtn.disabled = !(items.length >= 2 && limit > 0 && pkgPrice > 0);

                renderSelectedList();
                pushHiddenItems();
                updateDurationLimit();

                if (window.feather) feather.replace();
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const row = this.closest('.treatment-row');
                    const variantSelect = row.querySelector('.variant-select');
                    if (variantSelect) {
                        const opt = variantSelect.options[variantSelect.selectedIndex];
                        this.dataset.duration = opt.dataset.duration;
                        this.dataset.price = opt.dataset.price;
                    }
                    updateSummary();
                });
            });

            document.querySelectorAll('.variant-select').forEach(select => {
                select.addEventListener('click', e => e.stopPropagation());
                select.addEventListener('change', function () {
                    const row = this.closest('.treatment-row');
                    const cb = row.querySelector('.treatment-checkbox');
                    const opt = this.options[this.selectedIndex];
                    cb.dataset.duration = opt.dataset.duration;
                    cb.dataset.price = opt.dataset.price;
                    if (cb.checked) updateSummary();
                });
            });

            selectedList.addEventListener('click', function(e){
                const removeBtn = e.target.closest('.remove-selected');
                if (removeBtn) {
                    const id = removeBtn.dataset.id;
                    if (id.startsWith('cat-')) {
                        const cb = document.querySelector('.treatment-checkbox[data-id="' + id.replace('cat-','') + '"]');
                        if (cb) cb.checked = false;
                    } else {
                        customItems = customItems.filter(c => c.id !== id);
                    }
                    updateSummary();
                    return;
                }

                const spinBtn = e.target.closest('[data-spin]');
                if (spinBtn) {
                    const id = spinBtn.dataset.id;
                    const delta = parseInt(spinBtn.dataset.spin);
                    const input = document.querySelector('.js-dur[data-id="' + id + '"]');
                    let val = parseInt(input.value || 0) + delta;
                    if (val < 1) val = 1;
                    input.value = val;

                    if (id.startsWith('cat-')) {
                        const cb = document.querySelector('.treatment-checkbox[data-id="' + id.replace('cat-','') + '"]');
                        if (cb) {
                            const orig = parseInt(cb.dataset.duration || 1);
                            const origPrice = parseInt(cb.dataset.price || 0);
                            cb.dataset.duration = val;
                            cb.dataset.price = Math.round(origPrice * (val / orig));
                        }
                    } else {
                        const idx = customItems.findIndex(c => c.id === id);
                        if (idx > -1) {
                            const orig = customItems[idx].duration || 1;
                            const origPrice = customItems[idx].price || 0;
                            customItems[idx].duration = val;
                            customItems[idx].price = Math.round(origPrice * (val / orig));
                        }
                    }
                    updateSummary();
                }
            });

            selectedList.addEventListener('input', function(e){
                if (!e.target.classList.contains('js-dur')) return;
                const el = e.target;
                const id = el.dataset.id;
                let raw = el.value.replace(/[^0-9]/g, '');
                if (raw === '') return;
                let val = parseInt(raw);
                if (isNaN(val) || val < 1) return;

                if (id.startsWith('cat-')) {
                    const cb = document.querySelector('.treatment-checkbox[data-id="' + id.replace('cat-','') + '"]');
                    if (cb) {
                        const orig = parseInt(cb.dataset.duration || 1);
                        const origPrice = parseInt(cb.dataset.price || 0);
                        cb.dataset.duration = val;
                        cb.dataset.price = Math.round(origPrice * (val / orig));
                    }
                } else {
                    const idx = customItems.findIndex(c => c.id === id);
                    if (idx > -1) {
                        const orig = customItems[idx].duration || 1;
                        const origPrice = customItems[idx].price || 0;
                        customItems[idx].duration = val;
                        customItems[idx].price = Math.round(origPrice * (val / orig));
                    }
                }
                updateSummary();

                const newInput = document.querySelector('.js-dur[data-id="' + id + '"]');
                if (newInput) {
                    newInput.focus();
                    newInput.setSelectionRange(newInput.value.length, newInput.value.length);
                }
            });

            selectedList.addEventListener('keydown', function(e){
                if (!e.target.classList.contains('js-dur')) return;
                if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    const input = e.target;
                    const id = input.dataset.id;
                    let val = parseInt(input.value || 0) + (e.key === 'ArrowUp' ? 1 : -1);
                    if (val < 1) val = 1;
                    input.value = val;

                    if (id.startsWith('cat-')) {
                        const cb = document.querySelector('.treatment-checkbox[data-id="' + id.replace('cat-','') + '"]');
                        if (cb) {
                            const orig = parseInt(cb.dataset.duration || 1);
                            const origPrice = parseInt(cb.dataset.price || 0);
                            cb.dataset.duration = val;
                            cb.dataset.price = Math.round(origPrice * (val / orig));
                        }
                    } else {
                        const idx = customItems.findIndex(c => c.id === id);
                        if (idx > -1) {
                            const orig = customItems[idx].duration || 1;
                            const origPrice = customItems[idx].price || 0;
                            customItems[idx].duration = val;
                            customItems[idx].price = Math.round(origPrice * (val / orig));
                        }
                    }
                    updateSummary();

                    const newInput = document.querySelector('.js-dur[data-id="' + id + '"]');
                    if (newInput) {
                        newInput.focus();
                        newInput.setSelectionRange(newInput.value.length, newInput.value.length);
                    }
                }
            });

            presetChips.forEach(chip => {
                chip.addEventListener('click', function () {
                    presetChips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    const v = this.dataset.value;
                    if (v !== 'custom') {
                        packageDuration.value = v;
                    } else {
                        packageDuration.focus();
                    }
                    updateSummary();
                });
            });

            packageDuration.addEventListener('input', updateSummary);
            packagePriceInput.addEventListener('input', updateSummary);

            const toggleCustomStep = document.getElementById('toggleCustomStep');
            const customStepWrap   = document.getElementById('customStepWrap');
            const csName           = document.getElementById('csName');
            const csDuration       = document.getElementById('csDuration');
            const csPrice          = document.getElementById('csPrice');
            const csAdd            = document.getElementById('csAdd');

            toggleCustomStep.addEventListener('click', () => {
                customStepWrap.classList.toggle('is-open');
            });

            [csDuration, csPrice].forEach(inp => {
                inp.addEventListener('input', function () {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });

            csAdd.addEventListener('click', () => {
                const name = csName.value.trim();
                const dur  = parseInt(csDuration.value || 0);
                const pr   = parseInt(csPrice.value || 0);
                if (!name || dur < 1) return;

                customItems.push({
                    id: 'cs-' + Date.now(),
                    treatment_id: null,
                    name: name,
                    duration: dur,
                    price: pr,
                    is_custom: true,
                });

                csName.value = '';
                csDuration.value = '';
                csPrice.value = '';
                updateSummary();
            });

            const searchInput = document.getElementById('treatmentSearch');
            const categoryFilter = document.getElementById('categoryFilter');
            const rows = document.querySelectorAll('.treatment-row');

            function applyFilter() {
                const q = searchInput.value.trim().toLowerCase();
                const cat = categoryFilter.value;
                rows.forEach(row => {
                    const okName = row.dataset.name.includes(q);
                    const okCat = cat === 'all' || row.dataset.category === cat;
                    row.style.display = (okName && okCat) ? 'flex' : 'none';
                });
            }
            searchInput.addEventListener('input', applyFilter);
            categoryFilter.addEventListener('change', applyFilter);

            const imageInput    = document.getElementById('imageInput');
            const dropzoneTitle = document.getElementById('dropzoneTitle');
            const dropzoneHint  = document.getElementById('dropzoneHint');
            const dropzoneIcon  = document.getElementById('dropzoneIcon');
            const dropzoneLabel = document.getElementById('dropzoneLabel');

            imageInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;
                dropzoneTitle.textContent = file.name;
                dropzoneHint.textContent = (file.size / 1024).toFixed(0) + ' KB — click to change image';
                dropzoneLabel.classList.add('has-file');
                const reader = new FileReader();
                reader.onload = function (e) {
                    dropzoneIcon.innerHTML = `<img src="${e.target.result}" alt="preview">`;
                };
                reader.readAsDataURL(file);
            });

            updateSummary();

            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 300);
        });
    </script>
@endsection
@extends('../layout/' . $layout)

@section('subhead')
    <title>Add Package - Icewall</title>
@endsection

@section('subcontent')

<style>
     /* ===== DURATION CHIP ===== */
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
        background: rgb(var(--color-slate-100));
        color: rgb(var(--color-slate-600));
        border: 1px solid transparent;
        cursor: pointer;
        transition: .15s ease;
    }

    .dark .duration-chip {
        background: rgb(var(--color-darkmode-700));
        color: rgb(var(--color-slate-300))
    }

    .duration-chip:hover {
        background: rgb(var(--color-slate-200))
    }

    .dark .duration-chip:hover {
        background: rgb(var(--color-darkmode-600))
    }

    .duration-chip.active {
        background: rgb(var(--color-primary) / .12);
        color: rgb(var(--color-primary));
        border-color: rgb(var(--color-primary) / .35);
    }

    /* ===== STAT TILE ===== */
    .stat-tile {
        background: rgb(var(--color-slate-50));
        border: 1px solid rgb(var(--color-slate-200) / .7);
        border-radius: 12px;
        padding: 14px 12px;
        min-height: 76px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: hidden;
    }

    .dark .stat-tile {
        background: rgb(var(--color-darkmode-700));
        border-color: rgb(var(--color-darkmode-400))
    }

    .stat-tile .stat-label {
        font-size: 11.5px;
        color: rgb(var(--color-slate-500));
        margin-bottom: 6px;
        line-height: 1.2
    }

    .dark .stat-tile .stat-label {
        color: rgb(var(--color-slate-400))
    }

    .stat-tile .stat-value {
        font-size: 14.5px;
        font-weight: 700;
        color: rgb(var(--color-slate-800));
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .dark .stat-tile .stat-value {
        color: rgb(var(--color-slate-200))
    }

    /* ===== SUMMARY GROUP ===== */
    .summary-group {
        border: 1px solid rgb(var(--color-primary) / .15);
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
        border-top: 1px dashed rgb(var(--color-slate-300) / .7)
    }

    .dark .summary-row+.summary-row {
        border-top-color: rgb(var(--color-darkmode-400) / .7)
    }

    .summary-row .label {
        color: rgb(var(--color-slate-500))
    }

    .dark .summary-row .label {
        color: rgb(var(--color-slate-400))
    }

    .summary-row .value {
        font-weight: 700;
        color: rgb(var(--color-slate-800))
    }

    .dark .summary-row .value {
        color: rgb(var(--color-slate-200))
    }

    .summary-row.highlight {
        background: linear-gradient(135deg, rgb(var(--color-primary) / .06), rgb(var(--color-primary) / .02));
        padding: 16px 18px;
    }

    .summary-row.highlight .savings-label {
        font-size: 12px;
        color: rgb(var(--color-slate-500));
        margin-bottom: 4px
    }

    .dark .summary-row.highlight .savings-label {
        color: rgb(var(--color-slate-400))
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
        white-space: nowrap;
    }

    /* ===== TREATMENT CATALOG ===== */
    #treatmentCatalog {
        border-radius: 12px !important;
        border: 1px solid rgb(var(--color-slate-200)) !important;
        max-height: 360px;
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: thin;
    }

    .dark #treatmentCatalog {
        border-color: rgb(var(--color-darkmode-400)) !important
    }

    #treatmentCatalog::-webkit-scrollbar {
        width: 6px
    }

    #treatmentCatalog::-webkit-scrollbar-thumb {
        background: rgb(var(--color-slate-300));
        border-radius: 999px
    }

    .treatment-row {
        transition: background .15s ease
    }

    .treatment-row:hover {
        background: rgb(var(--color-slate-50))
    }

    .dark .treatment-row:hover {
        background: rgb(var(--color-darkmode-700))
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
        background: rgb(var(--color-primary) / .1);
        color: rgb(var(--color-primary));
        padding: 3px 10px;
        border-radius: 999px;
        flex: none;
    }

    .variant-select.form-control-sm {
        border-radius: 8px !important;
        font-size: 13px !important;
        padding-top: 7px !important;
        padding-bottom: 7px !important;
        margin-top: 8px
    }

    /* ===== SELECTED ===== */
    .selected-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 18px;
        margin-top: 18px;
        border-top: 1px dashed rgb(var(--color-slate-300) / .7)
    }

    .dark .selected-head {
        border-top-color: rgb(var(--color-darkmode-400) / .7)
    }

    .selected-head-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 13px;
        color: rgb(var(--color-slate-800))
    }

    .dark .selected-head-title {
        color: rgb(var(--color-slate-200))
    }

    .selected-count-pill {
        font-size: 11.5px;
        font-weight: 700;
        background: rgb(var(--color-primary) / .1);
        color: rgb(var(--color-primary));
        padding: 2px 8px;
        border-radius: 999px;
    }

    #selectedTreatmentsList {
        border: 1px dashed rgb(var(--color-slate-300) / .7);
        border-radius: 12px;
        margin-top: 12px;
        max-height: 300px;
        overflow-y: auto;
        scrollbar-width: thin
    }

    .dark #selectedTreatmentsList {
        border-color: rgb(var(--color-darkmode-400) / .7)
    }

    #selectedTreatmentsList::-webkit-scrollbar {
        width: 6px
    }

    #selectedTreatmentsList::-webkit-scrollbar-thumb {
        background: rgb(var(--color-slate-300));
        border-radius: 999px
    }

    .selected-item-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border-bottom: 1px dashed rgb(var(--color-slate-200) / .8)
    }

    .dark .selected-item-row {
        border-bottom-color: rgb(var(--color-darkmode-400) / .6)
    }

    .selected-item-row:last-child {
        border-bottom: none
    }

    .selected-item-name {
        font-weight: 600;
        font-size: 13px;
        color: rgb(var(--color-slate-800));
        flex: 1;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .dark .selected-item-name {
        color: rgb(var(--color-slate-200))
    }

    .selected-item-input-wrap {
        position: relative;
        flex: none
    }

    .selected-item-input {
        width: 84px;
        padding: 6px 26px 6px 8px;
        font-size: 12.5px;
        border: 1px solid rgb(var(--color-slate-300));
        border-radius: 8px;
        text-align: center;
        background: #fff;
        color: rgb(var(--color-slate-800))
    }

    .dark .selected-item-input {
        background: rgb(var(--color-darkmode-700));
        border-color: rgb(var(--color-darkmode-400));
        color: rgb(var(--color-slate-200))
    }

    .selected-item-input:focus {
        outline: none;
        border-color: rgb(var(--color-primary));
        box-shadow: 0 0 0 3px rgb(var(--color-primary) / .1)
    }

    .spin {
        position: absolute;
        right: 2px;
        top: 1px;
        bottom: 1px;
        width: 20px;
        display: flex;
        flex-direction: column
    }

    .spin button {
        flex: 1;
        border: 0;
        background: transparent;
        color: rgb(var(--color-slate-400));
        cursor: pointer;
        font-size: 10px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center
    }

    .spin button:hover {
        color: rgb(var(--color-primary))
    }

    .selected-item-price {
        font-size: 12.5px;
        font-weight: 700;
        color: rgb(var(--color-slate-800));
        min-width: 110px;
        text-align: right;
        flex: none
    }

    .dark .selected-item-price {
        color: rgb(var(--color-slate-200))
    }

    .selected-item-price small {
        display: block;
        font-size: 10.5px;
        font-weight: 500;
        color: rgb(var(--color-slate-400));
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

    /* ===== CUSTOM STEP ===== */
    .custom-step-wrap {
        margin-top: 14px;
        padding: 16px;
        border: 1px dashed rgb(var(--color-slate-300) / .7);
        border-radius: 12px;
        background: rgb(var(--color-slate-50));
        display: none
    }

    .dark .custom-step-wrap {
        background: rgb(var(--color-darkmode-700));
        border-color: rgb(var(--color-darkmode-400) / .7)
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
        border: 1px solid rgb(var(--color-slate-300));
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease
    }

    .dark .custom-step-field .input-group {
        background: rgb(var(--color-darkmode-700));
        border-color: rgb(var(--color-darkmode-400))
    }

    .custom-step-field .input-group:focus-within {
        border-color: rgb(var(--color-primary));
        box-shadow: 0 0 0 3px rgb(var(--color-primary) / .12)
    }

    .custom-step-field .input-group>* {
        border-radius: 0 !important;
        height: 100% !important;
        box-sizing: border-box
    }

    .custom-step-field .input-group .form-control {
        border: 0 !important;
        box-shadow: none !important;
        flex: 1;
        min-width: 0;
        background: transparent;
        color: rgb(var(--color-slate-800))
    }

    .dark .custom-step-field .input-group .form-control {
        color: rgb(var(--color-slate-200))
    }

    .custom-step-field .input-group .form-control:focus {
        box-shadow: none !important;
        border: 0 !important
    }

    .custom-step-field .input-group-text {
        background: rgb(var(--color-slate-50));
        color: rgb(var(--color-slate-500));
        font-size: 12.5px;
        font-weight: 600;
        padding: 0 12px;
        display: flex;
        align-items: center;
        white-space: nowrap;
        flex: none;
        border: 0
    }

    .dark .custom-step-field .input-group-text {
        background: rgb(var(--color-darkmode-800));
        color: rgb(var(--color-slate-400))
    }

    .custom-step-field .input-group-text:last-child {
        border-left: 1px solid rgb(var(--color-slate-200)) !important
    }

    .custom-step-field .input-group-text:first-child {
        border-right: 1px solid rgb(var(--color-slate-200)) !important
    }

    .dark .custom-step-field .input-group-text:last-child {
        border-left-color: rgb(var(--color-darkmode-400)) !important
    }

    .dark .custom-step-field .input-group-text:first-child {
        border-right-color: rgb(var(--color-darkmode-400)) !important
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
        color: rgb(var(--color-primary));
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 0
    }

    .custom-step-btn:hover {
        text-decoration: underline
    }

    /* ===== SEARCH + CATEGORY ===== */
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
        color: rgb(var(--color-slate-400));
        pointer-events: none
    }

    .search-wrap input.form-control {
        padding-left: 38px;
        border-radius: 10px
    }

    #categoryFilter.form-control {
        border-radius: 10px
    }

    /* ===== MEDIA ROW ===== */
    .media-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 16px;
        align-items: center;
        overflow: hidden;
        width: 100%;
        min-width: 0
    }

    .dropzone {
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1.5px dashed rgb(var(--color-slate-300));
        border-radius: 12px;
        padding: 14px 16px;
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease;
        background: rgb(var(--color-slate-50));
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
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
        background: rgb(var(--color-primary) / .03)
    }

    .dropzone-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        flex: none;
        background: rgb(var(--color-primary) / .1);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        color: rgb(var(--color-primary))
    }

    .dropzone-icon svg {
        width: 20px;
        height: 20px
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
        color: rgb(var(--color-slate-700));
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .dark .dropzone-title {
        color: rgb(var(--color-slate-300))
    }

    .dropzone-hint {
        font-size: 11.5px;
        color: rgb(var(--color-slate-400));
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
        color: rgb(var(--color-slate-500));
        white-space: nowrap
    }

    .dark .media-col-status .status-caption {
        color: rgb(var(--color-slate-400))
    }
</style>

{{-- TOOLBAR --}}
<div class="intro-y flex flex-wrap sm:flex-nowrap items-center gap-3 mt-8">
    <a href="{{ route('admin.packages.index') }}"
       class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors">
        <i data-feather="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h2 class="text-lg font-medium text-slate-800 dark:text-slate-200">Add Package</h2>
        <div class="text-slate-500 text-xs mt-0.5">Mix several treatments into one bundling package with a special price.</div>
    </div>
    <div class="flex items-center gap-2 sm:ml-auto mt-3 sm:mt-0">
        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" form="packageForm" id="saveBtn" class="btn btn-primary shadow-md" disabled>
            <i data-feather="check" class="w-4 h-4 mr-1"></i> Save Package
        </button>
    </div>
</div>

<form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" id="packageForm">
    @csrf

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- LEFT: DETAILS --}}
        <div class="intro-y col-span-12 lg:col-span-5">
            <div class="box">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                        <i data-feather="package" class="w-5 h-5"></i>
                    </div>
                    <h2 class="font-medium text-base">Package Details</h2>
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
                        <label class="form-label">Package Name</label>
                        <input type="text" name="name" id="pkgName" class="form-control w-full"
                               placeholder="e.g. Couples Escape"
                               value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Slug <span class="text-slate-500 font-normal text-xs">(auto from name, editable)</span></label>
                        <input type="text" name="slug" id="pkgSlug" class="form-control w-full"
                               placeholder="auto from name"
                               value="{{ old('slug') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Unit</label>
                        <select name="unit" class="form-select w-full">
                            <option value="person">Person</option>
                            <option value="couple">Couple</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Target Duration <span class="text-slate-500 font-normal text-xs">(total package processing time)</span></label>
                        <div class="duration-preset" id="durationPreset">
                            <button type="button" class="duration-chip" data-value="90">90m</button>
                            <button type="button" class="duration-chip" data-value="120">120m</button>
                            <button type="button" class="duration-chip" data-value="150">150m</button>
                            <button type="button" class="duration-chip" data-value="180">180m</button>
                            <button type="button" class="duration-chip" data-value="custom">Custom</button>
                        </div>
                        <input type="number" name="duration" id="packageDuration"
                               class="form-control w-full mt-3"
                               placeholder="Custom duration (mins)"
                               value="{{ old('duration') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Package Price <span class="text-slate-500 font-normal text-xs">(Rp)</span></label>
                        <input type="number" name="price" id="packagePrice"
                               class="form-control w-full"
                               placeholder="e.g. 299000"
                               value="{{ old('price') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tagline / Short Description</label>
                        <textarea name="description" class="form-control w-full"
                                  rows="2" maxlength="160"
                                  placeholder="e.g. A romantic escape designed for two.">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Package Image</label>
                        <div class="media-row">
                            <label for="imageInput" class="dropzone" id="dropzoneLabel">
                                <div class="dropzone-icon" id="dropzoneIcon"><i data-feather="image"></i></div>
                                <div class="dropzone-text">
                                    <span class="dropzone-title" id="dropzoneTitle">Click to upload image</span>
                                    <span class="dropzone-hint" id="dropzoneHint">PNG or JPG, max 2MB</span>
                                </div>
                            </label>
                            <input type="file" name="image" id="imageInput" accept="image/*" class="hidden-file-input">

                            <div class="media-col-status">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                                </div>
                                <span class="status-caption">Active</span>
                            <div class="form-check form-switch" style="margin-top: 8px;">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" class="form-check-input" value="1">
                                </div>
                                <span class="status-caption">Featured</span>
                                <div class="form-check form-switch" style="margin-top: 8px;">
                                    <input type="hidden" name="is_popular" value="0">
                                    <input type="checkbox" name="is_popular" class="form-check-input" value="1">
                                </div>
                                <span class="status-caption">Popular</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-slate-300/70 dark:border-darkmode-400/70 my-6"></div>

                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="calculator" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-medium text-base">Auto Summary</h3>
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

            {{-- SEO --}}
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

        {{-- RIGHT: TREATMENT SELECTOR --}}
        <div class="intro-y col-span-12 lg:col-span-7">
            <div class="box">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                        <i data-feather="check-square" class="w-5 h-5"></i>
                    </div>
                    <h2 class="font-medium text-base mr-auto">Treatment Selector</h2>
                    <span class="text-xs text-slate-400">Choose treatments to bundle</span>
                </div>

                <div class="p-5">

                    <div class="flex flex-col sm:flex-row gap-3 mb-5">
                        <div class="search-wrap">
                            <i data-feather="search" class="search-icon"></i>
                            <input type="text" id="treatmentSearch" class="form-control w-full" placeholder="Search treatment name...">
                        </div>

                        <select id="categoryFilter" class="form-select sm:w-48">
                            <option value="all">All Categories</option>
                            @foreach($treatments->pluck('category')->filter()->unique() as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="treatmentCatalog">
                        @foreach($treatments as $t)
                            @php
                                $variants = $t->prices;
                                $hasVariants = $variants->count() > 1;
                                $defaultVariant = $variants->sortBy('price')->first();
                            @endphp

                            <label class="flex items-start gap-3 px-4 py-4 cursor-pointer treatment-row"
                                   data-name="{{ strtolower($t->name) }}"
                                   data-category="{{ $t->category }}">

                                <input type="checkbox"
                                       class="form-check-input treatment-checkbox"
                                       data-id="{{ $t->id }}"
                                       data-name="{{ $t->name }}"
                                       data-duration="{{ $defaultVariant->duration ?? 0 }}"
                                       data-price="{{ $defaultVariant->price ?? 0 }}">

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="font-medium text-sm truncate text-slate-800 dark:text-slate-200">{{ $t->name }}</div>
                                        @if($hasVariants)
                                            <span class="variant-badge">{{ $variants->count() }} variants</span>
                                        @endif
                                    </div>

                                    @if($hasVariants)
                                        <select class="form-control form-control-sm w-full variant-select">
                                            @foreach($variants as $v)
                                                <option data-duration="{{ $v->duration }}" data-price="{{ $v->price }}">
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
                            <i data-feather="plus-circle" class="w-4 h-4"></i>
                            Add facility / custom step
                        </button>

                        <div class="custom-step-wrap" id="customStepWrap">
                            <div class="custom-step-grid">
                                <div class="custom-step-field">
                                    <input type="text" id="csName" class="form-control" placeholder="Facility name (e.g. Sauna)">
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
                                Leave price (0) empty if the facility is only a complement.
                            </div>
                        </div>
                    </div>

                    <div class="selected-head">
                        <div class="selected-head-title">
                            <span>Selected Items</span>
                            <span class="selected-count-pill" id="selectedCountLabel">0 items</span>
                        </div>
                        <span class="text-xs text-slate-400">Minimum 2 items to save</span>
                    </div>

                    <div id="selectedTreatmentsList">
                        <p class="text-slate-400 text-xs px-3 py-5 text-center" id="emptySelectedNote">
                            No items selected yet.
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

        // ===== Auto-fill slug dari name =====
        const pkgNameInput = document.getElementById('pkgName');
        const pkgSlugInput = document.getElementById('pkgSlug');
        let pkgSlugManual  = {{ old('slug') ? 'true' : 'false' }};

        pkgSlugInput.addEventListener('input', function () {
            pkgSlugManual = true;
        });

        pkgNameInput.addEventListener('input', function () {
            if (pkgSlugManual) return;
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
                    <div class="selected-item-name" title="${item.name}">${item.name}${item.is_custom ? ' <span style="color:rgb(var(--color-primary));font-size:10px">• custom</span>' : ''}</div>

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

        // ===== SELECTED LIST — CLICK (remove + spin) =====
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

        // ===== SELECTED LIST — INPUT DURATION =====
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

        // ===== SELECTED LIST — ARROW KEY =====
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

        // ===== DURATION PRESET CHIPS =====
        const customChip = durationPreset.querySelector('.duration-chip[data-value="custom"]');

        function setActiveChip(chip) {
            presetChips.forEach(c => c.classList.remove('active'));
            if (chip) chip.classList.add('active');
        }

        presetChips.forEach(chip => {
            chip.addEventListener('click', function () {
                const v = this.dataset.value;

                if (v !== 'custom') {
                    packageDuration.value = v;
                    setActiveChip(this);
                } else {
                    packageDuration.focus();
                    setActiveChip(this);
                }
                updateSummary();
            });
        });

        // Auto-sync: input manual → chip yang match otomatis nyala
        packageDuration.addEventListener('input', function () {
            const val = this.value.trim();
            const matchingChip = Array.from(presetChips).find(
                c => c.dataset.value !== 'custom' && c.dataset.value === val
            );

            if (matchingChip) {
                setActiveChip(matchingChip);
            } else {
                setActiveChip(customChip);
            }

            updateSummary();
        });

        packagePriceInput.addEventListener('input', updateSummary);

        // ===== CUSTOM STEP =====
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

        // ===== SEARCH + CATEGORY FILTER =====
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

        // ===== IMAGE PREVIEW =====
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

        // ===== INIT =====
        updateSummary();

        if (window.feather) feather.replace();
        setTimeout(() => { if (window.feather) feather.replace(); }, 100);
        setTimeout(() => { if (window.feather) feather.replace(); }, 300);
    });
</script>
@endsection
@extends('../layout/' . $layout)

@section('subhead')
    <title>Edit Blog Article - Icewall</title>
@endsection

@section('subcontent')

<style>
    /* ===== SOFT ALERT ===== */
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

    /* ===== EDITOR STYLING — FIX DARK MODE ===== */
    .np-editor-wrap {
        border: 1px solid rgb(var(--color-slate-300));
        border-radius: 12px;
        overflow: hidden;
        background: #fff
    }

    .dark .np-editor-wrap {
        background: rgb(var(--color-darkmode-700));
        border-color: rgb(var(--color-darkmode-400))
    }

    .np-editor-wrap .ck-editor__editable,
    .np-editor-wrap .editor {
        min-height: 420px;
        max-height: 700px;
        overflow-y: auto;
        font-size: 15px;
        line-height: 1.75;
        padding: 20px 24px !important;
        font-family: inherit;
        color: #334155 !important;
        background: #fff !important;
    }

    .np-editor-wrap .ck-editor__editable *,
    .np-editor-wrap .editor * {
        color: #334155;
    }

    .np-editor-wrap .ck-editor__editable h1,
    .np-editor-wrap .editor h1 {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.25;
        color: #0f172a !important;
        margin: 28px 0 14px;
        letter-spacing: -.5px;
        border-bottom: 1px solid rgba(148, 163, 184, .2);
        padding-bottom: 10px
    }

    .np-editor-wrap .ck-editor__editable h2,
    .np-editor-wrap .editor h2 {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.3;
        color: #1e293b !important;
        margin: 24px 0 12px
    }

    .np-editor-wrap .ck-editor__editable h3,
    .np-editor-wrap .editor h3 {
        font-size: 1.2rem;
        font-weight: 600;
        line-height: 1.4;
        color: #334155 !important;
        margin: 20px 0 10px
    }

    .np-editor-wrap .ck-editor__editable h4,
    .np-editor-wrap .editor h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #475569 !important;
        margin: 16px 0 8px;
        text-transform: uppercase;
        letter-spacing: .5px
    }

    .np-editor-wrap .ck-editor__editable p,
    .np-editor-wrap .editor p {
        margin: 0 0 14px
    }

    .np-editor-wrap .ck-editor__editable ul,
    .np-editor-wrap .editor ul,
    .np-editor-wrap .ck-editor__editable ol,
    .np-editor-wrap .editor ol {
        margin: 0 0 14px 22px;
        padding: 0
    }

    .np-editor-wrap .ck-editor__editable li,
    .np-editor-wrap .editor li {
        margin-bottom: 6px
    }

    .np-editor-wrap .ck-editor__editable blockquote,
    .np-editor-wrap .editor blockquote {
        background: rgb(var(--color-slate-100));
        border-left: 3px solid rgb(var(--color-primary));
        border-radius: 6px;
        margin: 18px 0;
        padding: 14px 20px;
        font-style: italic;
        color: rgb(var(--color-slate-700)) !important
    }

    .np-editor-wrap .ck-editor__editable a,
    .np-editor-wrap .editor a {
        color: rgb(var(--color-primary)) !important;
        text-decoration: underline
    }

    .np-editor-wrap .ck-editor__editable .np-emoji,
    .np-editor-wrap .editor .np-emoji {
        font-size: 1.15em;
        line-height: 1;
        margin-right: 4px;
        vertical-align: middle;
        display: inline-block;
    }

    .np-editor-wrap .ck-toolbar {
        border: none !important;
        border-bottom: 1px solid rgb(var(--color-slate-200)) !important;
        background: rgb(var(--color-slate-50)) !important;
    }

    .dark .np-editor-wrap .ck-toolbar {
        background: rgb(var(--color-darkmode-800)) !important;
        border-bottom-color: rgb(var(--color-darkmode-400)) !important;
    }

    .np-editor-wrap .ck-editor__main>.ck-editor__editable {
        border: none !important
    }

    .np-editor-wrap .ck.ck-editor__editable.ck-focused {
        box-shadow: none !important;
        border: none !important
    }

    .ck.ck-heading-dropdown .ck-heading_heading1 {
        font-size: 1.6rem;
        font-weight: 700
    }

    .ck.ck-heading-dropdown .ck-heading_heading2 {
        font-size: 1.3rem;
        font-weight: 700
    }

    .ck.ck-heading-dropdown .ck-heading_heading3 {
        font-size: 1.1rem;
        font-weight: 600
    }

    .ck.ck-heading-dropdown .ck-heading_paragraph {
        font-size: 0.95rem
    }

    /* ===== EDITOR ACTION BUTTONS ===== */
    .np-editor-actions {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap
    }

    .np-editor-actions button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        background: rgb(var(--color-slate-100));
        color: rgb(var(--color-slate-600));
        border: 1px solid rgb(var(--color-slate-300));
        cursor: pointer;
        transition: all .15s;
    }

    .dark .np-editor-actions button {
        background: rgb(var(--color-darkmode-700));
        color: rgb(var(--color-slate-300));
        border-color: rgb(var(--color-darkmode-400));
    }

    .np-editor-actions button:hover {
        background: rgb(var(--color-slate-200));
        border-color: rgb(var(--color-primary));
        color: rgb(var(--color-primary));
    }

    .dark .np-editor-actions button:hover {
        background: rgb(var(--color-darkmode-600))
    }

    .np-editor-actions svg {
        width: 14px;
        height: 14px
    }

    /* ===== EMOJI MODAL ===== */
    .np-emoji-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, .75);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        backdrop-filter: blur(4px);
    }

    .np-emoji-modal.show {
        display: flex
    }

    .np-emoji-modal-inner {
        background: #fff;
        border-radius: 16px;
        max-width: 640px;
        width: 100%;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px rgba(0, 0, 0, .35);
        overflow: hidden;
    }

    .dark .np-emoji-modal-inner {
        background: rgb(var(--color-darkmode-700));
        color: rgb(var(--color-slate-200))
    }

    .np-emoji-modal-head {
        padding: 16px 22px;
        border-bottom: 1px solid rgb(var(--color-slate-200));
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dark .np-emoji-modal-head {
        border-bottom-color: rgb(var(--color-darkmode-400))
    }

    .np-emoji-modal-head h3 {
        font-size: 14px;
        font-weight: 600;
        color: rgb(var(--color-slate-800));
        margin: 0
    }

    .dark .np-emoji-modal-head h3 {
        color: rgb(var(--color-slate-200))
    }

    .np-emoji-modal-head .close {
        margin-left: auto;
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: rgb(var(--color-slate-100));
        border: none;
        cursor: pointer;
        color: rgb(var(--color-slate-600));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dark .np-emoji-modal-head .close {
        background: rgb(var(--color-darkmode-600));
        color: rgb(var(--color-slate-300))
    }

    .np-emoji-modal-head .close:hover {
        background: rgb(var(--color-slate-200))
    }

    .np-emoji-search {
        padding: 12px 22px;
        border-bottom: 1px solid rgb(var(--color-slate-200))
    }

    .dark .np-emoji-search {
        border-bottom-color: rgb(var(--color-darkmode-400))
    }

    .np-emoji-search input {
        width: 100%;
        border-radius: 8px;
        border: 1px solid rgb(var(--color-slate-300));
        padding: 8px 12px;
        font-size: 13px;
        outline: none;
        background: #fff;
        color: rgb(var(--color-slate-800));
    }

    .dark .np-emoji-search input {
        background: rgb(var(--color-darkmode-600));
        border-color: rgb(var(--color-darkmode-400));
        color: rgb(var(--color-slate-200));
    }

    .np-emoji-search input:focus {
        border-color: rgb(var(--color-primary));
        box-shadow: 0 0 0 3px rgb(var(--color-primary) / .12);
    }

    .np-emoji-body {
        padding: 16px 22px;
        overflow-y: auto
    }

    .np-emoji-cat-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: rgb(var(--color-slate-500));
        margin: 16px 0 10px
    }

    .np-emoji-cat-title:first-child {
        margin-top: 0
    }

    .np-emoji-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(52px, 1fr));
        gap: 6px
    }

    .np-emoji-item {
        display: flex;
        align-items: center;
        justify-content: center;
        aspect-ratio: 1;
        border-radius: 10px;
        background: rgb(var(--color-slate-50));
        border: 1px solid rgb(var(--color-slate-200));
        cursor: pointer;
        transition: all .15s;
        font-size: 26px;
        line-height: 1;
        padding: 0;
    }

    .dark .np-emoji-item {
        background: rgb(var(--color-darkmode-800));
        border-color: rgb(var(--color-darkmode-400));
    }

    .np-emoji-item:hover {
        background: #fff;
        border-color: rgb(var(--color-primary));
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgb(var(--color-primary) / .2);
    }

    .dark .np-emoji-item:hover {
        background: rgb(var(--color-darkmode-600))
    }

    .np-emoji-empty {
        text-align: center;
        padding: 40px 20px;
        color: rgb(var(--color-slate-500));
        font-size: 13px;
        font-style: italic
    }

    /* ===== SLUG BOX ===== */
    .np-slug-box {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 10px;
        background: rgb(var(--color-slate-100));
        border: 1px dashed rgb(var(--color-slate-300));
        font-size: 12.5px;
        color: rgb(var(--color-slate-500));
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .dark .np-slug-box {
        background: rgb(var(--color-darkmode-700));
        border-color: rgb(var(--color-darkmode-400));
        color: rgb(var(--color-slate-400));
    }

    .np-slug-box svg {
        width: 14px;
        height: 14px;
        color: rgb(var(--color-slate-400));
        flex: none
    }

    .np-slug-box .url {
        color: rgb(var(--color-primary));
        font-weight: 500;
        word-break: break-all
    }

    /* ===== CHIPS ===== */
    .np-chips {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 12px
    }

    .np-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 8px;
        background: rgb(var(--color-slate-100));
        color: rgb(var(--color-slate-600));
        font-size: 11.5px;
        font-weight: 500;
    }

    .dark .np-chip {
        background: rgb(var(--color-darkmode-700));
        color: rgb(var(--color-slate-300));
    }

    .np-chip svg {
        width: 12px;
        height: 12px
    }

    .np-chip .kw-remove {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-left: 2px;
        opacity: .6;
        border: none;
        background: none;
        padding: 0;
        color: inherit;
    }

    .np-chip .kw-remove:hover {
        opacity: 1;
        color: #ef4444
    }

    .np-chip .kw-remove svg {
        pointer-events: none
    }

    /* ===== IMAGE GRID ===== */
    .np-img-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px
    }

    .np-img-grid:empty {
        display: none
    }

    .np-img-item {
        width: 96px;
        height: 96px;
        border-radius: 10px;
        position: relative;
        overflow: visible;
        border: 1px solid rgb(var(--color-slate-300));
    }

    .dark .np-img-item {
        border-color: rgb(var(--color-darkmode-400))
    }

    .np-img-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 9px
    }

    .np-img-item .remove {
        position: absolute;
        top: -7px;
        right: -7px;
        width: 22px;
        height: 22px;
        border-radius: 9999px;
        background: #ef4444;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(239, 68, 68, .4);
        cursor: pointer;
        border: none;
        padding: 0;
        z-index: 2;
    }

    .np-img-item .remove svg {
        width: 12px;
        height: 12px;
        pointer-events: none
    }

    /* ===== UPLOAD ZONE ===== */
    .np-upload-zone {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 16px;
        border-radius: 10px;
        border: 2px dashed rgb(var(--color-slate-300));
        background: rgb(var(--color-slate-50));
        cursor: pointer;
        font-size: 13px;
        color: rgb(var(--color-slate-500));
        transition: all .2s;
    }

    .dark .np-upload-zone {
        background: rgb(var(--color-darkmode-700));
        border-color: rgb(var(--color-darkmode-400));
        color: rgb(var(--color-slate-400));
    }

    .np-upload-zone:hover {
        border-color: rgb(var(--color-primary));
        background: rgb(var(--color-primary) / .03);
    }

    .np-upload-zone svg {
        width: 16px;
        height: 16px;
        flex: none
    }

    .np-upload-zone .text-primary {
        color: rgb(var(--color-primary));
        font-weight: 600
    }

    .np-hidden-file {
        position: absolute;
        left: -9999px;
        width: 1px;
        height: 1px;
        opacity: 0;
        overflow: hidden
    }

    /* ===== TAB ACTIVE STATE ===== */
    .np-tab-pane {
        display: none
    }

    .np-tab-pane.active {
        display: block
    }

    .np-tab.active {
        background: rgb(var(--color-primary) / .08) !important;
        color: rgb(var(--color-primary)) !important;
    }
</style>

{{-- ============================================
     TOOLBAR
============================================ --}}
<div class="intro-y flex items-center mt-10">
    <h2 class="text-lg font-medium mr-auto">Edit Blog Article</h2>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
            <i data-feather="arrow-left" class="w-4 h-4 mr-1"></i> Back
        </a>
        <button type="submit" form="blogForm" class="btn btn-primary">
            <i data-feather="check-circle" class="w-4 h-4 mr-1"></i> Update Article
        </button>
    </div>
</div>

{{-- ALERTS --}}
@if ($errors->any())
    <div class="intro-y mt-5">
        <div class="soft-alert soft-alert-danger">
            <i data-feather="alert-circle"></i>
            <div>
                <strong>There are {{ $errors->count() }} error(s):</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- FORM --}}
<form action="{{ route('admin.blogs.update', $blog) }}"
      method="POST"
      enctype="multipart/form-data"
      id="blogForm">

    @csrf
    @method('PUT')
    <textarea name="content" id="contentField" class="hidden">{{ old('content', $blog->content) }}</textarea>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- LEFT: MAIN --}}
        <div class="intro-y col-span-12 lg:col-span-8">

            {{-- TITLE --}}
            <div class="box">
                <div class="p-5">
                    <div class="mb-3">
                        <label class="form-label">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}"
                               class="form-control form-control-lg w-full"
                               placeholder="Write a catchy title..." required>
                    </div>
                    <div class="np-slug-box">
                        <i data-feather="link"></i>
                        <span>{{ url('/blog') }}/</span>
                        <span class="url" id="slugPreview">{{ $blog->slug }}</span>
                    </div>
                    <input type="hidden" name="slug" id="slug" value="{{ old('slug', $blog->slug) }}">
                </div>
            </div>

            {{-- TABS CARD --}}
            <div class="box mt-5">

                {{-- TABS --}}
                <div class="flex gap-1 p-2 border-b border-slate-200/60 dark:border-darkmode-400 overflow-x-auto" id="npTabs">
                    <button type="button" class="btn btn-outline-secondary border-0 shadow-none np-tab active" data-tab="content">
                        <i data-feather="file-text" class="w-4 h-4 mr-1"></i> Content
                    </button>
                    <button type="button" class="btn btn-outline-secondary border-0 shadow-none np-tab" data-tab="media">
                        <i data-feather="image" class="w-4 h-4 mr-1"></i> Caption & Images
                    </button>
                    <button type="button" class="btn btn-outline-secondary border-0 shadow-none np-tab" data-tab="meta">
                        <i data-feather="search" class="w-4 h-4 mr-1"></i> Meta
                    </button>
                    <button type="button" class="btn btn-outline-secondary border-0 shadow-none np-tab" data-tab="keywords">
                        <i data-feather="tag" class="w-4 h-4 mr-1"></i> Keywords
                    </button>
                </div>

                {{-- TAB: CONTENT --}}
                <div class="np-tab-pane active p-5" data-pane="content">
                    <div class="mb-4">
                        <label class="form-label">Article Content <span class="text-danger">*</span></label>
                        <div class="np-editor-wrap" id="editorWrap">
                            <div class="editor" data-placeholder="Write your article content here...">{!! old('content', $blog->content) !!}</div>
                        </div>

                        <div class="np-editor-actions">
                            <button type="button" id="insertEmojiBtn">
                                <i data-feather="smile"></i> Insert Emoji
                            </button>
                            <button type="button" id="insertHeadingEmojiBtn">
                                <i data-feather="star"></i> Emoji + Heading
                            </button>
                        </div>

                        <div class="np-chips">
                            <span class="np-chip"><i data-feather="file-text"></i> <span id="wordCount">0</span> words</span>
                            <span class="np-chip"><i data-feather="clock"></i> <span id="readTime">1</span> min read</span>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">
                            Excerpt
                            <span class="text-slate-500 font-normal text-xs">(short summary, max 210 characters)</span>
                        </label>
                        <textarea name="excerpt" id="excerpt" class="form-control w-full" maxlength="210" rows="4" placeholder="Short summary of the article for preview & SEO...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                    </div>
                </div>

                {{-- TAB: MEDIA --}}
                <div class="np-tab-pane p-5" data-pane="media">
                    <div class="mb-4">
                        <label class="form-label">Caption</label>
                        <input type="text" name="caption" value="{{ old('caption', $blog->caption) }}" class="form-control w-full" placeholder="Write a caption for the main image...">
                    </div>
                    <div>
                        <label class="form-label">
                            Gallery Images
                            <span class="text-slate-500 font-normal text-xs">(appears in the middle of the article, after the first heading)</span>
                        </label>
                        <div class="np-img-grid mb-3" id="galleryGrid">
                            @if($blog->gallery && is_array($blog->gallery))
                                @foreach($blog->gallery as $img)
                                    <div class="np-img-item">
                                        <img src="{{ asset('storage/'.$img) }}" alt="gallery">
                                        <button type="button" class="remove"><i data-feather="x"></i></button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <label for="galleryInput" class="np-upload-zone">
                            <i data-feather="image"></i>
                            <span><span class="text-primary">Upload files</span> or drag and drop here</span>
                            <input type="file" name="gallery[]" id="galleryInput" accept="image/*" multiple class="np-hidden-file">
                        </label>
                    </div>
                </div>

                {{-- TAB: META --}}
                <div class="np-tab-pane p-5" data-pane="meta">
                    <div class="mb-4">
                        <label class="form-label">
                            Meta Title
                            <span class="text-slate-500 font-normal text-xs">(empty = auto from title)</span>
                        </label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" class="form-control w-full" maxlength="255" placeholder="Meta title for search engines...">
                    </div>
                    <div>
                        <label class="form-label">
                            Meta Description
                            <span class="text-slate-500 font-normal text-xs">(max 500 characters)</span>
                        </label>
                        <textarea name="meta_description" class="form-control w-full" rows="4" maxlength="500" placeholder="Short description for Google search results...">{{ old('meta_description', $blog->meta_description) }}</textarea>
                    </div>
                </div>

                {{-- TAB: KEYWORDS --}}
                <div class="np-tab-pane p-5" data-pane="keywords">
                    <div>
                        <label class="form-label">
                            Keywords
                            <span class="text-slate-500 font-normal text-xs">(type and press Enter / comma)</span>
                        </label>
                        <input type="text" id="keywordsInput" class="form-control w-full" placeholder="wellness, spa, relaxation...">
                        <input type="hidden" name="keywords" id="keywordsHidden" value="{{ old('keywords', $blog->keywords) }}">
                        <div class="np-chips" id="keywordsChips"></div>
                        <div class="text-slate-500 text-xs mt-2">
                            These keywords will appear as <strong>tags</strong> on the user's blog detail page.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- RIGHT: SIDE --}}
        <div class="intro-y col-span-12 lg:col-span-4">

            {{-- AUTHOR --}}
            <div class="box">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <i data-feather="user" class="w-5 h-5 text-primary mr-3"></i>
                    <h2 class="font-medium text-base">Written By</h2>
                </div>
                <div class="p-5">
                    <label class="form-label">Author Name</label>
                    <input type="text" name="author_name"
                           value="{{ old('author_name', $blog->author_name ?? auth()->user()->name ?? 'Admin') }}"
                           class="form-control w-full" placeholder="Author name..." maxlength="255">
                </div>
            </div>

            {{-- THUMBNAIL --}}
            <div class="box mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <i data-feather="image" class="w-5 h-5 text-primary mr-3"></i>
                    <h2 class="font-medium text-base">Thumbnail</h2>
                </div>
                <div class="p-5">
                    <label for="imageInput" class="np-dropzone {{ $blog->image ? 'has-file' : '' }}" id="dropzoneLabel">
                        <input type="file" name="image" id="imageInput" accept="image/*" class="np-hidden-file">
                        <div id="dropzonePlaceholder" style="display:{{ $blog->image ? 'none' : 'flex' }};flex-direction:column;align-items:center;justify-content:center;width:100%">
                            <div class="icon"><i data-feather="upload-cloud"></i></div>
                            <div class="dz-title">Upload thumbnail</div>
                            <div class="dz-hint">PNG, JPG, WEBP · Max 4MB</div>
                        </div>
                        @if($blog->image)
                            <img src="{{ asset('storage/'.$blog->image) }}" alt="preview">
                        @endif
                    </label>
                </div>
            </div>

            {{-- DETAILS --}}
            <div class="box mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <i data-feather="settings" class="w-5 h-5 text-primary mr-3"></i>
                    <h2 class="font-medium text-base">Details</h2>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select w-full">
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $blog->sort_order) }}" class="form-control w-full" min="0">
                    </div>
                    <div>
                        <label class="form-label">
                            Publish Date
                            <span class="text-slate-500 font-normal text-xs">(optional)</span>
                        </label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}" class="form-control w-full">
                    </div>
                </div>
            </div>

            {{-- STATUS --}}
            <div class="box mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <i data-feather="toggle-right" class="w-5 h-5 text-primary mr-3"></i>
                    <h2 class="font-medium text-base">Status</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between gap-4 pb-4 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div>
                            <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">Publish</div>
                            <div class="text-xs text-slate-500 mt-0.5">Display article on blog</div>
                        </div>
                        <div class="form-check form-switch flex-none">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" class="form-check-input"
                                   id="isPublishedSwitch" {{ old('is_published', $blog->is_published) ? 'checked' : '' }} value="1">
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-4 pt-4">
                        <div>
                            <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">Show Author</div>
                            <div class="text-xs text-slate-500 mt-0.5">Display author name</div>
                        </div>
                        <div class="form-check form-switch flex-none">
                            <input type="hidden" name="show_author" value="0">
                            <input type="checkbox" name="show_author" class="form-check-input"
                                   id="showAuthorSwitch" {{ old('show_author', $blog->show_author ?? true) ? 'checked' : '' }} value="1">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

{{-- EMOJI PICKER MODAL --}}
<div class="np-emoji-modal" id="emojiPickerModal">
    <div class="np-emoji-modal-inner">
        <div class="np-emoji-modal-head">
            <i data-feather="smile" class="w-4 h-4" style="color:rgb(var(--color-slate-500));"></i>
            <h3>Choose Emoji</h3>
            <button type="button" class="close" id="closeEmojiPicker">
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="np-emoji-search">
            <input type="text" id="emojiSearchInput" placeholder="Search emoji... (e.g., leaf, water, heart, spa)">
        </div>
        <div class="np-emoji-body" id="emojiBody"></div>
    </div>
</div>

@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/29.2.0/classic/ckeditor.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    const NP_EMOJI_CATEGORIES = {
        'Wellness & Nature': [
            { emoji: '🌿', keywords: 'leaf herbal green nature' },
            { emoji: '🍃', keywords: 'leaf wind natural' },
            { emoji: '🌱', keywords: 'seedling sprout grow' },
            { emoji: '🌸', keywords: 'flower blossom cherry' },
            { emoji: '🌺', keywords: 'flower hibiscus tropical' },
            { emoji: '🌷', keywords: 'tulip flower' },
            { emoji: '🌼', keywords: 'flower daisy' },
            { emoji: '💐', keywords: 'bouquet flower' },
            { emoji: '🌳', keywords: 'tree nature forest' },
            { emoji: '🌲', keywords: 'tree evergreen pine' },
            { emoji: '🍀', keywords: 'clover luck green' },
            { emoji: '🌾', keywords: 'grain rice plant' }
        ],
        'Water & Oil': [
            { emoji: '💧', keywords: 'droplet water drop' },
            { emoji: '💦', keywords: 'sweat water drops' },
            { emoji: '🌊', keywords: 'wave ocean water' },
            { emoji: '🫧', keywords: 'bubbles soap foam' },
            { emoji: '🧴', keywords: 'lotion bottle cream' },
            { emoji: '🧼', keywords: 'soap bar clean' },
            { emoji: '🪷', keywords: 'lotus flower water' }
        ],
        'Spa & Relaxation': [
            { emoji: '♨️', keywords: 'hot spring spa onsen' },
            { emoji: '🧖', keywords: 'sauna steam relax' },
            { emoji: '💆', keywords: 'massage head relax' },
            { emoji: '💆‍♀️', keywords: 'massage woman relax' },
            { emoji: '🧘', keywords: 'yoga meditate zen' },
            { emoji: '🧘‍♀️', keywords: 'yoga woman meditate' },
            { emoji: '🛀', keywords: 'bath bathtub relax' },
            { emoji: '🕯️', keywords: 'candle light relax' }
        ],
        'Beauty & Care': [
            { emoji: '✨', keywords: 'sparkle shine glow' },
            { emoji: '💫', keywords: 'star sparkle' },
            { emoji: '💅', keywords: 'nail polish manicure' },
            { emoji: '💄', keywords: 'lipstick makeup' },
            { emoji: '🪞', keywords: 'mirror beauty' },
            { emoji: '🪥', keywords: 'toothbrush clean' }
        ],
        'Heart & Love': [
            { emoji: '💚', keywords: 'green heart' },
            { emoji: '💖', keywords: 'sparkle heart pink' },
            { emoji: '💝', keywords: 'heart gift love' },
            { emoji: '💗', keywords: 'heart growing pink' },
            { emoji: '🤍', keywords: 'white heart' }
        ],
        'Fruit & Vitamin': [
            { emoji: '🍋', keywords: 'lemon citrus vitamin' },
            { emoji: '🍊', keywords: 'orange citrus' },
            { emoji: '🥥', keywords: 'coconut tropical' },
            { emoji: '🍓', keywords: 'strawberry berry' },
            { emoji: '🫐', keywords: 'blueberry berry' },
            { emoji: '🍯', keywords: 'honey sweet' },
            { emoji: '🥑', keywords: 'avocado healthy' }
        ],
        'Sun & Light': [
            { emoji: '☀️', keywords: 'sun bright light' },
            { emoji: '🌞', keywords: 'sun face smile' },
            { emoji: '🌤️', keywords: 'sun cloud' },
            { emoji: '⭐', keywords: 'star' },
            { emoji: '🌟', keywords: 'glow star sparkle' },
            { emoji: '🌙', keywords: 'moon night' }
        ],
        'Misc': [
            { emoji: '☕', keywords: 'coffee tea drink' },
            { emoji: '🍵', keywords: 'tea matcha drink' },
            { emoji: '🎁', keywords: 'gift present' },
            { emoji: '🎀', keywords: 'ribbon bow' },
            { emoji: '💎', keywords: 'gem diamond' },
            { emoji: '🕊️', keywords: 'dove peace bird' }
        ]
    };

    function renderEmojiPicker() {
        const body = document.getElementById('emojiBody');
        const search = document.getElementById('emojiSearchInput');
        if (!body) return;

        function draw(filter) {
            const q = (filter || '').toLowerCase().trim();
            body.innerHTML = '';
            let found = 0;

            Object.keys(NP_EMOJI_CATEGORIES).forEach(function(catName) {
                const items = NP_EMOJI_CATEGORIES[catName].filter(function(item) {
                    if (!q) return true;
                    return item.keywords.toLowerCase().includes(q) || item.emoji.includes(q);
                });
                if (!items.length) return;
                found += items.length;

                const title = document.createElement('div');
                title.className = 'np-emoji-cat-title';
                title.textContent = catName;
                body.appendChild(title);

                const grid = document.createElement('div');
                grid.className = 'np-emoji-grid';
                items.forEach(function(item) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'np-emoji-item';
                    btn.textContent = item.emoji;
                    btn.dataset.emoji = item.emoji;
                    btn.title = item.keywords;
                    grid.appendChild(btn);
                });
                body.appendChild(grid);
            });

            if (!found) {
                body.innerHTML = '<div class="np-emoji-empty">Emoji not found. Try another word.</div>';
            }
        }

        draw('');
        if (search && !search.dataset.bound) {
            search.dataset.bound = '1';
            search.addEventListener('input', function () { draw(this.value); });
        }
        if (!body.dataset.bound) {
            body.dataset.bound = '1';
            body.addEventListener('click', function(e) {
                const item = e.target.closest('.np-emoji-item');
                if (!item) return;
                const emoji = item.dataset.emoji;
                if (emoji && typeof window.npInsertEmoji === 'function') {
                    window.npInsertEmoji(emoji);
                }
            });
        }
    }

    window.npInsertEmoji = function(emoji) {
        if (window.editor && window.editor.model) {
            window.editor.model.change(function(writer) {
                const html = '<span class="np-emoji">' + emoji + '</span>&nbsp;';
                const viewFragment = window.editor.data.processor.toView(html);
                const modelFragment = window.editor.data.toModel(viewFragment);
                window.editor.model.insertContent(modelFragment, window.editor.model.document.selection);
            });
        } else {
            alert('Editor not ready yet. Please try again in a moment.');
        }
        closeEmojiPicker();
    };

    window.npInsertHeadingWithEmoji = function(emoji) {
        if (window.editor && window.editor.model) {
            window.editor.model.change(function(writer) {
                const html = '<h3><span class="np-emoji">' + emoji + '</span> New Title</h3>';
                const viewFragment = window.editor.data.processor.toView(html);
                const modelFragment = window.editor.data.toModel(viewFragment);
                window.editor.model.insertContent(modelFragment, window.editor.model.document.selection);
            });
        } else {
            alert('Editor not ready yet. Please try again in a moment.');
        }
        closeEmojiPicker();
    };

    function openEmojiPicker(mode) {
        const modal = document.getElementById('emojiPickerModal');
        if (!modal) return;
        window.__emojiMode = mode || 'insert';
        modal.classList.add('show');
        renderEmojiPicker();
    }

    function closeEmojiPicker() {
        const modal = document.getElementById('emojiPickerModal');
        if (modal) modal.classList.remove('show');
    }

    function npInit() {
        const root = document.getElementById('blogForm');
        if (!root || root.dataset.npReady === '1') return;
        root.dataset.npReady = '1';

        if (window.feather) window.feather.replace();

        /* TABS */
        const npTabs = document.getElementById('npTabs');
        if (npTabs) {
            npTabs.addEventListener('click', function (e) {
                const btn = e.target.closest('.np-tab');
                if (!btn) return;
                e.preventDefault();
                const key = btn.getAttribute('data-tab');
                const card = btn.closest('.box');
                if (!card || !key) return;
                card.querySelectorAll('.np-tab').forEach(t => t.classList.remove('active'));
                card.querySelectorAll('.np-tab-pane').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                const pane = card.querySelector('.np-tab-pane[data-pane="' + key + '"]');
                if (pane) pane.classList.add('active');
            });
        }

        /* SLUG — edit: manual = true */
        const titleInput  = document.getElementById('title');
        const slugInput   = document.getElementById('slug');
        const slugPreview = document.getElementById('slugPreview');
        let slugManual = true;

        function makeSlug(str) {
            return String(str || '').toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
        function updateSlugPreview() {
            if (!slugPreview) return;
            const v = (slugInput && slugInput.value) || makeSlug(titleInput && titleInput.value);
            slugPreview.textContent = v || 'your-article-slug';
        }
        if (titleInput) titleInput.addEventListener('input', function () {
            if (!slugManual && slugInput) slugInput.value = makeSlug(titleInput.value);
            updateSlugPreview();
        });
        if (slugInput) slugInput.addEventListener('input', function () {
            slugManual = true; updateSlugPreview();
        });
        updateSlugPreview();

        /* EDITOR */
        const form = document.getElementById('blogForm');
        const contentField = document.getElementById('contentField');
        const wordCountEl  = document.getElementById('wordCount');
        const readTimeEl   = document.getElementById('readTime');
        const editorEl     = document.querySelector('.editor');

        function updateStats(html) {
            const text = String(html || '').replace(/<[^>]*>/g, ' ').trim();
            const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
            if (wordCountEl) wordCountEl.textContent = words.toLocaleString('id-ID');
            if (readTimeEl)  readTimeEl.textContent  = Math.max(1, Math.round(words / 200));
        }

        if (editorEl) {
            if (window.ClassicEditor) {
                window.ClassicEditor
                    .create(editorEl, {
                        placeholder: 'Write your article content here...',
                        heading: {
                            options: [
                                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                            ]
                        },
                        toolbar: [
                            'heading','|',
                            'bold','italic','link','|',
                            'bulletedList','numberedList','blockQuote','|',
                            'alignment','|',
                            'undo','redo'
                        ]
                    })
                    .then(editor => {
                        window.editor = editor;
                        updateStats(editor.getData());
                        editor.model.document.on('change:data', function () {
                            updateStats(editor.getData());
                        });
                    })
                    .catch(err => {
                        console.warn('[NP] CKEditor gagal, fallback:', err);
                        editorEl.setAttribute('contenteditable', 'true');
                        updateStats(editorEl.innerHTML);
                        editorEl.addEventListener('input', function () {
                            updateStats(editorEl.innerHTML);
                            contentField.value = editorEl.innerHTML;
                        });
                    });
            } else {
                editorEl.setAttribute('contenteditable', 'true');
                updateStats(editorEl.innerHTML);
                editorEl.addEventListener('input', function () {
                    updateStats(editorEl.innerHTML);
                    contentField.value = editorEl.innerHTML;
                });
            }
        }

        if (form) {
            form.addEventListener('submit', function () {
                if (window.editor && typeof window.editor.getData === 'function') {
                    contentField.value = window.editor.getData();
                } else if (editorEl) {
                    contentField.value = editorEl.innerHTML;
                }
            });
        }

        /* THUMBNAIL */
        const imageInput    = document.getElementById('imageInput');
        const dropzoneLabel = document.getElementById('dropzoneLabel');
        const placeholder   = document.getElementById('dropzonePlaceholder');
        if (imageInput) {
            imageInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;
                if (file.size > 4 * 1024 * 1024) { alert('Max 4MB'); this.value=''; return; }
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (dropzoneLabel) {
                        dropzoneLabel.classList.add('has-file');
                        if (placeholder) placeholder.style.display = 'none';
                        dropzoneLabel.querySelectorAll('img').forEach(i => i.remove());
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        dropzoneLabel.appendChild(img);
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        /* GALLERY */
        const galleryInput = document.getElementById('galleryInput');
        const galleryGrid  = document.getElementById('galleryGrid');
        if (galleryInput && galleryGrid) {
            galleryInput.addEventListener('change', function () {
                Array.from(this.files || []).forEach(file => {
                    if (file.size > 4 * 1024 * 1024) return;
                    const reader = new FileReader();
                    reader.onload = e => {
                        const item = document.createElement('div');
                        item.className = 'np-img-item';
                        item.innerHTML = '<img src="' + e.target.result + '" alt="gallery">' +
                                        '<button type="button" class="remove"><i data-feather="x"></i></button>';
                        galleryGrid.appendChild(item);
                        if (window.feather) window.feather.replace();
                    };
                    reader.readAsDataURL(file);
                });
            });
            galleryGrid.addEventListener('click', function (e) {
                const btn = e.target.closest('.remove');
                if (!btn) return;
                e.preventDefault();
                const item = btn.closest('.np-img-item');
                if (item) item.remove();
            });
        }

        /* KEYWORDS */
        const kwInput  = document.getElementById('keywordsInput');
        const kwHidden = document.getElementById('keywordsHidden');
        const kwChips  = document.getElementById('keywordsChips');
        let keywords = (kwHidden && kwHidden.value)
            ? kwHidden.value.split(',').map(s => s.trim()).filter(Boolean)
            : [];

        function renderKeywords() {
            if (!kwChips) return;
            kwChips.innerHTML = '';
            keywords.forEach((kw, i) => {
                const chip = document.createElement('span');
                chip.className = 'np-chip';
                chip.innerHTML = '<span>' + kw + '</span>' +
                                '<button type="button" class="kw-remove" data-index="' + i + '"><i data-feather="x"></i></button>';
                kwChips.appendChild(chip);
            });
            if (window.feather) window.feather.replace();
        }
        if (kwChips) {
            kwChips.addEventListener('click', function (e) {
                const btn = e.target.closest('.kw-remove');
                if (!btn) return;
                e.preventDefault();
                keywords.splice(parseInt(btn.getAttribute('data-index')), 1);
                if (kwHidden) kwHidden.value = keywords.join(',');
                renderKeywords();
            });
        }
        if (kwInput) {
            kwInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const v = kwInput.value.trim().replace(/,$/, '');
                    if (v && keywords.indexOf(v) === -1) {
                        keywords.push(v);
                        if (kwHidden) kwHidden.value = keywords.join(',');
                        kwInput.value = '';
                        renderKeywords();
                    }
                }
            });
        }
        renderKeywords();

        /* EMOJI PICKER BUTTONS */
        const insertEmojiBtn = document.getElementById('insertEmojiBtn');
        const insertHeadingEmojiBtn = document.getElementById('insertHeadingEmojiBtn');
        const closeEmojiBtn = document.getElementById('closeEmojiPicker');
        const emojiModal = document.getElementById('emojiPickerModal');

        if (insertEmojiBtn) insertEmojiBtn.addEventListener('click', function () {
            openEmojiPicker('insert');
        });
        if (insertHeadingEmojiBtn) insertHeadingEmojiBtn.addEventListener('click', function () {
            window.npInsertEmoji = window.npInsertHeadingWithEmoji;
            openEmojiPicker('heading');
        });
        if (closeEmojiBtn) closeEmojiBtn.addEventListener('click', closeEmojiPicker);
        if (emojiModal) emojiModal.addEventListener('click', function (e) {
            if (e.target === emojiModal) closeEmojiPicker();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && emojiModal && emojiModal.classList.contains('show')) closeEmojiPicker();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', npInit);
    } else {
        npInit();
    }
    setTimeout(npInit, 300);
</script>
@endpush
@endsection
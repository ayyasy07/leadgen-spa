@extends('../layout/' . $layout)

@section('subhead')
    <title>Add Blog Category - Icewall</title>
@endsection

@section('subcontent')

    <style>
        .bcat-card{
            background:#fff;
            border:1px solid rgba(148,163,184,.18);
            border-radius:14px;
            box-shadow:0 1px 2px rgba(15,23,42,.04);
            min-width:0
        }
        .bcat-card-head{
            display:flex;
            align-items:center;
            gap:12px;
            padding:20px 24px;
            border-bottom:1px solid rgba(148,163,184,.16)
        }
        .bcat-card-head .icon-badge{
            width:36px;
            height:36px;
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:rgba(59,130,246,.1);
            flex:none
        }
        .icon-badge svg{
            width:18px;
            height:18px;
            color:#3b82f6;
            stroke-width:2px
        }
        .bcat-card-body{
            padding:24px;
            min-width:0
        }
        .bcat-label{
            display:block;
            font-size:13px;
            font-weight:600;
            color:#475569;
            margin-bottom:8px
        }
        .bcat-hint{
            font-weight:400;
            color:#94a3b8;
            font-size:12px
        }
        .bcat-field{
            margin-bottom:22px
        }
        .bcat-card .form-control{
            border-radius:10px!important;
            border-color:rgba(148,163,184,.35)!important;
            font-size:13.5px
        }
        .bcat-card .form-control:focus{
            border-color:rgba(59,130,246,.6)!important;
            box-shadow:0 0 0 3px rgba(59,130,246,.12)!important
        }
        .page-toolbar{
            display:flex;
            align-items:center;
            gap:14px;
            flex-wrap:wrap;
            background:#fff;
            border:1px solid rgba(148,163,184,.18);
            border-radius:14px;
            padding:16px 20px;
            box-shadow:0 1px 2px rgba(15,23,42,.04);
            position:sticky;
            top:12px;
            z-index:30
        }
        .page-toolbar .back-btn{
            width:36px;
            height:36px;
            border-radius:10px;
            flex:none;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#f1f5f9;
            color:#475569;
            text-decoration:none
        }
        .page-toolbar .back-btn svg{
            width:17px;
            height:17px
        }
        .page-toolbar .toolbar-title{
            font-size:16px;
            font-weight:600;
            color:#1e293b
        }
        .page-toolbar .toolbar-sub{
            font-size:12px;
            color:#94a3b8;
            margin-top:2px
        }
        .toolbar-actions{
            display:flex;
            gap:10px;
            margin-left:auto
        }
        .btn-ghost-cancel{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:10px 18px;
            border-radius:10px;
            font-size:13px;
            font-weight:600;
            color:#64748b;
            background:#f1f5f9;
            border:none;
            cursor:pointer;
            text-decoration:none
        }
        .btn-ghost-cancel:hover{
            background:#e2e8f0
        }
        .btn-primary-save{
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:10px 20px;
            border-radius:10px;
            font-size:13px;
            font-weight:700;
            color:#fff;
            background:#3b82f6;
            border:none;
            cursor:pointer;
            box-shadow:0 4px 12px rgba(59,130,246,.3)
        }
        .btn-primary-save:hover{
            background:#2563eb;
            transform:translateY(-1px)
        }
        .btn-primary-save svg{
            width:15px;
            height:15px
        }
    </style>

    <div class="intro-y page-toolbar mt-8">
        <a href="{{ route('admin.blog-categories.index') }}" class="back-btn">
            <i data-feather="arrow-left"></i>
        </a>
        <div>
            <div class="toolbar-title">Add Blog Category</div>
            <div class="toolbar-sub">Create a new category for blog posts.</div>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('admin.blog-categories.index') }}" class="btn-ghost-cancel">Cancel</a>
            <button type="submit" form="categoryForm" class="btn-primary-save">
                <i data-feather="check"></i> Save Category
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 lg:col-span-7">
            <div class="bcat-card">
                <div class="bcat-card-head">
                    <div class="icon-badge"><i data-feather="tag"></i></div>
                    <h3 class="font-medium text-base">Category Details</h3>
                </div>
                <div class="bcat-card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger mb-5">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.blog-categories.store') }}"
                          method="POST"
                          id="categoryForm">
                        @csrf

                        <div class="bcat-field">
                            <label class="bcat-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   value="{{ old('name') }}"
                                   class="form-control w-full"
                                   placeholder="e.g. Wellness, Skincare, Lifestyle"
                                   required>
                            <div class="bcat-hint mt-2">Slug will be automatically generated from this name.</div>
                        </div>

                        <div class="bcat-field" style="margin-bottom:0">
                            <label class="bcat-label">
                                Sort Order <span class="bcat-hint">(smaller numbers appear first)</span>
                            </label>
                            <input type="number" name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   class="form-control w-full"
                                   min="0">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
        });
    </script>
@endsection
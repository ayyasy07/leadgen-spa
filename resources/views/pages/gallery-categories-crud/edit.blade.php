@extends('../layout/' . $layout)

@section('subhead')
    <title>Edit Gallery Category - Icewall</title>
@endsection

@section('subcontent')

    {{-- TOOLBAR --}}
    <div class="intro-y flex flex-wrap sm:flex-nowrap items-center gap-3 mt-8">
        <a href="{{ route('admin.gallery-categories.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-lg font-medium text-slate-800 dark:text-slate-200">Edit Gallery Category</h2>
            <div class="text-slate-500 text-xs mt-0.5">Change the category name or order.</div>
        </div>
        <div class="flex items-center gap-2 sm:ml-auto mt-3 sm:mt-0">
            <a href="{{ route('admin.gallery-categories.index') }}" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit" form="categoryForm" class="btn btn-primary shadow-md">
                <i data-feather="check" class="w-4 h-4 mr-1"></i> Update Category
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 lg:col-span-7">
            <div class="box">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                        <i data-feather="tag" class="w-5 h-5"></i>
                    </div>
                    <h2 class="font-medium text-base text-slate-800 dark:text-slate-200">Category Details</h2>
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

                    <form action="{{ route('admin.gallery-categories.update', $category) }}"
                          method="POST"
                          id="categoryForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   value="{{ old('name', $category->name) }}"
                                   class="form-control w-full"
                                   required>
                        </div>

                        <div>
                            <label class="form-label">
                                Sort Order
                                <span class="text-slate-500 font-normal text-xs">(smaller numbers appear first)</span>
                            </label>
                            <input type="number" name="sort_order"
                                   value="{{ old('sort_order', $category->sort_order) }}"
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
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);
        });
    </script>
@endsection
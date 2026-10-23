@extends('../layout/' . $layout)

@section('subhead')
    <title>Blog Categories - Icewall</title>
@endsection

@section('subcontent')

    <style>
        .soft-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .soft-alert svg {
            width: 16px;
            height: 16px;
            flex: none;
        }

        .soft-alert-success {
            background: rgba(34, 197, 94, .12);
            color: #15803d;
            border-color: rgba(34, 197, 94, .25);
        }

        .soft-alert-success svg {
            color: #16a34a;
        }

        .soft-alert-danger {
            background: rgba(239, 68, 68, .1);
            color: #b91c1c;
            border-color: rgba(239, 68, 68, .25);
        }

        .soft-alert-danger svg {
            color: #ef4444;
        }

        .sort-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            background: rgba(59, 130, 246, .08);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, .2);
            text-decoration: none;
            transition: all .15s ease;
            white-space: nowrap;
        }

        .sort-btn:hover {
            background: rgba(59, 130, 246, .16);
            border-color: rgba(59, 130, 246, .35);
        }

        .sort-btn svg {
            width: 14px;
            height: 14px;
            flex: none;
        }
    </style>

    <h2 class="intro-y text-lg font-medium mt-10">Blog Categories</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <div class="hidden md:block text-slate-500">
                Total: <span class="font-medium">{{ $categories->count() }}</span> categories
            </div>

            <div class="mx-auto"></div>

            {{-- SORT TOGGLE --}}
            @php
                $nextSort  = $sort === 'asc' ? 'desc' : 'asc';
                // Tombol nampilin TUJUAN sort berikutnya
                $sortLabel = $nextSort === 'asc' ? 'A → Z' : 'Z → A';
                $sortIcon  = $nextSort === 'asc' ? 'arrow-down' : 'arrow-up';
            @endphp
            <a href="{{ route('admin.blog-categories.index', ['sort' => $nextSort]) }}"
               class="sort-btn"
               title="Click to toggle sort order">
                <i data-feather="{{ $sortIcon }}"></i>
                {{ $sortLabel }}
            </a>

            <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary shadow-md">
                <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Category
            </a>
        </div>

        @if(session('success'))
            <div class="intro-y col-span-12">
                <div class="soft-alert soft-alert-success">
                    <i data-feather="check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="intro-y col-span-12">
                <div class="soft-alert soft-alert-danger">
                    <i data-feather="alert-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($categories->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="tag" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No categories yet</h3>
                    <p class="text-slate-500 text-sm mb-5">Create a category first before adding blog posts.</p>
                    <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add First Category
                    </a>
                </div>
            </div>
        @else

            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">CATEGORY</th>
                            <th class="text-center whitespace-nowrap">ARTICLES</th>
                            <th class="text-center whitespace-nowrap w-56">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $c)
                            <tr class="intro-x">
                                <td>
                                    <span class="font-medium whitespace-nowrap">{{ $c->name }}</span>
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5"
                                         style="font-family:monospace;">
                                        {{ $c->slug }}
                                    </div>
                                </td>

                                <td class="text-center whitespace-nowrap">
                                    <span class="inline-flex items-center"
                                          style="
                                            padding: 4px 10px;
                                            border-radius: 999px;
                                            font-size: 12px;
                                            font-weight: 600;
                                            background: rgba(59,130,246,.08);
                                            color: #3b82f6;
                                          ">
                                        <i data-feather="file-text" style="width:12px;height:12px;margin-right:6px;"></i>
                                        {{ $c->blogs_count }} articles
                                    </span>
                                </td>

                                <td class="table-report__action">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{ route('admin.blog-categories.edit', $c) }}">
                                            <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.blog-categories.destroy', $c) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete category {{ $c->name }}? Articles in this category will lose their category.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="flex items-center text-danger">
                                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block mx-auto text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $categories->count() }}</span> categor(y/ies)
                </div>
            </div>

        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);
        });
    </script>
@endsection
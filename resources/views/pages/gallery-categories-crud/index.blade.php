@extends('../layout/' . $layout)

@section('subhead')
    <title>Gallery Categories - Icewall</title>
@endsection

@section('subcontent')

    <style>
        /* Sort button */
        .sort-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            background: rgb(var(--color-primary) / .08);
            color: rgb(var(--color-primary));
            border: 1px solid rgb(var(--color-primary) / .2);
            text-decoration: none;
            transition: all .15s ease;
            white-space: nowrap;
        }

        .sort-btn:hover {
            background: rgb(var(--color-primary) / .16);
            border-color: rgb(var(--color-primary) / .35);
        }

        .sort-btn svg {
            width: 14px;
            height: 14px;
            flex: none;
        }
    </style>

    <h2 class="intro-y text-lg font-medium mt-10">Gallery Categories</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <div class="hidden md:block text-slate-500">
                Total: <span class="font-medium">{{ $categories->count() }}</span> categories
            </div>

            <div class="mx-auto"></div>

            {{-- SORT TOGGLE --}}
            @php
                $nextSort = $sort === 'asc' ? 'desc' : 'asc';
                $sortLabel = $sort === 'asc' ? 'A → Z' : 'Z → A';
                $sortIcon = $sort === 'asc' ? 'arrow-down' : 'arrow-up';
            @endphp
            <a href="{{ route('admin.gallery-categories.index', ['sort' => $nextSort]) }}"
               class="sort-btn"
               title="Click to toggle sort order">
                <i data-feather="{{ $sortIcon }}"></i>
                {{ $sortLabel }}
            </a>

            <a href="{{ route('admin.gallery-categories.create') }}" class="btn btn-primary shadow-md">
                <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Category
            </a>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="intro-y col-span-12">
                <div class="alert alert-soft-success flex items-center">
                    <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="intro-y col-span-12">
                <div class="alert alert-soft-danger flex items-center">
                    <i data-feather="alert-circle" class="w-4 h-4 mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- EMPTY --}}
        @if($categories->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="tag" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No categories yet</h3>
                    <p class="text-slate-500 text-sm mb-5">Add a category before adding gallery photos.</p>
                    <a href="{{ route('admin.gallery-categories.create') }}" class="btn btn-primary">
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
                            <th class="text-center whitespace-nowrap">PHOTOS</th>
                            <th class="text-center whitespace-nowrap w-56">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $c)
                            <tr class="intro-x">
                                <td>
                                    <span class="font-medium whitespace-nowrap text-slate-800 dark:text-slate-200">{{ $c->name }}</span>
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
                                            background: rgb(var(--color-primary) / .08);
                                            color: rgb(var(--color-primary));
                                          ">
                                        <i data-feather="image" style="width:12px;height:12px;margin-right:6px;"></i>
                                        {{ $c->galleries_count }} photos
                                    </span>
                                </td>

                                <td class="table-report__action">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3 text-primary"
                                           href="{{ route('admin.gallery-categories.edit', $c) }}">
                                            <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.gallery-categories.destroy', $c) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete category {{ $c->name }}? Photos in this category will lose their category.')">
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
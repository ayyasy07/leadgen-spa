@extends('../layout/' . $layout)

@section('subhead')
    <title>Testimonials - Icewall</title>
@endsection

@section('subcontent')

    {{-- PAGE TITLE --}}
    <h2 class="intro-y text-lg font-medium mt-10">Testimonials</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- TOOLBAR --}}
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <div class="hidden md:block text-slate-500">
                Total: <span class="font-medium">{{ $testimonials->count() }}</span> testimonials
            </div>

            <div class="mx-auto"></div>

            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary shadow-md">
                <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add Testimonial
            </a>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="intro-y col-span-12">
                <div class="alert alert-success show mb-2 flex items-center">
                    <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- EMPTY STATE --}}
        @if($testimonials->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="message-square" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No testimonials yet</h3>
                    <p class="text-slate-500 text-sm mb-5">Start adding customer testimonials to display on the website.</p>
                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add First Testimonial
                    </a>
                </div>
            </div>
        @else

            {{-- DATA LIST --}}
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">CUSTOMER</th>
                            <th class="text-center whitespace-nowrap">RATING</th>
                            <th class="whitespace-nowrap">MESSAGE</th>
                            <th class="text-center whitespace-nowrap">STATUS</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testimonials as $t)
                            <tr class="intro-x">

                                {{-- CUSTOMER --}}
                                <td>
                                    <div class="flex items-center">
                                        @if($t->photo)
                                            <div class="w-10 h-10 image-fit zoom-in">
                                                <img src="{{ asset('storage/'.$t->photo) }}"
                                                     alt="{{ $t->name }}"
                                                     class="tooltip rounded-full"
                                                     title="{{ $t->name }}">
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-none"
                                                 style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                                                {{ strtoupper(substr($t->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="ml-3 min-w-0">
                                            <span class="font-medium whitespace-nowrap">
                                                {{ $t->name }}
                                            </span>
                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                                {{ $t->role ?? 'Customer' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- RATING --}}
                                <td class="text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-feather="star"
                                               class="w-4 h-4"
                                               style="{{ $i <= $t->rating
                                                    ? 'fill:#fbbf24;stroke:#fbbf24;'
                                                    : 'fill:#e2e8f0;stroke:#e2e8f0;' }}"></i>
                                        @endfor
                                    </div>
                                </td>

                                {{-- MESSAGE --}}
                                <td>
                                    <div class="text-slate-600 text-sm leading-relaxed" style="max-width:420px;">
                                        {{ Str::limit($t->message, 120) }}
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="w-40">
                                    <div class="flex items-center justify-center {{ $t->is_active ? 'text-success' : 'text-danger' }}">
                                        <i data-feather="{{ $t->is_active ? 'check-circle' : 'x-circle' }}" class="w-4 h-4 mr-2"></i>
                                        {{ $t->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </td>

                                {{-- ACTIONS --}}
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3"
                                           href="{{ route('admin.testimonials.edit', $t) }}">
                                            <i data-feather="edit" class="w-4 h-4 mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.testimonials.destroy', $t) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete testimonial from {{ $t->name }}?')">
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

            {{-- FOOTER --}}
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block mx-auto text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $testimonials->count() }}</span> testimonial(s)
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
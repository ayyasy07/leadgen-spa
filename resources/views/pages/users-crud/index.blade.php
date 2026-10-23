@extends('../layout/' . $layout)

@section('subhead')
    <title>Users - Icewall</title>
@endsection

@section('subcontent')

    <h2 class="intro-y text-lg font-medium mt-10">Users</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- TOOLBAR --}}
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <div class="hidden md:block text-slate-500">
                Total: <span class="font-medium">{{ $users->count() }}</span> users
            </div>

            <div class="mx-auto"></div>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-md">
                <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add User
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

        {{-- FILTER --}}
        <div class="intro-y col-span-12">
            <form method="GET" action="{{ route('admin.users.index') }}"
                  class="flex flex-wrap items-center gap-2">
                <select name="role" class="form-select box w-40">
                    <option value="all" {{ $filterRole == 'all' ? 'selected' : '' }}>All Roles</option>
                    <option value="admin" {{ $filterRole == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="customer" {{ $filterRole == 'customer' ? 'selected' : '' }}>Customer</option>
                </select>

                <div class="relative text-slate-500" style="flex:1;min-width:220px;max-width:400px;">
                    <input type="text" name="search" value="{{ $filterSearch }}"
                           class="form-control box w-full pr-10"
                           placeholder="Search name, email, or phone...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>

                <button type="submit" class="btn btn-primary shadow-md">
                    <i data-feather="filter" class="w-4 h-4 mr-1"></i> Filter
                </button>

                @if($filterRole !== 'all' || $filterSearch)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="x" class="w-4 h-4 mr-1"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- EMPTY --}}
        @if($users->isEmpty())
            <div class="intro-y col-span-12">
                <div class="box p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="users" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-base mb-1">No users yet</h3>
                    <p class="text-slate-500 text-sm mb-5">Users will appear here after they register or when an admin adds them.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="w-4 h-4 mr-1"></i> Add First User
                    </a>
                </div>
            </div>
        @else

            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">USER</th>
                            <th class="whitespace-nowrap">PHONE</th>
                            <th class="text-center whitespace-nowrap">ROLE</th>
                            <th class="text-center whitespace-nowrap">JOINED</th>
                            <th class="text-center whitespace-nowrap w-32">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            @php
                                $roleStyle = [
                                    'admin'    => ['bg' => 'rgba(124,58,237,.12)', 'text' => '#7c3aed', 'icon' => 'shield'],
                                    'customer' => ['bg' => 'rgb(var(--color-primary) / .12)', 'text' => 'rgb(var(--color-primary))', 'icon' => 'user'],
                                ][$u->role] ?? ['bg' => 'rgba(148,163,184,.15)', 'text' => '#64748b', 'icon' => 'user'];
                            @endphp
                            <tr class="intro-x">

                                {{-- USER --}}
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-none"
                                             style="background:linear-gradient(135deg,rgb(var(--color-primary)),rgb(var(--color-primary) / .7));">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <a href="{{ route('admin.users.show', $u) }}"
                                               class="font-medium whitespace-nowrap hover:text-primary text-slate-800 dark:text-slate-200">
                                                {{ $u->name }}
                                            </a>
                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                                {{ $u->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- PHONE --}}
                                <td class="whitespace-nowrap">
                                    @if($u->phone)
                                        <span class="font-mono text-xs text-slate-600 dark:text-slate-400">{{ $u->phone }}</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                {{-- ROLE --}}
                                <td class="text-center whitespace-nowrap">
                                    <span class="inline-flex items-center"
                                          style="
                                            padding: 4px 10px;
                                            border-radius: 999px;
                                            font-size: 11.5px;
                                            font-weight: 700;
                                            text-transform: uppercase;
                                            letter-spacing: .3px;
                                            background: {{ $roleStyle['bg'] }};
                                            color: {{ $roleStyle['text'] }};
                                          ">
                                        <i data-feather="{{ $roleStyle['icon'] }}" style="width:12px;height:12px;margin-right:5px;"></i>
                                        {{ $u->role }}
                                    </span>
                                </td>

                                {{-- JOINED --}}
                                <td class="text-center whitespace-nowrap">
                                    <div class="text-slate-600 dark:text-slate-400 text-sm">
                                        {{ $u->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-slate-400 text-xs mt-0.5">
                                        {{ $u->created_at->diffForHumans() }}
                                    </div>
                                </td>

                                {{-- ACTIONS --}}
                                <td class="table-report__action">
                                    <div class="flex justify-center items-center gap-5">

                                        {{-- VIEW --}}
                                        <a href="{{ route('admin.users.show', $u) }}" class="flex items-center">
                                            <i data-feather="eye"
                                               class="w-5 h-5 text-slate-500 hover:text-primary transition-colors tooltip"
                                               title="View"></i>
                                        </a>

                                        {{-- EDIT --}}
                                        <a href="{{ route('admin.users.edit', $u) }}" class="flex items-center">
                                            <i data-feather="edit"
                                               class="w-5 h-5 text-slate-500 hover:text-primary transition-colors tooltip"
                                               title="Edit"></i>
                                        </a>

                                        {{-- DELETE --}}
                                        @if($u->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $u) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete user {{ $u->name }}?')"
                                                  class="flex items-center">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center">
                                                    <i data-feather="trash-2"
                                                       class="w-5 h-5 text-slate-500 hover:text-danger transition-colors tooltip"
                                                       title="Delete"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <div class="hidden md:block mx-auto text-slate-500 text-sm">
                    Showing <span class="font-medium">{{ $users->count() }}</span> user(s)
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
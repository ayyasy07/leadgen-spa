@extends('../layout/' . $layout)

@section('subhead')
    <title>Add User - Icewall</title>
@endsection

@section('subcontent')

    {{-- TOOLBAR --}}
    <div class="intro-y flex flex-wrap sm:flex-nowrap items-center gap-3 mt-8">
        <a href="{{ route('admin.users.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-lg font-medium text-slate-800 dark:text-slate-200">Add User</h2>
            <div class="text-slate-500 text-xs mt-0.5">Create a new user (admin / customer).</div>
        </div>
        <div class="flex items-center gap-2 sm:ml-auto mt-3 sm:mt-0">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" form="userForm" class="btn btn-primary shadow-md">
                <i data-feather="check" class="w-4 h-4 mr-1"></i> Save User
            </button>
        </div>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" id="userForm" class="mt-5">
        @csrf

        <div class="grid grid-cols-12 gap-6">

            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3 flex-none">
                            <i data-feather="user-plus" class="w-5 h-5"></i>
                        </div>
                        <h2 class="font-medium text-base">User Details</h2>
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
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control w-full" placeholder="e.g. John Doe" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control w-full" placeholder="e.g. john@example.com" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Phone <span class="text-slate-500 font-normal text-xs">(optional)</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="form-control w-full" placeholder="e.g. 0812 3456 7890">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select w-full" required>
                                <option value="customer" {{ old('role', 'customer') == 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control w-full" required>
                                <div class="text-slate-500 text-xs mt-1">Minimum 8 characters.</div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control w-full" required>
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
            setTimeout(() => { if (window.feather) feather.replace(); }, 100);
            setTimeout(() => { if (window.feather) feather.replace(); }, 500);
        });
    </script>
@endsection
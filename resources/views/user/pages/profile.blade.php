@extends('user.layouts.main')

@section('title', 'My Profile — LeadGen Spa')
@section('footer_type', 'grid')

@push('styles')
<style>
    .page-title {
        padding: 46px 0 8px;
        max-width: 900px;
        margin: 0 auto;
        padding-left: 24px;
        padding-right: 24px;
    }
    .back-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(0,0,0,.08);
        margin-bottom: 22px;
        font-size: 14px;
        color: var(--ink);
        transition: .25s;
        text-decoration: none;
    }
    .back-btn:hover { background: var(--primary); color: #fff; }
    .page-title h1 {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .page-title p { color: var(--gray); font-size: 13.5px; }

    .profile-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px 24px 90px;
    }

    .card {
        background: #fff;
        border-radius: 16px;
        padding: 32px 36px;
        box-shadow: 0 5px 25px rgba(0,0,0,.04);
        border: 1px solid var(--line);
        margin-bottom: 20px;
    }
    .card h2 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card h2 i {
        color: var(--primary);
        font-size: 14px;
    }

    .alert-success {
        background: rgba(34,197,94,.1);
        border: 1px solid rgba(34,197,94,.25);
        color: #15803d;
        padding: 14px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-error {
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.25);
        color: #b91c1c;
        padding: 14px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        margin-bottom: 20px;
    }
    .alert-error ul {
        margin: 8px 0 0 18px;
        padding: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 8px;
    }
    .form-group label .req {
        color: var(--primary);
    }
    .form-group input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid var(--line);
        border-radius: 10px;
        font-size: 14px;
        color: var(--ink);
        background: #fff;
        font-family: inherit;
        transition: .25s;
        outline: none;
    }
    .form-group input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(247, 108, 108, .12);
    }
    .form-group .hint {
        font-size: 12px;
        color: var(--gray);
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary);
        color: #fff;
        padding: 12px 28px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: all .3s ease;
        font-family: inherit;
    }
    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(247, 108, 108, .3);
    }
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        color: var(--gray);
        padding: 12px 24px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1.5px solid var(--line);
        cursor: pointer;
        text-decoration: none;
        transition: .25s;
    }
    .btn-ghost:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed var(--line);
        font-size: 14px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .label {
        color: var(--gray);
        font-weight: 600;
    }
    .info-row .value {
        color: var(--ink);
        font-weight: 600;
        text-align: right;
    }

    @media (max-width: 600px) {
        .card { padding: 24px 20px; }
        .info-row { flex-direction: column; gap: 4px; }
        .info-row .value { text-align: left; }
    }
</style>
@endpush

@section('content')

    <div class="page-title">
        <a href="{{ url('/') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>My Profile</h1>
        <p>Manage your account information and password.</p>
    </div>

    <div class="profile-wrap">

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if($errors->any())
            <div class="alert-error">
                <strong>There are {{ $errors->count() }} error(s):</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- INFO AKUN --}}
        <div class="card">
            <h2><i class="fas fa-user-circle"></i> Account Information</h2>

            <div class="info-row">
                <span class="label">Joined Since</span>
                <span class="value">{{ auth()->user()->created_at->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Role</span>
                <span class="value">{{ ucfirst(auth()->user()->role ?? 'Customer') }}</span>
            </div>
        </div>

        {{-- FORM EDIT --}}
        <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
            @csrf
            @method('PUT')

            {{-- DATA DIRI --}}
            <div class="card">
                <h2><i class="fas fa-id-card"></i> Personal Information</h2>

                <div class="form-group">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="name"
                           value="{{ old('name', auth()->user()->name) }}"
                           placeholder="Your full name" required>
                </div>

                <div class="form-group">
                    <label>Email <span class="req">*</span></label>
                    <input type="email" name="email"
                           value="{{ old('email', auth()->user()->email) }}"
                           placeholder="email@example.com" required>
                </div>

                <div class="form-group">
                    <label>WhatsApp Number</label>
                    <input type="text" name="phone"
                           value="{{ old('phone', auth()->user()->phone) }}"
                           placeholder="08xxxxxxxxxx">
                    <div class="hint">Used for booking confirmation.</div>
                </div>
            </div>

            {{-- GANTI PASSWORD --}}
            <div class="card">
                <h2><i class="fas fa-lock"></i> Change Password</h2>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password"
                           placeholder="Leave blank if you don't want to change it">
                    <div class="hint">Minimum 8 characters.</div>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           placeholder="Re-enter new password">
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ url('/') }}" class="btn-ghost">Cancel</a>
            </div>

        </form>

    </div>

@endsection
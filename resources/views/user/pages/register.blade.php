@extends('user.layouts.auth')

@section('title', 'Register — LeadGen Spa')

@section('content')

<div class="auth-container">
    <div class="auth-logo">
        <div class="logo-badge">
            <i class="fa-solid fa-spa"></i>
        </div>
        <span class="brand-name">LeadGen</span>
        <h2>Create Account</h2>
        <p>Register to start booking treatments</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" id="registerForm">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <div class="input-wrapper">
                <i class="fa-regular fa-user icon-left"></i>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Your name"
                       required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
                <i class="fa-regular fa-envelope icon-left"></i>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="you@email.com"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label for="phone">Phone / WhatsApp</label>
            <div class="input-wrapper">
                <i class="fa-brands fa-whatsapp icon-left"></i>
                <input type="tel"
                       id="phone"
                       name="phone"
                       value="{{ old('phone') }}"
                       placeholder="08xx-xxxx-xxxx">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <i class="fa-solid fa-lock icon-left"></i>
                {{-- Icon mata udah gue hapus dari sini --}}
                <input type="password"
                       id="password"
                       name="password"
                       placeholder="Create a password"
                       required>
            </div>
            <p class="hint">Minimum 8 characters.</p>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-wrapper">
                <i class="fa-solid fa-lock icon-left"></i>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       placeholder="Re-type your password"
                       required>
            </div>
        </div>

        <label class="terms">
            <input type="checkbox" name="agree" required>
            <span>I agree to the <a href="#">Terms &amp; Conditions</a> and <a href="#">Privacy Policy</a>.</span>
        </label>

        <button type="submit" class="auth-btn">
            <span id="btnText">Register</span>
        </button>
    </form>

    <div class="auth-link">
        Already have an account? <a href="{{ route('login') }}">Login</a>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Script toggle password udah gue hapus karena ikonnya juga udah ilang

    const registerForm = document.getElementById('registerForm');
    const btnText = document.getElementById('btnText');
    if (registerForm && btnText) {
        registerForm.addEventListener('submit', () => {
            btnText.innerHTML = '<span class="loading"></span>Creating account...';
        });
    }
</script>
@endpush
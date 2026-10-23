@extends('user.layouts.auth')

@section('title', 'Login — LeadGen Spa')

@section('content')

<div class="auth-container">
    <div class="auth-logo">
        <div class="logo-badge">
            <i class="fa-solid fa-spa"></i>
        </div>
        <span class="brand-name">LeadGen</span>
        <h2>Welcome Back</h2>
        <p>Login to your spa account</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" id="loginForm">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
                <i class="fa-regular fa-envelope icon-left"></i>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="your@email.com"
                       required autofocus autocomplete="email">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper" style="position: relative;">
                <i class="fa-solid fa-lock icon-left"></i>
                
                {{-- TETAP TYPE PASSWORD, TANPA IKON FONTAWESOME --}}
                <input type="password"
                       id="password"
                       name="password"
                       placeholder="Enter your password"
                       autocomplete="current-password"
                       required>
            </div>
        </div>

        <div class="form-options">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="#">Forgot Password?</a>
        </div>

        <button type="submit" class="auth-btn">
            <span id="btnText">Login</span>
        </button>
    </form>

    <div class="auth-link">
        Don't have an account? <a href="{{ route('register.index') }}">Register</a>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const loginForm = document.getElementById('loginForm');
    const btnText = document.getElementById('btnText');
    if (loginForm && btnText) {
        loginForm.addEventListener('submit', () => {
            btnText.innerHTML = '<span class="loading"></span>Logging in...';
        });
    }
</script>
@endpush
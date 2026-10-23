<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LeadGen Spa')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #a3141f;
            --coral: #f76c6c;
            --coral-dark: #ea5252;
            --ink: #232020;
            --gray: #777;
            --bg-cream: #fbf9f8;
            --white: #fff;
            --line: #e7e2df;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(170, 51, 55, 0.1);
            padding: 40px 35px;
            position: relative;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: -10px; left: -10px; right: -10px; bottom: -10px;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(247, 108, 108, 0.1), rgba(247, 108, 108, 0.02));
            z-index: -1;
        }

        /* LOGO */
        .auth-logo {
            text-align: center;
            margin-bottom: 26px;
        }
        .logo-badge {
            width: 56px;
            height: 56px;
            margin: 0 auto 14px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--coral), var(--coral-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px -6px rgba(247, 108, 108, 0.55);
        }
        .logo-badge i {
            font-size: 24px;
            color: #fff;
        }
        .auth-logo .brand-name {
            display: block;
            font-size: 21px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: .2px;
        }
        .auth-logo h2 {
            margin-top: 16px;
            font-size: 22px;
            font-weight: 700;
            color: var(--ink);
        }
        .auth-logo p {
            color: var(--gray);
            font-size: 13px;
            margin-top: 5px;
        }

        /* FORM */
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 8px;
        }
        .input-wrapper {
            position: relative;
            border: 1px solid #eee;
            border-radius: 12px;
            background: #fff;
            padding: 14px 15px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        .input-wrapper:focus-within {
            border-color: var(--coral);
            box-shadow: 0 0 0 4px rgba(247, 108, 108, 0.12);
        }
        .input-wrapper i.icon-left {
            width: 16px;
            flex: none;
            margin-right: 10px;
            color: #b7aaa9;
            font-size: 14px;
            text-align: center;
        }
        .input-wrapper input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            font-family: inherit;
            color: var(--ink);
            background: transparent;
            width: 100%;
            padding-right: 40px; /* Kasih ruang buat ikon browser */
        }
        .input-wrapper input::placeholder { color: #bbb; }

        /* CSS .toggle-password LAMA DIHAPUS DARI SINI */

        .hint {
            font-size: 12px;
            color: #aaa;
            margin-top: 5px;
        }

        /* FORM OPTIONS */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .form-options label {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--gray);
            cursor: pointer;
        }
        .form-options a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .form-options a:hover { text-decoration: underline; }

        /* TERMS */
        .terms {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: var(--gray);
            margin: 15px 0 20px;
            line-height: 1.5;
        }
        .terms input {
            width: 15px;
            height: 15px;
            margin-top: 2px;
            accent-color: var(--coral);
            cursor: pointer;
            flex: none;
        }
        .terms a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .terms a:hover { text-decoration: underline; }

        /* BUTTON */
        .auth-btn {
            width: 100%;
            background: var(--coral);
            color: var(--white);
            padding: 15px;
            border: none;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.4s ease;
        }
        .auth-btn:hover {
            background: var(--coral-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(247, 108, 108, 0.3);
        }
        .auth-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top: 2px solid var(--white);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 10px;
            vertical-align: middle;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* LINK BOTTOM */
        .auth-link {
            text-align: center;
            font-size: 13px;
            color: var(--gray);
            margin-top: 20px;
        }
        .auth-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-link a:hover { text-decoration: underline; }

        /* ALERT */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13px;
            line-height: 1.5;
        }
        .alert-success {
            background: rgba(40, 167, 69, .1);
            border-left: 4px solid #28a745;
            color: #155724;
        }
        .alert-danger {
            background: rgba(231, 76, 60, .1);
            border-left: 4px solid #e74c3c;
            color: #721c24;
        }
        .alert-danger ul {
            list-style: disc;
            padding-left: 20px;
            margin: 0;
        }

        /* BACK LINK */
        .back-home {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink);
            text-decoration: none;
            transition: .25s;
            z-index: 100;
        }
        .back-home:hover {
            background: var(--coral);
            color: #fff;
            transform: scale(1.05);
        }

        @media (max-width: 480px) {
            .auth-container { padding: 30px 22px; }
        }
    </style>
</head>
<body>

    {{-- Back to Home --}}
    <a href="{{ route('home') }}" class="back-home" title="Back to Home">
        <i class="fas fa-arrow-left"></i>
    </a>

    @yield('content')

</body>
</html>
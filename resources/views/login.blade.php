@extends('layouts.app')

@section('title', 'Login | Tempo HR')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        min-height: 100%;
    }

    body {
        background: #F6F8F7;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .login-page {
        width: 100%;
        min-height: 100vh;
        min-height: 100svh;
        display: flex;
        flex-direction: column;
        padding: 18px 24px 35px;
    }

    /* Mobile heading */

    .login-header {
        margin-bottom: 24px;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .page-title {
        color: #172E29;
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.7px;
        margin: 0 0 5px;
    }

    .page-subtitle {
        color: #687B74;
        font-size: 13px;
        margin: 0;
    }

    /* Login area */

    .login-layout {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        padding: 24px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 18px;
        box-shadow: 0 8px 30px rgba(23, 46, 41, .05);
    }

    .card-title {
        color: #172E29;
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .card-description {
        color: #687B74;
        font-size: 12px;
        margin: 0 0 25px;
    }

    /* Error alert */

    .error-alert {
        background: #FDF2F2;
        border: 1px solid #F8D7DA;
        color: #A94442;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 12px;
        margin-bottom: 20px;
    }

    .error-alert ul {
        margin: 0;
        padding-left: 16px;
    }

    /* Form */

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 17px;
    }

    .form-label {
        color: #687B74;
        font-size: 11px;
        font-weight: 600;
    }

    .form-input {
        width: 100%;
        height: 49px;
        padding: 0 13px;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .form-input:focus {
        border-color: #176454;
        box-shadow: 0 0 0 3px rgba(23, 100, 84, .08);
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-input {
        padding-right: 65px;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 13px;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #176454;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    /* Remember me */

    .remember-row {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 22px 0 25px;
    }

    .remember-checkbox {
        width: 17px;
        height: 17px;
        accent-color: #176454;
        cursor: pointer;
    }

    .remember-label {
        color: #172E29;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
    }

    /* Submit */

    .login-button {
        width: 100%;
        height: 49px;
        background: #176454;
        border: none;
        border-radius: 11px;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .login-button:hover {
        background: #125447;
        transform: translateY(-1px);
    }

    /* Account guidance */

    .access-guidance {
        width: 100%;
        max-width: 440px;
        margin-top: 17px;
        padding: 16px 18px;
        background: #E7F0EB;
        border: 1px solid #D9E8DF;
        border-radius: 12px;
    }

    .guidance-title {
        color: #176454;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .guidance-text {
        color: #176454;
        font-size: 12px;
        line-height: 1.5;
    }

    .login-footer {
        width: 100%;
        max-width: 440px;
        margin: 25px auto 0;
        color: #8A9993;
        font-size: 11px;
    }

    /* Desktop split layout */

    @media (min-width: 900px) {

        .login-page {
            min-height: 100vh;
            min-height: 100svh;
            padding: 0;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr);
            background: #FFFFFF;
        }

        /* Left welcome panel */

        .login-header {
            min-height: 100%;
            margin: 0;
            padding: clamp(45px, 6vw, 100px);
            background: #176454;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: "";
            position: absolute;
            width: 540px;
            height: 540px;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 50%;
            right: -190px;
            bottom: -170px;
        }

        .login-header::after {
            content: "";
            position: absolute;
            width: 360px;
            height: 360px;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 50%;
            right: -100px;
            bottom: -80px;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            margin: auto 0;
            max-width: 560px;
        }

        .login-header .eyebrow {
            color: #B9D9CB;
            font-size: 12px;
            letter-spacing: 2px;
            margin-bottom: 24px;
        }

        .login-header .page-title {
            color: #FFFFFF;
            font-size: clamp(42px, 4.5vw, 66px);
            line-height: 1.12;
            letter-spacing: -2px;
            margin-bottom: 18px;
        }

        .login-header .page-subtitle {
            color: #D4E7DF;
            font-size: clamp(15px, 1.2vw, 18px);
            line-height: 1.7;
            max-width: 420px;
        }

        .welcome-decoration {
            position: relative;
            z-index: 1;
            margin-top: 50px;
            max-width: 470px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, .16);
            background: rgba(255, 255, 255, .08);
            border-radius: 16px;
            backdrop-filter: blur(8px);
        }

        .decoration-label {
            color: #B9D9CB;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-bottom: 10px;
        }

        .decoration-text {
            color: #FFFFFF;
            font-size: 15px;
            line-height: 1.6;
            font-weight: 500;
        }

        .welcome-footer {
            position: relative;
            z-index: 1;
            color: #B9D9CB;
            font-size: 11px;
            margin-top: 45px;
        }

        /* Right login panel */

        .login-layout {
            min-height: 100%;
            padding: 45px clamp(35px, 5vw, 85px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #F6F8F7;
        }

        .login-card {
            max-width: 480px;
            padding: clamp(28px, 3vw, 42px);
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(23, 46, 41, .06);
        }

        .card-title {
            font-size: 22px;
        }

        .card-description {
            font-size: 13px;
            margin-bottom: 32px;
        }

        .form-group {
            gap: 9px;
            margin-bottom: 21px;
        }

        .form-label {
            font-size: 12px;
        }

        .form-input {
            height: 54px;
            font-size: 14px;
            padding: 0 15px;
        }

        .password-wrapper .form-input {
            padding-right: 70px;
        }

        .password-toggle {
            font-size: 12px;
        }

        .remember-row {
            margin: 25px 0 28px;
        }

        .remember-label {
            font-size: 13px;
        }

        .login-button {
            height: 54px;
            font-size: 14px;
        }

        .access-guidance {
            max-width: 480px;
            margin-top: 20px;
            padding: 18px 20px;
        }

        .guidance-title {
            font-size: 13px;
        }

        .guidance-text {
            font-size: 12px;
        }

        .login-footer {
            max-width: 480px;
            margin-top: 25px;
        }
    }

    /* Extra-wide desktop */

    @media (min-width: 1400px) {
        .login-page {
            grid-template-columns: 1.1fr .9fr;
        }

        .login-header {
            padding: 75px clamp(70px, 8vw, 150px);
        }

        .login-layout {
            padding-left: 70px;
            padding-right: 70px;
        }
    }

    /* Smaller screens */

    @media (max-width: 480px) {
        .login-page {
            padding-left: 20px;
            padding-right: 20px;
        }

        .login-card {
            max-width: none;
        }

        .access-guidance,
        .login-footer {
            max-width: none;
        }
    }
</style>

<main class="login-page">

    <!-- Welcome Panel -->
    <header class="login-header">

        <div class="brand-content">

            <div class="eyebrow">
                SMART HR · TEMPO
            </div>

            <h1 class="page-title">
                Welcome back.
            </h1>

            <p class="page-subtitle">
                A little clarity for your workday.
            </p>

            <div class="welcome-decoration">

                <div class="decoration-label">
                    YOUR WORKSPACE
                </div>

                <div class="decoration-text">
                    Your people, schedules, attendance, and payroll information, all in one place.
                </div>

            </div>

        </div>

        <div class="welcome-footer">
            Your people. Your time. In one place.
        </div>

    </header>

    <!-- Login Panel -->
    <section class="login-layout">

        <section class="login-card">

            <h2 class="card-title">
                Log in to your workspace
            </h2>

            <p class="card-description">
                Use your company-provided account.
            </p>

            <!-- Display Validation Errors if login fails -->
            @if ($errors->any())

                <div class="error-alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            @endif

            <form method="POST" action="{{ url('/login') }}">

                @csrf

                <div class="form-group">

                    <label class="form-label" for="email">
                        Work email
                    </label>

                    <input
                        class="form-input"
                        type="email"
                        id="email"
                        name="email"
                        placeholder="email"
                        value="{{ old('email') }}"
                        required
                    >

                </div>

                <div class="form-group">

                    <label class="form-label" for="password">
                        Password
                    </label>

                    <div class="password-wrapper">

                        <input
                            class="form-input"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="password-toggle"
                        >
                            Show
                        </button>

                    </div>

                </div>

                <div class="remember-row">

                    <input
                        class="remember-checkbox"
                        type="checkbox"
                        id="remember"
                        name="remember"
                        {{ old('remember') ? 'checked' : '' }}
                    >

                    <label class="remember-label" for="remember">
                        Remember me
                    </label>

                </div>

                <button type="submit" class="login-button">
                    Log in
                </button>

            </form>

        </section>

        <!-- Account Access Guidance -->
        <section class="access-guidance">

            <div class="guidance-title">
                Need access?
            </div>

            <div class="guidance-text">
                Contact your HR administrator for an account.
            </div>

        </section>

        <footer class="login-footer">
            Your people. Your time. In one place.
        </footer>

    </section>

</main>

<script>
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');

    passwordToggle.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';
        passwordToggle.textContent = isPassword ? 'Hide' : 'Show';
    });
</script>

@endsection
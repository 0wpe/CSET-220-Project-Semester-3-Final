@extends('layouts.app')

@section('title', 'Login | Tempo HR')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #F6F8F7;
        font-family: 'Inter', sans-serif;
    }

    .login-page {
        width: 100%;
        max-width: 1100px;
        min-height: 100vh;
        margin: 0 auto;
        padding: 18px 24px 40px;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .page-title {
        color: #172E29;
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .page-subtitle {
        color: #687B74;
        font-size: 13px;
        margin: 0 0 26px;
    }

    /* Login card */

    .login-card {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
        padding: 20px;
        background: #FFFFFF;
        border-radius: 20px;
    }

    .card-title {
        color: #172E29;
        font-size: 17px;
        font-weight: 600;
        margin: 0 0 5px;
    }

    .card-description {
        color: #687B74;
        font-size: 12px;
        margin: 0 0 28px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }

    .form-label {
        color: #687B74;
        font-size: 11px;
        font-weight: 500;
    }

    .form-input {
        width: 100%;
        height: 49px;
        padding: 0 12px;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }

    .form-input:focus {
        border-color: #176454;
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
        right: 12px;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #176454;
        font-family: inherit;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
    }

    /* Remember me */

    .remember-row {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 24px 0 28px;
    }

    .remember-checkbox {
        width: 18px;
        height: 18px;
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
        border-radius: 12px;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .login-button:hover {
        background: #125447;
    }

    /* Account guidance */

    .access-guidance {
        max-width: 420px;
        margin: 24px auto 0;
        padding: 14px 16px;
        background: #E7F0EB;
        border-radius: 12px;
    }

    .guidance-title {
        color: #176454;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .guidance-text {
        color: #176454;
        font-size: 12px;
        line-height: 1.5;
    }

    .login-footer {
        max-width: 420px;
        margin: 40px auto 0;
        color: #687B74;
        font-size: 12px;
    }

    /* Error alert banner */
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

    @media (max-width: 480px) {
        .login-page {
            padding-left: 24px;
            padding-right: 24px;
        }

        .login-card {
            max-width: none;
        }

        .access-guidance {
            max-width: none;
        }

        .login-footer {
            max-width: none;
        }
    }
</style>

<main class="login-page">

    <div class="eyebrow">
        SMART HR · TEMPO
    </div>

    <h1 class="page-title">
        Welcome back.
    </h1>

    <p class="page-subtitle">
        A little clarity for your workday.
    </p>

    <!-- Login form -->

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
                    placeholder="maya.thompson@tempo.co"
                    value="{{ old('email', 'maya.thompson@tempo.co') }}"
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

    <!-- Account access guidance -->

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

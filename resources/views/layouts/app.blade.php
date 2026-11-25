<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tempo HR')</title>

    <style>
        :root {
            --sidebar-width: clamp(240px, 18vw, 300px);

            --color-primary: #176454;
            --color-primary-dark: #124F43;
            --color-background: #F6F8F7;
            --color-surface: #FFFFFF;
            --color-text: #172E29;
            --color-muted: #71817C;
            --color-border: #E3EAE7;

            --page-padding-x: clamp(20px, 3vw, 55px);
            --page-padding-y: clamp(24px, 2.5vw, 44px);

            --font-size-base: clamp(14px, 0.2vw + 13px, 16px);
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--color-background);
            color: var(--color-text);
            font-family: 'Inter', Arial, sans-serif;
            font-size: var(--font-size-base);
            line-height: 1.5;
            overflow-x: hidden;
        }

        main {
            width: 100%;
            min-width: 0;
            min-height: 100vh;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        img {
            max-width: 100%;
        }

        @media (min-width: 768px) {
            body:not(.login-layout) main {
                width: calc(100% - var(--sidebar-width));
                margin-left: var(--sidebar-width);
            }
        }

        @media (max-width: 767px) {
            main {
                min-height: calc(100vh - 64px);
            }
        }
    </style>
</head>

<body class="{{ request()->routeIs('login') || request()->is('login') ? 'login-layout' : '' }}">

    @yield('content')

    @stack('scripts')

</body>

</html>
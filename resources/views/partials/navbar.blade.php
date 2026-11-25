@php
    $navUser = auth()->user();
@endphp

<style>
    .top-navbar,
    .top-navbar *,
    .navigation-overlay {
        box-sizing: border-box;
    }

    .top-navbar {
        position: relative;
        z-index: 1200;
        width: 100%;
        min-height: 76px;
        padding: 16px 20px;
        background: #FFFFFF;
        border-bottom: 1px solid #DFE7E2;
        font-family: 'Inter', Arial, sans-serif;
    }

    .navbar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 43px;
    }

    .navbar-logo {
        color: #176454;
        font-size: clamp(25px, 2vw, 30px);
        font-weight: 700;
        text-decoration: none;
        letter-spacing: -1px;
    }

    .navbar-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .workspace-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 94px;
        height: 28px;
        padding: 0 12px;
        background: #E7F0EB;
        border-radius: 20px;
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.4px;
    }

    .menu-toggle {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        width: 42px;
        height: 42px;
        padding: 8px;
        background: #E7F0EB;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        cursor: pointer;
    }

    .menu-toggle span {
        display: block;
        width: 20px;
        height: 2px;
        background: #176454;
        border-radius: 2px;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .menu-toggle[aria-expanded="true"] span:first-child {
        transform: translateY(7px) rotate(45deg);
    }

    .menu-toggle[aria-expanded="true"] span:nth-child(2) {
        opacity: 0;
    }

    .menu-toggle[aria-expanded="true"] span:last-child {
        transform: translateY(-7px) rotate(-45deg);
    }

    .horizontal-navigation {
        display: flex;
        position: fixed;
        top: 0;
        right: 0;
        width: min(320px, 88vw);
        height: 100vh;
        height: 100dvh;
        padding: 24px 18px;
        flex-direction: column;
        gap: 6px;
        background: #FFFFFF;
        border-left: 1px solid #DFE7E2;
        overflow-y: auto;
        overscroll-behavior: contain;
        z-index: 1201;
        transform: translateX(100%);
        visibility: hidden;
        transition: transform 0.25s ease, visibility 0.25s ease;
        box-shadow: -12px 0 35px rgba(23, 46, 41, 0.10);
    }

    .horizontal-navigation.open {
        transform: translateX(0);
        visibility: visible;
    }

    .mobile-navigation-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 8px 22px;
        border-bottom: 1px solid #DFE7E2;
        margin-bottom: 12px;
    }

    .mobile-navigation-heading span {
        color: #176454;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -1px;
    }

    .mobile-navigation-heading small {
        color: #687B74;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.7px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        width: 100%;
        min-height: 46px;
        padding: 0 15px;
        border-radius: 10px;
        color: #687B74;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .nav-link:hover {
        background: #F0F5F2;
        color: #176454;
    }

    .nav-link.active {
        background: #176454;
        color: #FFFFFF;
        font-weight: 600;
    }

    .navigation-spacer {
        flex: 1;
        min-height: 24px;
    }

    .sidebar-profile {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 16px 8px 12px;
        border-top: 1px solid #DFE7E2;
        text-decoration: none;
        min-width: 0;
    }

    .sidebar-profile:hover .sidebar-profile-name {
        color: #176454;
    }

    .sidebar-avatar,
    .sidebar-avatar-placeholder {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border-radius: 50%;
        object-fit: cover;
        background: #E7F0EB;
    }

    .sidebar-avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #176454;
        font-size: 14px;
        font-weight: 700;
    }

    .sidebar-profile-details {
        min-width: 0;
        flex: 1;
    }

    .sidebar-profile-name {
        display: block;
        overflow: hidden;
        color: #172E29;
        font-size: 13px;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: color 0.2s ease;
    }

    .sidebar-profile-role {
        display: block;
        margin-top: 3px;
        color: #687B74;
        font-size: 11px;
    }

    .sidebar-profile-arrow {
        color: #82928C;
        font-size: 17px;
    }

    .sidebar-logout-form {
        width: 100%;
        padding: 0 8px 6px;
    }

    .sidebar-logout-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 43px;
        padding: 10px 14px;
        background: #FCEFED;
        border: 1px solid #F4DEDA;
        border-radius: 9px;
        color: #A33B32;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .sidebar-logout-button:hover {
        background: #F8E1DD;
    }

    .navigation-overlay {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(15, 35, 29, 0.42);
        z-index: 1100;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .navigation-overlay.open {
        opacity: 1;
        visibility: visible;
    }

    body.employee-menu-open {
        overflow: hidden;
    }

    @media (max-width: 400px) {
        .top-navbar {
            padding-left: 14px;
            padding-right: 14px;
        }

        .workspace-badge {
            display: none;
        }
    }

    @media (min-width: 768px) {
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            height: 100dvh;
            min-height: 0;
            padding: clamp(24px, 2.5vw, 36px) 16px 20px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #DFE7E2;
            border-bottom: none;
            overflow: hidden;
        }

        .navbar-header {
            display: block;
            min-height: auto;
            padding: 0 10px 26px;
            border-bottom: 1px solid #DFE7E2;
        }

        .navbar-logo {
            display: inline-block;
            margin-bottom: 18px;
            font-size: clamp(27px, 2vw, 32px);
        }

        .navbar-actions {
            justify-content: flex-start;
        }

        .menu-toggle {
            display: none;
        }

        .navigation-overlay {
            display: none;
        }

        .horizontal-navigation {
            position: static;
            width: 100%;
            height: auto;
            min-height: 0;
            flex: 1;
            padding: 22px 4px 10px;
            flex-direction: column;
            gap: 6px;
            background: transparent;
            border: none;
            transform: none;
            visibility: visible;
            box-shadow: none;
            overflow-y: auto;
        }

        .mobile-navigation-heading {
            display: none;
        }

        .nav-link {
            min-height: clamp(43px, 3.5vw, 49px);
            padding: 0 14px;
            font-size: clamp(12px, 0.8vw, 14px);
        }

        .navigation-spacer {
            min-height: 20px;
        }

        .sidebar-profile {
            padding: 17px 8px 13px;
        }

        .sidebar-avatar,
        .sidebar-avatar-placeholder {
            width: 44px;
            height: 44px;
        }
    }

    @media (min-width: 1500px) {
        .top-navbar {
            padding-left: 20px;
            padding-right: 20px;
        }

        .navbar-header {
            padding-left: 12px;
            padding-right: 12px;
        }

        .horizontal-navigation {
            padding-left: 6px;
            padding-right: 6px;
        }

        .nav-link {
            padding-left: 17px;
            font-size: 14px;
        }
    }
</style>

<header class="top-navbar">

    <div class="navbar-header">

        <a href="{{ url('/employee/dashboard') }}" class="navbar-logo">
            tempo
        </a>

        <div class="navbar-actions">

            <span class="workspace-badge">
                WORKSPACE
            </span>

            <button
                type="button"
                class="menu-toggle"
                id="employeeMenuToggle"
                aria-label="Open navigation menu"
                aria-expanded="false"
                aria-controls="employeeNavigation"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>

    </div>

    <nav class="horizontal-navigation" id="employeeNavigation">

        <div class="mobile-navigation-heading">
            <span>tempo</span>
            <small>EMPLOYEE WORKSPACE</small>
        </div>

        <a href="{{ url('/employee/dashboard') }}"
           class="nav-link {{ ($activePage ?? '') === 'home' ? 'active' : '' }}">
            Home
        </a>

        <a href="{{ url('/employee/profile') }}"
           class="nav-link {{ ($activePage ?? '') === 'profile' ? 'active' : '' }}">
            Profile
        </a>

        <a href="{{ url('/employee/time-entry') }}"
           class="nav-link {{ ($activePage ?? '') === 'time' ? 'active' : '' }}">
            Time
        </a>

        <a href="{{ url('/employee/leave-requests') }}"
           class="nav-link {{ ($activePage ?? '') === 'leave' ? 'active' : '' }}">
            Leave
        </a>

        <a href="{{ url('/employee/payslips') }}"
           class="nav-link {{ ($activePage ?? '') === 'payslips' ? 'active' : '' }}">
            Payslips
        </a>

        <div class="navigation-spacer"></div>

        <a href="{{ url('/employee/profile') }}" class="sidebar-profile">

            @if($navUser && $navUser->profile_picture)
                <img
                    src="{{ asset('storage/' . $navUser->profile_picture) }}"
                    alt="Profile picture"
                    class="sidebar-avatar"
                >
            @else
                <div class="sidebar-avatar-placeholder">
                    {{ $navUser ? strtoupper(substr($navUser->first_name, 0, 1) . substr($navUser->last_name, 0, 1)) : 'EM' }}
                </div>
            @endif

            <div class="sidebar-profile-details">
                <span class="sidebar-profile-name">
                    {{ $navUser ? $navUser->first_name . ' ' . $navUser->last_name : 'Employee' }}
                </span>

                <span class="sidebar-profile-role">
                    Employee
                </span>
            </div>

            <span class="sidebar-profile-arrow">›</span>

        </a>

        <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
            @csrf

            <button type="submit" class="sidebar-logout-button">
                Log Out
            </button>
        </form>

    </nav>

</header>

<div class="navigation-overlay" id="employeeNavigationOverlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const menuToggle = document.getElementById('employeeMenuToggle');
        const navigation = document.getElementById('employeeNavigation');
        const overlay = document.getElementById('employeeNavigationOverlay');

        if (!menuToggle || !navigation || !overlay) {
            return;
        }

        function openMenu() {
            navigation.classList.add('open');
            overlay.classList.add('open');
            document.body.classList.add('employee-menu-open');

            menuToggle.setAttribute('aria-expanded', 'true');
            menuToggle.setAttribute('aria-label', 'Close navigation menu');
        }

        function closeMenu() {
            navigation.classList.remove('open');
            overlay.classList.remove('open');
            document.body.classList.remove('employee-menu-open');

            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Open navigation menu');
        }

        menuToggle.addEventListener('click', function () {
            if (navigation.classList.contains('open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        overlay.addEventListener('click', closeMenu);

        navigation.querySelectorAll('.nav-link, .sidebar-profile').forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                closeMenu();
            }
        });

    });
</script>
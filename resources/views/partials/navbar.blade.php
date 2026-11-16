
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .top-navbar {
        width: 100%;
        height: 160px;
        padding: 32px 16px 0;
        background: #FFFFFF;
        border-bottom: 1px solid #DFE7E2;
        font-family: 'Inter', sans-serif;
        box-sizing: border-box;
    }

    .navbar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 43px;
        margin: 0 8px 32px;
    }

    .navbar-logo {
        color: #176454;
        font-size: 28px;
        font-weight: 700;
        line-height: 1.45;
        text-decoration: none;
    }

    .workspace-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 94px;
        height: 26px;
        background: #E7F0EB;
        border-radius: 13px;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    .horizontal-navigation {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        width: 100%;
        max-width: 370px;
        height: 43px;
        margin: 0 auto;
        padding: 0;
        background: #F6F8F7;
        border-radius: 11px;
        box-sizing: border-box;
    }

    .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 0;
        height: 43px;
        border-radius: 9px;
        color: #687B74;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.45;
        text-decoration: none;
        white-space: nowrap;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .nav-link:hover {
        background: #E7F0EB;
        color: #176454;
    }

    .nav-link.active {
        background: #176454;
        color: #FFFFFF;
        font-weight: 600;
    }

    @media (max-width: 400px) {
        .top-navbar {
            padding-left: 12px;
            padding-right: 12px;
        }

        .nav-link {
            font-size: 11px;
        }
    }
</style>

<header class="top-navbar">

    <div class="navbar-header">

        <a href="{{ url('/employee/dashboard') }}" class="navbar-logo">
            tempo
        </a>

        <span class="workspace-badge">
            WORKSPACE
        </span>

    </div>

    <nav class="horizontal-navigation">

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
            Admin
        </a>

    </nav>

</header>
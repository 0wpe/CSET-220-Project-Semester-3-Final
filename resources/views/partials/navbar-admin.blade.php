
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .top-navbar {
        width: 100%;
        height: 200px;
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

    .admin-navigation {
        display: grid;
        grid-template-columns: 101px 98px 103px 68px;
        grid-template-rows: 39px 39px;
        gap: 2px 0;
        width: 100%;
        max-width: 370px;
        height: 82px;
        margin: 0 auto;
        padding: 0;
        background: #F6F8F7;
        border-radius: 11px;
        box-sizing: border-box;
        overflow: hidden;
    }

    .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 39px;
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

        .admin-navigation {
            grid-template-columns: 27.3% 26.5% 27.8% 18.4%;
        }

        .nav-link {
            font-size: 10px;
        }
    }
</style>

<header class="top-navbar">

    <div class="navbar-header">

        <a href="{{ url('/admin/dashboard') }}" class="navbar-logo">
            tempo
        </a>

        <span class="workspace-badge">
            WORKSPACE
        </span>

    </div>

    <nav class="admin-navigation">

        <a href="{{ url('/admin/dashboard') }}"
           class="nav-link {{ ($activePage ?? '') === 'dashboard' ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ url('/admin/employees') }}"
           class="nav-link {{ ($activePage ?? '') === 'employees' ? 'active' : '' }}">
            Employees
        </a>

        <a href="{{ url('/admin/attendance') }}"
           class="nav-link {{ ($activePage ?? '') === 'attendance' ? 'active' : '' }}">
            Attendance
        </a>

        <a href="{{ url('/admin/leave-requests') }}"
           class="nav-link {{ ($activePage ?? '') === 'leave' ? 'active' : '' }}">
            Leave
        </a>

        <a href="{{ url('/admin/payroll') }}"
           class="nav-link {{ ($activePage ?? '') === 'payroll' ? 'active' : '' }}">
            Payroll
        </a>

        <a href="{{ url('/admin/statistics') }}"
           class="nav-link {{ ($activePage ?? '') === 'statistics' ? 'active' : '' }}">
            Statistics
        </a>

        <a href="{{ url('/admin/schedule-management') }}"
           class="nav-link {{ ($activePage ?? '') === 'schedule' ? 'active' : '' }}">
            Schedule
        </a>

        <a href="{{ url('/admin/profile') }}"
           class="nav-link {{ ($activePage ?? '') === 'profile' ? 'active' : '' }}">
            Profile
        </a>

    </nav>

</header>

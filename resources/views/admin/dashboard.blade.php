@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'dashboard'])

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #F6F8F7;
        font-family: 'Inter', Arial, sans-serif;
        color: #172E29;
    }

    .dashboard-container {
        width: 100%;
        min-width: 0;
        margin: 0;
        padding: clamp(22px, 3vw, 44px) clamp(18px, 3vw, 55px);
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        flex-wrap: wrap;
    }

    .workspace-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
        color: #176454;
        margin-bottom: 7px;
    }

    .dashboard-title {
        font-size: clamp(27px, 2.3vw, 36px);
        font-weight: 650;
        line-height: 1.2;
        margin: 0;
        color: #172E29;
    }

    .dashboard-description {
        font-size: 14px;
        color: #687B74;
        margin: 8px 0 0;
    }

    .dashboard-date {
        color: #687B74;
        font-size: 12px;
        font-weight: 500;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 10px;
        padding: 10px 14px;
    }

    /* Statistics */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-top: 32px;
    }

    .stat-card {
        min-height: 130px;
        padding: 21px 22px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 17px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card.primary {
        background: #176454;
        border-color: #176454;
    }

    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: #687B74;
    }

    .stat-card.primary .stat-label {
        color: #D4E7DF;
    }

    .stat-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #E7F0EB;
        color: #176454;
        font-size: 16px;
        font-weight: 700;
    }

    .stat-card.primary .stat-icon {
        background: rgba(255, 255, 255, .16);
        color: #FFFFFF;
    }

    .stat-number {
        display: block;
        font-size: clamp(30px, 3vw, 40px);
        font-weight: 650;
        line-height: 1;
        color: #172E29;
        font-variant-numeric: tabular-nums;
    }

    .stat-card.primary .stat-number {
        color: #FFFFFF;
    }

    /* Dashboard columns */

    .dashboard-content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(320px, .85fr);
        gap: 24px;
        align-items: start;
        margin-top: 30px;
    }

    .dashboard-column {
        min-width: 0;
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin: 0 0 14px;
    }

    .section-heading h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 650;
        color: #172E29;
    }

    .section-count {
        font-size: 12px;
        font-weight: 600;
        color: #176454;
        background: #E7F0EB;
        padding: 6px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }

    /* Employee profiles */

    .employee-card {
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 17px;
        padding: 18px;
    }

    .employee-search {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        border: 1px solid #E3EAE7;
        outline: none;
        border-radius: 10px;
        background: #F8FAF9;
        font-family: inherit;
        font-size: 13px;
        color: #172E29;
        margin-bottom: 8px;
    }

    .employee-search:focus {
        border-color: #176454;
        background: #FFFFFF;
    }

    .employee-search::placeholder {
        color: #899791;
    }

    .employee-row {
        min-height: 72px;
        padding: 12px 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #E8EEEB;
        gap: 14px;
    }

    .employee-row:last-child {
        border-bottom: none;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .employee-avatar {
        width: 39px;
        height: 39px;
        flex-shrink: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #E7F0EB;
        color: #176454;
        font-size: 13px;
        font-weight: 700;
    }

    .employee-name {
        font-size: 13px;
        font-weight: 650;
        color: #172E29;
    }

    .employee-details {
        margin-top: 4px;
        font-size: 11px;
        color: #687B74;
    }

    .view-link {
        font-size: 12px;
        font-weight: 600;
        color: #176454;
        text-decoration: none;
        white-space: nowrap;
    }

    .view-link:hover {
        text-decoration: underline;
    }

    /* Leave requests */

    .leave-list {
        display: flex;
        flex-direction: column;
        gap: 13px;
    }

    .leave-card {
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
        padding: 19px;
    }

    .leave-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .leave-name {
        font-size: 14px;
        font-weight: 650;
        color: #172E29;
    }

    .pending-badge {
        padding: 5px 10px;
        border-radius: 20px;
        background: #F9EBDD;
        color: #A55F29;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .leave-details {
        margin-top: 13px;
        font-size: 12px;
        line-height: 1.6;
        color: #687B74;
    }

    .leave-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 17px;
    }

    .leave-button {
        min-height: 39px;
        border-radius: 10px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }

    .decline-button {
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        color: #52645D;
    }

    .approve-button {
        background: #176454;
        border: 1px solid #176454;
        color: #FFFFFF;
    }

    .decline-button:hover {
        background: #F6F8F7;
    }

    .approve-button:hover {
        background: #124F42;
        border-color: #124F42;
    }

    /* Responsive layout */

    @media (max-width: 900px) {
        .dashboard-content-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 28px;
        }

        .dashboard-date {
            display: none;
        }
    }

    @media (max-width: 600px) {
        .dashboard-container {
            padding: 22px 17px 30px;
        }

        .dashboard-title {
            font-size: 27px;
        }

        .dashboard-description {
            font-size: 12px;
        }

        .stats-grid {
            gap: 9px;
            margin-top: 25px;
        }

        .stat-card {
            min-height: 100px;
            padding: 12px;
            border-radius: 13px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-icon {
            display: none;
        }

        .stat-number {
            font-size: 27px;
            margin-top: 13px;
        }

        .dashboard-content-grid {
            margin-top: 26px;
            gap: 25px;
        }

        .section-heading h2 {
            font-size: 16px;
        }

        .employee-card {
            padding: 12px;
        }

        .employee-row {
            min-height: 65px;
            gap: 8px;
        }

        .employee-avatar {
            width: 34px;
            height: 34px;
            font-size: 11px;
        }

        .employee-name {
            font-size: 12px;
        }

        .employee-details {
            font-size: 10px;
        }

        .view-link {
            font-size: 11px;
        }

        .leave-card {
            padding: 15px;
        }
    }

    @media (max-width: 360px) {
        .dashboard-container {
            padding-left: 13px;
            padding-right: 13px;
        }

        .stats-grid {
            gap: 7px;
        }

        .stat-card {
            padding: 10px;
        }

        .stat-label {
            font-size: 9px;
        }

        .stat-number {
            font-size: 24px;
        }

        .employee-info {
            gap: 8px;
        }

        .employee-details {
            font-size: 9px;
        }
    }
</style>

<main class="dashboard-container">

    <header class="dashboard-header">
        <div>
            <div class="workspace-label">TEAM WORKSPACE</div>

            <h1 class="dashboard-title">Admin dashboard</h1>

            <p class="dashboard-description">
                A clear view of your people and their day.
            </p>
        </div>

        <div class="dashboard-date">
            Team overview
        </div>
    </header>

    <!-- Statistics cards -->

    <section class="stats-grid">

        <div class="stat-card primary">
            <div class="stat-top">
                <span class="stat-label">Employees</span>
                <span class="stat-icon">♙</span>
            </div>

            <span class="stat-number">5</span>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">On the clock</span>
                <span class="stat-icon">◷</span>
            </div>

            <span class="stat-number">4</span>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">On leave</span>
                <span class="stat-icon">▣</span>
            </div>

            <span class="stat-number">1</span>
        </div>

    </section>

    <div class="dashboard-content-grid">

        <!-- Employee profiles -->

        <section class="dashboard-column">

            <div class="section-heading">
                <h2>Employee profiles</h2>
                <span class="section-count">5 total</span>
            </div>

            <div class="employee-card">

                <input
                    type="text"
                    class="employee-search"
                    placeholder="Search name or department"
                    aria-label="Search employees"
                >

                <div class="employee-row">
                    <div class="employee-info">
                        <div class="employee-avatar">MT</div>

                        <div>
                            <div class="employee-name">Maya Thompson</div>
                            <div class="employee-details">
                                Product Designer · EMP-024
                            </div>
                        </div>
                    </div>

                    <a href="#" class="view-link">View →</a>
                </div>

                <div class="employee-row">
                    <div class="employee-info">
                        <div class="employee-avatar">LC</div>

                        <div>
                            <div class="employee-name">Leah Chen</div>
                            <div class="employee-details">
                                Engineer · EMP-018
                            </div>
                        </div>
                    </div>

                    <a href="#" class="view-link">View →</a>
                </div>

                <div class="employee-row">
                    <div class="employee-info">
                        <div class="employee-avatar">NW</div>

                        <div>
                            <div class="employee-name">Noah Williams</div>
                            <div class="employee-details">
                                Marketing Lead · EMP-011
                            </div>
                        </div>
                    </div>

                    <a href="#" class="view-link">View →</a>
                </div>

                <div class="employee-row">
                    <div class="employee-info">
                        <div class="employee-avatar">SP</div>

                        <div>
                            <div class="employee-name">Sofia Patel</div>
                            <div class="employee-details">
                                People Partner · EMP-030
                            </div>
                        </div>
                    </div>

                    <a href="#" class="view-link">View →</a>
                </div>

                <div class="employee-row">
                    <div class="employee-info">
                        <div class="employee-avatar">AM</div>

                        <div>
                            <div class="employee-name">Alex Morgan</div>
                            <div class="employee-details">
                                Design Manager · EMP-006
                            </div>
                        </div>
                    </div>

                    <a href="#" class="view-link">View →</a>
                </div>

            </div>

        </section>

        <!-- Leave requests -->

        <section class="dashboard-column">

            <div class="section-heading">
                <h2>Leave requests</h2>
                <span class="section-count">2 pending</span>
            </div>

            <div class="leave-list">

                <article class="leave-card">

                    <div class="leave-card-header">
                        <span class="leave-name">Leah Chen</span>
                        <span class="pending-badge">Pending</span>
                    </div>

                    <div class="leave-details">
                        Nov 12–13 · Annual leave · 2 days
                    </div>

                    <div class="leave-actions">
                        <button type="button" class="leave-button decline-button">
                            Decline
                        </button>

                        <button type="button" class="leave-button approve-button">
                            Approve
                        </button>
                    </div>

                </article>

                <article class="leave-card">

                    <div class="leave-card-header">
                        <span class="leave-name">Noah Williams</span>
                        <span class="pending-badge">Pending</span>
                    </div>

                    <div class="leave-details">
                        Nov 16 · Personal leave · 1 day
                    </div>

                    <div class="leave-actions">
                        <button type="button" class="leave-button decline-button">
                            Decline
                        </button>

                        <button type="button" class="leave-button approve-button">
                            Approve
                        </button>
                    </div>

                </article>

            </div>

        </section>

    </div>

</main>

@endsection
@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')

@include('partials.navbar', ['activePage' => 'home'])

<style>
    .employee-dashboard {
        width: 100%;
        min-width: 0;
        padding: clamp(20px, 2.5vw, 44px) clamp(16px, 3vw, 55px);
        font-family: 'Inter', Arial, sans-serif;
        color: #172E29;
    }

    .dashboard-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        margin-bottom: clamp(22px, 2.5vw, 34px);
    }

    .dashboard-date {
        color: #176454;
        font-size: clamp(10px, 0.7vw, 12px);
        font-weight: 700;
        letter-spacing: 1.1px;
        margin-bottom: 8px;
    }

    .dashboard-title {
        font-size: clamp(26px, 2.4vw, 38px);
        font-weight: 700;
        letter-spacing: -1.1px;
        line-height: 1.2;
        margin: 0;
    }

    .dashboard-subtitle {
        color: #71817C;
        font-size: clamp(13px, 0.9vw, 15px);
        margin: 8px 0 0;
    }

    .dashboard-header-note {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 10px 14px;
        background: #EAF2EE;
        border: 1px solid #DCE9E2;
        border-radius: 12px;
        color: #176454;
        font-size: 12px;
        font-weight: 600;
    }

    .dashboard-header-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #31966F;
        flex-shrink: 0;
    }

    /* Today's shift */

    .shift-card {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 25px;
        overflow: hidden;
        padding: clamp(22px, 2.5vw, 36px);
        margin-bottom: clamp(25px, 3vw, 38px);
        background: linear-gradient(120deg, #176454 0%, #124F43 100%);
        border-radius: 22px;
        color: #FFFFFF;
        box-shadow: 0 12px 30px rgba(23, 100, 84, 0.12);
    }

    .shift-card::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -90px;
        top: -130px;
        border: 1px solid rgba(255, 255, 255, 0.09);
        border-radius: 50%;
        box-shadow:
            0 0 0 28px rgba(255, 255, 255, 0.025),
            0 0 0 56px rgba(255, 255, 255, 0.02);
        pointer-events: none;
    }

    .shift-information,
    .shift-time-panel {
        position: relative;
        z-index: 1;
        min-width: 0;
    }

    .shift-top {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
    }

    .shift-label {
        color: #C4DAD1;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.1px;
    }

    .shift-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 11px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.13);
        border-radius: 20px;
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .shift-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #8BE0B4;
    }

    .shift-heading {
        font-size: clamp(23px, 2vw, 30px);
        font-weight: 650;
        letter-spacing: -0.5px;
        line-height: 1.25;
        margin: 0 0 9px;
    }

    .shift-details {
        color: #D4E7DF;
        font-size: clamp(12px, 0.9vw, 14px);
        margin: 0;
    }

    .shift-time-panel {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.18);
    }

    .shift-time-label {
        color: #C4DAD1;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 7px;
    }

    .shift-time {
        font-size: clamp(32px, 3.2vw, 46px);
        font-weight: 700;
        letter-spacing: -1.5px;
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    .clocked-in-time {
        color: #D4E7DF;
        font-size: 12px;
        margin-top: 8px;
    }

    .shift-link {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        min-height: 42px;
        padding: 0 17px;
        margin-top: 20px;
        background: #FFFFFF;
        border: 1px solid #FFFFFF;
        border-radius: 10px;
        color: #176454;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .shift-link:hover {
        background: #EAF2EE;
        transform: translateY(-1px);
    }

    /* Dashboard content */

    .dashboard-content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 25px;
    }

    .dashboard-main-column,
    .dashboard-side-column {
        display: flex;
        flex-direction: column;
        gap: 25px;
        min-width: 0;
    }

    .dashboard-section {
        min-width: 0;
    }

    .section-heading-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .section-heading {
        font-size: clamp(16px, 1.2vw, 19px);
        font-weight: 700;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .section-caption {
        color: #82908B;
        font-size: 11px;
        font-weight: 500;
    }

    /* Weekly overview */

    .weekly-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px;
    }

    .summary-card {
        position: relative;
        min-width: 0;
        min-height: 145px;
        padding: clamp(15px, 1.5vw, 22px);
        background: #FFFFFF;
        border: 1px solid #E6ECE9;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(23, 46, 41, 0.025);
    }

    .summary-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-bottom: 17px;
    }

    .summary-label {
        color: #71817C;
        font-size: clamp(11px, 0.8vw, 13px);
        font-weight: 600;
    }

    .summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        background: #EAF2EE;
        border-radius: 10px;
        color: #176454;
        font-size: 16px;
        font-weight: 700;
    }

    .summary-value {
        font-size: clamp(25px, 2.1vw, 34px);
        font-weight: 700;
        letter-spacing: -1px;
        line-height: 1.15;
        margin-bottom: 7px;
        overflow-wrap: anywhere;
    }

    .summary-caption {
        color: #82908B;
        font-size: 11px;
    }

    /* Quick actions */

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .action-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        min-height: 65px;
        padding: 14px 16px;
        background: #FFFFFF;
        border: 1px solid #E2EAE5;
        border-radius: 13px;
        color: #172E29;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease;
    }

    .action-link:hover {
        background: #F3F8F5;
        border-color: #B8D2C5;
        transform: translateY(-2px);
    }

    .action-link-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        background: #EAF2EE;
        border-radius: 9px;
        color: #176454;
        font-size: 16px;
    }

    .action-link-arrow {
        color: #176454;
        font-size: 17px;
    }

    /* Upcoming time off */

    .upcoming-panel {
        padding: clamp(17px, 1.8vw, 24px);
        background: #FFFFFF;
        border: 1px solid #E6ECE9;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(23, 46, 41, 0.025);
    }

    .upcoming-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #F7FAF8;
        border: 1px solid #EAF0EC;
        border-radius: 13px;
    }

    .date-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 58px;
        height: 68px;
        background: #E4F0E9;
        border-radius: 11px;
        color: #176454;
    }

    .date-month {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.8px;
    }

    .date-day {
        font-size: 27px;
        font-weight: 700;
        line-height: 1.15;
    }

    .upcoming-information {
        min-width: 0;
    }

    .leave-title {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 5px;
    }

    .leave-details {
        color: #71817C;
        font-size: 11px;
        margin-bottom: 9px;
    }

    .leave-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        background: #E4F3E9;
        border-radius: 20px;
        color: #24734D;
        font-size: 10px;
        font-weight: 700;
    }

    .upcoming-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #EAF0EC;
    }

    .upcoming-footer-text {
        color: #82908B;
        font-size: 11px;
    }

    .upcoming-link {
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .upcoming-link:hover {
        text-decoration: underline;
    }

    /* Dashboard footer */

    .dashboard-footer {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #82908B;
        font-size: 11px;
        margin-top: clamp(24px, 3vw, 38px);
        padding-top: 17px;
        border-top: 1px solid #E5ECE8;
    }

    .dashboard-footer-mark {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        background: #EAF2EE;
        border-radius: 50%;
        color: #176454;
        font-size: 12px;
        font-weight: 700;
    }

    /* Small phones */

    @media (max-width: 420px) {
        .employee-dashboard {
            padding-left: 16px;
            padding-right: 16px;
        }

        .dashboard-header-note {
            font-size: 10px;
        }

        .weekly-summary {
            gap: 9px;
        }

        .summary-card {
            min-height: 135px;
            padding: 13px;
        }

        .summary-icon {
            width: 29px;
            height: 29px;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }

        .action-link {
            min-height: 56px;
        }

        .shift-time {
            font-size: clamp(27px, 8vw, 34px);
        }
    }

    /* Tablet */

    @media (min-width: 768px) {
        .dashboard-header {
            margin-bottom: 28px;
        }

        .shift-card {
            grid-template-columns: minmax(0, 1.2fr) minmax(230px, 0.8fr);
            align-items: center;
            gap: 30px;
        }

        .shift-time-panel {
            padding-top: 0;
            padding-left: 25px;
            border-top: none;
            border-left: 1px solid rgba(255, 255, 255, 0.18);
        }

        .dashboard-content-grid {
            grid-template-columns: minmax(0, 1.15fr) minmax(260px, 0.85fr);
            gap: 20px;
        }

        .dashboard-main-column,
        .dashboard-side-column {
            gap: 25px;
        }

        .weekly-summary {
            gap: 15px;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }
    }

    /* Desktop */

    @media (min-width: 1100px) {
        .dashboard-header {
            margin-bottom: 32px;
        }

        .shift-card {
            grid-template-columns: minmax(0, 1.3fr) minmax(300px, 0.7fr);
            min-height: 240px;
            margin-bottom: 32px;
        }

        .shift-time-panel {
            padding-left: 35px;
        }

        .dashboard-content-grid {
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
            gap: 26px;
        }

        .dashboard-main-column,
        .dashboard-side-column {
            gap: 28px;
        }

        .weekly-summary {
            gap: 16px;
        }

        .summary-card {
            min-height: 155px;
        }

        .quick-actions {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .action-link {
            min-height: 72px;
            padding: 14px 17px;
        }

        .upcoming-panel {
            min-height: 190px;
        }
    }

    /* Wide desktop */

    @media (min-width: 1500px) {
        .employee-dashboard {
            padding: 44px 55px 35px;
        }

        .shift-card {
            min-height: 255px;
            padding: 38px;
        }

        .dashboard-content-grid {
            grid-template-columns: minmax(0, 1.2fr) minmax(360px, 0.8fr);
            gap: 32px;
        }

        .dashboard-main-column,
        .dashboard-side-column {
            gap: 32px;
        }

        .summary-card {
            min-height: 165px;
            padding: 25px;
        }

        .upcoming-panel {
            padding: 25px;
        }
    }
</style>

<main class="employee-dashboard">

    <header class="dashboard-header">
        <div>
            <div class="dashboard-date">
                MONDAY, NOVEMBER 9
            </div>

            <h1 class="dashboard-title">
                Good morning, Maya.
            </h1>

            <p class="dashboard-subtitle">
                A little clarity for your workday.
            </p>
        </div>

        <div class="dashboard-header-note">
            <span class="dashboard-header-dot"></span>
            Your workspace is up to date
        </div>
    </header>

    <!-- Today's shift -->

    <section class="shift-card">

        <div class="shift-information">

            <div class="shift-top">
                <span class="shift-label">TODAY'S SHIFT</span>

                <span class="shift-status">
                    <span class="shift-status-dot"></span>
                    On the clock
                </span>
            </div>

            <h2 class="shift-heading">
                Make today count.
            </h2>

            <p class="shift-details">
                09:00 AM – 05:00 PM · Office
            </p>

            <a href="/employee/time-entry" class="shift-link">
                View time entry <span aria-hidden="true">→</span>
            </a>

        </div>

        <div class="shift-time-panel">

            <div class="shift-time-label">
                TIME WORKED TODAY
            </div>

            <div class="shift-time">
                00h 00m 00sec
            </div>

            <div class="clocked-in-time">
                Clocked in at 09:00 AM
            </div>

        </div>

    </section>

    <div class="dashboard-content-grid">

        <div class="dashboard-main-column">

            <!-- Weekly overview -->

            <section class="dashboard-section">

                <div class="section-heading-row">
                    <h2 class="section-heading">
                        Your week at a glance
                    </h2>

                    <span class="section-caption">
                        Weekly overview
                    </span>
                </div>

                <div class="weekly-summary">

                    <div class="summary-card">

                        <div class="summary-card-top">
                            <span class="summary-label">
                                Hours this week
                            </span>

                            <span class="summary-icon" aria-hidden="true">
                                ◷
                            </span>
                        </div>

                        <div class="summary-value">
                            0.68
                        </div>

                        <div class="summary-caption">
                            of 40 scheduled hours
                        </div>

                    </div>

                    <div class="summary-card">

                        <div class="summary-card-top">
                            <span class="summary-label">
                                Leave available
                            </span>

                            <span class="summary-icon" aria-hidden="true">
                                ◫
                            </span>
                        </div>

                        <div class="summary-value">
                            12 days
                        </div>

                        <div class="summary-caption">
                            Annual leave balance
                        </div>

                    </div>

                </div>

            </section>

            <!-- Quick actions -->

            <section class="dashboard-section">

                <div class="section-heading-row">
                    <h2 class="section-heading">
                        Quick actions
                    </h2>

                    <span class="section-caption">
                        Shortcuts
                    </span>
                </div>

                <div class="quick-actions">

                    <a href="/employee/leave-requests" class="action-link">
                        <span class="action-link-icon" aria-hidden="true">
                            +
                        </span>

                        <span>Request leave</span>

                        <span class="action-link-arrow" aria-hidden="true">
                            →
                        </span>
                    </a>

                    <a href="/employee/profile" class="action-link">
                        <span class="action-link-icon" aria-hidden="true">
                            ◉
                        </span>

                        <span>My profile</span>

                        <span class="action-link-arrow" aria-hidden="true">
                            →
                        </span>
                    </a>

                </div>

            </section>

        </div>

        <div class="dashboard-side-column">

            <!-- Upcoming time off -->

            <section class="dashboard-section">

                <div class="section-heading-row">
                    <h2 class="section-heading">
                        Coming up
                    </h2>

                    <span class="section-caption">
                        Upcoming time off
                    </span>
                </div>

                <div class="upcoming-panel">

                    <div class="upcoming-card">

                        <div class="date-badge">
                            <span class="date-month">NOV</span>
                            <span class="date-day">20</span>
                        </div>

                        <div class="upcoming-information">

                            <div class="leave-title">
                                A little time to recharge
                            </div>

                            <div class="leave-details">
                                Annual leave · 1 day
                            </div>

                            <span class="leave-status">
                                Approved
                            </span>

                        </div>

                    </div>

                    <div class="upcoming-footer">

                        <span class="upcoming-footer-text">
                            Your next scheduled time off
                        </span>

                        <a href="/employee/leave-requests" class="upcoming-link">
                            View requests →
                        </a>

                    </div>

                </div>

            </section>

        </div>

    </div>

    <footer class="dashboard-footer">
        <span class="dashboard-footer-mark" aria-hidden="true">
            ✓
        </span>

        All caught up. Have a great Monday.
    </footer>

</main>

@endsection
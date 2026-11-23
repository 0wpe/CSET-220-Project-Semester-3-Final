
@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')

@include('partials.navbar', ['activePage' => 'home'])

<style>
    * {
        box-sizing: border-box;
    }

    .employee-dashboard {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 18px 24px 30px;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .dashboard-date {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .dashboard-title {
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .dashboard-subtitle {
        color: #687B74;
        font-size: 13px;
        margin: 0 0 20px;
    }

    /* Today's shift */

    .shift-card {
        background: #176454;
        border-radius: 20px;
        padding: 20px;
        color: #FFFFFF;
        margin-bottom: 28px;
    }

    .shift-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .shift-label {
        color: #C4DAD1;
        font-size: 10px;
        font-weight: 600;
    }

    .shift-status {
        background: #2A7565;
        border-radius: 13px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
    }

    .shift-heading {
        font-size: 24px;
        font-weight: 600;
        margin: 0 0 6px;
    }

    .shift-details {
        color: #D4E7DF;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .shift-time {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .clocked-in-time {
        color: #D4E7DF;
        font-size: 12px;
        margin-bottom: 16px;
    }

    .shift-link {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 32px;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 12px;
        color: #172E29;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .shift-link:hover {
        background: #F2F6F4;
    }

    /* Section headings */

    .section-heading {
        font-size: 17px;
        font-weight: 600;
        margin: 0 0 12px;
    }

    /* Weekly overview */

    .weekly-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }

    .summary-card {
        min-height: 111px;
        padding: 15px 16px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .summary-label {
        display: block;
        color: #687B74;
        font-size: 12px;
        font-weight: 400;
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 29px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .summary-caption {
        color: #687B74;
        font-size: 11px;
    }

    /* Quick actions */

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }

    .action-link {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 50px;
        padding: 10px;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 12px;
        color: #172E29;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
    }

    .action-link:hover {
        background: #F6F8F7;
    }

    /* Upcoming time off */

    .upcoming-card {
        display: flex;
        align-items: center;
        gap: 15px;
        min-height: 113px;
        padding: 16px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .date-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 55px;
        height: 70px;
        background: #E7F0EB;
        border-radius: 12px;
        color: #176454;
    }

    .date-month {
        font-size: 10px;
        font-weight: 600;
    }

    .date-day {
        font-size: 25px;
        font-weight: 600;
    }

    .leave-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .leave-details {
        color: #687B74;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .leave-status {
        display: inline-block;
        padding: 4px 10px;
        background: #E7F0EB;
        border-radius: 13px;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    /* Footer */

    .dashboard-footer {
        color: #687B74;
        font-size: 12px;
        margin-top: 20px;
    }

    @media (max-width: 360px) {
        .employee-dashboard {
            padding-left: 16px;
            padding-right: 16px;
        }

        .weekly-summary,
        .quick-actions {
            gap: 8px;
        }

        .summary-card {
            padding: 12px;
        }

        .shift-time {
            font-size: 32px;
        }
    }
</style>

<main class="employee-dashboard">

    <div class="dashboard-date">
        MONDAY, NOVEMBER 9
    </div>

    <h1 class="dashboard-title">
        Good morning, Maya.
    </h1>

    <p class="dashboard-subtitle">
        A little clarity for your workday.
    </p>

    <!-- Today's shift -->

    <section class="shift-card">

        <div class="shift-top">
            <span class="shift-label">TODAY’S SHIFT</span>
            <span class="shift-status">On the clock</span>
        </div>

        <h2 class="shift-heading">
            Make today count.
        </h2>

        <div class="shift-details">
            09:00 AM – 05:00 PM · Office
        </div>

        <div class="shift-time">
            00h 00m 00sec
        </div>

        <div class="clocked-in-time">
            Clocked in at 09:00 AM
        </div>

        <a href="/employee/time-entry" class="shift-link">
            View time entry&nbsp; →
        </a>

    </section>

    <!-- Weekly overview -->

    <section>

        <h2 class="section-heading">
            Your week at a glance
        </h2>

        <div class="weekly-summary">

            <div class="summary-card">
                <span class="summary-label">
                    Hours this week
                </span>

                <div class="summary-value">
                    0.68
                </div>

                <div class="summary-caption">
                    of 40 scheduled
                </div>
            </div>

            <div class="summary-card">
                <span class="summary-label">
                    Leave available
                </span>

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

    <section>

        <h2 class="section-heading">
            Quick actions
        </h2>

        <div class="quick-actions">

            <a href="/employee/leave-requests" class="action-link">
                Request leave
            </a>

            <a href="/employee/profile" class="action-link">
                My profile
            </a>

        </div>

    </section>

    <!-- Upcoming time off -->

    <section>

        <h2 class="section-heading">
            Coming up
        </h2>

        <div class="upcoming-card">

            <div class="date-badge">
                <span class="date-month">NOV</span>
                <span class="date-day">20</span>
            </div>

            <div>
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

    </section>

    <p class="dashboard-footer">
        All caught up. Have a great Monday.
    </p>

</main>

@endsection


@extends('layouts.app')

@section('title', 'Admin Profile View')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'employees'])

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

    .profile-container {
        width: 100%;
        max-width: 402px;
        margin: 0 auto;
        padding: 18px 24px 40px;
    }

    /* Page heading */

    .back-link {
        display: inline-block;
        margin-bottom: 18px;
        color: #176454;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .profile-title {
        margin: 0;
        font-size: 26px;
        font-weight: 600;
        line-height: 1.45;
        color: #172E29;
    }

    .profile-subtitle {
        margin: 3px 0 0;
        font-size: 12px;
        font-weight: 400;
        color: #687B74;
    }

    /* Employee record card */

    .employee-card {
        margin-top: 20px;
        padding: 17px 18px;
        min-height: 132px;
        background: #FFFFFF;
        border-radius: 18px;
    }

    .employee-name {
        margin: 0;
        font-size: 23px;
        font-weight: 600;
        line-height: 1.45;
        color: #172E29;
    }

    .employee-position {
        margin-top: 3px;
        font-size: 13px;
        font-weight: 400;
        color: #687B74;
    }

    .employee-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 17px;
    }

    .active-badge {
        padding: 4px 10px;
        background: #E7F0EB;
        border-radius: 13px;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    .employment-type {
        font-size: 11px;
        font-weight: 400;
        color: #687B74;
    }

    /* Section headings */

    .section-heading {
        margin: 27px 0 12px;
        font-size: 17px;
        font-weight: 600;
        color: #172E29;
    }

    /* Contact and employment details */

    .details-card {
        padding: 15px 17px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .detail-label {
        margin-bottom: 5px;
        font-size: 11px;
        font-weight: 500;
        color: #687B74;
    }

    .detail-value {
        font-size: 13px;
        font-weight: 500;
        color: #172E29;
        overflow-wrap: anywhere;
    }

    .detail-divider {
        height: 1px;
        margin: 15px 0;
        background: #DFE7E2;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .schedule-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .schedule-row .detail-label {
        margin: 0;
    }

    .schedule-row .detail-value {
        font-size: 11px;
    }

    /* Attendance summary */

    .attendance-card {
        padding: 17px;
        background: #E7F0EB;
        border-radius: 16px;
    }

    .attendance-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .attendance-label {
        margin-bottom: 5px;
        font-size: 11px;
        font-weight: 500;
        color: #687B74;
    }

    .attendance-value {
        font-size: 13px;
        font-weight: 500;
        color: #172E29;
    }

    .leave-balance {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 22px;
        font-size: 12px;
        font-weight: 400;
        color: #176454;
    }

    .leave-days {
        font-size: 14px;
        font-weight: 600;
    }

    /* Upcoming leave */

    .upcoming-leave-card {
        min-height: 63px;
        padding: 0 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FFFFFF;
        border-radius: 12px;
    }

    .upcoming-leave-date {
        font-size: 12px;
        font-weight: 500;
        color: #172E29;
    }

    .approved-badge {
        padding: 4px 10px;
        background: #E7F0EB;
        border-radius: 13px;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    /* Responsive adjustments */

    @media (max-width: 360px) {
        .profile-container {
            padding-left: 16px;
            padding-right: 16px;
        }

        .profile-title {
            font-size: 24px;
        }

        .employee-name {
            font-size: 21px;
        }

        .schedule-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<main class="profile-container">

    <!-- Back navigation -->

    <a href="{{ url('/admin/employees') }}" class="back-link">
        ← Employee profiles
    </a>


    <!-- Page heading -->

    <h1 class="profile-title">Admin profile view</h1>

    <p class="profile-subtitle">
        Employee record · EMP-024
    </p>


    <!-- Employee record -->

    <section class="employee-card">

        <h2 class="employee-name">Maya Thompson</h2>

        <div class="employee-position">
            Product Designer · Design
        </div>

        <div class="employee-card-footer">

            <span class="active-badge">
                Active employee
            </span>

            <span class="employment-type">
                Full-time
            </span>

        </div>

    </section>


    <!-- Contact and employment -->

    <section>

        <h2 class="section-heading">
            Contact &amp; employment
        </h2>

        <div class="details-card">

            <!-- Work email -->

            <div>
                <div class="detail-label">Work email</div>
                <div class="detail-value">
                    maya.thompson@tempo.co
                </div>
            </div>

            <div class="detail-divider"></div>


            <!-- Phone and location -->

            <div class="detail-grid">

                <div>
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">
                        +1 (415) 555-0182
                    </div>
                </div>

                <div>
                    <div class="detail-label">Location</div>
                    <div class="detail-value">
                        San Francisco, CA
                    </div>
                </div>

            </div>

            <div class="detail-divider"></div>


            <!-- Manager and joined date -->

            <div class="detail-grid">

                <div>
                    <div class="detail-label">Manager</div>
                    <div class="detail-value">
                        Alex Morgan
                    </div>
                </div>

                <div>
                    <div class="detail-label">Joined</div>
                    <div class="detail-value">
                        March 12, 2023
                    </div>
                </div>

            </div>

            <div class="detail-divider"></div>


            <!-- Work schedule -->

            <div class="schedule-row">

                <div class="detail-label">
                    Work schedule
                </div>

                <div class="detail-value">
                    Mon–Fri · 09:00–17:00 PT
                </div>

            </div>

        </div>

    </section>


    <!-- Attendance and leave -->

    <section>

        <h2 class="section-heading">
            Attendance &amp; leave
        </h2>

        <div class="attendance-card">

            <div class="attendance-grid">

                <div>
                    <div class="attendance-label">
                        Today’s status
                    </div>

                    <div class="attendance-value">
                        On the clock
                    </div>
                </div>

                <div>
                    <div class="attendance-label">
                        Clock-in
                    </div>

                    <div class="attendance-value">
                        09:00 AM
                    </div>
                </div>

            </div>

            <div class="leave-balance">

                <span>Annual leave balance</span>

                <span class="leave-days">
                    12 days
                </span>

            </div>

        </div>

    </section>


    <!-- Upcoming leave -->

    <section>

        <h2 class="section-heading">
            Upcoming leave
        </h2>

        <div class="upcoming-leave-card">

            <span class="upcoming-leave-date">
                Nov 20 · Annual leave
            </span>

            <span class="approved-badge">
                Approved
            </span>

        </div>

    </section>

</main>

@endsection

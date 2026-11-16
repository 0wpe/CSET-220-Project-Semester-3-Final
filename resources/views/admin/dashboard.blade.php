
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
        max-width: 402px;
        margin: 0 auto;
        padding: 18px 24px 40px;
    }

    /* Dashboard heading */

    .workspace-label {
        font-size: 11px;
        font-weight: 600;
        color: #176454;
        margin-bottom: 5px;
    }

    .dashboard-title {
        font-size: 28px;
        font-weight: 600;
        line-height: 1.45;
        margin: 0;
        color: #172E29;
    }

    .dashboard-description {
        font-size: 13px;
        font-weight: 400;
        color: #687B74;
        margin: 3px 0 0;
    }

    /* Statistics cards */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 32px;
    }

    .stat-card {
        height: 85px;
        padding: 12px;
        background: #FFFFFF;
        border-radius: 14px;
    }

    .stat-card.primary {
        background: #176454;
    }

    .stat-label {
        font-size: 10px;
        font-weight: 500;
        color: #687B74;
    }

    .stat-card.primary .stat-label {
        color: #D4E7DF;
    }

    .stat-number {
        display: block;
        margin-top: 7px;
        font-size: 28px;
        font-weight: 600;
        line-height: 1.45;
        color: #172E29;
    }

    .stat-card.primary .stat-number {
        color: #FFFFFF;
    }

    /* Section headings */

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 27px 0 12px;
    }

    .section-heading h2 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #172E29;
    }

    .section-count {
        font-size: 12px;
        font-weight: 600;
        color: #176454;
    }

    /* Employee profiles */

    .employee-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 12px;
    }

    .employee-search {
        width: 100%;
        height: 38px;
        padding: 0 12px;
        border: none;
        outline: none;
        border-radius: 9px;
        background: #F6F8F7;
        font-family: inherit;
        font-size: 12px;
        color: #172E29;
        margin-bottom: 10px;
    }

    .employee-search::placeholder {
        color: #687B74;
    }

    .employee-row {
        min-height: 52px;
        padding: 7px 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #DFE7E2;
    }

    .employee-row:last-child {
        border-bottom: none;
    }

    .employee-name {
        font-size: 13px;
        font-weight: 600;
        color: #172E29;
    }

    .employee-details {
        margin-top: 3px;
        font-size: 10px;
        font-weight: 400;
        color: #687B74;
    }

    .view-link {
        font-size: 11px;
        font-weight: 500;
        color: #176454;
        text-decoration: none;
    }

    .view-link:hover {
        text-decoration: underline;
    }

    /* Leave requests */

    .leave-card {
        background: #FFFFFF;
        border-radius: 14px;
        padding: 12px 15px;
        margin-bottom: 12px;
    }

    .leave-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .leave-name {
        font-size: 13px;
        font-weight: 600;
        color: #172E29;
    }

    .pending-badge {
        padding: 4px 10px;
        border-radius: 13px;
        background: #F9EBDD;
        color: #A55F29;
        font-size: 11px;
        font-weight: 500;
    }

    .leave-details {
        margin-top: 8px;
        font-size: 11px;
        color: #687B74;
    }

    .leave-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 12px;
    }

    .leave-button {
        height: 30px;
        border-radius: 11px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .decline-button {
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        color: #172E29;
    }

    .approve-button {
        background: #176454;
        border: 1px solid #176454;
        color: #FFFFFF;
    }

    .leave-button:hover {
        opacity: 0.85;
    }

    /* Responsive adjustments */

    @media (max-width: 360px) {
        .dashboard-container {
            padding-left: 16px;
            padding-right: 16px;
        }

        .stats-grid {
            gap: 8px;
        }

        .stat-card {
            padding: 10px;
        }

        .dashboard-title {
            font-size: 25px;
        }
    }
</style>

<main class="dashboard-container">

    <!-- Dashboard heading -->

    <div class="workspace-label">TEAM WORKSPACE</div>

    <h1 class="dashboard-title">Admin dashboard</h1>

    <p class="dashboard-description">
        A clear view of your people and their day.
    </p>


    <!-- Statistics cards -->

    <section class="stats-grid">

        <div class="stat-card primary">
            <span class="stat-label">Employees</span>
            <span class="stat-number">5</span>
        </div>

        <div class="stat-card">
            <span class="stat-label">On the clock</span>
            <span class="stat-number">4</span>
        </div>

        <div class="stat-card">
            <span class="stat-label">On leave</span>
            <span class="stat-number">1</span>
        </div>

    </section>


    <!-- Employee profiles -->

    <section>

        <div class="section-heading">
            <h2>Employee profiles</h2>
            <span class="section-count">5 total</span>
        </div>

        <div class="employee-card">

            <input
                type="text"
                class="employee-search"
                placeholder="Search name or department"
            >

            <div class="employee-row">
                <div>
                    <div class="employee-name">Maya Thompson</div>
                    <div class="employee-details">
                        Product Designer · EMP-024
                    </div>
                </div>

                <a href="#" class="view-link">View →</a>
            </div>

            <div class="employee-row">
                <div>
                    <div class="employee-name">Leah Chen</div>
                    <div class="employee-details">
                        Engineer · EMP-018
                    </div>
                </div>

                <a href="#" class="view-link">View →</a>
            </div>

            <div class="employee-row">
                <div>
                    <div class="employee-name">Noah Williams</div>
                    <div class="employee-details">
                        Marketing Lead · EMP-011
                    </div>
                </div>

                <a href="#" class="view-link">View →</a>
            </div>

            <div class="employee-row">
                <div>
                    <div class="employee-name">Sofia Patel</div>
                    <div class="employee-details">
                        People Partner · EMP-030
                    </div>
                </div>

                <a href="#" class="view-link">View →</a>
            </div>

            <div class="employee-row">
                <div>
                    <div class="employee-name">Alex Morgan</div>
                    <div class="employee-details">
                        Design Manager · EMP-006
                    </div>
                </div>

                <a href="#" class="view-link">View →</a>
            </div>

        </div>

    </section>


    <!-- Leave requests -->

    <section>

        <div class="section-heading">
            <h2>Leave requests</h2>
            <span class="section-count">2 pending</span>
        </div>


        <!-- Leah Chen -->

        <div class="leave-card">

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

        </div>


        <!-- Noah Williams -->

        <div class="leave-card">

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

        </div>

    </section>

</main>

@endsection
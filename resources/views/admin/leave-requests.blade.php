
@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'leave-requests'])

<style>
    * {
        box-sizing: border-box;
    }

    .leave-management {
        max-width: 1100px;
        margin: 35px auto;
        padding: 0 24px 40px;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .leave-header {
        margin-bottom: 28px;
    }

    .section-label {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .leave-header h1 {
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 8px;
    }

    .leave-header p {
        color: #687B74;
        font-size: 13px;
        margin: 0;
    }

    .leave-overview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 32px;
    }

    .overview-card {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 12px;
        min-height: 72px;
    }

    .overview-card.pending {
        background: #176454;
    }

    .overview-label {
        display: block;
        color: #687B74;
        font-size: 11px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .pending .overview-label {
        color: #D4E7DF;
    }

    .overview-number {
        display: block;
        color: #172E29;
        font-size: 22px;
        font-weight: 600;
    }

    .pending .overview-number {
        color: #FFFFFF;
    }

    .leave-section {
        margin-bottom: 32px;
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }

    .section-heading h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .section-count {
        color: #176454;
        font-size: 12px;
        font-weight: 500;
    }

    .leave-card {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(23, 46, 41, 0.03);
    }

    .leave-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .employee-name {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .employee-department {
        color: #687B74;
        font-size: 11px;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 500;
        white-space: nowrap;
    }

    .status.pending {
        background: #FBEBDC;
        color: #8E4F22;
    }

    .status.accepted {
        background: #E7F0EB;
        color: #176454;
    }

    .status.denied {
        background: #F9E9E7;
        color: #A13C32;
    }

    .leave-details {
        margin-top: 16px;
    }

    .leave-dates {
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 7px;
    }

    .leave-reason {
        color: #687B74;
        font-size: 11px;
        line-height: 1.5;
    }

    .leave-actions {
        display: flex;
        gap: 10px;
        margin-top: 16px;
    }

    .leave-button {
        flex: 1;
        height: 34px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .deny-button {
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        color: #172E29;
    }

    .accept-button {
        background: #176454;
        border: 1px solid #176454;
        color: #FFFFFF;
    }

    .history-card {
        min-height: 114px;
    }

    .history-details {
        margin-top: 12px;
    }

    .reviewed-by {
        color: #687B74;
        font-size: 10px;
        margin-top: 7px;
    }

    .history-footer {
        color: #687B74;
        font-size: 11px;
        margin-top: 16px;
    }

    @media (max-width: 600px) {
        .leave-management {
            margin-top: 24px;
            padding: 0 16px 30px;
        }

        .leave-header h1 {
            font-size: 25px;
        }

        .leave-overview {
            gap: 8px;
        }

        .overview-card {
            padding: 10px;
        }

        .section-heading h2 {
            font-size: 16px;
        }
    }
</style>

<div class="leave-management">

    <!-- Leave Overview -->
    <div class="leave-header">
        <div class="section-label">TEAM TIME OFF</div>

        <h1>Leave Management</h1>

        <p>Review requests and manage your team's time off.</p>
    </div>

    <div class="leave-overview">

        <div class="overview-card pending">
            <span class="overview-label">Pending</span>
            <span class="overview-number">2</span>
        </div>

        <div class="overview-card">
            <span class="overview-label">Accepted</span>
            <span class="overview-number">1</span>
        </div>

        <div class="overview-card">
            <span class="overview-label">Denied</span>
            <span class="overview-number">1</span>
        </div>

    </div>

    <!-- Pending Requests -->
    <section class="leave-section">

        <div class="section-heading">
            <h2>Pending Requests</h2>
            <span class="section-count">2 incoming</span>
        </div>

        <!-- Leah Chen -->
        <div class="leave-card">

            <div class="leave-card-header">
                <div>
                    <h3 class="employee-name">Leah Chen</h3>
                    <div class="employee-department">
                        Engineering · EMP-018
                    </div>
                </div>

                <span class="status pending">Pending</span>
            </div>

            <div class="leave-details">
                <div class="leave-dates">
                    Nov 12–13, 2026 · Annual leave · 2 days
                </div>

                <div class="leave-reason">
                    Reason: Family commitments
                </div>
            </div>

            <div class="leave-actions">
                <button type="button" class="leave-button deny-button">
                    Deny
                </button>

                <button type="button" class="leave-button accept-button">
                    Accept
                </button>
            </div>

        </div>

        <!-- Noah Williams -->
        <div class="leave-card">

            <div class="leave-card-header">
                <div>
                    <h3 class="employee-name">Noah Williams</h3>
                    <div class="employee-department">
                        Marketing · EMP-011
                    </div>
                </div>

                <span class="status pending">Pending</span>
            </div>

            <div class="leave-details">
                <div class="leave-dates">
                    Nov 16, 2026 · Personal leave · 1 day
                </div>

                <div class="leave-reason">
                    Reason: Personal appointment
                </div>
            </div>

            <div class="leave-actions">
                <button type="button" class="leave-button deny-button">
                    Deny
                </button>

                <button type="button" class="leave-button accept-button">
                    Accept
                </button>
            </div>

        </div>

    </section>

    <!-- Request History -->
    <section class="leave-section">

        <div class="section-heading">
            <h2>Request History</h2>
            <span class="section-count">2 reviewed</span>
        </div>

        <!-- Maya Thompson -->
        <div class="leave-card history-card">

            <div class="leave-card-header">
                <div>
                    <h3 class="employee-name">Maya Thompson</h3>
                    <div class="employee-department">
                        Design · EMP-024
                    </div>
                </div>

                <span class="status accepted">Accepted</span>
            </div>

            <div class="history-details">
                <div class="leave-dates">
                    Nov 20, 2026 · Annual leave · 1 day
                </div>

                <div class="reviewed-by">
                    Accepted by Alex Morgan · Nov 9, 2026
                </div>
            </div>

        </div>

        <!-- Sofia Patel -->
        <div class="leave-card history-card">

            <div class="leave-card-header">
                <div>
                    <h3 class="employee-name">Sofia Patel</h3>
                    <div class="employee-department">
                        People · EMP-030
                    </div>
                </div>

                <span class="status denied">Denied</span>
            </div>

            <div class="history-details">
                <div class="leave-dates">
                    Nov 6, 2026 · Personal leave · 1 day
                </div>

                <div class="reviewed-by">
                    Reason: Minimum team coverage required
                </div>
            </div>

        </div>

        <div class="history-footer">
            All 4 requests shown
        </div>

    </section>

</div>

@endsection

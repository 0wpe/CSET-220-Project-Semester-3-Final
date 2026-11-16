
@extends('layouts.app')

@section('title', 'Payroll Management')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'payroll'])

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

    .payroll-container {
        width: 100%;
        max-width: 402px;
        margin: 0 auto;
        padding: 18px 24px 40px;
    }

    /* Page heading */

    .workspace-label {
        font-size: 11px;
        font-weight: 600;
        color: #176454;
        margin-bottom: 5px;
    }

    .payroll-title {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        line-height: 1.45;
        color: #172E29;
    }

    .payroll-description {
        margin: 3px 0 0;
        font-size: 13px;
        font-weight: 400;
        color: #687B74;
    }

    /* Month selector */

    .month-selector {
        width: 100%;
        height: 49px;
        margin-top: 19px;
        padding: 0 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        color: #172E29;
        cursor: pointer;
    }

    .month-selector span:last-child {
        font-size: 15px;
    }

    /* Payroll summary */

    .payroll-summary {
        margin-top: 16px;
        padding: 15px 18px;
        height: 125px;
        background: #176454;
        border-radius: 20px;
    }

    .summary-label {
        font-size: 10px;
        font-weight: 600;
        color: #C4DAD1;
    }

    .summary-amount {
        display: block;
        margin-top: 3px;
        font-size: 29px;
        font-weight: 600;
        line-height: 1.45;
        color: #FFFFFF;
    }

    .summary-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
        font-size: 12px;
        font-weight: 500;
        color: #FFFFFF;
    }

    .summary-status {
        font-weight: 400;
        color: #D4E7DF;
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
        font-size: 11px;
        font-weight: 500;
        color: #176454;
    }

    /* Employee payroll list */

    .payroll-list {
        padding: 0 8px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .payroll-row {
        min-height: 43px;
        padding: 5px 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #DFE7E2;
        border-radius: 8px;
    }

    .payroll-row:last-child {
        border-bottom: none;
    }

    .payroll-row.selected {
        background: #E7F0EB;
    }

    .employee-name {
        font-size: 12px;
        font-weight: 600;
        color: #172E29;
    }

    .employee-id {
        margin-top: 2px;
        font-size: 10px;
        font-weight: 400;
        color: #687B74;
    }

    .payroll-row-right {
        text-align: right;
    }

    .employee-amount {
        font-size: 12px;
        font-weight: 600;
        color: #172E29;
    }

    .employee-status {
        display: block;
        margin-top: 2px;
        font-size: 10px;
        font-weight: 500;
        color: #176454;
    }

    .employee-status.pending {
        color: #8E4F22;
    }

    /* Payroll details */

    .payroll-details {
        margin-top: 16px;
        padding: 14px 18px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .details-title {
        margin: 0 0 16px;
        font-size: 14px;
        font-weight: 600;
        color: #172E29;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 9px;
        font-size: 12px;
        font-weight: 400;
        color: #687B74;
    }

    .detail-amount {
        color: #172E29;
        font-weight: 500;
    }

    .total-divider {
        height: 1px;
        margin: 10px 0;
        background: #DFE7E2;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        font-weight: 600;
        color: #172E29;
    }

    .total-amount {
        color: #176454;
    }

    /* Action buttons */

    .payroll-actions {
        display: flex;
        flex-direction: column;
        gap: 18px;
        margin-top: 16px;
    }

    .action-button {
        width: 100%;
        height: 44px;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .generate-button {
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        color: #172E29;
    }

    .process-button {
        height: 49px;
        background: #176454;
        border: 1px solid #176454;
        color: #FFFFFF;
    }

    .action-button:hover {
        opacity: 0.88;
    }

    /* Responsive adjustments */

    @media (max-width: 360px) {
        .payroll-container {
            padding-left: 16px;
            padding-right: 16px;
        }

        .payroll-title {
            font-size: 25px;
        }

        .summary-amount {
            font-size: 26px;
        }

        .summary-footer {
            font-size: 10px;
        }
    }
</style>

<main class="payroll-container">

    <!-- Page heading -->

    <div class="workspace-label">TEAM PAYROLL</div>

    <h1 class="payroll-title">Payroll management</h1>

    <p class="payroll-description">
        Review, generate and process monthly pay.
    </p>


    <!-- Payroll month selector -->

    <button type="button" class="month-selector">
        <span>November 2026</span>
        <span>⌄</span>
    </button>


    <!-- Payroll summary -->

    <section class="payroll-summary">

        <div class="summary-label">TOTAL NET PAYROLL</div>

        <span class="summary-amount">$26,200.00</span>

        <div class="summary-footer">
            <span>5 employees</span>

            <span class="summary-status">
                3 processed · 2 pending
            </span>
        </div>

    </section>


    <!-- Employee payroll -->

    <section>

        <div class="section-heading">
            <h2>Employee payroll</h2>
            <span class="section-count">5 employees</span>
        </div>

        <div class="payroll-list">

            <!-- Maya Thompson -->

            <div class="payroll-row">

                <div>
                    <div class="employee-name">Maya Thompson</div>
                    <div class="employee-id">EMP-024</div>
                </div>

                <div class="payroll-row-right">
                    <div class="employee-amount">$4,750</div>
                    <span class="employee-status">Processed ›</span>
                </div>

            </div>


            <!-- Leah Chen -->

            <div class="payroll-row selected">

                <div>
                    <div class="employee-name">Leah Chen</div>
                    <div class="employee-id">EMP-018</div>
                </div>

                <div class="payroll-row-right">
                    <div class="employee-amount">$5,800</div>
                    <span class="employee-status pending">Pending ⌃</span>
                </div>

            </div>


            <!-- Noah Williams -->

            <div class="payroll-row">

                <div>
                    <div class="employee-name">Noah Williams</div>
                    <div class="employee-id">EMP-011</div>
                </div>

                <div class="payroll-row-right">
                    <div class="employee-amount">$5,100</div>
                    <span class="employee-status">Processed ›</span>
                </div>

            </div>


            <!-- Sofia Patel -->

            <div class="payroll-row">

                <div>
                    <div class="employee-name">Sofia Patel</div>
                    <div class="employee-id">EMP-030</div>
                </div>

                <div class="payroll-row-right">
                    <div class="employee-amount">$4,200</div>
                    <span class="employee-status">Processed ›</span>
                </div>

            </div>


            <!-- Alex Morgan -->

            <div class="payroll-row">

                <div>
                    <div class="employee-name">Alex Morgan</div>
                    <div class="employee-id">EMP-006</div>
                </div>

                <div class="payroll-row-right">
                    <div class="employee-amount">$6,350</div>
                    <span class="employee-status pending">Pending ›</span>
                </div>

            </div>

        </div>

    </section>


    <!-- Selected employee payroll details -->

    <section class="payroll-details">

        <h3 class="details-title">Leah Chen · November</h3>

        <div class="detail-row">
            <span>Regular · 160 hours</span>
            <span class="detail-amount">$6,400.00</span>
        </div>

        <div class="detail-row">
            <span>Overtime · 3 hours</span>
            <span class="detail-amount">$180.00</span>
        </div>

        <div class="detail-row">
            <span>Deductions</span>
            <span class="detail-amount">−$780.00</span>
        </div>

        <div class="total-divider"></div>

        <div class="total-row">
            <span>Total salary</span>
            <span class="total-amount">$5,800.00</span>
        </div>

    </section>


    <!-- Payroll actions -->

    <section class="payroll-actions">

        <button type="button" class="action-button generate-button">
            Generate payslip
        </button>

        <button type="button" class="action-button process-button">
            Process payroll · 2 pending
        </button>

    </section>

</main>

@endsection

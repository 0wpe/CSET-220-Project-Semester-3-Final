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
        min-width: 0;
        margin: 0;
        padding: clamp(22px, 3vw, 44px) clamp(18px, 3vw, 55px);
    }

    /* Page heading */

    .payroll-header {
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

    .payroll-title {
        margin: 0;
        font-size: clamp(27px, 2.3vw, 36px);
        font-weight: 650;
        line-height: 1.2;
        color: #172E29;
    }

    .payroll-description {
        margin: 8px 0 0;
        font-size: 14px;
        color: #687B74;
    }

    .payroll-period-label {
        padding: 10px 14px;
        border: 1px solid #E3EAE7;
        border-radius: 10px;
        background: #FFFFFF;
        color: #687B74;
        font-size: 12px;
        font-weight: 500;
    }

    /* Month selector */

    .month-selector {
        width: 100%;
        max-width: 300px;
        min-height: 48px;
        margin-top: 25px;
        padding: 0 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 11px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        color: #172E29;
        cursor: pointer;
    }

    .month-selector:hover {
        border-color: #176454;
    }

    .month-selector span:last-child {
        color: #176454;
        font-size: 17px;
    }

    /* Payroll summary */

    .payroll-summary {
        position: relative;
        overflow: hidden;
        margin-top: 20px;
        padding: 25px 28px;
        min-height: 155px;
        background: #176454;
        border-radius: 19px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .payroll-summary::after {
        content: '';
        position: absolute;
        width: 210px;
        height: 210px;
        right: -55px;
        top: -105px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 50%;
        box-shadow: 0 0 0 24px rgba(255, 255, 255, .035),
                    0 0 0 48px rgba(255, 255, 255, .025);
        pointer-events: none;
    }

    .summary-label {
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .5px;
        color: #C4DAD1;
    }

    .summary-amount {
        display: block;
        margin-top: 9px;
        font-size: clamp(32px, 3.5vw, 43px);
        font-weight: 650;
        line-height: 1.15;
        color: #FFFFFF;
        font-variant-numeric: tabular-nums;
    }

    .summary-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
        font-size: 12px;
        font-weight: 500;
        color: #FFFFFF;
    }

    .summary-status {
        color: #D4E7DF;
        font-weight: 400;
    }

    /* Payroll workspace */

    .payroll-workspace {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(320px, .9fr);
        gap: 22px;
        align-items: start;
        margin-top: 32px;
    }

    .payroll-column {
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
        font-size: 11px;
        font-weight: 600;
        color: #176454;
        background: #E7F0EB;
        padding: 6px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }

    /* Employee payroll list */

    .payroll-list {
        padding: 5px 13px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
    }

    .payroll-row {
        min-height: 68px;
        padding: 11px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #E8EEEB;
        border-radius: 9px;
        gap: 12px;
    }

    .payroll-row:last-child {
        border-bottom: none;
    }

    .payroll-row.selected {
        background: #E7F0EB;
        border-bottom-color: transparent;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .employee-avatar {
        width: 37px;
        height: 37px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #E7F0EB;
        color: #176454;
        font-size: 11px;
        font-weight: 700;
    }

    .selected .employee-avatar {
        background: #176454;
        color: #FFFFFF;
    }

    .employee-name {
        font-size: 13px;
        font-weight: 650;
        color: #172E29;
    }

    .employee-id {
        margin-top: 4px;
        font-size: 10px;
        color: #687B74;
    }

    .payroll-row-right {
        text-align: right;
        flex-shrink: 0;
    }

    .employee-amount {
        font-size: 13px;
        font-weight: 650;
        color: #172E29;
        font-variant-numeric: tabular-nums;
    }

    .employee-status {
        display: block;
        margin-top: 4px;
        font-size: 10px;
        font-weight: 600;
        color: #176454;
    }

    .employee-status.pending {
        color: #A55F29;
    }

    /* Payroll details */

    .payroll-details {
        padding: 22px 23px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
    }

    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
    }

    .details-title {
        margin: 0;
        font-size: 16px;
        font-weight: 650;
        color: #172E29;
    }

    .details-period {
        font-size: 10px;
        font-weight: 600;
        color: #176454;
        background: #E7F0EB;
        border-radius: 20px;
        padding: 6px 9px;
        white-space: nowrap;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 12px;
        color: #687B74;
    }

    .detail-amount {
        color: #172E29;
        font-weight: 600;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .total-divider {
        height: 1px;
        margin: 17px 0;
        background: #DFE7E2;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        font-weight: 650;
        color: #172E29;
    }

    .total-amount {
        color: #176454;
        font-size: 18px;
        font-variant-numeric: tabular-nums;
    }

    /* Action buttons */

    .payroll-actions {
        display: flex;
        flex-direction: column;
        gap: 11px;
        margin-top: 16px;
    }

    .action-button {
        width: 100%;
        min-height: 46px;
        border-radius: 11px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }

    .generate-button {
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        color: #172E29;
    }

    .process-button {
        background: #176454;
        border: 1px solid #176454;
        color: #FFFFFF;
    }

    .generate-button:hover {
        background: #F6F8F7;
    }

    .process-button:hover {
        background: #124F42;
        border-color: #124F42;
    }

    /* Responsive layout */

    @media (max-width: 950px) {
        .payroll-workspace {
            grid-template-columns: minmax(0, 1fr);
            gap: 25px;
        }

        .payroll-period-label {
            display: none;
        }
    }

    @media (max-width: 600px) {
        .payroll-container {
            padding: 22px 17px 30px;
        }

        .payroll-title {
            font-size: 27px;
        }

        .payroll-description {
            font-size: 12px;
        }

        .month-selector {
            max-width: none;
            margin-top: 22px;
        }

        .payroll-summary {
            min-height: 130px;
            padding: 18px;
            border-radius: 15px;
            margin-top: 14px;
        }

        .summary-label {
            font-size: 10px;
        }

        .summary-amount {
            font-size: 32px;
        }

        .summary-footer {
            font-size: 10px;
            margin-top: 14px;
        }

        .payroll-workspace {
            margin-top: 25px;
        }

        .section-heading h2 {
            font-size: 16px;
        }

        .payroll-list {
            padding: 4px 8px;
            border-radius: 14px;
        }

        .payroll-row {
            min-height: 62px;
            padding: 9px 8px;
        }

        .employee-avatar {
            width: 33px;
            height: 33px;
            font-size: 10px;
        }

        .employee-info {
            gap: 8px;
        }

        .employee-name {
            font-size: 12px;
        }

        .employee-id {
            font-size: 9px;
        }

        .employee-amount {
            font-size: 12px;
        }

        .employee-status {
            font-size: 9px;
        }

        .payroll-details {
            padding: 18px;
            border-radius: 14px;
        }

        .details-title {
            font-size: 14px;
        }

        .detail-row {
            font-size: 11px;
        }

        .total-row {
            font-size: 13px;
        }

        .total-amount {
            font-size: 16px;
        }
    }

    @media (max-width: 360px) {
        .payroll-container {
            padding-left: 13px;
            padding-right: 13px;
        }

        .summary-amount {
            font-size: 28px;
        }

        .summary-footer {
            font-size: 9px;
        }

        .payroll-row {
            gap: 7px;
        }

        .employee-info {
            gap: 6px;
        }

        .employee-name {
            font-size: 11px;
        }

        .employee-amount {
            font-size: 11px;
        }
    }
</style>

<main class="payroll-container">

    <!-- Page heading -->

    <header class="payroll-header">
        <div>
            <div class="workspace-label">TEAM PAYROLL</div>

            <h1 class="payroll-title">Payroll management</h1>

            <p class="payroll-description">
                Review, generate and process monthly pay.
            </p>
        </div>

        <div class="payroll-period-label">
            Monthly payroll overview
        </div>
    </header>

    <!-- Payroll month selector -->

    <button type="button" class="month-selector">
        <span>November 2026</span>
        <span>⌄</span>
    </button>

    <!-- Payroll summary -->

    <section class="payroll-summary">

        <div>
            <div class="summary-label">TOTAL NET PAYROLL</div>

            <span class="summary-amount">$26,200.00</span>
        </div>

        <div class="summary-footer">
            <span>5 employees</span>

            <span class="summary-status">
                3 processed · 2 pending
            </span>
        </div>

    </section>

    <!-- Payroll workspace -->

    <div class="payroll-workspace">

        <!-- Employee payroll -->

        <section class="payroll-column">

            <div class="section-heading">
                <h2>Employee payroll</h2>
                <span class="section-count">5 employees</span>
            </div>

            <div class="payroll-list">

                <!-- Maya Thompson -->

                <div class="payroll-row">

                    <div class="employee-info">
                        <div class="employee-avatar">MT</div>

                        <div>
                            <div class="employee-name">Maya Thompson</div>
                            <div class="employee-id">EMP-024</div>
                        </div>
                    </div>

                    <div class="payroll-row-right">
                        <div class="employee-amount">$4,750</div>
                        <span class="employee-status">Processed ›</span>
                    </div>

                </div>

                <!-- Leah Chen -->

                <div class="payroll-row selected">

                    <div class="employee-info">
                        <div class="employee-avatar">LC</div>

                        <div>
                            <div class="employee-name">Leah Chen</div>
                            <div class="employee-id">EMP-018</div>
                        </div>
                    </div>

                    <div class="payroll-row-right">
                        <div class="employee-amount">$5,800</div>
                        <span class="employee-status pending">Pending ⌃</span>
                    </div>

                </div>

                <!-- Noah Williams -->

                <div class="payroll-row">

                    <div class="employee-info">
                        <div class="employee-avatar">NW</div>

                        <div>
                            <div class="employee-name">Noah Williams</div>
                            <div class="employee-id">EMP-011</div>
                        </div>
                    </div>

                    <div class="payroll-row-right">
                        <div class="employee-amount">$5,100</div>
                        <span class="employee-status">Processed ›</span>
                    </div>

                </div>

                <!-- Sofia Patel -->

                <div class="payroll-row">

                    <div class="employee-info">
                        <div class="employee-avatar">SP</div>

                        <div>
                            <div class="employee-name">Sofia Patel</div>
                            <div class="employee-id">EMP-030</div>
                        </div>
                    </div>

                    <div class="payroll-row-right">
                        <div class="employee-amount">$4,200</div>
                        <span class="employee-status">Processed ›</span>
                    </div>

                </div>

                <!-- Alex Morgan -->

                <div class="payroll-row">

                    <div class="employee-info">
                        <div class="employee-avatar">AM</div>

                        <div>
                            <div class="employee-name">Alex Morgan</div>
                            <div class="employee-id">EMP-006</div>
                        </div>
                    </div>

                    <div class="payroll-row-right">
                        <div class="employee-amount">$6,350</div>
                        <span class="employee-status pending">Pending ›</span>
                    </div>

                </div>

            </div>

        </section>

        <!-- Selected employee payroll details -->

        <section class="payroll-column">

            <div class="section-heading">
                <h2>Payroll breakdown</h2>
                <span class="section-count">Selected employee</span>
            </div>

            <div class="payroll-details">

                <div class="details-header">
                    <h3 class="details-title">Leah Chen</h3>
                    <span class="details-period">November 2026</span>
                </div>

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

            </div>

            <!-- Payroll actions -->

            <section class="payroll-actions">

                <button type="button" class="action-button generate-button">
                    Generate payslip
                </button>

                <button type="button" class="action-button process-button">
                    Process payroll · 2 pending
                </button>

            </section>

        </section>

    </div>

</main>

@endsection
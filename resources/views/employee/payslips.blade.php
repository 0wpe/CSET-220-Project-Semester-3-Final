
@extends('layouts.app')

@section('title', 'Your Payslips | Tempo HR')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #F6F8F7;
        font-family: 'Inter', sans-serif;
    }

    .payslip-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 18px 24px 40px;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .page-title {
        color: #172E29;
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .page-subtitle {
        color: #687B74;
        font-size: 13px;
        margin: 0 0 26px;
    }

    /* Latest net pay */

    .latest-pay {
        padding: 17px 20px;
        background: #176454;
        border-radius: 20px;
        color: #FFFFFF;
    }

    .latest-label {
        color: #C4DAD1;
        font-size: 10px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .latest-amount {
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .latest-date {
        color: #D4E7DF;
        font-size: 12px;
    }

    /* Monthly history */

    .history-section {
        margin-top: 26px;
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .section-title {
        color: #172E29;
        font-size: 17px;
        font-weight: 600;
        margin: 0;
    }

    .section-year {
        color: #176454;
        font-size: 12px;
        font-weight: 500;
    }

    /* Expanded payslip */

    .payslip-card {
        padding: 18px;
        background: #FFFFFF;
        border-radius: 20px;
        margin-bottom: 16px;
    }

    .payslip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .payslip-month {
        color: #172E29;
        font-size: 17px;
        font-weight: 600;
        margin: 0;
    }

    .paid-badge {
        padding: 5px 10px;
        background: #E7F0EB;
        border-radius: 12px;
        color: #176454;
        font-size: 10px;
        font-weight: 500;
    }

    .employee-name {
        color: #687B74;
        font-size: 12px;
        margin-top: 8px;
    }

    .period-label {
        color: #687B74;
        font-size: 11px;
        font-weight: 500;
        margin-top: 24px;
        margin-bottom: 5px;
    }

    .period-date {
        color: #172E29;
        font-size: 13px;
        font-weight: 500;
    }

    .payslip-divider {
        height: 1px;
        background: #DFE7E2;
        margin: 14px 0;
    }

    .earning-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin: 18px 0;
        color: #687B74;
        font-size: 12px;
    }

    .earning-amount {
        color: #172E29;
        font-weight: 500;
        white-space: nowrap;
    }

    .net-pay-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #172E29;
        font-size: 16px;
        font-weight: 600;
    }

    .net-pay-amount {
        color: #176454;
        white-space: nowrap;
    }

    /* Download button */

    .download-button {
        width: 100%;
        height: 43px;
        margin-top: 20px;
        background: #176454;
        border: none;
        border-radius: 12px;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .download-button:hover {
        background: #125447;
    }

    /* Previous payslips */

    .previous-payslip {
        background: #FFFFFF;
        border-radius: 16px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .previous-toggle {
        width: 100%;
        min-height: 78px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: transparent;
        border: none;
        text-align: left;
        cursor: pointer;
        font-family: inherit;
    }

    .previous-info {
        min-width: 0;
    }

    .previous-month {
        color: #172E29;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .previous-period {
        color: #687B74;
        font-size: 11px;
    }

    .previous-payment {
        text-align: right;
        flex-shrink: 0;
    }

    .previous-amount {
        color: #176454;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        white-space: nowrap;
    }

    .previous-status {
        color: #687B74;
        font-size: 11px;
    }

    .previous-arrow {
        color: #176454;
        font-size: 16px;
        margin-left: 5px;
    }

    .previous-details {
        display: none;
        padding: 0 18px 18px;
    }

    .previous-details.open {
        display: block;
    }

    .previous-details .earning-row {
        margin: 12px 0;
    }

    .previous-download {
        width: 100%;
        height: 40px;
        background: #E7F0EB;
        border: none;
        border-radius: 10px;
        color: #176454;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    @media (min-width: 700px) {
        .latest-pay,
        .history-section {
            max-width: 600px;
        }
    }

    @media (max-width: 380px) {
        .payslip-page {
            padding-left: 16px;
            padding-right: 16px;
        }

        .previous-toggle {
            padding-left: 12px;
            padding-right: 12px;
        }

        .previous-month {
            font-size: 13px;
        }
    }
</style>

@include('partials.navbar', ['activePage' => 'payslips'])

<main class="payslip-page">

    <div class="eyebrow">
        MY PAY
    </div>

    <h1 class="page-title">
        Your payslips
    </h1>

    <p class="page-subtitle">
        Your earnings, clearly broken down.
    </p>

    <!-- Latest net pay -->

    <section class="latest-pay">

        <div class="latest-label">
            LATEST NET PAY · OCTOBER 2026
        </div>

        <div class="latest-amount">
            $4,750.00
        </div>

        <div class="latest-date">
            Paid October 31, 2026
        </div>

    </section>

    <!-- Monthly history -->

    <section class="history-section">

        <div class="section-heading">

            <h2 class="section-title">
                Monthly history
            </h2>

            <span class="section-year">
                2026
            </span>

        </div>

        <!-- October payslip -->

        <article class="payslip-card">

            <div class="payslip-header">

                <h3 class="payslip-month">
                    October 2026
                </h3>

                <span class="paid-badge">
                    Paid
                </span>

            </div>

            <div class="employee-name">
                Maya Thompson · EMP-024
            </div>

            <div class="period-label">
                Pay period
            </div>

            <div class="period-date">
                Oct 1 – Oct 31, 2026
            </div>

            <div class="payslip-divider"></div>

            <div class="earning-row">
                <span>Regular earnings · 160 h</span>
                <span class="earning-amount">$5,200.00</span>
            </div>

            <div class="earning-row">
                <span>Overtime · 4 h</span>
                <span class="earning-amount">$195.00</span>
            </div>

            <div class="earning-row">
                <span>Deductions · tax &amp; benefits</span>
                <span class="earning-amount">−$645.00</span>
            </div>

            <div class="payslip-divider"></div>

            <div class="net-pay-row">
                <span>Net pay</span>
                <span class="net-pay-amount">$4,750.00</span>
            </div>

            <button type="button" class="download-button" onclick="alert('PDF downloads will be available when payroll is connected.')">
                Download PDF
            </button>

        </article>

        <!-- September payslip -->

        <article class="previous-payslip">

            <button
                type="button"
                class="previous-toggle"
                aria-expanded="false"
                aria-controls="september-details"
            >

                <div class="previous-info">
                    <div class="previous-month">September 2026</div>
                    <div class="previous-period">Sep 1 – Sep 30, 2026</div>
                </div>

                <div class="previous-payment">
                    <div class="previous-amount">$4,555.00 <span class="previous-arrow">›</span></div>
                    <div class="previous-status">Paid · View details</div>
                </div>

            </button>

            <div class="previous-details" id="september-details">

                <div class="payslip-divider"></div>

                <div class="earning-row">
                    <span>Regular earnings · 160 h</span>
                    <span class="earning-amount">$5,000.00</span>
                </div>

                <div class="earning-row">
                    <span>Overtime · 2 h</span>
                    <span class="earning-amount">$100.00</span>
                </div>

                <div class="earning-row">
                    <span>Deductions · tax &amp; benefits</span>
                    <span class="earning-amount">−$545.00</span>
                </div>

                <div class="payslip-divider"></div>

                <div class="net-pay-row">
                    <span>Net pay</span>
                    <span class="net-pay-amount">$4,555.00</span>
                </div>

                <button type="button" class="previous-download" style="margin-top: 16px;" onclick="alert('PDF downloads will be available when payroll is connected.')">
                    Download PDF
                </button>

            </div>

        </article>

        <!-- August payslip -->

        <article class="previous-payslip">

            <button
                type="button"
                class="previous-toggle"
                aria-expanded="false"
                aria-controls="august-details"
            >

                <div class="previous-info">
                    <div class="previous-month">August 2026</div>
                    <div class="previous-period">Aug 1 – Aug 31, 2026</div>
                </div>

                <div class="previous-payment">
                    <div class="previous-amount">$4,640.00 <span class="previous-arrow">›</span></div>
                    <div class="previous-status">Paid · View details</div>
                </div>

            </button>

            <div class="previous-details" id="august-details">

                <div class="payslip-divider"></div>

                <div class="earning-row">
                    <span>Regular earnings · 160 h</span>
                    <span class="earning-amount">$5,000.00</span>
                </div>

                <div class="earning-row">
                    <span>Overtime · 4 h</span>
                    <span class="earning-amount">$200.00</span>
                </div>

                <div class="earning-row">
                    <span>Deductions · tax &amp; benefits</span>
                    <span class="earning-amount">−$560.00</span>
                </div>

                <div class="payslip-divider"></div>

                <div class="net-pay-row">
                    <span>Net pay</span>
                    <span class="net-pay-amount">$4,640.00</span>
                </div>

                <button type="button" class="previous-download" style="margin-top: 16px;" onclick="alert('PDF downloads will be available when payroll is connected.')">
                    Download PDF
                </button>

            </div>

        </article>

    </section>

</main>

<script>
    document.querySelectorAll('.previous-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const detailsId = this.getAttribute('aria-controls');
            const details = document.getElementById(detailsId);
            const isOpen = details.classList.toggle('open');

            this.setAttribute('aria-expanded', isOpen);
        });
    });
</script>

@endsection

@extends('layouts.app')

@section('title', 'Your Payslips | Tempo HR')

@section('content')

@include('partials.navbar', ['activePage' => 'payslips'])

<style>
    .payslip-page {
        width: 100%;
        min-width: 0;
        margin: 0;
        padding: clamp(22px, 2.5vw, 42px) clamp(18px, 3vw, 55px);
        color: #172E29;
        font-family: 'Inter', Arial, sans-serif;
    }

    .page-header {
        margin-bottom: clamp(24px, 3vw, 34px);
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.1px;
        margin-bottom: 9px;
    }

    .eyebrow::before {
        content: '';
        width: 7px;
        height: 7px;
        background: #176454;
        border-radius: 50%;
    }

    .page-title {
        color: #172E29;
        font-size: clamp(27px, 2.5vw, 38px);
        font-weight: 700;
        letter-spacing: -1.2px;
        line-height: 1.2;
        margin: 0 0 7px;
    }

    .page-subtitle {
        color: #71817C;
        font-size: clamp(13px, 1vw, 15px);
        margin: 0;
    }

    .payslip-content-grid {
        display: grid;
        grid-template-columns: minmax(280px, 0.8fr) minmax(0, 1.2fr);
        gap: clamp(20px, 2.2vw, 32px);
        align-items: start;
    }

    .payslip-summary-column,
    .payslip-history-column {
        min-width: 0;
    }

    /* Latest payment */

    .latest-pay {
        position: relative;
        overflow: hidden;
        padding: clamp(24px, 2.5vw, 32px);
        background: linear-gradient(135deg, #176454 0%, #124F43 100%);
        border-radius: 20px;
        color: #FFFFFF;
        box-shadow: 0 8px 22px rgba(23, 100, 84, 0.13);
    }

    .latest-pay::after {
        content: '';
        position: absolute;
        width: 210px;
        height: 210px;
        right: -85px;
        top: -105px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 50%;
        box-shadow: 0 0 0 28px rgba(255, 255, 255, 0.035),
                    0 0 0 56px rgba(255, 255, 255, 0.025);
        pointer-events: none;
    }

    .latest-label {
        position: relative;
        z-index: 1;
        color: #C4DAD1;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 13px;
    }

    .latest-amount {
        position: relative;
        z-index: 1;
        font-size: clamp(32px, 3vw, 45px);
        font-weight: 700;
        letter-spacing: -1.5px;
        margin-bottom: 7px;
        overflow-wrap: anywhere;
    }

    .latest-date {
        position: relative;
        z-index: 1;
        color: #D4E7DF;
        font-size: 12px;
    }

    /* History section */

    .history-section {
        min-width: 0;
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .section-title {
        color: #172E29;
        font-size: clamp(17px, 1.4vw, 20px);
        font-weight: 700;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .section-year {
        padding: 6px 11px;
        background: #E7F0EB;
        border-radius: 20px;
        color: #176454;
        font-size: 11px;
        font-weight: 700;
    }

    /* Latest payslip card */

    .payslip-card {
        padding: clamp(19px, 2vw, 26px);
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(23, 46, 41, 0.035);
    }

    .payslip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .payslip-month {
        color: #172E29;
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .paid-badge {
        padding: 6px 11px;
        background: #E7F0EB;
        border-radius: 20px;
        color: #176454;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .employee-name {
        color: #71817C;
        font-size: 12px;
        margin-top: 9px;
    }

    .period-label {
        color: #82918B;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        margin-top: 24px;
        margin-bottom: 6px;
    }

    .period-date {
        color: #172E29;
        font-size: 13px;
        font-weight: 600;
    }

    .payslip-divider {
        height: 1px;
        background: #E7EDE9;
        margin: 17px 0;
    }

    .earning-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin: 17px 0;
        color: #71817C;
        font-size: 12px;
    }

    .earning-amount {
        color: #172E29;
        font-weight: 600;
        white-space: nowrap;
    }

    .net-pay-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        color: #172E29;
        font-size: 16px;
        font-weight: 700;
    }

    .net-pay-amount {
        color: #176454;
        white-space: nowrap;
    }

    .download-button {
        width: 100%;
        min-height: 47px;
        margin-top: 22px;
        padding: 12px 16px;
        background: #176454;
        border: none;
        border-radius: 12px;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s, transform 0.2s;
    }

    .download-button:hover {
        background: #124F43;
        transform: translateY(-1px);
    }

    /* Previous payslips */

    .previous-payslip {
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 15px;
        margin-top: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(23, 46, 41, 0.025);
    }

    .previous-toggle {
        width: 100%;
        min-height: 82px;
        padding: 15px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: transparent;
        border: none;
        text-align: left;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.2s;
    }

    .previous-toggle:hover {
        background: #F8FAF9;
    }

    .previous-info {
        min-width: 0;
    }

    .previous-month {
        color: #172E29;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .previous-period {
        color: #82918B;
        font-size: 11px;
    }

    .previous-payment {
        text-align: right;
        flex-shrink: 0;
    }

    .previous-amount {
        color: #176454;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 6px;
        white-space: nowrap;
    }

    .previous-status {
        color: #82918B;
        font-size: 10px;
    }

    .previous-arrow {
        display: inline-block;
        color: #176454;
        font-size: 17px;
        margin-left: 5px;
        transition: transform 0.2s;
    }

    .previous-toggle[aria-expanded="true"] .previous-arrow {
        transform: rotate(90deg);
    }

    .previous-details {
        display: none;
        padding: 0 18px 18px;
    }

    .previous-details.open {
        display: block;
    }

    .previous-details .earning-row {
        margin: 13px 0;
    }

    .previous-download {
        width: 100%;
        min-height: 42px;
        padding: 10px 14px;
        background: #E7F0EB;
        border: none;
        border-radius: 10px;
        color: #176454;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .previous-download:hover {
        background: #D5E8DC;
    }

    /* Empty state */

    .empty-state {
        padding: clamp(35px, 5vw, 65px) 20px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 18px;
        color: #71817C;
        font-size: 14px;
        text-align: center;
        box-shadow: 0 4px 16px rgba(23, 46, 41, 0.035);
    }

    /* Responsive layout */

    @media (max-width: 1099px) {
        .payslip-content-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .payslip-summary-column {
            max-width: none;
        }

        .latest-pay {
            padding: 26px;
        }
    }

    @media (max-width: 767px) {
        .payslip-page {
            padding: 23px 18px 35px;
        }

        .page-header {
            margin-bottom: 22px;
        }

        .payslip-content-grid {
            gap: 22px;
        }

        .latest-pay {
            padding: 23px;
            border-radius: 17px;
        }

        .history-section {
            margin-top: 0;
        }

        .payslip-card {
            padding: 19px;
        }

        .previous-toggle {
            min-height: 76px;
            padding: 13px 15px;
        }

        .previous-details {
            padding: 0 15px 16px;
        }
    }

    @media (max-width: 400px) {
        .payslip-page {
            padding-left: 14px;
            padding-right: 14px;
        }

        .payslip-card {
            padding: 15px;
        }

        .payslip-month {
            font-size: 16px;
        }

        .previous-toggle {
            padding-left: 12px;
            padding-right: 12px;
        }

        .previous-month {
            font-size: 13px;
        }

        .previous-amount {
            font-size: 12px;
        }

        .previous-status {
            font-size: 9px;
        }

        .earning-row {
            font-size: 11px;
        }

        .net-pay-row {
            font-size: 14px;
        }
    }

    @media (min-width: 1500px) {
        .payslip-content-grid {
            grid-template-columns: minmax(320px, 0.8fr) minmax(0, 1.2fr);
            gap: 36px;
        }

        .latest-pay {
            padding: 34px;
        }

        .payslip-card {
            padding: 28px;
        }

        .previous-toggle {
            padding: 20px 22px;
        }
    }
</style>

<main class="payslip-page">

    <header class="page-header">

        <div class="eyebrow">
            MY PAY
        </div>

        <h1 class="page-title">
            Your payslips
        </h1>

        <p class="page-subtitle">
            Your earnings, clearly broken down.
        </p>

    </header>

    @if($payrolls->isNotEmpty())

        @php
            $latestPayslip = $payrolls->first();
            $previousPayslips = $payrolls->slice(1);
            $latestMonth = \Carbon\Carbon::parse($latestPayslip->payroll_month);
        @endphp

        <div class="payslip-content-grid">

            <div class="payslip-summary-column">

                <section class="latest-pay">

                    <div class="latest-label">
                        LATEST NET PAY · {{ strtoupper($latestMonth->format('F Y')) }}
                    </div>

                    <div class="latest-amount">
                        ${{ number_format($latestPayslip->net_pay, 2) }}
                    </div>

                    <div class="latest-date">
                        Processed payroll for {{ $latestMonth->format('F Y') }}
                    </div>

                </section>

            </div>

            <section class="history-section payslip-history-column">

                <div class="section-heading">

                    <h2 class="section-title">
                        Monthly history
                    </h2>

                    <span class="section-year">
                        {{ $latestMonth->format('Y') }}
                    </span>

                </div>

                <article class="payslip-card">

                    <div class="payslip-header">

                        <h3 class="payslip-month">
                            {{ $latestMonth->format('F Y') }}
                        </h3>

                        <span class="paid-badge">
                            Processed
                        </span>

                    </div>

                    <div class="employee-name">
                        {{ $employee->user->first_name }}
                        {{ $employee->user->last_name }}
                        · Employee ID: {{ $employee->employee_id }}
                    </div>

                    <div class="period-label">
                        Pay period
                    </div>

                    <div class="period-date">
                        {{ $latestMonth->copy()->startOfMonth()->format('M j') }}
                        –
                        {{ $latestMonth->copy()->endOfMonth()->format('M j, Y') }}
                    </div>

                    <div class="payslip-divider"></div>

                    <div class="earning-row">
                        <span>Regular hours</span>
                        <span class="earning-amount">
                            {{ number_format($latestPayslip->regular_hours ?? 0, 2) }} h
                        </span>
                    </div>

                    <div class="earning-row">
                        <span>Overtime hours</span>
                        <span class="earning-amount">
                            {{ number_format($latestPayslip->overtime_hours ?? 0, 2) }} h
                        </span>
                    </div>

                    <div class="earning-row">
                        <span>Gross earnings</span>
                        <span class="earning-amount">
                            ${{ number_format($latestPayslip->gross_pay, 2) }}
                        </span>
                    </div>

                    <div class="earning-row">
                        <span>Deductions</span>
                        <span class="earning-amount">
                            -${{ number_format($latestPayslip->deductions, 2) }}
                        </span>
                    </div>

                    <div class="payslip-divider"></div>

                    <div class="net-pay-row">
                        <span>Net pay</span>
                        <span class="net-pay-amount">
                            ${{ number_format($latestPayslip->net_pay, 2) }}
                        </span>
                    </div>

                    <a
                        href="{{ route('employee.payslips.download', $latestPayslip->payroll_id) }}"
                        class="download-button"
                    >
                        Download PDF
                    </a>

                </article>

                @foreach($previousPayslips as $payslip)

                    @php
                        $month = \Carbon\Carbon::parse($payslip->payroll_month);
                        $detailsId = 'payslip-details-' . $payslip->payroll_id;
                    @endphp

                    <article class="previous-payslip">

                        <button
                            type="button"
                            class="previous-toggle"
                            aria-expanded="false"
                            aria-controls="{{ $detailsId }}"
                        >

                            <div class="previous-info">

                                <div class="previous-month">
                                    {{ $month->format('F Y') }}
                                </div>

                                <div class="previous-period">
                                    {{ $month->copy()->startOfMonth()->format('M j') }}
                                    –
                                    {{ $month->copy()->endOfMonth()->format('M j, Y') }}
                                </div>

                            </div>

                            <div class="previous-payment">

                                <div class="previous-amount">
                                    ${{ number_format($payslip->net_pay, 2) }}
                                    <span class="previous-arrow">›</span>
                                </div>

                                <div class="previous-status">
                                    Processed · View details
                                </div>

                            </div>

                        </button>

                        <div class="previous-details" id="{{ $detailsId }}">

                            <div class="payslip-divider"></div>

                            <div class="earning-row">
                                <span>Regular hours</span>
                                <span class="earning-amount">
                                    {{ number_format($payslip->regular_hours ?? 0, 2) }} h
                                </span>
                            </div>

                            <div class="earning-row">
                                <span>Overtime hours</span>
                                <span class="earning-amount">
                                    {{ number_format($payslip->overtime_hours ?? 0, 2) }} h
                                </span>
                            </div>

                            <div class="earning-row">
                                <span>Gross earnings</span>
                                <span class="earning-amount">
                                    ${{ number_format($payslip->gross_pay, 2) }}
                                </span>
                            </div>

                            <div class="earning-row">
                                <span>Deductions</span>
                                <span class="earning-amount">
                                    -${{ number_format($payslip->deductions, 2) }}
                                </span>
                            </div>

                            <div class="payslip-divider"></div>

                            <div class="net-pay-row">
                                <span>Net pay</span>
                                <span class="net-pay-amount">
                                    ${{ number_format($payslip->net_pay, 2) }}
                                </span>
                            </div>

                            <a
                                href="{{ route('employee.payslips.download', $payslip->payroll_id) }}"
                                class="previous-download"
                                style="margin-top: 16px;"
                            >
                                Download PDF
                            </a>

                        </div>

                    </article>

                @endforeach

            </section>

        </div>

    @else

        <section class="empty-state">
            You don't have any processed payslips yet.
        </section>

    @endif

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.previous-toggle').forEach(button => {
            button.addEventListener('click', function () {

                const detailsId = this.getAttribute('aria-controls');
                const details = document.getElementById(detailsId);

                if (!details) {
                    return;
                }

                const isOpen = details.classList.toggle('open');

                this.setAttribute('aria-expanded', isOpen);
            });
        });
    });
</script>

@endsection
@extends('layouts.app')

@section('title', 'Attendance')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'attendance'])

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #F6F8F7;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .attendance-page {
        width: 100%;
        padding: clamp(22px, 3vw, 44px);
    }

    /* Header */

    .attendance-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .page-title {
        font-size: clamp(28px, 2.2vw, 36px);
        font-weight: 700;
        letter-spacing: -1px;
        margin: 0;
    }

    .page-subtitle {
        color: #71817C;
        font-size: 14px;
        margin: 8px 0 0;
    }

    /* Filters */

    .filters {
        display: grid;
        grid-template-columns: minmax(180px, 220px) minmax(200px, 250px);
        gap: 12px;
    }

    .filter-select {
        width: 100%;
        height: 46px;
        padding: 0 14px;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 11px;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        outline: none;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: #176454;
        box-shadow: 0 0 0 3px rgba(23, 100, 84, .08);
    }

    /* Summary */

    .attendance-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .summary-card {
        min-height: 132px;
        padding: 22px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(23, 46, 41, .035);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .summary-card.present {
        background: #176454;
        border-color: #176454;
    }

    .summary-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .summary-label {
        color: #71817C;
        font-size: 12px;
        font-weight: 600;
    }

    .summary-card.present .summary-label {
        color: #D4E7DF;
    }

    .summary-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #E7F0EB;
        color: #176454;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
    }

    .summary-card.present .summary-icon {
        background: rgba(255, 255, 255, .15);
        color: #FFFFFF;
    }

    .summary-number {
        color: #172E29;
        font-size: clamp(30px, 2.5vw, 38px);
        font-weight: 700;
        line-height: 1;
        letter-spacing: -1px;
    }

    .summary-card.present .summary-number {
        color: #FFFFFF;
    }

    .summary-description {
        color: #8A9993;
        font-size: 11px;
        margin-top: 7px;
    }

    .summary-card.present .summary-description {
        color: #D4E7DF;
    }

    /* Currently clocked in */

    .clocked-in-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        background: #E7F0EB;
        border: 1px solid #D5E5DC;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 28px;
    }

    .banner-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .live-indicator {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: #26966D;
        box-shadow: 0 0 0 5px rgba(38, 150, 109, .12);
        flex-shrink: 0;
    }

    .banner-title {
        color: #176454;
        font-size: 14px;
        font-weight: 700;
    }

    .banner-subtitle {
        color: #71817C;
        font-size: 11px;
        margin-top: 4px;
    }

    .banner-names {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .clocked-person {
        padding: 8px 12px;
        background: rgba(255, 255, 255, .75);
        border: 1px solid #D5E5DC;
        border-radius: 9px;
        color: #176454;
        font-size: 11px;
        font-weight: 600;
    }

    /* Employee section */

    .employee-section {
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(23, 46, 41, .035);
    }

    .employee-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .employee-heading h2 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .employee-count {
        color: #176454;
        background: #E7F0EB;
        border-radius: 20px;
        padding: 7px 11px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .employee-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .employee-card {
        min-width: 0;
        min-height: 112px;
        padding: 17px;
        background: #FAFBFA;
        border: 1px solid #E8EEEA;
        border-radius: 13px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-rows: auto auto;
        align-items: center;
        column-gap: 12px;
        row-gap: 12px;
        transition: border-color .2s, transform .2s;
    }

    .employee-card:hover {
        border-color: #B8D3C6;
        transform: translateY(-2px);
    }

    .employee-identity {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #E7F0EB;
        border-radius: 12px;
        color: #176454;
        font-size: 12px;
        font-weight: 700;
    }

    .employee-name {
        color: #172E29;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 4px;
        overflow-wrap: anywhere;
    }

    .employee-department {
        color: #8A9993;
        font-size: 11px;
    }

    .employee-status {
        justify-self: end;
        align-self: start;
        padding: 6px 10px;
        border-radius: 20px;
        background: #E7F0EB;
        color: #176454;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .employee-status.absent {
        background: #FBEBDC;
        color: #A45C2C;
    }

    .employee-status.clocked-in {
        background: #E5F1FA;
        color: #326D99;
    }

    .employee-time {
        grid-column: 1;
        color: #71817C;
        font-size: 11px;
    }

    .employee-hours {
        grid-column: 2;
        grid-row: 2;
        justify-self: end;
        color: #172E29;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* Responsive */

    @media (max-width: 1100px) {
        .attendance-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .filters {
            width: 100%;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .employee-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .attendance-page {
            padding: 22px 16px 30px;
        }

        .attendance-summary {
            gap: 10px;
        }

        .summary-card {
            min-height: 110px;
            padding: 15px;
        }

        .summary-number {
            font-size: 29px;
        }

        .clocked-in-banner {
            align-items: flex-start;
            flex-direction: column;
            padding: 18px;
            gap: 16px;
        }

        .banner-names {
            justify-content: flex-start;
        }

        .employee-section {
            padding: 17px;
        }

        .employee-list {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    @media (max-width: 420px) {
        .filters {
            grid-template-columns: 1fr;
        }

        .attendance-summary {
            gap: 8px;
        }

        .summary-card {
            min-height: 100px;
            padding: 12px;
        }

        .summary-label {
            font-size: 10px;
        }

        .summary-number {
            font-size: 25px;
        }

        .summary-icon {
            width: 27px;
            height: 27px;
            font-size: 11px;
        }

        .employee-section {
            padding: 14px;
        }

        .employee-card {
            padding: 13px;
        }
    }
</style>

<main class="attendance-page">

    <!-- Page Heading -->
    <div class="attendance-header">

        <div>
            <div class="eyebrow">TEAM ATTENDANCE</div>

            <h1 class="page-title">Attendance</h1>

            <p class="page-subtitle">
                Who’s here, and how the day is going.
            </p>
        </div>

        <!-- Filters -->
        <div class="filters">

            <select class="filter-select">
                <option>November 10, 2026</option>
            </select>

            <select class="filter-select">
                <option>All departments</option>
                <option>Design</option>
                <option>Engineering</option>
                <option>Marketing</option>
                <option>People</option>
            </select>

        </div>

    </div>

    <!-- Attendance Summary -->
    <section class="attendance-summary">

        <div class="summary-card present">

            <div class="summary-top">
                <span class="summary-label">Present</span>
                <span class="summary-icon">P</span>
            </div>

            <div>
                <div class="summary-number">4</div>
                <div class="summary-description">Employees present today</div>
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-top">
                <span class="summary-label">Absent</span>
                <span class="summary-icon">A</span>
            </div>

            <div>
                <div class="summary-number">1</div>
                <div class="summary-description">Employees absent today</div>
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-top">
                <span class="summary-label">Clocked in</span>
                <span class="summary-icon">C</span>
            </div>

            <div>
                <div class="summary-number">2</div>
                <div class="summary-description">Currently working</div>
            </div>

        </div>

    </section>

    <!-- Currently Clocked In -->
    <section class="clocked-in-banner">

        <div class="banner-heading">

            <span class="live-indicator"></span>

            <div>
                <div class="banner-title">
                    Currently clocked in · 2 employees
                </div>

                <div class="banner-subtitle">
                    Employees who have started their shift
                </div>
            </div>

        </div>

        <div class="banner-names">

            <span class="clocked-person">Noah Williams</span>

            <span class="clocked-person">Alex Morgan</span>

        </div>

    </section>

    <!-- Employee Attendance -->
    <section class="employee-section">

        <div class="employee-heading">

            <h2>Employee attendance</h2>

            <span class="employee-count">5 employees</span>

        </div>

        <div class="employee-list">

            <article class="employee-card">

                <div class="employee-identity">

                    <div class="employee-avatar">MT</div>

                    <div>
                        <div class="employee-name">Maya Thompson</div>
                        <div class="employee-department">Design · EMP-024</div>
                    </div>

                </div>

                <span class="employee-status">Present</span>

                <div class="employee-time">
                    In 09:00 · Out 17:00
                </div>

                <div class="employee-hours">8h 00m</div>

            </article>

            <article class="employee-card">

                <div class="employee-identity">

                    <div class="employee-avatar">LC</div>

                    <div>
                        <div class="employee-name">Leah Chen</div>
                        <div class="employee-department">Engineering · EMP-018</div>
                    </div>

                </div>

                <span class="employee-status">Present</span>

                <div class="employee-time">
                    In 08:45 · Out 17:15
                </div>

                <div class="employee-hours">8h 30m</div>

            </article>

            <article class="employee-card">

                <div class="employee-identity">

                    <div class="employee-avatar">NW</div>

                    <div>
                        <div class="employee-name">Noah Williams</div>
                        <div class="employee-department">Marketing · EMP-011</div>
                    </div>

                </div>

                <span class="employee-status clocked-in">Clocked in</span>

                <div class="employee-time">
                    In 09:10 · Out —
                </div>

                <div class="employee-hours">8h 20m</div>

            </article>

            <article class="employee-card">

                <div class="employee-identity">

                    <div class="employee-avatar">SP</div>

                    <div>
                        <div class="employee-name">Sofia Patel</div>
                        <div class="employee-department">People · EMP-030</div>
                    </div>

                </div>

                <span class="employee-status absent">Absent</span>

                <div class="employee-time">
                    In — · Out —
                </div>

                <div class="employee-hours">0h 00m</div>

            </article>

            <article class="employee-card">

                <div class="employee-identity">

                    <div class="employee-avatar">AM</div>

                    <div>
                        <div class="employee-name">Alex Morgan</div>
                        <div class="employee-department">Design · EMP-006</div>
                    </div>

                </div>

                <span class="employee-status clocked-in">Clocked in</span>

                <div class="employee-time">
                    In 09:00 · Out —
                </div>

                <div class="employee-hours">8h 30m</div>

            </article>

        </div>

    </section>

</main>

@endsection
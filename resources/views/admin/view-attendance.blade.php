
@extends('layouts.app')

@section('title', 'Attendance')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'attendance'])

<style>
    * {
        box-sizing: border-box;
    }

    .attendance-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 24px;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-bottom: 8px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .page-subtitle {
        color: #687B74;
        font-size: 13px;
        margin: 0 0 20px;
    }

    .filters {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .filter-select {
        width: 100%;
        height: 49px;
        padding: 0 14px;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        color: #172E29;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        outline: none;
    }

    .attendance-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .summary-card {
        min-height: 84px;
        padding: 12px 14px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .summary-card.present {
        background: #176454;
        color: #FFFFFF;
    }

    .summary-label {
        display: block;
        color: #687B74;
        font-size: 11px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .summary-card.present .summary-label {
        color: #D4E7DF;
    }

    .summary-number {
        font-size: 29px;
        font-weight: 600;
        line-height: 1.45;
    }

    .clocked-in-banner {
        background: #E7F0EB;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
    }

    .banner-title {
        color: #176454;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .banner-names {
        color: #176454;
        font-size: 12px;
        font-weight: 400;
    }

    .employee-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .employee-heading h2 {
        font-size: 17px;
        font-weight: 600;
        margin: 0;
    }

    .employee-count {
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    .employee-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .employee-card {
        min-height: 74px;
        padding: 9px 14px;
        background: #FFFFFF;
        border-radius: 12px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-rows: auto auto;
        align-items: center;
        column-gap: 12px;
    }

    .employee-name {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .employee-department {
        color: #687B74;
        font-size: 10px;
        font-weight: 400;
    }

    .employee-status {
        justify-self: end;
        align-self: start;
        padding: 4px 9px;
        border-radius: 11px;
        background: #E7F0EB;
        color: #176454;
        font-size: 10px;
        font-weight: 500;
        white-space: nowrap;
    }

    .employee-status.absent {
        background: #FBEBDC;
        color: #8E4F22;
    }

    .employee-time {
        grid-column: 1;
        color: #687B74;
        font-size: 11px;
        margin-top: 7px;
    }

    .employee-hours {
        grid-column: 2;
        grid-row: 2;
        justify-self: end;
        color: #172E29;
        font-size: 11px;
        font-weight: 600;
        margin-top: 7px;
    }

    @media (max-width: 600px) {
        .attendance-page {
            padding: 18px 24px;
        }

        .filters {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .attendance-summary {
            gap: 12px;
        }

        .summary-card {
            padding: 12px 14px;
        }
    }

    @media (max-width: 360px) {
        .attendance-page {
            padding: 18px 16px;
        }

        .attendance-summary {
            gap: 8px;
        }

        .summary-card {
            padding: 10px;
        }

        .employee-card {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
</style>

<main class="attendance-page">

    <div class="eyebrow">TEAM ATTENDANCE</div>

    <h1 class="page-title">Attendance</h1>

    <p class="page-subtitle">
        Who’s here, and how the day is going.
    </p>

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

    <section class="attendance-summary">

        <div class="summary-card present">
            <span class="summary-label">Present</span>
            <div class="summary-number">4</div>
        </div>

        <div class="summary-card">
            <span class="summary-label">Absent</span>
            <div class="summary-number">1</div>
        </div>

        <div class="summary-card">
            <span class="summary-label">Clocked in</span>
            <div class="summary-number">2</div>
        </div>

    </section>

    <section class="clocked-in-banner">

        <div class="banner-title">
            Currently clocked in · 2
        </div>

        <div class="banner-names">
            Noah Williams · Alex Morgan
        </div>

    </section>

    <div class="employee-heading">
        <h2>Employee attendance</h2>
        <span class="employee-count">5 employees</span>
    </div>

    <section class="employee-list">

        <article class="employee-card">
            <div>
                <div class="employee-name">Maya Thompson</div>
                <div class="employee-department">Design · EMP-024</div>
            </div>

            <span class="employee-status">Present</span>

            <div class="employee-time">
                In 09:00 · Out 17:00
            </div>

            <div class="employee-hours">8h 00m</div>
        </article>

        <article class="employee-card">
            <div>
                <div class="employee-name">Leah Chen</div>
                <div class="employee-department">Engineering · EMP-018</div>
            </div>

            <span class="employee-status">Present</span>

            <div class="employee-time">
                In 08:45 · Out 17:15
            </div>

            <div class="employee-hours">8h 30m</div>
        </article>

        <article class="employee-card">
            <div>
                <div class="employee-name">Noah Williams</div>
                <div class="employee-department">Marketing · EMP-011</div>
            </div>

            <span class="employee-status">Clocked in</span>

            <div class="employee-time">
                In 09:10 · Out —
            </div>

            <div class="employee-hours">8h 20m</div>
        </article>

        <article class="employee-card">
            <div>
                <div class="employee-name">Sofia Patel</div>
                <div class="employee-department">People · EMP-030</div>
            </div>

            <span class="employee-status absent">Absent</span>

            <div class="employee-time">
                In — · Out —
            </div>

            <div class="employee-hours">0h 00m</div>
        </article>

        <article class="employee-card">
            <div>
                <div class="employee-name">Alex Morgan</div>
                <div class="employee-department">Design · EMP-006</div>
            </div>

            <span class="employee-status">Clocked in</span>

            <div class="employee-time">
                In 09:00 · Out —
            </div>

            <div class="employee-hours">8h 30m</div>
        </article>

    </section>

</main>

@endsection

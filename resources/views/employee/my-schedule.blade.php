@extends('layouts.app')

@section('title', 'My Schedule | Tempo HR')

@section('content')

@include('partials.navbar', ['activePage' => 'time'])

<style>
    .schedule-page {
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

    .schedule-content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(300px, 0.85fr);
        gap: clamp(20px, 2.2vw, 32px);
        align-items: start;
    }

    .schedule-main-column,
    .schedule-side-column {
        min-width: 0;
    }

    .calendar-card,
    .stat-card,
    .upcoming-card {
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        box-shadow: 0 4px 16px rgba(23, 46, 41, 0.035);
    }

    /* Calendar */

    .calendar-card {
        padding: clamp(17px, 2vw, 26px);
        border-radius: 20px;
    }

    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .calendar-month {
        color: #172E29;
        font-size: clamp(16px, 1.3vw, 20px);
        font-weight: 700;
        text-align: center;
    }

    .month-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background: #F1F6F3;
        border: 1px solid #E3EAE7;
        border-radius: 11px;
        color: #176454;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, transform 0.2s;
    }

    .month-button:hover {
        background: #176454;
        color: #FFFFFF;
        transform: translateY(-1px);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: clamp(5px, 0.7vw, 9px);
    }

    .weekday {
        color: #82918B;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        padding-bottom: 8px;
    }

    .calendar-day {
        min-width: 0;
        min-height: clamp(42px, 4.2vw, 62px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        background: #F6F8F7;
        color: #687B74;
        font-family: inherit;
        border: 1px solid transparent;
        border-radius: 11px;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s, transform 0.2s;
    }

    .calendar-day:not(.empty):hover {
        border-color: #176454;
        transform: translateY(-2px);
    }

    .calendar-day .day-number {
        font-size: clamp(12px, 1vw, 15px);
        font-weight: 600;
        line-height: 1.2;
    }

    .calendar-day .day-status {
        font-size: 9px;
        font-weight: 600;
        line-height: 1.2;
    }

    .calendar-day.work {
        background: #E7F0EB;
        color: #176454;
    }

    .calendar-day.work .day-number {
        color: #172E29;
    }

    .calendar-day.off {
        background: #F6F8F7;
        color: #82918B;
    }

    .calendar-day.leave {
        background: #FFFFFF;
        border: 1px solid #176454;
        color: #176454;
    }

    .calendar-day.selected {
        background: #176454;
        border-color: #176454;
        color: #FFFFFF;
        box-shadow: 0 4px 10px rgba(23, 100, 84, 0.2);
    }

    .calendar-day.selected .day-number,
    .calendar-day.selected .day-status {
        color: #FFFFFF;
    }

    .calendar-day.empty {
        background: transparent;
        border: none;
        cursor: default;
    }

    .calendar-legend {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin: 22px 2px 0;
        padding-top: 17px;
        border-top: 1px solid #EDF1EF;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #71817C;
        font-size: 11px;
        font-weight: 500;
    }

    .legend-color {
        width: 10px;
        height: 10px;
        flex-shrink: 0;
        border-radius: 3px;
    }

    .legend-work {
        background: #BFD8CB;
    }

    .legend-off {
        background: #E9EEEB;
    }

    .legend-leave {
        background: #FFFFFF;
        border: 1px solid #176454;
    }

    /* Monthly statistics */

    .schedule-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 13px;
        margin-top: 20px;
    }

    .stat-card {
        min-width: 0;
        padding: clamp(14px, 1.5vw, 20px);
        border-radius: 16px;
    }

    .stat-label {
        color: #71817C;
        font-size: clamp(10px, 0.85vw, 12px);
        font-weight: 500;
        line-height: 1.4;
    }

    .stat-number {
        color: #172E29;
        font-size: clamp(25px, 2.2vw, 34px);
        font-weight: 700;
        letter-spacing: -1px;
        margin-top: 5px;
    }

    /* Next shift */

    .next-shift {
        position: relative;
        overflow: hidden;
        padding: clamp(22px, 2.2vw, 30px);
        background: linear-gradient(135deg, #176454 0%, #124F43 100%);
        border-radius: 20px;
        color: #FFFFFF;
        box-shadow: 0 8px 22px rgba(23, 100, 84, 0.13);
    }

    .next-shift::after {
        content: '';
        position: absolute;
        width: 190px;
        height: 190px;
        right: -75px;
        top: -95px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 50%;
        box-shadow: 0 0 0 25px rgba(255, 255, 255, 0.035),
                    0 0 0 50px rgba(255, 255, 255, 0.025);
        pointer-events: none;
    }

    .next-shift-label {
        position: relative;
        z-index: 1;
        color: #C4DAD1;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 13px;
    }

    .next-shift-time {
        position: relative;
        z-index: 1;
        font-size: clamp(27px, 2.5vw, 37px);
        font-weight: 700;
        letter-spacing: -1px;
        margin-bottom: 7px;
    }

    .next-shift-details {
        position: relative;
        z-index: 1;
        color: #D4E7DF;
        font-size: 12px;
    }

    /* Upcoming shifts */

    .upcoming-section {
        margin-top: 27px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 15px;
    }

    .section-title {
        color: #172E29;
        font-size: clamp(16px, 1.3vw, 20px);
        font-weight: 700;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .section-month {
        padding: 6px 11px;
        background: #E7F0EB;
        border-radius: 20px;
        color: #176454;
        font-size: 10px;
        font-weight: 700;
    }

    .upcoming-list {
        display: flex;
        flex-direction: column;
        gap: 11px;
    }

    .upcoming-card {
        display: flex;
        align-items: center;
        gap: 15px;
        min-height: 86px;
        padding: 14px 16px;
        border-radius: 16px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .upcoming-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(23, 46, 41, 0.07);
    }

    .shift-date {
        width: 61px;
        height: 57px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #E7F0EB;
        border-radius: 12px;
        color: #176454;
    }

    .shift-date-label {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.4px;
    }

    .shift-date-number {
        font-size: 23px;
        font-weight: 700;
        line-height: 1.2;
    }

    .shift-info {
        min-width: 0;
    }

    .shift-time {
        color: #172E29;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .shift-details {
        color: #71817C;
        font-size: 11px;
        line-height: 1.5;
    }

    /* Approved leave */

    .approved-leave {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 15px;
        padding: 15px 17px;
        background: #E7F0EB;
        border: 1px solid #D5E7DC;
        border-radius: 13px;
        color: #176454;
        font-size: 12px;
        font-weight: 600;
    }

    .approved-leave::before {
        content: '✓';
        display: flex;
        align-items: center;
        justify-content: center;
        width: 23px;
        height: 23px;
        flex-shrink: 0;
        background: #176454;
        border-radius: 50%;
        color: #FFFFFF;
        font-size: 12px;
    }

    /* Responsive layout */

    @media (max-width: 1099px) {
        .schedule-content-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .schedule-side-column {
            display: grid;
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
            gap: 20px;
            align-items: start;
        }

        .upcoming-section {
            margin-top: 0;
        }

        .next-shift {
            min-height: 100%;
        }
    }

    @media (max-width: 767px) {
        .schedule-page {
            padding: 23px 18px 35px;
        }

        .page-header {
            margin-bottom: 22px;
        }

        .schedule-content-grid {
            gap: 20px;
        }

        .calendar-card {
            padding: 15px;
            border-radius: 17px;
        }

        .calendar-header {
            margin-bottom: 17px;
        }

        .calendar-grid {
            gap: 5px;
        }

        .calendar-day {
            min-height: 43px;
            border-radius: 8px;
        }

        .calendar-day .day-status {
            font-size: 8px;
        }

        .weekday {
            font-size: 10px;
        }

        .calendar-legend {
            gap: 12px;
            margin-top: 17px;
            padding-top: 14px;
        }

        .legend-item {
            font-size: 10px;
        }

        .schedule-stats {
            gap: 9px;
            margin-top: 14px;
        }

        .stat-card {
            padding: 13px 10px;
            border-radius: 13px;
        }

        .schedule-side-column {
            grid-template-columns: minmax(0, 1fr);
            gap: 20px;
        }

        .next-shift {
            min-height: auto;
            padding: 22px;
        }

        .upcoming-section {
            margin-top: 0;
        }
    }

    @media (max-width: 400px) {
        .schedule-page {
            padding-left: 14px;
            padding-right: 14px;
        }

        .calendar-card {
            padding: 12px;
        }

        .calendar-grid {
            gap: 4px;
        }

        .calendar-day {
            min-height: 39px;
        }

        .calendar-day .day-number {
            font-size: 11px;
        }

        .calendar-day .day-status {
            font-size: 7px;
        }

        .schedule-stats {
            gap: 7px;
        }

        .stat-card {
            padding: 11px 8px;
        }

        .stat-label {
            font-size: 9px;
        }

        .stat-number {
            font-size: 24px;
        }

        .calendar-legend {
            gap: 9px;
        }

        .legend-item {
            font-size: 9px;
        }
    }

    .back-button {
    display: inline-block;
    margin-bottom: 20px;
    padding: 9px 16px;
    border: 1px solid #dce5e1;
    border-radius: 8px;
    color: #176b5b;
    background: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}

.back-button:hover {
    background: #eaf3ef;
    color: #14594d;
}
</style>

    @media (min-width: 1500px) {
        .schedule-content-grid {
            grid-template-columns: minmax(0, 1.2fr) minmax(340px, 0.8fr);
            gap: 36px;
        }

        .calendar-card {
            padding: 28px;
        }

        .calendar-day {
            min-height: 65px;
        }
<a href="{{ route('employee.time-entry') }}" class="back-button">
    ← Back to Time Entry
</a>

    <div class="eyebrow">
        MY TIME · PUBLISHED
    </div>

        .next-shift {
            padding: 32px;
        }

        .upcoming-card {
            min-height: 94px;
            padding: 17px 19px;
        }
    }
</style>

<main class="schedule-page">

    <header class="page-header">
        <div class="eyebrow">
            MY TIME · PUBLISHED
        </div>

        <h1 class="page-title">
            My schedule
        </h1>

        <p class="page-subtitle">
            Maya Thompson · EMP-024
        </p>
    </header>

    <div class="schedule-content-grid">

        <div class="schedule-main-column">

            <section class="calendar-card">

                <div class="calendar-header">

                    <button type="button" class="month-button" id="previous-month" aria-label="Previous month">
                        ‹
                    </button>

                    <div class="calendar-month" id="calendar-month">
                        November 2026
                    </div>

                    <button type="button" class="month-button" id="next-month" aria-label="Next month">
                        ›
                    </button>

                </div>

                <div class="calendar-grid" id="calendar-grid">

                    <div class="weekday">S</div>
                    <div class="weekday">M</div>
                    <div class="weekday">T</div>
                    <div class="weekday">W</div>
                    <div class="weekday">T</div>
                    <div class="weekday">F</div>
                    <div class="weekday">S</div>

                </div>

                <div class="calendar-legend">

                    <div class="legend-item">
                        <span class="legend-color legend-work"></span>
                        Scheduled
                    </div>

                    <div class="legend-item">
                        <span class="legend-color legend-off"></span>
                        Day off
                    </div>

                    <div class="legend-item">
                        <span class="legend-color legend-leave"></span>
                        Approved leave
                    </div>

                </div>

            </section>

            <section class="schedule-stats">

                <div class="stat-card">
                    <div class="stat-label">Scheduled shifts</div>
                    <div class="stat-number">20</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Days off</div>
                    <div class="stat-number">9</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Approved leave</div>
                    <div class="stat-number">1</div>
                </div>

            </section>

        </div>

        <div class="schedule-side-column">

            <section class="next-shift">

                <div class="next-shift-label">
                    NEXT SHIFT · THU, NOV 12
                </div>

                <div class="next-shift-time">
                    09:00 – 17:00
                </div>

                <div class="next-shift-details">
                    8 hours · Office · Day shift
                </div>

            </section>

            <section class="upcoming-section">

                <div class="section-heading">

                    <h2 class="section-title">
                        Upcoming shifts
                    </h2>

                    <span class="section-month">
                        November
                    </span>

                </div>

                <div class="upcoming-list">

                    <article class="upcoming-card">

                        <div class="shift-date">
                            <span class="shift-date-label">FRI, NOV</span>
                            <span class="shift-date-number">13</span>
                        </div>

                        <div class="shift-info">
                            <div class="shift-time">09:00 – 17:00</div>
                            <div class="shift-details">8 hours · Office · Day shift</div>
                        </div>

                    </article>

                    <article class="upcoming-card">

                        <div class="shift-date">
                            <span class="shift-date-label">MON, NOV</span>
                            <span class="shift-date-number">16</span>
                        </div>

                        <div class="shift-info">
                            <div class="shift-time">10:00 – 18:00</div>
                            <div class="shift-details">8 hours · Office · Mid shift</div>
                        </div>

                    </article>

                </div>

                <div class="approved-leave">
                    Approved leave · Fri, Nov 20
                </div>

            </section>

        </div>

    </div>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarGrid = document.getElementById('calendar-grid');
        const calendarMonth = document.getElementById('calendar-month');
        const previousMonthButton = document.getElementById('previous-month');
        const nextMonthButton = document.getElementById('next-month');

        if (!calendarGrid || !calendarMonth || !previousMonthButton || !nextMonthButton) {
            return;
        }

        let currentMonth = 10;
        let currentYear = 2026;

        const scheduleData = {
            2026: {
                10: {
                    1: 'off',
                    2: 'work',
                    3: 'work',
                    4: 'work',
                    5: 'work',
                    6: 'work',
                    7: 'off',
                    8: 'off',
                    9: 'work',
                    10: 'work',
                    11: 'work',
                    12: 'work',
                    13: 'work',
                    14: 'off',
                    15: 'off',
                    16: 'work',
                    17: 'work',
                    18: 'work',
                    19: 'work',
                    20: 'leave',
                    21: 'off',
                    22: 'off',
                    23: 'work',
                    24: 'work',
                    25: 'work',
                    26: 'work',
                    27: 'work',
                    28: 'off',
                    29: 'off',
                    30: 'work'
                }
            }
        };

        function renderCalendar() {
            const monthName = new Date(currentYear, currentMonth).toLocaleString('en-US', {
                month: 'long'
            });

            calendarMonth.textContent = `${monthName} ${currentYear}`;

            calendarGrid.innerHTML = `
                <div class="weekday">S</div>
                <div class="weekday">M</div>
                <div class="weekday">T</div>
                <div class="weekday">W</div>
                <div class="weekday">T</div>
                <div class="weekday">F</div>
                <div class="weekday">S</div>
            `;

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
                calendarGrid.appendChild(emptyDay);
            }

            const monthSchedule = scheduleData[currentYear]?.[currentMonth] || {};

            for (let day = 1; day <= daysInMonth; day++) {
                const status = monthSchedule[day] || 'off';

                const dayButton = document.createElement('button');
                dayButton.type = 'button';
                dayButton.className = `calendar-day ${status}`;

                if (currentYear === 2026 && currentMonth === 10 && day === 12) {
                    dayButton.classList.add('selected');
                }

                const statusLabel = status === 'work'
                    ? 'Work'
                    : status === 'leave'
                        ? 'Leave'
                        : 'Off';

                dayButton.innerHTML = `
                    <span class="day-number">${day}</span>
                    <span class="day-status">${statusLabel}</span>
                `;

                dayButton.setAttribute(
                    'aria-label',
                    `${monthName} ${day}, ${statusLabel}`
                );

                calendarGrid.appendChild(dayButton);
            }
        }

        previousMonthButton.addEventListener('click', function () {
            currentMonth--;

            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }

            renderCalendar();
        });

        nextMonthButton.addEventListener('click', function () {
            currentMonth++;

            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }

            renderCalendar();
        });

        renderCalendar();
    });
</script>

@endsection
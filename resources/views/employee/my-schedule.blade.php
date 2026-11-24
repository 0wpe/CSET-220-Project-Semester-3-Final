
@extends('layouts.app')

@section('title', 'My Schedule | Tempo HR')

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

    .schedule-page {
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

    /* Calendar */

    .calendar-card {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 12px;
    }

    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .calendar-month {
        color: #172E29;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
    }

    .month-button {
        width: 36px;
        height: 34px;
        background: #F6F8F7;
        border: none;
        border-radius: 9px;
        color: #176454;
        font-size: 22px;
        cursor: pointer;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 4px 6px;
    }

    .weekday {
        color: #687B74;
        font-size: 10px;
        font-weight: 600;
        text-align: center;
        padding-bottom: 4px;
    }

    .calendar-day {
        min-width: 0;
        height: 32px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0;
        border-radius: 8px;
        background: #F6F8F7;
        color: #687B74;
        font-family: inherit;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .calendar-day .day-number {
        font-size: 12px;
        font-weight: 500;
        line-height: 16px;
    }

    .calendar-day .day-status {
        font-size: 8px;
        font-weight: 500;
        line-height: 10px;
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
        color: #687B74;
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
        gap: 12px;
        margin: 14px 4px 0;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #687B74;
        font-size: 9px;
        font-weight: 500;
    }

    .legend-color {
        width: 9px;
        height: 9px;
        border-radius: 3px;
    }

    .legend-work {
        background: #E7F0EB;
    }

    .legend-off {
        background: #F6F8F7;
    }

    .legend-leave {
        background: #FFFFFF;
        border: 1px solid #176454;
    }

    /* Monthly statistics */

    .schedule-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 24px;
    }

    .stat-card {
        min-width: 0;
        background: #FFFFFF;
        border-radius: 16px;
        padding: 11px 12px;
    }

    .stat-label {
        color: #687B74;
        font-size: 11px;
        font-weight: 400;
        white-space: nowrap;
    }

    .stat-number {
        color: #172E29;
        font-size: 27px;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Next shift */

    .next-shift {
        margin-top: 16px;
        padding: 15px 18px;
        background: #176454;
        border-radius: 20px;
        color: #FFFFFF;
    }

    .next-shift-label {
        color: #C4DAD1;
        font-size: 10px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .next-shift-time {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .next-shift-details {
        color: #D4E7DF;
        font-size: 12px;
    }

    /* Upcoming shifts */

    .upcoming-section {
        margin-top: 26px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .section-title {
        color: #172E29;
        font-size: 17px;
        font-weight: 600;
        margin: 0;
    }

    .section-month {
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    .upcoming-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .upcoming-card {
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 72px;
        padding: 10px 14px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .shift-date {
        width: 60px;
        height: 52px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #E7F0EB;
        border-radius: 10px;
        color: #176454;
    }

    .shift-date-label {
        font-size: 8px;
        font-weight: 600;
    }

    .shift-date-number {
        font-size: 21px;
        font-weight: 600;
        line-height: 30px;
    }

    .shift-info {
        min-width: 0;
    }

    .shift-time {
        color: #172E29;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .shift-details {
        color: #687B74;
        font-size: 11px;
    }

    /* Approved leave */

    .approved-leave {
        margin-top: 16px;
        padding: 12px 14px;
        background: #E7F0EB;
        border-radius: 12px;
        color: #176454;
        font-size: 12px;
        font-weight: 500;
    }

    @media (min-width: 700px) {
        .calendar-card {
            max-width: 600px;
        }

        .schedule-stats {
            max-width: 600px;
        }

        .next-shift {
            max-width: 600px;
        }

        .upcoming-section {
            max-width: 600px;
        }
    }

    @media (max-width: 380px) {
        .schedule-page {
            padding-left: 16px;
            padding-right: 16px;
        }

        .schedule-stats {
            gap: 7px;
        }

        .stat-card {
            padding: 10px 8px;
        }

        .stat-label {
            font-size: 10px;
        }

        .calendar-grid {
            gap: 4px;
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

@include('partials.navbar', ['activePage' => 'time'])

<main class="schedule-page">

<a href="{{ route('employee.time-entry') }}" class="back-button">
    ← Back to Time Entry
</a>

    <div class="eyebrow">
        MY TIME · PUBLISHED
    </div>

    <h1 class="page-title">
        My schedule
    </h1>

    <p class="page-subtitle">
        Maya Thompson · EMP-024
    </p>

    <!-- Monthly calendar -->

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

    <!-- Monthly statistics -->

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

    <!-- Next shift -->

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

    <!-- Upcoming shifts -->

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

</main>

<script>
    const calendarGrid = document.getElementById('calendar-grid');
    const calendarMonth = document.getElementById('calendar-month');

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

    document.getElementById('previous-month').addEventListener('click', function () {
        currentMonth--;

        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }

        renderCalendar();
    });

    document.getElementById('next-month').addEventListener('click', function () {
        currentMonth++;

        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }

        renderCalendar();
    });

    renderCalendar();
</script>

@endsection

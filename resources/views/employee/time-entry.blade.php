@extends('layouts.app')

@section('title', 'Time Entry | Tempo HR')

@section('content')
    @include('partials.navbar', ['activePage' => 'time'])

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

        .time-page {
            width: 100%;
            padding: clamp(22px, 3vw, 44px) clamp(18px, 3vw, 55px);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 28px;
        }

        .eyebrow {
            color: #176454;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .7px;
            margin-bottom: 8px;
        }

        .page-title {
            font-size: clamp(27px, 2.5vw, 36px);
            font-weight: 650;
            margin: 0 0 5px;
            letter-spacing: -.7px;
        }

        .page-subtitle {
            color: #687B74;
            font-size: 14px;
            margin: 0;
        }

        .date-label {
            color: #687B74;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }

        .time-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(280px, .85fr);
            gap: 22px;
            align-items: stretch;
        }

        .timer-card {
            background: #FFFFFF;
            border: 1px solid #E3EAE7;
            border-radius: 22px;
            padding: clamp(24px, 3vw, 38px);
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 330px;
        }

        .timer-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .timer-heading {
            font-size: 15px;
            font-weight: 600;
            margin: 0;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #E7F0EB;
            color: #176454;
            border-radius: 20px;
            padding: 7px 12px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #21966F;
        }

        .timer-display {
            text-align: center;
            padding: 25px 0 20px;
        }

        .timer {
            font-size: clamp(48px, 7vw, 76px);
            line-height: 1.2;
            font-weight: 650;
            letter-spacing: 2px;
            font-variant-numeric: tabular-nums;
            color: #172E29;
        }

        .timer-description {
            color: #687B74;
            font-size: 13px;
            margin: 12px 0 0;
        }

        .timer-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            max-width: 520px;
            width: 100%;
            margin: 0 auto;
        }

        .timer-button {
            min-height: 49px;
            border-radius: 11px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #DFE7E2;
            background: #FFFFFF;
            color: #172E29;
            transition: background .2s, border-color .2s;
        }

        .timer-button.primary {
            background: #176454;
            color: #FFFFFF;
            border-color: #176454;
        }

        .timer-button.primary:hover:not(:disabled) {
            background: #124F42;
        }

        .timer-button:hover:not(:disabled):not(.primary) {
            background: #F2F6F3;
        }

        .timer-button:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .today-card {
            background: #176454;
            color: #FFFFFF;
            border-radius: 22px;
            padding: 27px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 330px;
        }

        .today-card-label {
            color: #C4DAD1;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .today-hours {
            font-size: clamp(36px, 4vw, 49px);
            font-weight: 650;
            margin: 12px 0 5px;
            font-variant-numeric: tabular-nums;
        }

        .today-description {
            color: #D4E7DF;
            font-size: 12px;
            margin: 0;
        }

        .today-divider {
            height: 1px;
            background: rgba(255, 255, 255, .2);
            margin: 25px 0;
        }

        .today-detail {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .today-detail:last-child {
            margin-bottom: 0;
        }

        .today-detail span:first-child {
            color: #C4DAD1;
        }

        .today-detail span:last-child {
            font-weight: 600;
            text-align: right;
        }

        .time-content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            gap: 22px;
            margin-top: 25px;
            align-items: start;
        }

        .content-section {
            min-width: 0;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 13px;
        }

        .section-heading h2 {
            font-size: 17px;
            font-weight: 650;
            margin: 0;
        }

        .section-caption {
            color: #687B74;
            font-size: 11px;
        }

        .view-link {
            color: #176454;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        .activity-card,
        .weekly-card {
            background: #FFFFFF;
            border: 1px solid #E3EAE7;
            border-radius: 17px;
            padding: 0 22px;
        }

        .activity-top {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 23px 0;
        }

        .activity-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 30px;
            background: #E7F0EB;
            color: #176454;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 700;
        }

        .activity-info {
            flex: 1;
            min-width: 0;
        }

        .activity-info strong {
            display: block;
            font-size: 14px;
            font-weight: 600;
        }

        .activity-info span {
            display: block;
            color: #687B74;
            font-size: 12px;
            margin-top: 5px;
        }

        .activity-time {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .activity-bottom {
            border-top: 1px solid #DFE7E2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 17px 0;
            font-size: 12px;
        }

        .muted {
            color: #687B74;
        }

        .week-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 65px;
            border-bottom: 1px solid #DFE7E2;
            gap: 12px;
        }

        .week-row:last-child {
            border-bottom: none;
        }

        .week-date {
            font-size: 13px;
            font-weight: 600;
        }

        .week-description {
            color: #687B74;
            font-size: 11px;
            margin-top: 5px;
        }

        .week-hours {
            color: #172E29;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .week-row.current .week-hours {
            color: #176454;
        }

        .week-row.current .week-date {
            color: #176454;
        }

        .timezone-note {
            color: #687B74;
            font-size: 11px;
            margin: 20px 0 0;
        }

        @media (max-width: 900px) {
            .time-main-grid {
                grid-template-columns: 1fr;
            }

            .timer-card {
                min-height: 310px;
            }

            .today-card {
                min-height: auto;
            }

            .today-card-bottom {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0 25px;
            }

            .today-divider {
                margin: 20px 0;
            }
        }

        @media (max-width: 650px) {
            .time-page {
                padding: 22px 18px 30px;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 22px;
            }

            .date-label {
                font-size: 12px;
            }

            .time-content-grid {
                grid-template-columns: 1fr;
                gap: 22px;
                margin-top: 22px;
            }

            .timer-card {
                min-height: 290px;
                padding: 22px 18px;
            }

            .timer {
                font-size: clamp(43px, 12vw, 60px);
            }

            .today-card {
                padding: 23px;
            }
        }

        @media (max-width: 400px) {
            .timer-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .timer-actions {
                gap: 9px;
            }

            .timer-button {
                font-size: 12px;
            }

            .activity-card,
            .weekly-card {
                padding: 0 14px;
            }

            .activity-top {
                gap: 10px;
            }

            .activity-time {
                font-size: 11px;
            }

            .activity-info strong {
                font-size: 13px;
            }

            .activity-info span {
                font-size: 11px;
            }

            .today-card-bottom {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <main class="time-page">

        <header class="page-header">
            <div>
                <div class="eyebrow">ATTENDANCE</div>

                <h1 class="page-title">Time entry</h1>

                <p class="page-subtitle">
                    Stay on top of your working hours.
                </p>
            </div>

            <div class="date-label">
                Monday, November 9
            </div>
        </header>

        <div class="time-main-grid">

            <section class="timer-card">

                <div class="timer-top">
                    <h2 class="timer-heading">Your work session</h2>

                    <span class="status" id="clockStatus">
                        <span class="status-dot"></span>
                        On the clock
                    </span>
                </div>

                <div class="timer-display">
                    <div class="timer" id="timer">00:41:00</div>

                    <p class="timer-description" id="timerDescription">
                        Clocked in today at 09:00 AM
                    </p>
                </div>

                <div class="timer-actions">
                    <button
                        type="button"
                        class="timer-button"
                        id="clockInButton"
                        disabled
                    >
                        Clock-In
                    </button>

                    <button
                        type="button"
                        class="timer-button primary"
                        id="clockOutButton"
                    >
                        Clock-Out
                    </button>
                </div>

            </section>

            <section class="today-card">

                <div>
                    <div class="today-card-label">Today's total</div>

                    <div class="today-hours" id="todayHours">00h 41m</div>

                    <p class="today-description">
                        Time recorded for your current shift
                    </p>
                </div>

                <div>
                    <div class="today-divider"></div>

                    <div class="today-card-bottom">
                        <div class="today-detail">
                            <span>Clock-in</span>
                            <span id="summaryClockIn">09:00 AM</span>
                        </div>

                        <div class="today-detail">
                            <span>Clock-out</span>
                            <span id="summaryClockOut">Not recorded</span>
                        </div>
                    </div>
                </div>

            </section>

        </div>

        <div class="time-content-grid">

            <section class="content-section">

                <div class="section-heading">
                    <h2>Today's activity</h2>
                    <span class="section-caption">Current shift</span>
                </div>

                <div class="activity-card">

                    <div class="activity-top">

                        <span class="activity-badge" id="activityBadge">
                            IN
                        </span>

                        <div class="activity-info">
                            <strong id="activityTitle">Clocked in</strong>
                            <span>Office · San Francisco</span>
                        </div>

                        <span class="activity-time" id="clockInTime">
                            09:00 AM
                        </span>

                    </div>

                    <div class="activity-bottom">
                        <span class="muted">Clock-out</span>

                        <span class="muted" id="clockOutTime">
                            Not yet recorded
                        </span>
                    </div>

                </div>

            </section>

            <section class="content-section">

                <div class="section-heading">
                    <h2>This week</h2>

                    <a href="/employee/my-schedule" class="view-link">
                        View schedule
                    </a>
                </div>

                <div class="weekly-card">

                    <div class="week-row current">
                        <div>
                            <div class="week-date">Mon, Nov 9</div>
                            <div class="week-description" id="weeklyStatus">
                                In progress
                            </div>
                        </div>

                        <span class="week-hours" id="weeklyHours">
                            00h 41m
                        </span>
                    </div>

                    <div class="week-row">
                        <div>
                            <div class="week-date">Fri, Nov 6</div>
                            <div class="week-description">09:00 – 17:00</div>
                        </div>

                        <span class="week-hours">08h 00m</span>
                    </div>

                    <div class="week-row">
                        <div>
                            <div class="week-date">Thu, Nov 5</div>
                            <div class="week-description">09:00 – 17:00</div>
                        </div>

                        <span class="week-hours">08h 00m</span>
                    </div>

                </div>

            </section>

        </div>

        <p class="timezone-note">
            Times are shown in Pacific Time (PT).
        </p>

    </main>

    <script>
        let elapsedSeconds = 41 * 60;
        let isClockedIn = true;
        let clockInTimestamp = Date.now() - elapsedSeconds * 1000;
        let timerInterval = null;

        const timer = document.getElementById('timer');
        const clockStatus = document.getElementById('clockStatus');
        const timerDescription = document.getElementById('timerDescription');
        const clockInButton = document.getElementById('clockInButton');
        const clockOutButton = document.getElementById('clockOutButton');
        const clockInTime = document.getElementById('clockInTime');
        const clockOutTime = document.getElementById('clockOutTime');
        const activityBadge = document.getElementById('activityBadge');
        const activityTitle = document.getElementById('activityTitle');
        const weeklyHours = document.getElementById('weeklyHours');
        const weeklyStatus = document.getElementById('weeklyStatus');
        const todayHours = document.getElementById('todayHours');
        const summaryClockIn = document.getElementById('summaryClockIn');
        const summaryClockOut = document.getElementById('summaryClockOut');

        function formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;

            return [
                String(hours).padStart(2, '0'),
                String(minutes).padStart(2, '0'),
                String(remainingSeconds).padStart(2, '0')
            ].join(':');
        }

        function formatHoursMinutes(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);

            return String(hours).padStart(2, '0') + 'h ' +
                String(minutes).padStart(2, '0') + 'm';
        }

        function formatClockTime(date) {
            return date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }

        function updateTimer() {
            if (isClockedIn) {
                elapsedSeconds = Math.floor(
                    (Date.now() - clockInTimestamp) / 1000
                );
            }

            timer.textContent = formatTime(elapsedSeconds);
            weeklyHours.textContent = formatHoursMinutes(elapsedSeconds);
            todayHours.textContent = formatHoursMinutes(elapsedSeconds);
        }

        function startTimer() {
            clearInterval(timerInterval);
            timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        }

        clockInButton.addEventListener('click', function () {
            if (isClockedIn) return;

            isClockedIn = true;
            elapsedSeconds = 0;
            clockInTimestamp = Date.now();

            const currentTime = formatClockTime(new Date());

            clockStatus.innerHTML =
                '<span class="status-dot"></span> On the clock';

            timerDescription.textContent =
                'Clocked in today at ' + currentTime;

            clockInTime.textContent = currentTime;
            summaryClockIn.textContent = currentTime;

            clockOutTime.textContent = 'Not yet recorded';
            summaryClockOut.textContent = 'Not recorded';

            activityBadge.textContent = 'IN';
            activityTitle.textContent = 'Clocked in';

            clockInButton.disabled = true;
            clockOutButton.disabled = false;
            weeklyStatus.textContent = 'In progress';

            startTimer();
        });

        clockOutButton.addEventListener('click', function () {
            if (!isClockedIn) return;

            elapsedSeconds = Math.floor(
                (Date.now() - clockInTimestamp) / 1000
            );

            isClockedIn = false;

            const currentTime = formatClockTime(new Date());

            clockStatus.innerHTML = 'Shift complete';

            timerDescription.textContent =
                'Clocked out at ' + currentTime;

            clockOutTime.textContent = currentTime;
            summaryClockOut.textContent = currentTime;

            activityBadge.textContent = 'OUT';
            activityTitle.textContent = 'Clocked out';

            clockInButton.disabled = false;
            clockOutButton.disabled = true;
            weeklyStatus.textContent = 'Completed';

            updateTimer();
            clearInterval(timerInterval);
        });

        startTimer();
    </script>
@endsection
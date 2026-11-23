
@extends('layouts.app')

@section('title', 'Time Entry')

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
            width: min(100% - 48px, 900px);
            margin: 18px auto 40px;
        }

        .eyebrow {
            color: #176454;
            font-size: 11px;
            font-weight: 600;
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
            margin: 0;
        }

        .timer-card {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 20px 18px 18px;
            margin-top: 30px;
            text-align: center;
        }

        .status {
            display: inline-block;
            background: #E7F0EB;
            color: #176454;
            border-radius: 13px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 500;
        }

        .timer {
            font-size: clamp(42px, 12vw, 51px);
            line-height: 1.45;
            font-weight: 600;
            margin: 22px 0 0;
            font-variant-numeric: tabular-nums;
        }

        .timer-description {
            color: #687B74;
            font-size: 13px;
            margin: 0;
        }

        .timer-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 30px;
        }

        .timer-button {
            min-height: 50px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #DFE7E2;
            background: #FFFFFF;
            color: #172E29;
        }

        .timer-button.primary {
            background: #176454;
            color: #FFFFFF;
            border-color: #176454;
        }

        .timer-button:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 27px 0 12px;
        }

        .section-heading h2 {
            font-size: 17px;
            font-weight: 600;
            margin: 0;
        }

        .view-link {
            color: #176454;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .activity-card,
        .weekly-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 0 18px;
        }

        .activity-top {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px 0;
        }

        .activity-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 39px;
            height: 26px;
            background: #E7F0EB;
            color: #176454;
            border-radius: 13px;
            font-size: 11px;
            font-weight: 500;
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
            margin-top: 4px;
        }

        .activity-time {
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }

        .activity-bottom {
            border-top: 1px solid #DFE7E2;
            display: flex;
            justify-content: space-between;
            padding: 17px 0;
            font-size: 13px;
        }

        .muted {
            color: #687B74;
            font-size: 12px;
        }

        .week-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 57px;
            border-bottom: 1px solid #DFE7E2;
            gap: 12px;
        }

        .week-row:last-child {
            border-bottom: none;
        }

        .week-date {
            font-size: 12px;
            font-weight: 500;
        }

        .week-description {
            color: #687B74;
            font-size: 10px;
            margin-top: 4px;
        }

        .week-hours {
            color: #172E29;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .week-row.current .week-hours {
            color: #176454;
        }

        .timezone-note {
            color: #687B74;
            font-size: 12px;
            margin: 22px 0 0;
        }

        @media (max-width: 420px) {
            .timer-actions {
                gap: 10px;
            }

            .activity-top {
                gap: 10px;
            }

            .activity-time {
                font-size: 11px;
            }
        }
    </style>

    <main class="time-page">
        <div class="eyebrow">MONDAY, NOVEMBER 9</div>

        <h1 class="page-title">Time entry</h1>
        <p class="page-subtitle">Stay on top of your working hours.</p>

        <section class="timer-card">
            <span class="status" id="clockStatus">On the clock</span>

            <div class="timer" id="timer">00:41:00</div>

            <p class="timer-description" id="timerDescription">
                Clocked in today at 09:00 AM
            </p>

            <div class="timer-actions">
                <button type="button" class="timer-button" id="clockInButton" disabled>
                    Clock-In
                </button>

                <button type="button" class="timer-button primary" id="clockOutButton">
                    Clock-Out
                </button>
            </div>
        </section>

        <div class="section-heading">
            <h2>Today's activity</h2>
        </div>

        <section class="activity-card">
            <div class="activity-top">
                <span class="activity-badge" id="activityBadge">IN</span>

                <div class="activity-info">
                    <strong id="activityTitle">Clocked in</strong>
                    <span>Office · San Francisco</span>
                </div>

                <span class="activity-time" id="clockInTime">09:00 AM</span>
            </div>

            <div class="activity-bottom">
                <span class="muted">Clock-out</span>
                <span class="muted" id="clockOutTime">Not yet recorded</span>
            </div>
        </section>

        <div class="section-heading">
            <h2>This week</h2>
            <a href="/employee/my-schedule" class="view-link">View all</a>
        </div>

        <section class="weekly-card">
            <div class="week-row current">
                <div>
                    <div class="week-date">Mon, Nov 9</div>
                    <div class="week-description" id="weeklyStatus">In progress</div>
                </div>
                <span class="week-hours" id="weeklyHours">00h 00m 00sec</span>
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
        </section>

        <p class="timezone-note">Times are shown in Pacific Time (PT).</p>
    </main>

    <script>
        let elapsedSeconds = 0;
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
                elapsedSeconds = Math.floor((Date.now() - clockInTimestamp) / 1000);
            }

            timer.textContent = formatTime(elapsedSeconds);
            weeklyHours.textContent = formatHoursMinutes(elapsedSeconds);
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

            clockStatus.textContent = 'On the clock';
            timerDescription.textContent = 'Clocked in today at ' + currentTime;
            clockInTime.textContent = currentTime;
            clockOutTime.textContent = 'Not yet recorded';

            activityBadge.textContent = 'IN';
            activityTitle.textContent = 'Clocked in';

            clockInButton.disabled = true;
            clockOutButton.disabled = false;
            weeklyStatus.textContent = 'In progress';

            startTimer();
        });

        clockOutButton.addEventListener('click', function () {
            if (!isClockedIn) return;

            elapsedSeconds = Math.floor((Date.now() - clockInTimestamp) / 1000);
            isClockedIn = false;

            const currentTime = formatClockTime(new Date());

            clockStatus.textContent = 'Shift complete';
            timerDescription.textContent = 'Clocked out at ' + currentTime;
            clockOutTime.textContent = currentTime;

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

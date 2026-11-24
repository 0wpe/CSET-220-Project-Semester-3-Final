
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

        .break-actions {
            display: flex;
            justify-content: center;
            margin-top: 14px;
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
            <span class="status" id="clockStatus">Not Clocked In</span>

            <div class="timer" id="timer">00:00:00</div>

            <p class="timer-description" id="timerDescription">
                Clocked in today at 09:00 AM
            </p>

            
        <div class="timer-actions">
    @if(!$attendance || $attendance->clock_out)
        <form method="POST" action="{{ route('employee.clock-in') }}">
            @csrf
            <button type="submit" class="timer-button" id="clockInButton">
                Clock-In
            </button>
        </form>
    @else
        <button type="button" class="timer-button" id="clockInButton" disabled>
            Clock-In
        </button>
    @endif

    @if($attendance && !$attendance->clock_out)
        <form method="POST" action="{{ route('employee.clock-out') }}">
            @csrf
            <button type="submit" class="timer-button primary" id="clockOutButton">
                Clock-Out
            </button>
        </form>
    @else
        <button type="button" class="timer-button primary" id="clockOutButton" disabled>
            Clock-Out
        </button>
    @endif
</div>

@if($attendance && !$attendance->clock_out)
    <div class="break-actions">
        @if($isOnBreak)
            <form method="POST" action="{{ route('employee.resume-work') }}">
                @csrf
                <button type="submit" class="timer-button">
                    Resume Work
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('employee.start-break') }}">
                @csrf
                <button type="submit" class="timer-button">
                    Start Break
                </button>
            </form>
        @endif
    </div>
@endif
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
    const savedClockInTimestamp = @json(
    $attendance && !$attendance->clock_out
        ? $attendance->clock_in->timestamp * 1000
        : null
);

const savedShiftStartTimestamp = @json(
    $attendance
        ? $attendance->clock_in->timestamp * 1000
        : null
);

const savedBreakMinutes = @json($attendance ? $attendance->break_minutes : 0);

const isOnBreak = @json($isOnBreak);

const breakStartedTimestamp = @json(
    $isOnBreak
        ? session()->get('break_started_at_' . $attendance->employee_id) * 1000
        : null
);

const savedClockOutTimestamp = @json(
    $attendance && $attendance->clock_out
        ? $attendance->clock_out->timestamp * 1000
        : null
);

let clockInTimestamp = savedClockInTimestamp;
let isClockedIn = clockInTimestamp !== null;
let elapsedSeconds = 0;
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
        let totalSeconds = Math.floor(
            (Date.now() - clockInTimestamp) / 1000
        );

        let breakSeconds = savedBreakMinutes * 60;

        if (isOnBreak && breakStartedTimestamp !== null) {
            breakSeconds += Math.floor(
                (Date.now() - breakStartedTimestamp) / 1000
            );
        }

        elapsedSeconds = Math.max(0, totalSeconds - breakSeconds);
    }

    timer.textContent = formatTime(elapsedSeconds);

    if (weeklyHours) {
        weeklyHours.textContent = formatHoursMinutes(elapsedSeconds);
    }
}

function startTimer() {
    clearInterval(timerInterval);

    updateTimer();

    timerInterval = setInterval(updateTimer, 1000);
}

// Set the page based on the saved attendance record
if (isClockedIn) {
    const startTime = new Date(clockInTimestamp);
    const currentTime = formatClockTime(startTime);

       if (isOnBreak) {
    clockStatus.textContent = 'On Break';
    timerDescription.textContent = 'You are currently on break.';
} else {
    clockStatus.textContent = 'On the clock';
    timerDescription.textContent = 'Clocked in today at ' + currentTime;
}

    if (clockInTime) {
        clockInTime.textContent = currentTime;
    }

    if (clockOutTime) {
        clockOutTime.textContent = 'Not yet recorded';
    }

    if (activityBadge) activityBadge.textContent = 'IN';
    if (activityTitle) activityTitle.textContent = 'Clocked in';
    if (weeklyStatus) weeklyStatus.textContent = 'In progress';

    clockInButton.disabled = true;
    clockOutButton.disabled = false;

    startTimer();

} else if (savedClockOutTimestamp !== null) {
    const startTime = new Date(@json(
        $attendance ? $attendance->clock_in->timestamp * 1000 : null
    ));

    const endTime = new Date(savedClockOutTimestamp);
    clearInterval(timerInterval);

elapsedSeconds = Math.max(
    0,
    Math.floor(
        (savedClockOutTimestamp - savedShiftStartTimestamp) / 1000
    ) - (savedBreakMinutes * 60)
);

    clockStatus.textContent = 'Shift complete';
    timerDescription.textContent = 'Clocked out at ' + formatClockTime(endTime);

    if (clockInTime) {
        clockInTime.textContent = formatClockTime(startTime);
    }

    if (clockOutTime) {
        clockOutTime.textContent = formatClockTime(endTime);
    }

    if (activityBadge) activityBadge.textContent = 'OUT';
    if (activityTitle) activityTitle.textContent = 'Clocked out';
    if (weeklyStatus) weeklyStatus.textContent = 'Completed';

    clockInButton.disabled = false;
    clockOutButton.disabled = true;

    updateTimer();

} else {
    clockStatus.textContent = 'Not Clocked In';
    timerDescription.textContent = 'You have not clocked in yet.';

    if (activityBadge) activityBadge.textContent = 'OUT';
    if (activityTitle) activityTitle.textContent = 'Not clocked in';
    if (weeklyStatus) weeklyStatus.textContent = 'Not started';

    clockInButton.disabled = false;
    clockOutButton.disabled = true;

    updateTimer();
}
    </script>
@endsection

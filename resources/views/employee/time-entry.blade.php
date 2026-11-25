@extends('layouts.app')

@section('title', 'Time Entry | Tempo HR')

@section('content')

    @include('partials.navbar', ['activePage' => 'time'])

    @php
        /*
        |--------------------------------------------------------------------------
        | Attendance and break calculations
        |--------------------------------------------------------------------------
        */

        $isClockedIn = $attendance
            && $attendance->clock_in
            && !$attendance->clock_out;

        $hasClockedOut = $attendance
            && $attendance->clock_out;

        // The database is the source of truth for the current break state.
        $isOnBreak = $isClockedIn
            && $attendance->break_started_at;

        $clockInTimeValue = $attendance?->clock_in;
        $clockOutTimeValue = $attendance?->clock_out;

        // Actual completed break duration, including partial minutes.
        $breakSeconds = (int) ($attendance?->break_seconds ?? 0);

        // Fallback for older attendance records.
        if (
            $breakSeconds === 0
            && (int) ($attendance?->break_minutes ?? 0) > 0
        ) {
            $breakSeconds = (int) $attendance->break_minutes * 60;
        }

        // Current ongoing break duration.
        $breakStartedAt = $attendance?->break_started_at;

        $activeBreakSeconds = 0;

        if ($isOnBreak && $breakStartedAt) {
            $activeBreakSeconds = max(
                0,
                now()->getTimestamp() - $breakStartedAt->getTimestamp()
            );
        }

        // Scheduled break allowance comes from the assigned shift.
        $scheduledBreakMinutes = (int) ($scheduledBreakMinutes ?? 0);

        // Calculate actual worked time.
        $elapsedSeconds = 0;

        if ($clockInTimeValue) {
            $endTime = $clockOutTimeValue ?? now();

            $elapsedSeconds = max(
                0,
                $endTime->getTimestamp()
                    - $clockInTimeValue->getTimestamp()
                    - $breakSeconds
                    - $activeBreakSeconds
            );
        }

        // Total actual break duration, including an ongoing break.
        $actualBreakSeconds = $breakSeconds + $activeBreakSeconds;

        $actualBreakMinutes = (int) ceil($actualBreakSeconds / 60);

        $breakOverageMinutes = max(
            0,
            $actualBreakMinutes - $scheduledBreakMinutes
        );

        // Only show attendance records from the current week for this employee.
        $weekAttendance = $employee->attendance()
            ->with(['assignment.shift'])
            ->whereBetween('clock_in', [now()->startOfWeek(), now()->endOfWeek()])
            ->orderByDesc('clock_in')
            ->get();
    @endphp

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

        .break-actions {
            display: flex;
            justify-content: center;
            margin-top: 14px;
        }

        .timer-button {
            min-height: 49px;
            width: 100%;
            padding: 0 15px;
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

        .timer-button.break-button {
            background: #F2F6F3;
            color: #176454;
            border-color: #D5E4DB;
        }

        .timer-button.break-button:hover:not(:disabled) {
            background: #E7F0EB;
        }

        .timer-button.primary:hover:not(:disabled) {
            background: #124F42;
        }

        .timer-button:hover:not(:disabled):not(.primary):not(.break-button) {
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

        @if(session('success'))
            <div role="status" style="margin-bottom: 18px; padding: 12px 16px; border-radius: 10px; background: #E7F0EB; color: #176454;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div role="alert" style="margin-bottom: 18px; padding: 12px 16px; border-radius: 10px; background: #FCEFED; color: #A33B32;">
                {{ session('error') }}
            </div>
        @endif

        <header class="page-header">
            <div>
                <div class="eyebrow">ATTENDANCE</div>

                <h1 class="page-title">Time entry</h1>

                <p class="page-subtitle">
                    Stay on top of your working hours.
                </p>
            </div>

            <div class="date-label">
                {{ now()->format('l, F j, Y') }}
            </div>
        </header>

        <div class="time-main-grid">

            {{-- WORK SESSION TIMER --}}

            <section class="timer-card">

                <div class="timer-top">
                    <h2 class="timer-heading">Your work session</h2>

                    <span class="status" id="clockStatus">
                        <span
                            class="status-dot"
                            @if(!$isClockedIn) style="background: #82928C;"
                            @elseif($isOnBreak) style="background: #D69B38;"
                            @endif
                        ></span>

                        {{ $hasClockedOut
                            ? 'Shift complete'
                            : ($isOnBreak
                                ? 'On break'
                                : ($isClockedIn
                                    ? 'On the clock'
                                    : 'Not clocked in')) }}
                    </span>
                </div>

                <div class="timer-display">

                    <div class="timer" id="timer">00:00:00</div>

                    <p class="timer-description" id="timerDescription">
                        @if($isOnBreak)
                            Your work timer is paused while you are on break.
                        @elseif($clockInTimeValue)
                            @if($hasClockedOut)
                                Shift completed at {{ $clockOutTimeValue->format('h:i A') }}
                            @else
                                Clocked in today at {{ $clockInTimeValue->format('h:i A') }}
                            @endif
                        @elseif($assignment)
                            Ready to clock in
                        @else
                            No shift assigned today
                        @endif
                    </p>

                </div>

                <div class="timer-actions">

                    {{-- CLOCK-IN --}}

                    @if($assignment && !$attendance)

                        <form action="{{ route('employee.clock-in') }}" method="POST">
                            @csrf

                            <button
                                type="submit"
                                class="timer-button primary"
                                style="width: 100%;"
                            >
                                Clock-In
                            </button>
                        </form>

                    @else

                        <button
                            type="button"
                            class="timer-button primary"
                            disabled
                        >
                            Clock-In
                        </button>

                    @endif

                    {{-- CLOCK-OUT --}}

                    @if($isClockedIn && !$isOnBreak)

                        <form action="{{ route('employee.clock-out') }}" method="POST">
                            @csrf

                            <button
                                type="submit"
                                class="timer-button"
                                style="width: 100%;"
                            >
                                Clock-Out
                            </button>
                        </form>

                    @else

                        <button
                            type="button"
                            class="timer-button"
                            disabled
                        >
                            Clock-Out
                        </button>

                    @endif

                </div>

                {{-- BREAK CONTROLS --}}

                @if($isClockedIn)

                    <div class="break-actions">

                        @if($isOnBreak)

                            <form method="POST" action="{{ route('employee.resume-work') }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="timer-button break-button"
                                >
                                    Resume Work
                                </button>
                            </form>

                        @else

                            <form method="POST" action="{{ route('employee.start-break') }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="timer-button break-button"
                                >
                                    Start Break
                                </button>
                            </form>

                        @endif

                    </div>

                @endif

            </section>

            {{-- TODAY'S SUMMARY --}}

            <section class="today-card">

                <div>
                    <div class="today-card-label">Today's total</div>

                    <div class="today-hours" id="todayHours">00h 00m</div>

                    <p class="today-description">
                        Time recorded for your current shift
                    </p>
                </div>

                <div>
                    <div class="today-divider"></div>

                    <div class="today-card-bottom">

                        <div class="today-detail">
                            <span>Clock-in</span>

                            <span id="summaryClockIn">
                                {{ $clockInTimeValue
                                    ? $clockInTimeValue->format('h:i A')
                                    : 'Not recorded' }}
                            </span>
                        </div>

                        <div class="today-detail">
                            <span>Clock-out</span>

                            <span id="summaryClockOut">
                                {{ $clockOutTimeValue
                                    ? $clockOutTimeValue->format('h:i A')
                                    : 'Not recorded' }}
                            </span>
                        </div>

                        <div class="today-detail">
                            <span>Scheduled break</span>

                            <span>
                                {{ $scheduledBreakMinutes }} min
                            </span>
                        </div>

                        <div class="today-detail">
                            <span>Actual break</span>

                            <span id="summaryBreak">
                                {{ $actualBreakMinutes }} min
                            </span>
                        </div>

                        <div class="today-detail">
                            <span>Break overage</span>

                            <span id="summaryBreakOverage">
                                {{ $breakOverageMinutes }} min
                            </span>
                        </div>

                    </div>
                </div>

            </section>

        </div>

        {{-- ACTIVITY AND WEEKLY ATTENDANCE --}}

        <div class="time-content-grid">

            <section class="content-section">

                <div class="section-heading">
                    <h2>Today's activity</h2>

                    <span class="section-caption">Current shift</span>
                </div>

                <div class="activity-card">

                    <div class="activity-top">

                        <span class="activity-badge" id="activityBadge">
                            {{ $hasClockedOut
                                ? 'OUT'
                                : ($isOnBreak
                                    ? 'BRK'
                                    : ($isClockedIn ? 'IN' : '--')) }}
                        </span>

                        <div class="activity-info">

                            <strong id="activityTitle">
                                {{ $hasClockedOut
                                    ? 'Clocked out'
                                    : ($isOnBreak
                                        ? 'On break'
                                        : ($isClockedIn
                                            ? 'Clocked in'
                                            : 'No activity yet')) }}
                            </strong>

                            <span>
                                @if($assignment && $assignment->shift)

                                    Scheduled shift:

                                    {{ \Illuminate\Support\Carbon::parse($assignment->shift->start_time)->format('h:i A') }}

                                    –

                                    {{ \Illuminate\Support\Carbon::parse($assignment->shift->end_time)->format('h:i A') }}

                                @else

                                    No shift assigned today

                                @endif
                            </span>

                        </div>

                        <span class="activity-time" id="clockInTime">
                            {{ $clockInTimeValue
                                ? $clockInTimeValue->format('h:i A')
                                : '—' }}
                        </span>

                    </div>

                    <div class="activity-bottom">

                        <span class="muted">Clock-out</span>

                        <span class="muted" id="clockOutTime">
                            {{ $clockOutTimeValue
                                ? $clockOutTimeValue->format('h:i A')
                                : 'Not yet recorded' }}
                        </span>

                    </div>

                </div>

            </section>

            {{-- WEEKLY ATTENDANCE --}}

            <section class="content-section">

                <div class="section-heading">
                    <h2>This week</h2>

                    <a href="/employee/my-schedule" class="view-link">
                        View schedule
                    </a>
                </div>

                <div class="weekly-card">

                    @forelse($weekAttendance as $weekRecord)

                        @php
                            $recordEnd = $weekRecord->clock_out ?? now();

                            $recordSeconds = max(
                                0,
                                $recordEnd->getTimestamp()
                                    - $weekRecord->clock_in->getTimestamp()
                            );

                            // Deduct completed actual breaks, including partial minutes.
                            $recordBreakSeconds = (int) ($weekRecord->break_seconds ?? 0);

                            // Fallback for older attendance records.
                            if (
                                $recordBreakSeconds === 0
                                && (int) $weekRecord->break_minutes > 0
                            ) {
                                $recordBreakSeconds = (int) $weekRecord->break_minutes * 60;
                            }

                            $recordSeconds = max(
                                0,
                                $recordSeconds - $recordBreakSeconds
                            );

                            // Also deduct an ongoing break for the current record.
                            if ($weekRecord->break_started_at && !$weekRecord->clock_out) {

                                $recordActiveBreakSeconds = max(
                                    0,
                                    now()->getTimestamp()
                                        - $weekRecord->break_started_at->getTimestamp()
                                );

                                $recordSeconds = max(
                                    0,
                                    $recordSeconds - $recordActiveBreakSeconds
                                );
                            }

                            $recordHours = floor($recordSeconds / 3600);

                            $recordMinutes = floor(
                                ($recordSeconds % 3600) / 60
                            );
                        @endphp

                        <div class="week-row {{ $weekRecord->attendance_id === ($attendance?->attendance_id) ? 'current' : '' }}">

                            <div>

                                <div class="week-date">
                                    {{ $weekRecord->clock_in->format('D, M j') }}
                                </div>

                                <div class="week-description">

                                    {{ $weekRecord->clock_in->format('h:i A') }}

                                    –

                                    {{ $weekRecord->clock_out
                                        ? $weekRecord->clock_out->format('h:i A')
                                        : ($weekRecord->break_started_at
                                            ? 'On break'
                                            : 'In progress') }}

                                </div>

                            </div>

                            <span class="week-hours">
                                {{ sprintf('%02dh %02dm', $recordHours, $recordMinutes) }}
                            </span>

                        </div>

                    @empty

                        <div class="week-row">

                            <div>
                                <div class="week-date">
                                    No attendance recorded
                                </div>

                                <div class="week-description">
                                    Your recorded shifts will appear here
                                </div>
                            </div>

                            <span class="week-hours">—</span>

                        </div>

                    @endforelse

                </div>

            </section>

        </div>

        <p class="timezone-note">
            Times are shown in {{ config('app.timezone') }}.
        </p>

    </main>

    <script>
        (function () {

            /*
            |--------------------------------------------------------------------------
            | Elements
            |--------------------------------------------------------------------------
            */

            const timer = document.getElementById('timer');
            const todayHours = document.getElementById('todayHours');
            const summaryBreak = document.getElementById('summaryBreak');
            const summaryBreakOverage = document.getElementById('summaryBreakOverage');

            /*
            |--------------------------------------------------------------------------
            | Database attendance values
            |--------------------------------------------------------------------------
            */

            const isClockedIn = @json((bool) $isClockedIn);
            const isOnBreak = @json((bool) $isOnBreak);
            const hasClockedOut = @json((bool) $hasClockedOut);

            const clockInTimestamp = @json(
                $clockInTimeValue
                    ? $clockInTimeValue->getTimestamp()
                    : null
            );

            const clockOutTimestamp = @json(
                $clockOutTimeValue
                    ? $clockOutTimeValue->getTimestamp()
                    : null
            );

            const breakStartedTimestamp = @json(
                $breakStartedAt
                    ? $breakStartedAt->getTimestamp()
                    : null
            );

            const completedBreakSeconds = @json((int) $breakSeconds);

            const scheduledBreakMinutes = @json((int) $scheduledBreakMinutes);

            /*
            |--------------------------------------------------------------------------
            | Time formatting
            |--------------------------------------------------------------------------
            */

            function formatTime(totalSeconds) {

                const hours = Math.floor(totalSeconds / 3600);

                const minutes = Math.floor(
                    (totalSeconds % 3600) / 60
                );

                const seconds = totalSeconds % 60;

                return [hours, minutes, seconds]
                    .map(value => String(value).padStart(2, '0'))
                    .join(':');
            }

            function formatHoursMinutes(totalSeconds) {

                const hours = Math.floor(totalSeconds / 3600);

                const minutes = Math.floor(
                    (totalSeconds % 3600) / 60
                );

                return String(hours).padStart(2, '0') + 'h ' +
                    String(minutes).padStart(2, '0') + 'm';
            }

            /*
            |--------------------------------------------------------------------------
            | Timer calculations
            |--------------------------------------------------------------------------
            */

            function updateTimer() {

                const currentTimestamp = Math.floor(Date.now() / 1000);

                let activeBreakSeconds = 0;

                // Calculate the currently running break from the database timestamp.
                if (isOnBreak && breakStartedTimestamp !== null) {

                    activeBreakSeconds = Math.max(
                        0,
                        currentTimestamp - breakStartedTimestamp
                    );

                }

                let elapsedSeconds = 0;

                if (clockInTimestamp !== null) {

                    // A completed shift uses its saved clock-out time.
                    const endTimestamp = hasClockedOut && clockOutTimestamp !== null
                        ? clockOutTimestamp
                        : currentTimestamp;

                    const totalElapsed = Math.max(
                        0,
                        endTimestamp - clockInTimestamp
                    );

                    // Subtract completed breaks and the current active break.
                    elapsedSeconds = Math.max(
                        0,
                        totalElapsed
                            - completedBreakSeconds
                            - (hasClockedOut ? 0 : activeBreakSeconds)
                    );

                }

                // Calculate total break duration.
                const totalActualBreakSeconds =
                    completedBreakSeconds + activeBreakSeconds;

                const actualBreakMinutes = Math.ceil(
                    totalActualBreakSeconds / 60
                );

                const breakOverageMinutes = Math.max(
                    0,
                    actualBreakMinutes - scheduledBreakMinutes
                );

                /*
                |--------------------------------------------------------------------------
                | Update displayed values
                |--------------------------------------------------------------------------
                */

                timer.textContent = formatTime(elapsedSeconds);

                todayHours.textContent = formatHoursMinutes(elapsedSeconds);

                summaryBreak.textContent = actualBreakMinutes + ' min';

                summaryBreakOverage.textContent =
                    breakOverageMinutes + ' min';

            }

            /*
            |--------------------------------------------------------------------------
            | Start the timer
            |--------------------------------------------------------------------------
            */

            updateTimer();

            // Keep updating while the employee is clocked in.
            // This also updates the break duration while the employee is on break.
            if (isClockedIn) {

                window.setInterval(updateTimer, 1000);

            }

        })();
    </script>

@endsection
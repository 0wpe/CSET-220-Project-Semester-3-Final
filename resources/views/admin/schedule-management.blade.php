@extends('layouts.app')

@section('title', 'Schedule Management')

@section('content')
@include('partials.navbar-admin', ['activePage' => 'schedule'])

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #F6F8F7;
        font-family: 'Inter', Arial, sans-serif;
        color: #172E29;
    }

    .schedule-page {
        width: 100%;
        min-width: 0;
        padding: clamp(22px, 3vw, 44px) clamp(18px, 3vw, 55px);
    }

    /* Page heading */

    .schedule-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .eyebrow {
        margin-bottom: 8px;
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
    }

    .page-title {
        margin: 0;
        color: #172E29;
        font-size: clamp(27px, 2.3vw, 36px);
        font-weight: 650;
        line-height: 1.2;
    }

    .page-subtitle {
        margin: 8px 0 0;
        color: #687B74;
        font-size: 13px;
    }

    .schedule-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }

    .new-schedule-btn {
        min-height: 42px;
        padding: 10px 17px;
        border: 1px solid #DFE7E2;
        border-radius: 9px;
        background: #FFFFFF;
        color: #176454;
        font-family: inherit;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        transition: .2s ease;
    }

    .new-schedule-btn:hover {
        border-color: #176454;
        background: #E7F0EB;
    }

    .draft-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        border: 1px solid #DCE9E1;
        border-radius: 20px;
        background: #E7F0EB;
        color: #176454;
        font-size: 11px;
        font-weight: 650;
    }

    /* Alerts */

    .alert,
    .alert-validation {
        margin-bottom: 16px;
        padding: 13px 16px;
        border-radius: 10px;
        font-size: 12px;
        line-height: 1.5;
    }

    .alert-success {
        background: #E7F0EB;
        border-left: 4px solid #176454;
        color: #176454;
    }

    .alert-error,
    .alert-validation {
        background: #FCE8E8;
        border-left: 4px solid #B42318;
        color: #B42318;
    }

    /* Calendar */

    .calendar-card {
        margin-bottom: 30px;
        padding: clamp(17px, 2vw, 27px);
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 17px;
        box-shadow: 0 3px 12px rgba(23, 46, 41, .035);
    }

    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 23px;
    }

    .calendar-header h2 {
        margin: 0;
        color: #172E29;
        font-size: 18px;
        font-weight: 650;
        text-align: center;
    }

    .month-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 39px;
        height: 37px;
        flex-shrink: 0;
        border: 1px solid #E3EAE7;
        border-radius: 9px;
        background: #F6F8F7;
        color: #176454;
        font-size: 23px;
        text-decoration: none;
        transition: .2s ease;
    }

    .month-btn:hover {
        background: #E7F0EB;
        border-color: #C9DCD1;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 8px;
    }

    .weekday {
        padding-bottom: 9px;
        color: #71817C;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
    }

    .calendar-day {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-width: 0;
        min-height: 62px;
        border: 1px solid transparent;
        border-radius: 10px;
        background: #E7F0EB;
        color: #172E29;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        transition: .15s ease;
    }

    .calendar-day small {
        color: #176454;
        font-size: 9px;
        font-weight: 550;
    }

    .calendar-day.off {
        background: #F6F8F7;
        color: #687B74;
    }

    .calendar-day.off small {
        color: #71817C;
    }

    .calendar-day.selected {
        border-color: #176454;
        background: #176454;
        color: #FFFFFF;
        box-shadow: 0 3px 8px rgba(23, 100, 84, .18);
    }

    .calendar-day.selected small {
        color: #FFFFFF;
    }

    .calendar-day.empty {
        background: transparent;
        border-color: transparent;
        pointer-events: none;
    }

    .calendar-day:not(.empty):hover {
        border-color: #176454;
        transform: translateY(-1px);
    }

    .calendar-note {
        margin: 17px 0 0;
        color: #71817C;
        font-size: 11px;
    }

    /* Selected day */

    .selected-day-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 10px;
        margin: 0 0 17px;
    }

    .selected-day-heading h2 {
        margin: 0 0 5px;
        color: #172E29;
        font-size: 19px;
        font-weight: 650;
    }

    .selected-day-heading p {
        margin: 0;
        color: #687B74;
        font-size: 12px;
    }

    /* Shift cards */

    .shift-card {
        margin-bottom: 15px;
        padding: clamp(17px, 2vw, 25px);
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(23, 46, 41, .025);
    }

    .shift-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #E9EFEC;
    }

    .shift-card-header h3 {
        margin: 0;
        color: #172E29;
        font-size: 15px;
        font-weight: 650;
    }

    .employee-count {
        color: #687B74;
        font-size: 11px;
        white-space: nowrap;
    }

    .time-fields {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 18px;
    }

    .field-group {
        min-width: 0;
    }

    .field-group label {
        display: block;
        margin-bottom: 7px;
        color: #687B74;
        font-size: 11px;
        font-weight: 600;
    }

    .field-group input,
    .field-group select {
        width: 100%;
        min-width: 0;
        height: 42px;
        padding: 0 11px;
        border: 1px solid #DFE7E2;
        border-radius: 8px;
        background: #FFFFFF;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
    }

    .field-group input:focus,
    .field-group select:focus {
        outline: none;
        border-color: #176454;
        box-shadow: 0 0 0 3px rgba(23, 100, 84, .08);
    }

    /* Employee assignments */

    .assignments {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 4px 0 17px;
    }

    .assignment-tag {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 7px 11px;
        border: 1px solid #DCE9E1;
        border-radius: 20px;
        background: #E7F0EB;
        color: #176454;
        font-size: 11px;
        font-weight: 600;
    }

    .assignment-tag button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        padding: 0;
        border: none;
        border-radius: 50%;
        background: transparent;
        color: #176454;
        font-size: 16px;
        cursor: pointer;
    }

    .assignment-tag button:hover {
        background: rgba(23, 100, 84, .12);
    }

    .assignment-tag form {
        display: inline-flex;
        margin: 0;
    }

    .shift-actions {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .text-btn {
        padding: 9px 4px;
        border: none;
        background: transparent;
        color: #176454;
        font-family: inherit;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        text-decoration: none;
    }

    .text-btn:hover {
        color: #124F43;
        text-decoration: underline;
    }

    .text-btn.remove {
        color: #9B5550;
        font-weight: 550;
    }

    .text-btn.remove:hover {
        color: #B42318;
    }

    /* Empty states */

    .empty-state {
        margin-bottom: 16px;
        padding: 35px 20px;
        border: 1px dashed #C9D8D0;
        border-radius: 14px;
        background: #FFFFFF;
        color: #687B74;
        font-size: 12px;
        text-align: center;
    }

    .empty-state strong {
        display: block;
        margin-bottom: 7px;
        color: #172E29;
        font-size: 14px;
        font-weight: 650;
    }

    /* Schedule action buttons */

    .bottom-actions {
        display: flex;
        gap: 12px;
        margin: 22px 0 30px;
    }

    .save-btn,
    .publish-btn {
        min-height: 47px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        transition: .2s ease;
    }

    .save-btn {
        flex: 1;
        border: 1px solid #D8E3DD;
        background: #FFFFFF;
        color: #176454;
    }

    .save-btn:hover {
        border-color: #176454;
        background: #E7F0EB;
    }

    .publish-btn {
        flex: 1.6;
        border: 1px solid #176454;
        background: #176454;
        color: #FFFFFF;
    }

    .publish-btn:hover {
        background: #124F43;
    }

    /* Schedule history */

    .section-heading {
        margin: 34px 0 16px;
        color: #172E29;
        font-size: 19px;
        font-weight: 650;
    }

    .history-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 11px;
        padding: 19px 21px;
        border: 1px solid #E3EAE7;
        border-radius: 13px;
        background: #FFFFFF;
        transition: border-color .2s ease;
    }

    .history-card:hover {
        border-color: #C9DCD1;
    }

    .history-info {
        min-width: 0;
    }

    .history-info h3 {
        margin: 0 0 5px;
        color: #172E29;
        font-size: 14px;
        font-weight: 650;
    }

    .history-info p {
        margin: 0;
        color: #71817C;
        font-size: 11px;
    }

    .history-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-shrink: 0;
    }

    .history-status {
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 650;
    }

    .history-status.draft {
        background: #FFF3D6;
        color: #956B13;
    }

    .history-status.published {
        background: #E7F0EB;
        color: #176454;
    }

    .view-schedule-btn {
        border: none;
        background: transparent;
        color: #176454;
        font-family: inherit;
        font-size: 11px;
        font-weight: 650;
        cursor: pointer;
        text-decoration: none;
    }

    .view-schedule-btn:hover {
        text-decoration: underline;
    }

    /* Tablet */

    @media (max-width: 900px) {
        .schedule-page {
            padding: 25px 22px 35px;
        }

        .calendar-day {
            min-height: 54px;
        }

        .time-fields {
            gap: 12px;
        }
    }

    /* Mobile */

    @media (max-width: 600px) {
        .schedule-page {
            padding: 22px 16px 30px;
        }

        .schedule-heading {
            margin-bottom: 20px;
        }

        .page-subtitle {
            font-size: 12px;
        }

        .schedule-actions {
            align-items: flex-start;
            flex-direction: column;
            margin-bottom: 19px;
        }

        .calendar-card {
            padding: 14px 11px;
            border-radius: 14px;
        }

        .calendar-header {
            margin-bottom: 17px;
        }

        .calendar-header h2 {
            font-size: 16px;
        }

        .month-btn {
            width: 34px;
            height: 34px;
        }

        .calendar-grid {
            gap: 4px;
        }

        .weekday {
            font-size: 10px;
            padding-bottom: 7px;
        }

        .calendar-day {
            min-height: 44px;
            gap: 3px;
            border-radius: 7px;
            font-size: 11px;
        }

        .calendar-day small {
            font-size: 7px;
        }

        .calendar-note {
            font-size: 10px;
        }

        .selected-day-heading h2 {
            font-size: 17px;
        }

        .selected-day-heading p {
            font-size: 11px;
        }

        .shift-card {
            padding: 17px;
        }

        .shift-card-header {
            margin-bottom: 16px;
        }

        .shift-card-header h3 {
            font-size: 13px;
        }

        .time-fields {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .field-group label {
            font-size: 10px;
        }

        .field-group input,
        .field-group select {
            height: 40px;
            font-size: 12px;
        }

        .shift-actions {
            align-items: stretch;
        }

        .shift-actions > .field-group {
            flex-basis: 100%;
        }

        .shift-actions > button {
            align-self: flex-start;
        }

        .bottom-actions {
            gap: 8px;
        }

        .save-btn,
        .publish-btn {
            min-height: 44px;
            font-size: 11px;
        }

        .history-card {
            align-items: flex-start;
            flex-direction: column;
            padding: 16px;
        }

        .history-meta {
            width: 100%;
            justify-content: space-between;
        }

        .section-heading {
            font-size: 17px;
        }
    }

    @media (max-width: 360px) {
        .schedule-page {
            padding-left: 12px;
            padding-right: 12px;
        }

        .calendar-card {
            padding-left: 8px;
            padding-right: 8px;
        }

        .calendar-day {
            min-height: 39px;
            font-size: 10px;
        }

        .calendar-day small {
            font-size: 6px;
        }

        .time-fields {
            grid-template-columns: minmax(0, 1fr);
        }

        .bottom-actions {
            flex-direction: column;
        }

        .save-btn,
        .publish-btn {
            width: 100%;
            flex: 1;
        }
    }

    @media (min-width: 1500px) {
        .calendar-day {
            min-height: 78px;
        }

        .calendar-grid {
            gap: 10px;
        }
    }
</style>

<main class="schedule-page">

    <header class="schedule-heading">
        <div>
            <div class="eyebrow">TEAM SCHEDULING</div>

            <h1 class="page-title">Schedule management</h1>

            <p class="page-subtitle">
                Plan your team’s month, one shift at a time.
            </p>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-validation">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="schedule-actions">

        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf

            <input
                type="hidden"
                name="schedule_month"
                value="{{ $month }}"
            >

            <button class="new-schedule-btn" type="submit">
                + New monthly schedule
            </button>
        </form>

        @if($selectedSchedule)
            <span class="draft-status">
                {{ $selectedSchedule->status }} · {{ $monthDate->format('M Y') }}
            </span>
        @endif

    </div>

    <!-- Monthly Calendar -->

    <div class="calendar-card">

        <div class="calendar-header">

            <a
                class="month-btn"
                href="{{ url('/admin/schedule-management') }}?month={{ $monthDate->copy()->subMonth()->format('Y-m') }}"
                aria-label="Previous month"
            >
                ‹
            </a>

            <h2>{{ $monthDate->format('F Y') }}</h2>

            <a
                class="month-btn"
                href="{{ url('/admin/schedule-management') }}?month={{ $monthDate->copy()->addMonth()->format('Y-m') }}"
                aria-label="Next month"
            >
                ›
            </a>

        </div>

        <div class="calendar-grid">

            <div class="weekday">S</div>
            <div class="weekday">M</div>
            <div class="weekday">T</div>
            <div class="weekday">W</div>
            <div class="weekday">T</div>
            <div class="weekday">F</div>
            <div class="weekday">S</div>

            @php
                $daysInMonth = $monthDate->daysInMonth;
                $firstDayOfWeek = $monthDate->copy()->startOfMonth()->dayOfWeek;

                $selectedDay = \Carbon\Carbon::parse($selectedDate)->day;
            @endphp

            @for($i = 0; $i < $firstDayOfWeek; $i++)
                <div class="calendar-day empty"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)

                @php
                    $calendarDate = $monthDate->copy()->day($day)->format('Y-m-d');

                    $dailyShifts = $selectedSchedule
                        ? $selectedSchedule->shifts->where('shift_date', $calendarDate)
                        : collect();

                    $dailyEmployeeCount = $dailyShifts
                        ->flatMap(function ($shift) {
                            return $shift->assignments->pluck('employee_id');
                        })
                        ->unique()
                        ->count();

                    $isSelected = $calendarDate === $selectedDate;
                    $isOff = $dailyShifts->isEmpty();
                @endphp

                <a
                    href="{{ url('/admin/schedule-management') }}?month={{ $month }}&date={{ $calendarDate }}"
                    class="calendar-day {{ $isOff ? 'off' : '' }} {{ $isSelected ? 'selected' : '' }}"
                >
                    <span>{{ $day }}</span>

                    <small>
                        @if($dailyShifts->isNotEmpty())
                            {{ $dailyEmployeeCount }} staff
                        @else
                            Off
                        @endif
                    </small>
                </a>

            @endfor

        </div>

        <p class="calendar-note">
            Select a day to view or edit its assignments.
        </p>

    </div>

    <!-- Selected Day -->

    <div class="selected-day-heading">

        <div>
            <h2>
                {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j') }}
            </h2>

            <p>
                {{ $shifts->flatMap(function ($shift) {
                    return $shift->assignments->pluck('employee_id');
                })->unique()->count() }}
                employees scheduled · {{ $shifts->count() }}
                {{ $shifts->count() === 1 ? 'shift' : 'shifts' }}
            </p>
        </div>

    </div>

    @if(!$selectedSchedule)

        <div class="empty-state">
            <strong>No schedule for this month</strong>
            Create a monthly schedule to start assigning shifts.
        </div>

    @elseif($selectedSchedule->status === 'Published')

        <div class="alert alert-success">
            This schedule has been published. Editing is currently disabled.
        </div>

        <form
            action="{{ route('admin.schedules.reopen', $selectedSchedule->schedule_id) }}"
            method="POST"
            style="margin-bottom: 20px;"
            onsubmit="return confirm('Reopen this published schedule as a draft?');"
        >
            @csrf
            @method('PATCH')

            <button class="new-schedule-btn" type="submit">
                Edit Schedule
            </button>
        </form>

    @endif

    @if($selectedSchedule && $selectedSchedule->status === 'Draft')

        <!-- Existing Shifts -->

        @forelse($shifts as $shift)

            <div class="shift-card">

                <div class="shift-card-header">

                    <h3>
                        Shift · {{ \Carbon\Carbon::parse($shift->start_time)->format('g:i A') }}
                        – {{ \Carbon\Carbon::parse($shift->end_time)->format('g:i A') }}
                    </h3>

                    <span class="employee-count">
                        {{ $shift->assignments->count() }}
                        {{ $shift->assignments->count() === 1 ? 'employee' : 'employees' }}
                    </span>

                </div>

                <!-- Update Shift -->

                <form
                    action="{{ route('admin.schedules.shifts.update', $shift->shift_id) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <div class="time-fields">

                        <div class="field-group">
                            <label for="start-{{ $shift->shift_id }}">Shift start</label>

                            <input
                                type="time"
                                id="start-{{ $shift->shift_id }}"
                                name="start_time"
                                value="{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}"
                                required
                            >
                        </div>

                        <div class="field-group">
                            <label for="end-{{ $shift->shift_id }}">Shift end</label>

                            <input
                                type="time"
                                id="end-{{ $shift->shift_id }}"
                                name="end_time"
                                value="{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}"
                                required
                            >
                        </div>

                        <div class="field-group">
                            <label for="break-{{ $shift->shift_id }}">Break duration (minutes)</label>

                            <input
                                type="number"
                                id="break-{{ $shift->shift_id }}"
                                name="break_minutes"
                                min="0"
                                value="{{ $shift->break_minutes }}"
                                required
                            >
                        </div>

                    </div>

                    <div class="shift-actions">
                        <button class="text-btn" type="submit">
                            Save shift changes
                        </button>
                    </div>

                </form>

                <!-- Employee Assignments -->

                <div class="assignments">

                    @forelse($shift->assignments as $assignment)

                        @php
                            $assignedUser = $assignment->employee->user ?? null;
                        @endphp

                        <div class="assignment-tag">

                            {{ $assignedUser
                                ? $assignedUser->first_name . ' ' . $assignedUser->last_name
                                : 'Employee #' . $assignment->employee_id
                            }}

                            <form
                                action="{{ route('admin.schedules.assignments.destroy', $assignment->assignment_id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" aria-label="Remove employee">
                                    ×
                                </button>
                            </form>

                        </div>

                    @empty

                        <span class="employee-count">
                            No employees assigned yet.
                        </span>

                    @endforelse

                </div>

                <!-- Add Employee -->

                <form
                    action="{{ route('admin.schedules.assignments.store', $shift->shift_id) }}"
                    method="POST"
                    class="shift-actions"
                >
                    @csrf

                    <div class="field-group" style="flex: 1; min-width: 180px;">

                        <label for="employee-{{ $shift->shift_id }}">
                            Assign employee
                        </label>

                        <select
                            id="employee-{{ $shift->shift_id }}"
                            name="employee_id"
                            required
                        >
                            <option value="">Select employee</option>

                            @foreach($employees as $employee)

                                @php
                                    $employeeUser = $employee->user;
                                    $alreadyAssigned = $shift->assignments
                                        ->contains('employee_id', $employee->employee_id);
                                @endphp

                                @if($employeeUser && !$alreadyAssigned)
                                    <option value="{{ $employee->employee_id }}">
                                        {{ $employeeUser->first_name }}
                                        {{ $employeeUser->last_name }}
                                    </option>
                                @endif

                            @endforeach

                        </select>

                    </div>

                    <button class="text-btn" type="submit">
                        + Add employee
                    </button>

                </form>

                <!-- Remove Shift -->

                <div class="shift-actions">

                    <form
                        action="{{ route('admin.schedules.shifts.destroy', $shift->shift_id) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to remove this shift?');"
                    >
                        @csrf
                        @method('DELETE')

                        <button class="text-btn remove" type="submit">
                            Remove shift
                        </button>
                    </form>

                </div>

            </div>

        @empty

            <div class="empty-state">
                <strong>No shifts for this day</strong>
                Add a shift below to begin scheduling employees.
            </div>

        @endforelse

        <!-- Add New Shift -->

        <div class="shift-card">

            <div class="shift-card-header">
                <h3>Add another shift</h3>
            </div>

            <form
                action="{{ route('admin.schedules.shifts.store', $selectedSchedule->schedule_id) }}"
                method="POST"
            >
                @csrf

                <input
                    type="hidden"
                    name="shift_date"
                    value="{{ $selectedDate }}"
                >

                <div class="time-fields">

                    <div class="field-group">
                        <label for="new-start">Shift start</label>
                        <input
                            type="time"
                            id="new-start"
                            name="start_time"
                            required
                        >
                    </div>

                    <div class="field-group">
                        <label for="new-end">Shift end</label>
                        <input
                            type="time"
                            id="new-end"
                            name="end_time"
                            required
                        >
                    </div>

                    <div class="field-group">
                        <label for="new-break">Break duration (minutes)</label>
                        <input
                            type="number"
                            id="new-break"
                            name="break_minutes"
                            min="0"
                            value="0"
                            required
                        >
                    </div>

                </div>

                <button class="text-btn" type="submit">
                    + Add another shift
                </button>

            </form>

        </div>

        <!-- Schedule Actions -->

        <div class="bottom-actions">

            <form
                action="{{ route('admin.schedules.draft', $selectedSchedule->schedule_id) }}"
                method="POST"
                style="flex: 1;"
            >
                @csrf
                @method('PATCH')

                <button class="save-btn" type="submit" style="width: 100%;">
                    Save draft
                </button>
            </form>

            <form
                action="{{ route('admin.schedules.publish', $selectedSchedule->schedule_id) }}"
                method="POST"
                style="flex: 1.6;"
                onsubmit="return confirm('Are you sure you want to publish this schedule?');"
            >
                @csrf
                @method('PATCH')

                <button class="publish-btn" type="submit" style="width: 100%;">
                    Publish schedule
                </button>
            </form>

        </div>

    @endif

    <!-- Monthly Schedule History -->

    <h2 class="section-heading">
        Monthly Schedule History
    </h2>

    @forelse($schedules as $schedule)

        <div class="history-card">

            <div class="history-info">

                <h3>
                    {{ \Carbon\Carbon::parse($schedule->schedule_month)->format('F Y') }}
                </h3>

                <p>
                    {{ $schedule->shifts_count }}
                    {{ $schedule->shifts_count === 1 ? 'shift' : 'shifts' }}
                </p>

            </div>

            <div class="history-meta">

                <span class="history-status {{ strtolower($schedule->status) }}">
                    {{ $schedule->status }}
                </span>

                <a
                    href="{{ url('/admin/schedule-management') }}?month={{ \Carbon\Carbon::parse($schedule->schedule_month)->format('Y-m') }}"
                    class="view-schedule-btn"
                >
                    View
                </a>

            </div>

        </div>

    @empty

        <div class="empty-state">
            <strong>No monthly schedules yet</strong>
            Your monthly schedules will appear here after you create them.
        </div>

    @endforelse

</main>

@endsection
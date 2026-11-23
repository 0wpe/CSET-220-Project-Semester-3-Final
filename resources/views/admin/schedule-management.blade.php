
@extends('layouts.app')

@section('title', 'Schedule Management')

@section('content')
@include('partials.navbar-admin', ['activePage' => 'schedule'])

<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: #F6F8F7;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .schedule-page {
        width: min(100% - 32px, 900px);
        margin: 32px auto;
        padding-bottom: 40px;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .4px;
        margin-bottom: 8px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 600;
        line-height: 1.45;
        margin: 0;
    }

    .page-subtitle {
        color: #687B74;
        font-size: 13px;
        margin: 4px 0 20px;
    }

    .schedule-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .new-schedule-btn {
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 12px;
        padding: 10px 18px;
        color: #172E29;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .new-schedule-btn:hover {
        border-color: #176454;
    }

    .draft-status {
        background: #E7F0EB;
        color: #176454;
        border-radius: 13px;
        padding: 6px 14px;
        font-size: 10px;
        font-weight: 500;
    }

    .section-heading {
        font-size: 17px;
        font-weight: 600;
        margin: 28px 0 12px;
    }

    .calendar-card {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 28px;
    }

    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .calendar-header h2 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .month-btn {
        width: 36px;
        height: 34px;
        border: none;
        border-radius: 9px;
        background: #F6F8F7;
        color: #176454;
        font-size: 22px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .month-btn:hover {
        background: #E7F0EB;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 4px 6px;
    }

    .weekday {
        text-align: center;
        color: #687B74;
        font-size: 10px;
        font-weight: 600;
        padding-bottom: 8px;
    }

    .calendar-day {
        min-height: 44px;
        border-radius: 8px;
        background: #E7F0EB;
        color: #172E29;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
    }

    .calendar-day small {
        font-size: 8px;
        color: #176454;
        font-weight: 500;
    }

    .calendar-day.off {
        background: #F6F8F7;
        color: #687B74;
    }

    .calendar-day.off small {
        color: #687B74;
    }

    .calendar-day.selected {
        background: #176454;
        color: #FFFFFF;
        font-weight: 600;
    }

    .calendar-day.selected small {
        color: #FFFFFF;
    }

    .calendar-day.empty {
        background: transparent;
    }

    .calendar-day:not(.empty):hover {
        outline: 1px solid #176454;
    }

    .calendar-note {
        color: #687B74;
        font-size: 10px;
        margin: 14px 0 0;
    }

    .selected-day-heading {
        margin-bottom: 14px;
    }

    .selected-day-heading h2 {
        font-size: 17px;
        font-weight: 600;
        margin: 0 0 5px;
    }

    .selected-day-heading p {
        color: #687B74;
        font-size: 12px;
        margin: 0;
    }

    .shift-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .shift-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .shift-card-header h3 {
        font-size: 14px;
        font-weight: 600;
        margin: 0;
    }

    .employee-count {
        color: #687B74;
        font-size: 11px;
        white-space: nowrap;
    }

    .time-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .field-group label {
        display: block;
        color: #687B74;
        font-size: 10px;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .field-group input,
    .field-group select {
        width: 100%;
        height: 36px;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        padding: 0 10px;
        color: #172E29;
        background: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
    }

    .assignments {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }

    .assignment-tag {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #E7F0EB;
        color: #176454;
        border-radius: 13px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 500;
    }

    .assignment-tag button {
        border: none;
        background: transparent;
        color: #176454;
        font-size: 14px;
        cursor: pointer;
        padding: 0;
    }

    .assignment-tag form {
        display: inline-flex;
        margin: 0;
    }

    .shift-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .text-btn {
        border: none;
        background: transparent;
        color: #176454;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        padding: 8px 4px;
        cursor: pointer;
        text-decoration: none;
    }

    .text-btn.remove {
        color: #687B74;
        font-weight: 500;
    }

    .compact-shift {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        background: #FFFFFF;
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 12px;
    }

    .compact-shift h3 {
        font-size: 13px;
        font-weight: 600;
        margin: 0 0 5px;
    }

    .compact-shift p {
        color: #687B74;
        font-size: 12px;
        margin: 0;
    }

    .compact-actions {
        display: flex;
        gap: 8px;
    }

    .compact-actions button {
        border: none;
        background: transparent;
        border-radius: 6px;
        padding: 7px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        color: #176454;
    }

    .compact-actions button:last-child {
        color: #687B74;
        font-weight: 500;
    }

    .add-shift {
        margin: 4px 0 18px;
    }

    .bottom-actions {
        display: flex;
        gap: 12px;
    }

    .save-btn,
    .publish-btn {
        height: 49px;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .save-btn {
        flex: 1;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        color: #172E29;
    }

    .publish-btn {
        flex: 1.6;
        background: #176454;
        border: 1px solid #176454;
        color: #FFFFFF;
    }

    .empty-state {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 30px 18px;
        text-align: center;
        color: #687B74;
        font-size: 12px;
        margin-bottom: 16px;
    }

    .empty-state strong {
        display: block;
        color: #172E29;
        font-size: 14px;
        margin-bottom: 7px;
    }

    .history-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .history-info h3 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 6px;
    }

    .history-info p {
        color: #687B74;
        font-size: 11px;
        margin: 0;
    }

    .history-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .history-status {
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
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
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }

    .alert {
        padding: 12px 15px;
        margin-bottom: 15px;
        border-radius: 9px;
        font-size: 12px;
    }

    .alert-success {
        background: #E7F0EB;
        color: #176454;
    }

    .alert-error {
        background: #FCE8E8;
        color: #B42318;
    }

    .alert-validation {
        background: #FCE8E8;
        color: #B42318;
        border-radius: 9px;
        padding: 12px 15px;
        font-size: 12px;
        margin-bottom: 15px;
    }

    @media (max-width: 480px) {
        .schedule-page {
            width: calc(100% - 32px);
            margin-top: 24px;
        }

        .calendar-card {
            padding: 12px;
        }

        .calendar-grid {
            gap: 4px;
        }

        .calendar-day {
            min-height: 38px;
        }

        .time-fields {
            gap: 10px;
        }

        .bottom-actions {
            gap: 8px;
        }

        .save-btn,
        .publish-btn {
            font-size: 12px;
        }

        .history-card {
            align-items: flex-start;
            flex-direction: column;
        }

        .history-meta {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>

<div class="schedule-page">

    <div class="eyebrow">TEAM SCHEDULING</div>

    <h1 class="page-title">Schedule management</h1>

    <p class="page-subtitle">
        Plan your team’s month, one shift at a time.
    </p>

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

</div>

@endsection

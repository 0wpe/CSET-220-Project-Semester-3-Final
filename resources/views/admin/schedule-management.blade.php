
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

    .draft-status {
        background: #E7F0EB;
        color: #176454;
        border-radius: 13px;
        padding: 6px 14px;
        font-size: 10px;
        font-weight: 500;
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
        cursor: pointer;
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
        cursor: default;
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
    }
</style>

<div class="schedule-page">

    <div class="eyebrow">TEAM SCHEDULING</div>

    <h1 class="page-title">Schedule management</h1>

    <p class="page-subtitle">
        Plan your team’s month, one shift at a time.
    </p>

    <div class="schedule-actions">
        <button class="new-schedule-btn" type="button">
            + New monthly schedule
        </button>

        <span class="draft-status">Draft · Nov 2026</span>
    </div>

    <!-- Monthly Calendar -->
    <div class="calendar-card">

        <div class="calendar-header">
            <button class="month-btn" type="button" aria-label="Previous month">
                ‹
            </button>

            <h2>November 2026</h2>

            <button class="month-btn" type="button" aria-label="Next month">
                ›
            </button>
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
                $scheduleDays = [
                    1 => 'Off',
                    2 => '5 staff',
                    3 => '5 staff',
                    4 => '5 staff',
                    5 => '5 staff',
                    6 => '5 staff',
                    7 => 'Off',
                    8 => 'Off',
                    9 => '5 staff',
                    10 => '5 staff',
                    11 => '5 staff',
                    12 => '3 staff',
                    13 => '4 staff',
                    14 => 'Off',
                    15 => 'Off',
                    16 => '4 staff',
                    17 => '5 staff',
                    18 => '5 staff',
                    19 => '5 staff',
                    20 => '4 staff',
                    21 => 'Off',
                    22 => 'Off',
                    23 => '5 staff',
                    24 => '5 staff',
                    25 => '5 staff',
                    26 => '5 staff',
                    27 => '5 staff',
                    28 => 'Off',
                    29 => 'Off',
                    30 => '5 staff',
                ];
            @endphp

            @for ($i = 0; $i < 6; $i++)
                <div class="calendar-day empty"></div>
            @endfor

            @foreach ($scheduleDays as $day => $staff)
                <div class="calendar-day {{ $staff === 'Off' ? 'off' : '' }} {{ $day === 12 ? 'selected' : '' }}">
                    <span>{{ $day }}</span>
                    <small>{{ $staff }}</small>
                </div>
            @endforeach

        </div>

        <p class="calendar-note">
            Select a day to view or edit its assignments.
        </p>

    </div>

    <!-- Selected Day -->
    <div class="selected-day-heading">
        <h2>Thursday, November 12</h2>
        <p>3 employees scheduled · 2 shifts</p>
    </div>

    <!-- Day Shift Editor -->
    <div class="shift-card">

        <div class="shift-card-header">
            <h3>Day shift · Editing</h3>
            <span class="employee-count">2 employees</span>
        </div>

        <div class="time-fields">

            <div class="field-group">
                <label for="day-start">Shift start</label>
                <input type="time" id="day-start" value="09:00">
            </div>

            <div class="field-group">
                <label for="day-end">Shift end</label>
                <input type="time" id="day-end" value="17:00">
            </div>

        </div>

        <div class="assignments">

            <div class="assignment-tag">
                Maya Thompson
                <button type="button" aria-label="Remove Maya">×</button>
            </div>

            <div class="assignment-tag">
                Alex Morgan
                <button type="button" aria-label="Remove Alex">×</button>
            </div>

        </div>

        <div class="shift-actions">
            <button class="text-btn" type="button">
                + Add employees
            </button>

            <button class="text-btn remove" type="button">
                Remove shift
            </button>
        </div>

    </div>

    <!-- Late Shift -->
    <div class="compact-shift">

        <div>
            <h3>Late shift · 13:00–21:00</h3>
            <p>Noah Williams</p>
        </div>

        <div class="compact-actions">
            <button type="button">Edit</button>
            <button type="button">Remove</button>
        </div>

    </div>

    <div class="add-shift">
        <button class="text-btn" type="button">
            + Add another shift
        </button>
    </div>

    <!-- Schedule Actions -->
    <div class="bottom-actions">

        <button class="save-btn" type="button">
            Save draft
        </button>

        <button class="publish-btn" type="button">
            Publish schedule
        </button>

    </div>

</div>
@endsection

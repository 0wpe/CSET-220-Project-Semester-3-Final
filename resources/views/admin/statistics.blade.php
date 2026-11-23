
@extends('layouts.app')

@section('title', 'Workforce Statistics')

@section('content')
@include('partials.navbar-admin', ['activePage' => 'statistics'])

<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: #F6F8F7;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .statistics-page {
        width: min(100% - 32px, 900px);
        margin: 18px auto 40px;
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

    .month-selector {
        width: 100%;
        height: 49px;
        padding: 0 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        color: #172E29;
        font-family: inherit;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .month-selector select {
        width: 100%;
        height: 100%;
        border: none;
        outline: none;
        background: transparent;
        color: #172E29;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 14px;
    }

    .summary-card {
        min-height: 79px;
        padding: 12px 16px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .summary-card.highlight {
        background: #176454;
    }

    .summary-label {
        display: block;
        color: #687B74;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .summary-card.highlight .summary-label {
        color: #D4E7DF;
    }

    .summary-value {
        font-size: 29px;
        font-weight: 600;
        line-height: 1.45;
    }

    .summary-card.highlight .summary-value {
        color: #FFFFFF;
    }

    .attendance-totals {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        background: #E7F0EB;
        border-radius: 12px;
        padding: 11px 16px;
        margin-bottom: 16px;
    }

    .total-label {
        display: block;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .total-value {
        color: #176454;
        font-size: 14px;
        font-weight: 600;
    }

    .chart-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .chart-heading {
        font-size: 17px;
        font-weight: 600;
        margin: 0 0 5px;
    }

    .chart-subtitle {
        color: #687B74;
        font-size: 11px;
        margin: 0 0 18px;
    }

    .attendance-chart {
        position: relative;
        height: 150px;
        margin: 0 0 8px;
        padding-left: 36px;
    }

    .chart-plot {
        position: relative;
        height: 120px;
        border-bottom: 1px solid #DFE7E2;
        background: repeating-linear-gradient(
            to bottom,
            transparent 0,
            transparent 39px,
            #DFE7E2 40px,
            transparent 41px
        );
    }

    .chart-y-labels {
        position: absolute;
        left: 0;
        top: -6px;
        height: 125px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #687B74;
        font-size: 10px;
    }

    .chart-plot svg {
        width: 100%;
        height: 100%;
        overflow: visible;
    }

    .chart-x-labels {
        display: flex;
        justify-content: space-between;
        color: #687B74;
        font-size: 10px;
        margin-top: 8px;
    }

    .chart-footer {
        color: #687B74;
        font-size: 10px;
        margin-top: 12px;
    }

    .department-content {
        display: grid;
        grid-template-columns: 145px 1fr;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
    }

    .pie-chart {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: {{ $departmentGradient }};
        position: relative;
        margin: auto;
    }

    .pie-chart::after {
        content: "";
        position: absolute;
        width: 55px;
        height: 55px;
        background: #FFFFFF;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .department-legend {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .legend-item {
        display: grid;
        grid-template-columns: 10px 1fr auto;
        align-items: center;
        gap: 9px;
        font-size: 11px;
    }

    .legend-swatch {
        width: 10px;
        height: 10px;
        border-radius: 3px;
    }

    .legend-name {
        color: #172E29;
        font-weight: 500;
    }

    .legend-percent {
        color: #176454;
        font-weight: 600;
        white-space: nowrap;
    }

    .legend-count {
        color: #687B74;
        font-size: 10px;
        font-weight: 400;
        margin-left: 4px;
    }

    .empty-chart {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 120px;
        color: #687B74;
        font-size: 12px;
        text-align: center;
    }

    .page-footer {
        color: #687B74;
        font-size: 11px;
        margin-top: 18px;
    }

    @media (max-width: 480px) {
        .statistics-page {
            width: calc(100% - 32px);
        }

        .department-content {
            grid-template-columns: 125px 1fr;
            gap: 8px;
        }

        .pie-chart {
            width: 112px;
            height: 112px;
        }

        .pie-chart::after {
            width: 46px;
            height: 46px;
        }

        .department-legend {
            gap: 13px;
        }
    }

    @media (max-width: 350px) {
        .department-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .department-legend {
            width: 100%;
        }
    }
</style>

<div class="statistics-page">

    <!-- Page Heading -->
    <div class="eyebrow">WORKFORCE INSIGHTS</div>

    <h1 class="page-title">Statistics</h1>

    <p class="page-subtitle">
        A clearer picture of your team.
    </p>

    <!-- Month Selector -->
    <form
        action="{{ url('/admin/statistics') }}"
        method="GET"
        class="month-selector"
    >
        <select
            name="month"
            aria-label="Select statistics month"
            onchange="this.form.submit()"
        >
            @for($i = 0; $i < 12; $i++)

                @php
                    $optionMonth = now()->startOfMonth()->subMonths($i);
                    $optionValue = $optionMonth->format('Y-m');
                @endphp

                <option
                    value="{{ $optionValue }}"
                    {{ $month === $optionValue ? 'selected' : '' }}
                >
                    {{ $optionMonth->format('F Y') }}
                </option>

            @endfor
        </select>
    </form>

    <!-- Workforce Summary -->
    <div class="summary-cards">

        <div class="summary-card">
            <span class="summary-label">Employees</span>

            <div class="summary-value">
                {{ $employeeCount }}
            </div>
        </div>

        <div class="summary-card highlight">
            <span class="summary-label">Avg. attendance</span>

            <div class="summary-value">
                {{ number_format($averageAttendance, 1) }}%
            </div>
        </div>

    </div>

    <!-- Attendance Totals -->
    <div class="attendance-totals">

        <div>
            <span class="total-label">Present</span>

            <span class="total-value">
                {{ number_format($presentEmployeeDays) }} employee-days
            </span>
        </div>

        <div>
            <span class="total-label">Absent</span>

            <span class="total-value">
                {{ number_format($absentEmployeeDays) }} employee-days
            </span>
        </div>

    </div>

    <!-- Attendance Trends -->
    <div class="chart-card">

        <h2 class="chart-heading">Attendance trends</h2>

        <p class="chart-subtitle">
            Daily attendance rate · {{ $monthDate->format('F Y') }}
        </p>

        @if($attendanceTrends->isNotEmpty())

            @php
                $chartPoints = [];
                $chartLabels = [];
                $chartAreaPoints = [];

                $trendCount = $attendanceTrends->count();

                foreach ($attendanceTrends as $index => $trend) {

                    $x = $trendCount > 1
                        ? ($index / ($trendCount - 1)) * 280
                        : 140;

                    $rate = max(60, min(100, $trend['attendance_rate']));

                    $y = ((100 - $rate) / 40) * 120;

                    $chartPoints[] = round($x, 2) . ',' . round($y, 2);

                    $chartAreaPoints[] = round($x, 2) . ',' . round($y, 2);

                    $chartLabels[] = [
                        'day' => $trend['day'],
                        'index' => $index,
                    ];
                }

                $polylinePoints = implode(' ', $chartPoints);

                $areaPath = 'M' . implode(' L', $chartAreaPoints)
                    . ' L280,120 L0,120 Z';

                $labelInterval = max(1, ceil($trendCount / 7));

                $visibleLabels = collect($chartLabels)
                    ->filter(function ($label) use ($labelInterval, $trendCount) {
                        return $label['index'] % $labelInterval === 0
                            || $label['index'] === $trendCount - 1;
                    });
            @endphp

            <div class="attendance-chart">

                <div class="chart-y-labels">
                    <span>100%</span>
                    <span>80%</span>
                    <span>60%</span>
                </div>

                <div class="chart-plot">

                    <svg
                        viewBox="0 0 280 120"
                        preserveAspectRatio="none"
                        role="img"
                        aria-label="Daily attendance trends for {{ $monthDate->format('F Y') }}"
                    >

                        <path
                            d="{{ $areaPath }}"
                            fill="#E7F0EB"
                        />

                        <polyline
                            points="{{ $polylinePoints }}"
                            fill="none"
                            stroke="#176454"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                        @foreach($chartPoints as $point)

                            @php
                                [$circleX, $circleY] = explode(',', $point);
                            @endphp

                            <circle
                                cx="{{ $circleX }}"
                                cy="{{ $circleY }}"
                                r="4"
                                fill="#176454"
                                stroke="#FFFFFF"
                                stroke-width="1.5"
                            />

                        @endforeach

                    </svg>

                </div>

                <div class="chart-x-labels">

                    @foreach($visibleLabels as $label)

                        <span>{{ $label['day'] }}</span>

                    @endforeach

                </div>

            </div>

        @else

            <div class="empty-chart">
                No attendance records found for this month.
            </div>

        @endif

        <div class="chart-footer">
            {{ $monthDate->format('F Y') }}
        </div>

    </div>

    <!-- Department Distribution -->
    <div class="chart-card">

        <h2 class="chart-heading">Department distribution</h2>

        <p class="chart-subtitle">
            {{ $employeeCount }} employees · {{ $departmentCount }} departments
        </p>

        <div class="department-content">

            <div
                class="pie-chart"
                role="img"
                aria-label="Department distribution across {{ $departmentCount }} departments"
            >
            </div>

            <div class="department-legend">

                @forelse($departmentDistribution as $department)

                    <div class="legend-item">

                        <span
                            class="legend-swatch"
                            style="background: {{ $department['color'] }};"
                        ></span>

                        <span class="legend-name">
                            {{ $department['name'] }}

                            <span class="legend-count">
                                ({{ $department['count'] }})
                            </span>
                        </span>

                        <span class="legend-percent">
                            {{ number_format($department['percentage'], 1) }}%
                        </span>

                    </div>

                @empty

                    <div class="empty-chart">
                        No departments found.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

    <div class="page-footer">
        {{ $monthDate->format('M 1–') }}{{ $monthDate->daysInMonth }}
        · {{ number_format($scheduledEmployeeDays) }} scheduled employee-days
    </div>

</div>

@endsection

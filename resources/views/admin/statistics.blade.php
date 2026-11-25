@extends('layouts.app')

@section('title', 'Workforce Statistics')

@section('content')
@include('partials.navbar-admin', ['activePage' => 'statistics'])

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

    .statistics-page {
        width: 100%;
        padding: clamp(22px, 3vw, 44px);
    }

    .statistics-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.1px;
        margin-bottom: 8px;
    }

    .page-title {
        font-size: clamp(28px, 2.2vw, 36px);
        font-weight: 700;
        line-height: 1.2;
        margin: 0;
        letter-spacing: -1px;
    }

    .page-subtitle {
        color: #71817C;
        font-size: 14px;
        margin: 8px 0 0;
    }

    .month-selector {
        width: 220px;
        min-width: 180px;
        height: 48px;
        padding: 0 15px;
        display: flex;
        align-items: center;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(23, 46, 41, .03);
    }

    .month-selector select {
        width: 100%;
        height: 100%;
        border: none;
        outline: none;
        background: transparent;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    /* Summary cards */

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .summary-card {
        min-width: 0;
        min-height: 132px;
        padding: 22px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(23, 46, 41, .035);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .summary-card.highlight {
        background: #176454;
        border-color: #176454;
    }

    .summary-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .summary-label {
        color: #71817C;
        font-size: 12px;
        font-weight: 600;
    }

    .summary-card.highlight .summary-label {
        color: #D4E7DF;
    }

    .summary-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #E7F0EB;
        color: #176454;
        font-size: 15px;
        font-weight: 700;
    }

    .summary-card.highlight .summary-icon {
        background: rgba(255, 255, 255, .15);
        color: #FFFFFF;
    }

    .summary-value {
        color: #172E29;
        font-size: clamp(28px, 2.4vw, 36px);
        font-weight: 700;
        line-height: 1;
        letter-spacing: -1px;
    }

    .summary-card.highlight .summary-value {
        color: #FFFFFF;
    }

    .summary-description {
        color: #8A9993;
        font-size: 11px;
        margin-top: 8px;
    }

    .summary-card.highlight .summary-description {
        color: #D4E7DF;
    }

    /* Chart layout */

    .statistics-chart-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(300px, 1fr);
        gap: 20px;
        align-items: stretch;
    }

    .chart-card {
        min-width: 0;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 18px;
        padding: clamp(20px, 2vw, 28px);
        box-shadow: 0 4px 16px rgba(23, 46, 41, .035);
    }

    .chart-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .chart-heading {
        color: #172E29;
        font-size: 17px;
        font-weight: 700;
        margin: 0;
    }

    .chart-subtitle {
        color: #8A9993;
        font-size: 12px;
        margin: 6px 0 0;
    }

    .chart-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 10px;
        border-radius: 20px;
        background: #E7F0EB;
        color: #176454;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .chart-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #176454;
    }

    /* Attendance graph */

    .attendance-chart {
        width: 100%;
        position: relative;
        padding-left: 42px;
    }

    .chart-y-labels {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 30px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #8A9993;
        font-size: 10px;
        font-weight: 500;
    }

    .chart-plot {
        position: relative;
        width: 100%;
        height: clamp(210px, 20vw, 285px);
        border-bottom: 1px solid #E3EAE7;
        background: repeating-linear-gradient(
            to bottom,
            transparent 0,
            transparent calc(25% - 1px),
            #EDF1EF calc(25% - 1px),
            #EDF1EF 25%
        );
    }

    .chart-plot svg {
        display: block;
        width: 100%;
        height: 100%;
        overflow: visible;
    }

    .chart-x-labels {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        color: #8A9993;
        font-size: 10px;
        margin-top: 12px;
    }

    .chart-x-labels span {
        text-align: center;
    }

    .chart-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-top: 1px solid #EDF1EF;
        margin-top: 22px;
        padding-top: 16px;
        color: #8A9993;
        font-size: 11px;
    }

    .chart-legend {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #687B74;
        font-weight: 600;
    }

    .chart-legend-line {
        width: 18px;
        height: 3px;
        border-radius: 5px;
        background: #176454;
    }

    .empty-chart {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 230px;
        padding: 24px;
        color: #8A9993;
        font-size: 13px;
        text-align: center;
        background: #F8FAF9;
        border-radius: 12px;
    }

    /* Department distribution */

    .department-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 26px;
        padding: 8px 0 4px;
    }

    .pie-chart {
        width: clamp(150px, 14vw, 190px);
        height: clamp(150px, 14vw, 190px);
        flex-shrink: 0;
        border-radius: 50%;
        background: {{ $departmentGradient }};
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pie-chart::after {
        content: "";
        position: absolute;
        width: 62%;
        height: 62%;
        background: #FFFFFF;
        border-radius: 50%;
    }

    .pie-center {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .pie-center-value {
        color: #172E29;
        font-size: 28px;
        font-weight: 700;
        line-height: 1.1;
    }

    .pie-center-label {
        color: #8A9993;
        font-size: 10px;
        font-weight: 600;
        margin-top: 4px;
    }

    .department-legend {
        display: flex;
        flex-direction: column;
        gap: 15px;
        width: 100%;
    }

    .legend-item {
        display: grid;
        grid-template-columns: 11px minmax(0, 1fr) auto;
        align-items: center;
        gap: 10px;
        font-size: 12px;
    }

    .legend-swatch {
        width: 10px;
        height: 10px;
        border-radius: 3px;
    }

    .legend-name {
        color: #344B43;
        font-weight: 600;
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .legend-count {
        color: #8A9993;
        font-size: 11px;
        font-weight: 400;
        margin-left: 4px;
    }

    .legend-percent {
        color: #176454;
        font-weight: 700;
        white-space: nowrap;
    }

    .department-empty {
        color: #8A9993;
        font-size: 12px;
        text-align: center;
        padding: 15px 0;
    }

    /* Footer */

    .page-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        color: #8A9993;
        font-size: 11px;
        margin-top: 22px;
    }

    /* Responsive layouts */

    @media (max-width: 1100px) {
        .summary-cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .statistics-chart-grid {
            grid-template-columns: minmax(0, 1.4fr) minmax(260px, 1fr);
        }

        .department-content {
            gap: 20px;
        }
    }

    @media (max-width: 850px) {
        .statistics-chart-grid {
            grid-template-columns: 1fr;
        }

        .department-content {
            display: grid;
            grid-template-columns: 180px minmax(0, 1fr);
            align-items: center;
            gap: 28px;
        }

        .department-legend {
            gap: 17px;
        }
    }

    @media (max-width: 600px) {
        .statistics-page {
            padding: 22px 16px 30px;
        }

        .statistics-header {
            align-items: stretch;
            flex-direction: column;
            gap: 18px;
            margin-bottom: 22px;
        }

        .month-selector {
            width: 100%;
        }

        .summary-cards {
            gap: 12px;
            margin-bottom: 16px;
        }

        .summary-card {
            min-height: 112px;
            padding: 16px;
            border-radius: 14px;
        }

        .summary-value {
            font-size: 29px;
        }

        .summary-icon {
            width: 30px;
            height: 30px;
        }

        .chart-card {
            padding: 18px;
            border-radius: 15px;
        }

        .chart-card-header {
            margin-bottom: 20px;
        }

        .chart-heading {
            font-size: 16px;
        }

        .chart-badge {
            font-size: 9px;
            padding: 6px 8px;
        }

        .chart-plot {
            height: 210px;
        }

        .department-content {
            grid-template-columns: 1fr;
            justify-items: center;
            gap: 24px;
        }

        .department-legend {
            gap: 14px;
        }

        .page-footer {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 380px) {
        .summary-card {
            padding: 13px;
        }

        .summary-label {
            font-size: 11px;
        }

        .summary-value {
            font-size: 25px;
        }

        .chart-card {
            padding: 15px;
        }

        .chart-badge {
            display: none;
        }

        .attendance-chart {
            padding-left: 34px;
        }

        .chart-x-labels {
            font-size: 9px;
        }
    }
</style>

<main class="statistics-page">

    <!-- Page Heading -->
    <div class="statistics-header">

        <div>
            <div class="eyebrow">WORKFORCE INSIGHTS</div>

            <h1 class="page-title">Statistics</h1>

            <p class="page-subtitle">
                A clearer picture of your team.
            </p>
        </div>

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

    </div>

    <!-- Workforce Summary -->
    <div class="summary-cards">

        <div class="summary-card">

            <div class="summary-top">
                <span class="summary-label">Total employees</span>
                <span class="summary-icon">E</span>
            </div>

            <div>
                <div class="summary-value">
                    {{ number_format($employeeCount) }}
                </div>

                <div class="summary-description">
                    Current workforce
                </div>
            </div>

        </div>

        <div class="summary-card highlight">

            <div class="summary-top">
                <span class="summary-label">Average attendance</span>
                <span class="summary-icon">%</span>
            </div>

            <div>
                <div class="summary-value">
                    {{ number_format($averageAttendance, 1) }}%
                </div>

                <div class="summary-description">
                    Average for selected month
                </div>
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-top">
                <span class="summary-label">Present</span>
                <span class="summary-icon">P</span>
            </div>

            <div>
                <div class="summary-value">
                    {{ number_format($presentEmployeeDays) }}
                </div>

                <div class="summary-description">
                    Employee-days recorded
                </div>
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-top">
                <span class="summary-label">Absent</span>
                <span class="summary-icon">A</span>
            </div>

            <div>
                <div class="summary-value">
                    {{ number_format($absentEmployeeDays) }}
                </div>

                <div class="summary-description">
                    Employee-days recorded
                </div>
            </div>

        </div>

    </div>

    <!-- Statistics Charts -->
    <div class="statistics-chart-grid">

        <!-- Attendance Trends -->
        <div class="chart-card">

            <div class="chart-card-header">

                <div>
                    <h2 class="chart-heading">Attendance trends</h2>

                    <p class="chart-subtitle">
                        Daily attendance rate · {{ $monthDate->format('F Y') }}
                    </p>
                </div>

                <div class="chart-badge">
                    <span class="chart-badge-dot"></span>
                    Attendance
                </div>

            </div>

            @if($attendanceTrends->isNotEmpty())

                @php
                    $chartPoints = [];
                    $chartLabels = [];

                    $trendCount = $attendanceTrends->count();

                    foreach ($attendanceTrends as $index => $trend) {

                        $x = $trendCount > 1
                            ? ($index / ($trendCount - 1)) * 1000
                            : 500;

                        $rate = max(60, min(100, $trend['attendance_rate']));

                        $y = ((100 - $rate) / 40) * 220;

                        $chartPoints[] = [
                            'x' => round($x, 2),
                            'y' => round($y, 2),
                            'rate' => $rate,
                        ];

                        $chartLabels[] = [
                            'day' => $trend['day'],
                            'index' => $index,
                        ];
                    }

                    // Create a smoother curved line between attendance points.
                    $smoothPath = '';

                    if (count($chartPoints) > 0) {

                        $smoothPath = 'M ' . $chartPoints[0]['x']
                            . ' ' . $chartPoints[0]['y'];

                        for ($i = 1; $i < count($chartPoints); $i++) {

                            $previous = $chartPoints[$i - 1];
                            $current = $chartPoints[$i];

                            $middleX = ($previous['x'] + $current['x']) / 2;

                            $smoothPath .= ' C '
                                . $middleX . ' ' . $previous['y'] . ', '
                                . $middleX . ' ' . $current['y'] . ', '
                                . $current['x'] . ' ' . $current['y'];
                        }
                    }

                    $areaPath = $smoothPath
                        . ' L 1000 220 L 0 220 Z';

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
                            viewBox="0 0 1000 220"
                            preserveAspectRatio="none"
                            role="img"
                            aria-label="Daily attendance trends for {{ $monthDate->format('F Y') }}"
                        >

                            <defs>
                                <linearGradient
                                    id="attendanceAreaGradient"
                                    x1="0"
                                    y1="0"
                                    x2="0"
                                    y2="1"
                                >
                                    <stop
                                        offset="0%"
                                        stop-color="#176454"
                                        stop-opacity=".22"
                                    />

                                    <stop
                                        offset="100%"
                                        stop-color="#176454"
                                        stop-opacity=".01"
                                    />
                                </linearGradient>
                            </defs>

                            <!-- Shaded area -->
                            <path
                                d="{{ $areaPath }}"
                                fill="url(#attendanceAreaGradient)"
                            />

                            <!-- Smooth attendance line -->
                            <path
                                d="{{ $smoothPath }}"
                                fill="none"
                                stroke="#176454"
                                stroke-width="4"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                vector-effect="non-scaling-stroke"
                            />

                            <!-- Data points -->
                            @foreach($chartPoints as $point)

                                <circle
                                    cx="{{ $point['x'] }}"
                                    cy="{{ $point['y'] }}"
                                    r="6"
                                    fill="#FFFFFF"
                                    stroke="#176454"
                                    stroke-width="3"
                                    vector-effect="non-scaling-stroke"
                                >
                                    <title>
                                        {{ $point['rate'] }}% attendance
                                    </title>
                                </circle>

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

                <span>
                    {{ $monthDate->format('F Y') }}
                </span>

                <span class="chart-legend">
                    <span class="chart-legend-line"></span>
                    Attendance rate
                </span>

            </div>

        </div>

        <!-- Department Distribution -->
        <div class="chart-card">

            <div class="chart-card-header">

                <div>
                    <h2 class="chart-heading">Department distribution</h2>

                    <p class="chart-subtitle">
                        Workforce by department
                    </p>
                </div>

            </div>

            <div class="department-content">

                <div
                    class="pie-chart"
                    role="img"
                    aria-label="Department distribution across {{ $departmentCount }} departments"
                >
                    <div class="pie-center">
                        <span class="pie-center-value">
                            {{ $departmentCount }}
                        </span>

                        <span class="pie-center-label">
                            Departments
                        </span>
                    </div>
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

                        <div class="department-empty">
                            No departments found.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

    <!-- Page Footer -->
    <div class="page-footer">

        <span>
            {{ $monthDate->format('M 1–') }}{{ $monthDate->daysInMonth }}
        </span>

        <span>
            {{ number_format($scheduledEmployeeDays) }} scheduled employee-days
        </span>

    </div>

</main>

@endsection
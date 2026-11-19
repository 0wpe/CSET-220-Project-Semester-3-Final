<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    // Calculate payable hours for an attendance record
    public function calculateHours($clockIn, $clockOut, $breakMinutes = 0)
    {
        if (!$clockIn || !$clockOut) {
            return 0;
        }

        $start = Carbon::parse($clockIn);
        $end = Carbon::parse($clockOut);

        $minutesWorked = $start->diffInMinutes($end);
        $minutesWorked -= $breakMinutes;

        return max(0, $minutesWorked / 60);
    }

    // Retrieve complete workweeks overlapping the payroll month
    public function getAttendanceForPayroll($employeeId, $month)
    {
        $monthStart = Carbon::parse($month)->startOfMonth();
        $monthEnd = Carbon::parse($month)->endOfMonth();

        $weekStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        return Attendance::where('employee_id', $employeeId)
            ->whereBetween('clock_in', [
                $weekStart->startOfDay(),
                $weekEnd->endOfDay()
            ])
            ->whereNotNull('clock_out')
            ->orderBy('clock_in')
            ->get();
    }

    // Calculate regular and overtime hours for a payroll month
    public function calculateMonthlyHours($attendanceRecords, $month)
    {
        $monthStart = Carbon::parse($month)->startOfMonth();
        $monthEnd = Carbon::parse($month)->endOfMonth();

        $weeklyHours = [];

        foreach ($attendanceRecords as $record) {
            $weekStart = Carbon::parse($record->clock_in)
                ->startOfWeek(Carbon::MONDAY)
                ->toDateString();

            $hours = $this->calculateHours(
                $record->clock_in,
                $record->clock_out,
                $record->break_minutes
            );

            if (!isset($weeklyHours[$weekStart])) {
                $weeklyHours[$weekStart] = [
                    'total' => 0,
                    'regular' => 0,
                    'overtime' => 0,
                ];
            }

            $previousHours = $weeklyHours[$weekStart]['total'];

            $overtime = max(0, $previousHours + $hours - 40)
                - max(0, $previousHours - 40);

            $regular = $hours - $overtime;

            $weeklyHours[$weekStart]['total'] += $hours;

            $recordDate = Carbon::parse($record->clock_in);

            if ($recordDate->between($monthStart, $monthEnd)) {
                $weeklyHours[$weekStart]['regular'] += $regular;
                $weeklyHours[$weekStart]['overtime'] += $overtime;
            }
        }

        $regularHours = 0;
        $overtimeHours = 0;

        foreach ($weeklyHours as $week) {
            $regularHours += $week['regular'];
            $overtimeHours += $week['overtime'];
        }

        return [
            'regular_hours' => round($regularHours, 2),
            'overtime_hours' => round($overtimeHours, 2),
        ];
    }

    // Calculate an employee's monthly pay
    public function calculateEmployeePay($employeeId, $month)
    {
        $employee = Employee::with('user')->findOrFail($employeeId);

        $regularHours = 0;
        $overtimeHours = 0;
        $regularPay = 0;
        $overtimePay = 0;

        if ($employee->pay_type === 'Hourly') {
            if ($employee->hourly_rate === null) {
                abort(422, 'Employee hourly rate is missing.');
            }

            $attendance = $this->getAttendanceForPayroll(
                $employeeId,
                $month
            );

            $hours = $this->calculateMonthlyHours($attendance, $month);

            $regularHours = $hours['regular_hours'];
            $overtimeHours = $hours['overtime_hours'];

            $regularPay = $regularHours * $employee->hourly_rate;

            $overtimePay = $overtimeHours
                * $employee->hourly_rate
                * 1.5;

            $grossPay = $regularPay + $overtimePay;
        } else {
            if ($employee->base_salary === null) {
                abort(422, 'Employee base salary is missing.');
            }

            $grossPay = $employee->base_salary / 12;
        }

        // Estimated deductions (20%)
        $deductions = $grossPay * 0.20;

        $netPay = $grossPay - $deductions;

        return [
            'employee_id' => $employee->employee_id,
            'employee_name' => $employee->user->first_name . ' ' . $employee->user->last_name,
            'pay_type' => $employee->pay_type,
            'payroll_month' => Carbon::parse($month)->startOfMonth()->toDateString(),
            'regular_hours' => round($regularHours, 2),
            'overtime_hours' => round($overtimeHours, 2),
            'regular_pay' => round($regularPay, 2),
            'overtime_pay' => round($overtimePay, 2),
            'gross_pay' => round($grossPay, 2),
            'deductions' => round($deductions, 2),
            'net_pay' => round($netPay, 2),
        ];
    }

    // Calculate and immediately process an employee's payroll
    public function processEmployeePayroll($employeeId, $month)
    {
        $pay = $this->calculateEmployeePay($employeeId, $month);

        $payroll = Payroll::updateOrCreate(
            [
                'employee_id' => $pay['employee_id'],
                'payroll_month' => $pay['payroll_month'],
            ],
            [
                'total_salary' => $pay['gross_pay'],
                'regular_hours' => $pay['regular_hours'],
                'overtime_hours' => $pay['overtime_hours'],
                'gross_pay' => $pay['gross_pay'],
                'deductions' => $pay['deductions'],
                'net_pay' => $pay['net_pay'],
                'is_processed' => 1,
            ]
        );

        return response()->json([
            'message' => 'Payroll processed successfully.',
            'payroll' => $payroll,
        ]);
    }

    // Retrieve an employee's processed payslips
    public function employeePayslips($employeeId)
    {
        return Payroll::where('employee_id', $employeeId)
            ->where('is_processed', true)
            ->orderBy('payroll_month', 'desc')
            ->get();
    }

    // Download an employee's processed payslip as a PDF
    public function downloadPayslip($payrollId)
    {
        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $payroll = Payroll::where('payroll_id', $payrollId)
            ->where('employee_id', $employee->employee_id)
            ->where('is_processed', true)
            ->firstOrFail();

        $pdf = Pdf::loadView('employee.payslip-pdf', compact(
            'employee',
            'payroll'
        ))->setPaper('a4', 'portrait');

        $month = Carbon::parse($payroll->payroll_month)->format('Y-m');

        return $pdf->download("payslip-{$month}.pdf");
    }
}

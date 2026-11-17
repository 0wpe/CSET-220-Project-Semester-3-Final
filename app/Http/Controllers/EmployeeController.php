<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        /** @var User $user */
        $employeeRecord = $user->employee()->with('department')->first();

        return view('employee.dashboard', compact('user', 'employeeRecord'));
    }

    public function profile()
    {
        $user = Auth::user();
        /** @var User $user */
        $employeeRecord = $user->employee()->with(['department', 'payChangeHistory'])->first();

        return view('employee.profile', compact('user', 'employeeRecord'));
    }

    public function timeEntry()
    {
        $employee = Auth::user()->employee;
        $attendanceLogs = $employee ? $employee->attendance : collect();

        return view('employee.time-entry', compact('attendanceLogs'));
    }

    public function leaveRequests()
    {
        $employee = Auth::user()->employee;
        $leaveRequests = $employee ? $employee->leaveRequests : collect();

        return view('employee.leave-requests', compact('leaveRequests'));
    }

    public function mySchedule()
    {
        $employee = Auth::user()->employee;
        $shifts = $employee ? $employee->shiftAssignments : collect();

        return view('employee.my-schedule', compact('shifts'));
    }

    public function payslips()
    {
        $employee = Auth::user()->employee;
        $payslips = $employee ? $employee->payroll : collect();

        return view('employee.payslips', compact('payslips'));
    }
}

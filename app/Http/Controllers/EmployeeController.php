<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        return view('employee.dashboard');
    }

    public function profile()
    {
        return view('employee.profile');
    }

    public function timeEntry()
    {
        return view('employee.time-entry');
    }

    public function leaveRequests()
    {
        return view('employee.leave-requests');
    }

    public function mySchedule()
    {
        return view('employee.my-schedule');
    }

    public function payslips()
    {
        return view('employee.payslips');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function employees()
    {
        return view('admin.add-edit-employee');
    }

    public function profileView()
    {
        return view('admin.profile-view');
    }

    public function attendance()
    {
        return view('admin.view-attendance');
    }

    public function payroll()
    {
        return view('admin.payroll-management');
    }

    public function scheduleManagement()
    {
        return view('admin.schedule-management');
    }

    public function statistics()
    {
        return view('admin.statistics');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Schedule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $adminRecord = $user->admin; // Utilizing User -> Admin relationship[cite: 2]

        // Example: Fetch schedules created by this admin using the model relationship[cite: 3]
        $schedules = $adminRecord ? $adminRecord->schedules : collect();

        return view('admin.dashboard', compact('user', 'adminRecord', 'schedules'));
    }

    public function employees()
    {
        // Eager load department and user data for employees
        $employees = Employee::with(['user', 'department'])->get();

        return view('admin.add-edit-employee', compact('employees'));
    }

    public function profileView()
    {
        $user = Auth::user();
        return view('admin.profile-view', compact('user'));
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
        $schedules = Schedule::with('admin.user')->get();
        return view('admin.schedule-management', compact('schedules'));
    }

    public function statistics()
    {
        return view('admin.statistics');
    }
}

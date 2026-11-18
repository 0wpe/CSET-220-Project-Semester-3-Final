<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
  
    public function index()
    {
        $employees = Employee::with('user')->get();
        $departments = DB::table('departments')->get();
        return view('admin.add-edit-employee', compact('employees', 'departments'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'base_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'pay_type' => 'required|in:Hourly,Salaried',
        ]);
        $temporaryPassword = Str::random(12);

        DB::transaction(function () use ($validatedData, $temporaryPassword) {
            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => $temporaryPassword,
                'role' => 'employee',
            ]);

            Employee::create([
                'user_id' => $user->user_id,
                'department_id' => $validatedData['department_id'] ?? null,
                'designation' => $validatedData['designation'] ?? null,
                'base_salary' => $validatedData['base_salary'] ?? 0,
                'hourly_rate' => $validatedData['hourly_rate'] ?? 0,
                'pay_type' => $validatedData['pay_type'],
            ]);
        });

        return redirect()->back()
            ->with('success', 'Employee added successfully.')
            ->with('temporaryPassword', $temporaryPassword);
    }
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validatedData = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'base_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'pay_type' => 'required|in:Hourly,Salary',
        ]);

        $employee->update($validatedData);

        return redirect()->back()->with('success', 'Employee updated successfully.');
    }
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->back()->with('success', 'Employee deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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
                'password' => Hash::make($temporaryPassword),
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

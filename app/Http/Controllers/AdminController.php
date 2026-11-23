<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function employees()
{
    $employees = Employee::with(['user', 'department'])->get();

    $departments = Department::all();

    return view('admin.add-edit-employee', compact('employees', 'departments'));
}

    public function profile()
    {
        $user = Auth::user();

        $admin = Admin::with('user')
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        return view('admin.profile-view', compact('admin'));
    }

   public function store(Request $request)
{
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email',
        'department_id' => 'required|exists:departments,department_id',
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
            'department_id' => $validatedData['department_id'],
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
    $employee = Employee::with('user')->findOrFail($id);

    $validatedData = $request->validate([
        'email' => 'required|email|unique:Users,email,' . $employee->user_id . ',user_id',
        'department_id' => 'required|exists:Departments,department_id',
        'designation' => 'nullable|string|max:255',
        'base_salary' => 'nullable|numeric|min:0',
        'hourly_rate' => 'nullable|numeric|min:0',
        'pay_type' => 'required|in:Hourly,Salaried',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    DB::transaction(function () use ($employee, $validatedData) {

    $employee->user->update([
        'email' => $validatedData['email'],
    ]);

    // Update password only if a new one was entered
    if (!empty($validatedData['password'])) {
        $employee->user->update([
            'password' => Hash::make($validatedData['password'])
        ]);
    }

    $employee->update([
        'department_id' => $validatedData['department_id'],
        'designation' => $validatedData['designation'],
        'base_salary' => $validatedData['base_salary'] ?? 0,
        'hourly_rate' => $validatedData['hourly_rate'] ?? 0,
        'pay_type' => $validatedData['pay_type'],
    ]);

});

    return redirect()->back()->with('success', 'Employee updated successfully.');
}

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('Users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'phone_number' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $profilePicture = $user->profile_picture;

        if ($request->hasFile('profile_picture')) {
            if ($profilePicture) {
                Storage::disk('public')->delete($profilePicture);
            }

            $profilePicture = $request->file('profile_picture')
                ->store('profile-pictures', 'public');
        }

        DB::table('Users')
            ->where('user_id', $user->user_id)
            ->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'location' => $validated['location'] ?? null,
                'profile_picture' => $profilePicture,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function destroy($id)
{
    $employee = Employee::with('user')->findOrFail($id);

    DB::transaction(function () use ($employee) {

        DB::table('attendance')
            ->where('employee_id', $employee->employee_id)
            ->delete();

        DB::table('payroll')
            ->where('employee_id', $employee->employee_id)
            ->delete();

        DB::table('paychangehistory')
            ->where('employee_id', $employee->employee_id)
            ->delete();

        DB::table('leaverequest')
            ->where('employee_id', $employee->employee_id)
            ->delete();

        DB::table('shiftassignments')
            ->where('employee_id', $employee->employee_id)
            ->delete();

        $userId = $employee->user_id;

        $employee->delete();

        User::where('user_id', $userId)->delete();
    });

    return redirect()->back()->with('success', 'Employee permanently deleted.');
}

    public function profileView($id)
    {
        $employee = Employee::with(['user', 'department'])
            ->findOrFail($id);

        return view('admin.profile-view', compact('employee'));
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

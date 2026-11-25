<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PayrollController;
use App\Models\Department;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        return view('employee.dashboard');
    }

    public function profile()
    {
        $employee = Employee::with(['user', 'department'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('employee.profile', compact('employee'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:Users,email,' . $user->user_id . ',user_id',
            'phone_number' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $updateData = [
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'] ?? null,
            'location' => $validatedData['location'] ?? null,
        ];

        if ($request->hasFile('profile_picture')) {
            $newPicturePath = $request->file('profile_picture')
                ->store('profile-pictures', 'public');

            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $updateData['profile_picture'] = $newPicturePath;
        }

        DB::table('Users')
            ->where('user_id', $user->user_id)
            ->update($updateData);

        return redirect('/employee/profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function timeEntry()
{
    $employee = Employee::where('user_id', Auth::id())
        ->firstOrFail();

    $attendance = $employee->attendance()
        ->latest('attendance_id')
        ->first();

        $isOnBreak = $attendance && !$attendance->clock_out
    && session()->has('break_started_at_' . $employee->employee_id);
    return view('employee.time-entry', compact('attendance', 'isOnBreak'));
}

public function clockIn()
{
    $employee = Employee::where('user_id', Auth::id())
        ->firstOrFail();

    $existingAttendance = $employee->attendance()
        ->whereNull('clock_out')
        ->exists();

    if ($existingAttendance) {
        return redirect()->back()
            ->with('error', 'You are already clocked in.');
    }

    $employee->attendance()->create([
        'clock_in' => now(),
        'break_minutes' => 0,
    ]);

    return redirect()->back()
        ->with('success', 'You have successfully clocked in.');
}

public function clockOut()
{
    $employee = Employee::where('user_id', Auth::id())
        ->firstOrFail();

    $attendance = $employee->attendance()
        ->whereNull('clock_out')
        ->latest('attendance_id')
        ->first();

    if (!$attendance) {
        return redirect()->back()
            ->with('error', 'You are not currently clocked in.');
    }

    $attendance->update([
        'clock_out' => now(),
    ]);

    return redirect()->back()
        ->with('success', 'You have successfully clocked out.');
}

public function startBreak()
{
    $employee = Employee::where('user_id', Auth::id())
        ->firstOrFail();

    $attendance = $employee->attendance()
        ->whereNull('clock_out')
        ->latest('attendance_id')
        ->first();

    if (!$attendance) {
        return redirect()->back()
            ->with('error', 'You must clock in before starting a break.');
    }

    $sessionKey = 'break_started_at_' . $employee->employee_id;

    if (session()->has($sessionKey)) {
        return redirect()->back()
            ->with('error', 'You are already on break.');
    }

    session()->put($sessionKey, now()->timestamp);

    return redirect()->back()
        ->with('success', 'Your break has started.');
}
    public function leaveRequests()
    {
        return view('employee.leave-requests');
    }

    public function mySchedule()
    {
        return view('employee.my-schedule');
    }

    // Display the logged-in employee's processed payslips
    public function payslips()
    {
        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $payrolls = app(PayrollController::class)
            ->employeePayslips($employee->employee_id);

        return view('employee.payslips', compact('employee', 'payrolls'));
    }

    public function index()
    {
        $employees = Employee::with('user')->get();
        $departments = Department::all();

        return view('admin.add-edit-employee', compact('employees', 'departments'));
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
            'department_id' => 'required|exists:departments,department_id',
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

        return redirect()->back();
    }

    public function resumeWork()
{
    $employee = Employee::where('user_id', Auth::id())
        ->firstOrFail();

    $attendance = $employee->attendance()
        ->whereNull('clock_out')
        ->latest('attendance_id')
        ->first();

    if (!$attendance) {
        return redirect()->back()
            ->with('error', 'You are not currently clocked in.');
    }

    $sessionKey = 'break_started_at_' . $employee->employee_id;

    if (!session()->has($sessionKey)) {
        return redirect()->back()
            ->with('error', 'You are not currently on break.');
    }

    $breakStartedAt = session()->get($sessionKey);

    $breakSeconds = now()->timestamp - $breakStartedAt;
    $breakMinutes = (int) floor($breakSeconds / 60);

    $attendance->update([
        'break_minutes' => $attendance->break_minutes + $breakMinutes,
    ]);

    session()->forget($sessionKey);

    return redirect()->back()
        ->with('success', 'Your break has ended. Welcome back!');
}
}

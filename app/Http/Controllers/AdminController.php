<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Admin;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Department;

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
            ]);

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
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

    // Schedule Management

    public function scheduleManagement(Request $request)
    {
        $validated = $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        $month = $validated['month'] ?? now()->format('Y-m');

        $monthDate = \Carbon\Carbon::createFromFormat(
            'Y-m-d',
            $month . '-01'
        );

        $selectedDate = $validated['date'] ?? $monthDate->format('Y-m-01');

        if (\Carbon\Carbon::parse($selectedDate)->format('Y-m') !== $month) {
            $selectedDate = $monthDate->format('Y-m-01');
        }

        $selectedSchedule = Schedule::with('shifts.assignments.employee.user')
            ->whereYear('schedule_month', $monthDate->year)
            ->whereMonth('schedule_month', $monthDate->month)
            ->first();

        $shifts = $selectedSchedule
            ? $selectedSchedule->shifts()
                ->with('assignments.employee.user')
                ->whereDate('shift_date', $selectedDate)
                ->get()
            : collect();

        $employees = Employee::with('user')
            ->orderBy('employee_id')
            ->get();

        $schedules = Schedule::withCount('shifts')
            ->orderBy('schedule_month', 'desc')
            ->get();

        return view('admin.schedule-management', compact(
            'schedules',
            'selectedSchedule',
            'selectedDate',
            'month',
            'monthDate',
            'shifts',
            'employees'
        ));
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'schedule_month' => 'required|date_format:Y-m',
        ]);

        $user = Auth::user();

        $admin = Admin::where('user_id', $user->user_id)
            ->firstOrFail();

        $scheduleMonth = \Carbon\Carbon::createFromFormat(
            'Y-m-d',
            $validated['schedule_month'] . '-01'
        )->toDateString();

        $existingSchedule = Schedule::where(
            'schedule_month',
            $scheduleMonth
        )->first();

        if ($existingSchedule) {
            return redirect()
                ->back()
                ->with('error', 'A schedule already exists for this month.');
        }

        Schedule::create([
            'admin_id' => $admin->admin_id,
            'schedule_month' => $scheduleMonth,
            'status' => 'Draft',
        ]);

        return redirect()
            ->to('/admin/schedule-management?month=' . $validated['schedule_month'])
            ->with('success', 'Monthly schedule created successfully.');
    }

    public function storeShift(Request $request, $scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        $this->ensureDraftSchedule($schedule);

        $validated = $request->validate([
            'shift_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_minutes' => 'required|integer|min:0',
        ]);

        $this->ensureDateWithinScheduleMonth(
            $validated['shift_date'],
            $schedule
        );

        if (strtotime($validated['end_time']) <= strtotime($validated['start_time'])) {
            return redirect()
                ->back()
                ->withErrors([
                    'end_time' => 'The shift end time must be after the start time.',
                ])
                ->withInput();
        }

        Shift::create([
            'schedule_id' => $schedule->schedule_id,
            'shift_date' => $validated['shift_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'break_minutes' => $validated['break_minutes'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Shift created successfully.');
    }

    public function updateShift(Request $request, $shiftId)
    {
        $shift = Shift::with('schedule')->findOrFail($shiftId);

        $this->ensureDraftSchedule($shift->schedule);

        $validated = $request->validate([
            'shift_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_minutes' => 'required|integer|min:0',
        ]);

        $this->ensureDateWithinScheduleMonth(
            $validated['shift_date'],
            $shift->schedule
        );

        if (strtotime($validated['end_time']) <= strtotime($validated['start_time'])) {
            return redirect()
                ->back()
                ->withErrors([
                    'end_time' => 'The shift end time must be after the start time.',
                ])
                ->withInput();
        }

        $shift->update([
            'shift_date' => $validated['shift_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'break_minutes' => $validated['break_minutes'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Shift updated successfully.');
    }

    public function destroyShift($shiftId)
    {
        $shift = Shift::with('schedule')->findOrFail($shiftId);

        $this->ensureDraftSchedule($shift->schedule);

        $shift->delete();

        return redirect()
            ->back()
            ->with('success', 'Shift deleted successfully.');
    }

    public function storeShiftAssignment(Request $request, $shiftId)
    {
        $shift = Shift::with('schedule')->findOrFail($shiftId);

        $this->ensureDraftSchedule($shift->schedule);

        $validated = $request->validate([
            'employee_id' => 'required|exists:Employee,employee_id',
        ]);

        $existingAssignment = ShiftAssignment::where('shift_id', $shift->shift_id)
            ->where('employee_id', $validated['employee_id'])
            ->exists();

        if ($existingAssignment) {
            return redirect()
                ->back()
                ->with('error', 'This employee is already assigned to this shift.');
        }

        ShiftAssignment::create([
            'shift_id' => $shift->shift_id,
            'employee_id' => $validated['employee_id'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Employee assigned successfully.');
    }

    public function destroyShiftAssignment($assignmentId)
    {
        $assignment = ShiftAssignment::with('shift.schedule')
            ->findOrFail($assignmentId);

        $this->ensureDraftSchedule($assignment->shift->schedule);

        $assignment->delete();

        return redirect()
            ->back()
            ->with('success', 'Employee removed from shift successfully.');
    }

    public function saveScheduleDraft($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        $this->ensureDraftSchedule($schedule);

        $schedule->update([
            'status' => 'Draft',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Schedule saved as a draft.');
    }

    public function publishSchedule($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        $this->ensureDraftSchedule($schedule);

        $schedule->update([
            'status' => 'Published',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Schedule published successfully.');
    }

    public function reopenSchedule($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        abort_unless(
            $schedule->status === 'Published',
            403,
            'Only published schedules can be reopened.'
        );

        $schedule->update([
            'status' => 'Draft',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Schedule reopened as a draft. You can now edit it.');
    }

    private function ensureDraftSchedule(Schedule $schedule)
    {
        abort_unless(
            $schedule->status === 'Draft',
            403,
            'This schedule has already been published and cannot be edited.'
        );
    }

    private function ensureDateWithinScheduleMonth($date, Schedule $schedule)
    {
        $dateMonth = \Carbon\Carbon::parse($date)->format('Y-m');

        $scheduleMonth = \Carbon\Carbon::parse($schedule->schedule_month)
            ->format('Y-m');

        abort_unless(
            $dateMonth === $scheduleMonth,
            422,
            'The shift date must be within the schedule month.'
        );
    }

    public function statistics()
    {
        return view('admin.statistics');
    }
}

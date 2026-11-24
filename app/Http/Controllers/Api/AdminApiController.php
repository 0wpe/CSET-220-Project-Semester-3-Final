<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminApiController extends Controller
{
    /**
     * Summary data for the admin dashboard.
     */
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'counts' => [
                'employees' => Employee::count(),
                'departments' => Department::count(),
                'admins' => Admin::count(),
            ],
        ]);
    }

    /**
     * List all employees along with departments.
     */
    public function employees(): JsonResponse
    {
        $employees = Employee::with(['user', 'department'])->get();
        $departments = Department::all();

        return response()->json([
            'employees' => $employees,
            'departments' => $departments,
        ]);
    }

    /**
     * Get the authenticated admin's profile.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        $admin = Admin::with('user')
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        return response()->json([
            'admin' => $admin,
        ]);
    }

    /**
     * Create a new employee and associated user account.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:Users,email',
            'department_id' => 'required|exists:Departments,department_id',
            'designation' => 'nullable|string|max:255',
            'base_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'pay_type' => 'required|in:Hourly,Salaried',
        ]);

        $temporaryPassword = Str::random(12);

        $employee = DB::transaction(function () use ($validatedData, $temporaryPassword) {
            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($temporaryPassword),
                'role' => 'Employee',
            ]);

            return Employee::create([
                'user_id' => $user->user_id,
                'department_id' => $validatedData['department_id'],
                'designation' => $validatedData['designation'] ?? null,
                'base_salary' => $validatedData['base_salary'] ?? 0,
                'hourly_rate' => $validatedData['hourly_rate'] ?? 0,
                'pay_type' => $validatedData['pay_type'],
            ]);
        });

        $employee->load(['user', 'department']);

        return response()->json([
            'message' => 'Employee added successfully.',
            'employee' => $employee,
            'temporary_password' => $temporaryPassword,
        ], 201);
    }

    /**
     * Update an existing employee and linked user details.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $employee = Employee::with('user')->findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('Users', 'email')->ignore($employee->user_id, 'user_id'),
            ],
            'department_id' => 'required|exists:Departments,department_id',
            'designation' => 'nullable|string|max:255',
            'base_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'pay_type' => 'required|in:Hourly,Salaried',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($employee, $validatedData) {
            $userUpdates = [
                'email' => $validatedData['email'],
            ];

            if (array_key_exists('first_name', $validatedData)) {
                $userUpdates['first_name'] = $validatedData['first_name'];
            }

            if (array_key_exists('last_name', $validatedData)) {
                $userUpdates['last_name'] = $validatedData['last_name'];
            }

            if (!empty($validatedData['password'])) {
                $userUpdates['password'] = Hash::make($validatedData['password']);
            }

            $employee->user->update($userUpdates);

            $employee->update([
                'department_id' => $validatedData['department_id'],
                'designation' => $validatedData['designation'] ?? null,
                'base_salary' => $validatedData['base_salary'] ?? 0,
                'hourly_rate' => $validatedData['hourly_rate'] ?? 0,
                'pay_type' => $validatedData['pay_type'],
            ]);
        });

        $employee->load(['user', 'department']);

        return response()->json([
            'message' => 'Employee updated successfully.',
            'employee' => $employee,
        ]);
    }

    /**
     * Update the authenticated admin's profile with only provided fields.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('Users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'phone_number' => 'sometimes|nullable|string|max:20',
            'location' => 'sometimes|nullable|string|max:255',
            'profile_picture' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ]);

        $userUpdates = [];

        if (array_key_exists('first_name', $validated)) {
            $userUpdates['first_name'] = $validated['first_name'];
        }

        if (array_key_exists('last_name', $validated)) {
            $userUpdates['last_name'] = $validated['last_name'];
        }

        if (array_key_exists('email', $validated)) {
            $userUpdates['email'] = $validated['email'];
        }

        if (array_key_exists('phone_number', $validated)) {
            $userUpdates['phone_number'] = $validated['phone_number'];
        }

        if (array_key_exists('location', $validated)) {
            $userUpdates['location'] = $validated['location'];
        }

        if (!empty($validated['password'])) {
            $userUpdates['password'] = Hash::make($validated['password']);
        }

        $oldPicture = $user->profile_picture;
        $newPicture = null;
        $hasNewPicture = $request->hasFile('profile_picture');

        if ($hasNewPicture) {
            $newPicture = $request->file('profile_picture')
                ->store('profile-pictures', 'public');
            $userUpdates['profile_picture'] = $newPicture;
        } elseif (array_key_exists('profile_picture', $validated) && $validated['profile_picture'] === null) {
            // Allows explicitly clearing the profile picture if `null` is sent
            $userUpdates['profile_picture'] = null;
        }

        if (!empty($userUpdates)) {
            DB::transaction(function () use ($user, $userUpdates) {
                $user->update($userUpdates);
            });
        }

        if (($hasNewPicture || (array_key_exists('profile_picture', $validated) && $validated['profile_picture'] === null)) && $oldPicture) {
            Storage::disk('public')->delete($oldPicture);
        }

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Permanently delete an employee and related records.
     */
    public function destroy($id): JsonResponse
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

        return response()->json([
            'message' => 'Employee permanently deleted.',
        ]);
    }

    /**
     * View a single employee's profile details.
     */
    public function profileView($id): JsonResponse
    {
        $employee = Employee::with(['user', 'department'])
            ->findOrFail($id);

        return response()->json([
            'employee' => $employee,
        ]);
    }

    /**
     * List attendance records (optionally filtered by employee_id or date).
     */
    public function attendance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|integer|exists:Employee,employee_id',
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        $query = Attendance::with(['employee.user', 'assignment.shift'])
            ->orderByDesc('clock_in');

        if (!empty($validated['employee_id'])) {
            $query->where('employee_id', $validated['employee_id']);
        }

        if (!empty($validated['date'])) {
            $query->whereDate('clock_in', $validated['date']);
        }

        return response()->json([
            'attendance' => $query->get(),
        ]);
    }

    /**
     * List payroll records (optionally filtered by employee_id or month Y-m).
     */
    public function payroll(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|integer|exists:Employee,employee_id',
            'month' => 'nullable|date_format:Y-m',
        ]);

        $query = Payroll::with(['employee.user', 'employee.department'])
            ->orderByDesc('payroll_month');

        if (!empty($validated['employee_id'])) {
            $query->where('employee_id', $validated['employee_id']);
        }

        if (!empty($validated['month'])) {
            $monthDate = Carbon::createFromFormat('Y-m-d', $validated['month'] . '-01');
            $query->whereYear('payroll_month', $monthDate->year)
                ->whereMonth('payroll_month', $monthDate->month);
        }

        return response()->json([
            'payroll' => $query->get(),
        ]);
    }

    /**
     * Retrieve schedule management data for a given month and date.
     */
    public function scheduleManagement(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        $month = $validated['month'] ?? now()->format('Y-m');

        $monthDate = Carbon::createFromFormat('Y-m-d', $month . '-01');

        $selectedDate = $validated['date'] ?? $monthDate->format('Y-m-01');

        if (Carbon::parse($selectedDate)->format('Y-m') !== $month) {
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

        return response()->json([
            'schedules' => $schedules,
            'selected_schedule' => $selectedSchedule,
            'selected_date' => $selectedDate,
            'month' => $month,
            'shifts' => $shifts,
            'employees' => $employees,
        ]);
    }

    /**
     * Create a new monthly schedule in Draft status.
     */
    public function storeSchedule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'schedule_month' => 'required|date_format:Y-m',
        ]);

        $user = $request->user();

        $admin = Admin::where('user_id', $user->user_id)
            ->firstOrFail();

        $scheduleMonth = Carbon::createFromFormat(
            'Y-m-d',
            $validated['schedule_month'] . '-01'
        )->toDateString();

        $existingSchedule = Schedule::where('schedule_month', $scheduleMonth)->first();

        if ($existingSchedule) {
            return response()->json([
                'message' => 'A schedule already exists for this month.',
            ], 409);
        }

        $schedule = Schedule::create([
            'admin_id' => $admin->admin_id,
            'schedule_month' => $scheduleMonth,
            'status' => 'Draft',
        ]);

        return response()->json([
            'message' => 'Monthly schedule created successfully.',
            'schedule' => $schedule,
        ], 201);
    }

    /**
     * Create a shift under a Draft schedule.
     */
    public function storeShift(Request $request, $scheduleId): JsonResponse
    {
        $schedule = Schedule::findOrFail($scheduleId);

        $this->ensureDraftSchedule($schedule);

        $validated = $request->validate([
            'shift_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_minutes' => 'required|integer|min:0',
        ]);

        $this->ensureDateWithinScheduleMonth($validated['shift_date'], $schedule);

        if (strtotime($validated['end_time']) <= strtotime($validated['start_time'])) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => [
                    'end_time' => ['The shift end time must be after the start time.'],
                ],
            ], 422);
        }

        $shift = Shift::create([
            'schedule_id' => $schedule->schedule_id,
            'shift_date' => $validated['shift_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'break_minutes' => $validated['break_minutes'],
        ]);

        return response()->json([
            'message' => 'Shift created successfully.',
            'shift' => $shift,
        ], 201);
    }

    /**
     * Update an existing shift on a Draft schedule.
     */
    public function updateShift(Request $request, $shiftId): JsonResponse
    {
        $shift = Shift::with('schedule')->findOrFail($shiftId);

        $this->ensureDraftSchedule($shift->schedule);

        $validated = $request->validate([
            'shift_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_minutes' => 'required|integer|min:0',
        ]);

        $this->ensureDateWithinScheduleMonth($validated['shift_date'], $shift->schedule);

        if (strtotime($validated['end_time']) <= strtotime($validated['start_time'])) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => [
                    'end_time' => ['The shift end time must be after the start time.'],
                ],
            ], 422);
        }

        $shift->update([
            'shift_date' => $validated['shift_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'break_minutes' => $validated['break_minutes'],
        ]);

        return response()->json([
            'message' => 'Shift updated successfully.',
            'shift' => $shift->fresh(),
        ]);
    }

    /**
     * Delete a shift from a Draft schedule.
     */
    public function destroyShift($shiftId): JsonResponse
    {
        $shift = Shift::with('schedule')->findOrFail($shiftId);

        $this->ensureDraftSchedule($shift->schedule);

        $shift->delete();

        return response()->json([
            'message' => 'Shift deleted successfully.',
        ]);
    }

    /**
     * Assign an employee to a shift on a Draft schedule.
     */
    public function storeShiftAssignment(Request $request, $shiftId): JsonResponse
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
            return response()->json([
                'message' => 'This employee is already assigned to this shift.',
            ], 409);
        }

        $assignment = ShiftAssignment::create([
            'shift_id' => $shift->shift_id,
            'employee_id' => $validated['employee_id'],
        ]);

        $assignment->load('employee.user');

        return response()->json([
            'message' => 'Employee assigned successfully.',
            'assignment' => $assignment,
        ], 201);
    }

    /**
     * Remove an employee assignment from a shift on a Draft schedule.
     */
    public function destroyShiftAssignment($assignmentId): JsonResponse
    {
        $assignment = ShiftAssignment::with('shift.schedule')
            ->findOrFail($assignmentId);

        $this->ensureDraftSchedule($assignment->shift->schedule);

        $assignment->delete();

        return response()->json([
            'message' => 'Employee removed from shift successfully.',
        ]);
    }

    /**
     * Keep or save a schedule as a Draft.
     */
    public function saveScheduleDraft($scheduleId): JsonResponse
    {
        $schedule = Schedule::findOrFail($scheduleId);

        $this->ensureDraftSchedule($schedule);

        $schedule->update([
            'status' => 'Draft',
        ]);

        return response()->json([
            'message' => 'Schedule saved as a draft.',
            'schedule' => $schedule->fresh(),
        ]);
    }

    /**
     * Publish a Draft schedule.
     */
    public function publishSchedule($scheduleId): JsonResponse
    {
        $schedule = Schedule::findOrFail($scheduleId);

        $this->ensureDraftSchedule($schedule);

        $schedule->update([
            'status' => 'Published',
        ]);

        return response()->json([
            'message' => 'Schedule published successfully.',
            'schedule' => $schedule->fresh(),
        ]);
    }

    /**
     * Reopen a Published schedule back to Draft.
     */
    public function reopenSchedule($scheduleId): JsonResponse
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

        return response()->json([
            'message' => 'Schedule reopened as a draft. You can now edit it.',
            'schedule' => $schedule->fresh(),
        ]);
    }

    private function ensureDraftSchedule(Schedule $schedule): void
    {
        abort_unless(
            $schedule->status === 'Draft',
            403,
            'This schedule has already been published and cannot be edited.'
        );
    }

    private function ensureDateWithinScheduleMonth($date, Schedule $schedule): void
    {
        $dateMonth = Carbon::parse($date)->format('Y-m');

        $scheduleMonth = Carbon::parse($schedule->schedule_month)
            ->format('Y-m');

        abort_unless(
            $dateMonth === $scheduleMonth,
            422,
            'The shift date must be within the schedule month.'
        );
    }
}

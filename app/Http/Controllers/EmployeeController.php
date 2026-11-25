<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\ShiftAssignment;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PayrollController;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $employee = Employee::with(['user', 'department'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $now = now();

        /*
         * Annual leave balance
         */
        $annualLeaveAllowance = (float) $employee->annual_leave_allowance;

        $yearStart = $now->copy()->startOfYear();
        $yearEnd = $now->copy()->endOfYear();

        $approvedAnnualLeaveRequests = $employee->leaveRequests()
            ->whereRaw('LOWER(status) = ?', ['approved'])
            ->whereRaw('LOWER(leave_type) = ?', ['annual leave'])
            ->whereDate('start_date', '<=', $yearEnd->toDateString())
            ->whereDate('end_date', '>=', $yearStart->toDateString())
            ->get();

        $annualLeaveUsed = 0;

        foreach ($approvedAnnualLeaveRequests as $leaveRequest) {
            $startDate = Carbon::parse($leaveRequest->start_date);
            $endDate = Carbon::parse($leaveRequest->end_date);

            if ($startDate->lt($yearStart)) {
                $startDate = $yearStart->copy();
            }

            if ($endDate->gt($yearEnd)) {
                $endDate = $yearEnd->copy();
            }

            $annualLeaveUsed += $startDate->diffInDays($endDate) + 1;
        }

        $remainingAnnualLeave = max(
            0,
            $annualLeaveAllowance - $annualLeaveUsed
        );

        /*
         * Today's shift and attendance
         */
        $today = $now->toDateString();

        $todayAssignment = ShiftAssignment::with(['shift.schedule'])
            ->where('employee_id', $employee->employee_id)
            ->whereHas('shift', function ($query) use ($today) {
                $query->whereDate('shift_date', $today)
                    ->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('status', 'Published');
                    });
            })
            ->first();

        $todayAttendance = null;

        if ($todayAssignment) {
            $todayAttendance = Attendance::where(
                'assignment_id',
                $todayAssignment->assignment_id
            )
                ->where('employee_id', $employee->employee_id)
                ->first();
        }

        $shiftStatus = 'No shift assigned';
        $shiftHeading = 'Enjoy your day.';
        $shiftDetails = 'You do not have a published shift scheduled for today.';
        $clockedInTime = null;

        if ($todayAssignment && $todayAssignment->shift) {
            $shift = $todayAssignment->shift;

            $shiftStart = Carbon::parse($shift->start_time)->format('h:i A');
            $shiftEnd = Carbon::parse($shift->end_time)->format('h:i A');

            $shiftStatus = 'Not clocked in';
            $shiftHeading = 'Your shift is scheduled.';
            $shiftDetails = $shiftStart . ' – ' . $shiftEnd;

            if ($todayAttendance && $todayAttendance->clock_in) {
                $clockedInTime = Carbon::parse(
                    $todayAttendance->clock_in
                )->format('h:i A');

                if ($todayAttendance->clock_out) {
                    $shiftStatus = 'Shift completed';
                    $shiftHeading = 'You have finished your shift.';
                } elseif ($todayAttendance->break_started_at) {
                    $shiftStatus = 'On break';
                    $shiftHeading = 'Enjoy your break.';
                } else {
                    $shiftStatus = 'On the clock';
                    $shiftHeading = 'Make today count.';
                }
            }
        }

        /*
         * Time worked today
         */
        $todayWorkedSeconds = 0;

        if ($todayAttendance && $todayAttendance->clock_in) {
            $clockIn = Carbon::parse($todayAttendance->clock_in);

            $clockOut = $todayAttendance->clock_out
                ? Carbon::parse($todayAttendance->clock_out)
                : $now->copy();

            if ($clockOut->gt($now)) {
                $clockOut = $now->copy();
            }

            $completedBreakSeconds = (int) $todayAttendance->break_seconds;

            // Fallback for older records that have not been converted.
            if (
                $completedBreakSeconds === 0
                && (int) $todayAttendance->break_minutes > 0
            ) {
                $completedBreakSeconds =
                    (int) $todayAttendance->break_minutes * 60;
            }

            $todayWorkedSeconds = max(
                0,
                $clockOut->timestamp
                    - $clockIn->timestamp
                    - $completedBreakSeconds
            );

            // Do not count time elapsed during an active break.
            if ($todayAttendance->break_started_at) {
                $activeBreakSeconds = max(
                    0,
                    $now->timestamp
                        - Carbon::parse($todayAttendance->break_started_at)->timestamp
                );

                $todayWorkedSeconds = max(
                    0,
                    $todayWorkedSeconds - $activeBreakSeconds
                );
            }
        }

        $isOnBreak = $todayAttendance
            && $todayAttendance->break_started_at
            && !$todayAttendance->clock_out;

        $scheduledBreakMinutes = (int) (
            $todayAssignment?->shift?->break_minutes ?? 0
        );

        /*
         * Current week's date range
         */
        $weekStart = $now->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $now->copy()->endOfWeek(Carbon::SUNDAY);

        /*
         * Weekly hours worked
         */
        $weeklyAttendances = Attendance::where(
            'employee_id',
            $employee->employee_id
        )
            ->whereBetween('clock_in', [$weekStart, $weekEnd])
            ->get();

        $weeklyWorkedSeconds = 0;

        foreach ($weeklyAttendances as $attendance) {
            if (!$attendance->clock_in) {
                continue;
            }

            $clockIn = Carbon::parse($attendance->clock_in);

            $clockOut = $attendance->clock_out
                ? Carbon::parse($attendance->clock_out)
                : $now->copy();

            if ($clockOut->gt($now)) {
                $clockOut = $now->copy();
            }

            $completedBreakSeconds = (int) $attendance->break_seconds;

            // Fallback for older records that have not been converted.
            if (
                $completedBreakSeconds === 0
                && (int) $attendance->break_minutes > 0
            ) {
                $completedBreakSeconds =
                    (int) $attendance->break_minutes * 60;
            }

            $workedSeconds = max(
                0,
                $clockOut->timestamp
                    - $clockIn->timestamp
                    - $completedBreakSeconds
            );

            // Exclude an ongoing break from weekly worked time too.
            if ($attendance->break_started_at) {
                $activeBreakSeconds = max(
                    0,
                    $now->timestamp
                        - Carbon::parse($attendance->break_started_at)->timestamp
                );

                $workedSeconds = max(
                    0,
                    $workedSeconds - $activeBreakSeconds
                );
            }

            $weeklyWorkedSeconds += $workedSeconds;
        }

        $weeklyWorkedHours = $weeklyWorkedSeconds / 3600;

        /*
         * Scheduled hours this week
         */
        $weeklyAssignments = ShiftAssignment::with('shift')
            ->where('employee_id', $employee->employee_id)
            ->whereHas('shift', function ($query) use ($weekStart, $weekEnd) {
                $query->whereBetween('shift_date', [
                    $weekStart->toDateString(),
                    $weekEnd->toDateString(),
                ])
                    ->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('status', 'Published');
                    });
            })
            ->get();

        $scheduledSeconds = 0;

        foreach ($weeklyAssignments as $assignment) {
            $shift = $assignment->shift;

            if (!$shift) {
                continue;
            }

            $shiftDate = Carbon::parse($shift->shift_date)->toDateString();

            $scheduledStart = Carbon::parse(
                $shiftDate . ' ' . $shift->start_time
            );

            $scheduledEnd = Carbon::parse(
                $shiftDate . ' ' . $shift->end_time
            );

            if ($scheduledEnd->lte($scheduledStart)) {
                $scheduledEnd->addDay();
            }

            $scheduledSeconds += max(
                0,
                $scheduledEnd->timestamp
                    - $scheduledStart->timestamp
                    - ((int) $shift->break_minutes * 60)
            );
        }

        $scheduledHours = $scheduledSeconds / 3600;

        /*
         * Upcoming approved leave
         */
        $upcomingLeave = $employee->leaveRequests()
            ->whereRaw('LOWER(status) = ?', ['approved'])
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date')
            ->first();

        $upcomingLeaveDays = null;

        if ($upcomingLeave) {
            $leaveStart = Carbon::parse($upcomingLeave->start_date);
            $leaveEnd = Carbon::parse($upcomingLeave->end_date);

            $upcomingLeaveDays = $leaveStart->diffInDays($leaveEnd) + 1;
        }

        /*
         * Footer message
         */
        $currentDay = $now->format('l');

        return view('employee.dashboard', compact(
            'employee',
            'annualLeaveAllowance',
            'annualLeaveUsed',
            'remainingAnnualLeave',
            'todayAssignment',
            'todayAttendance',
            'shiftStatus',
            'shiftHeading',
            'shiftDetails',
            'clockedInTime',
            'todayWorkedSeconds',
            'weeklyWorkedHours',
            'scheduledHours',
            'upcomingLeave',
            'upcomingLeaveDays',
            'currentDay',
            'isOnBreak',
            'scheduledBreakMinutes'
        ));
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

        $assignment = ShiftAssignment::with(['shift.schedule'])
            ->where('employee_id', $employee->employee_id)
            ->whereHas('shift', function ($query) {
                $query->whereDate('shift_date', now()->toDateString())
                    ->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('status', 'Published');
                    });
            })
            ->first();

        $attendance = null;

        if ($assignment) {
            $attendance = Attendance::where('assignment_id', $assignment->assignment_id)
                ->where('employee_id', $employee->employee_id)
                ->first();
        }

        $scheduledBreakMinutes = (int) (
            $assignment?->shift?->break_minutes ?? 0
        );

        return view('employee.time-entry', compact(
            'employee',
            'assignment',
            'attendance',
            'scheduledBreakMinutes'
        ));
    }

    public function clockIn()
    {
        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $assignment = ShiftAssignment::where('employee_id', $employee->employee_id)
            ->whereHas('shift', function ($query) {
                $query->whereDate('shift_date', now()->toDateString())
                    ->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('status', 'Published');
                    });
            })
            ->first();

        if (!$assignment) {
            return redirect('/employee/time-entry')
                ->with('error', 'You do not have a published shift assigned for today.');
        }

        $existingAttendance = Attendance::where('assignment_id', $assignment->assignment_id)
            ->where('employee_id', $employee->employee_id)
            ->first();

        if ($existingAttendance) {
            return redirect('/employee/time-entry')
                ->with('error', 'Attendance has already been recorded for this shift.');
        }

        Attendance::create([
            'employee_id' => $employee->employee_id,
            'assignment_id' => $assignment->assignment_id,
            'clock_in' => now(),
            'clock_out' => null,
            'break_minutes' => 0,
            'break_seconds' => 0,
            'break_started_at' => null,
        ]);

        return redirect('/employee/time-entry')
            ->with('success', 'You have successfully clocked in.');
    }

    public function startBreak()
    {
        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $attendance = Attendance::where('employee_id', $employee->employee_id)
            ->whereDate('clock_in', now()->toDateString())
            ->whereNull('clock_out')
            ->first();

        if (!$attendance) {
            return redirect('/employee/time-entry')
                ->with('error', 'You must be clocked in before starting a break.');
        }

        if ($attendance->break_started_at) {
            return redirect('/employee/time-entry')
                ->with('error', 'You are already on a break.');
        }

        $attendance->update([
            'break_started_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Your break has started.');
    }

    public function endBreak()
    {
        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $attendance = Attendance::where('employee_id', $employee->employee_id)
            ->whereDate('clock_in', now()->toDateString())
            ->whereNull('clock_out')
            ->first();

        if (!$attendance) {
            return redirect('/employee/time-entry')
                ->with('error', 'No active attendance record was found.');
        }

        if (!$attendance->break_started_at) {
            return redirect()->back()
                ->with('error', 'You are not currently on a break.');
        }

        $breakStart = Carbon::parse($attendance->break_started_at);

        // Calculate the exact break duration in seconds.
        $breakSeconds = max(
            0,
            now()->timestamp - $breakStart->timestamp
        );

        // Accumulate this break with all previous breaks.
        $totalBreakSeconds = (int) $attendance->break_seconds
            + $breakSeconds;

        // Keep break_minutes synchronized for existing code.
        $totalBreakMinutes = intdiv($totalBreakSeconds, 60);

        $attendance->update([
            'break_seconds' => $totalBreakSeconds,
            'break_minutes' => $totalBreakMinutes,
            'break_started_at' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Your break has ended. Your timer has resumed.');
    }

    public function clockOut()
    {
        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $assignment = ShiftAssignment::where('employee_id', $employee->employee_id)
            ->whereHas('shift', function ($query) {
                $query->whereDate('shift_date', now()->toDateString())
                    ->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('status', 'Published');
                    });
            })
            ->first();

        if (!$assignment) {
            return redirect('/employee/time-entry')
                ->with('error', 'You do not have a published shift assigned for today.');
        }

        $attendance = Attendance::where('assignment_id', $assignment->assignment_id)
            ->where('employee_id', $employee->employee_id)
            ->first();

        if (!$attendance) {
            return redirect('/employee/time-entry')
                ->with('error', 'You must clock in before you can clock out.');
        }

        if ($attendance->clock_out) {
            return redirect('/employee/time-entry')
                ->with('error', 'You have already clocked out for this shift.');
        }

        if ($attendance->break_started_at) {
            return redirect('/employee/time-entry')
                ->with('error', 'Please end your break before clocking out.');
        }

        $attendance->update([
            'clock_out' => now(),
        ]);

        return redirect('/employee/time-entry')
            ->with('success', 'You have successfully clocked out.');
    }

    public function leaveRequests()
    {
        return view('employee.leave-requests');
    }

    public function mySchedule()
    {
        return view('employee.my-schedule');
    }

    // Display the logged-in employee's processed payslips.
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
            'annual_leave_allowance' => 'nullable|numeric|min:0|max:999.99',
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
                'annual_leave_allowance' => $validatedData['annual_leave_allowance'] ?? 12,
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
            'annual_leave_allowance' => 'sometimes|numeric|min:0|max:999.99',
        ]);

        $employee->update($validatedData);

        return redirect()->back()
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->back();
    }
}

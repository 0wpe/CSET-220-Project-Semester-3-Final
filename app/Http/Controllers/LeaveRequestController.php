<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', Auth::id())->first();

        $recentRequests = $employee
            ? LeaveRequest::where('employee_id', $employee->employee_id)
                ->orderByDesc('submitted_at')
                ->orderByDesc('request_id')
                ->take(5)
                ->get()
            : collect();

        return view('employee.leave-requests', [
            'employee' => $employee,
            'recentRequests' => $recentRequests,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:annual,personal,sick,unpaid',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:500',
        ]);

        // Matches existing `leave_type` values in the table (e.g., Vacation, Personal matters)[cite: 2]
        $leaveTypeMap = [
            'annual'   => 'Vacation',
            'personal' => 'Personal matters',
            'sick'     => 'Medical appointment',
            'unpaid'   => 'Family commitment',
        ];

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        try {
            DB::transaction(function () use ($employee, $validated, $leaveTypeMap) {
                LeaveRequest::create([
                    'employee_id'  => $employee->employee_id,
                    'leave_type'   => $leaveTypeMap[$validated['leave_type']],
                    'start_date'   => Carbon::parse($validated['start_date'])->toDateString(),
                    'end_date'     => Carbon::parse($validated['end_date'])->toDateString(),
                    'status'       => 'Pending',
                    'reviewed_by'  => null,
                    'submitted_at' => now(),
                    'reason'       => $validated['reason'] ?? null,
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to store leave request: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Unable to save leave request. Please try again.']);
        }

        return redirect()->route('employee.leave-requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }
}

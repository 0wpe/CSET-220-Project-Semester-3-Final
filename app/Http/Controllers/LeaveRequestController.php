<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Employee;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->role === 'Admin') {
            $leaveRequests = LeaveRequest::with(['employee.user', 'employee.department', 'reviewer'])
                ->orderByDesc('submitted_at')
                ->orderByDesc('request_id')
                ->get();

            return view('admin.leave-requests', [
                'leaveRequests' => $leaveRequests,
            ]);
        }

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
            'leave_type' => 'required|in:Annual Leave,Personal Leave,Sick Leave,Unpaid Leave',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:500',
        ]);

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        try {
            DB::transaction(function () use ($employee, $validated) {
                $leaveRequest = LeaveRequest::create([
                    'employee_id'  => $employee->employee_id,
                    'leave_type' => $validated['leave_type'],
                    'start_date'   => Carbon::parse($validated['start_date'])->toDateString(),
                    'end_date'     => Carbon::parse($validated['end_date'])->toDateString(),
                    'status'       => 'Pending',
                    'reviewed_by'  => null,
                    'submitted_at' => now(),
                    'reason'       => $validated['reason'] ?? null,
                ]);

                $leaveRequest->forceFill([
                    'submitted_at' => now(),
                ])->save();
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

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $user = Auth::user();
        $admin = Admin::where('user_id', $user->user_id ?? $user->id)->first();

        try {
            DB::transaction(function () use ($leaveRequest, $validated, $admin) {
                $leaveRequest->update([
                    'status'      => $validated['status'],
                    'reviewed_by' => $admin?->admin_id,
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to update leave request status: ' . $e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Unable to update leave request status. Please try again.']);
        }

        return redirect()->back()
            ->with('success', "Leave request marked as {$validated['status']}.");
    }
}

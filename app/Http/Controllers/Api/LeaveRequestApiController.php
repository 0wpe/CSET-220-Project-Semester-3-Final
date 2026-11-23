<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveRequestApiController extends Controller
{
    /**
     * Resolve the user authenticated via the request's Sanctum bearer token.
     */
    private function resolveBearerUser(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return null;
        }

        return $request->user('sanctum') ?? $request->user();
    }

    /**
     * List leave requests.
     * - Admins receive all leave requests with employee & reviewer details.
     * - Employees receive their own leave requests.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $this->resolveBearerUser($request);

        if (!$user) {
            return response()->json([
                'message' => 'Missing or invalid bearer token.',
            ], 401);
        }

        if ($user->role === 'Admin') {
            $leaveRequests = LeaveRequest::with([
                'employee.user',
                'employee.department',
                'reviewer.user',
            ])
                ->orderByDesc('submitted_at')
                ->orderByDesc('request_id')
                ->get();

            return response()->json([
                'data' => $leaveRequests,
            ], 200);
        }

        $employee = Employee::where('user_id', $user->user_id)->first();

        if (!$employee) {
            return response()->json([
                'message' => 'Employee profile not found.',
                'data' => [],
            ], 200);
        }

        $leaveRequests = LeaveRequest::with(['reviewer.user'])
            ->where('employee_id', $employee->employee_id)
            ->orderByDesc('submitted_at')
            ->orderByDesc('request_id')
            ->get();

        return response()->json([
            'data' => $leaveRequests,
        ], 200);
    }

    /**
     * Store a newly created leave request for the authenticated employee.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $this->resolveBearerUser($request);

        if (!$user) {
            return response()->json([
                'message' => 'Missing or invalid bearer token.',
            ], 401);
        }

        $validated = $request->validate([
            'leave_type' => 'required|in:Annual Leave,Personal Leave,Sick Leave,Unpaid Leave',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:500',
        ]);

        $employee = Employee::where('user_id', $user->user_id)->first();

        if (!$employee) {
            return response()->json([
                'message' => 'Employee profile not found for the authenticated user.',
            ], 404);
        }

        try {
            $leaveRequest = DB::transaction(function () use ($employee, $validated) {
                $created = LeaveRequest::create([
                    'employee_id'  => $employee->employee_id,
                    'leave_type'   => $validated['leave_type'],
                    'start_date'   => Carbon::parse($validated['start_date'])->toDateString(),
                    'end_date'     => Carbon::parse($validated['end_date'])->toDateString(),
                    'status'       => 'Pending',
                    'reviewed_by'  => null,
                    'submitted_at' => now(),
                    'reason'       => $validated['reason'] ?? null,
                ]);

                $created->forceFill([
                    'submitted_at' => now(),
                ])->save();

                return $created->fresh(['employee.user', 'employee.department']);
            });

            return response()->json([
                'message' => 'Leave request submitted successfully.',
                'data'    => $leaveRequest,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('API failed to store leave request: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to save leave request. Please try again.',
            ], 500);
        }
    }

    /**
     * Display a single leave request.
     * - Admins can view any leave request.
     * - Employees can only view their own leave request.
     */
    public function show(Request $request, LeaveRequest $leaveRequest): JsonResponse
    {
        $user = $this->resolveBearerUser($request);

        if (!$user) {
            return response()->json([
                'message' => 'Missing or invalid bearer token.',
            ], 401);
        }

        if ($user->role !== 'Admin') {
            $employee = Employee::where('user_id', $user->user_id)->first();

            if (!$employee || $leaveRequest->employee_id !== $employee->employee_id) {
                return response()->json([
                    'message' => 'Unauthorized access.',
                ], 403);
            }
        }

        $leaveRequest->load(['employee.user', 'employee.department', 'reviewer.user']);

        return response()->json([
            'data' => $leaveRequest,
        ], 200);
    }

    /**
     * Update the status of a leave request (Admin review workflow).
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest): JsonResponse
    {
        $user = $this->resolveBearerUser($request);

        if (!$user) {
            return response()->json([
                'message' => 'Missing or invalid bearer token.',
            ], 401);
        }

        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $admin = Admin::where('user_id', $user->user_id)->first();

        if (!$admin) {
            return response()->json([
                'message' => 'Admin profile not found for the authenticated user.',
            ], 404);
        }

        try {
            DB::transaction(function () use ($leaveRequest, $validated, $admin) {
                $leaveRequest->update([
                    'status'      => $validated['status'],
                    'reviewed_by' => $admin->admin_id,
                ]);
            });

            $leaveRequest->load(['employee.user', 'employee.department', 'reviewer.user']);

            return response()->json([
                'message' => "Leave request marked as {$validated['status']}.",
                'data'    => $leaveRequest,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API failed to update leave request status: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to update leave request status. Please try again.',
            ], 500);
        }
    }
}

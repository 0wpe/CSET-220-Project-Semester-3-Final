<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\LeaveRequestApiController;

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Shared authenticated leave request endpoints
    Route::get('/leave-requests', [LeaveRequestApiController::class, 'index']);
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestApiController::class, 'show']);

    // Authenticated Employee Routes
    Route::middleware(['role:Employee'])
        ->prefix('employee')
        ->group(function () {
            Route::post('/leave-requests', [LeaveRequestApiController::class, 'store']);
        });

    // Authenticated Admin Routes
    Route::middleware(['role:Admin'])
        ->prefix('admin')
        ->group(function () {
            Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
            Route::get('/employees', [AdminApiController::class, 'employees']);
            Route::post('/employees', [AdminApiController::class, 'store']);
            Route::put('/employees/{id}', [AdminApiController::class, 'update']);
            Route::delete('/employees/{id}', [AdminApiController::class, 'destroy']);
            Route::get('/employees/{id}', [AdminApiController::class, 'profileView']);

            Route::get('/profile', [AdminApiController::class, 'profile']);
            Route::post('/profile', [AdminApiController::class, 'updateProfile']);

            Route::get('/attendance', [AdminApiController::class, 'attendance']);
            Route::get('/payroll', [AdminApiController::class, 'payroll']);

            // Schedule Management
            Route::get('/schedule-management', [AdminApiController::class, 'scheduleManagement']);
            Route::post('/schedule-management', [AdminApiController::class, 'storeSchedule']);
            Route::post('/schedules/{schedule}/shifts', [AdminApiController::class, 'storeShift']);
            Route::put('/shifts/{shift}', [AdminApiController::class, 'updateShift']);
            Route::delete('/shifts/{shift}', [AdminApiController::class, 'destroyShift']);
            Route::post('/shifts/{shift}/assignments', [AdminApiController::class, 'storeShiftAssignment']);
            Route::delete('/shift-assignments/{assignment}', [AdminApiController::class, 'destroyShiftAssignment']);
            Route::patch('/schedules/{schedule}/draft', [AdminApiController::class, 'saveScheduleDraft']);
            Route::patch('/schedules/{schedule}/publish', [AdminApiController::class, 'publishSchedule']);
            Route::patch('/schedules/{schedule}/reopen', [AdminApiController::class, 'reopenSchedule']);

            Route::patch('/leave-requests/{leaveRequest}/status', [LeaveRequestApiController::class, 'updateStatus']);
        });
});

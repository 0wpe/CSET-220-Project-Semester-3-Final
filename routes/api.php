<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\LeaveRequestApiController;

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::get('/leave-requests', [LeaveRequestApiController::class, 'index']);
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestApiController::class, 'show']);

    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Employee-only submission
    Route::middleware(['role:Employee'])->group(function () {
        Route::post('/leave-requests', [LeaveRequestApiController::class, 'store']);
    });

    // Admin-only status review
    Route::middleware(['role:Admin'])->group(function () {
        Route::patch('/leave-requests/{leaveRequest}/status', [LeaveRequestApiController::class, 'updateStatus']);
    });
});

<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/test", function (User $user) {
    return $user->id;
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Attendance
Route::post("/clock_in", [AttendanceController::class, 'store'])->middleware('auth:sanctum');
Route::post("/clock_out/{attendance}", [AttendanceController::class, 'update'])->middleware('auth:sanctum');
Route::get("/attendance", [AttendanceController::class, 'index'])->middleware('auth:sanctum');

// Leave Types
// To be updated
Route::post("/user/{user}/leave_type/{leaveType}", [LeaveTypeController::class, 'update'])->middleware('auth:sanctum');
Route::post("/user/{user}/leave_type/{leaveType}/use", [LeaveTypeController::class, 'incrementUse'])->middleware('auth:sanctum');

// Leave Requests
// Route::post("/leave_request", [LeaveRequestController::class, 'store'])->middleware('auth:sanctum');
// Route::post("/leave_request/{leaveRequest}", [LeaveRequestController::class, 'update'])->middleware('auth:sanctum');
// Route::get("/leave_request", [LeaveRequestController::class, 'index'])->middleware('auth:sanctum');
// Route::get("/leave_request/{leaveRequest}", [LeaveRequestController::class, 'show'])->middleware('auth:sanctum');
// Route::delete("/leave_request/{leaveRequest}", [LeaveRequestController::class, 'destroy'])->middleware('auth:sanctum');
Route::apiResource('leave_request', LeaveRequestController::class)->middleware('auth:sanctum');

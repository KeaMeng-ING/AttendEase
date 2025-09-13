<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();
        // $user = \App\Models\User::find(11); // Replace 1 with the desired 

        // Get the date from the query parameter (default to today's date if not provided)
        // $date = $request->query('date', now()->toDateString());

        // Retrieve attendance records for the logged-in user on the specified date
        $attendances = $user->attendances()
            // ->where('attendance_date', $date)
            ->latest()
            ->take(3)
            ->get();

        // Return the attendance records as JSON
        return response()->json($attendances, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clockInTime = now();
        $attendanceDate = $clockInTime->toDateString();

        // Check if an attendance record already exists for the user today
        $existingAttendance = $request->user()->attendances()->where('attendance_date', $attendanceDate)->first();

        if ($existingAttendance) {
            return response()->json([
                'message' => 'You have already clocked in today.',
            ], 400);
        }

        // Check if the clock-in time is before 8:00 AM
        $status = $clockInTime->toTimeString() < '08:00' ? 'present' : 'late';

        // Create a new attendance record
        $attendance = $request->user()->attendances()->create([
            'clock_in' => $clockInTime,
            'attendance_date' => $attendanceDate,
            'status' => $status,

        ]);

        return response()->json([
            'attendance_id' => $attendance->id,
            'attendance' => $attendance,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        // Check if the user has already clocked out
        if ($attendance->clock_out !== null) {
            return response()->json([
                'message' => 'You have already clocked out for this attendance record.',
            ], 400);
        }

        $clockOutTime = now();

        $attendance = $attendance->update([
            'clock_out' => $clockOutTime,
        ]);

        return response()->json($attendance, 200);
    }

    public function showCurrent(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();

        // Retrieve today's attendance record for the logged-in user
        $attendance = $user->attendances()
            ->where('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'No attendance record found for today.',
            ], 404);
        }

        return response()->json($attendance, 200);
    }
}

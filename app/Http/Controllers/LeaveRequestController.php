<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLeaveRequestRequest;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return LeaveRequest::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            "start_date" => 'required|date',
            "end_date" => 'required|date|after_or_equal:start_date',
            "reason" => 'required|string|max:255',
            "leave_type_id" => 'required|exists:leave_types,id',
        ]);

        $leaveRequest = LeaveRequest::create([
            'user_id' => $request->user()->id,
            'start_date' => $fields['start_date'],
            'end_date' => $fields['end_date'],
            'reason' => $fields['reason'],
            'leave_type_id' => $fields['leave_type_id'],
        ]);

        return response()->json(['message' => 'Leave request created successfully', 'data' => $leaveRequest], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        return $leaveRequest;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        // Check if the authenticated user has the "admin" role or "manager" role
        if ($request->user()->role !== 'admin' && $request->user()->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized. Only admins and managers can update leave requests.'], 403);
        }

        $fields = $request->validate([
            "status" => 'sometimes|in:pending,approved,rejected',
            "reviewed_by" => "required|exists:users,id",
        ]);

        $leaveRequest->update($fields);

        return response()->json(['message' => 'Leave request updated successfully', 'data' => $leaveRequest], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return response()->json(['message' => 'Leave request deleted successfully'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function updateAllocation(Request $request, User $user, LeaveType $leaveType)
    {
        $fields = $request->validate([
            'allocation' => 'required|integer|min:0',
            'allocation_mode' => 'required|in:monthly,yearly',
        ]);

        // Ensure the leave type belongs to the user
        if ($leaveType->user_id !== $user->id) {
            return response()->json(['message' => 'Leave type does not belong to the specified user'], 403);
        }

        $leaveType->update($fields);

        return response()->json([
            'message' => 'Leave type updated successfully',
            'data' => $leaveType,
        ]);
    }

    public function incrementUse(User $user, LeaveType $leaveType)
    {
        if ($leaveType->user_id !== $user->id) {
            return response()->json(['message' => 'Leave type does not belong to the specified user'], 403);
        }

        // Increment the 'used' field by 1
        $leaveType->increment('used');

        return response()->json([
            'message' => 'Leave type usage incremented successfully',
            'data' => $leaveType,
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $leaveTypes = $user->leaveTypes; // Assuming a user has many leave types

        return response()->json($leaveTypes, 200);
    }
}

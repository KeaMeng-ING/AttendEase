<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_id' => 'required|integer|unique:users',
            'department' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'role' => 'nullable|string|in:employee,manager,admin|max:50',
        ]);

        $user = User::create($fields);

        $token = $user->createToken($request->first_name);

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'login' => 'required|max:255', // This will accept either email or employee ID
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('employee_id', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                "message" => "The provided credentials are incorrect.",
            ];
        }

        $token = $user->createToken($user->email);

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'You are Logged out',
        ], 200);
    }
}

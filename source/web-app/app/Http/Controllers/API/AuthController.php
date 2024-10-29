<?php

namespace App\Http\Controllers\API;

use App\Models\MobileUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:mobile_users',
                'password' => 'required|string|min:8',
                'phone_number' => 'nullable|string|max:15',
                'gender' => 'nullable|string',
                'dob' => 'required|date',
                'blood_type' => 'nullable|string',
                'organ' => 'nullable|string',
                'allergies' => 'nullable|string',
            ]);

            // Create mobile user
            $mobileUser = MobileUser::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => $request->password,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'blood_type' => $request->blood_type,
                'organ' => $request->organ,
                'allergies' => $request->allergies,
                'status' => 'pending',
            ]);

            // Return success response
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $mobileUser,
            ], 201);

        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    public function login(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
    
        // Attempt to find the user by email
        $user = MobileUser::where('email', $request->email)->first();
    
        // Check if the user exists and the plain-text password matches
        if ($user && $request->password === $user->password) {
            // Check if user status is approved
            if ($user->status !== 'approved') {
                return response()->json([
                    'message' => 'Your account is pending approval',
                    'status' => 'pending',
                ], 200);
            }
    
            // Password matches and user is approved; return success with selected user details
            return response()->json([
                'message' => 'Login successful',
                'status' => 'approved',
                'data' => [
                    'id' => $user->id, // Include the user ID in the response
                    'email' => $user->email, // Include email
                    'full_name' => $user->full_name, // Include full name
                ],
            ], 200);
        }
    
        // Invalid credentials
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
   
}

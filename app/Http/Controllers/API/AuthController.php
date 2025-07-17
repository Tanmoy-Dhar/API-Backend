<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|min:3|max:60',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'error' => $validateUser->errors()->all()
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => "$user->name! You registered successfully",
            'data' => $user
        ], 200);
    }
    public function login(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Validation failed',
                    'error' => $validateUser->errors()->all()
                ]
            );
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            return response()->json(
                [
                    'status' => true,
                    'message' => "$user->name! You logged in successfully",
                    'token' => $user->createToken('API Token')->plainTextToken,
                    'token_type' => 'Bearer',
                    // 'data' => $user
                ],
                200
            );
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
                'error' => 'The provided credentials do not match our records.'
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        $user = request()->user();
        $user->tokens()->delete(); // Revoke all tokens for the user
        return response()->json(
            [
                'status' => true,
                'message' => 'You have been logged out successfully',
            ], 200);
    }
}

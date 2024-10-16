<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens; // Make sure this is included
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['logout']);
    }

    public function login(LoginRequest $request)
    {


        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credentials are incorrect'], 401);
        }

        // Create a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token in the response
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout()
    {
        $this->middleware(['auth:sanctum']);
        // Revoke the token
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out successfully',
        ], status: 200);
    }

    public function register(RegisterRequest $request)
    {
        // Create a new user instance
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Hash the password

        // Save the user to the database
        $user->save();

        // Create a token for the user using Sanctum
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Return a success response with user data and the token
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }
}

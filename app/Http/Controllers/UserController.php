<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PhpParser\Node\Stmt\Return_;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['user', 'index', 'update', 'destroy','show']);
    }
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id); // Get the authenticated user

        $auth_user = $request->user();

        // Retrieve all users from the database
        if ($auth_user->role != 'admin' && $auth_user->id != $user->id) {
            return response()->json(['message' => 'You are not authorized'], 403); // Unauthorized response
        }

        
        // Return the users and authenticated user data as JSON
        return response()->json([
            'data' => $user,
        ], 200);
    }

    public function index(Request $request)
    {
        // Retrieve the authenticated user's data
        $auth_user = $request->user(); // Get the authenticated user

        if ($auth_user->role != 'admin') {
            return 'You are not authorized';
        }


        // Retrieve all users from the database
        $users = User::paginate(10); 
        $data = [
            'auth_user' => $auth_user,
            'users' => $users
        ];
        // Return the users and authenticated user data as JSON
        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Retrieve the authenticated user's data
        $auth_user = $request->user();

        // Find the user to update
        $user = User::findOrFail($id);

        // Check if the authenticated user is an admin or the user being updated
        if ($auth_user->role != 'admin' && $auth_user->id != $user->id) {
            return response()->json(['message' => 'You are not authorized'], 403); // Unauthorized response
        }

        // Validate the incoming request data (optional but recommended)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ensure unique email except for the current user
            // Add other fields as needed
        ]);

        // Update the user's details
        $user->update($validatedData);

        // Return a success response
        return response()->json([
            'message' => 'User updated successfully.',
            'data' => $user
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        // Logic for deleting a user
        $auth_user = $request->user();

        // Find the user by ID
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Check authorization
        if ($auth_user->role != 'admin' && $auth_user->id != $user->id) {
            return response()->json(['message' => 'You are not authorized.'], 403);
        }

        $user->delete(); // Delete user
        return response()->json(['message' => 'User deleted successfully.'], 200);
    }
}

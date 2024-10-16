<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Requests\UserAddressRequest;
use App\Models\User;
use PhpParser\Node\Stmt\Return_;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['user', 'index', 'update', 'destroy', 'show',]);
    }

    public function index(Request $request)
    {
        // Retrieve the authenticated user's data
        $auth_user = $request->user(); // Get the authenticated user

        // Check if the authenticated user is an admin
        if ($auth_user->role !== 'admin') {
            return response()->json(['message' => 'You are not authorized'], 403); // Return 403 Forbidden
        }

        // Retrieve all user addresses from the database
        $addresses = UserAddress::with('user')->paginate(10); // Eager load user relationship

        // Return the addresses and authenticated user data as JSON
        return response()->json([
            'data' => [
                'auth_user' => $auth_user,
                'addresses' => $addresses,
            ],
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserAddressRequest $request)
    {
        // If the validation passes, create the address
        $address = new UserAddress();
        $address->user_id = $request->user_id;
        $address->address_line = $request->address_line;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;
        $address->save();

        return response()->json(['message' => 'Address created successfully!'], 201);
    }

    public function update(UserAddressRequest $request, $id)
    {
        // Find the address by ID
        $address = UserAddress::findOrFail($id); // This will throw a 404 if not found

        // Update the address fields
        $address->user_id = $request->user_id;
        $address->address_line = $request->address_line;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;
        $address->save();

        return response()->json(['message' => 'Address updated successfully!'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAddress $userAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAddress $userAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the address by ID
        $address = UserAddress::findOrFail($id); // This will throw a 404 if not found

        // Delete the address
        $address->delete();

        return response()->json(['message' => 'Address deleted successfully!'], 200);
    }
}

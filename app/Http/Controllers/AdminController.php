<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'sometimes|string|in:user,admin', // Validate the role field if provided
        ]);

        // Ensure that the role is explicitly set to 'user' unless the current admin intentionally sets it to 'admin'
        $role = $request->has('role') ? $request->role : 'user';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role, // Explicitly set the role, default to 'user' if not provided
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'user'=> $user,
            'message'=>'the user had succusfuly been found'
        ],200);
    }

    public function update(Request $request, $id)
{
    // Find the user by ID
    $user = User::findOrFail($id);

    // Validate the request data
    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
        'password' => 'sometimes|string|min:6',
        'role' => 'sometimes|string|in:user,admin', // Validate the role field if provided
    ]);

    // Prepare the data to update
    $data = $request->only(['name', 'email', 'role']);
    
    // Handle the password separately to hash it if it's provided
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // Update the user with the validated data
    $user->update($data);

    return response()->json([
        'user' => $user,
        'message' => 'The user has been successfully updated'
    ], 200);
}
/**
 * Summary of destroy
 * @param mixed $id id of the user had to be deleted
 * @return mixed|\Illuminate\Http\JsonResponse response json of deleting the user
 */
public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message'=>'The user had succusfuly been deleted'], 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return User::all();
    }

    // Get single user
    public function show(User $user)
    {
        return $user;
    }

    // Create new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json($user, 201);
    }

    // Update user
    public function update(Request $request, $id)
    {
        // Kiểm tra sự tồn tại của user
        $user = User::findOrFail($id);

        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|min:6'
        ]);

        // Cập nhật
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] 
                ? Hash::make($validated['password']) 
                : $user->password
        ]);

        return response()->json($user);
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}

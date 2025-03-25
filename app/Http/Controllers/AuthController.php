<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return response()->json($user, 201);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Tạo token Sanctum và trả về
        $user = Auth::user();
        $token = $user->createToken('sanctum_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout failed'], 500);
        }
    }

    public function user()
    {
        return response()->json(Auth::user());
    }
    public function getUserProfile($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
    }
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
            ]);

            $user->update($validated);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi cập nhật thông tin'], 500);
        }
    }
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        try {
            $validated = $request->validate([
                'currentPassword' => 'required',
                'newPassword' => 'required|string|min:8|confirmed',
            ]);
    
            if (!Hash::check($validated['currentPassword'], $user->password)) {
                return response()->json(['message' => 'Mật khẩu hiện tại không chính xác'], 422);
            }
    
            $user->update([
                'password' => Hash::make($validated['newPassword']),
            ]);
    
            return response()->json(['message' => 'Cập nhật mật khẩu thành công']);
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi cập nhật mật khẩu'], 500);
        }
    }
    
}

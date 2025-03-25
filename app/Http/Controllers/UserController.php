<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Chỉ cho phép người dùng đã xác thực
    }

    public function index()
    {
        $users = User::select('id', 'name', 'email', 'avatar', 'phone', 'role', 'created_at')->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $authUser = auth()->user();

        if ($request->has('role') && $authUser->role !== 'admin') {
            return response()->json(['message' => 'Bạn không có quyền thay đổi vai trò'], 403);
        }

        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'phone'  => 'nullable|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'role'   => 'in:admin,user'
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation error in update: ' . json_encode($e->errors()));
            return response()->json(['errors' => $e->errors()], 422);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');

            // Xóa ảnh cũ nếu có (nếu lưu đường dẫn tương đối trong DB sẽ đơn giản hơn)
            if ($user->avatar) {
                Storage::disk('public')->delete(str_replace(asset('storage/'), '', $user->avatar));
            }

            $validated['avatar'] = asset('storage/' . $path);
        }

        $user->update($validated);
        $updatedUser = $user->fresh();

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $updatedUser->toArray()
        ]);
    }


    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Người dùng đã bị xóa!']);
    }
}

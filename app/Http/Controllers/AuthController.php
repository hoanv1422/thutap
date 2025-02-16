<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Jobs\SendEmailJob; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    //
     // Đăng ký tài khoản
     public function register(Request $request)
     {
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8',
         ]);
 
         // Tạo tài khoản
         $user = User::create([
             'name' => $validated['name'],
             'email' => $validated['email'],
             'password' => Hash::make($validated['password']),
         ]);
 
         // Gửi email xác nhận (qua Queue)
         SendEmailJob::dispatch($user->email);
 
         return response()->json([
             'message' => 'Đăng ký thành công! Kiểm tra email để xác nhận tài khoản.',
             'token' => $user->createToken('auth_token')->plainTextToken,
         ], 201);
     }
 
     // Đăng nhập tài khoản
     public function login(Request $request)
     {
         $credentials = $request->validate([
             'email' => 'required|email',
             'password' => 'required',
         ]);
 
         if (!Auth::attempt($credentials)) {
             return response()->json(['message' => 'Sai email hoặc mật khẩu'], 401);
         }
 
         $user = Auth::user();
         return response()->json([
             'message' => 'Đăng nhập thành công',
             'token' => $user->createToken('auth_token')->plainTextToken,
         ]);
     }
 
     // Đăng xuất tài khoản
     public function logout(Request $request)
     {
         $request->user()->tokens()->delete();
         return response()->json(['message' => 'Đăng xuất thành công']);
     }
}

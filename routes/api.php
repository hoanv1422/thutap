<?php

use App\Models\User;
use App\Events\TestEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Các route auth (đăng nhập, đăng ký)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

// Các route được bảo vệ bởi Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Thông tin người dùng hiện tại
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/user/profile/{id}', [AuthController::class, 'getUserProfile']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Quản lý bài viết
    Route::apiResource('posts', PostController::class);

    // Quản lý công việc
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);

    // Quản lý người dùng:
    // Các route này dành cho quản trị viên hoặc cho mục đích quản lý người dùng trong hệ thống.
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::put('user/password', [AuthController::class, 'updatePassword']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAllAsRead']);
});
Broadcast::routes(['middleware' => ['auth:sanctum']]);

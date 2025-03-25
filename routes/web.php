<?php

use App\Events\TestEvent;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Route test
Route::get('/test-broadcast', function () {
    event(new TestEvent('Xin chào từ server!'));
    return 'Đã bắn sự kiện TestEvent lên kênh test-channel.';
});

// Fallback cho Vue SPA
Route::get('{any}', function () {
    return view('index');
})->where('any', '.*');
Route::get('/', function () {
    return view('app'); 
});


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'viewLogin')->name('login')->middleware('guest');
    Route::post('/login', 'login');
    Route::get('/register', 'viewRegister')->name('register')->middleware('guest');
    Route::post('/register', 'register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'viewDashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::controller(ValidationController::class)->group(function () {
    Route::get('/verify-code/{userId}', 'viewVerifyCode')->name('verify-code')->middleware('signed');
    Route::post('/verify-code/{userId}', 'verifyCode');
});

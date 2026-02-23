<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('login', [AuthController::class, 'sendOtp']);
Route::post('verifyotp', [AuthController::class, 'verifyOtp']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user-details', [AuthController::class, 'userDetails']);
    Route::post('logout', [AuthController::class, 'logout']);
});

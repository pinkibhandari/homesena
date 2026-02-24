<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;

// Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('login', [AuthController::class, 'sendOtp']);
Route::post('verifyotp', [AuthController::class, 'verifyOtp']);
Route::get('services', [ServiceController::class, 'getServices']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user-details', [AuthController::class, 'userDetails']);
    Route::post('save-address', [AuthController::class, 'saveAddress']);
    Route::get('address-list', [AuthController::class, 'addressList']);
   

});

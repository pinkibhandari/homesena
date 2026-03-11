<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\PaymentController;

// Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('login', [AuthController::class, 'sendOtp']);
Route::post('verifyotp', [AuthController::class, 'verifyOtp']);
// service route
Route::get('services', [ServiceController::class, 'getServices']);
Route::get('services/{id}', [ServiceController::class, 'getServiceById']);
Route::post('service/create',[ServiceController::class, 'create']);
// athenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    // user details and address
    Route::get('user-details', [AuthController::class, 'userDetails']);
    Route::post('save-address', [AuthController::class, 'saveAddress']);
    Route::get('address-list', [AuthController::class, 'addressList']);
    // boonking route
    Route::post('/booking/create', [BookingController::class, 'store']);
    Route::post('/slots/{id}/accept', [BookingController::class, 'accept']); // Expert accepts a specific slot
    Route::get('/booking/user', [BookingController::class, 'getUserBookings']);
    Route::get('/booking/{id}', [BookingController::class, 'getBookingById']);
    Route::put('/slots/{id}/cancel', [BookingController::class, 'cancelBookingSlots']);
    // Route::put('/booking/{id}/cancel', [BookingController::class, 'cancelBookingSlot']);
    Route::put('/slots/{id}/reschedule', [BookingController::class, 'rescheduleBookingSlots']);
    Route::post('/slots/{id}/confirmOTP', [BookingController::class, 'confirmOtp']);
    //location route
    Route::get('/location/available-services', [LocationController::class, 'nearbyServices']);  
    Route::post('/location/update', [LocationController::class, 'updateLocation']); // user update location
    Route::post('/location/expert-update', [LocationController::class, 'expertUpdateLocation']); // expert update location
    Route::get('/location/expert-tracking/{slotId}', [LocationController::class, 'expertTracking']);
    // rating and review
    Route::post('/rating/slot/{id}', [ReviewController::class, 'submitReview']);
    Route::get('/rating/user', [ReviewController::class, 'getUserGivenReviews']);  
    //payment route
    Route::get('/payment/methods',[PaymentController::class, 'paymentMethods']);
    Route::post('/payment/pay',[PaymentController::class, 'initiatePayment']);
    Route::post('/payment/verify',[PaymentController::class, 'initiatePayment']);
    
    

   
});
   

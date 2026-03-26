<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\ServiceController;
use App\Http\Controllers\Api\User\BookingController;
use App\Http\Controllers\Api\User\LocationController;
use App\Http\Controllers\Api\User\ReviewController;
use App\Http\Controllers\Api\User\PaymentController;
use App\Http\Controllers\Api\Expert\ExpertController;
use App\Http\Controllers\Api\User\ContactController;
use App\Http\Controllers\Api\User\UserHomeController;
use App\Http\Controllers\Api\User\CmsPageController;

// Route::post('send-otp', [AuthController::class, 'sendOtp']);

Route::post('login', [AuthController::class, 'sendOtp']);
Route::post('verifyotp', [AuthController::class, 'verifyOtp']);
Route::post('resend-otp', [AuthController::class, 'resendOtp']);
 // CmsPage
Route::get('/cms/{slug}', [CmsPageController::class, 'getCmsPage']);

// service route
Route::get('services', [ServiceController::class, 'getServices']);
Route::get('services/{id}', [ServiceController::class, 'getServiceById']);
// Route::post('service/create',[ServiceController::class, 'create']);
// athenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('delete-account', [AuthController::class, 'deleteAccount']);
    // user details and address
    Route::post('profile', [UserController::class, 'profile']);
    Route::get('user-details', [UserController::class, 'userDetails']);
    Route::post('save-address', [UserController::class, 'saveAddress']);
    Route::post('update-address/{id}', [UserController::class, 'updateAddress']);
    Route::get('address-list', [UserController::class, 'addressList']);
    Route::get('user-home', [UserHomeController::class, 'userHome']);
   
    
    // boonking route
    Route::post('/booking/create', [BookingController::class, 'createBooking']);
    Route::post('/slots/{id}/accept', [BookingController::class, 'accept']); // Expert accepts a specific slot
    Route::get('/bookings', [BookingController::class, 'getUserBookings']);
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
    // contact-us
    Route::post('contact-us',[ContactController::class, 'store']);

    /*-------------  expert api ---------------------------------------**/
     Route::middleware('role:expert')->prefix('expert')->group(function(){
        Route::post('details',[ExpertController::class,'storeDetails']);
        Route::post('emergency-contacts',[ExpertController::class,'saveEmergencyContacts']);
        

    });
   
    

   
});
   

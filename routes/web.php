<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ExpertController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ServiceVariantController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\CmsPagesController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\HomePromotionController;
use App\Http\Controllers\Admin\InstantBookingController;
use App\Http\Controllers\Frontend\FrontendController;


/****************** Frontend Routes ******************/
Route::get('/', function () {
    return view('frontend.pages.home');
});



/*****        Admin Routes *************/
Route::get('admin/login', function () {
    // If already logged in AND admin
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    return view('admin.login');
})->name('admin.login');
Route::post('admin/login', [AuthController::class, 'adminLogin']);
/* forgot password  */
Route::get('/admin/forgot-password', [AuthController::class, 'showForgot'])->name('admin.forgot');
Route::post('/admin/otp', [AuthController::class, 'sendOtp'])->name('admin.sendOtp');
Route::get('/admin/otp', [AuthController::class, 'showOtp'])->name('admin.otp');
Route::post('/admin/verify-otp', [AuthController::class, 'verifyOtp'])->name('admin.verifyOtp');
Route::get('/admin/reset-password', [AuthController::class, 'showResetPassword'])->name('admin.resetPassword');
Route::post('/admin/reset-password', [AuthController::class, 'resetPassword'])->name('admin.resetPasswordSubmit');
/*  ----------------- admin   ---------------------*/
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Change Password Routes
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change_password');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('update_password');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Users 
    Route::resource('users', UserController::class);
    Route::delete('/admin/device/{id}', [UserController::class, 'deleteDevice'])->name('device.delete');
    Route::resource('experts', ExpertController::class);
    Route::post('update-approve-status', [ExpertController::class, 'updateApproveStatus']);
    // Route::get('expert/view/{id}', [ExpertController::class, 'view'])->name('view.expert');
    Route::resource('services', ServiceController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('instant_bookings', InstantBookingController::class);
    Route::resource('training_centers', TrainingController::class);
    Route::resource('service_variants', ServiceVariantController::class);
    Route::resource('time_slots', TimeSlotController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('cms_pages', CmsPagesController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('home_promotion', HomePromotionController::class);
    // Route::get('/users', [UserController::class, 'index'])
    //     ->name('admin.users.index');
    // Route::get('/users/create', [UserController::class, 'create'])
    //     ->name('admin.users.create');
    // Route::get('/users/edit', [UserController::class, 'edit'])
    //     ->name('admin.users.edit');
    // Route::delete('admin/users/{id}', [UserController::class, 'destroy'])
    //     ->name('admin.users.destroy');

    // Services
    // Route::get('/services', [ServiceController::class, 'index'])
    //     ->name('admin.services.index');

    // Route::get('/services/create', [ServiceController::class, 'create'])
    //     ->name('admin.services.create');

    // Route::get('/services/edit', [ServiceController::class, 'edit'])
    //     ->name('admin.services.edit');

    // Bookings
    // Route::get('/booking', [BookingController::class, 'index'])
    //     ->name('admin.bookings.index');
    // Route::get('/bookings/create', [BookingController::class, 'create'])
    //     ->name('admin.bookings.create');
    // Route::get('/bookings/edit', [BookingController::class, 'edit'])
    //     ->name('admin.bookings.edit');
    // Experts
    // Route::get('/experts', [ExpertController::class, 'index'])
    //     ->name('admin.experts.index');
    // Route::get('/experts/create', [ExpertController::class, 'create'])
    //     ->name('admin.experts.create');
    // Route::get('/experts/edit', [ExpertController::class, 'edit'])
    //     ->name('admin.experts.edit');
    // Training Centers
    // Route::get('/training_centers', [TrainingController::class, 'index'])
    //     ->name('admin.training_centers.index');
    // Route::get('/training_centers/create', [TrainingController::class, 'create'])
    //     ->name('admin.training_centers.create');
    // Route::get('/training_centers/edit', [TrainingController::class, 'edit'])
    //     ->name('admin.training_centers.edit');
    // Payment
    // Route::get('/payments', [PaymentController::class, 'index'])
    //     ->name('admin.payments.index');
    // Route::get('/payments/create', [PaymentController::class, 'create'])
    //     ->name('admin.payments.create');
    // Route::get('/payments/edit', [PaymentController::class, 'edit'])
    //     ->name('admin.payments.edit');
    // // payment method
    // Route::get('/payments/payment-methods', [PaymentController::class, 'paymentMethods'])
    // ->name('admin.payments.payment_methods');
    // Route::get('/payments/create-payment-methods', [PaymentController::class, 'createPaymentMethods'])
    // ->name('admin.payments.create_payment_methods');
    //  Route::get('/payments/edit-payment-methods', [PaymentController::class, 'editPaymentMethods'])
    // ->name('admin.payments.edit_payment_methods');

});
Route::get('page/{slug}', [FrontendController::class, 'page'])->name('page');

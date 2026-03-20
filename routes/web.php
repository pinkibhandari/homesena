<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ExpertController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\PaymentController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Users 
    Route::resource('users', UserController::class);
    Route::delete('/admin/device/{id}', [UserController::class, 'deleteDevice'])
         ->name('device.delete');
    Route::resource('experts', ExpertController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('training_centers', TrainingController::class);
    Route::resource('payments', PaymentController::class);
   
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

<?php

use App\Http\Controllers\Api\{AuthController, DriverController, OtpController, CarController, RequestController};
use Illuminate\Support\Facades\Route;

/* Customer Authentication Routes Starts Here*/
Route::post('login', [AuthController::class, 'loginCustomer'])->name('auth.customer.login');
Route::post('register', [AuthController::class, 'registerCustomer'])->name('auth.customer.register');
Route::post('logout', [AuthController::class, 'logoutCustomer'])->name('auth.customer.logout');
/* Customer Authentication Routes Ends Here*/

/* Driver Authentication Routes Starts Here */
Route::group(['prefix' => 'driver'], static function(){
    Route::post('register', [DriverController::class, 'registerDriver'])->name('auth.driver.register');
    Route::post('login', [DriverController::class, 'driverLoginWithOtp'])->name('auth.driver.login');
    Route::post('car/register', [CarController::class, 'registerCar'])->middleware('auth:drivers');
    Route::group(['prefix' => 'otp'], static function(){
        Route::post('generate', [OtpController::class, 'otpGenerate'])->name('otp.generate');
    });
});
/* Driver Authentication Routes Ends Here */

/* Request for Ride Routes*/
Route::group(['prefix' => 'request'], static function(){
    Route::post('submit', [RequestController::class, 'submit'])->middleware('auth:users');
    Route::post('accept', [RequestController::class, 'accept'])->middleware('auth:drivers');
});



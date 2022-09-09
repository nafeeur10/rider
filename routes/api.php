<?php

use App\Http\Controllers\Api\{AuthController, DriverController};
use Illuminate\Support\Facades\Route;

/* Customer Authentication Routes Starts Here*/
Route::post('login', [AuthController::class, 'loginCustomer'])->name('auth.customer.login');
Route::post('register', [AuthController::class, 'registerCustomer'])->name('auth.customer.register');
Route::post('logout', [AuthController::class, 'logoutCustomer'])->name('auth.customer.logout');
/* Customer Authentication Routes Ends Here*/

/* Driver Authentication Routes Starts Here */
Route::post('driver/register', [DriverController::class, 'registerDriver'])->name('auth.driver.register');
/* Driver Authentication Routes Ends Here */

<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Authentication Routes Starts Here*/
//Route::middleware('api')->group(static function (): void {
//    Route::post('me', [AuthController::class, 'login'])->name('auth.login');
//});
Route::post('login', [AuthController::class, 'loginCustomer'])->name('auth.customer.login');
Route::post('register', [AuthController::class, 'registerCustomer'])->name('auth.customer.register');
Route::post('logout', [AuthController::class, 'logoutCustomer'])->name('auth.customer.logout');
//Route::post('/auth/register', [AuthController::class, 'createUser']);
//Route::post('/auth/login', [AuthController::class, 'loginUser']);

/* Authentication Routes Ends Here*/

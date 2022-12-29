<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserAuth;
use App\Http\Middleware\VerifyUser;
use App\Http\Requests\EmailVerificationRequest as RequestsEmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::prefix('user')->controller(UserController::class)->group(function () {
                 // Signup Route 
    Route::post('/register', [UserController::class,'add']);
                // Email Verification Route 
    Route::get('/verfiy/{hash}',[UserController::class,'verfiyEmail'])->name("verfiy");
                // Login Route 
    Route::post('/login', [UserController::class,'login']);
                //  Forgot Password Route 
    Route::post('/forgot', [UserController::class,'forgot'])->name('forgot');
                // Forgot Password Link Route 
    Route::get('/reset-password/{token}', function ($token) {
        return response()->pfResponce($token,"change password now with this token ",200);
    })->name('password.reset');
                //  Reset Password Route 
    Route::post('/resetpassword', [UserController::class,'resetPassword'])->name('password.update');
                //   Update Profile Route 
    Route::post('/update', [UserController::class,'update'])->middleware(VerifyUser::class)->name('user.update');
                // Profile View Route 
    Route::get('/profile', [UserController::class,'profile'])->middleware(VerifyUser::class)->name('profile');
                // Logout Route 
    Route::post('/logout', [UserController::class,'logout'])->middleware(VerifyUser::class)->name('user.logout');
});



















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
    Route::post('/register', [UserController::class,'add']);
    Route::get('/verfiy/{hash}',[UserController::class,'verfiyEmail'])->name("verfiy");
    Route::post('/login', [UserController::class,'login']);
    Route::post('/forgot', [UserController::class,'forgot'])->name('forgot');
    Route::get('/reset-password/{token}', function ($token) {

        return response()->pfResponce($token,"change password now with this token ",200);
    })->name('password.reset');
    Route::post('/resetpassword', [UserController::class,'restPassword'])->name('password.update');
    Route::post('/update', [UserController::class,'update'])->middleware(VerifyUser::class)->name('user.update');
    Route::post('/logout', [UserController::class,'logout'])->middleware(VerifyUser::class)->name('user.logout');
    Route::get('/profile', [UserController::class,'profile'])->middleware(VerifyUser::class)->name('profile');
});



















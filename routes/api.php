<?php

use App\Http\Controllers\V1\Api\GoogleMapController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\Auth\RegisterController;
use App\Http\Controllers\V1\Api\CommonController;
use App\Http\Controllers\V1\Api\SampleController;
use App\Http\Controllers\V1\Api\TeamScheduleController;
use App\Http\Controllers\V1\Api\TeamStadiumController;
use App\Http\Controllers\V1\Api\TeamMatchingSettingController;
use App\Http\Controllers\V1\Api\ConversationController;
use App\Http\Controllers\V1\Api\MessageController;

Route::group(['middleware' => ['localization', 'cors']], function () {

    Route::group(['prefix' => 'v1'], function () {

        Route::group(['prefix' => 'auth'], function () {

            Route::post('/register', [RegisterController::class, 'register'])->name('API_001');

            Route::post('/login', [AuthController::class, 'login'])->name('API_002');

            Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('API_003');

            Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('API_004');

            Route::group(['prefix' => 'password'], function () {

                Route::post('/forgot', [ForgotPasswordController::class, 'forgot'])->name('API_005');

                Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('API_006');

                Route::post('/reset', [ForgotPasswordController::class, 'reset'])->name('API_007');
            });

            Route::group(['middleware' => ['auth:api', 'auth.active']], function () {

                Route::get('/me', [AuthController::class, 'me'])->name('API_008');

                Route::post('/logout', [AuthController::class, 'logout'])->name('API_009');

                Route::post('/refresh', [AuthController::class, 'refresh'])->name('API_010');

                // Route::post('/update', [AuthController::class, 'update'])->name('API_011');

                Route::post('/change-password', [AuthController::class, 'changePassword'])->name('API_012');

                Route::post('/delete-account', [AuthController::class, 'deleteAccount'])->name('API_013');
            });
        });

        Route::group(['middleware' => ['auth:api', 'auth.active']], function () {
        });
    });
});

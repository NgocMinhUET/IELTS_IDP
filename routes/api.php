<?php

use App\Http\Controllers\V1\SkillAnswerController;
use App\Http\Controllers\V1\SkillController;
use App\Http\Controllers\V1\TestController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\Auth\RegisterController;

Route::group(['middleware' => ['localization', 'cors']], function () {

    Route::group(['prefix' => 'v1'], function () {

        Route::group(['prefix' => 'auth'], function () {

            Route::post('/register', [RegisterController::class, 'register'])->name('API_001');

            Route::post('/login', [AuthController::class, 'login'])->name('API_002');

            Route::get('/verify', [RegisterController::class, 'verify'])->name('API_003');

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
            Route::group(['prefix' => 'tests'], function () {
                Route::get('/', [TestController::class, 'getTests']);
                Route::post('enroll', [TestController::class, 'enrollTest']);
            });
            Route::get('/skills', [SkillController::class, 'getSkillForExam']);
            Route::get('/questions', [SkillController::class, 'getQuestions']);
            Route::post('/answers', [SkillAnswerController::class, 'submitAnswer']);
        });
    });
});

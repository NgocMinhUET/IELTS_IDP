<?php

use App\Http\Controllers\CMS\ExamController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/policy', function () {
    return view('policy');
});

Route::get('/terms_of_use', function () {
    return view('terms_of_use');
});

Route::get('/login', function () {
    return view('auth.sign_in');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => []], function () {
    Route::group(['prefix' => 'exams', 'as' => 'exams.'], function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/create', [ExamController::class, 'create'])->name('create');
        Route::get('/store', [ExamController::class, 'store'])->name('store');
        Route::get('/{id}', [ExamController::class, 'detail'])->name('detail');
    });
});

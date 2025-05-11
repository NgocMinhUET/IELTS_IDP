<?php

use App\Http\Controllers\CMS\AuthController;
use App\Http\Controllers\CMS\ExamController;
use App\Http\Controllers\CMS\FillInContentQuestionController;
use App\Http\Controllers\CMS\FillInImageQuestionController;
use App\Http\Controllers\CMS\MediaController;
use App\Http\Controllers\CMS\ParagraphController;
use App\Http\Controllers\CMS\PartController;
use App\Http\Controllers\CMS\QuestionController;
use App\Http\Controllers\CMS\SkillController;
use App\Http\Controllers\CMS\StudentController;
use App\Http\Controllers\CMS\TeacherController;
use App\Http\Controllers\CMS\TestController;
use App\Http\Controllers\CMS\WritingQuestionController;
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

Route::get('/policy', function () {
    return view('policy');
});

Route::get('/terms_of_use', function () {
    return view('terms_of_use');
});



Route::group(['middleware' => ['guest:admin']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
});

Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => []], function () {
        Route::group(['prefix' => 'tests', 'as' => 'tests.'], function () {
            Route::get('/', [TestController::class, 'index'])->name('index');
            Route::get('/create', [TestController::class, 'create'])->name('create');
            Route::post('/store', [TestController::class, 'store'])->name('store');
            Route::get('/{id}', [TestController::class, 'detail'])->name('detail');
            Route::put('/{id}', [TestController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'exams', 'as' => 'exams.'], function () {
            Route::get('/', [ExamController::class, 'index'])->name('index');
            Route::get('/create', [ExamController::class, 'create'])->name('create');
            Route::post('/store', [ExamController::class, 'store'])->name('store');
            Route::patch('/{id}/status', [ExamController::class, 'updateApproveStatus'])
                ->middleware('role.admin')
                ->name('status');
            Route::get('/{id}', [ExamController::class, 'detail'])->name('detail');
            Route::put('/{id}', [ExamController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'skills', 'as' => 'skills.'], function () {
            Route::get('/{id}', [SkillController::class, 'detail'])->name('detail');
            Route::put('/{id}', [SkillController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'parts', 'as' => 'parts.'], function () {
            Route::group(['prefix' => '{id}'], function () {
                Route::get('/', [PartController::class, 'detail'])->name('detail');
                Route::put('/', [PartController::class, 'update'])->name('update');
                Route::group(['prefix' => 'questions', 'as' => 'questions.'], function () {
                    Route::get('/create', [QuestionController::class, 'create'])->name('create');
                    Route::post('/store', [QuestionController::class, 'store'])->name('store');
                    Route::get('/{questionId}', [QuestionController::class, 'detail'])->name('detail');
                    Route::put('/{questionId}', [QuestionController::class, 'update'])->name('update');
                });

                Route::group(['prefix' => 'fic-questions', 'as' => 'fic-questions.'], function () {
                    Route::post('/store', [FillInContentQuestionController::class, 'store'])->name('store');
                    Route::get('/{questionId}', [FillInContentQuestionController::class, 'detail'])->name('detail');
                    Route::put('/{questionId}', [FillInContentQuestionController::class, 'update'])->name('update');
                });

                Route::group(['prefix' => 'fii-questions', 'as' => 'fii-questions.'], function () {
                    Route::post('/store', [FillInImageQuestionController::class, 'store'])->name('store');
                    Route::get('/{questionId}', [FillInImageQuestionController::class, 'detail'])->name('detail');
                    Route::put('/{questionId}', [FillInImageQuestionController::class, 'update'])->name('update');
                });

                Route::group(['prefix' => 'paragraphs', 'as' => 'paragraphs.'], function () {
                    Route::get('/create', [ParagraphController::class, 'create'])->name('create');
                    Route::post('/store', [ParagraphController::class, 'store'])->name('store');
                    Route::get('/{paragraphId}', [ParagraphController::class, 'edit'])->name('edit');
                    Route::put('/{paragraphId}', [ParagraphController::class, 'update'])->name('update');
                });

                Route::group(['prefix' => 'writing-questions', 'as' => 'writing-questions.'], function () {
                    Route::post('/store', [WritingQuestionController::class, 'store'])->name('store');
                    Route::get('/{questionId}', [WritingQuestionController::class, 'edit'])->name('edit');
                    Route::put('/{questionId}', [WritingQuestionController::class, 'update'])->name('update');
                });
            });
        });

        Route::group(['prefix' => 'teachers', 'as' => 'teachers.', 'middleware' => ['role.admin']], function () {
            Route::get('/', [TeacherController::class, 'index'])->name('index');
            Route::get('/create', [TeacherController::class, 'create'])->name('create');
            Route::post('/store', [TeacherController::class, 'store'])->name('store');
            Route::get('/{id}', [TeacherController::class, 'detail'])->name('detail');
            Route::put('/{id}', [TeacherController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/store', [StudentController::class, 'store'])->name('store');
            Route::get('/import', [StudentController::class, 'import'])->name('import');
            Route::post('/import', [StudentController::class, 'executeImport'])->name('import.execute');
            Route::get('/{id}', [StudentController::class, 'detail'])->name('detail');
            Route::put('/{id}', [StudentController::class, 'update'])->name('update');
        });
    });
});

Route::get('/media/private/{media}', [MediaController::class, 'streamPrivate'])
    ->name('media.private')
    ->middleware('signed');

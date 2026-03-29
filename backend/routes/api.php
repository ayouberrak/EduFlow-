<?php

use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\FavoritesController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EnrollmentController;
use App\Http\Controllers\API\TeacherStatisticsController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('forgot-password', [PasswordResetController::class, 'forgot']);
Route::post('reset-password', [PasswordResetController::class, 'reset']);

Route::middleware('auth:api')->group(function () {


    Route::middleware('student')->group(function () {

        Route::prefix('favorites')->group(function () {
            Route::get('/', [FavoritesController::class, 'index']);
            Route::post('/add/{course_id}', [FavoritesController::class, 'add']);
            Route::post('/remove/{course_id}', [FavoritesController::class, 'remove']);
        });

        Route::prefix('courses')->group(function () {
            Route::get('/', [CourseController::class, 'index']);
            Route::get('/{id}', [CourseController::class, 'show']);

            Route::post('/enroll/{course_id}', [EnrollmentController::class, 'enroll']);
            Route::post('/confirm-enrollment', [EnrollmentController::class, 'confirmEnrollment']);
            Route::delete('/unenroll/{course_id}', [EnrollmentController::class, 'unenroll']);

        });

        Route::get('/recommend-courses', [UserController::class, 'recommendCourses']);
        Route::post('/add-domains', [UserController::class, 'addDomains']);
    });




    Route::middleware('enseignant')->group(function () {
        Route::prefix('courses')->group(function () {
            Route::post('/add', [CourseController::class, 'store']);
            Route::put('/update/{id}', [CourseController::class, 'update']);
            Route::delete('/delete/{id}', [CourseController::class, 'destroy']);


            Route::get('/enrollments/{course_id}', [EnrollmentController::class, 'courseEnrollments']);
        });

        Route::get('/stats', [TeacherStatisticsController::class, 'index']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});
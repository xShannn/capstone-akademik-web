<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

//  Login
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (HARUS LOGIN)
|--------------------------------------------------------------------------
*/

// Grouping utama: Memerlukan otentikasi Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // ==== STUDENTS
    Route::middleware(['role:student'])->group(function () {
        // Semua route di sini memerlukan role 'student'
        Route::get('/students', [StudentController::class, 'index']);
        Route::post('/students', [StudentController::class, 'store']);
        Route::get('/students/{id}', [StudentController::class, 'show']);
        Route::put('/students/{id}', [StudentController::class, 'update']);
        Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    });

    Route::middleware(['role:student'])->group(function () {
        // ==== TEACHERS 
        Route::get('/teacher/profile', [TeacherController::class, 'profile']);
        Route::post('/teacher/update-password', [TeacherController::class, 'updatePassword']);

        Route::get('/teacher/schedule', [TeacherController::class, 'schedule']);
        Route::get('/teacher/classes', [TeacherController::class, 'classes']);
        // Input nilai murid
        Route::post('/teacher/grade', [TeacherController::class, 'submitGrade']);
        // Buat tugas per mata pelajaran
        Route::post('/teacher/task', [TeacherController::class, 'createTask']);
        // Absen — generate daftar murid & centang kehadiran
        Route::post('/teacher/attendance', [TeacherController::class, 'attendance']);
    });

    Route::middleware('role: parent')->group(function () {
        Route::prefix('parent')->group(function () {
            Route::get('profile', [ParentController::class, 'profile']);
            Route::put('profile/update', [ParentController::class, 'updateProfile']);

            Route::get('students', [ParentController::class, 'myChildren']);
            Route::get('students/{id}', [ParentController::class, 'childDetail']);
            // 🔹 Jadwal
            Route::get('students/{id}/schedule', [ParentController::class, 'childSchedule']);

            // 🔹 Panduan SPP
            Route::get('payment-guides', [ParentController::class, 'paymentGuides']);

            // 🔹 Status Pembayaran SPP
            Route::get('students/{id}/spp', [ParentController::class, 'childSppStatus']);
        });
    });
});

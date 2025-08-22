<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseTestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\AttendanceController;

// Firebase test routes
Route::get('/firestore/add', [FirebaseTestController::class, 'addUser']);
Route::get('/firestore/get', [FirebaseTestController::class, 'getUsers']);

// Authentication routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Development route to create default admin (remove in production)
Route::get('/create-admin', [AuthController::class, 'createDefaultAdmin'])->name('create.admin');

// Protected routes
Route::middleware(App\Http\Middleware\FirebaseAuth::class)->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    
    // User Management (Quản lý tài khoản)
    Route::resource('users', UserController::class);
    
    // Subject Management (Quản lý môn học)
    Route::resource('subjects', SubjectController::class);
    
    // ClassRoom Management (Quản lý lớp học phần)
    Route::resource('classrooms', ClassRoomController::class);
    
    // Attendance Management (Lịch học và điểm danh)
    Route::resource('attendances', AttendanceController::class);
    Route::get('/attendances/class/{classId}', [AttendanceController::class, 'getAttendancesByClass'])->name('attendances.by_class');
    Route::get('/attendances/student/{studentId}', [AttendanceController::class, 'getAttendancesByStudent'])->name('attendances.by_student');
    Route::post('/attendance/face-recognition', [AttendanceController::class, 'checkAttendanceByFace'])->name('attendance.face_recognition');
});

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ReportController;

// Authentication routes
Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($credentials['email'] === 'admin@example.com' && $credentials['password'] === 'password') {
        session([
            'user_id' => 'admin',
            'user_role' => 'admin',
            'user_email' => 'admin@example.com',
            'user_name' => 'Administrator'
        ]);
        return redirect('/dashboard')->with('success', 'Đăng nhập thành công!');
    }
    
    return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.']);
})->name('login.post');

Route::post('/logout', function () {
    session()->flush();
    return redirect('/')->with('success', 'Đăng xuất thành công!');
})->name('logout');

// Protected routes - 8 màng hình chính
Route::middleware('web')->group(function () {
    // 1. Dashboard (Tổng quan)
    Route::get('/dashboard', function() {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }
        return app(DashboardController::class)->index();
    })->name('dashboard');
    
    // 2. Quản lý tài khoản
    Route::get('/users', function() {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }
        return app(UserController::class)->index();
    })->name('users.index');
    
    // 3. Quản lý môn học
    Route::get('/subjects', function() {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }
        return app(SubjectController::class)->index();
    })->name('subjects.index');
    
    // 4. Quản lý lớp học phần
    Route::get('/classrooms', function() {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }
        return app(ClassRoomController::class)->index();
    })->name('classrooms.index');
    
    // 5. Lịch học và điểm danh (READ-ONLY)
    Route::get('/attendances', function() {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }
        return app(AttendanceController::class)->index();
    })->name('attendances.index');
    
    // API routes cho attendance
    Route::get('/api/attendances/class/{classId}', function($classId) {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return app(AttendanceController::class)->getAttendancesByClass($classId);
    })->name('api.attendances.by_class');
    
    Route::get('/api/attendances/date/{date}', function($date) {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return app(AttendanceController::class)->getAttendancesByDate($date);
    })->name('api.attendances.by_date');
    
    Route::get('/attendances/export', function() {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }
        return app(AttendanceController::class)->exportAttendances(request());
    })->name('attendances.export');
    
    // 6. Kiểm tra dữ liệu
    Route::get('/data', function() {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }
        return app(DataController::class)->index();
    })->name('data.index');
    
    // 7. Xuất báo cáo
    Route::get('/reports', function() {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }
        return app(ReportController::class)->index();
    })->name('reports.index');
});
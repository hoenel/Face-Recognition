<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
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

// Protected routes
Route::middleware('web')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Subject Management
    Route::resource('subjects', SubjectController::class);
    
    // ClassRoom Management
    Route::resource('classrooms', ClassRoomController::class);
    
    // Attendance Management
    Route::resource('attendances', AttendanceController::class);
    
    // Data Management
    Route::get('/data', [DataController::class, 'index'])->name('data.index');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

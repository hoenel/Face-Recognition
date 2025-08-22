<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\FirebaseTestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ReportController;

// Test route - simple
Route::get('/test', function () {
    return 'Server hoạt động OK! ' . now();
});

// Simple home route without AuthController
Route::get('/', function () {
    return view('auth.login');
});

// Simple login routes without controller
Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($credentials['email'] === 'admin@example.com' && $credentials['password'] === 'password') {
        session(['user_id' => 'admin', 'user_role' => 'admin', 'user_email' => 'admin@example.com']);
        return redirect('/dashboard')->with('success', 'Đăng nhập thành công!');
    }
    
    return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.']);
});

// Firebase test routes
Route::get('/firestore/add', [FirebaseTestController::class, 'addUser']);
Route::get('/firestore/get', [FirebaseTestController::class, 'getUsers']);

// Test Firebase students
Route::get('/test-students', function () {
    $firebaseService = app(App\Services\FirebaseService::class);
    $students = $firebaseService->getAllStudents();
    
    $html = '<h1>🎓 Firebase Students Data</h1>';
    $html .= '<div style="font-family: Arial; padding: 20px;">';
    
    foreach ($students as $student) {
        $html .= '<div style="border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px;">';
        $html .= '<h3>👨‍🎓 ' . $student['name'] . '</h3>';
        $html .= '<p><strong>ID:</strong> ' . $student['student_id'] . '</p>';
        $html .= '<p><strong>Lớp:</strong> ' . $student['class_id'] . '</p>';
        $html .= '<p><strong>Môn học:</strong> ' . $student['course_enrollments'] . '</p>';
        $html .= '<p><strong>Ngày sinh:</strong> ' . $student['date_of_birth'] . '</p>';
        $html .= '<p><strong>Chuyên ngành:</strong> ' . $student['field_of_study'] . '</p>';
        $html .= '<p><strong>Quê quán:</strong> ' . $student['hometown'] . '</p>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html;
});

// Authentication routes - COMMENTED OUT (causing crashes)
// Route::get('/', [AuthController::class, 'showLogin'])->name('login');
// Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
// Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Development route to create default admin (remove in production)
// Route::get('/create-admin', [AuthController::class, 'createDefaultAdmin'])->name('create.admin');

// Demo login route for testing (remove in production)
Route::get('/demo-login', function () {
    session(['user_id' => 'demo_user', 'user_role' => 'admin']);
    return redirect('/dashboard');
})->name('demo-login');

// Simple test login without CSRF
Route::get('/simple-login', function () {
    session(['user_id' => 'firebase_user', 'user_role' => 'admin', 'user_email' => 'admin@firebase.com']);
    return redirect('/dashboard')->with('success', 'Đăng nhập thành công!');
})->name('simple-login');

Route::get('/test-firebase', function () {
    // Test cơ bản trước
    return response()->json([
        'step1' => 'Route works',
        'step2' => 'Testing Firebase service...'
    ]);
})->name('test-firebase');

Route::get('/test-firebase-final', function () {
    try {
        $firebaseService = app(\App\Services\FirebaseService::class);
        
        // Test với timeout ngắn
        $users = $firebaseService->getAllUsers();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Firebase connection successful!',
            'users_count' => count($users),
            'first_user' => $users[0] ?? null,
            'data_source' => (count($users) > 0 && isset($users[0]['email']) && strpos($users[0]['email'], '@') !== false) ? 'Firebase real data' : 'Mock data fallback'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'line' => $e->getLine()
        ]);
    }
})->name('test-firebase-final');

// Protected routes
Route::middleware('firebase.auth')->group(function () {
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
    
    // Data Verification (Kiểm tra dữ liệu)
    Route::get('/data', [DataController::class, 'index'])->name('data.index');
    Route::post('/data/validate', [DataController::class, 'validate'])->name('data.validate');
    Route::post('/data/sync', [DataController::class, 'sync'])->name('data.sync');
    
    // Reports (Xuất báo cáo)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/download/{id}', [ReportController::class, 'download'])->name('reports.download');
});

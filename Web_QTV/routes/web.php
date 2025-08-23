<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Simple Login Routes
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Simple admin login check
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

// Dashboard
Route::get('/dashboard', function() {
    if (!session('user_id')) {
        return redirect('/')->with('error', 'Vui lòng đăng nhập!');
    }
    
    $stats = [
        'students' => 1280,
        'teachers' => 82,
        'classes' => 148,
        'attendance_today' => 1024
    ];
    
    return view('dashboard.index', compact('stats'));
})->name('dashboard');

// Simple management routes
Route::get('/users', function() {
    if (!session('user_id')) {
        return redirect('/')->with('error', 'Vui lòng đăng nhập!');
    }
    
    $users = [
        ['id' => 1, 'name' => 'Nguyễn Văn A', 'email' => 'nguyenvana@example.com', 'role' => 'Sinh viên', 'status' => 'Hoạt động'],
        ['id' => 2, 'name' => 'Trần Thị B', 'email' => 'tranthib@example.com', 'role' => 'Giảng viên', 'status' => 'Đã khóa']
    ];
    
    return view('users.index', compact('users'));
})->name('users.index');

Route::get('/subjects', function() {
    if (!session('user_id')) {
        return redirect('/')->with('error', 'Vui lòng đăng nhập!');
    }
    
    $subjects = [
        ['id' => 1, 'code' => 'CT101', 'name' => 'Lập trình C++', 'credits' => 3, 'department' => 'CNTT'],
        ['id' => 2, 'code' => 'KT202', 'name' => 'Kinh tế học', 'credits' => 2, 'department' => 'Kinh tế']
    ];
    
    return view('subjects.index', compact('subjects'));
})->name('subjects.index');

Route::get('/classrooms', function() {
    if (!session('user_id')) {
        return redirect('/')->with('error', 'Vui lòng đăng nhập!');
    }
    
    $classrooms = [
        ['id' => 1, 'code' => 'LHP01', 'name' => 'Lập trình C++ - Nhóm 1', 'subject' => 'Lập trình C++', 'teacher' => 'Nguyễn Văn A', 'students' => 45],
        ['id' => 2, 'code' => 'LHP02', 'name' => 'Cơ sở dữ liệu - Nhóm 2', 'subject' => 'Cơ sở dữ liệu', 'teacher' => 'Trần Thị B', 'students' => 52]
    ];
    
    return view('classrooms.index', compact('classrooms'));
})->name('classrooms.index');

Route::get('/attendances', function() {
    if (!session('user_id')) {
        return redirect('/')->with('error', 'Vui lòng đăng nhập!');
    }
    
    $attendances = [
        ['id' => 1, 'class' => 'LHP001', 'subject' => 'Lập trình C++', 'date' => '01/08/2025', 'time' => '07:50 - 08:30', 'teacher' => 'Thầy Nguyễn Văn A', 'status' => 'Đã điểm danh'],
        ['id' => 2, 'class' => 'LHP002', 'subject' => 'Kinh tế học', 'date' => '01/08/2025', 'time' => '09:45 - 11:45', 'teacher' => 'Cô Trần Thị B', 'status' => 'Chưa điểm danh'],
        ['id' => 3, 'class' => 'LHP003', 'subject' => 'Toán rời rạc', 'date' => '01/08/2025', 'time' => '13:00 - 15:00', 'teacher' => 'Thầy Lê Văn C', 'status' => 'Đã điểm danh']
    ];
    
    return view('attendances.index', compact('attendances'));
})->name('attendances.index');

Route::get('/students', function() {
    if (!session('user_id')) {
        return redirect('/')->with('error', 'Vui lòng đăng nhập!');
    }
    
    return view('students.index');
})->name('students.index');

Route::get('/reports', function() {
    if (!session('user_id')) {
        return redirect('/')->with('error', 'Vui lòng đăng nhập!');
    }
    
    return view('reports.index');
})->name('reports.index');

// Logout
Route::get('/logout', function() {
    session()->flush();
    return redirect('/')->with('success', 'Đăng xuất thành công!');
})->name('logout');
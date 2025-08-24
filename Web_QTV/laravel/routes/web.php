<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SimpleAuth;

// Trang chủ - điều hướng đến login
Route::get('/', function () {
    if (session('logged_in')) {
        return redirect('/dashboard');
    }
    return view('dang_nhap');
});

// Login routes
Route::get('/login', function () {
    if (session('logged_in')) {
        return redirect('/dashboard');
    }
    return view('dang_nhap');
})->name('login');

Route::post('/login', function () {
    $email = request('email');
    $password = request('password');
    
    // Demo users
    $users = [
        'admin@htd.edu.vn' => ['password' => '123456', 'name' => 'Quản trị viên'],
        'demo@example.com' => ['password' => '123456', 'name' => 'Demo User'],
        'teacher@htd.edu.vn' => ['password' => '123456', 'name' => 'Giảng viên'],
    ];
    
    if (isset($users[$email]) && $users[$email]['password'] === $password) {
        session([
            'logged_in' => true,
            'user_email' => $email,
            'user_name' => $users[$email]['name']
        ]);
        return redirect('/dashboard')->with('success', 'Đăng nhập thành công!');
    }
    
    return back()->with('error', 'Email hoặc mật khẩu không đúng!');
});

// Logout
Route::post('/logout', function () {
    session()->flush();
    return redirect('/login');
})->name('logout');

// Protected routes với middleware SimpleAuth
Route::middleware([SimpleAuth::class])->group(function () {
    
    // Dashboard với dữ liệu test Firebase
    Route::get('/dashboard', function () {
        // Dữ liệu test từ Firebase
        $stats = [
            'total_accounts' => 150,
            'total_subjects' => 25,
            'total_classes' => 45,
            'today_attendance' => 320
        ];
        
        $recent_activities = [
            ['time' => '14:30 - 23/08/2025', 'action' => 'Điểm danh lớp Toán cao cấp 1', 'user' => 'Nguyễn Văn A'],
            ['time' => '13:15 - 23/08/2025', 'action' => 'Tạo lớp học phần mới', 'user' => 'Admin'],
            ['time' => '10:45 - 23/08/2025', 'action' => 'Cập nhật thông tin môn học', 'user' => 'Trần Thị B'],
        ];
        
        return view('trang_chu', compact('stats', 'recent_activities'));
    })->name('dashboard');
    
    // Quản lí tài khoản với dữ liệu test Firebase
    Route::get('/accounts', function () {
        // Dữ liệu test từ Firebase
        $accounts = [
            ['id' => 1, 'name' => 'Nguyễn Văn Admin', 'email' => 'admin@htd.edu.vn', 'role' => 'admin', 'status' => 'active', 'created_at' => '15/08/2025'],
            ['id' => 2, 'name' => 'Trần Thị B', 'email' => 'teacher@htd.edu.vn', 'role' => 'teacher', 'status' => 'active', 'created_at' => '16/08/2025'],
            ['id' => 3, 'name' => 'Phạm Văn C', 'email' => 'demo@example.com', 'role' => 'student', 'status' => 'active', 'created_at' => '17/08/2025'],
            ['id' => 4, 'name' => 'Lê Thị D', 'email' => 'student1@htd.edu.vn', 'role' => 'student', 'status' => 'inactive', 'created_at' => '18/08/2025'],
        ];
        
        return view('quan_ly_tai_khoan', compact('accounts'));
    })->name('accounts.index');
    
    // Quản lí môn học với dữ liệu Firebase thực
    Route::get('/subjects', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        try {
            $subjects = $firebase->getCourses();
            
            // Nếu không có dữ liệu từ Firebase, dùng fallback
            if (empty($subjects)) {
                $subjects = [
                    ['id' => '1', 'code' => 'MATH101', 'name' => 'Toán cao cấp 1', 'credits' => 3, 'department' => 'Khoa Cơ bản', 'teacher' => 'TS. Nguyễn A', 'status' => 'active'],
                    ['id' => '2', 'code' => 'PHYS101', 'name' => 'Vật lý đại cương', 'credits' => 4, 'department' => 'Khoa Cơ bản', 'teacher' => 'PGS. Trần B', 'status' => 'active'],
                    ['id' => '3', 'code' => 'CS201', 'name' => 'Lập trình web', 'credits' => 3, 'department' => 'Khoa CNTT', 'teacher' => 'ThS. Phạm C', 'status' => 'pending'],
                    ['id' => '4', 'code' => 'HYDRO101', 'name' => 'Cơ sở thuỷ lực', 'credits' => 3, 'department' => 'Khoa Thuỷ lợi', 'teacher' => 'Prof. Lê D', 'status' => 'active'],
                    ['id' => '5', 'code' => 'MECH101', 'name' => 'Cơ học kỹ thuật', 'credits' => 4, 'department' => 'Khoa Cơ khí', 'teacher' => 'TS. Hoàng E', 'status' => 'completed'],
                ];
            }
        } catch (\Exception $e) {
            // Fallback data nếu Firebase lỗi
            $subjects = [
                ['id' => '1', 'code' => 'MATH101', 'name' => 'Toán cao cấp 1', 'credits' => 3, 'department' => 'Khoa Cơ bản', 'teacher' => 'TS. Nguyễn A', 'status' => 'active'],
                ['id' => '2', 'code' => 'PHYS101', 'name' => 'Vật lý đại cương', 'credits' => 4, 'department' => 'Khoa Cơ bản', 'teacher' => 'PGS. Trần B', 'status' => 'active'],
                ['id' => '3', 'code' => 'CS201', 'name' => 'Lập trình web', 'credits' => 3, 'department' => 'Khoa CNTT', 'teacher' => 'ThS. Phạm C', 'status' => 'pending'],
                ['id' => '4', 'code' => 'HYDRO101', 'name' => 'Cơ sở thuỷ lực', 'credits' => 3, 'department' => 'Khoa Thuỷ lợi', 'teacher' => 'Prof. Lê D', 'status' => 'active'],
                ['id' => '5', 'code' => 'MECH101', 'name' => 'Cơ học kỹ thuật', 'credits' => 4, 'department' => 'Khoa Cơ khí', 'teacher' => 'TS. Hoàng E', 'status' => 'completed'],
            ];
        }
        
        return view('quan_ly_mon_hoc', compact('subjects'));
    })->name('subjects.index');
    
    // Quản lí lớp học phần với dữ liệu test Firebase
    Route::get('/classes', function () {
        // Dữ liệu test từ Firebase
        $classes = [
            ['code' => 'MATH101-01', 'subject' => 'Toán cao cấp 1', 'teacher' => 'TS. Nguyễn Văn A', 'room' => 'A101', 'schedule' => 'T2,T4,T6 (07:30-09:00)', 'students' => '45/50', 'status' => 'active'],
            ['code' => 'PHYS101-01', 'subject' => 'Vật lý đại cương', 'teacher' => 'PGS. Trần Thị B', 'room' => 'B203', 'schedule' => 'T3,T5 (09:15-10:45)', 'students' => '38/40', 'status' => 'active'],
            ['code' => 'CS201-01', 'subject' => 'Lập trình web', 'teacher' => 'ThS. Phạm Văn C', 'room' => 'C105', 'schedule' => 'T2,T4 (13:30-15:00)', 'students' => '25/30', 'status' => 'pending'],
            ['code' => 'HYDRO101-01', 'subject' => 'Cơ sở thuỷ lực', 'teacher' => 'Prof. Lê Thị D', 'room' => 'D301', 'schedule' => 'T3,T6 (15:15-16:45)', 'students' => '20/25', 'status' => 'active'],
            ['code' => 'MECH101-01', 'subject' => 'Cơ học kỹ thuật', 'teacher' => 'TS. Hoàng Văn E', 'room' => 'E201', 'schedule' => 'T5,T7 (07:30-09:00)', 'students' => '35/40', 'status' => 'completed'],
        ];
        
        return view('quan_ly_lop_hoc_phan', compact('classes'));
    })->name('classes.index');
    
    // Lịch học và điểm danh với dữ liệu test Firebase
    Route::get('/schedules', function () {
        // Dữ liệu test từ Firebase
        $schedules = [
            ['time' => '07:30 - 09:00', 'class_code' => 'MATH101-01', 'subject' => 'Toán cao cấp 1', 'teacher' => 'TS. Nguyễn Văn A', 'room' => 'A101', 'present' => 42, 'total' => 45, 'status' => 'completed'],
            ['time' => '09:15 - 10:45', 'class_code' => 'PHYS101-01', 'subject' => 'Vật lý đại cương', 'teacher' => 'PGS. Trần Thị B', 'room' => 'B203', 'present' => 0, 'total' => 38, 'status' => 'ongoing'],
            ['time' => '13:30 - 15:00', 'class_code' => 'CS201-01', 'subject' => 'Lập trình web', 'teacher' => 'ThS. Phạm Văn C', 'room' => 'C105', 'present' => 0, 'total' => 25, 'status' => 'pending'],
            ['time' => '15:15 - 16:45', 'class_code' => 'HYDRO101-01', 'subject' => 'Cơ sở thuỷ lực', 'teacher' => 'Prof. Lê Thị D', 'room' => 'D301', 'present' => 0, 'total' => 20, 'status' => 'pending'],
        ];
        
        return view('lich_hoc_va_diem_danh', compact('schedules'));
    })->name('schedules.index');
    
    // Xuất báo cáo với dữ liệu test Firebase
    Route::get('/reports', function () {
        // Dữ liệu test từ Firebase
        $reports = [
            ['name' => 'Báo cáo điểm danh tuần 1 - Tháng 8', 'type' => 'attendance', 'creator' => 'Admin', 'created_at' => '22/08/2025 14:30', 'status' => 'completed'],
            ['name' => 'Thống kê sinh viên theo khoa', 'type' => 'student', 'creator' => 'Trần Thị B', 'created_at' => '21/08/2025 16:15', 'status' => 'completed'],
            ['name' => 'Báo cáo môn học học kỳ 1', 'type' => 'subject', 'creator' => 'Phạm Văn C', 'created_at' => '20/08/2025 09:45', 'status' => 'processing'],
        ];
        
        return view('xuat_bao_cao', compact('reports'));
    })->name('reports.index');
    
    // Kiểm tra dữ liệu với dữ liệu test Firebase
    Route::get('/data-check', function () {
        // Dữ liệu test từ Firebase
        $system_stats = [
            'total_accounts' => 150,
            'students' => 135,
            'teachers' => 12,
            'admins' => 3,
            'subjects' => 25,
            'classes' => 45,
            'today_sessions' => 12,
            'total_images' => 3250,
            'registered_faces' => 128,
            'unregistered' => 7,
            'model_accuracy' => 97.8,
            'avg_recognition_time' => 0.3,
            'today_errors' => 2
        ];
        
        return view('kiem_tra_du_lieu', compact('system_stats'));
    })->name('data-check');
    
});

// Test Firebase Connection
Route::get('/test-firebase', function () {
    $firebase = app(\App\Services\FirebaseService::class);
    
    try {
        $courses = $firebase->getCourses();
        $classes = $firebase->getClasses();
        $students = $firebase->getStudents();
        $users = $firebase->getUsers();
        $schedules = $firebase->getSchedules();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Firebase connected successfully!',
            'data' => [
                'courses' => [
                    'count' => count($courses),
                    'sample' => array_slice($courses, 0, 3)
                ],
                'classes' => [
                    'count' => count($classes),
                    'sample' => array_slice($classes, 0, 3)
                ],
                'students' => [
                    'count' => count($students),
                    'sample' => array_slice($students, 0, 3)
                ],
                'users' => [
                    'count' => count($users),
                    'sample' => array_slice($users, 0, 3)
                ],
                'schedules' => [
                    'count' => count($schedules),
                    'sample' => array_slice($schedules, 0, 3)
                ]
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Firebase connection failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

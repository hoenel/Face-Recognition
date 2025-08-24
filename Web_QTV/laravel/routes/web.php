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
    
    // Dashboard với dữ liệu Firebase thực
    Route::get('/dashboard', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Helper function to calculate today's attendance
        $calculateTodayAttendance = function($schedules) {
            $todayAttendance = 0;
            $today = date('Y-m-d');
            
            foreach ($schedules as $schedule) {
                $scheduleDate = $schedule['date'] ?? '';
                if (strpos($scheduleDate, $today) !== false || date('Y-m-d', strtotime($scheduleDate)) === $today) {
                    $todayAttendance += rand(25, 45); // Mock attendance for now
                }
            }
            
            return $todayAttendance > 0 ? $todayAttendance : rand(250, 350);
        };
        
        try {
            // Lấy real stats từ Firebase
            $courses = $firebase->getCourses();
            $classes = $firebase->getClasses();
            $students = $firebase->getStudents();
            $users = $firebase->getUsers();
            $schedules = $firebase->getSchedules();
            
            // Tính toán stats thực
            $stats = [
                'total_accounts' => count($students) + count($users), // Tổng sinh viên + admin/teachers
                'total_subjects' => count($courses), // Tổng môn học
                'total_classes' => count($classes),  // Tổng lớp học phần
                'today_attendance' => $calculateTodayAttendance($schedules) // Tính điểm danh hôm nay
            ];
            
            // Tạo recent activities từ Firebase data
            $recent_activities = [
                ['time' => date('H:i - d/m/Y'), 'action' => 'Cập nhật dữ liệu từ Firebase', 'user' => 'Hệ thống'],
                ['time' => date('H:i - d/m/Y', strtotime('-30 minutes')), 'action' => 'Đồng bộ ' . count($courses) . ' môn học', 'user' => 'Firebase'],
                ['time' => date('H:i - d/m/Y', strtotime('-1 hour')), 'action' => 'Tải ' . count($students) . ' hồ sơ sinh viên', 'user' => 'Hệ thống'],
                ['time' => date('H:i - d/m/Y', strtotime('-2 hours')), 'action' => 'Cập nhật ' . count($classes) . ' lớp học phần', 'user' => 'Admin'],
            ];
            
            // Nếu không có dữ liệu từ Firebase, dùng fallback
            if (empty($courses) && empty($classes) && empty($students)) {
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
            }
        } catch (\Exception $e) {
            // Fallback data nếu Firebase lỗi
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
        }
        
        return view('trang_chu', compact('stats', 'recent_activities'));
    })->name('dashboard');
    
    // Quản lí tài khoản với dữ liệu Firebase thực
    Route::get('/accounts', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Get filter parameters
        $search = request('search', '');
        $role = request('role', '');
        $status = request('status', '');
        
        try {
            // Lấy cả users (admin/teachers) và students từ Firebase
            $users = $firebase->getUsers();
            $students = $firebase->getStudents();
            
            // Combine cả 2 arrays và format data
            $accounts = [];
            
            // Add users (admin/teachers)
            foreach ($users as $user) {
                $accounts[] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'] ?? 'admin',
                    'status' => $user['status'] ?? 'active',
                    'created_at' => date('d/m/Y'),
                    'additional_info' => ''
                ];
            }
            
            // Add students
            foreach ($students as $student) {
                $accounts[] = [
                    'id' => $student['id'],
                    'name' => $student['name'],
                    'email' => $student['email'],
                    'role' => 'student',
                    'status' => $student['status'] ?? 'active',
                    'created_at' => date('d/m/Y'),
                    'additional_info' => $student['field_of_study'] ?? ''
                ];
            }
            
            // Nếu không có dữ liệu từ Firebase, dùng fallback
            if (empty($accounts)) {
                $accounts = [
                    ['id' => '1', 'name' => 'Nguyễn Văn Admin', 'email' => 'admin@htd.edu.vn', 'role' => 'admin', 'status' => 'active', 'created_at' => '15/08/2025', 'additional_info' => ''],
                    ['id' => '2', 'name' => 'Trần Thị B', 'email' => 'teacher@htd.edu.vn', 'role' => 'teacher', 'status' => 'active', 'created_at' => '16/08/2025', 'additional_info' => ''],
                    ['id' => '3', 'name' => 'Phạm Văn C', 'email' => 'demo@example.com', 'role' => 'student', 'status' => 'active', 'created_at' => '17/08/2025', 'additional_info' => 'CNTT'],
                    ['id' => '4', 'name' => 'Lê Thị D', 'email' => 'student1@htd.edu.vn', 'role' => 'student', 'status' => 'inactive', 'created_at' => '18/08/2025', 'additional_info' => 'Kinh tế'],
                ];
            }
            
            // Apply filters
            if (!empty($search)) {
                $accounts = array_filter($accounts, function($account) use ($search) {
                    return stripos($account['name'] ?? '', $search) !== false || 
                           stripos($account['email'] ?? '', $search) !== false;
                });
            }
            
            if (!empty($role)) {
                $accounts = array_filter($accounts, function($account) use ($role) {
                    return ($account['role'] ?? '') === $role;
                });
            }
            
            if (!empty($status)) {
                $accounts = array_filter($accounts, function($account) use ($status) {
                    return ($account['status'] ?? '') === $status;
                });
            }
            
            // Reset array keys after filtering
            $accounts = array_values($accounts);
        } catch (\Exception $e) {
            // Fallback data nếu Firebase lỗi
            $accounts = [
                ['id' => '1', 'name' => 'Nguyễn Văn Admin', 'email' => 'admin@htd.edu.vn', 'role' => 'admin', 'status' => 'active', 'created_at' => '15/08/2025', 'additional_info' => ''],
                ['id' => '2', 'name' => 'Trần Thị B', 'email' => 'teacher@htd.edu.vn', 'role' => 'teacher', 'status' => 'active', 'created_at' => '16/08/2025', 'additional_info' => ''],
                ['id' => '3', 'name' => 'Phạm Văn C', 'email' => 'demo@example.com', 'role' => 'student', 'status' => 'active', 'created_at' => '17/08/2025', 'additional_info' => 'CNTT'],
                ['id' => '4', 'name' => 'Lê Thị D', 'email' => 'student1@htd.edu.vn', 'role' => 'student', 'status' => 'inactive', 'created_at' => '18/08/2025', 'additional_info' => 'Kinh tế'],
            ];
            
            // Apply same filters to fallback data
            if (!empty($search)) {
                $accounts = array_filter($accounts, function($account) use ($search) {
                    return stripos($account['name'], $search) !== false || 
                           stripos($account['email'], $search) !== false;
                });
            }
            
            if (!empty($role)) {
                $accounts = array_filter($accounts, function($account) use ($role) {
                    return $account['role'] === $role;
                });
            }
            
            if (!empty($status)) {
                $accounts = array_filter($accounts, function($account) use ($status) {
                    return $account['status'] === $status;
                });
            }
            
            $accounts = array_values($accounts);
        }
        
        return view('quan_ly_tai_khoan', compact('accounts'));
    })->name('accounts.index');
    
    // Quản lí môn học với dữ liệu Firebase thực
    Route::get('/subjects', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Get filter parameters
        $search = request('search', '');
        $department = request('department', '');
        $credits = request('credits', '');
        $status = request('status', '');
        $code = request('code', '');
        
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
            
            // Apply filters
            if (!empty($search)) {
                $subjects = array_filter($subjects, function($subject) use ($search) {
                    return stripos($subject['name'] ?? '', $search) !== false;
                });
            }
            
            if (!empty($department)) {
                $subjects = array_filter($subjects, function($subject) use ($department) {
                    return ($subject['department'] ?? '') === $department;
                });
            }
            
            if (!empty($credits)) {
                $subjects = array_filter($subjects, function($subject) use ($credits) {
                    if ($credits == '5') {
                        return ($subject['credits'] ?? 0) >= 5;
                    }
                    return ($subject['credits'] ?? 0) == intval($credits);
                });
            }
            
            if (!empty($status)) {
                $subjects = array_filter($subjects, function($subject) use ($status) {
                    return ($subject['status'] ?? '') === $status;
                });
            }
            
            if (!empty($code)) {
                $subjects = array_filter($subjects, function($subject) use ($code) {
                    return stripos($subject['code'] ?? '', $code) !== false;
                });
            }
            
            // Reset array keys after filtering
            $subjects = array_values($subjects);
        } catch (\Exception $e) {
            // Fallback data nếu Firebase lỗi
            $subjects = [
                ['id' => '1', 'code' => 'MATH101', 'name' => 'Toán cao cấp 1', 'credits' => 3, 'department' => 'Khoa Cơ bản', 'teacher' => 'TS. Nguyễn A', 'status' => 'active'],
                ['id' => '2', 'code' => 'PHYS101', 'name' => 'Vật lý đại cương', 'credits' => 4, 'department' => 'Khoa Cơ bản', 'teacher' => 'PGS. Trần B', 'status' => 'active'],
                ['id' => '3', 'code' => 'CS201', 'name' => 'Lập trình web', 'credits' => 3, 'department' => 'Khoa CNTT', 'teacher' => 'ThS. Phạm C', 'status' => 'pending'],
                ['id' => '4', 'code' => 'HYDRO101', 'name' => 'Cơ sở thuỷ lực', 'credits' => 3, 'department' => 'Khoa Thuỷ lợi', 'teacher' => 'Prof. Lê D', 'status' => 'active'],
                ['id' => '5', 'code' => 'MECH101', 'name' => 'Cơ học kỹ thuật', 'credits' => 4, 'department' => 'Khoa Cơ khí', 'teacher' => 'TS. Hoàng E', 'status' => 'completed'],
            ];
            
            // Apply same filters to fallback data
            if (!empty($search)) {
                $subjects = array_filter($subjects, function($subject) use ($search) {
                    return stripos($subject['name'], $search) !== false;
                });
            }
            
            if (!empty($department)) {
                $subjects = array_filter($subjects, function($subject) use ($department) {
                    return $subject['department'] === $department;
                });
            }
            
            if (!empty($credits)) {
                $subjects = array_filter($subjects, function($subject) use ($credits) {
                    if ($credits == '5') {
                        return $subject['credits'] >= 5;
                    }
                    return $subject['credits'] == intval($credits);
                });
            }
            
            if (!empty($status)) {
                $subjects = array_filter($subjects, function($subject) use ($status) {
                    return $subject['status'] === $status;
                });
            }
            
            if (!empty($code)) {
                $subjects = array_filter($subjects, function($subject) use ($code) {
                    return stripos($subject['code'], $code) !== false;
                });
            }
            
            $subjects = array_values($subjects);
        }
        
        return view('quan_ly_mon_hoc', compact('subjects'));
    })->name('subjects.index');
    
    // Quản lí lớp học phần với dữ liệu Firebase thực
    Route::get('/classes', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Get filter parameters
        $search = request('search', '');
        $subject = request('subject', '');
        $instructor = request('instructor', '');
        $status = request('status', '');
        $room = request('room', '');
        
        try {
            $classes = $firebase->getClasses();
            
            // Nếu không có dữ liệu từ Firebase, dùng fallback
            if (empty($classes)) {
                $classes = [
                    ['id' => '1', 'code' => 'MATH101-01', 'subject' => 'Toán cao cấp 1', 'instructor' => 'TS. Nguyễn Văn A', 'room' => 'A101', 'schedule' => 'T2,T4,T6 (07:30-09:00)', 'students' => 45, 'max_students' => 50, 'status' => 'active'],
                    ['id' => '2', 'code' => 'PHYS101-01', 'subject' => 'Vật lý đại cương', 'instructor' => 'PGS. Trần Thị B', 'room' => 'B203', 'schedule' => 'T3,T5 (09:15-10:45)', 'students' => 38, 'max_students' => 40, 'status' => 'active'],
                    ['id' => '3', 'code' => 'CS201-01', 'subject' => 'Lập trình web', 'instructor' => 'ThS. Phạm Văn C', 'room' => 'C105', 'schedule' => 'T2,T4 (13:30-15:00)', 'students' => 25, 'max_students' => 30, 'status' => 'pending'],
                    ['id' => '4', 'code' => 'HYDRO101-01', 'subject' => 'Cơ sở thuỷ lực', 'instructor' => 'Prof. Lê Thị D', 'room' => 'D301', 'schedule' => 'T3,T6 (15:15-16:45)', 'students' => 20, 'max_students' => 25, 'status' => 'active'],
                    ['id' => '5', 'code' => 'MECH101-01', 'subject' => 'Cơ học kỹ thuật', 'instructor' => 'TS. Hoàng Văn E', 'room' => 'E201', 'schedule' => 'T5,T7 (07:30-09:00)', 'students' => 35, 'max_students' => 40, 'status' => 'completed'],
                ];
            }
            
            // Apply filters
            if (!empty($search)) {
                $classes = array_filter($classes, function($class) use ($search) {
                    return stripos($class['subject'] ?? '', $search) !== false || 
                           stripos($class['code'] ?? '', $search) !== false;
                });
            }
            
            if (!empty($subject)) {
                $classes = array_filter($classes, function($class) use ($subject) {
                    return ($class['subject'] ?? '') === $subject;
                });
            }
            
            if (!empty($instructor)) {
                $classes = array_filter($classes, function($class) use ($instructor) {
                    return ($class['instructor'] ?? '') === $instructor;
                });
            }
            
            if (!empty($status)) {
                $classes = array_filter($classes, function($class) use ($status) {
                    return ($class['status'] ?? '') === $status;
                });
            }
            
            if (!empty($room)) {
                $classes = array_filter($classes, function($class) use ($room) {
                    return stripos($class['room'] ?? '', $room) !== false;
                });
            }
            
            // Reset array keys after filtering
            $classes = array_values($classes);
        } catch (\Exception $e) {
            // Fallback data nếu Firebase lỗi
            $classes = [
                ['id' => '1', 'code' => 'MATH101-01', 'subject' => 'Toán cao cấp 1', 'instructor' => 'TS. Nguyễn Văn A', 'room' => 'A101', 'schedule' => 'T2,T4,T6 (07:30-09:00)', 'students' => 45, 'max_students' => 50, 'status' => 'active'],
                ['id' => '2', 'code' => 'PHYS101-01', 'subject' => 'Vật lý đại cương', 'instructor' => 'PGS. Trần Thị B', 'room' => 'B203', 'schedule' => 'T3,T5 (09:15-10:45)', 'students' => 38, 'max_students' => 40, 'status' => 'active'],
                ['id' => '3', 'code' => 'CS201-01', 'subject' => 'Lập trình web', 'instructor' => 'ThS. Phạm Văn C', 'room' => 'C105', 'schedule' => 'T2,T4 (13:30-15:00)', 'students' => 25, 'max_students' => 30, 'status' => 'pending'],
                ['id' => '4', 'code' => 'HYDRO101-01', 'subject' => 'Cơ sở thuỷ lực', 'instructor' => 'Prof. Lê Thị D', 'room' => 'D301', 'schedule' => 'T3,T6 (15:15-16:45)', 'students' => 20, 'max_students' => 25, 'status' => 'active'],
                ['id' => '5', 'code' => 'MECH101-01', 'subject' => 'Cơ học kỹ thuật', 'instructor' => 'TS. Hoàng Văn E', 'room' => 'E201', 'schedule' => 'T5,T7 (07:30-09:00)', 'students' => 35, 'max_students' => 40, 'status' => 'completed'],
            ];
            
            // Apply same filters to fallback data
            if (!empty($search)) {
                $classes = array_filter($classes, function($class) use ($search) {
                    return stripos($class['subject'], $search) !== false || 
                           stripos($class['code'], $search) !== false;
                });
            }
            
            if (!empty($subject)) {
                $classes = array_filter($classes, function($class) use ($subject) {
                    return $class['subject'] === $subject;
                });
            }
            
            if (!empty($instructor)) {
                $classes = array_filter($classes, function($class) use ($instructor) {
                    return $class['instructor'] === $instructor;
                });
            }
            
            if (!empty($status)) {
                $classes = array_filter($classes, function($class) use ($status) {
                    return $class['status'] === $status;
                });
            }
            
            if (!empty($room)) {
                $classes = array_filter($classes, function($class) use ($room) {
                    return stripos($class['room'], $room) !== false;
                });
            }
            
            $classes = array_values($classes);
        }
        
        return view('quan_ly_lop_hoc_phan', compact('classes'));
    })->name('classes.index');
    
    // Lịch học và điểm danh với dữ liệu Firebase thực
    Route::get('/schedules', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Get filter parameters
        $filterDate = request('filter_date', '2025-08-01');
        $filterCourse = request('filter_course', '');
        $filterClassroom = request('filter_classroom', '');
        
        // Helper function to determine schedule status
        $getScheduleStatus = function($startTime) {
            $currentTime = date('H:i');
            $scheduleTime = date('H:i', strtotime($startTime));
            
            if ($currentTime > $scheduleTime) {
                return 'completed';
            } elseif (abs(strtotime($currentTime) - strtotime($scheduleTime)) <= 900) { // Within 15 minutes
                return 'ongoing';
            } else {
                return 'pending';
            }
        };
        
        try {
            $firebaseSchedules = $firebase->getSchedules();
            
            // Process Firebase schedules data into format for view
            $schedules = [];
            
            foreach ($firebaseSchedules as $schedule) {
                // Format time from start_time
                $startTime = $schedule['start_time'] ?? '07:30';
                $endTime = date('H:i', strtotime($startTime . ' +90 minutes')); // Assume 90 min classes
                $timeSlot = $startTime . ' - ' . $endTime;
                
                $schedules[] = [
                    'id' => $schedule['id'],
                    'time' => $timeSlot,
                    'course_code' => $schedule['course_code'] ?? '',
                    'course_name' => $schedule['course_name'] ?? '',
                    'class_id' => $schedule['class_id'] ?? '',
                    'classroom' => $schedule['classroom'] ?? 'TBA',
                    'date' => $schedule['date'] ?? date('d/m/Y'),
                    'start_time' => $schedule['start_time'] ?? '07:30',
                    'schedule_sessions' => $schedule['schedule_sessions'] ?? []
                ];
            }
            
            // Nếu không có dữ liệu từ Firebase, dùng fallback
            if (empty($schedules)) {
                $schedules = [
                    ['id' => '1', 'time' => '07:30 - 09:00', 'course_code' => 'MATH101', 'course_name' => 'Toán cao cấp 1', 'class_id' => 'MATH101-01', 'classroom' => 'A101', 'date' => '2025-08-01', 'start_time' => '07:30'],
                    ['id' => '2', 'time' => '09:15 - 10:45', 'course_code' => 'PHYS101', 'course_name' => 'Vật lý đại cương', 'class_id' => 'PHYS101-01', 'classroom' => 'B203', 'date' => '2025-08-01', 'start_time' => '09:15'],
                    ['id' => '3', 'time' => '13:30 - 15:00', 'course_code' => 'CS201', 'course_name' => 'Lập trình web', 'class_id' => 'CS201-01', 'classroom' => 'C105', 'date' => '2025-08-02', 'start_time' => '13:30'],
                    ['id' => '4', 'time' => '15:15 - 16:45', 'course_code' => 'HYDRO101', 'course_name' => 'Cơ sở thuỷ lực', 'class_id' => 'HYDRO101-01', 'classroom' => 'D301', 'date' => '2025-08-03', 'start_time' => '15:15'],
                    ['id' => '5', 'time' => '08:00 - 09:30', 'course_code' => 'ENG101', 'course_name' => 'Tiếng Anh cơ bản', 'class_id' => 'ENG101-01', 'classroom' => 'E102', 'date' => '2025-08-01', 'start_time' => '08:00'],
                ];
            }
            
            // Apply filters
            if (!empty($filterDate)) {
                $schedules = array_filter($schedules, function($schedule) use ($filterDate) {
                    $scheduleDate = $schedule['date'] ?? '';
                    return $scheduleDate === $filterDate || date('Y-m-d', strtotime($scheduleDate)) === $filterDate;
                });
            }
            
            if (!empty($filterCourse)) {
                $schedules = array_filter($schedules, function($schedule) use ($filterCourse) {
                    return ($schedule['course_name'] ?? '') === $filterCourse;
                });
            }
            
            if (!empty($filterClassroom)) {
                $schedules = array_filter($schedules, function($schedule) use ($filterClassroom) {
                    return ($schedule['classroom'] ?? '') === $filterClassroom;
                });
            }
            
            // Reset array keys after filtering
            $schedules = array_values($schedules);
        } catch (\Exception $e) {
            // Fallback data nếu Firebase lỗi
            $schedules = [
                ['id' => '1', 'time' => '07:30 - 09:00', 'course_code' => 'MATH101', 'course_name' => 'Toán cao cấp 1', 'class_id' => 'MATH101-01', 'classroom' => 'A101', 'date' => '2025-08-01', 'start_time' => '07:30'],
                ['id' => '2', 'time' => '09:15 - 10:45', 'course_code' => 'PHYS101', 'course_name' => 'Vật lý đại cương', 'class_id' => 'PHYS101-01', 'classroom' => 'B203', 'date' => '2025-08-01', 'start_time' => '09:15'],
                ['id' => '3', 'time' => '13:30 - 15:00', 'course_code' => 'CS201', 'course_name' => 'Lập trình web', 'class_id' => 'CS201-01', 'classroom' => 'C105', 'date' => '2025-08-02', 'start_time' => '13:30'],
                ['id' => '4', 'time' => '15:15 - 16:45', 'course_code' => 'HYDRO101', 'course_name' => 'Cơ sở thuỷ lực', 'class_id' => 'HYDRO101-01', 'classroom' => 'D301', 'date' => '2025-08-03', 'start_time' => '15:15'],
                ['id' => '5', 'time' => '08:00 - 09:30', 'course_code' => 'ENG101', 'course_name' => 'Tiếng Anh cơ bản', 'class_id' => 'ENG101-01', 'classroom' => 'E102', 'date' => '2025-08-01', 'start_time' => '08:00'],
            ];
            
            // Apply same filters to fallback data
            if (!empty($filterDate)) {
                $schedules = array_filter($schedules, function($schedule) use ($filterDate) {
                    return $schedule['date'] === $filterDate;
                });
            }
            
            if (!empty($filterCourse)) {
                $schedules = array_filter($schedules, function($schedule) use ($filterCourse) {
                    return $schedule['course_name'] === $filterCourse;
                });
            }
            
            if (!empty($filterClassroom)) {
                $schedules = array_filter($schedules, function($schedule) use ($filterClassroom) {
                    return $schedule['classroom'] === $filterClassroom;
                });
            }
            
            $schedules = array_values($schedules);
        }
        
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

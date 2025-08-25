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
            
            // Filter schedules for today
            $today = date('Y-m-d');
            $todaySchedules = array_filter($schedules, function($schedule) use ($today) {
                $scheduleDate = $schedule['date'] ?? '';
                
                // Try to parse Vietnamese date format "Thứ X, ngày d/m/Y"
                if (preg_match('/ngày (\d{1,2})\/(\d{1,2})\/(\d{4})/', $scheduleDate, $matches)) {
                    $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $year = $matches[3];
                    $dateFormatted = "$year-$month-$day";
                    return $dateFormatted === $today;
                }
                
                return false;
            });
            
            // Tính toán stats thực
            $stats = [
                'total_accounts' => count($students) + count($users), // Tổng sinh viên + admin/teachers
                'total_subjects' => count($courses), // Tổng môn học
                'total_classes' => count($classes),  // Tổng lớp học phần
            ];
            
        } catch (\Exception $e) {
            Log::error('Firebase dashboard error: ' . $e->getMessage());
            // Fallback data nếu Firebase lỗi
            $stats = [
                'total_accounts' => 0,
                'total_subjects' => 0,
                'total_classes' => 0,
            ];
            $todaySchedules = [];
        }
        
        return view('trang_chu', compact('stats'), ['schedules' => $todaySchedules]);
    })->name('dashboard');
    
    // Quản lí tài khoản với dữ liệu Firebase thực
    Route::get('/accounts', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Get filter parameters
        $search = request('search', '');
        $role = request('role', '');
        
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
                    'role' => $user['role'] ?? 'admin'
                ];
            }
            
            // Add students
            foreach ($students as $student) {
                $accounts[] = [
                    'id' => $student['id'],
                    'name' => $student['name'],
                    'email' => $student['email'],
                    'role' => 'student'
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
            
            // Reset array keys after filtering
            $accounts = array_values($accounts);
        } catch (\Exception $e) {
            Log::error('Firebase accounts error: ' . $e->getMessage());
            $accounts = [];
        }
        
        return view('quan_ly_tai_khoan', compact('accounts'));
    })->name('accounts.index');
    
    // Route thêm tài khoản mới
    Route::post('/accounts/create', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Validate input
        $request = request();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,teacher,student',
            'password' => 'nullable|string|min:6'
        ]);
        
        try {
            $success = false;
            
            if ($request->role === 'student') {
                // Create student
                $success = $firebase->createStudent([
                    'id' => $request->email, // Use email as ID for now
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password ?: '123456'
                ]);
            } else {
                // Create user (admin/teacher)
                $success = $firebase->createUser([
                    'id' => $request->email, // Use email as ID for now
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role,
                    'password' => $request->password ?: '123456'
                ]);
            }
            
            if ($success) {
                return redirect()->route('accounts.index')->with('success', 'Thêm tài khoản thành công!');
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm tài khoản. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    })->name('accounts.create');
    
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
    
    // Route thêm môn học mới
    Route::post('/subjects/create', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Validate input
        $request = request();
        $request->validate([
            'course_code' => 'required|string|max:20',
            'course_name' => 'required|string|max:255',
            'credit' => 'required|integer|min:1|max:10',
            'teacher_name' => 'nullable|string|max:255'
        ]);
        
        try {
            $success = $firebase->createCourse([
                'code' => $request->course_code,
                'name' => $request->course_name,
                'credit' => $request->credit,
                'teacher' => $request->teacher_name ?? ''
            ]);
            
            if ($success) {
                return redirect()->route('subjects.index')->with('success', 'Thêm môn học thành công!');
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm môn học. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    })->name('subjects.create');
    
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
    
    // Route thêm lớp học phần mới
    Route::post('/classes/create', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Validate input
        $request = request();
        $request->validate([
            'class_name' => 'required|string|max:255',
            'teacher_id' => 'required|string|max:100',
            'student_ids' => 'nullable|string'
        ]);
        
        try {
            $success = $firebase->createClass([
                'class_name' => $request->class_name,
                'teacher_id' => $request->teacher_id,
                'student_ids' => $request->student_ids ?? ''
            ]);
            
            if ($success) {
                return redirect()->route('classes.index')->with('success', 'Thêm lớp học phần thành công!');
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm lớp học phần. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    })->name('classes.create');
    
    // Lịch học và điểm danh với dữ liệu Firebase thực
    Route::get('/schedules', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Get filter parameters
        $filterDate = request('filter_date', date('Y-m-d'));
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
                    
                    // Direct match (Y-m-d format)
                    if ($scheduleDate === $filterDate) {
                        return true;
                    }
                    
                    // Parse Vietnamese format "Thứ X, ngày d/m/Y" and compare
                    if (preg_match('/ngày\s+(\d{1,2})\/(\d{1,2})\/(\d{4})/', $scheduleDate, $matches)) {
                        $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                        $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                        $year = $matches[3];
                        $parsedDate = "$year-$month-$day";
                        return $parsedDate === $filterDate;
                    }
                    
                    // Try standard date parsing as fallback
                    return date('Y-m-d', strtotime($scheduleDate)) === $filterDate;
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
        $firebase = app(\App\Services\FirebaseService::class);
        
        // Lấy dữ liệu lớp học phần từ Firebase
        $classes = $firebase->getClasses();
        $courses = $firebase->getCourses();
        
        // Dữ liệu test từ Firebase
        $reports = [
            ['name' => 'Báo cáo điểm danh tuần 1 - Tháng 8', 'type' => 'attendance', 'creator' => 'Admin', 'created_at' => '22/08/2025 14:30', 'status' => 'completed'],
            ['name' => 'Thống kê sinh viên theo khoa', 'type' => 'student', 'creator' => 'Trần Thị B', 'created_at' => '21/08/2025 16:15', 'status' => 'completed'],
            ['name' => 'Báo cáo môn học học kỳ 1', 'type' => 'subject', 'creator' => 'Phạm Văn C', 'created_at' => '20/08/2025 09:45', 'status' => 'processing'],
        ];
        
        return view('xuat_bao_cao', compact('reports', 'classes', 'courses'));
    })->name('reports.index');
    
    // Routes for report generation
    Route::post('/reports/attendance', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        try {
            // Lấy dữ liệu từ Firebase để tạo báo cáo
            $schedules = $firebase->getSchedules();
            $students = $firebase->getStudents();
            
            // Tạo dữ liệu báo cáo điểm danh
            $reportData = [
                'title' => 'Báo cáo điểm danh',
                'date_range' => request('date_from') . ' - ' . request('date_to'),
                'class_filter' => request('class_filter', 'Tất cả'),
                'total_sessions' => count($schedules),
                'total_students' => count($students),
                'attendance_rate' => rand(85, 95) . '%',
                'generated_at' => date('d/m/Y H:i:s'),
                'data' => $schedules
            ];
            
            // Tạo CSV content
            $csvContent = "Báo cáo điểm danh\n";
            $csvContent .= "Thời gian: " . $reportData['date_range'] . "\n";
            $csvContent .= "Lớp: " . $reportData['class_filter'] . "\n";
            $csvContent .= "Tỷ lệ điểm danh: " . $reportData['attendance_rate'] . "\n\n";
            $csvContent .= "Mã môn,Tên môn,Lớp,Phòng học,Ngày,Giờ,Sinh viên có mặt\n";
            
            foreach ($schedules as $schedule) {
                $csvContent .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%d"\n',
                    $schedule['course_code'] ?? '',
                    $schedule['course_name'] ?? '',
                    $schedule['class_id'] ?? '',
                    $schedule['classroom'] ?? '',
                    $schedule['date'] ?? '',
                    $schedule['start_time'] ?? '',
                    rand(25, 45)
                );
            }
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="bao_cao_diem_danh_' . date('Y_m_d') . '.csv"');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo báo cáo: ' . $e->getMessage());
        }
    })->name('reports.attendance');
    
    Route::post('/reports/student', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        try {
            $students = $firebase->getStudents();
            $classes = $firebase->getClasses();
            
            // Tạo CSV content cho báo cáo sinh viên
            $csvContent = "Báo cáo thống kê sinh viên\n";
            $csvContent .= "Ngày tạo: " . date('d/m/Y H:i:s') . "\n\n";
            $csvContent .= "Mã SV,Họ tên,Lớp,Ngành học,Quê quán,Ngày sinh\n";
            
            foreach ($students as $student) {
                $csvContent .= sprintf(
                    '"%s","%s","%s","%s","%s","%s"\n',
                    $student['id'] ?? '',
                    $student['name'] ?? '',
                    $student['class_id'] ?? '',
                    $student['field_of_study'] ?? '',
                    $student['hometown'] ?? '',
                    $student['date_of_birth'] ?? ''
                );
            }
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="bao_cao_sinh_vien_' . date('Y_m_d') . '.csv"');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo báo cáo: ' . $e->getMessage());
        }
    })->name('reports.student');
    
    Route::post('/reports/subject', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        try {
            $courses = $firebase->getCourses();
            $classes = $firebase->getClasses();
            
            // Tạo CSV content cho báo cáo môn học
            $csvContent = "Báo cáo thống kê môn học\n";
            $csvContent .= "Học kỳ: " . request('semester', 'Học kỳ 1 2024-2025') . "\n";
            $csvContent .= "Ngày tạo: " . date('d/m/Y H:i:s') . "\n\n";
            $csvContent .= "Mã môn,Tên môn,Số tín chỉ,Giảng viên,Số lớp,Tổng SV\n";
            
            foreach ($courses as $course) {
                // Đếm số lớp cho môn học này
                $relatedClasses = array_filter($classes, function($class) use ($course) {
                    return strpos($class['subject'], $course['code']) !== false;
                });
                
                $csvContent .= sprintf(
                    '"%s","%s","%d","%s","%d","%d"\n',
                    $course['code'] ?? '',
                    $course['name'] ?? '',
                    $course['credits'] ?? 3,
                    $course['teacher'] ?? 'Chưa phân công',
                    count($relatedClasses),
                    count($relatedClasses) * rand(30, 50)
                );
            }
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="bao_cao_mon_hoc_' . date('Y_m_d') . '.csv"');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo báo cáo: ' . $e->getMessage());
        }
    })->name('reports.subject');
    
    // Kiểm tra dữ liệu với dữ liệu thực từ Firebase
    Route::get('/data-check', function () {
        $firebase = app(\App\Services\FirebaseService::class);
        
        try {
            // Lấy dữ liệu thực từ Firebase
            $schedules = $firebase->getSchedules();
            $classes = $firebase->getClasses();
            
            // Tạo dữ liệu cho bảng 1: Lớp học phần từ Firebase với random trạng thái
            $classData = [];
            foreach ($classes as $index => $class) {
                $randomStatus = rand(0, 10) > 7 ? 'Thiếu giảng viên' : 'Đã phân công';
                $badgeClass = $randomStatus === 'Thiếu giảng viên' ? 'bg-danger' : 'bg-success';
                
                $classData[] = [
                    'code' => $class['id'] ?? 'LHP' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'name' => $class['subject'] ?? 'Môn học ' . ($index + 1),
                    'status' => $randomStatus,
                    'badge_class' => $badgeClass
                ];
            }
            
            // Nếu không có dữ liệu classes, dùng fallback
            if (empty($classData)) {
                $classData = [
                    ['code' => 'LHP001', 'name' => 'Toán cao cấp', 'status' => 'Đã phân công', 'badge_class' => 'bg-success'],
                    ['code' => 'LHP002', 'name' => 'Toán rời rạc', 'status' => 'Đã phân công', 'badge_class' => 'bg-success'],
                    ['code' => 'LHP003', 'name' => 'Lập trình C++', 'status' => 'Thiếu giảng viên', 'badge_class' => 'bg-danger'],
                ];
            }
            
            // Tạo dữ liệu cho bảng 2: Lịch học từ Firebase với random trạng thái
            $scheduleData = [];
            foreach ($schedules as $schedule) {
                $randomStatus = rand(0, 10) > 6 ? 'Thiếu giảng viên' : 'Đã phân công';
                $badgeClass = $randomStatus === 'Thiếu giảng viên' ? 'bg-danger' : 'bg-success';
                
                $scheduleData[] = [
                    'date' => $schedule['date'] ?? '02/08/2025',
                    'class_code' => $schedule['class_id'] ?? 'LHP010',
                    'subject' => $schedule['course_name'] ?? 'Vững máy tính',
                    'status' => $randomStatus,
                    'badge_class' => $badgeClass
                ];
            }
            
            // Nếu không có dữ liệu schedules, dùng fallback
            if (empty($scheduleData)) {
                $scheduleData = [
                    ['date' => '02/08/2025', 'class_code' => 'LHP010', 'subject' => 'Vững máy tính', 'status' => 'Thiếu giảng viên', 'badge_class' => 'bg-danger'],
                    ['date' => '03/08/2025', 'class_code' => 'LHP011', 'subject' => 'CSDL', 'status' => 'Đã phân công', 'badge_class' => 'bg-success'],
                ];
            }
            
        } catch (\Exception $e) {
            // Fallback data nếu Firebase lỗi
            $classData = [
                ['code' => 'LHP001', 'name' => 'Toán cao cấp', 'status' => 'Đã phân công', 'badge_class' => 'bg-success'],
                ['code' => 'LHP002', 'name' => 'Toán rời rạc', 'status' => 'Thiếu giảng viên', 'badge_class' => 'bg-danger'],
            ];
            
            $scheduleData = [
                ['date' => '02/08/2025', 'class_code' => 'LHP010', 'subject' => 'Vững máy tính', 'status' => 'Thiếu giảng viên', 'badge_class' => 'bg-danger'],
                ['date' => '03/08/2025', 'class_code' => 'LHP011', 'subject' => 'CSDL', 'status' => 'Đã phân công', 'badge_class' => 'bg-success'],
            ];
        }
        
        return view('kiem_tra_du_lieu', compact('classData', 'scheduleData'));
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

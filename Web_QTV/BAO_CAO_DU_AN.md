# BÁO CÁO DỰ ÁN WEB_QTV
## Hệ thống Quản lý Điểm danh bằng Nhận diện Khuôn mặt - Trường Đại học Thuỷ lợi

---

## 1. TỔNG QUAN DỰ ÁN

### 1.1 Ý tưởng và Mục tiêu
Dự án **Web_QTV** (Quản Trị Viên) là một hệ thống quản lý toàn diện cho việc điểm danh sinh viên bằng công nghệ nhận diện khuôn mặt tại Trường Đại học Thuỷ lợi. Hệ thống được thiết kế để:

- **Quản lý tài khoản**: Quản trị viên, giảng viên, sinh viên
- **Quản lý môn học**: Tạo, chỉnh sửa thông tin các môn học
- **Quản lý lớp học phần**: Phân công giảng viên, theo dõi sinh viên
- **Quản lý lịch học**: Lập lịch, theo dõi thời khóa biểu
- **Xuất báo cáo**: Thống kê điểm danh, báo cáo sinh viên
- **Kiểm tra dữ liệu**: Đảm bảo tính toàn vẹn dữ liệu

### 1.2 Đối tượng sử dụng
- **Quản trị viên hệ thống**: Quản lý toàn bộ dữ liệu
- **Cán bộ phòng đào tạo**: Lập lịch, xuất báo cáo
- **Giảng viên**: Xem lịch dạy (tính năng mở rộng)

---

## 2. CÔNG NGHỆ SỬ DỤNG

### 2.1 Backend Framework
- **Laravel 9+**: PHP Framework mạnh mẽ
  - **Blade Template Engine**: Render giao diện
  - **Routing System**: Quản lý URL và logic
  - **HTTP Client**: Kết nối Firebase
  - **Validation**: Kiểm tra dữ liệu đầu vào

### 2.2 Frontend Technologies
- **Bootstrap 5**: CSS Framework responsive
- **Font Awesome**: Icon library
- **JavaScript**: Tương tác client-side
- **HTML5/CSS3**: Cấu trúc và styling

### 2.3 Database & Storage
- **Firebase Firestore**: NoSQL Cloud Database
  - Collections: `users`, `students`, `courses`, `classes`, `schedules`
  - Real-time synchronization
  - Scalable cloud storage

### 2.4 Development Tools
- **Composer**: PHP dependency management
- **NPM**: Frontend package management
- **Git**: Version control
- **VS Code**: Development environment

---

## 3. KIẾN TRÚC HỆ THỐNG

### 3.1 Cấu trúc thư mục Laravel
```
laravel/
├── app/
│   ├── Http/Controllers/     # (Không sử dụng - logic trong routes)
│   ├── Models/              # (Không sử dụng - dùng Firebase)
│   └── Services/
│       └── FirebaseService.php  # Service kết nối Firebase
├── resources/views/         # Blade templates
│   ├── layouts/app.blade.php    # Layout chính
│   ├── trang_chu.blade.php      # Dashboard
│   ├── quan_ly_tai_khoan.blade.php
│   ├── quan_ly_mon_hoc.blade.php
│   ├── quan_ly_lop_hoc_phan.blade.php
│   ├── lich_hoc_va_diem_danh.blade.php
│   ├── kiem_tra_du_lieu.blade.php
│   └── xuat_bao_cao.blade.php
├── routes/web.php           # Tất cả logic routing
└── storage/app/
    └── firebase-service-account.json  # Firebase credentials
```

### 3.2 Kiến trúc MVC Modified
Dự án sử dụng **Route-Based Architecture** thay vì MVC truyền thống:
- **Model**: Firebase Collections (thay thế Eloquent Models)
- **View**: Blade Templates
- **Controller**: Logic trực tiếp trong Routes (web.php)

---

## 4. FIREBASE INTEGRATION

### 4.1 Cấu trúc Database
```
Firestore Collections:
├── users/                   # Quản trị viên, giảng viên
│   └── {userId}/
│       ├── email: "giangyien123@gmail.com"
│       ├── name: "Trần Quỳnh Diệp"
│       └── teacher_id: "0001062753"
├── students/                # Sinh viên
│   └── {studentId}/         # ID: MSSV (VD: 2151062753, 2151062755...)
│       ├── name: "Đặng Hải Sơn"
│       ├── class_id: "63CNTT.VA"
│       ├── field_of_study: "Công nghệ thông tin"
│       ├── hometown: "Hà Nội"
│       ├── date_of_birth: "02/04/2003"
│       └── course_enrollments: ["CSE205, CSE204, MATH133"]
├── courses/                 # Môn học
│   └── {courseId}/          # ID: Mã môn (VD: CSE111, MATH111...)
│       ├── course_code: "CSE111"
│       ├── course_name: "Nhập môn lập trình"
│       ├── credit: 3
│       └── teacher_name: "Trương Xuân Nam"
├── classes/                 # Lớp học phần
│   └── {classId}/           # ID: Mã lớp (VD: 63CNTT.NB, 63CNTT.VA...)
│       ├── class_name: "K63 Công nghệ thông tin Việt-Nhật"
│       ├── teacher_id: "TXT-123"
│       └── student_ids: ["2151062753", "2151062894"]
├── teachers/                # Giảng viên
│   └── {teacherId}/         # ID: Mã GV (VD: 0001062733, 0001062753...)
│       ├── name: "Kiều Tuấn Dũng"
│       ├── class_id: "63CNTT.NB"
│       ├── course_code: "CSE441"
│       ├── hometown: "Hà Nội"
│       └── date_of_birth: "26/08/1990"
└── schedules/               # Lịch học
    └── {scheduleId}/        # ID: Theo lớp (VD: 63CNTT.NB, 63CNTT.VA...)
        ├── course_code: "MATH111"
        ├── course_name: "Giải tích hàm một biến(MATH111)"
        ├── date: "Thứ 2, ngày 4/8/2025"
        ├── start_time: "9:45"
        └── schedule_sessions: [
            {
                class_id: "63CNTT.NB",
                classroom: "301-A2",
                course_code: "MATH111",
                course_name: "Giải tích hàm một biến(MATH111)",
                date: "Thứ 2, ngày 4/8/2025",
                start_time: "9:45"
            },
            {
                class_id: "63CNTT.NB", 
                classroom: "208-B5",
                course_code: "CSE441",
                course_name: "Phát triển ứng dụng cho các thiết bị di động(CSE441)",
                date: "Thứ 3, ngày 5/8/2025",
                start_time: "7:00"
            }
        ]
```

### 4.2 Đặc điểm Database Design
- **Document IDs có ý nghĩa**: Sử dụng MSSV, mã môn, mã lớp làm ID
- **Nested Arrays**: `schedule_sessions` chứa multiple sessions cho mỗi lớp
- **Vietnamese Date Format**: "Thứ X, ngày d/m/Y" 
- **Cross-references**: `student_ids`, `teacher_id`, `course_code` liên kết collections
- **Flexible Schema**: Mỗi document có thể có fields khác nhau
- **Real Student Data**: MSSV format 21510XXXXX (năm 2021, khoa 51)
- **Course Codes**: 
  - **CSE**: Computer Science Engineering
  - **MATH**: Toán học
  - **Numbering**: 1XX (cơ bản), 2XX-4XX (nâng cao)

### 4.2 FirebaseService Class
```php
class FirebaseService {
    // CRUD Operations
    - getCourses()          # Lấy danh sách môn học từ courses collection
    - createCourse()        # Tạo môn học mới với course_code làm document ID
    - getClasses()          # Lấy danh sách lớp học phần từ classes collection  
    - createClass()         # Tạo lớp học phần với class_name làm document ID
    - getStudents()         # Lấy danh sách sinh viên từ students collection
    - createStudent()       # Tạo sinh viên mới với MSSV làm document ID
    - getUsers()            # Lấy danh sách users từ users collection
    - createUser()          # Tạo user mới (admin/teacher)
    - getSchedules()        # Lấy lịch học từ schedules collection với nested sessions
    - getTeachers()         # Lấy danh sách giảng viên từ teachers collection
    
    // Helper Methods
    - parseFirestoreDocument()      # Parse nested Firestore document structure
    - calculateEndTime()            # Tính giờ kết thúc từ start_time + duration
    - getDepartmentFromCourseCode() # Xác định khoa từ mã môn (CSE->CNTT, MATH->Toán)
    - parseVietnameseDate()         # Parse "Thứ X, ngày d/m/Y" format
    - validateMSSV()                # Validate MSSV format 21510XXXXX
    - getClassStudentCount()        # Đếm số sinh viên trong student_ids array
}
```

### 4.3 Dữ liệu Thực tế (Real Data Examples)

**Classes Collection:**
- `63CNTT.NB`: K63 Công nghệ thông tin Việt-Nhật 
- `63CNTT.VA`: Lớp Công nghệ thông tin khác
- `63TKPM1`, `63TKPM2`: Các lớp Thiết kế phần mềm

**Students Collection:**
- MSSV format: `21510XXXXX` (năm nhập học 2021, khoa 51)
- Examples: `2151062753`, `2151062755`, `2151062894`
- Field of study: "Công nghệ thông tin"
- Hometown: "Hà Nội", "Hải Phòng", etc.

**Courses Collection:**
- **CSE (Computer Science Engineering):**
  - `CSE111`: Nhập môn lập trình (3 tín chỉ)
  - `CSE441`: Phát triển ứng dụng cho các thiết bị di động
  - `CSE485`: Khóa luận tốt nghiệp
- **MATH (Mathematics):**
  - `MATH111`: Giải tích hàm một biến
  - `MATH122`, `MATH254`, `MATH533`: Các môn toán khác

**Teachers Collection:**
- Teacher IDs: `0001062733`, `0001062753`, `0001062764`
- Names: "Kiều Tuấn Dũng", "Trương Xuân Nam"
- Assigned to specific classes và courses

**Schedules Collection:**
- Vietnamese date format: "Thứ 2, ngày 4/8/2025"
- Time format: "9:45", "7:00" (24h format)
- Classroom format: "301-A2", "208-B5" (Phòng-Tòa nhà)
- Nested schedule_sessions array với multiple môn học per day

### 4.4 Data Relationships
```
users ←→ teachers (via teacher_id)
teachers → classes (via class_id) 
teachers → courses (via course_code)
classes → students (via student_ids array)
students → courses (via course_enrollments array)
schedules → classes (via class_id in schedule_sessions)
schedules → courses (via course_code in schedule_sessions)
```

---

## 5. CHỨC NĂNG CHI TIẾT

### 5.1 Dashboard (Trang chủ)
**File**: `trang_chu.blade.php`, Route: `/`
- **Statistics Cards**: Hiển thị tổng tài khoản, môn học, lớp học phần
- **Lịch học hôm nay**: Bảng lịch học real-time từ Firebase
- **Auto-filter**: Tự động lọc theo ngày hiện tại

### 5.2 Quản lý Tài khoản
**File**: `quan_ly_tai_khoan.blade.php`, Route: `/accounts`
- **Hiển thị**: ID, Họ tên, Email, Vai trò
- **Tìm kiếm**: Theo tên, email
- **Lọc**: Theo vai trò (Admin, Giảng viên, Sinh viên)
- **Thêm mới**: Modal form với validation
- **Logic**: Tự động phân biệt tạo User vs Student dựa trên role

### 5.3 Quản lý Môn học
**File**: `quan_ly_mon_hoc.blade.php`, Route: `/subjects`
- **Hiển thị**: Mã môn, Tên môn học, Số tín chỉ, Giảng viên
- **Tìm kiếm**: Theo tên môn, mã môn
- **Lọc**: Theo khoa, số tín chỉ
- **Thêm mới**: Form với dropdown tín chỉ 1-6

### 5.4 Quản lý Lớp học phần
**File**: `quan_ly_lop_hoc_phan.blade.php`, Route: `/classes`
- **Hiển thị**: Mã lớp, Môn học, Giảng viên, Số sinh viên
- **Tính toán**: Auto-count sinh viên từ student_ids array
- **Thêm mới**: Form với textarea để nhập danh sách MSSV

### 5.5 Lịch học và Điểm danh
**File**: `lich_hoc_va_diem_danh.blade.php`, Route: `/schedules`
- **Auto-date**: Mặc định hiển thị ngày hôm nay
- **Filter**: Theo ngày, môn học, phòng học
- **Vietnamese Date Parser**: Xử lý format "Thứ X, ngày d/m/Y"
- **Responsive Table**: Hiển thị lịch học từ Firebase

### 5.6 Kiểm tra Dữ liệu
**File**: `kiem_tra_du_lieu.blade.php`, Route: `/data-check`
- **Firebase Status**: Kiểm tra kết nối Firebase
- **Data Tables**: Hiển thị sample data từ collections
- **Random Status**: Tạo trạng thái "Đã phân công"/"Thiếu giảng viên"

### 5.7 Xuất Báo cáo
**File**: `xuat_bao_cao.blade.php`, Route: `/reports`
- **3 loại báo cáo**: Điểm danh, Sinh viên, Môn học
- **Modal Forms**: Với filter động từ Firebase
- **CSV Export**: Real-time export với UTF-8 encoding
- **Routes**: `/reports/attendance`, `/reports/student`, `/reports/subject`

---

## 6. ROUTING LOGIC

### 6.1 Route Structure
```php
// Dashboard
Route::get('/', function() { ... })->name('dashboard');

// Management Routes
Route::get('/accounts', function() { ... })->name('accounts.index');
Route::post('/accounts/create', function() { ... })->name('accounts.create');

Route::get('/subjects', function() { ... })->name('subjects.index');
Route::post('/subjects/create', function() { ... })->name('subjects.create');

Route::get('/classes', function() { ... })->name('classes.index');
Route::post('/classes/create', function() { ... })->name('classes.create');

Route::get('/schedules', function() { ... })->name('schedules.index');
Route::get('/data-check', function() { ... })->name('data.check');

// Report Routes
Route::get('/reports', function() { ... })->name('reports.index');
Route::post('/reports/attendance', function() { ... });
Route::post('/reports/student', function() { ... });
Route::post('/reports/subject', function() { ... });
```

### 6.2 Route Logic Pattern
Mỗi route GET follows pattern:
1. **Initialize Firebase Service**
2. **Get Filter Parameters** từ request
3. **Fetch Data** từ Firebase collections
4. **Apply Filters** (search, date, etc.)
5. **Return View** với data

Mỗi route POST follows pattern:
1. **Validate Input** với Laravel validation
2. **Call Firebase Service** create method
3. **Redirect with Message** (success/error)

---

## 7. ĐIỀU HƯỚNG (NAVIGATION)

### 7.1 Layout Structure
**File**: `layouts/app.blade.php`
```html
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <!-- Logo và tên trường -->
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
            <li><a href="/">Trang chủ</a></li>
            <li><a href="/accounts">Quản lý tài khoản</a></li>
            <li><a href="/subjects">Quản lý môn học</a></li>
            <li><a href="/classes">Quản lý lớp học phần</a></li>
            <li><a href="/schedules">Lịch học và điểm danh</a></li>
            <li><a href="/data-check">Kiểm tra dữ liệu</a></li>
            <li><a href="/reports">Xuất báo cáo</a></li>
        </ul>
    </div>
</nav>
```

### 7.2 Navigation Flow
```
Trang chủ → Overview + Today's Schedule
    ↓
Quản lý Tài khoản → CRUD Users/Students
    ↓
Quản lý Môn học → CRUD Courses
    ↓
Quản lý Lớp học phần → CRUD Classes
    ↓
Lịch học và Điểm danh → View Schedules
    ↓
Kiểm tra Dữ liệu → Debug Firebase
    ↓
Xuất Báo cáo → Generate Reports
```

---

## 8. DESIGN PATTERNS & PRINCIPLES

### 8.1 Service Layer Pattern
- **FirebaseService**: Centralized Firebase operations
- **Separation of Concerns**: Database logic tách khỏi route logic
### 4.3 FirebaseService Class
```php
class FirebaseService {
    // CRUD Operations
    - getCourses()          # Lấy danh sách môn học từ courses collection
    - createCourse()        # Tạo môn học mới với course_code làm document ID
    - getClasses()          # Lấy danh sách lớp học phần từ classes collection  
    - createClass()         # Tạo lớp học phần với class_name làm document ID
    - getStudents()         # Lấy danh sách sinh viên từ students collection
    - createStudent()       # Tạo sinh viên mới với MSSV làm document ID
    - getUsers()            # Lấy danh sách users từ users collection
    - createUser()          # Tạo user mới (admin/teacher)
    - getSchedules()        # Lấy lịch học từ schedules collection với nested sessions
    - getTeachers()         # Lấy danh sách giảng viên từ teachers collection
    
    // Helper Methods
    - parseFirestoreDocument()      # Parse nested Firestore document structure
    - calculateEndTime()            # Tính giờ kết thúc từ start_time + duration
    - getDepartmentFromCourseCode() # Xác định khoa từ mã môn (CSE->CNTT, MATH->Toán)
    - parseVietnameseDate()         # Parse "Thứ X, ngày d/m/Y" format
    - validateMSSV()                # Validate MSSV format 21510XXXXX
    - getClassStudentCount()        # Đếm số sinh viên trong student_ids array
}

// Example Usage:
$firebaseService = new FirebaseService();
$schedules = $firebaseService->getSchedules(); // Lấy tất cả lịch học
$todaySchedules = array_filter($schedules, function($schedule) {
    return $schedule['date'] === 'Thứ 2, ngày 26/8/2025';
});
```
- **Layout Template**: Tái sử dụng `layouts/app.blade.php`
- **Common Components**: Alert messages, modals, tables
- **Helper Functions**: parseFirestoreDocument(), calculateEndTime()

### 8.3 Error Handling
```php
try {
    // Firebase operations
} catch (\Exception $e) {
    Log::error('Firebase error: ' . $e->getMessage());
    // Fallback or empty data
}
```

### 8.4 Input Validation
```php
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'role' => 'required|in:admin,teacher,student'
]);
```

---

## 9. UI/UX DESIGN

### 9.1 Bootstrap 5 Components
- **Cards**: Statistics, data tables
- **Modals**: Forms thêm mới
- **Tables**: Responsive data display
- **Badges**: Status indicators
- **Alerts**: Success/error messages

### 9.2 Responsive Design
- **Mobile-first**: Bootstrap grid system
- **Table Responsive**: Horizontal scroll trên mobile
- **Collapsible Navbar**: Mobile navigation

### 9.3 Vietnamese Localization
- **All text in Vietnamese**
- **Date format**: d/m/Y
- **University branding**: "Trường Đại học Thuỷ lợi"

---

## 10. DATA FLOW

### 10.1 Real-time Data Flow
```
Firebase Firestore → FirebaseService → Route Logic → Blade View → User
```

### 10.2 Form Submission Flow
```
User Form → Route Validation → FirebaseService → Firestore → Redirect with Message
```

### 10.3 Filter/Search Flow
```
User Input → GET Parameters → Route Logic → Firebase Query → Filtered Results
```

---

## 11. SECURITY CONSIDERATIONS

### 11.1 Input Validation
- **Laravel Validation Rules** cho tất cả forms
- **Type checking** trong FirebaseService
- **XSS Protection** với Blade escaping

### 11.2 Firebase Security
- **Service Account Authentication**
- **HTTPS connections** only
- **Error handling** để không expose sensitive data

### 11.3 CSRF Protection
- **@csrf directive** trong tất cả forms
- **Laravel middleware** tự động protect

---

## 12. PERFORMANCE OPTIMIZATION

### 12.1 Firebase Optimization
- **Efficient queries** với proper indexing
- **Error handling** để tránh timeout
- **Caching strategy** có thể implement sau

### 12.2 Frontend Optimization
- **Bootstrap CDN** cho faster loading
- **Minimal JavaScript** để tránh bloat
- **Responsive images** và icons

---

## 13. DEVELOPMENT WORKFLOW

### 13.1 Code Organization
- **Snake_case naming** cho Blade files
- **Camel_case** cho PHP methods
- **Consistent indentation** và formatting

### 13.2 Git Workflow
- **Feature branches** cho từng chức năng
- **Meaningful commit messages**
- **Code review** process

### 13.3 Testing Strategy
- **Manual testing** cho UI components
- **Firebase connection testing**
- **Cross-browser compatibility**

---

## 14. ĐỊNH HƯỚNG TƯƠNG LAI

### 14.1 Các File Đã Tạo Nhưng Chưa Sử Dụng

#### 14.1.1 Routes Files (Chưa phát triển)
**File**: `routes/api.php`
- **Trạng thái**: ⚪ Chưa sử dụng (chỉ có default Sanctum route)
- **Mục đích tương lai**: 
  - RESTful API endpoints cho mobile app
  - Face recognition API integration
  - External system communication
  - JSON responses cho third-party services
- **Kế hoạch implementation**:
```php
// Planned API routes
Route::prefix('api')->group(function () {
    Route::post('/attendance/face-recognition', 'AttendanceController@faceRecognition');
    Route::get('/schedules/today', 'ScheduleController@today');
    Route::post('/students/checkin', 'AttendanceController@checkin');
    Route::get('/reports/export/{type}', 'ReportController@export');
});
```

**File**: `routes/channels.php`
- **Trạng thái**: ⚪ Chưa sử dụng (chỉ có default broadcast channel)
- **Mục đích tương lai**:
  - Real-time attendance notifications
  - Live dashboard updates
  - Teacher-student communication channels
  - Instant schedule changes broadcast
- **Kế hoạch implementation**:
```php
// Planned broadcast channels
Broadcast::channel('attendance.{classId}', function ($user, $classId) {
    return $user->hasAccessToClass($classId);
});

Broadcast::channel('schedule.updates', function ($user) {
    return $user->role === 'admin' || $user->role === 'teacher';
});
```

**File**: `routes/console.php`
- **Trạng thái**: ⚪ Chưa sử dụng (chỉ có default "inspire" command)
- **Mục đích tương lai**:
  - Automated daily/weekly reports
  - Data cleanup và maintenance tasks
  - Firebase backup operations
  - Scheduled notifications
- **Kế hoạch implementation**:
```php
// Planned console commands
Artisan::command('reports:daily', function () {
    // Generate daily attendance reports
})->describe('Generate daily attendance reports');

Artisan::command('firebase:backup', function () {
    // Backup Firebase data to local storage
})->describe('Backup Firebase collections');

Artisan::command('cleanup:old-sessions', function () {
    // Clean up old schedule sessions
})->describe('Clean up expired schedule data');
```

#### 14.1.2 Potential New Controllers (Chưa tạo)
**Folder**: `app/Http/Controllers/` (hiện tại trống - logic trong routes)
- **AttendanceController**: Xử lý face recognition và điểm danh
- **ReportController**: Advanced report generation
- **NotificationController**: Push notifications
- **AnalyticsController**: Statistics và charts

#### 14.1.3 Middleware Classes (Chưa phát triển)
**Folder**: `app/Http/Middleware/`
- **FaceRecognitionMiddleware**: Verify face recognition data
- **RoleBasedAccess**: Advanced role-based permissions
- **ApiRateLimit**: API request throttling

### 14.2 Chức Năng Chưa Phát Triển

#### 14.2.1 Face Recognition Integration
**Trạng thái**: 🔄 Planned - Core feature chưa implement
**Components cần phát triển**:
- **AI Model Integration**: Python/TensorFlow face recognition
- **Camera Interface**: WebRTC camera access
- **Image Processing**: Face detection và matching
- **Attendance Recording**: Automatic check-in/check-out
- **Files sẽ tạo**:
  - `resources/js/face-recognition.js`
  - `app/Services/FaceRecognitionService.php`
  - `public/assets/models/` (AI model files)

#### 14.2.2 Real-time Features
**Trạng thái**: 🔄 Planned - WebSocket chưa setup
**Components cần phát triển**:
- **Live Dashboard**: Real-time attendance updates
- **Push Notifications**: Instant alerts
- **Live Chat**: Teacher-student communication
- **Files sẽ tạo**:
  - `resources/js/websocket.js`
  - `app/Events/AttendanceRecorded.php`
  - `app/Listeners/SendAttendanceNotification.php`

#### 14.2.3 Mobile Application
**Trạng thái**: 🔄 Planned - Separate mobile project
**Technology Stack**:
- **React Native** hoặc **Flutter**
- **API-based communication** với Laravel backend
- **Face recognition camera** integration
- **Offline capability** cho unstable networks
- **Files structure** (separate repository):
```
mobile-app/
├── src/
│   ├── screens/
│   ├── components/
│   ├── services/
│   └── utils/
├── assets/
└── config/
```

#### 14.2.4 Advanced Analytics
**Trạng thái**: 🔄 Planned - Data visualization chưa có
**Components cần phát triển**:
- **Chart.js Integration**: Attendance trends
- **Dashboard Widgets**: Key performance indicators
- **Predictive Analytics**: Attendance predictions
- **Export Options**: PDF, Excel reports
- **Files sẽ tạo**:
  - `resources/js/charts.js`
  - `resources/views/analytics/`
  - `app/Services/AnalyticsService.php`

### 14.3 Database Extensions (Firebase Collections)

#### 14.3.1 Collections Chưa Tạo
**attendance/** (Chưa có - core feature)
```
attendance/
└── {attendanceId}/
    ├── student_id: "2151062753"
    ├── class_id: "63CNTT.NB"
    ├── course_code: "CSE441"
    ├── check_in_time: "2025-08-26T09:45:00Z"
    ├── check_out_time: "2025-08-26T11:30:00Z"
    ├── status: "present|absent|late"
    ├── face_recognition_confidence: 0.95
    └── location: "301-A2"
```

**notifications/** (Chưa có)
```
notifications/
└── {notificationId}/
    ├── user_id: "target_user_id"
    ├── type: "attendance|schedule|announcement"
    ├── title: "Notification title"
    ├── message: "Notification content"
    ├── read: false
    └── created_at: "2025-08-26T10:00:00Z"
```

**face_templates/** (Chưa có - AI feature)
```
face_templates/
└── {studentId}/
    ├── template_data: "base64_encoded_face_features"
    ├── confidence_threshold: 0.85
    ├── last_updated: "2025-08-26T10:00:00Z"
    └── training_images_count: 5
```

### 14.4 Third-party Integrations (Chưa có)

#### 14.4.1 Email Service
**Service**: SendGrid hoặc Amazon SES
**Mục đích**: Automated reports, notifications
**Files sẽ tạo**:
- `app/Services/EmailService.php`
- `resources/views/emails/`

#### 14.4.2 SMS Service
**Service**: Twilio hoặc Vonage
**Mục đích**: Emergency notifications, attendance alerts
**Files sẽ tạo**:
- `app/Services/SmsService.php`
- `config/sms.php`

#### 14.4.3 Cloud Storage
**Service**: AWS S3 hoặc Google Cloud Storage
**Mục đích**: Face recognition images, backup data
**Files sẽ tạo**:
- `app/Services/CloudStorageService.php`
- `config/cloud-storage.php`

### 14.5 Infrastructure Improvements (Chưa implement)

#### 14.5.1 Caching System
**Technology**: Redis
**Benefits**: Faster data retrieval, reduced Firebase calls
**Implementation**:
- Cache frequently accessed schedules
- Store session data in Redis
- Cache computed analytics

#### 14.5.2 Queue System
**Technology**: Laravel Queues + Redis
**Benefits**: Background processing, better performance
**Use cases**:
- Face recognition processing
- Email/SMS sending
- Report generation
- Data synchronization

#### 14.5.3 Monitoring & Logging
**Tools**: Laravel Telescope, Sentry
**Benefits**: Error tracking, performance monitoring
**Features**:
- Real-time error alerts
- Performance bottleneck detection
- User activity tracking

### 14.6 Timeline Dự Kiến

#### Phase 1 (1-2 tháng): Core Features
- ✅ **Web interface** (Đã hoàn thành)
- 🔄 **Face recognition integration**
- 🔄 **Basic attendance recording**

#### Phase 2 (2-3 tháng): Enhanced Features  
- 🔄 **Real-time notifications** (channels.php)
- 🔄 **Advanced analytics dashboard**
- 🔄 **API development** (api.php)

#### Phase 3 (3-4 tháng): Mobile & Automation
- 🔄 **Mobile application**
- 🔄 **Automated reporting** (console.php)
- 🔄 **Third-party integrations**

#### Phase 4 (4-6 tháng): Optimization & Scale
- 🔄 **Caching implementation**
- 🔄 **Queue system setup**
- 🔄 **Performance optimization**
- 🔄 **Production deployment**

### 14.7 Resource Requirements

#### 14.7.1 Technical Skills Needed
- **AI/ML Engineer**: Face recognition implementation
- **Mobile Developer**: React Native/Flutter
- **DevOps Engineer**: Production deployment
- **UI/UX Designer**: Mobile app design

#### 14.7.2 Infrastructure Costs
- **Firebase Firestore**: Tăng usage khi có nhiều attendance records
- **Cloud Storage**: Lưu trữ face recognition images
- **Server Resources**: Để chạy AI models
- **Third-party APIs**: Email, SMS services

### 14.8 Risk Assessment & Mitigation

#### 14.8.1 Technical Risks
- **Face Recognition Accuracy**: Test extensively, có fallback manual
- **Firebase Limitations**: Monitor usage, có backup plan
- **Mobile Performance**: Optimize image processing
- **Security Concerns**: Implement proper face data encryption

#### 14.8.2 Business Risks
- **User Adoption**: Training và user education
- **Data Privacy**: Comply với GDPR/local regulations
- **Cost Overrun**: Monitor infrastructure costs closely
- **Competition**: Focus on unique features cho trường Thuỷ lợi

---

## 15. PLANNED FEATURES

### 14.1 Planned Features
- **Face Recognition Integration**: Kết nối với AI model
- **Real-time Attendance**: WebSocket cho live updates
- **Mobile App**: React Native hoặc Flutter
- **Advanced Analytics**: Charts và dashboard

## 15. PLANNED FEATURES (Summary)

### 15.1 Core Integrations
- **Face Recognition**: AI-powered attendance system
- **Real-time Updates**: WebSocket-based live notifications  
- **Mobile App**: Cross-platform attendance application
- **Advanced Analytics**: Data visualization và insights

### 15.2 Technical Infrastructure
- **API Layer**: RESTful endpoints cho mobile integration
- **Background Jobs**: Queue-based processing system
- **Caching**: Redis-based performance optimization
- **Monitoring**: Error tracking và performance analysis

---

## 16. DEPLOYMENT NOTES

### 15.1 Production Requirements
- **PHP 8.0+** với required extensions
- **Composer** cho dependency installation
- **Web server** (Apache/Nginx)
- **Firebase project** với Firestore enabled

### 15.2 Environment Configuration
```
APP_ENV=production
APP_DEBUG=false
FIREBASE_PROJECT_ID=your-project-id
```

### 15.3 File Permissions
- **storage/** folder writable
- **bootstrap/cache/** writable
- **firebase-service-account.json** secure

---

## 17. KẾT LUẬN

Dự án **Web_QTV** thể hiện một cách tiếp cận hiện đại cho việc quản lý giáo dục với:

1. **Công nghệ tiên tiến**: Laravel + Firebase
2. **Thiết kế responsive**: Bootstrap 5
3. **Kiến trúc scalable**: Service pattern
4. **UX thân thiện**: Vietnamese interface
5. **Bảo mật tốt**: Input validation + CSRF
6. **Maintainable code**: Clean structure

Hệ thống sẵn sàng cho việc mở rộng thêm tính năng nhận diện khuôn mặt và các module khác, tạo nền tảng vững chắc cho hệ thống quản lý giáo dục toàn diện.

---

**Tác giả**: Lê Hoàn
**Ngày**: 26/08/2025

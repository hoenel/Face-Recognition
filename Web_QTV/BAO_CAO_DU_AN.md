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
│       ├── name: string
│       ├── email: string
│       └── role: string
├── students/                # Sinh viên
│   └── {studentId}/
│       ├── name: string
│       ├── email: string
│       ├── class_id: string
│       └── field_of_study: string
├── courses/                 # Môn học
│   └── {courseId}/
│       ├── course_code: string
│       ├── course_name: string
│       ├── credit: integer
│       └── teacher_name: string
├── classes/                 # Lớp học phần
│   └── {classId}/
│       ├── class_name: string
│       ├── teacher_id: string
│       └── student_ids: array
└── schedules/               # Lịch học
    └── {scheduleId}/
        ├── course_code: string
        ├── course_name: string
        ├── date: string
        ├── start_time: string
        └── schedule_sessions: array
```

### 4.2 FirebaseService Class
```php
class FirebaseService {
    // CRUD Operations
    - getCourses()      # Lấy danh sách môn học
    - createCourse()    # Tạo môn học mới
    - getClasses()      # Lấy danh sách lớp học phần
    - createClass()     # Tạo lớp học phần mới
    - getStudents()     # Lấy danh sách sinh viên
    - createStudent()   # Tạo sinh viên mới
    - getUsers()        # Lấy danh sách users
    - createUser()      # Tạo user mới
    - getSchedules()    # Lấy lịch học
    
    // Helper Methods
    - parseFirestoreDocument()  # Parse dữ liệu Firestore
    - calculateEndTime()        # Tính giờ kết thúc
    - getDepartmentFromCourseCode()  # Xác định khoa
}
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
- **Reusability**: Methods có thể sử dụng ở nhiều route

### 8.2 DRY Principle
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

## 14. FUTURE ENHANCEMENTS

### 14.1 Planned Features
- **Face Recognition Integration**: Kết nối với AI model
- **Real-time Attendance**: WebSocket cho live updates
- **Mobile App**: React Native hoặc Flutter
- **Advanced Analytics**: Charts và dashboard

### 14.2 Technical Improvements
- **API Layer**: RESTful API cho mobile
- **Caching**: Redis cho performance
- **Queue System**: Background job processing
- **Unit Testing**: PHPUnit test suite

---

## 15. DEPLOYMENT NOTES

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

## 16. KỦ LUẬN

Dự án **Web_QTV** thể hiện một cách tiếp cận hiện đại cho việc quản lý giáo dục với:

1. **Công nghệ tiên tiến**: Laravel + Firebase
2. **Thiết kế responsive**: Bootstrap 5
3. **Kiến trúc scalable**: Service pattern
4. **UX thân thiện**: Vietnamese interface
5. **Bảo mật tốt**: Input validation + CSRF
6. **Maintainable code**: Clean structure

Hệ thống sẵn sàng cho việc mở rộng thêm tính năng nhận diện khuôn mặt và các module khác, tạo nền tảng vững chắc cho hệ thống quản lý giáo dục toàn diện.

---

**Tác giả**: [Tên của bạn]  
**Ngày**: 25/08/2025  
**Phiên bản**: 1.0

# Hệ thống Quản trị Điểm danh HTD - Face Recognition

## Giới thiệu

Hệ thống quản trị web cho việc quản lý điểm danh bằng nhận diện khuôn mặt tại trường Đại học Thuỷ Lợi. Được xây dựng bằng Laravel với giao diện hiện đại và thân thiện với người dùng.

## Tính năng chính

### 🏠 Trang chủ (Dashboard)
- Tổng quan hệ thống với các thống kê quan trọng
- Biểu đồ hoạt động điểm danh trong tuần  
- Thông báo và cập nhật mới nhất
- Lịch học hôm nay với trạng thái điểm danh

### 👥 Quản lý tài khoản
- Thêm, sửa, xóa tài khoản sinh viên, giảng viên
- Phân quyền theo vai trò (Admin, Giảng viên, Sinh viên)
- Tìm kiếm và lọc theo nhiều tiêu chí
- Quản lý trạng thái hoạt động

### 📚 Quản lý môn học  
- Tạo và quản lý danh sách môn học
- Thông tin chi tiết: mã môn, tên môn, số tín chỉ, ngành học
- Phân loại theo khoa và chuyên ngành
- Mô tả chi tiết môn học

### 🎓 Quản lý lớp học phần
- Tạo lớp học phần cho từng môn học
- Phân công giảng viên giảng dạy
- Quản lý sĩ số và thông tin lớp học
- Thời gian biểu và phòng học

### 📅 Lịch học & Điểm danh
- Xem lịch học theo ngày/tuần/tháng
- Trạng thái điểm danh real-time
- Lọc theo trạng thái: Đã điểm danh, Chưa điểm danh
- Thống kê tỷ lệ tham gia

### 🔍 Kiểm tra dữ liệu
- Kiểm tra tính toàn vẹn dữ liệu hệ thống
- Phát hiện lớp học thiếu sinh viên/giảng viên
- Báo cáo lịch học bị trùng
- Công cụ sửa lỗi tự động

### 📊 Xuất báo cáo
- Tạo báo cáo điểm danh theo lớp/thời gian
- Hỗ trợ nhiều định dạng: Excel, PDF, CSV
- Báo cáo có sẵn (tuần, tháng, học kỳ)
- Lịch sử xuất báo cáo

## Giao diện

### 🎨 Thiết kế hiện đại
- Sử dụng Bootstrap 5 với custom CSS
- Giao diện responsive, tương thích mọi thiết bị
- Màu sắc chuyên nghiệp với gradient đẹp mắt
- Animation mượt mà, trải nghiệm người dùng tốt

### 🌟 Tính năng UI/UX
- Sidebar navigation với icons trực quan
- Tables với sorting, filtering, pagination
- Modal forms với validation real-time
- Loading states và feedback người dùng
- Dark/Light theme support
- Mobile-first responsive design

## Cấu trúc dự án

```
laravel/
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php        # Layout chính
│       ├── auth/
│       │   └── login.blade.php      # Trang đăng nhập
│       ├── dashboard.blade.php      # Trang chủ
│       ├── accounts/
│       │   └── index.blade.php      # Quản lý tài khoản
│       ├── subjects/
│       │   └── index.blade.php      # Quản lý môn học
│       ├── classes/
│       │   └── index.blade.php      # Quản lý lớp học phần
│       ├── schedules/
│       │   └── index.blade.php      # Lịch học & điểm danh
│       ├── data-check.blade.php     # Kiểm tra dữ liệu
│       └── reports/
│           └── index.blade.php      # Xuất báo cáo
├── public/
│   ├── css/
│   │   └── custom.css              # CSS tùy chỉnh
│   └── js/
│       └── app.js                  # JavaScript chính
└── routes/
    └── web.php                     # Định nghĩa routes
```

## Cài đặt và chạy

### Yêu cầu hệ thống
- PHP >= 8.0
- Composer
- Laravel >= 9.0
- MySQL/PostgreSQL
- Node.js & NPM (optional)

### Hướng dẫn cài đặt

1. **Clone repository**
```bash
git clone <repository-url>
cd Web_QTV/laravel
```

2. **Cài đặt dependencies**
```bash
composer install
```

3. **Cấu hình environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Cấu hình database trong file .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=htd_attendance
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Chạy migrations**
```bash
php artisan migrate
```

6. **Seed dữ liệu mẫu (optional)**
```bash
php artisan db:seed
```

7. **Khởi động server**
```bash
php artisan serve
```

8. **Truy cập ứng dụng**
```
http://localhost:8000
```

## Sử dụng

### Đăng nhập
- Truy cập trang chủ sẽ tự động chuyển hướng đến trang đăng nhập
- Sử dụng email và mật khẩu được cấp để đăng nhập
- Sau khi đăng nhập thành công, hệ thống chuyển đến Dashboard

### Navigation
- Sử dụng sidebar để điều hướng giữa các trang
- Trên mobile, nhấn icon menu để mở/đóng sidebar
- Active page được highlight rõ ràng

### Quản lý dữ liệu
- Sử dụng nút "Thêm" để tạo mới
- Click vào nút "Sửa" để chỉnh sửa
- Xác nhận trước khi xóa dữ liệu
- Sử dụng bộ lọc để tìm kiếm nhanh

### Xuất báo cáo
- Chọn lớp học phần và khoảng thời gian
- Chọn định dạng file mong muốn
- Click "Xuất báo cáo" để tải xuống

## Công nghệ sử dụng

### Backend
- **Laravel 9+**: PHP Framework chính
- **MySQL**: Cơ sở dữ liệu
- **Blade Templates**: Template engine

### Frontend  
- **Bootstrap 5**: CSS Framework
- **jQuery**: JavaScript library
- **Font Awesome**: Icons
- **Chart.js**: Biểu đồ thống kê
- **SweetAlert2**: Modal alerts đẹp

### Styling
- **Custom CSS**: Tùy chỉnh giao diện
- **CSS Grid & Flexbox**: Layout responsive
- **CSS Animations**: Hiệu ứng mượt mà
- **Custom Scrollbar**: Thanh cuộn đẹp

---

© 2025 HTD Face Recognition System. All rights reserved.

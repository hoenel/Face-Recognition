<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-database-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore-compat.js"></script>
    <style>
        body { background-color: #f4f6f9; font-family: Arial, sans-serif; }
        .sidebar { min-height: 100vh; background-color: #1f2a44; color: #fff; padding: 20px; }
        .sidebar h4 { color: #ecf0f1; font-weight: bold; text-align: center; }
        .sidebar a { display: block; padding: 10px; color: #bdc3c7; text-decoration: none; border-radius: 5px; margin-bottom: 10px; }
        .sidebar a.active, .sidebar a:hover { background: #34495e; color: #fff; }
        .main-content { padding: 30px; }
    </style>
    @yield('head')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-2 sidebar">
            <h4>HTD - Điểm Danh</h4>
            <a href="{{ route('tongquan') }}" class="@yield('active-tongquan')">Tổng quan lớp học</a>
            <a href="{{ route('lich') }}" class="@yield('active-lich')">Lịch giảng dạy</a>
            <a href="{{ route('taobuoihoc') }}" class="@yield('active-taobuoihoc')">Tạo buổi học / điểm danh</a>
            <a href="{{ route('trangthaidiemdanh') }}" class="@yield('active-trangthaidiemdanh')">Trạng thái điểm danh</a>
            <a href="{{ route('thongkechuyencan') }}" class="@yield('active-thongkechuyencan')">Thống kê chuyên cần</a>
            <a href="{{ route('xuatbaocao') }}" class="@yield('active-xuatbaocao')">Xuất báo cáo</a>
            <a href="{{ route('dangnhap') }}" class="@yield('active-dangnhap')">Đăng xuất</a>
        </div>
        <div class="col-10 main-content">
            @yield('content')
        </div>
    </div>
</div>
<script>
const firebaseConfig = {
    apiKey: "AIzaSyAygULXRSt9Nsqy2rb9Z4NNvh4Z4KvdK7c",
    authDomain: "facerecognitionapp-f034d.firebaseapp.com",
    databaseURL: "https://facerecognitionapp-f034d-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "facerecognitionapp-f034d",
    storageBucket: "facerecognitionapp-f034d.firebasestorage.app",
    messagingSenderId: "1042946521446",
    appId: "1:1042946521446:web:02de5802629d422a5330a7"
};
firebase.initializeApp(firebaseConfig);
</script>
@yield('scripts')
</body>
</html>

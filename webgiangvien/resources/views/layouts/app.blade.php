<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-database-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-auth-compat.js"></script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #f8fafc;
        }
        .sidebar {
            min-height: 100vh;
            width: 220px;
            background: linear-gradient(180deg, #243B55, #141E30);
            color: #fff;
            padding: 30px 10px 10px 10px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.04);
            position: fixed;
            top: 0; left: 0;
            border-radius: 0 20px 20px 0;
        }
        .sidebar h4 {
            color: #ecf0f1;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
            font-size: 22px;
            letter-spacing: 1px;
        }
        .sidebar a {
            display: block;
            padding: 12px 18px;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .sidebar a.active, .sidebar a:hover {
            background: #2e3d5c;
            color: #fff;
        }
        .main-content {
            margin-left: 220px;
            padding: 40px 30px;
        }
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

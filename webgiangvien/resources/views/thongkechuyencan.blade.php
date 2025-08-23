<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê chuyên cần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background-color: #f5f6f8;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(180deg, #243B55, #141E30);
            color: #fff;
            padding-top: 15px;
        }
        .sidebar h5 {
            text-align: center;
            margin-bottom: 25px;
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #1d2d44;
            border-radius: 4px;
        }
        .header {
            margin-left: 220px;
            padding: 15px 20px;
            background: #2f3640;
            color: #fff;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h5>HTD - Điểm Danh</h5>
        <a href="{{ route('tongquan') }}">Tổng quan lớp học</a>
        <a href="{{ route('lich') }}">Lịch giảng dạy</a>
        <a href="{{ route('taobuoihoc') }}">Tạo buổi học / điểm danh</a>
        <a href="{{ route('trangthaidiemdanh') }}">Trạng thái điểm danh</a>
        <a href="{{ route('thongkechuyencan') }}" class="active">Thống kê chuyên cần</a>
        <a href="{{ route('xuatbaocao') }}">Xuất báo cáo</a>
        <a href="{{ route('dangnhap') }}">Đăng xuất</a>
    </div>

    <!-- Header -->
    <div class="header">
        Thống kê chuyên cần
    </div>

    <!-- Content -->
    <div class="content">
        <div class="card">
            <h5 class="mb-3">Thống kê chuyên cần</h5>

            <div class="mb-3 row">
                <label class="col-sm-1 col-form-label">Lớp:</label>
                <div class="col-sm-3">
                    <select class="form-select">
                        <option>[DHKTPM17A]</option>
                    </select>
                </div>

                <label class="col-sm-1 col-form-label">Môn học:</label>
                <div class="col-sm-3">
                    <select class="form-select">
                        <option>Lập trình ứng dụng di động</option>
                    </select>
                </div>
            </div>

           
            <h2>Thống kê chuyên cần</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Tên SV</th>
                        <th>Số buổi có mặt</th>
                        <th>Tổng số buổi</th>
                        <th>Tỷ lệ chuyên cần</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dữ liệu sẽ được đổ từ Firebase -->
                </tbody>
            </table>
            <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js"></script>
            <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js"></script>
            <script>
              const firebaseConfig = {
                    apiKey: "AIzaSyAygULXRSt9Nsqy2rb9Z4NNvh4Z4KvdK7c",
                    authDomain: "facerecognitionapp-f034d.firebaseapp.com",
                    databaseURL: "https://facerecognitionapp-f034d-default-rtdb.asia-southeast1.firebasedatabase.app",
                    projectId: "facerecognitionapp-f034d",
                    storageBucket: "facerecognitionapp-f034d.firebasestorage.app",
                    messagingSenderId: "1042946521446",
                    appId: "1:1042946521446:web:02de5802629d422a5330a7"
                    measurementId: "G-DWMNDS01TZ"
              };
              firebase.initializeApp(firebaseConfig);
              document.addEventListener('DOMContentLoaded', function() {
                const tbody = document.querySelector('table tbody');
                firebase.database().ref('chuyencan').on('value', function(snapshot) {
                    tbody.innerHTML = '';
                    const data = snapshot.val();
                    if(data) {
                        Object.values(data).forEach(function(item) {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${item.masv || ''}</td>
                                <td>${item.tensv || ''}</td>
                                <td>${item.sobuoi || ''}</td>
                                <td>${item.tongbuoi || ''}</td>
                                <td>${item.tyle || ''}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu</td></tr>';
                    }
                });
              });
            </script>
        </div
    </div>
</body>
</html>

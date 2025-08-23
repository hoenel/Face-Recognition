<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trạng thái điểm danh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .sidebar {
            height: 100vh;
            background-color: #1f2a44;
            padding: 20px;
            color: white;
        }
        .sidebar h4 {
            color: #ecf0f1;
            font-weight: bold;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #bdc3c7;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #34495e;
            border-radius: 5px;
            color: #fff;
        }
        .content {
            padding: 20px;
        }
        .card {
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-2 sidebar">
            <h4>HTD - Diem_Danh</h4>
            <a href="{{ route('tongquan') }}">Tổng quan lớp học</a>
            <a href="{{ route('lich') }}">Lịch giảng dạy</a>
            <a href="{{ route('taobuoihoc') }}">Tạo buổi học / điểm danh</a>
            <a href="{{ route('trangthaidiemdanh') }}">Trạng thái điểm danh</a>
            <a href="{{ route('thongkechuyencan') }}" class="active">Thống kê chuyên cần</a>
            <a href="{{ route('xuatbaocao') }}">Xuất báo cáo</a>
            <a href="{{ route('dangnhap') }}">Đăng xuất</a>
        </div>

        <!-- Nội dung -->
        <div class="col-10 content">
            <div class="card">
                <h5 class="mb-3">Trạng thái điểm danh</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã SV</th>
                            <th>Tên SV</th>
                            <th>Ngày</th>
                            <th>Trạng thái</th>
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
                    firebase.database().ref('trangthai').on('value', function(snapshot) {
                        tbody.innerHTML = '';
                        const data = snapshot.val();
                        if(data) {
                            Object.values(data).forEach(function(item) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${item.masv || ''}</td>
                                    <td>${item.tensv || ''}</td>
                                    <td>${item.ngay || ''}</td>
                                    <td>${item.trangthai || ''}</td>
                                `;
                                tbody.appendChild(tr);
                            });
                        } else {
                            tbody.innerHTML = '<tr><td colspan="4">Không có dữ liệu</td></tr>';
                        }
                    });
                  });
                </script>
                <form class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Lớp</label>
                        <select class="form-select">
                            <option>DHCPMT12A</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Môn học</label>
                        <select class="form-select">
                            <option>Lập trình ứng dụng di động</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ngày giảng dạy</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tiết học</label>
                        <select class="form-select">
                            <option>1-3</option>
                        </select>
                    </div>
                </form>

                <!-- Bảng danh sách -->
                <h6 class="mt-3">Trạng thái điểm danh buổi học:</h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>MSSV</th>
                            <th>Họ tên</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2012345</td>
                            <td>Nguyễn Văn A</td>
                            <td>Có mặt</td>
                        </tr>
                        <tr>
                            <td>2012346</td>
                            <td>Trần Thị B</td>
                            <td>Muộn</td>
                        </tr>
                        <tr>
                            <td>2012350</td>
                            <td>Phạm Văn D</td>
                            <td>Vắng</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tổng quan lớp học</title>
    <!-- Firebase SDK đã được import và khởi tạo từ layout -->
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #1f2a44;
            color: #fff;
            padding: 20px 10px;
        }
        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
            text-align: center;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background: #2e3d5c;
        }

        /* Main content */
        .main {
            flex: 1;
            background: #f4f6f9;
            padding: 20px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #1f2a44;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>HTD - Điểm Danh</h2>
        <a href="{{ route('tongquan') }}">Tổng quan lớp học</a>
        <a href="{{ route('lich') }}">Lịch giảng dạy</a>
        <a href="{{ route('taobuoihoc') }}">Tạo buổi học / điểm danh</a>
        <a href="{{ route('trangthaidiemdanh') }}">Trạng thái điểm danh</a>
        <a href="{{ route('thongkechuyencan') }}" class="active">Thống kê chuyên cần</a>
        <a href="{{ route('xuatbaocao') }}">Xuất báo cáo</a>
        <a href="{{ route('dangnhap') }}">Đăng xuất</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="card">
            <h2>Tổng quan lớp học</h2>
            <table>
                <thead>
                    <tr>
                        <th>Mã lớp học phần</th>
                        <th>Tên môn học</th>
                        <th>Số tiết dạy</th>
                        <th>Tổng số SV</th>
                        <th>Tỷ lệ chuyên cần</th>
                    </tr>
                </thead>
                <tbody>
                        <!-- Dữ liệu sẽ được đổ từ Firebase -->
                    </tbody>
                </table>
                <script>
                // Lấy dữ liệu từ Firebase và hiển thị lên bảng
                document.addEventListener('DOMContentLoaded', function() {
                    const tbody = document.querySelector('table tbody');
                    firebase.database().ref('tongquan').on('value', function(snapshot) {
                        tbody.innerHTML = '';
                        const data = snapshot.val();
                        if(data) {
                            Object.values(data).forEach(function(item) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${item.malop || ''}</td>
                                    <td>${item.tenmon || ''}</td>
                                    <td>${item.sotiet || ''}</td>
                                    <td>${item.tongsv || ''}</td>
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
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

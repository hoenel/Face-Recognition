<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan lớp học</title>
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
        <a href="#">Tổng quan lớp học</a>
        <a href="#">Lịch dạy</a>
        <a href="#">Tạo buổi học</a>
        <a href="#">Điểm danh</a>
        <a href="#">Trạng thái điểm danh</a>
        <a href="#">Thống kê</a>
        <a href="#">Báo cáo</a>
        <a href="#">Đăng xuất</a>
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
                    <tr>
                        <td>CNTT2023</td>
                        <td>Cơ sở dữ liệu</td>
                        <td>5</td>
                        <td>30</td>
                        <td>90%</td>
                    </tr>
                    <tr>
                        <td>CNTT2024</td>
                        <td>Lập trình Java</td>
                        <td>4</td>
                        <td>28</td>
                        <td>85%</td>
                    </tr>
                    <tr>
                        <td>CNTT2025</td>
                        <td>Trí tuệ nhân tạo</td>
                        <td>6</td>
                        <td>25</td>
                        <td>88%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

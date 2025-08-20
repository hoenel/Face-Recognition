<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch giảng dạy</title>
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
        .filters {
            margin-bottom: 20px;
        }
        .filters select, .filters input, .filters button {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .event {
            color: #fff;
            padding: 4px;
            border-radius: 4px;
            margin: 2px 0;
            display: block;
        }
        .blue { background: #007bff; }
        .green { background: #28a745; }
        .orange { background: #fd7e14; }
        .red { background: #dc3545; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>HTD - Điểm Danh</h2>
        <a href="/dashboard">Tổng quan lớp học</a>
        <a href="/schedule">Lịch giảng dạy</a>
        <a href="#">Tạo buổi học / Điểm danh</a>
        <a href="#">Điểm danh lớp học</a>
        <a href="#">Trạng thái điểm danh</a>
        <a href="#">Thống kê chuyên cần</a>
        <a href="#">Xuất báo cáo</a>
        <a href="#">Đăng xuất</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="card">
            <h2>Lịch giảng dạy</h2>

            <!-- Bộ lọc -->
            <div class="filters">
                <select>
                    <option>Lớp: CNTT2023</option>
                    <option>Lớp: CNTT2024</option>
                </select>
                <select>
                    <option>Môn học: CSDL</option>
                    <option>Môn học: Java</option>
                </select>
                <input type="date">
                <select>
                    <option>Học kỳ 1</option>
                    <option>Học kỳ 2</option>
                </select>
                <button>Lọc</button>
            </div>

            <!-- Bảng lịch -->
            <table>
                <thead>
                    <tr>
                        <th>T2</th>
                        <th>T3</th>
                        <th>T4</th>
                        <th>T5</th>
                        <th>T6</th>
                        <th>T7</th>
                        <th>CN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>6</td>
                        <td>7</td>
                        <td>8</td>
                        <td>9</td>
                        <td>10</td>
                        <td>11</td>
                        <td>12</td>
                    </tr>
                    <tr>
                        <td>
                            <span class="event blue">Java - P301</span>
                        </td>
                        <td>
                            <span class="event green">CSDL - P302</span>
                        </td>
                        <td>
                            <span class="event orange">AI - P303</span>
                        </td>
                        <td>
                            <span class="event red">Python - P304</span>
                        </td>
                        <td></td>
                        <td>
                            <span class="event blue">Web - P305</span>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

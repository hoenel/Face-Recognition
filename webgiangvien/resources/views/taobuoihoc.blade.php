<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo buổi học / điểm danh</title>
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
    <div class="sidebar">
        <h3>HTD - Diem_Danh</h3>
        <a href="{{ route('tongquan') }}">Tổng quan lớp học</a>
        <a href="{{ route('lich') }}">Lịch giảng dạy</a>
        <a href="{{ route('taobuoihoc') }}">Tạo buổi học / điểm danh</a>
        <a href="{{ route('trangthaidiemdanh') }}">Trạng thái điểm danh</a>
        <a href="{{ route('thongkechuyencan') }}" class="active">Thống kê chuyên cần</a>
        <a href="{{ route('xuatbaocao') }}">Xuất báo cáo</a>
        <a href="{{ route('dangnhap') }}">Đăng xuất</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Tạo buổi học / điểm danh</h2>

            <div class="form-group">
                <label for="lop">Lớp</label>
                <select id="lop">
                    <option>DHKTMT7A</option>
                    <option>DHKTMT8B</option>
                </select>
            </div>

            <div class="form-group">
                <label for="monhoc">Môn học</label>
                <select id="monhoc">
                    <option>Lập trình ứng dụng di động</option>
                    <option>Cơ sở dữ liệu</option>
                    <option>Mạng máy tính</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ngay">Ngày giảng dạy</label>
                <input type="date" id="ngay">
            </div>

            <div class="form-group">
                <label for="tiet">Tiết học</label>
                <input type="text" id="tiet" placeholder="VD: 1-3">
            </div>

            <button onclick="themBuoiHoc()">Tạo buổi học & mở điểm danh</button>

            <div class="session-list" id="dsBuoiHoc">
                <h3>Danh sách buổi học đã mở trong tháng:</h3>
                <div class="session-item">DHKTMT7A - Lập trình ứng dụng di động - 2025-07-31 - Tiết: 1-3</div>
                <div class="session-item">DHKTMT7B - Cơ sở dữ liệu - 2025-07-31 - Tiết: 4-6</div>
                <div class="session-item">DHKTMT8A - Mạng máy tính - 2025-08-01 - Tiết: 1-3</div>
                <div class="session-item">DHKTMT8B - Hệ điều hành - 2025-08-02 - Tiết: 4-6</div>
            </div>
        </div>
    </div>

    <script>
        function themBuoiHoc() {
            let lop = document.getElementById("lop").value;
            let monhoc = document.getElementById("monhoc").value;
            let ngay = document.getElementById("ngay").value;
            let tiet = document.getElementById("tiet").value;

            if(lop && monhoc && ngay && tiet){
                let div = document.createElement("div");
                div.className = "session-item";
                div.innerText = lop + " - " + monhoc + " - " + ngay + " - Tiết: " + tiet;
                document.getElementById("dsBuoiHoc").appendChild(div);
                alert("Đã tạo buổi học thành công!");
            } else {
                alert("Vui lòng nhập đầy đủ thông tin!");
            }
        }
    </script>
</body>
</html>

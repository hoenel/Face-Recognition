<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xuất báo cáo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #2e2e2e;
    }
    .sidebar {
      min-height: 100vh;
      background-color: #1f3a4d;
      color: #fff;
      padding-top: 20px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 10px 15px;
      margin: 5px 0;
      border-radius: 6px;
    }
    .sidebar a:hover {
      background-color: #324c63;
    }
    .header {
      background-color: #3c3c3c;
      color: white;
      padding: 10px 20px;
      font-weight: bold;
    }
    .content {
      background-color: #f5f6f7;
      padding: 20px;
      border-radius: 6px;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <div class="header">
    Xuất báo cáo
  </div>

  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
      <h5>HTD - Diem_Danh</h5>
        <a href="{{ route('tongquan') }}">Tổng quan lớp học</a>
        <a href="{{ route('lich') }}">Lịch giảng dạy</a>
        <a href="{{ route('taobuoihoc') }}">Tạo buổi học / điểm danh</a>
        <a href="{{ route('trangthaidiemdanh') }}">Trạng thái điểm danh</a>
        <a href="{{ route('thongkechuyencan') }}" class="active">Thống kê chuyên cần</a>
        <a href="{{ route('xuatbaocao') }}">Xuất báo cáo</a>
        <a href="{{ route('dangnhap') }}">Đăng xuất</a>
    </div>

    <!-- Nội dung -->
    <div class="flex-grow-1 p-4">
      <div class="content">
        <h5>Xuất báo cáo chuyên cần</h5>
        <form class="mt-3">
          <div class="mb-3">
            <label for="lop" class="form-label">Lớp:</label>
            <select class="form-select" id="lop">
              <option>DS17CNTT</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="mon" class="form-label">Môn học:</label>
            <select class="form-select" id="mon">
              <option>Lập trình cơ bản</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success">Xuất báo cáo</button>
        </form>
        <div class="alert alert-success mt-3" role="alert">
          Báo cáo đã được xuất thành công!
        </div>
      </div>
    </div>
  </div>
</body>
</html>

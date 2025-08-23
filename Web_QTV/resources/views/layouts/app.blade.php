<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HTD - Điểm danh')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        
        .main-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 200px;
            background: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            background: #34495e;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #3f4f5f;
        }
        
        .sidebar-title {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            color: white;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid #3f4f5f;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #3498db;
            color: white;
        }
        
        .content-area {
            margin-left: 200px;
            flex: 1;
            background: #f8f9fa;
        }
        
        .content-header {
            background: white;
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .content-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        .content-body {
            padding: 30px;
        }
        
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background: #3498db;
            color: white;
            border: none;
            font-weight: 500;
            font-size: 13px;
            padding: 12px;
        }
        
        .table td {
            padding: 12px;
            font-size: 13px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 4px;
        }
        
        .btn-success {
            background: #27ae60;
            border-color: #27ae60;
        }
        
        .btn-danger {
            background: #e74c3c;
            border-color: #e74c3c;
        }
        
        .btn-primary {
            background: #3498db;
            border-color: #3498db;
        }
        
        .badge {
            font-size: 11px;
            padding: 4px 8px;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-size: 12px;
            border-top: 1px solid #e9ecef;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h5 class="sidebar-title">Admin - điểm_danh</h5>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Trang chủ</a></li>
                <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">Quản lý tài khoản</a></li>
                <li><a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.*') ? 'active' : '' }}">Quản lý môn học</a></li>
                <li><a href="{{ route('classrooms.index') }}" class="{{ request()->routeIs('classrooms.*') ? 'active' : '' }}">Quản lý lớp học phần</a></li>
                <li><a href="{{ route('attendances.index') }}" class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}">Lịch học & Điểm danh</a></li>
                <li><a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">Kiểm tra dữ liệu</a></li>
                <li><a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">Xuất báo cáo</a></li>
                <li><a href="{{ route('logout') }}">Đăng xuất</a></li>
            </ul>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <div class="content-header">
                <h1 class="content-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            
            <div class="content-body">
                @yield('content')
            </div>
            
            <div class="footer">
                Hệ thống điểm danh - Trường Đại học Thủy lợi
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

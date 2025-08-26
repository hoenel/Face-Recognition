<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống điểm danh - HTD')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 220px;
            background-color: #2c4a6b;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar .logo {
            padding: 20px 15px;
            text-align: left;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .logo h5 {
            margin: 0;
            font-weight: 500;
            font-size: 1rem;
            color: white;
        }
        
        .sidebar .nav-link {
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            border: none;
            background: none;
            transition: background-color 0.2s;
            font-size: 0.85rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #ffffff;
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 16px;
            margin-right: 10px;
            font-size: 0.85rem;
        }
        
        .sidebar .nav-bottom {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 15px;
        }
        
        .main-content {
            margin-left: 220px;
            min-height: 100vh;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .nav-user-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #374151;
            color: #9CA3AF;
            text-align: center;
        }
        
        .nav-user-info .fa-user-circle {
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h5>Admin - điểm danh</h5>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Trang chủ
            </a>
            <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                <i class="fas fa-users"></i>
                Quản lý tài khoản
            </a>
            <a class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}">
                <i class="fas fa-book"></i>
                Tổng quan lớp học
            </a>
            <a class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                <i class="fas fa-chalkboard-teacher"></i>
                tạo buổi học / điểm danh
            </a>
            <a class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}" href="{{ route('schedules.index') }}">
                <i class="fas fa-calendar-alt"></i>
                Lịch giảng dạy
            </a>
            <a class="nav-link {{ request()->routeIs('data-check') ? 'active' : '' }}" href="{{ route('data-check') }}">
                <i class="fas fa-database"></i>
                Kiểm tra dữ liệu
            </a>
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="fas fa-chart-bar"></i>
                Xuất báo cáo
            </a>
            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                Đăng xuất
            </a>
        </nav>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>

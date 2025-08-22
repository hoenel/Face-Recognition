<style>
.custom-sidebar {
    background: #2c3e50 !important;
    width: 220px;
    min-height: 100vh;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.custom-sidebar .nav-link {
    color: rgba(255,255,255,0.9) !important;
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 3px;
    transition: all 0.2s ease;
}

.custom-sidebar .nav-link:hover {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
}

.custom-sidebar .nav-link.active {
    background: rgba(255,255,255,0.15) !important;
    color: white !important;
    font-weight: 600;
}

.sidebar-header {
    padding: 20px 15px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 15px;
}

.sidebar-header h5 {
    color: white;
    font-weight: 700;
    margin: 0;
    font-size: 1.1rem;
}

.logout-section {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.logout-btn {
    background: transparent !important;
    border: none !important;
    color: rgba(255,255,255,0.9) !important;
    transition: all 0.2s ease;
    font-size: 14px;
    padding: 12px 15px;
    border-radius: 6px;
    width: 100%;
    text-align: left;
}

.logout-btn:hover {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
}
</style>

<div class="custom-sidebar text-white p-0">
    <div class="sidebar-header">
        <h5>Admin - diem_danh</h5>
    </div>
    <div class="p-3">
                <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i>Tổng quan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>Quản lý tài khoản
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i>Quản lý môn học
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('classrooms.index') }}" class="nav-link {{ request()->routeIs('classrooms.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Quản lý lớp học phần
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check me-2"></i>Lịch học & Điểm danh
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('data.index') }}" class="nav-link {{ request()->routeIs('data.*') ? 'active' : '' }}">
                    <i class="fas fa-database me-2"></i>Kiểm tra dữ liệu
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i>Xuất báo cáo
                </a>
            </li>
        </ul>
        
                <!-- Logout Section -->
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                </button>
            </form>
        </div>
    </div>
</div>

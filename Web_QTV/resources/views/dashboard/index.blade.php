@extends('layouts.app')

@section('title', 'Dashboard - Hệ thống điểm danh')

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <!-- Top Header -->
            <div class="col-12">
                <div class="top-header bg-white shadow-sm p-3 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="text-primary fw-bold mb-0">
                                <i class="fas fa-graduation-cap me-2"></i>
                                HỆ THỐNG QUẢN LÝ ĐIỂM DANH SINH VIÊN
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="user-info d-inline-flex align-items-center">
                                <div class="me-3">
                                    <small class="text-muted d-block">Xin chào,</small>
                                    <strong class="text-dark">{{ $user['name'] }}</strong>
                                </div>
                                <div class="user-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation Cards -->
        <div class="row g-4">
            <!-- Quản lý tài khoản -->
            <div class="col-lg-4 col-md-6">
                <div class="nav-card">
                    <a href="{{ route('users.index') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-circle bg-primary bg-opacity-10 mx-auto mb-3">
                                    <i class="fas fa-users text-primary fa-2x"></i>
                                </div>
                                <h5 class="card-title text-dark fw-bold">QUẢN LÝ TÀI KHOẢN</h5>
                                <p class="card-text text-muted">Quản lý thông tin sinh viên, giáo viên và admin</p>
                                <div class="mt-3">
                                    <span class="badge bg-primary">1,234 Tài khoản</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Quản lý môn học -->
            <div class="col-lg-4 col-md-6">
                <div class="nav-card">
                    <a href="{{ route('subjects.index') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-circle bg-success bg-opacity-10 mx-auto mb-3">
                                    <i class="fas fa-book text-success fa-2x"></i>
                                </div>
                                <h5 class="card-title text-dark fw-bold">QUẢN LÝ MÔN HỌC</h5>
                                <p class="card-text text-muted">Thêm, sửa, xóa các môn học trong hệ thống</p>
                                <div class="mt-3">
                                    <span class="badge bg-success">156 Môn học</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Quản lý lớp học phần -->
            <div class="col-lg-4 col-md-6">
                <div class="nav-card">
                    <a href="{{ route('classrooms.index') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-circle bg-info bg-opacity-10 mx-auto mb-3">
                                    <i class="fas fa-chalkboard text-info fa-2x"></i>
                                </div>
                                <h5 class="card-title text-dark fw-bold">QUẢN LÝ LỚP HỌC PHẦN</h5>
                                <p class="card-text text-muted">Tạo và quản lý các lớp học phần</p>
                                <div class="mt-3">
                                    <span class="badge bg-info">24 Lớp học</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Lịch học và điểm danh -->
            <div class="col-lg-4 col-md-6">
                <div class="nav-card">
                    <a href="{{ route('attendances.index') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-circle bg-warning bg-opacity-10 mx-auto mb-3">
                                    <i class="fas fa-calendar-check text-warning fa-2x"></i>
                                </div>
                                <h5 class="card-title text-dark fw-bold">LỊCH HỌC VÀ ĐIỂM DANH</h5>
                                <p class="card-text text-muted">Xem lịch học và thực hiện điểm danh</p>
                                <div class="mt-3">
                                    <span class="badge bg-warning">87% Có mặt hôm nay</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Kiểm tra dữ liệu -->
            <div class="col-lg-4 col-md-6">
                <div class="nav-card">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-circle bg-danger bg-opacity-10 mx-auto mb-3">
                                    <i class="fas fa-search text-danger fa-2x"></i>
                                </div>
                                <h5 class="card-title text-dark fw-bold">KIỂM TRA DỮ LIỆU</h5>
                                <p class="card-text text-muted">Kiểm tra và xác minh dữ liệu hệ thống</p>
                                <div class="mt-3">
                                    <span class="badge bg-danger">Cần kiểm tra</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Xuất báo cáo -->
            <div class="col-lg-4 col-md-6">
                <div class="nav-card">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="icon-circle bg-secondary bg-opacity-10 mx-auto mb-3">
                                    <i class="fas fa-file-export text-secondary fa-2x"></i>
                                </div>
                                <h5 class="card-title text-dark fw-bold">XUẤT BÁO CÁO</h5>
                                <p class="card-text text-muted">Tạo và xuất các báo cáo thống kê</p>
                                <div class="mt-3">
                                    <span class="badge bg-secondary">Excel/PDF</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 text-dark fw-bold">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            THỐNG KÊ TỔNG QUAN
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="stat-item p-3 rounded bg-primary bg-opacity-10">
                                    <h3 class="text-primary fw-bold mb-1">1,234</h3>
                                    <p class="text-muted mb-0">Tổng sinh viên</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="stat-item p-3 rounded bg-success bg-opacity-10">
                                    <h3 class="text-success fw-bold mb-1">156</h3>
                                    <p class="text-muted mb-0">Môn học</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="stat-item p-3 rounded bg-info bg-opacity-10">
                                    <h3 class="text-info fw-bold mb-1">24</h3>
                                    <p class="text-muted mb-0">Lớp học phần</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="stat-item p-3 rounded bg-warning bg-opacity-10">
                                    <h3 class="text-warning fw-bold mb-1">87%</h3>
                                    <p class="text-muted mb-0">Tỷ lệ có mặt</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.dashboard-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.top-header {
    border-radius: 15px;
    border-left: 5px solid var(--primary-color);
}

.nav-card {
    transition: transform 0.3s ease;
}

.nav-card:hover {
    transform: translateY(-5px);
}

.hover-lift {
    transition: all 0.3s ease;
    border-radius: 15px !important;
}

.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.hover-lift:hover .icon-circle {
    transform: scale(1.1);
}

.card-title {
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.card-text {
    font-size: 0.9rem;
    line-height: 1.5;
}

.stat-item {
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: scale(1.05);
}

.user-avatar {
    transition: transform 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.1);
}

/* Animation cho cards */
.nav-card {
    animation: fadeInUp 0.6s ease-out;
}

.nav-card:nth-child(1) { animation-delay: 0.1s; }
.nav-card:nth-child(2) { animation-delay: 0.2s; }
.nav-card:nth-child(3) { animation-delay: 0.3s; }
.nav-card:nth-child(4) { animation-delay: 0.4s; }
.nav-card:nth-child(5) { animation-delay: 0.5s; }
.nav-card:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile responsive */
@media (max-width: 768px) {
    .top-header h4 {
        font-size: 1.2rem;
    }
    
    .user-info {
        margin-top: 10px;
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .icon-circle {
        width: 60px;
        height: 60px;
    }
    
    .icon-circle i {
        font-size: 1.5rem !important;
    }
}
</style>
@endpush
@endsection

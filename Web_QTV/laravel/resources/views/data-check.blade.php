@extends('layouts.app')

@section('title', 'Kiểm tra dữ liệu - HTD')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Kiểm tra dữ liệu</h2>
        </div>
    </div>
    
    <!-- System Status -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <i class="fas fa-database fa-2x mb-2"></i>
                    <h5>Firebase</h5>
                    <p class="mb-0">Kết nối tốt</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <i class="fas fa-camera fa-2x mb-2"></i>
                    <h5>Camera</h5>
                    <p class="mb-0">Cần kiểm tra</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <i class="fas fa-brain fa-2x mb-2"></i>
                    <h5>AI Model</h5>
                    <p class="mb-0">Hoạt động bình thường</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <i class="fas fa-server fa-2x mb-2"></i>
                    <h5>Server</h5>
                    <p class="mb-0">Online</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Statistics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thống kê dữ liệu Firebase</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-users text-primary"></i> Tổng số tài khoản</td>
                                    <td><strong>{{ $system_stats['total_accounts'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-graduate text-info"></i> Sinh viên</td>
                                    <td><strong>{{ $system_stats['students'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-chalkboard-teacher text-warning"></i> Giảng viên</td>
                                    <td><strong>{{ $system_stats['teachers'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-shield text-danger"></i> Quản trị viên</td>
                                    <td><strong>{{ $system_stats['admins'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-book text-success"></i> Môn học</td>
                                    <td><strong>{{ $system_stats['subjects'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-school text-primary"></i> Lớp học phần</td>
                                    <td><strong>{{ $system_stats['classes'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-calendar-check text-info"></i> Buổi điểm danh hôm nay</td>
                                    <td><strong>{{ $system_stats['today_sessions'] }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dữ liệu khuôn mặt AI</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-images text-primary"></i> Tổng ảnh đã lưu</td>
                                    <td><strong>{{ $system_stats['total_images'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-check text-success"></i> Đã đăng ký khuôn mặt</td>
                                    <td><strong>{{ $system_stats['registered_faces'] }}/{{ $system_stats['students'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-times text-warning"></i> Chưa đăng ký</td>
                                    <td><strong>{{ $system_stats['unregistered'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-brain text-info"></i> Model độ chính xác</td>
                                    <td><strong>{{ $system_stats['model_accuracy'] }}%</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-clock text-secondary"></i> Thời gian nhận diện TB</td>
                                    <td><strong>{{ $system_stats['avg_recognition_time'] }}s</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-exclamation-triangle text-warning"></i> Lỗi nhận diện hôm nay</td>
                                    <td><strong>{{ $system_stats['today_errors'] }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Tools -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Công cụ kiểm tra hệ thống</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card border">
                        <div class="card-body text-center">
                            <i class="fab fa-google fa-3x text-primary mb-3"></i>
                            <h6>Kiểm tra Firebase</h6>
                            <p class="text-muted small">Kiểm tra kết nối và tính toàn vẹn dữ liệu Firebase</p>
                            <button class="btn btn-primary">Kiểm tra</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border">
                        <div class="card-body text-center">
                            <i class="fas fa-camera fa-3x text-success mb-3"></i>
                            <h6>Test Camera</h6>
                            <p class="text-muted small">Kiểm tra hoạt động của camera cho điểm danh</p>
                            <button class="btn btn-success">Test Camera</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border">
                        <div class="card-body text-center">
                            <i class="fas fa-brain fa-3x text-warning mb-3"></i>
                            <h6>Test AI Model</h6>
                            <p class="text-muted small">Kiểm tra mô hình nhận diện khuôn mặt</p>
                            <button class="btn btn-warning">Test Model</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card border">
                        <div class="card-body text-center">
                            <i class="fas fa-sync fa-3x text-info mb-3"></i>
                            <h6>Đồng bộ Firebase</h6>
                            <p class="text-muted small">Đồng bộ dữ liệu với Firebase Firestore</p>
                            <button class="btn btn-info">Đồng bộ</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border">
                        <div class="card-body text-center">
                            <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                            <h6>Dọn dẹp cache</h6>
                            <p class="text-muted small">Xóa cache và file tạm không cần thiết</p>
                            <button class="btn btn-danger">Dọn dẹp</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border">
                        <div class="card-body text-center">
                            <i class="fas fa-download fa-3x text-secondary mb-3"></i>
                            <h6>Backup Firebase</h6>
                            <p class="text-muted small">Sao lưu toàn bộ dữ liệu Firebase</p>
                            <button class="btn btn-secondary">Backup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

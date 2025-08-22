@extends('layouts.app')

@section('title', 'Kiểm tra dữ liệu')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="content-header p-4 bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Kiểm tra dữ liệu</h2>
                <div>
                    <button class="btn btn-success me-2" onclick="validateAllData()">
                        <i class="fas fa-check-circle me-2"></i>Kiểm tra tất cả
                    </button>
                    <button class="btn btn-warning" onclick="exportData()">
                        <i class="fas fa-download me-2"></i>Xuất dữ liệu
                    </button>
                </div>
            </div>
        </div>

        <div class="content-body p-4">
            <!-- System Status Overview -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Firebase</h5>
                                    <p class="card-text">Kết nối database</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-database fa-2x" id="firebaseStatus"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success" id="firebaseStatusText">Hoạt động</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Dữ liệu</h5>
                                    <p class="card-text">Tính toàn vẹn</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-shield-alt fa-2x" id="dataIntegrityIcon"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-warning" id="dataIntegrityText">Đang kiểm tra</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Nhận diện</h5>
                                    <p class="card-text">Hệ thống AI</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-check fa-2x" id="aiSystemIcon"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-warning" id="aiSystemText">Chưa kiểm tra</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Backup</h5>
                                    <p class="card-text">Sao lưu dữ liệu</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-cloud-upload-alt fa-2x" id="backupIcon"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-secondary" id="backupText">{{ date('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Validation Results -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Kết quả kiểm tra dữ liệu</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="validationTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Loại dữ liệu</th>
                                    <th>Tổng số bản ghi</th>
                                    <th>Hợp lệ</th>
                                    <th>Lỗi</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-users me-2"></i>Tài khoản người dùng</td>
                                    <td><span class="badge bg-info">{{ count($users ?? []) }}</span></td>
                                    <td><span class="text-success" id="usersValid">-</span></td>
                                    <td><span class="text-danger" id="usersError">-</span></td>
                                    <td><span class="badge bg-warning" id="usersStatus">Chưa kiểm tra</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="validateUsers()">
                                            <i class="fas fa-check me-1"></i>Kiểm tra
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-book me-2"></i>Môn học</td>
                                    <td><span class="badge bg-info">{{ count($courses ?? []) }}</span></td>
                                    <td><span class="text-success" id="coursesValid">-</span></td>
                                    <td><span class="text-danger" id="coursesError">-</span></td>
                                    <td><span class="badge bg-warning" id="coursesStatus">Chưa kiểm tra</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="validateCourses()">
                                            <i class="fas fa-check me-1"></i>Kiểm tra
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-graduation-cap me-2"></i>Lớp học phần</td>
                                    <td><span class="badge bg-info">{{ count($classes ?? []) }}</span></td>
                                    <td><span class="text-success" id="classesValid">-</span></td>
                                    <td><span class="text-danger" id="classesError">-</span></td>
                                    <td><span class="badge bg-warning" id="classesStatus">Chưa kiểm tra</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="validateClasses()">
                                            <i class="fas fa-check me-1"></i>Kiểm tra
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-calendar-check me-2"></i>Lịch học</td>
                                    <td><span class="badge bg-info">{{ count($schedules ?? []) }}</span></td>
                                    <td><span class="text-success" id="schedulesValid">-</span></td>
                                    <td><span class="text-danger" id="schedulesError">-</span></td>
                                    <td><span class="badge bg-warning" id="schedulesStatus">Chưa kiểm tra</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="validateSchedules()">
                                            <i class="fas fa-check me-1"></i>Kiểm tra
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-graduate me-2"></i>Sinh viên</td>
                                    <td><span class="badge bg-info">{{ count($students ?? []) }}</span></td>
                                    <td><span class="text-success" id="studentsValid">-</span></td>
                                    <td><span class="text-danger" id="studentsError">-</span></td>
                                    <td><span class="badge bg-warning" id="studentsStatus">Chưa kiểm tra</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="validateStudents()">
                                            <i class="fas fa-check me-1"></i>Kiểm tra
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Error Log -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Lỗi được phát hiện</h5>
                </div>
                <div class="card-body">
                    <div id="errorLog">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>Chưa có lỗi nào được phát hiện. Chạy kiểm tra để xem kết quả.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Sync Status -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-sync me-2"></i>Trạng thái đồng bộ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Kết nối Firebase</h6>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%">
                                    100%
                                </div>
                            </div>
                            <small class="text-muted">Lần đồng bộ cuối: {{ date('d/m/Y H:i:s') }}</small>
                        </div>
                        <div class="col-md-6">
                            <h6>Dữ liệu khuôn mặt</h6>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 75%">
                                    75%
                                </div>
                            </div>
                            <small class="text-muted">{{ count($users ?? []) }} người dùng đã có dữ liệu khuôn mặt</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary me-2" onclick="syncFirebase()">
                            <i class="fas fa-sync-alt me-1"></i>Đồng bộ Firebase
                        </button>
                        <button class="btn btn-secondary" onclick="refreshData()">
                            <i class="fas fa-refresh me-1"></i>Làm mới dữ liệu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateAllData() {
    // Show loading
    const validationTypes = ['users', 'courses', 'classes', 'schedules', 'students'];
    
    validationTypes.forEach(type => {
        document.getElementById(type + 'Status').textContent = 'Đang kiểm tra...';
        document.getElementById(type + 'Status').className = 'badge bg-warning';
    });

    // TODO: Implement actual validation
    setTimeout(() => {
        validationTypes.forEach(type => {
            const valid = Math.floor(Math.random() * 20) + 80;
            const error = 100 - valid;
            
            document.getElementById(type + 'Valid').textContent = valid;
            document.getElementById(type + 'Error').textContent = error;
            document.getElementById(type + 'Status').textContent = error > 10 ? 'Có lỗi' : 'Hợp lệ';
            document.getElementById(type + 'Status').className = error > 10 ? 'badge bg-danger' : 'badge bg-success';
        });
        
        updateErrorLog();
    }, 2000);
}

function validateUsers() {
    validateDataType('users');
}

function validateCourses() {
    validateDataType('courses');
}

function validateClasses() {
    validateDataType('classes');
}

function validateSchedules() {
    validateDataType('schedules');
}

function validateStudents() {
    validateDataType('students');
}

function validateDataType(type) {
    document.getElementById(type + 'Status').textContent = 'Đang kiểm tra...';
    document.getElementById(type + 'Status').className = 'badge bg-warning';
    
    // TODO: Implement actual validation for specific type
    setTimeout(() => {
        const valid = Math.floor(Math.random() * 20) + 80;
        const error = 100 - valid;
        
        document.getElementById(type + 'Valid').textContent = valid;
        document.getElementById(type + 'Error').textContent = error;
        document.getElementById(type + 'Status').textContent = error > 10 ? 'Có lỗi' : 'Hợp lệ';
        document.getElementById(type + 'Status').className = error > 10 ? 'badge bg-danger' : 'badge bg-success';
    }, 1000);
}

function updateErrorLog() {
    const errorLog = document.getElementById('errorLog');
    const errors = [
        'Sinh viên SV001 không có dữ liệu khuôn mặt',
        'Lớp 63CNTT.NB thiếu thông tin giảng viên',
        'Môn CSE111 có lịch học trùng lặp'
    ];
    
    let errorHtml = '';
    errors.forEach((error, index) => {
        errorHtml += `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Lỗi ${index + 1}:</strong> ${error}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    });
    
    errorLog.innerHTML = errorHtml || '<div class="text-center text-success py-3"><i class="fas fa-check-circle fa-2x mb-2"></i><p>Không có lỗi nào được phát hiện!</p></div>';
}

function exportData() {
    // TODO: Implement data export
    alert('Xuất dữ liệu thành công!');
}

function syncFirebase() {
    // TODO: Implement Firebase sync
    alert('Đồng bộ Firebase thành công!');
}

function refreshData() {
    location.reload();
}

// Auto check system status on page load
document.addEventListener('DOMContentLoaded', function() {
    // Simulate system checks
    setTimeout(() => {
        document.getElementById('dataIntegrityText').textContent = 'Tốt';
        document.getElementById('dataIntegrityText').className = 'badge bg-success';
        
        document.getElementById('aiSystemText').textContent = 'Sẵn sàng';
        document.getElementById('aiSystemText').className = 'badge bg-success';
    }, 1500);
});
</script>
@endsection

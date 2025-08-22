@extends('layouts.app')

@section('title', 'Xuất báo cáo')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="content-header p-4 bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Xuất báo cáo</h2>
                <div>
                    <button class="btn btn-success me-2" onclick="generateAllReports()">
                        <i class="fas fa-file-excel me-2"></i>Xuất tất cả
                    </button>
                    <button class="btn btn-primary" onclick="scheduleReport()">
                        <i class="fas fa-clock me-2"></i>Báo cáo định kỳ
                    </button>
                </div>
            </div>
        </div>

        <div class="content-body p-4">
            <!-- Report Filter -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ lọc báo cáo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Thời gian từ</label>
                            <input type="date" class="form-control" id="fromDate" value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Thời gian đến</label>
                            <input type="date" class="form-control" id="toDate" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Lớp học phần</label>
                            <select class="form-select" id="classFilter">
                                <option value="">Tất cả lớp</option>
                                <option value="63CNTT.NB">63CNTT.NB</option>
                                <option value="63CNTT.VA">63CNTT.VA</option>
                                <option value="63CNTT1">63CNTT1</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Môn học</label>
                            <select class="form-select" id="subjectFilter">
                                <option value="">Tất cả môn</option>
                                <option value="CSE111">CSE111 - Nhập môn lập trình</option>
                                <option value="MATH111">MATH111 - Giải tích</option>
                                <option value="CSE441">CSE441 - Mobile App</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Types -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Báo cáo điểm danh</h6>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Thống kê tỷ lệ điểm danh theo lớp, môn học và thời gian.</p>
                            <div class="mb-3">
                                <label class="form-label">Loại báo cáo</label>
                                <select class="form-select" id="attendanceReportType">
                                    <option value="summary">Tổng quan</option>
                                    <option value="detailed">Chi tiết</option>
                                    <option value="comparison">So sánh</option>
                                </select>
                            </div>
                            <button class="btn btn-success w-100" onclick="generateAttendanceReport()">
                                <i class="fas fa-download me-2"></i>Xuất báo cáo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-users me-2"></i>Báo cáo sinh viên</h6>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Danh sách sinh viên và thông tin điểm danh của từng cá nhân.</p>
                            <div class="mb-3">
                                <label class="form-label">Định dạng</label>
                                <select class="form-select" id="studentReportFormat">
                                    <option value="excel">Excel (.xlsx)</option>
                                    <option value="pdf">PDF</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                            <button class="btn btn-info w-100" onclick="generateStudentReport()">
                                <i class="fas fa-download me-2"></i>Xuất báo cáo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Báo cáo lịch học</h6>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Thống kê lịch học và tình hình tổ chức các buổi học.</p>
                            <div class="mb-3">
                                <label class="form-label">Kỳ học</label>
                                <select class="form-select" id="semesterFilter">
                                    <option value="2024-1">Học kỳ 1 (2024-2025)</option>
                                    <option value="2024-2">Học kỳ 2 (2024-2025)</option>
                                    <option value="2023-3">Học kỳ hè (2024)</option>
                                </select>
                            </div>
                            <button class="btn btn-warning w-100" onclick="generateScheduleReport()">
                                <i class="fas fa-download me-2"></i>Xuất báo cáo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Thống kê tổng quan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary">{{ count($classes ?? []) }}</h3>
                                <p class="mb-0">Lớp học phần</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success">{{ count($students ?? []) }}</h3>
                                <p class="mb-0">Sinh viên</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info">{{ count($courses ?? []) }}</h3>
                                <p class="mb-0">Môn học</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-warning">85%</h3>
                                <p class="mb-0">Tỷ lệ điểm danh</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="attendanceChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="classChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Báo cáo gần đây</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên báo cáo</th>
                                    <th>Loại</th>
                                    <th>Thời gian tạo</th>
                                    <th>Người tạo</th>
                                    <th>Kích thước</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Báo cáo điểm danh tháng 12</td>
                                    <td><span class="badge bg-success">Điểm danh</span></td>
                                    <td>{{ date('d/m/Y H:i') }}</td>
                                    <td>Admin</td>
                                    <td>2.5 MB</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" onclick="downloadReport('attendance_12_2024.xlsx')">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info me-1" onclick="viewReport('attendance_12_2024')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteReport('attendance_12_2024')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Danh sách sinh viên 63CNTT</td>
                                    <td><span class="badge bg-info">Sinh viên</span></td>
                                    <td>{{ date('d/m/Y H:i', strtotime('-1 day')) }}</td>
                                    <td>Admin</td>
                                    <td>1.8 MB</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" onclick="downloadReport('students_63cntt.xlsx')">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info me-1" onclick="viewReport('students_63cntt')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteReport('students_63cntt')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Lịch học học kỳ 1</td>
                                    <td><span class="badge bg-warning">Lịch học</span></td>
                                    <td>{{ date('d/m/Y H:i', strtotime('-3 days')) }}</td>
                                    <td>Admin</td>
                                    <td>3.2 MB</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" onclick="downloadReport('schedule_sem1.pdf')">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info me-1" onclick="viewReport('schedule_sem1')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteReport('schedule_sem1')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                label: 'Tỷ lệ điểm danh (%)',
                data: [78, 82, 85, 80, 88, 92, 87, 89, 91, 86, 88, 85],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Tỷ lệ điểm danh theo tháng'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Class Chart
    const classCtx = document.getElementById('classChart').getContext('2d');
    new Chart(classCtx, {
        type: 'doughnut',
        data: {
            labels: ['Có mặt', 'Vắng có phép', 'Vắng không phép'],
            datasets: [{
                data: [75, 15, 10],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 99, 132)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Phân bố trạng thái điểm danh'
                }
            }
        }
    });
});

function generateAllReports() {
    generateAttendanceReport();
    generateStudentReport();
    generateScheduleReport();
}

function generateAttendanceReport() {
    const reportType = document.getElementById('attendanceReportType').value;
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    const classFilter = document.getElementById('classFilter').value;
    
    // TODO: Implement attendance report generation
    alert(`Đang tạo báo cáo điểm danh (${reportType}) từ ${fromDate} đến ${toDate}${classFilter ? ' cho lớp ' + classFilter : ''}`);
}

function generateStudentReport() {
    const format = document.getElementById('studentReportFormat').value;
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    
    // TODO: Implement student report generation
    alert(`Đang tạo báo cáo sinh viên định dạng ${format} từ ${fromDate} đến ${toDate}`);
}

function generateScheduleReport() {
    const semester = document.getElementById('semesterFilter').value;
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    
    // TODO: Implement schedule report generation
    alert(`Đang tạo báo cáo lịch học ${semester} từ ${fromDate} đến ${toDate}`);
}

function scheduleReport() {
    // TODO: Implement scheduled reports
    alert('Tính năng báo cáo định kỳ sẽ được phát triển sau');
}

function downloadReport(filename) {
    // TODO: Implement file download
    alert('Đang tải xuống: ' + filename);
}

function viewReport(reportId) {
    // TODO: Implement report preview
    alert('Xem báo cáo: ' + reportId);
}

function deleteReport(reportId) {
    if (confirm('Bạn có chắc chắn muốn xóa báo cáo này?')) {
        // TODO: Implement report deletion
        alert('Đã xóa báo cáo: ' + reportId);
    }
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Xuất báo cáo - HTD')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Xuất báo cáo</h2>
        </div>
    </div>
    
    <!-- Report Types -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                    <h5>Báo cáo điểm danh</h5>
                    <p class="text-muted">Thống kê điểm danh theo lớp, môn học, thời gian</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#attendanceReportModal">
                        Tạo báo cáo
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h5>Báo cáo sinh viên</h5>
                    <p class="text-muted">Thống kê thông tin sinh viên, tỷ lệ tham gia</p>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#studentReportModal">
                        Tạo báo cáo
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-3x text-warning mb-3"></i>
                    <h5>Báo cáo môn học</h5>
                    <p class="text-muted">Thống kê theo môn học, lớp học phần</p>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#subjectReportModal">
                        Tạo báo cáo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Report Modal -->
<div class="modal fade" id="attendanceReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo báo cáo điểm danh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="attendanceReportForm" action="/reports/attendance" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên báo cáo</label>
                        <input type="text" name="report_name" class="form-control" placeholder="Nhập tên báo cáo" value="Báo cáo điểm danh">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="from_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="to_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lớp học phần</label>
                        <select name="class_id" class="form-control">
                            <option value="">Tất cả lớp học phần</option>
                            @if(isset($classes) && count($classes) > 0)
                                @foreach($classes as $class)
                                    <option value="{{ $class['id'] }}">{{ $class['code'] }} - {{ $class['subject'] }}</option>
                                @endforeach
                            @else
                                <option value="default1">Không có dữ liệu lớp học phần</option>
                            @endif
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="attendanceReportForm" class="btn btn-primary">Tạo báo cáo</button>
            </div>
        </div>
    </div>
</div>

<!-- Student Report Modal -->
<div class="modal fade" id="studentReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo báo cáo sinh viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="studentReportForm" action="/reports/student" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên báo cáo</label>
                        <input type="text" name="report_name" class="form-control" placeholder="Nhập tên báo cáo" value="Báo cáo sinh viên">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Khoa</label>
                        <select name="department" class="form-control">
                            <option value="">Tất cả khoa</option>
                            <option value="cntt">Công nghệ thông tin</option>
                            <option value="co_khi">Cơ khí</option>
                            <option value="thuy_loi">Thuỷ lợi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Năm học</label>
                        <select name="academic_year" class="form-control">
                            <option value="2024-2025">2024-2025</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2022-2023">2022-2023</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="studentReportForm" class="btn btn-success">Tạo báo cáo</button>
            </div>
        </div>
    </div>
</div>

<!-- Subject Report Modal -->
<div class="modal fade" id="subjectReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo báo cáo môn học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="subjectReportForm" action="/reports/subject" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên báo cáo</label>
                        <input type="text" name="report_name" class="form-control" placeholder="Nhập tên báo cáo" value="Báo cáo môn học">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Môn học</label>
                        <select name="subject_id" class="form-control">
                            <option value="">Tất cả môn học</option>
                            @if(isset($courses) && count($courses) > 0)
                                @foreach($courses as $course)
                                    <option value="{{ $course['id'] }}">{{ $course['code'] }} - {{ $course['name'] }}</option>
                                @endforeach
                            @else
                                <option value="default1">Không có dữ liệu môn học</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Học kỳ</label>
                        <select name="semester" class="form-control">
                            <option value="hk1_2024_2025">Học kỳ 1 - 2024-2025</option>
                            <option value="hk2_2023_2024">Học kỳ 2 - 2023-2024</option>
                            <option value="hk_he_2024">Học kỳ hè - 2024</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="subjectReportForm" class="btn btn-warning">Tạo báo cáo</button>
            </div>
        </div>
    </div>
</div>
@endsection

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
    
    <!-- Recent Reports -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Báo cáo gần đây</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Tên báo cáo</th>
                            <th>Loại</th>
                            <th>Người tạo</th>
                            <th>Thời gian tạo</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Báo cáo điểm danh tuần 1 - Tháng 8</td>
                            <td><span class="badge bg-primary">Điểm danh</span></td>
                            <td>Admin</td>
                            <td>22/08/2025 14:30</td>
                            <td><span class="badge bg-success">Hoàn thành</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Tải về</button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> Xem</button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Thống kê sinh viên theo khoa</td>
                            <td><span class="badge bg-success">Sinh viên</span></td>
                            <td>Trần Thị B</td>
                            <td>21/08/2025 16:15</td>
                            <td><span class="badge bg-success">Hoàn thành</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Tải về</button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> Xem</button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Báo cáo môn học học kỳ 1</td>
                            <td><span class="badge bg-warning">Môn học</span></td>
                            <td>Phạm Văn C</td>
                            <td>20/08/2025 09:45</td>
                            <td><span class="badge bg-warning">Đang xử lý</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-clock"></i> Chờ</button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                <form>
                    <div class="mb-3">
                        <label class="form-label">Tên báo cáo</label>
                        <input type="text" class="form-control" placeholder="Nhập tên báo cáo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lớp học phần</label>
                        <select class="form-control">
                            <option>Tất cả lớp học phần</option>
                            <option>MATH101-01</option>
                            <option>PHYS101-01</option>
                            <option>CS201-01</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary">Tạo báo cáo</button>
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
                <form>
                    <div class="mb-3">
                        <label class="form-label">Tên báo cáo</label>
                        <input type="text" class="form-control" placeholder="Nhập tên báo cáo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Khoa</label>
                        <select class="form-control">
                            <option>Tất cả khoa</option>
                            <option>Công nghệ thông tin</option>
                            <option>Cơ khí</option>
                            <option>Thuỷ lợi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Năm học</label>
                        <select class="form-control">
                            <option>2024-2025</option>
                            <option>2023-2024</option>
                            <option>2022-2023</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success">Tạo báo cáo</button>
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
                <form>
                    <div class="mb-3">
                        <label class="form-label">Tên báo cáo</label>
                        <input type="text" class="form-control" placeholder="Nhập tên báo cáo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Môn học</label>
                        <select class="form-control">
                            <option>Tất cả môn học</option>
                            <option>Toán cao cấp 1</option>
                            <option>Vật lý đại cương</option>
                            <option>Lập trình web</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Học kỳ</label>
                        <select class="form-control">
                            <option>Học kỳ 1 - 2024-2025</option>
                            <option>Học kỳ 2 - 2023-2024</option>
                            <option>Học kỳ hè - 2024</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-warning">Tạo báo cáo</button>
            </div>
        </div>
    </div>
</div>
@endsection

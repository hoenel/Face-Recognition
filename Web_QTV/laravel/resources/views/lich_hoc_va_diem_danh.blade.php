@extends('layouts.app')

@section('title', 'Lịch học và điểm danh - HTD')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lịch học và điểm danh</h2>
        <button class="btn btn-primary">
            <i class="fas fa-camera"></i> Bắt đầu điểm danh
        </button>
    </div>
    
    <!-- Date and Class Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Chọn ngày</label>
                    <input type="date" class="form-control" value="2025-08-23">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Chọn lớp học phần</label>
                    <select class="form-control">
                        <option>Tất cả lớp học phần</option>
                        <option>MATH101-01 - Toán cao cấp 1</option>
                        <option>PHYS101-01 - Vật lý đại cương</option>
                        <option>CS201-01 - Lập trình web</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái điểm danh</label>
                    <select class="form-control">
                        <option>Tất cả</option>
                        <option>Đã điểm danh</option>
                        <option>Chưa điểm danh</option>
                        <option>Đang diễn ra</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-outline-primary w-100 d-block">Lọc</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Schedule Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lịch học ngày 23/08/2025</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Thời gian</th>
                            <th>Mã lớp</th>
                            <th>Môn học</th>
                            <th>Giảng viên</th>
                            <th>Phòng học</th>
                            <th>Sĩ số</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>07:30 - 09:00</strong></td>
                            <td>MATH101-01</td>
                            <td>Toán cao cấp 1</td>
                            <td>TS. Nguyễn Văn A</td>
                            <td>A101</td>
                            <td>
                                <span class="badge bg-success">42/45</span>
                                <small class="text-muted d-block">Có mặt: 42, Vắng: 3</small>
                            </td>
                            <td><span class="badge bg-success">Đã điểm danh</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> Xem</button>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Sửa</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>09:15 - 10:45</strong></td>
                            <td>PHYS101-01</td>
                            <td>Vật lý đại cương</td>
                            <td>PGS. Trần Thị B</td>
                            <td>B203</td>
                            <td>
                                <span class="badge bg-info">0/38</span>
                                <small class="text-muted d-block">Chưa điểm danh</small>
                            </td>
                            <td><span class="badge bg-warning">Đang diễn ra</span></td>
                            <td>
                                <button class="btn btn-sm btn-success"><i class="fas fa-camera"></i> Điểm danh</button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-list"></i> DS lớp</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>13:30 - 15:00</strong></td>
                            <td>CS201-01</td>
                            <td>Lập trình web</td>
                            <td>ThS. Phạm Văn C</td>
                            <td>C105</td>
                            <td>
                                <span class="badge bg-secondary">0/25</span>
                                <small class="text-muted d-block">Chưa đến giờ</small>
                            </td>
                            <td><span class="badge bg-secondary">Chưa bắt đầu</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-clock"></i> Chờ</button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-list"></i> DS lớp</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>15:15 - 16:45</strong></td>
                            <td>HYDRO101-01</td>
                            <td>Cơ sở thuỷ lực</td>
                            <td>Prof. Lê Thị D</td>
                            <td>D301</td>
                            <td>
                                <span class="badge bg-secondary">0/20</span>
                                <small class="text-muted d-block">Chưa đến giờ</small>
                            </td>
                            <td><span class="badge bg-secondary">Chưa bắt đầu</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-clock"></i> Chờ</button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-list"></i> DS lớp</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Attendance Summary -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h4>1</h4>
                    <p class="mb-0">Lớp đã điểm danh</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h4>1</h4>
                    <p class="mb-0">Lớp đang diễn ra</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body text-center">
                    <h4>2</h4>
                    <p class="mb-0">Lớp chưa bắt đầu</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h4>93.3%</h4>
                    <p class="mb-0">Tỷ lệ có mặt</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

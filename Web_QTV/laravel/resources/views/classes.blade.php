@extends('layouts.app')

@section('title', 'Quản lí lớp học phần - HTD')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lí lớp học phần</h2>
        <button class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm lớp học phần
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Tìm kiếm lớp học...">
                </div>
                <div class="col-md-2">
                    <select class="form-control">
                        <option>Tất cả môn học</option>
                        <option>Toán cao cấp 1</option>
                        <option>Vật lý đại cương</option>
                        <option>Lập trình web</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control">
                        <option>Tất cả giảng viên</option>
                        <option>Nguyễn Văn A</option>
                        <option>Trần Thị B</option>
                        <option>Phạm Văn C</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control">
                        <option>Tất cả trạng thái</option>
                        <option>Đang diễn ra</option>
                        <option>Chưa bắt đầu</option>
                        <option>Đã kết thúc</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-outline-primary w-100">Tìm</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Classes Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Mã lớp</th>
                            <th>Môn học</th>
                            <th>Giảng viên</th>
                            <th>Phòng học</th>
                            <th>Thời gian</th>
                            <th>Số sinh viên</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>MATH101-01</strong></td>
                            <td>Toán cao cấp 1</td>
                            <td>TS. Nguyễn Văn A</td>
                            <td>A101</td>
                            <td>T2,T4,T6 (07:30-09:00)</td>
                            <td><span class="badge bg-info">45/50</span></td>
                            <td><span class="badge bg-success">Đang diễn ra</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-users"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="fas fa-calendar"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>PHYS101-01</strong></td>
                            <td>Vật lý đại cương</td>
                            <td>PGS. Trần Thị B</td>
                            <td>B203</td>
                            <td>T3,T5 (09:15-10:45)</td>
                            <td><span class="badge bg-info">38/40</span></td>
                            <td><span class="badge bg-success">Đang diễn ra</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-users"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="fas fa-calendar"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>CS201-01</strong></td>
                            <td>Lập trình web</td>
                            <td>ThS. Phạm Văn C</td>
                            <td>C105</td>
                            <td>T2,T4 (13:30-15:00)</td>
                            <td><span class="badge bg-info">25/30</span></td>
                            <td><span class="badge bg-warning">Chưa bắt đầu</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-users"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="fas fa-calendar"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>HYDRO101-01</strong></td>
                            <td>Cơ sở thuỷ lực</td>
                            <td>Prof. Lê Thị D</td>
                            <td>D301</td>
                            <td>T3,T6 (15:15-16:45)</td>
                            <td><span class="badge bg-info">20/25</span></td>
                            <td><span class="badge bg-success">Đang diễn ra</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-users"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="fas fa-calendar"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>MECH101-01</strong></td>
                            <td>Cơ học kỹ thuật</td>
                            <td>TS. Hoàng Văn E</td>
                            <td>E201</td>
                            <td>T5,T7 (07:30-09:00)</td>
                            <td><span class="badge bg-info">35/40</span></td>
                            <td><span class="badge bg-secondary">Đã kết thúc</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-users"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="fas fa-calendar"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <span class="page-link">Trước</span>
                    </li>
                    <li class="page-item active">
                        <span class="page-link">1</span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

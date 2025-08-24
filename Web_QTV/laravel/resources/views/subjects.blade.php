@extends('layouts.app')

@section('title', 'Quản lí môn học - HTD')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lí môn học</h2>
        <button class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm môn học
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Tìm kiếm môn học...">
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option>Tất cả khoa</option>
                        <option>Khoa Công nghệ thông tin</option>
                        <option>Khoa Cơ khí</option>
                        <option>Khoa Thuỷ lợi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option>Tất cả học kỳ</option>
                        <option>Học kỳ 1</option>
                        <option>Học kỳ 2</option>
                        <option>Học kỳ hè</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">Tìm kiếm</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Subjects Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Mã môn</th>
                            <th>Tên môn học</th>
                            <th>Số tín chỉ</th>
                            <th>Khoa</th>
                            <th>Học kỳ</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>MATH101</strong></td>
                            <td>Toán cao cấp 1</td>
                            <td>3</td>
                            <td>Khoa Cơ bản</td>
                            <td>Học kỳ 1</td>
                            <td><span class="badge bg-success">Đang diễn ra</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>PHYS101</strong></td>
                            <td>Vật lý đại cương</td>
                            <td>4</td>
                            <td>Khoa Cơ bản</td>
                            <td>Học kỳ 1</td>
                            <td><span class="badge bg-success">Đang diễn ra</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>CS201</strong></td>
                            <td>Lập trình web</td>
                            <td>3</td>
                            <td>Khoa Công nghệ thông tin</td>
                            <td>Học kỳ 2</td>
                            <td><span class="badge bg-warning">Chưa bắt đầu</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>HYDRO101</strong></td>
                            <td>Cơ sở thuỷ lực</td>
                            <td>3</td>
                            <td>Khoa Thuỷ lợi</td>
                            <td>Học kỳ 1</td>
                            <td><span class="badge bg-success">Đang diễn ra</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>MECH101</strong></td>
                            <td>Cơ học kỹ thuật</td>
                            <td>4</td>
                            <td>Khoa Cơ khí</td>
                            <td>Học kỳ 1</td>
                            <td><span class="badge bg-secondary">Đã kết thúc</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>
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

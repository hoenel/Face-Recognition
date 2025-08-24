@extends('layouts.app')

@section('title', 'Quản lý Môn học - HTD')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý Môn học</h2>
        <button class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm môn học
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Tìm kiếm theo tên môn...">
                </div>
                <div class="col-md-2">
                    <select class="form-control">
                        <option>Tất cả ngành</option>
                        <option>Khoa Cơ bản</option>
                        <option>Khoa Công nghệ thông tin</option>
                        <option>Khoa Thuỷ lợi</option>
                        <option>Khoa Cơ khí</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control">
                        <option>Tất cả tín chỉ</option>
                        <option>1 tín chỉ</option>
                        <option>2 tín chỉ</option>
                        <option>3 tín chỉ</option>
                        <option>4 tín chỉ</option>
                        <option>5+ tín chỉ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control">
                        <option>Tất cả trạng thái</option>
                        <option>Đang mở</option>
                        <option>Tạm dừng</option>
                        <option>Ngừng giảng dạy</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="Mã môn học...">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-outline-primary w-100">Tìm</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #4a90e2; color: white;">
                        <tr>
                            <th>Mã môn</th>
                            <th>Tên môn học</th>
                            <th>Số tín chỉ</th>
                            <th>Ngành học</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                        <tr>
                            <td><strong>{{ $subject['code'] }}</strong></td>
                            <td>{{ $subject['name'] }}</td>
                            <td><span class="badge bg-info">{{ $subject['credits'] }}</span></td>
                            <td>{{ $subject['department'] }}</td>
                            <td>
                                <button class="btn btn-sm btn-success me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(count($subjects) == 0)
            <div class="text-center py-5">
                <i class="fas fa-book text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Chưa có môn học nào - Trường Đại học Thuỷ lợi</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

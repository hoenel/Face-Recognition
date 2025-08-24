@extends('layouts.app')

@section('title', 'Quản lý lớp học phần - HTD')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý lớp học phần</h2>
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
                        @foreach($classes as $index => $class)
                        <tr>
                            <td><strong>{{ $class['code'] }}</strong></td>
                            <td>{{ $class['subject'] }}</td>
                            <td>{{ $class['instructor'] }}</td>
                            <td>{{ $class['room'] }}</td>
                            <td>{{ $class['schedule'] }}</td>
                            <td><span class="badge bg-info">{{ $class['students'] }}</span></td>
                            <td>
                                @if($class['status'] == 'active')
                                    <span class="badge bg-success">Đang diễn ra</span>
                                @elseif($class['status'] == 'pending')
                                    <span class="badge bg-warning">Chưa bắt đầu</span>
                                @else
                                    <span class="badge bg-secondary">Đã kết thúc</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-users"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="fas fa-calendar"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(count($classes) == 0)
            <div class="text-center py-5">
                <i class="fas fa-chalkboard-teacher text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Chưa có lớp học phần nào - Trường Đại học Thuỷ lợi</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

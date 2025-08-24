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
            <form method="GET" action="{{ route('subjects.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên môn..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="department" class="form-control">
                            <option value="">Tất cả ngành</option>
                            @php
                                $departments = array_unique(array_column($subjects, 'department'));
                            @endphp
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="credits" class="form-control">
                            <option value="">Tất cả tín chỉ</option>
                            <option value="1" {{ request('credits') == '1' ? 'selected' : '' }}>1 tín chỉ</option>
                            <option value="2" {{ request('credits') == '2' ? 'selected' : '' }}>2 tín chỉ</option>
                            <option value="3" {{ request('credits') == '3' ? 'selected' : '' }}>3 tín chỉ</option>
                            <option value="4" {{ request('credits') == '4' ? 'selected' : '' }}>4 tín chỉ</option>
                            <option value="5" {{ request('credits') == '5' ? 'selected' : '' }}>5+ tín chỉ</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang mở</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Tạm dừng</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Ngừng giảng dạy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="code" class="form-control" placeholder="Mã môn học..." value="{{ request('code') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-outline-primary w-100">Tìm</button>
                    </div>
                </div>
            </form>
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
                            <th>Giảng viên</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                        <tr>
                            <td><strong>{{ $subject['code'] }}</strong></td>
                            <td>{{ $subject['name'] }}</td>
                            <td><span class="badge bg-info">{{ $subject['credits'] }}</span></td>
                            <td>{{ $subject['teacher'] ?? 'Chưa phân công' }}</td>
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

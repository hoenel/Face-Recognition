@extends('layouts.app')

@section('title', 'Quản lý Môn học - HTD')

@section('content')
<div class="container-fluid">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý Môn học</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                        <tr>
                            <td><strong>{{ $subject['code'] }}</strong></td>
                            <td>{{ $subject['name'] }}</td>
                            <td><span class="badge bg-info">{{ $subject['credits'] }}</span></td>
                            <td>{{ $subject['teacher'] ?? 'Chưa phân công' }}</td>
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

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm môn học mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSubjectForm" action="/subjects/create" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Mã môn học <span class="text-danger">*</span></label>
                        <input type="text" name="course_code" class="form-control" placeholder="Ví dụ: CS101, MATH201" required>
                        <small class="text-muted">Nhập mã môn học (không có dấu cách)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên môn học <span class="text-danger">*</span></label>
                        <input type="text" name="course_name" class="form-control" placeholder="Ví dụ: Lập trình Java cơ bản" required>
                        <small class="text-muted">Nhập tên đầy đủ của môn học</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số tín chỉ <span class="text-danger">*</span></label>
                        <select name="credit" class="form-control" required>
                            <option value="">Chọn số tín chỉ</option>
                            <option value="1">1 tín chỉ</option>
                            <option value="2">2 tín chỉ</option>
                            <option value="3">3 tín chỉ</option>
                            <option value="4">4 tín chỉ</option>
                            <option value="5">5 tín chỉ</option>
                            <option value="6">6 tín chỉ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên giảng viên</label>
                        <input type="text" name="teacher_name" class="form-control" placeholder="Ví dụ: TS. Nguyễn Văn A">
                        <small class="text-muted">Có thể để trống và phân công sau</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="addSubjectForm" class="btn btn-primary">Thêm môn học</button>
            </div>
        </div>
    </div>
</div>
@endsection

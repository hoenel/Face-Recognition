@extends('layouts.app')

@section('title', 'Quản lý lớp học phần - HTD')

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
        <h2>Quản lý lớp học phần</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
            <i class="fas fa-plus"></i> Thêm lớp học phần
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('classes.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm lớp học..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="subject" class="form-control">
                            <option value="">Tất cả môn học</option>
                            @php
                                $subjects = array_unique(array_column($classes, 'subject'));
                            @endphp
                            @foreach($subjects as $subject)
                                <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="instructor" class="form-control">
                            <option value="">Tất cả giảng viên</option>
                            @php
                                $instructors = array_unique(array_column($classes, 'instructor'));
                            @endphp
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor }}" {{ request('instructor') == $instructor ? 'selected' : '' }}>{{ $instructor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Tìm</button>
                    </div>
                </div>
            </form>
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
                            <th>Số sinh viên</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $index => $class)
                        <tr>
                            <td><strong>{{ $class['code'] }}</strong></td>
                            <td>{{ $class['subject'] }}</td>
                            <td>{{ $class['instructor'] }}</td>
                            <td><span class="badge bg-info">{{ $class['students'] }}</span></td>
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

<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm lớp học phần mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addClassForm" action="/classes/create" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên lớp học phần <span class="text-danger">*</span></label>
                        <input type="text" name="class_name" class="form-control" placeholder="Ví dụ: K63 Công nghệ thông tin Việt-Nhật" required>
                        <small class="text-muted">Nhập tên đầy đủ của lớp học phần</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mã giảng viên <span class="text-danger">*</span></label>
                        <input type="text" name="teacher_id" class="form-control" placeholder="Ví dụ: TXT-123" required>
                        <small class="text-muted">Nhập mã giảng viên phụ trách</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Danh sách mã sinh viên</label>
                        <textarea name="student_ids" class="form-control" rows="4" placeholder="Nhập danh sách mã sinh viên, mỗi mã một dòng hoặc cách nhau bằng dấu phẩy&#10;Ví dụ:&#10;2151062753&#10;2151062894&#10;hoặc: 2151062753, 2151062894"></textarea>
                        <small class="text-muted">Có thể để trống và thêm sinh viên sau</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="addClassForm" class="btn btn-primary">Thêm lớp học phần</button>
            </div>
        </div>
    </div>
</div>
@endsection

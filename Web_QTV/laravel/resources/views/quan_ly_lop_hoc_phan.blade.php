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
            <form method="GET" action="{{ route('classes.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm lớp học..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
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
                        <select name="status" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang diễn ra</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa bắt đầu</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="room" class="form-control" placeholder="Phòng học..." value="{{ request('room') }}">
                    </div>
                    <div class="col-md-1">
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

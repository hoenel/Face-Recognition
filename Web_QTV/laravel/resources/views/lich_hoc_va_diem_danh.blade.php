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
            <form method="GET" action="{{ route('schedules.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Chọn ngày</label>
                        <input type="date" name="filter_date" class="form-control" value="{{ request('filter_date', '2025-08-01') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Chọn môn học</label>
                        <select name="filter_course" class="form-control">
                            <option value="">Tất cả môn học</option>
                            @php
                                $courseNames = array_unique(array_filter(array_map(function($s) { 
                                    return $s['course_name'] ?? ''; 
                                }, $schedules)));
                            @endphp
                            @foreach($courseNames as $courseName)
                                @if(!empty($courseName))
                                    <option value="{{ $courseName }}" {{ request('filter_course') == $courseName ? 'selected' : '' }}>{{ $courseName }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Phòng học</label>
                        <select name="filter_classroom" class="form-control">
                            <option value="">Tất cả phòng</option>
                            @php
                                $classrooms = array_unique(array_filter(array_map(function($s) { 
                                    return $s['classroom'] ?? ''; 
                                }, $schedules)));
                            @endphp
                            @foreach($classrooms as $classroom)
                                @if(!empty($classroom))
                                    <option value="{{ $classroom }}" {{ request('filter_classroom') == $classroom ? 'selected' : '' }}>{{ $classroom }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-outline-primary w-100 d-block">Lọc</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Schedule Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lịch học ngày {{ request('filter_date') ? date('d/m/Y', strtotime(request('filter_date'))) : '01/08/2025' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Thời gian</th>
                            <th>Mã môn học</th>
                            <th>Tên môn học</th>
                            <th>Lớp</th>
                            <th>Phòng học</th>
                            <th>Ngày</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td><strong>{{ $schedule['time'] ?? $schedule['start_time'] ?? 'N/A' }}</strong></td>
                            <td>{{ $schedule['course_code'] ?? 'N/A' }}</td>
                            <td>{{ $schedule['course_name'] ?? 'N/A' }}</td>
                            <td>{{ $schedule['class_id'] ?? 'N/A' }}</td>
                            <td>{{ $schedule['classroom'] ?? 'N/A' }}</td>
                            <td>{{ isset($schedule['date']) ? date('d/m/Y', strtotime($schedule['date'])) : 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> Xem</button>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Sửa</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(count($schedules) == 0)
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Chưa có lịch học nào - Trường Đại học Thuỷ lợi</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Schedule Summary -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h4>{{ count($schedules) }}</h4>
                    <p class="mb-0">Tổng số lịch học</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h4>{{ count(array_filter($schedules, function($s) { return !empty($s['classroom'] ?? ''); })) }}</h4>
                    <p class="mb-0">Đã phân phòng</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    @php
                        $uniqueCourses = array_unique(array_filter(array_map(function($s) { 
                            return $s['course_code'] ?? ''; 
                        }, $schedules)));
                    @endphp
                    <h4>{{ count($uniqueCourses) }}</h4>
                    <p class="mb-0">Số môn học</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

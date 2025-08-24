@extends('layouts.app')

@section('title', 'Kiểm tra tính toàn vẹn dữ liệu - HTD')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Kiểm tra tính toàn vẹn dữ liệu</h2>
        </div>
    </div>
    
    <!-- Connection Status -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <i class="fas fa-database fa-2x mb-2"></i>
                    <h5>Firebase</h5>
                    <p class="mb-0">Kết nối tốt</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <i class="fas fa-server fa-2x mb-2"></i>
                    <h5>Server</h5>
                    <p class="mb-0">Online</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Danh sách lớp học phần -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Danh sách lớp học phần</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã lớp học phần</th>
                            <th>Tên lớp</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classData as $class)
                        <tr>
                            <td>{{ $class['code'] }}</td>
                            <td>{{ $class['name'] }}</td>
                            <td><span class="badge {{ $class['badge_class'] }}">{{ $class['status'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center py-3">
                <span class="text-success">
                    <i class="fas fa-check-circle"></i>
                    Dữ liệu từ Firebase
                </span>
            </div>
        </div>
    </div>
    
    <!-- Lịch học -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Lịch học</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày</th>
                            <th>Mã lớp</th>
                            <th>Môn học</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scheduleData as $schedule)
                        <tr>
                            <td>{{ $schedule['date'] }}</td>
                            <td>{{ $schedule['class_code'] }}</td>
                            <td>{{ $schedule['subject'] }}</td>
                            <td><span class="badge {{ $schedule['badge_class'] }}">{{ $schedule['status'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center py-3">
                <span class="text-muted">
                    <i class="fas fa-calendar-alt"></i>
                    Hệ thống điểm danh - Trường Đại học Thuỷ lợi
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

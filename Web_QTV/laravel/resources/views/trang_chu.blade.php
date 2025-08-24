@extends('layouts.app')

@section('title', 'Trang chủ - HTD')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Trang chủ</h2>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Tài khoản</h5>
                            <h3>{{ $stats['total_accounts'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Môn học</h5>
                            <h3>{{ $stats['total_subjects'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Lớp học phần</h5>
                            <h3>{{ $stats['total_classes'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Today's Schedule -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Lịch học hôm nay - {{ date('d/m/Y') }}</h5>
                </div>
                <div class="card-body">
                    @if(count($schedules) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Mã môn học</th>
                                        <th>Tên môn học</th>
                                        <th>Lớp</th>
                                        <th>Phòng học</th>
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
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Không có lịch học hôm nay - Trường Đại học Thuỷ lợi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

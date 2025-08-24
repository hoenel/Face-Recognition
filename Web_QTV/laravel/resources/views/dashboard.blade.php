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
        <div class="col-md-3 mb-3">
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
        
        <div class="col-md-3 mb-3">
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
        
        <div class="col-md-3 mb-3">
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
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Điểm danh hôm nay</h5>
                            <h3>{{ $stats['today_attendance'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Hoạt động gần đây</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Hoạt động</th>
                                    <th>Người thực hiện</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_activities as $activity)
                                <tr>
                                    <td>{{ $activity['time'] }}</td>
                                    <td>{{ $activity['action'] }}</td>
                                    <td>{{ $activity['user'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Lịch học hôm nay</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <strong>07:30 - 09:00</strong>
                            <div class="text-muted">Toán cao cấp 1 - Phòng A101</div>
                        </div>
                        <div class="timeline-item mt-3">
                            <strong>09:15 - 10:45</strong>
                            <div class="text-muted">Vật lý đại cương - Phòng B203</div>
                        </div>
                        <div class="timeline-item mt-3">
                            <strong>13:30 - 15:00</strong>
                            <div class="text-muted">Lập trình web - Phòng C105</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

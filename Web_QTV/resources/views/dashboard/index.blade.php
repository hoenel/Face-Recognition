@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="content-header p-4 bg-light border-bottom">
            <h2 class="mb-0 fw-bold">Tổng quan hệ thống</h2>
        </div>

        <div class="content-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Số lượng sinh viên</h6>
                            <h4 class="fw-bold text-primary">{{ $stats['students'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Số lượng giảng viên</h6>
                            <h4 class="fw-bold text-primary">{{ $stats['teachers'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Tổng lớp học phần</h6>
                            <h4 class="fw-bold text-primary">{{ $stats['classes'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Tỷ lệ điểm danh</h6>
                            <h4 class="fw-bold text-primary">{{ $stats['attendance_rate'] ?? 0 }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-center text-muted small py-3">
            Hệ thống điểm danh - Trường Đại học Thủy lợi
        </footer>
    </div>
</div>
@endsection

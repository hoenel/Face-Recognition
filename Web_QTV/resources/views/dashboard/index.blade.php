@extends('layouts.app')

@section('title', 'Tổng quan hệ thống')
@section('page-title', 'Tổng quan hệ thống')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Số lượng sinh viên</h6>
                <h3 class="text-primary fw-bold">{{ $stats['students'] ?? 1280 }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Số lượng giảng viên</h6>
                <h3 class="text-primary fw-bold">{{ $stats['teachers'] ?? 82 }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Tổng lớp học phần</h6>
                <h3 class="text-primary fw-bold">{{ $stats['classes'] ?? 148 }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Lượt điểm danh hôm nay</h6>
                <h3 class="text-primary fw-bold">{{ $stats['attendance_today'] ?? 1024 }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection

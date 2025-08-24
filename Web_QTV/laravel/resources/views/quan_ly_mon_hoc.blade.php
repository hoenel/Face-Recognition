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
                            <th>Ngành học</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                        <tr>
                            <td><strong>{{ $subject['code'] }}</strong></td>
                            <td>{{ $subject['name'] }}</td>
                            <td><span class="badge bg-info">{{ $subject['credits'] }}</span></td>
                            <td>{{ $subject['department'] }}</td>
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

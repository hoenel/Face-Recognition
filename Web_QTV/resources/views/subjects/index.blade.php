@extends('layouts.app')

@section('title', 'Quản lý Môn học')
@section('page-title', 'Quản lý Môn học')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: #3498db; color: white;">
                <h6 class="mb-0">Danh sách môn học</h6>
                <button class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Thêm môn học
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã môn</th>
                                <th>Tên môn học</th>
                                <th>Số tín chỉ</th>
                                <th>Ngành học</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                            <tr>
                                <td>{{ $subject['code'] }}</td>
                                <td>{{ $subject['name'] }}</td>
                                <td>{{ $subject['credits'] }}</td>
                                <td>{{ $subject['department'] }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm">Sửa</button>
                                    <button class="btn btn-danger btn-sm">Xóa</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Quản lý Lớp học phần')
@section('page-title', 'Quản lý Lớp học phần')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: #3498db; color: white;">
                <h6 class="mb-0">Danh sách lớp học phần</h6>
                <button class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Thêm lớp học phần
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã lớp</th>
                                <th>Tên lớp</th>
                                <th>Môn học</th>
                                <th>Giảng viên</th>
                                <th>Sĩ số</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classrooms as $classroom)
                            <tr>
                                <td>{{ $classroom['code'] }}</td>
                                <td>{{ $classroom['name'] }}</td>
                                <td>{{ $classroom['subject'] }}</td>
                                <td>{{ $classroom['teacher'] }}</td>
                                <td>{{ $classroom['students'] }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm">Sửa</button>
                                    <button class="btn btn-danger btn-sm">Xóa</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
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

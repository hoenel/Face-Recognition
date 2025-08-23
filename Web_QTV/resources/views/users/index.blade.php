@extends('layouts.app')

@section('title', 'Quản lý tài khoản')
@section('page-title', 'Quản lý tài khoản')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: #3498db; color: white;">
                <h6 class="mb-0">Danh sách tài khoản</h6>
                <button class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Thêm tài khoản
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['role'] }}</td>
                                <td>
                                    @if($user['status'] == 'Hoạt động')
                                        <span class="badge bg-success">{{ $user['status'] }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $user['status'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm">Sửa</button>
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

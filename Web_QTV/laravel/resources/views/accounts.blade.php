@extends('layouts.app')

@section('title', 'Quản lí tài khoản - HTD')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lí tài khoản</h2>
        <button class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm tài khoản
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Tìm kiếm theo tên, email...">
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option>Tất cả vai trò</option>
                        <option>Quản trị viên</option>
                        <option>Giảng viên</option>
                        <option>Sinh viên</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option>Tất cả trạng thái</option>
                        <option>Hoạt động</option>
                        <option>Không hoạt động</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">Tìm kiếm</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Accounts Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)
                        <tr>
                            <td>{{ $account['id'] }}</td>
                            <td>{{ $account['name'] }}</td>
                            <td>{{ $account['email'] }}</td>
                            <td>
                                @if($account['role'] == 'admin')
                                    <span class="badge bg-danger">Quản trị viên</span>
                                @elseif($account['role'] == 'teacher')
                                    <span class="badge bg-warning">Giảng viên</span>
                                @else
                                    <span class="badge bg-info">Sinh viên</span>
                                @endif
                            </td>
                            <td>
                                @if($account['status'] == 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td>{{ $account['created_at'] }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <span class="page-link">Trước</span>
                    </li>
                    <li class="page-item active">
                        <span class="page-link">1</span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

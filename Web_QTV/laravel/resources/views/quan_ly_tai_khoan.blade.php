@extends('layouts.app')

@section('title', 'Quản lí tài khoản - HTD')

@section('content')
<div class="container-fluid">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lí tài khoản</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            <i class="fas fa-plus"></i> Thêm tài khoản
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('accounts.index') }}">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="role" class="form-control">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Giảng viên</option>
                            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Sinh viên</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary w-100">Tìm kiếm</button>
                    </div>
                </div>
            </form>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(count($accounts) == 0)
            <div class="text-center py-5">
                <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Chưa có tài khoản nào - Trường Đại học Thuỷ lợi</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm tài khoản mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAccountForm" action="/accounts/create" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="Ví dụ: nguyen.van.a@htd.edu.vn" required>
                        <small class="text-muted">Email sẽ được dùng để đăng nhập</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="role" class="form-control" required>
                            <option value="">Chọn vai trò</option>
                            <option value="admin">Quản trị viên</option>
                            <option value="teacher">Giảng viên</option>
                            <option value="student">Sinh viên</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu tạm thời</label>
                        <input type="password" name="password" class="form-control" placeholder="Để trống sẽ tạo mật khẩu mặc định: 123456">
                        <small class="text-muted">Người dùng nên đổi mật khẩu sau lần đăng nhập đầu tiên</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="addAccountForm" class="btn btn-primary">Thêm tài khoản</button>
            </div>
        </div>
    </div>
</div>
@endsection

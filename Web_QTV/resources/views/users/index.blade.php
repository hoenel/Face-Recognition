@extends('layouts.app')

@section('title', 'Quản lý tài khoản')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="content-header p-4 bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Quản lý tài khoản</h2>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus me-2"></i>Thêm tài khoản
                </button>
            </div>
        </div>

        <div class="content-body p-4">
            <!-- Users Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
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
                                    <td>
                                        @if(isset($user['role']))
                                            @if($user['role'] == 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @elseif($user['role'] == 'teacher')
                                                <span class="badge bg-warning">Giáo viên</span>
                                            @else
                                                <span class="badge bg-info">Sinh viên</span>
                                            @endif
                                        @else
                                            <span class="badge bg-info">Sinh viên</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Hoạt động</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning me-1" onclick="editUser('{{ $user['id'] }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteUser('{{ $user['id'] }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm tài khoản mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vai trò</label>
                        <select class="form-select" name="role">
                            <option value="student">Sinh viên</option>
                            <option value="teacher">Giáo viên</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3" id="studentFields" style="display: none;">
                        <label class="form-label">Mã sinh viên</label>
                        <input type="text" class="form-control" name="student_id">
                        <label class="form-label mt-2">Lớp</label>
                        <select class="form-select" name="class_id">
                            <option value="">Chọn lớp</option>
                            <option value="63CNTT.NB">63CNTT.NB</option>
                            <option value="63CNTT.VA">63CNTT.VA</option>
                            <option value="63CNTT1">63CNTT1</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(id) {
    // TODO: Implement edit functionality
    alert('Chỉnh sửa user ID: ' + id);
}

function deleteUser(id) {
    if(confirm('Bạn có chắc chắn muốn xóa tài khoản này?')) {
        // TODO: Implement delete functionality
        alert('Xóa user ID: ' + id);
    }
}

// Show/hide student fields based on role selection
document.querySelector('select[name="role"]').addEventListener('change', function() {
    const studentFields = document.getElementById('studentFields');
    if(this.value === 'student') {
        studentFields.style.display = 'block';
    } else {
        studentFields.style.display = 'none';
    }
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Quản lý lớp học')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="content-header p-4 bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Quản lý lớp học</h2>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClassModal">
                    <i class="fas fa-plus me-2"></i>Thêm lớp học phần
                </button>
            </div>
        </div>

        <div class="content-body p-4">
            <!-- Classes Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã lớp</th>
                                    <th>Tên lớp</th>
                                    <th>Số sinh viên</th>
                                    <th>Giảng viên</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classrooms as $index => $class)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-primary">{{ $class['id'] }}</span></td>
                                    <td>{{ $class['class_name'] }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ is_array($class['student_ids']) ? count($class['student_ids']) : 0 }} sinh viên
                                        </span>
                                    </td>
                                    <td>{{ $class['teacher_id'] }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info me-1" onclick="viewStudents('{{ $class['id'] }}')">
                                            <i class="fas fa-users"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning me-1" onclick="editClass('{{ $class['id'] }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteClass('{{ $class['id'] }}')">
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

<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm lớp học phần mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('classrooms.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã lớp</label>
                        <input type="text" class="form-control" name="class_id" placeholder="VD: 63CNTT.NB" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên lớp</label>
                        <input type="text" class="form-control" name="class_name" placeholder="VD: K63 Công nghệ thông tin Việt-Nhật" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mã giảng viên</label>
                        <input type="text" class="form-control" name="teacher_id" placeholder="VD: TXT-123" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Danh sách sinh viên (mỗi dòng một mã sinh viên)</label>
                        <textarea class="form-control" name="student_ids" rows="5" placeholder="2151062753&#10;2151062894"></textarea>
                        <small class="text-muted">Nhập mã sinh viên, mỗi dòng một mã</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm lớp học phần</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewStudents(classId) {
    // TODO: Implement view students functionality
    alert('Xem danh sách sinh viên lớp: ' + classId);
}

function editClass(id) {
    // TODO: Implement edit functionality
    alert('Chỉnh sửa lớp ID: ' + id);
}

function deleteClass(id) {
    if(confirm('Bạn có chắc chắn muốn xóa lớp học phần này?')) {
        // TODO: Implement delete functionality
        alert('Xóa lớp ID: ' + id);
    }
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Quản lý học phần')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="content-header p-4 bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Quản lý học phần</h2>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="fas fa-plus me-2"></i>Thêm học phần
                </button>
            </div>
        </div>

        <div class="content-body p-4">
            <!-- Subjects Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã học phần</th>
                                    <th>Tên học phần</th>
                                    <th>Giảng viên</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $index => $subject)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-info">{{ $subject['course_code'] }}</span></td>
                                    <td>{{ $subject['course_name'] }}</td>
                                    <td>{{ $subject['teacher_name'] }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning me-1" onclick="editSubject('{{ $subject['id'] }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteSubject('{{ $subject['id'] }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Không có dữ liệu</td>
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

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm học phần mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã học phần</label>
                        <input type="text" class="form-control" name="course_code" placeholder="VD: CSE111" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên học phần</label>
                        <input type="text" class="form-control" name="course_name" placeholder="VD: Nhập môn lập trình" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giảng viên phụ trách</label>
                        <input type="text" class="form-control" name="teacher_name" placeholder="VD: Trương Xuân Nam" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm học phần</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editSubject(id) {
    // TODO: Implement edit functionality
    alert('Chỉnh sửa học phần ID: ' + id);
}

function deleteSubject(id) {
    if(confirm('Bạn có chắc chắn muốn xóa học phần này?')) {
        // TODO: Implement delete functionality
        alert('Xóa học phần ID: ' + id);
    }
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Lịch học & Điểm danh')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="content-header p-4 bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Lịch học & Điểm danh</h2>
                <div>
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                        <i class="fas fa-calendar-plus me-2"></i>Thêm lịch học
                    </button>
                    <button class="btn btn-success" onclick="startAttendance()">
                        <i class="fas fa-camera me-2"></i>Bắt đầu điểm danh
                    </button>
                </div>
            </div>
        </div>

        <div class="content-body p-4">
            <!-- Schedule Filter -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Lọc theo lớp</label>
                            <select class="form-select" id="classFilter">
                                <option value="">Tất cả lớp</option>
                                <option value="63CNTT.NB">63CNTT.NB</option>
                                <option value="63CNTT.VA">63CNTT.VA</option>
                                <option value="63CNTT1">63CNTT1</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ngày</label>
                            <input type="date" class="form-control" id="dateFilter" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary d-block" onclick="filterSchedule()">
                                <i class="fas fa-search me-1"></i>Lọc
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Lịch học hôm nay</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Môn học</th>
                                    <th>Lớp</th>
                                    <th>Phòng</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                    @foreach($schedule['schedule_sessions'] as $session)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">{{ $session['start_time'] }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $session['course_name'] }}</strong><br>
                                            <small class="text-muted">{{ $session['course_code'] }}</small>
                                        </td>
                                        <td>{{ $session['class_id'] }}</td>
                                        <td>{{ $session['classroom'] }}</td>
                                        <td>
                                            @php
                                                $currentTime = date('H:i');
                                                $sessionTime = $session['start_time'];
                                            @endphp
                                            @if($currentTime < $sessionTime)
                                                <span class="badge bg-warning">Chưa bắt đầu</span>
                                            @elseif($currentTime >= $sessionTime && $currentTime <= date('H:i', strtotime($sessionTime . ' +90 minutes')))
                                                <span class="badge bg-success">Đang diễn ra</span>
                                            @else
                                                <span class="badge bg-secondary">Đã kết thúc</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($currentTime >= $sessionTime && $currentTime <= date('H:i', strtotime($sessionTime . ' +90 minutes')))
                                                <button class="btn btn-sm btn-success" onclick="takeAttendance('{{ $session['class_id'] }}', '{{ $session['course_code'] }}')">
                                                    <i class="fas fa-camera me-1"></i>Điểm danh
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-info" onclick="viewAttendance('{{ $session['class_id'] }}', '{{ $session['course_code'] }}')">
                                                    <i class="fas fa-eye me-1"></i>Xem
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Không có lịch học hôm nay</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử điểm danh</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Ngày</th>
                                    <th>Sinh viên</th>
                                    <th>Môn học</th>
                                    <th>Lớp</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian điểm danh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance['date'] }}</td>
                                    <td>
                                        <strong>{{ $attendance['student_name'] }}</strong><br>
                                        <small class="text-muted">{{ $attendance['student_id'] }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $attendance['course_name'] }}</strong><br>
                                        <small class="text-muted">{{ $attendance['course_code'] }}</small>
                                    </td>
                                    <td>{{ $attendance['class_id'] }}</td>
                                    <td>
                                        @if($attendance['status'] == 'present')
                                            <span class="badge bg-success">Có mặt</span>
                                        @else
                                            <span class="badge bg-danger">Vắng mặt</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance['time'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có dữ liệu điểm danh</td>
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

<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm lịch học mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('attendances.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lớp</label>
                                <select class="form-select" name="class_id" required>
                                    <option value="">Chọn lớp</option>
                                    <option value="63CNTT.NB">63CNTT.NB</option>
                                    <option value="63CNTT.VA">63CNTT.VA</option>
                                    <option value="63CNTT1">63CNTT1</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Môn học</label>
                                <select class="form-select" name="course_code" required>
                                    <option value="">Chọn môn học</option>
                                    <option value="CSE111">CSE111 - Nhập môn lập trình</option>
                                    <option value="MATH111">MATH111 - Giải tích hàm một biến</option>
                                    <option value="CSE441">CSE441 - Phát triển ứng dụng mobile</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày học</label>
                                <input type="date" class="form-control" name="date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Giờ bắt đầu</label>
                                <input type="time" class="form-control" name="start_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phòng học</label>
                        <input type="text" class="form-control" name="classroom" placeholder="VD: 301-A2" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm lịch học</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function startAttendance() {
    // TODO: Implement face recognition attendance
    alert('Tính năng điểm danh bằng nhận diện khuôn mặt sẽ được triển khai sau');
}

function takeAttendance(classId, courseCode) {
    // TODO: Implement attendance taking
    alert('Điểm danh cho lớp ' + classId + ', môn ' + courseCode);
}

function viewAttendance(classId, courseCode) {
    // TODO: Implement view attendance
    alert('Xem điểm danh lớp ' + classId + ', môn ' + courseCode);
}

function filterSchedule() {
    const classFilter = document.getElementById('classFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    
    // TODO: Implement filtering
    console.log('Filter by class:', classFilter, 'date:', dateFilter);
}
</script>
@endsection

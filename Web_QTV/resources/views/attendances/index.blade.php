@extends('layouts.app')

@section('title', 'Lịch học & Điểm danh')
@section('page-title', 'Lịch học & Điểm danh')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <label class="me-2">Lọc theo trạng thái:</label>
            <select class="form-select" style="width: auto;">
                <option>Tất cả</option>
                <option>Đã điểm danh</option>
                <option>Chưa điểm danh</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Lớp học phần</th>
                                <th>Môn học</th>
                                <th>Ngày</th>
                                <th>Giờ học</th>
                                <th>Giảng viên</th>
                                <th>Trạng thái điểm danh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance['class'] }}</td>
                                <td>{{ $attendance['subject'] }}</td>
                                <td>{{ $attendance['date'] }}</td>
                                <td>{{ $attendance['time'] }}</td>
                                <td>{{ $attendance['teacher'] }}</td>
                                <td>
                                    @if($attendance['status'] == 'Đã điểm danh')
                                        <span class="badge bg-success">{{ $attendance['status'] }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $attendance['status'] }}</span>
                                    @endif
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

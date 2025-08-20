@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Điểm danh lớp: {{ $session->class->name }} - Môn: {{ $session->subject->name }}</h5>
        <p>Ngày: {{ $session->date }} | Tiết: {{ $session->period }}</p>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('attendance.store', $session->id) }}">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Họ tên</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->student_code }}</td>
                        <td>{{ $student->name }}</td>
                        <td>
                            <select name="attendance[{{ $student->id }}]" class="form-control">
                                <option value="present" {{ isset($attendance[$student->id]) && $attendance[$student->id]->status == 'present' ? 'selected' : '' }}>Có mặt</option>
                                <option value="absent" {{ isset($attendance[$student->id]) && $attendance[$student->id]->status == 'absent' ? 'selected' : '' }}>Vắng</option>
                                <option value="late" {{ isset($attendance[$student->id]) && $attendance[$student->id]->status == 'late' ? 'selected' : '' }}>Đi trễ</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Lưu điểm danh</button>
        </form>
    </div>
</div>
@endsection

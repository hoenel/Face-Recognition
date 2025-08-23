@extends('layouts.app')

@section('title', 'Kiểm tra tính toàn vẹn dữ liệu')
@section('page-title', 'Kiểm tra tính toàn vẹn dữ liệu')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background: #3498db; color: white;">
                <h6 class="mb-0">Danh sách lớp học phần không có sinh viên</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã lớp học phần</th>
                                <th>Tên lớp</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>LHP001</td>
                                <td>CTD1-OT</td>
                                <td><span class="badge bg-danger">Không có sinh viên</span></td>
                            </tr>
                            <tr>
                                <td>LHP002</td>
                                <td>Toán rời rạc</td>
                                <td><span class="badge bg-success">Hợp lệ</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background: #3498db; color: white;">
                <h6 class="mb-0">Lịch học không có giảng viên</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Mã lớp</th>
                                <th>Môn học</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>02/08/2025</td>
                                <td>LHP010</td>
                                <td>Mạng máy tính</td>
                                <td><span class="badge bg-danger">Thiếu giảng viên</span></td>
                            </tr>
                            <tr>
                                <td>03/08/2025</td>
                                <td>LHP011</td>
                                <td>CSDL</td>
                                <td><span class="badge bg-success">Đã phân công</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

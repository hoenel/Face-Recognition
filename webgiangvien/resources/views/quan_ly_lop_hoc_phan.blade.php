@extends('layouts.app')

@section('title', 'Quản lý lớp học phần - HTD')

@section('content')
<div class="container-fluid">
    <h2 class="mt-3 mb-4">Tạo buổi học / điểm danh</h2>
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="classSelect" class="form-label">Lớp:</label>
                    <select id="classSelect" class="form-select">
                        <option>63CNTT.VA</option>
                        <option>63CNTT.NB</option>
                        <option>63CNTT1</option>
                        <option>63CNTT2</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="subjectSelect" class="form-label">Môn học:</label>
                    <select id="subjectSelect" class="form-select">
                        <option>Lập trình ứng dụng di động</option>
                        <option>Cơ sở dữ liệu</option>
                        <option>Mạng máy tính</option>
                        <option>Hệ điều hành</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="dateInput" class="form-label">Ngày giảng dạy:</label>
                    <input type="date" id="dateInput" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="periodInput" class="form-label">Tiết học:</label>
                    <input type="text" id="periodInput" class="form-control" placeholder="VD: 1-3">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary">Tạo buổi học & Mở điểm danh</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Danh sách buổi học đã mở trong tháng:</h5>
            <div class="list-group">
                <div class="list-group-item">
                    <span class="badge bg-primary">63CNTT.VA</span> - Lập trình ứng dụng di động - 2025-07-31 - Tiết: 1-3
                </div>
                <div class="list-group-item">
                    <span class="badge bg-primary">63CNTT.NB</span> - Cơ sở dữ liệu - 2025-07-31 - Tiết: 4-6
                </div>
                <div class="list-group-item">
                    <span class="badge bg-primary">63CNTT1</span> - Mạng máy tính - 2025-08-01 - Tiết: 1-3
                </div>
                <div class="list-group-item">
                    <span class="badge bg-primary">63CNTT2</span> - Hệ điều hành - 2025-08-02 - Tiết: 4-6
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Trạng thái điểm danh')

@section('active-trangthaidiemdanh', 'active')

@section('content')
<div class="card">
    <h5 class="mb-3">Trạng thái điểm danh</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Tên SV</th>
                <th>Ngày</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dữ liệu sẽ được đổ từ Firebase -->
        </tbody>
    </table>
    <form class="row g-3 mb-3">
        <div class="col-md-3">
            <label class="form-label">Lớp</label>
            <select class="form-select">
                <option>DHCPMT12A</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Môn học</label>
            <select class="form-select">
                <option>Lập trình ứng dụng di động</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Ngày giảng dạy</label>
            <input type="date" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tiết học</label>
            <select class="form-select">
                <option>1-3</option>
            </select>
        </div>
    </form>
    <h6 class="mt-3">Trạng thái điểm danh buổi học:</h6>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>MSSV</th>
                <th>Họ tên</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2012345</td>
                <td>Nguyễn Văn A</td>
                <td>Có mặt</td>
            </tr>
            <tr>
                <td>2012346</td>
                <td>Trần Thị B</td>
                <td>Muộn</td>
            </tr>
            <tr>
                <td>2012350</td>
                <td>Phạm Văn D</td>
                <td>Vắng</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.querySelector('.table tbody');
    firebase.database().ref('trangthai').on('value', function(snapshot) {
        tbody.innerHTML = '';
        const data = snapshot.val();
        if(data) {
            Object.values(data).forEach(function(item) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.masv || ''}</td>
                    <td>${item.tensv || ''}</td>
                    <td>${item.ngay || ''}</td>
                    <td>${item.trangthai || ''}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="4">Không có dữ liệu</td></tr>';
        }
    });
});
</script>
@endsection

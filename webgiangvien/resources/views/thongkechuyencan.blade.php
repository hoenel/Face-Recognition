@extends('layouts.app')

@section('title', 'Thống kê chuyên cần')

@section('active-thongkechuyencan', 'active')

@section('content')
<div class="card">
    <h5 class="mb-3">Thống kê chuyên cần</h5>
    <div class="mb-3 row">
        <label class="col-sm-1 col-form-label">Lớp:</label>
        <div class="col-sm-3">
            <select class="form-select">
                <option>[DHKTPM17A]</option>
            </select>
        </div>
        <label class="col-sm-1 col-form-label">Môn học:</label>
        <div class="col-sm-3">
            <select class="form-select">
                <option>Lập trình ứng dụng di động</option>
            </select>
        </div>
    </div>
    <h2>Thống kê chuyên cần</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Tên SV</th>
                <th>Số buổi có mặt</th>
                <th>Tổng số buổi</th>
                <th>Tỷ lệ chuyên cần</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dữ liệu sẽ được đổ từ Firebase -->
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.querySelector('table tbody');
    firebase.database().ref('chuyencan').on('value', function(snapshot) {
        tbody.innerHTML = '';
        const data = snapshot.val();
        if(data) {
            Object.values(data).forEach(function(item) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.masv || ''}</td>
                    <td>${item.tensv || ''}</td>
                    <td>${item.sobuoi || ''}</td>
                    <td>${item.tongbuoi || ''}</td>
                    <td>${item.tyle || ''}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu</td></tr>';
        }
    });
});
</script>
@endsection

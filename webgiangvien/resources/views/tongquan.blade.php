@extends('layouts.app')

@section('title', 'Tổng quan lớp học')

@section('active-tongquan', 'active')

@section('content')
<div class="card">
    <h2>Tổng quan lớp học</h2>
    <table>
        <thead>
            <tr>
                <th>Mã lớp học phần</th>
                <th>Tên môn học</th>
                <th>Số tiết dạy</th>
                <th>Tổng số SV</th>
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
    firebase.database().ref('tongquan').on('value', function(snapshot) {
        tbody.innerHTML = '';
        const data = snapshot.val();
        if(data) {
            Object.values(data).forEach(function(item) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.malop || ''}</td>
                    <td>${item.tenmon || ''}</td>
                    <td>${item.sotiet || ''}</td>
                    <td>${item.tongsv || ''}</td>
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

@extends('layouts.app')

@section('title', 'Tạo buổi học / điểm danh')

@section('head')
<style>
    .form-group { margin-bottom: 15px; }
    .session-list { margin-top: 30px; }
    .session-item { background: #f4f6f9; padding: 8px 12px; border-radius: 6px; margin-bottom: 6px; }
</style>
@endsection

@section('active-taobuoihoc', 'active')

@section('content')
<div class="card">
    <h2>Tạo buổi học / điểm danh</h2>
    <div class="form-group">
        <label for="lop">Lớp</label>
        <select id="lop">
            <option>DHKTMT7A</option>
            <option>DHKTMT8B</option>
        </select>
    </div>
    <div class="form-group">
        <label for="monhoc">Môn học</label>
        <select id="monhoc">
            <option>Lập trình ứng dụng di động</option>
            <option>Cơ sở dữ liệu</option>
            <option>Mạng máy tính</option>
        </select>
    </div>
    <div class="form-group">
        <label for="ngay">Ngày giảng dạy</label>
        <input type="date" id="ngay">
    </div>
    <div class="form-group">
        <label for="tiet">Tiết học</label>
        <input type="text" id="tiet" placeholder="VD: 1-3">
    </div>
    <button onclick="themBuoiHoc()">Tạo buổi học & mở điểm danh</button>
    <div class="session-list" id="dsBuoiHoc">
        <h3>Danh sách buổi học đã mở trong tháng:</h3>
        <div class="session-item">DHKTMT7A - Lập trình ứng dụng di động - 2025-07-31 - Tiết: 1-3</div>
        <div class="session-item">DHKTMT7B - Cơ sở dữ liệu - 2025-07-31 - Tiết: 4-6</div>
        <div class="session-item">DHKTMT8A - Mạng máy tính - 2025-08-01 - Tiết: 1-3</div>
        <div class="session-item">DHKTMT8B - Hệ điều hành - 2025-08-02 - Tiết: 4-6</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function themBuoiHoc() {
    let lop = document.getElementById("lop").value;
    let monhoc = document.getElementById("monhoc").value;
    let ngay = document.getElementById("ngay").value;
    let tiet = document.getElementById("tiet").value;
    if(lop && monhoc && ngay && tiet){
        let div = document.createElement("div");
        div.className = "session-item";
        div.innerText = lop + " - " + monhoc + " - " + ngay + " - Tiết: " + tiet;
        document.getElementById("dsBuoiHoc").appendChild(div);
        alert("Đã tạo buổi học thành công!");
    } else {
        alert("Vui lòng nhập đầy đủ thông tin!");
    }
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Xuất báo cáo điểm danh')
@section('page-title', 'Xuất báo cáo điểm danh')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="classSelect" class="form-label">Chọn lớp học phần</label>
                        <select class="form-select" id="classSelect">
                            <option selected>CTD1-OT (LHP001)</option>
                            <option>Toán rời rạc (LHP002)</option>
                            <option>Mạng máy tính (LHP003)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fromDate" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="fromDate" value="2025-08-01">
                    </div>
                    
                    <div class="mb-3">
                        <label for="toDate" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="toDate" value="2025-08-31">
                    </div>
                    
                    <div class="mb-3">
                        <label for="reportFormat" class="form-label">Định dạng báo cáo</label>
                        <select class="form-select" id="reportFormat">
                            <option selected>.xlsx (Excel)</option>
                            <option>.pdf (PDF)</option>
                            <option>.csv (CSV)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i> Xuất báo cáo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

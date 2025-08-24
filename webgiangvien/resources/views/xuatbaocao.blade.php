@extends('layouts.app')

@section('title', 'Xuất báo cáo')

@section('active-xuatbaocao', 'active')

@section('content')
<div class="card">
  <h5>Xuất báo cáo chuyên cần</h5>
  <form class="mt-3">
    <div class="mb-3">
      <label for="lop" class="form-label">Lớp:</label>
      <select class="form-select" id="lop">
        <option>DS17CNTT</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="mon" class="form-label">Môn học:</label>
      <select class="form-select" id="mon">
        <option>Lập trình cơ bản</option>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Xuất báo cáo</button>
  </form>
  <div class="alert alert-success mt-3" role="alert">
    Báo cáo đã được xuất thành công!
  </div>
</div>
@endsection

@extends('layouts.auth')

@section('title', 'Đăng nhập - HTD')

@section('content')
<div class="login-header">
    <h1>HTD</h1>
    <p>Hệ thống điểm danh khuôn mặt</p>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    
    <button type="submit" class="btn btn-login">Đăng nhập</button>
</form>

<div class="demo-accounts">
    <h6>Tài khoản demo:</h6>
    <div class="demo-account">
        <i class="fas fa-user-shield"></i>
        admin@htd.edu.vn - <i class="fas fa-key"></i> 123456
    </div>
    <div class="demo-account">
        <i class="fas fa-user"></i>
        demo@example.com - <i class="fas fa-key"></i> 123456
    </div>
    <div class="demo-account">
        <i class="fas fa-chalkboard-teacher"></i>
        teacher@htd.edu.vn - <i class="fas fa-key"></i> 123456
    </div>
</div>
@endsection

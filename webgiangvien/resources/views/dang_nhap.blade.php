@extends('layouts.auth')

@section('title', 'Đăng nhập - HTD')

@section('content')
<div class="login-header">
    <h1>HTD - điểm danh</h1>
    <p>Đăng nhập hệ thống</p>
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
@endsection

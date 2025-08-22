@extends('layouts.auth')

@section('title', 'Đăng nhập - HTD điểm danh')

@section('content')
<div class="login-container">
    <div class="login-form-wrapper">
        <div class="login-form">
            <!-- Header -->
            <div class="login-header">
                <h5 class="app-title">HTD - điểm_danh</h5>
                <h6 class="login-subtitle">Đăng nhập hệ thống</h6>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Email"
                       required>

                <!-- Password Field -->
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Mật khẩu"
                       required>

                <!-- Login Button -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <span id="loginText">Đăng nhập</span>
                    <span id="loginSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                </button>

                <!-- Forgot Password Link -->
                <div class="forgot-password">
                    <small>Quên mật khẩu? Liên hệ IT</small>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form submission with loading state
    $('#loginForm').submit(function() {
        const loginBtn = $('#loginBtn');
        const loginText = $('#loginText');
        const loginSpinner = $('#loginSpinner');
        
        loginBtn.prop('disabled', true);
        loginText.text('Đang xử lý...');
        loginSpinner.removeClass('d-none');
    });
    
    // Demo: Click anywhere to auto-fill (for testing)
    $('.login-form').click(function() {
        if ($('#email').val() === '') {
            $('#email').val('admin@example.com');
            $('#password').val('password');
        }
    });
});
</script>
@endpush

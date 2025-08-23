<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTD - Điểm danh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-title {
            color: #3498db;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .login-subtitle {
            color: #34495e;
            font-size: 16px;
            font-weight: 500;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-login {
            background: #3498db;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: 500;
            padding: 12px;
            width: 100%;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }
        
        .forgot-password a {
            color: #7f8c8d;
            text-decoration: none;
            font-size: 12px;
        }
        
        .forgot-password a:hover {
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2 class="login-title">HTD - điểm_danh</h2>
            <p class="login-subtitle">Đăng nhập hệ thống</p>
        </div>
        
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            @if ($errors->any())
                <div class="alert alert-danger alert-sm">
                    {{ $errors->first() }}
                </div>
            @endif
            
            @if (session('success'))
                <div class="alert alert-success alert-sm">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="mb-3">
                <input type="email" 
                       class="form-control" 
                       name="email" 
                       placeholder="Email" 
                       value="{{ old('email') }}" 
                       required>
            </div>
            
            <div class="mb-3">
                <input type="password" 
                       class="form-control" 
                       name="password" 
                       placeholder="Mật khẩu" 
                       required>
            </div>
            
            <button type="submit" class="btn btn-login">
                Đăng nhập
            </button>
            
            <div class="forgot-password">
                <a href="#">Quên mật khẩu? Liên hệ IT</a>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

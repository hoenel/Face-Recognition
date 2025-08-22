<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Đăng nhập - HTD điểm danh')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --gray-bg: #e5e5e5;
            --form-bg: #ffffff;
            --text-primary: #374151;
            --text-secondary: #9ca3af;
            --input-border: #d1d5db;
            --input-bg: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--gray-bg);
            color: var(--text-primary);
            line-height: 1.5;
        }

        .login-container {
            min-height: 100vh;
            background-color: var(--gray-bg);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 350px;
        }

        .login-form {
            background: var(--form-bg);
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .app-title {
            color: var(--primary-color);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 0;
        }

        .form-control {
            border: 1px solid var(--input-border);
            border-radius: 4px;
            padding: 12px 15px;
            font-size: 14px;
            background-color: var(--input-bg);
            transition: all 0.2s ease;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: var(--form-bg);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .btn-login {
            background-color: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: white;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            width: 100%;
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            color: white;
        }

        .btn-login:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
            color: white;
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password small {
            color: var(--text-secondary);
            font-size: 12px;
        }

        .alert {
            font-size: 13px;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #dc2626;
        }

        /* Animation */
        .login-form {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Mobile responsive */
        @media (max-width: 576px) {
            .login-form-wrapper {
                max-width: 300px;
            }
            
            .login-form {
                padding: 30px 25px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>

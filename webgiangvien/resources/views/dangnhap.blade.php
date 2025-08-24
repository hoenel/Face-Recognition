<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f8fafc;
        }
        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            width: 350px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .login-box button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .login-box button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-box">
    <h2>Đăng nhập</h2>
    <input type="text" id="email" placeholder="Email đăng nhập" required class="form-control mb-2">
    <input type="password" id="password" placeholder="Mật khẩu" required class="form-control mb-2">
    <button type="button" class="btn btn-primary w-100" onclick="loginFirebase()">Đăng nhập</button>
    </div>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore-compat.js"></script>
    <script>
    const firebaseConfig = {
        apiKey: "AIzaSyAygULXRSt9Nsqy2rb9Z4NNvh4Z4KvdK7c",
        authDomain: "facerecognitionapp-f034d.firebaseapp.com",
        databaseURL: "https://facerecognitionapp-f034d-default-rtdb.asia-southeast1.firebasedatabase.app",
        projectId: "facerecognitionapp-f034d",
        storageBucket: "facerecognitionapp-f034d.firebasestorage.app",
        messagingSenderId: "1042946521446",
        appId: "1:1042946521446:web:02de5802629d422a5330a7"
    };
    if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
    const db = firebase.firestore();
    function loginFirebase() {
        const email = document.getElementById('email').value.trim().toLowerCase();
        const password = document.getElementById('password').value.trim();
        if (!email || !password) {
            alert('Vui lòng nhập đầy đủ thông tin!');
            return;
        }
        db.collection('users').doc('2FLgrE6Xz7bksTdbps1HJfc3y383').get().then(function(doc) {
            const user = doc.exists ? doc.data() : null;
            const dbEmail = (user && user.email) ? user.email.trim().toLowerCase() : '';
            console.log('Firestore user:', user);
            console.log('Email nhập:', email);
            console.log('Email trong DB:', dbEmail);
            if (user && dbEmail === email && password === 'giangvien123') {
                window.location.href = "{{ route('tongquan') }}";
            } else {
                alert('Email hoặc mật khẩu không đúng!');
            }
        });
    }
    </script>
</body>
</html>

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseService;

class AuthController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService = null)
    {
        $this->firebaseService = $firebaseService;
    }

    public function showLogin()
    {
        // Kiểm tra nếu đã đăng nhập thì redirect về dashboard
        if (Session::has('user')) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Tạm thời sử dụng admin account cố định để test UI
            if ($credentials['email'] === 'admin@example.com' && $credentials['password'] === 'password') {
                // Đăng nhập thành công, lưu thông tin user vào session
                Session::put('user', [
                    'id' => 'admin123',
                    'name' => 'Administrator',
                    'email' => 'admin@example.com',
                    'role' => 'admin',
                    'student_id' => null,
                    'phone' => null,
                ]);

                Session::regenerate();

                return redirect()->intended('dashboard')->with('success', 'Đăng nhập thành công!');
            } else {
                return back()->withErrors([
                    'email' => 'Email hoặc mật khẩu không chính xác.',
                ])->withInput($request->only('email'));
            }
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại.',
            ])->withInput($request->only('email'));
        }

        // TODO: Khi Firebase setup xong, uncomment code dưới đây
        /*
        try {
            // Tìm user trong Firebase theo email
            $usersCollection = $this->firebaseService->getFirestore()
                ->collection('users')
                ->where('email', '=', $credentials['email'])
                ->documents();

            $user = null;
            foreach ($usersCollection as $userDoc) {
                $userData = $userDoc->data();
                $userData['id'] = $userDoc->id();
                
                // Kiểm tra mật khẩu
                if (Hash::check($credentials['password'], $userData['password'])) {
                    $user = $userData;
                    break;
                }
            }

            if ($user) {
                // Đăng nhập thành công, lưu thông tin user vào session
                Session::put('user', [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'student_id' => $user['student_id'] ?? null,
                    'phone' => $user['phone'] ?? null,
                ]);

                Session::regenerate();

                return redirect()->intended('dashboard')->with('success', 'Đăng nhập thành công!');
            } else {
                return back()->withErrors([
                    'email' => 'Email hoặc mật khẩu không chính xác.',
                ])->withInput($request->only('email'));
            }

        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại.',
            ])->withInput($request->only('email'));
        }
        */
    }

    public function logout(Request $request)
    {
        Session::forget('user');
        Session::invalidate();
        Session::regenerateToken();
        
        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }

    // Helper method để tạo user admin mặc định (chỉ để test)
    public function createDefaultAdmin()
    {
        try {
            $adminData = [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'student_id' => null,
                'phone' => null,
                'face_data' => null,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ];

            $this->firebaseService->createUser($adminData);

            return response()->json([
                'success' => true,
                'message' => 'Admin account created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating admin: ' . $e->getMessage()
            ], 500);
        }
    }
}

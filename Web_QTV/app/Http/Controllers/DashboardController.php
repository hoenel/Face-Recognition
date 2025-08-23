<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class DashboardController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        // Lấy user từ session
        $user = [
            'id' => session('user_id', 'admin'),
            'name' => session('user_name', 'Administrator'),
            'email' => session('user_email', 'admin@example.com'),
            'role' => session('user_role', 'admin')
        ];
        
        try {
            // Lấy dữ liệu thống kê từ Firebase - chỉ từ collections có thật
            $students = $this->firebaseService->getAllStudents();
            $users = $this->firebaseService->getAllUsers();
            $classes = $this->firebaseService->getAllClasses();
            // KHÔNG lấy attendances vì collection không tồn tại
            
            $stats = [
                'students' => count($students),
                'teachers' => count($users),
                'classes' => count($classes),
                'attendance_rate' => 0 // Tạm thời set 0 vì chưa có attendance data
            ];
        } catch (\Exception $e) {
            \Log::error('Dashboard Firebase error: ' . $e->getMessage());
            // Fallback to mock data if Firebase fails
            $stats = [
                'students' => 125,
                'teachers' => 15,
                'classes' => 8,
                'attendance_rate' => 0
            ];
        }

        return view('dashboard.index', compact('user', 'stats'));
    }

    public function profile()
    {
        $user = [
            'id' => session('user_id', 'admin'),
            'name' => session('user_name', 'Administrator'),
            'email' => session('user_email', 'admin@example.com'),
            'role' => session('user_role', 'admin')
        ];
        
        return view('dashboard.profile', compact('user'));
    }
}
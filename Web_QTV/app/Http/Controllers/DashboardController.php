<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        try {
            $user = Session::get('user');
            
            // Lấy thống kê thực từ Firebase
            $studentsCount = $this->getCollectionCount('students');
            $teachersCount = $this->getUserCountByRole('teacher');
            $classesCount = $this->getCollectionCount('classes');
            $attendanceRate = $this->calculateAttendanceRate();

            $stats = [
                'students' => $studentsCount,
                'teachers' => $teachersCount,
                'classes' => $classesCount,
                'attendance_rate' => $attendanceRate
            ];

            return view('dashboard.index', compact('user', 'stats'));
        } catch (\Exception $e) {
            // Fallback to default values if Firebase fails
            $user = Session::get('user');
            $stats = [
                'students' => 0,
                'teachers' => 0,
                'classes' => 0,
                'attendance_rate' => 0
            ];
            
            return view('dashboard.index', compact('user', 'stats'))
                ->with('error', 'Không thể tải thống kê: ' . $e->getMessage());
        }
    }

    public function profile()
    {
        $user = Session::get('user');
        return view('dashboard.profile', compact('user'));
    }

    private function getCollectionCount($collectionName)
    {
        try {
            switch ($collectionName) {
                case 'students':
                    return count($this->firebaseService->getAllStudents());
                case 'classes':
                    return count($this->firebaseService->getAllClassRooms());
                case 'courses':
                    return count($this->firebaseService->getAllSubjects());
                default:
                    return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getUserCountByRole($role)
    {
        try {
            $users = $this->firebaseService->getAllUsers();
            $count = 0;
            foreach ($users as $user) {
                if (isset($user['role']) && $user['role'] === $role) {
                    $count++;
                }
            }
            return $count;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function calculateAttendanceRate()
    {
        try {
            $attendances = $this->firebaseService->getAllAttendances();
            $total = count($attendances);
            $present = 0;
            
            foreach ($attendances as $attendance) {
                if (isset($attendance['status']) && $attendance['status'] === 'present') {
                    $present++;
                }
            }
            
            return $total > 0 ? round(($present / $total) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}

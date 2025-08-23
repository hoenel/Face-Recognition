<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class AttendanceController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        try {
            // Lấy dữ liệu từ Firebase - READ ONLY
            $schedules = $this->firebaseService->getAllSchedules();
            $attendances = $this->firebaseService->getAllAttendances();
            $students = $this->firebaseService->getAllStudents();
            $classes = $this->firebaseService->getAllClasses();
            
            // Thống kê
            $stats = [
                'total_schedules' => count($schedules),
                'total_attendances' => count($attendances),
                'total_students' => count($students),
                'attendance_rate' => count($students) > 0 ? round((count($attendances) / count($students)) * 100, 1) : 0
            ];
            
            return view('attendances.index', compact('schedules', 'attendances', 'students', 'classes', 'stats'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }
}
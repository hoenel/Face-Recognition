<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ClassSession;
use App\Models\Student;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    // Hiển thị danh sách sinh viên để điểm danh
    public function show($sessionId)
    {
        $session = ClassSession::with('subject')->findOrFail($sessionId);
        $students = Student::where('class_id', $session->class_id)->get();
        
        // Lấy trạng thái điểm danh trước đó (nếu có)
        $attendance = Attendance::where('session_id', $sessionId)->get()->keyBy('student_id');

        return view('attendance.show', compact('session', 'students', 'attendance'));
    }

    // Lưu kết quả điểm danh
    public function store(Request $request, $sessionId)
    {
        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                ['session_id' => $sessionId, 'student_id' => $studentId],
                ['status' => $status]
            );
        }

        return redirect()->back()->with('success', 'Lưu điểm danh thành công!');
    }
}
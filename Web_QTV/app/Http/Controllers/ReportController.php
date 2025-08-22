<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ReportController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        try {
            // Lấy dữ liệu từ Firebase service (mock data)
            $classesData = $this->firebaseService->getAllClassRooms();
            $studentsData = $this->firebaseService->getAllStudents();
            $coursesData = $this->firebaseService->getAllSubjects();

            return view('reports.index', [
                'classes' => $classesData,
                'students' => $studentsData,
                'courses' => $coursesData
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải dữ liệu báo cáo: ' . $e->getMessage());
        }
    }

    public function generate(Request $request)
    {
        try {
            $reportType = $request->input('type');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            $classFilter = $request->input('class_filter');
            $format = $request->input('format', 'excel');

            switch ($reportType) {
                case 'attendance':
                    return $this->generateAttendanceReport($fromDate, $toDate, $classFilter, $format);
                case 'student':
                    return $this->generateStudentReport($fromDate, $toDate, $classFilter, $format);
                case 'schedule':
                    return $this->generateScheduleReport($fromDate, $toDate, $classFilter, $format);
                default:
                    return response()->json(['error' => 'Loại báo cáo không hợp lệ'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function download($id)
    {
        try {
            // TODO: Implement file download logic
            return response()->json(['message' => 'Tải xuống file: ' . $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function generateAttendanceReport($fromDate, $toDate, $classFilter, $format)
    {
        try {
            // Lấy dữ liệu điểm danh từ Firebase
            $attendancesQuery = $this->firebaseService->getFirestore()
                ->collection('attendances');

            if ($fromDate) {
                $attendancesQuery = $attendancesQuery->where('date', '>=', $fromDate);
            }
            if ($toDate) {
                $attendancesQuery = $attendancesQuery->where('date', '<=', $toDate);
            }
            if ($classFilter) {
                $attendancesQuery = $attendancesQuery->where('class_id', '=', $classFilter);
            }

            $attendances = $attendancesQuery->documents();
            
            $reportData = [];
            foreach ($attendances as $attendance) {
                $attendanceData = $attendance->data();
                $attendanceData['id'] = $attendance->id();
                $reportData[] = $attendanceData;
            }

            // TODO: Generate actual file (Excel, PDF, CSV)
            return response()->json([
                'success' => true,
                'message' => "Báo cáo điểm danh đã được tạo ({$format})",
                'data_count' => count($reportData),
                'filename' => "attendance_report_" . date('Y-m-d') . ".{$format}"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function generateStudentReport($fromDate, $toDate, $classFilter, $format)
    {
        try {
            // Lấy dữ liệu sinh viên từ Firebase
            $studentsQuery = $this->firebaseService->getFirestore()
                ->collection('students');

            if ($classFilter) {
                $studentsQuery = $studentsQuery->where('class_id', '=', $classFilter);
            }

            $students = $studentsQuery->documents();
            
            $reportData = [];
            foreach ($students as $student) {
                $studentData = $student->data();
                $studentData['id'] = $student->id();
                
                // Lấy thêm thông tin điểm danh của sinh viên
                $attendanceQuery = $this->firebaseService->getFirestore()
                    ->collection('attendances')
                    ->where('student_id', '=', $studentData['student_id']);

                if ($fromDate) {
                    $attendanceQuery = $attendanceQuery->where('date', '>=', $fromDate);
                }
                if ($toDate) {
                    $attendanceQuery = $attendanceQuery->where('date', '<=', $toDate);
                }

                $attendanceRecords = $attendanceQuery->documents();
                $attendanceCount = 0;
                $presentCount = 0;

                foreach ($attendanceRecords as $record) {
                    $attendanceCount++;
                    if ($record->data()['status'] === 'present') {
                        $presentCount++;
                    }
                }

                $studentData['total_sessions'] = $attendanceCount;
                $studentData['present_sessions'] = $presentCount;
                $studentData['attendance_rate'] = $attendanceCount > 0 ? round(($presentCount / $attendanceCount) * 100, 2) : 0;
                
                $reportData[] = $studentData;
            }

            // TODO: Generate actual file (Excel, PDF, CSV)
            return response()->json([
                'success' => true,
                'message' => "Báo cáo sinh viên đã được tạo ({$format})",
                'data_count' => count($reportData),
                'filename' => "student_report_" . date('Y-m-d') . ".{$format}"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function generateScheduleReport($fromDate, $toDate, $classFilter, $format)
    {
        try {
            // Lấy dữ liệu lịch học từ Firebase
            $schedulesQuery = $this->firebaseService->getFirestore()
                ->collection('schedules');

            if ($classFilter) {
                $schedulesQuery = $schedulesQuery->where('class_id', '=', $classFilter);
            }

            $schedules = $schedulesQuery->documents();
            
            $reportData = [];
            foreach ($schedules as $schedule) {
                $scheduleData = $schedule->data();
                $scheduleData['id'] = $schedule->id();
                $reportData[] = $scheduleData;
            }

            // TODO: Generate actual file (Excel, PDF, CSV)
            return response()->json([
                'success' => true,
                'message' => "Báo cáo lịch học đã được tạo ({$format})",
                'data_count' => count($reportData),
                'filename' => "schedule_report_" . date('Y-m-d') . ".{$format}"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

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
            // Lấy dữ liệu điểm danh từ mock service
            $attendances = $this->firebaseService->getAllAttendances();

            // Lấy dữ liệu lịch học hôm nay từ mock service
            $schedules = $this->firebaseService->getAllSchedules();
            
            return view('attendances.index', compact('attendances', 'schedules'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải danh sách điểm danh: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Lấy danh sách lớp học
            $classRoomsCollection = $this->firebaseService->getAllClassRooms();
            $classRooms = [];
            foreach ($classRoomsCollection as $classRoom) {
                $classRoomData = $classRoom->data();
                $classRoomData['id'] = $classRoom->id();
                $classRooms[] = $classRoomData;
            }
            
            return view('attendances.create', compact('classRooms'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|string',
            'student_id' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late',
            'face_recognition_data' => 'nullable|array',
        ]);

        try {
            $attendanceData = [
                'class_id' => $request->class_id,
                'student_id' => $request->student_id,
                'date' => new \DateTime($request->date),
                'status' => $request->status,
                'face_recognition_data' => $request->face_recognition_data,
                'check_in_time' => new \DateTime(),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ];

            $this->firebaseService->createAttendance($attendanceData);

            return redirect()->route('attendances.index')->with('success', 'Điểm danh đã được ghi nhận!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể ghi nhận điểm danh: ' . $e->getMessage());
        }
    }

    public function checkAttendanceByFace(Request $request)
    {
        $request->validate([
            'class_id' => 'required|string',
            'face_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Upload ảnh khuôn mặt
            $imagePath = $request->file('face_image')->store('temp');
            $fileName = 'attendance_' . time() . '.' . $request->file('face_image')->getClientOriginalExtension();
            
            // Gọi API nhận diện khuôn mặt (giả lập)
            // Trong thực tế, bạn sẽ gọi Firebase ML hoặc API nhận diện khuôn mặt khác
            $recognitionResult = $this->processFaceRecognition($imagePath);
            
            if ($recognitionResult && isset($recognitionResult['student_id'])) {
                $attendanceData = [
                    'class_id' => $request->class_id,
                    'student_id' => $recognitionResult['student_id'],
                    'date' => new \DateTime(),
                    'status' => 'present',
                    'face_recognition_data' => $recognitionResult,
                    'check_in_time' => new \DateTime(),
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime(),
                ];

                $this->firebaseService->createAttendance($attendanceData);

                return response()->json([
                    'success' => true,
                    'message' => 'Điểm danh thành công!',
                    'student_id' => $recognitionResult['student_id']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không nhận diện được khuôn mặt!'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAttendancesByClass($classId)
    {
        try {
            $attendancesCollection = $this->firebaseService->getAttendancesByClass($classId);
            $attendances = [];
            
            foreach ($attendancesCollection as $attendance) {
                $attendanceData = $attendance->data();
                $attendanceData['id'] = $attendance->id();
                
                // Lấy thông tin sinh viên
                $studentDoc = $this->firebaseService->getUser($attendanceData['student_id']);
                if ($studentDoc->exists()) {
                    $attendanceData['student'] = $studentDoc->data();
                }
                
                $attendances[] = $attendanceData;
            }
            
            return view('attendances.by_class', compact('attendances', 'classId'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải danh sách điểm danh: ' . $e->getMessage());
        }
    }

    public function getAttendancesByStudent($studentId)
    {
        try {
            $attendancesCollection = $this->firebaseService->getAttendancesByStudent($studentId);
            $attendances = [];
            
            foreach ($attendancesCollection as $attendance) {
                $attendanceData = $attendance->data();
                $attendanceData['id'] = $attendance->id();
                
                // Lấy thông tin lớp học
                $classDoc = $this->firebaseService->getClassRoom($attendanceData['class_id']);
                if ($classDoc->exists()) {
                    $attendanceData['class'] = $classDoc->data();
                }
                
                $attendances[] = $attendanceData;
            }
            
            return view('attendances.by_student', compact('attendances', 'studentId'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải danh sách điểm danh: ' . $e->getMessage());
        }
    }

    private function processFaceRecognition($imagePath)
    {
        // Giả lập xử lý nhận diện khuôn mặt
        // Trong thực tế, bạn sẽ tích hợp với Firebase ML hoặc API nhận diện khuôn mặt
        
        // Trả về kết quả giả lập
        return [
            'student_id' => 'student_123', // ID sinh viên được nhận diện
            'confidence' => 0.95,
            'face_coordinates' => [
                'x' => 100,
                'y' => 150,
                'width' => 200,
                'height' => 250
            ],
            'timestamp' => time()
        ];
    }
}

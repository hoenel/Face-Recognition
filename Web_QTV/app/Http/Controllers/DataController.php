<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class DataController extends Controller
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
            $usersData = $this->firebaseService->getAllUsers();
            $coursesData = $this->firebaseService->getAllSubjects(); 
            $classesData = $this->firebaseService->getAllClassRooms();
            $schedulesData = $this->firebaseService->getAllSchedules();
            $studentsData = $this->firebaseService->getAllStudents();

            return view('data.index', [
                'users' => $usersData,
                'courses' => $coursesData,
                'classes' => $classesData,
                'schedules' => $schedulesData,
                'students' => $studentsData
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải dữ liệu kiểm tra: ' . $e->getMessage());
        }
    }

    public function validate(Request $request)
    {
        try {
            $type = $request->input('type');
            $result = [];

            switch ($type) {
                case 'users':
                    $result = $this->validateUsers();
                    break;
                case 'courses':
                    $result = $this->validateCourses();
                    break;
                case 'classes':
                    $result = $this->validateClasses();
                    break;
                case 'schedules':
                    $result = $this->validateSchedules();
                    break;
                case 'students':
                    $result = $this->validateStudents();
                    break;
                default:
                    $result = ['error' => 'Loại dữ liệu không hợp lệ'];
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sync(Request $request)
    {
        try {
            // TODO: Implement Firebase sync logic
            return response()->json(['success' => true, 'message' => 'Đồng bộ thành công']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function validateUsers()
    {
        try {
            $users = $this->firebaseService->getAllUsers();
            $valid = 0;
            $errors = [];

            foreach ($users as $user) {
                // Kiểm tra các trường bắt buộc
                if (empty($user['email']) || empty($user['name'])) {
                    $errors[] = "User {$user['id']} thiếu thông tin email hoặc tên";
                } else {
                    $valid++;
                }

                // Kiểm tra dữ liệu khuôn mặt cho sinh viên
                if (isset($user['role']) && $user['role'] === 'student' && empty($user['face_data'])) {
                    $errors[] = "Sinh viên {$user['name']} chưa có dữ liệu khuôn mặt";
                }
            }

            return [
                'valid' => $valid,
                'errors' => count($errors),
                'error_details' => $errors
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function validateCourses()
    {
        try {
            $courses = $this->firebaseService->getAllSubjects();
            $valid = 0;
            $errors = [];

            foreach ($courses as $course) {
                if (empty($course['course_code']) || empty($course['course_name'])) {
                    $errors[] = "Môn học {$course['id']} thiếu mã môn hoặc tên môn";
                } else {
                    $valid++;
                }
            }

            return [
                'valid' => $valid,
                'errors' => count($errors),
                'error_details' => $errors
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function validateClasses()
    {
        try {
            $classes = $this->firebaseService->getAllClassRooms();
            $valid = 0;
            $errors = [];

            foreach ($classes as $class) {
                if (empty($class['id'])) {
                    $errors[] = "Lớp thiếu mã lớp";
                } else {
                    $valid++;
                }

                if (empty($class['teacher_id'])) {
                    $errors[] = "Lớp {$class['id']} chưa có giảng viên phụ trách";
                }
            }

            return [
                'valid' => $valid,
                'errors' => count($errors),
                'error_details' => $errors
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function validateSchedules()
    {
        try {
            $schedules = $this->firebaseService->getAllSchedules();
            $valid = 0;
            $errors = [];

            foreach ($schedules as $schedule) {
                if (empty($schedule['id']) || empty($schedule['schedule_sessions'])) {
                    $errors[] = "Lịch học {$schedule['id']} thiếu thông tin lớp hoặc buổi học";
                } else {
                    $valid++;
                }
            }

            return [
                'valid' => $valid,
                'errors' => count($errors),
                'error_details' => $errors
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function validateStudents()
    {
        try {
            $students = $this->firebaseService->getAllStudents();
            $valid = 0;
            $errors = [];

            foreach ($students as $student) {
                if (empty($student['id']) || empty($student['name'])) {
                    $errors[] = "Sinh viên {$student['id']} thiếu mã SV hoặc tên";
                } else {
                    $valid++;
                }
            }

            return [
                'valid' => $valid,
                'errors' => count($errors),
                'error_details' => $errors
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

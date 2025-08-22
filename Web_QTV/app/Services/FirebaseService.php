<?php

namespace App\Services;

use Google\Cloud\Firestore\FirestoreClient;

class FirebaseService
{
    private $firestore;

    public function __construct()
    {
        // For now, we'll use mock data that matches the real Firebase structure
        // TODO: Initialize Firebase when ready
        // $this->firestore = new FirestoreClient([
        //     'projectId' => 'facerecognitionapp-f034d',
        //     'keyFilePath' => app_path('firebase/facerecognitionapp-f034d-firebase-adminsdk-fbsvc-fe97396fa1.json'),
        // ]);
    }

    // Users Collection - structure based on Firebase data
    public function createUser($data)
    {
        // Mock implementation
        return ['id' => 'mock_user_id', 'message' => 'User created successfully'];
    }

    public function getUser($id)
    {
        // Mock data for testing
        if ($id === 'admin') {
            return [
                'id' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'role' => 'admin'
            ];
        }
        return null;
    }

    public function getUserByEmail($email)
    {
        // Mock data for testing
        if ($email === 'admin@example.com') {
            return [
                'id' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'role' => 'admin'
            ];
        }
        return null;
    }

    public function getAllUsers()
    {
        // Mock data matching Firebase structure
        return [
            [
                'id' => '3bcHQ3MTnBWDoasnkUNG8WNbchM2',
                'class_id' => '63CNTT.NB',
                'email' => 'lehoancdoc@gmail.com',
                'name' => 'Lê Hoàn',
                'student_id' => '2151062768'
            ],
            [
                'id' => 'SStxxMeBcRXDVG4B59XcVeGioiK2',
                'class_id' => '63CNTT.VA',
                'email' => 'teacher@example.com',
                'name' => 'Giáo viên',
                'role' => 'teacher'
            ]
        ];
    }

    public function updateUser($id, $data)
    {
        return ['message' => 'User updated successfully'];
    }

    public function deleteUser($id)
    {
        return ['message' => 'User deleted successfully'];
    }

    // Courses Collection - renamed from Subjects to match Firebase
    public function createSubject($data)
    {
        return ['id' => 'mock_subject_id', 'message' => 'Subject created successfully'];
    }

    public function getAllSubjects()
    {
        // Mock data matching Firebase courses structure
        return [
            [
                'id' => 'CSE111',
                'course_code' => 'CSE111',
                'course_name' => 'Nhập môn lập trình',
                'teacher_name' => 'Trương Xuân Nam'
            ],
            [
                'id' => 'CSE284',
                'course_code' => 'CSE284',
                'course_name' => 'Cấu trúc dữ liệu',
                'teacher_name' => 'Nguyễn Văn A'
            ],
            [
                'id' => 'CSE295',
                'course_code' => 'CSE295',
                'course_name' => 'Lập trình hướng đối tượng',
                'teacher_name' => 'Trần Thị B'
            ],
            [
                'id' => 'MATH111',
                'course_code' => 'MATH111',
                'course_name' => 'Giải tích hàm một biến(MATH111)',
                'teacher_name' => 'Lê Văn C'
            ]
        ];
    }

    public function getSubject($id)
    {
        return [
            'id' => $id,
            'course_code' => 'CSE111',
            'course_name' => 'Nhập môn lập trình',
            'teacher_name' => 'Trương Xuân Nam'
        ];
    }

    public function updateSubject($id, $data)
    {
        return ['message' => 'Subject updated successfully'];
    }

    public function deleteSubject($id)
    {
        return ['message' => 'Subject deleted successfully'];
    }

    // Classes Collection - updated to match Firebase structure
    public function createClassRoom($data)
    {
        return ['id' => 'mock_class_id', 'message' => 'Class created successfully'];
    }

    public function getAllClassRooms()
    {
        // Mock data matching Firebase classes structure
        return [
            [
                'id' => '63CNTT.NB',
                'class_name' => 'K63 Công nghệ thông tin Việt-Nhật',
                'student_ids' => ['2151062753', '2151062894'],
                'teacher_id' => 'TXT-123'
            ],
            [
                'id' => '63CNTT.VA',
                'class_name' => 'K63 Công nghệ thông tin Việt-Anh',
                'student_ids' => ['2151860244', '2151862753'],
                'teacher_id' => 'TXT-124'
            ],
            [
                'id' => '63CNTT1',
                'class_name' => 'K63 Công nghệ thông tin 1',
                'student_ids' => ['2151862754', '2151862755'],
                'teacher_id' => 'TXT-125'
            ]
        ];
    }

    public function getClassRoom($id)
    {
        return [
            'id' => $id,
            'class_name' => 'K63 Công nghệ thông tin Việt-Nhật',
            'student_ids' => ['2151062753', '2151062894'],
            'teacher_id' => 'TXT-123'
        ];
    }

    public function updateClassRoom($id, $data)
    {
        return ['message' => 'Class updated successfully'];
    }

    public function deleteClassRoom($id)
    {
        return ['message' => 'Class deleted successfully'];
    }

    // Students Collection - new method to match Firebase structure
    public function getAllStudents()
    {
        // Mock data matching Firebase students structure
        return [
            [
                'id' => '2151860244',
                'class_id' => '63CNTT.VA',
                'course_enrollments' => 'CSE205, CSE294, MATH533',
                'date_of_birth' => '02/04/2003',
                'field_of_study' => 'Công nghệ thông tin',
                'hometown' => 'Hà Nội',
                'name' => 'Đặng Hải Sơn'
            ],
            [
                'id' => '2151862753',
                'class_id' => '63CNTT.NB',
                'course_enrollments' => 'CSE111, MATH111',
                'date_of_birth' => '15/06/2003',
                'field_of_study' => 'Công nghệ thông tin',
                'hometown' => 'Hồ Chí Minh',
                'name' => 'Nguyễn Văn An'
            ]
        ];
    }

    public function getStudent($id)
    {
        return [
            'id' => $id,
            'class_id' => '63CNTT.VA',
            'course_enrollments' => 'CSE205, CSE294, MATH533',
            'date_of_birth' => '02/04/2003',
            'field_of_study' => 'Công nghệ thông tin',
            'hometown' => 'Hà Nội',
            'name' => 'Đặng Hải Sơn'
        ];
    }

    // Schedules Collection - new method to match Firebase structure
    public function getAllSchedules()
    {
        // Mock data matching Firebase schedules structure
        return [
            [
                'id' => '63CNTT.NB',
                'schedule_sessions' => [
                    [
                        'class_id' => '63CNTT.NB',
                        'classroom' => '301-A2',
                        'course_code' => 'MATH111',
                        'course_name' => 'Giải tích hàm một biến(MATH111)',
                        'date' => 'Thứ 2, ngày 4/8/2025',
                        'start_time' => '9:45'
                    ],
                    [
                        'class_id' => '63CNTT.NB',
                        'classroom' => '208-B5',
                        'course_code' => 'CSE441',
                        'course_name' => 'Phát triển ứng dụng cho các thiết bị di động(CSE441)',
                        'date' => 'Thứ 3, ngày 5/8/2025',
                        'start_time' => '7:00'
                    ]
                ]
            ]
        ];
    }

    public function getSchedule($classId)
    {
        return [
            'id' => $classId,
            'schedule_sessions' => [
                [
                    'class_id' => $classId,
                    'classroom' => '301-A2',
                    'course_code' => 'MATH111',
                    'course_name' => 'Giải tích hàm một biến(MATH111)',
                    'date' => 'Thứ 2, ngày 4/8/2025',
                    'start_time' => '9:45'
                ]
            ]
        ];
    }

    // Attendances - updated to match real attendance data
    public function createAttendance($data)
    {
        return ['id' => 'mock_attendance_id', 'message' => 'Attendance recorded successfully'];
    }

    public function getAllAttendances()
    {
        return [
            [
                'id' => 'att1',
                'student_id' => '2151860244',
                'student_name' => 'Đặng Hải Sơn',
                'class_id' => '63CNTT.VA',
                'course_code' => 'CSE205',
                'course_name' => 'Cấu trúc dữ liệu',
                'date' => '2025-08-22',
                'time' => '07:00',
                'status' => 'present',
                'classroom' => '301-A2'
            ],
            [
                'id' => 'att2',
                'student_id' => '2151862753',
                'student_name' => 'Nguyễn Văn An',
                'class_id' => '63CNTT.NB',
                'course_code' => 'MATH111',
                'course_name' => 'Giải tích hàm một biến',
                'date' => '2025-08-22',
                'time' => '09:45',
                'status' => 'absent',
                'classroom' => '208-B5'
            ]
        ];
    }

    public function getAttendance($id)
    {
        return [
            'id' => $id,
            'student_id' => '2151860244',
            'student_name' => 'Đặng Hải Sơn',
            'class_id' => '63CNTT.VA',
            'course_code' => 'CSE205',
            'course_name' => 'Cấu trúc dữ liệu',
            'date' => '2025-08-22',
            'time' => '07:00',
            'status' => 'present',
            'classroom' => '301-A2'
        ];
    }

    public function getAttendancesByClass($classId)
    {
        return [
            [
                'id' => 'att1',
                'student_id' => '2151860244',
                'student_name' => 'Đặng Hải Sơn',
                'class_id' => $classId,
                'course_code' => 'CSE205',
                'status' => 'present'
            ]
        ];
    }

    public function getAttendancesByStudent($studentId)
    {
        return [
            [
                'id' => 'att1',
                'student_id' => $studentId,
                'course_code' => 'CSE205',
                'course_name' => 'Cấu trúc dữ liệu',
                'date' => '2025-08-22',
                'status' => 'present'
            ]
        ];
    }

    public function updateAttendance($id, $data)
    {
        return ['message' => 'Attendance updated successfully'];
    }

    public function deleteAttendance($id)
    {
        return ['message' => 'Attendance deleted successfully'];
    }

    // Notifications (Realtime Database) - new method
    public function getNotifications()
    {
        return [
            [
                'id' => 'notif_1',
                'message' => 'Thần gửi các em sinh viên, khoa gửi các em thông tin về cuộc thi hùng biện tiếng Nhật lần thứ 4(chi tiết trong file đính kèm). Các em sinh viên yêu thích',
                'title' => 'Văn phòng khoa CNTT',
                'timestamp' => '2025-08-21 22:30:36'
            ]
        ];
    }

    // Face recognition methods
    public function storeFaceData($userId, $faceData)
    {
        return true;
    }
}

<?php

namespace App\Services;

use Google\Cloud\Firestore\FirestoreClient;

class FirebaseService
{
    private $firestore;

    public function __construct()
    {
        $this->firestore = null;
        
        try {
            $credentialsPath = app_path('firebase/facerecognitionapp-f034d-firebase-adminsdk-fbsvc-fe97396fa1.json');
            
            if (!file_exists($credentialsPath)) {
                \Log::warning('Firebase credentials file not found');
                return;
            }
            
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
            
            $this->firestore = new FirestoreClient([
                'projectId' => 'facerecognitionapp-f034d',
                'keyFilePath' => $credentialsPath,
                'transport' => 'rest',
                'requestTimeout' => 30.0,
                'retries' => 1
            ]);
            
            \Log::info('🔥 Firebase Firestore initialized successfully');
            
        } catch (\Exception $e) {
            \Log::error('Firebase setup failed: ' . $e->getMessage());
            $this->firestore = null;
        }
    }

    public function getAllUsers()
    {
        if ($this->firestore) {
            try {
                $usersCollection = $this->firestore->collection('users')->documents();
                $users = [];
                
                foreach ($usersCollection as $user) {
                    if ($user->exists()) {
                        $userData = $user->data();
                        $userData['id'] = $user->id();
                        $users[] = $userData;
                    }
                }
                
                \Log::info('🔥 Loaded ' . count($users) . ' users from Firebase');
                
                if (!empty($users)) {
                    return $users;
                }
            } catch (\Exception $e) {
                \Log::error('Firebase error: ' . $e->getMessage());
            }
        }
        
        return [
            [
                'id' => '3bcHQ3MTnBWDoasnkUNG8WNbchM2',
                'name' => 'Lê Hoàn',
                'email' => 'lehoancdoc@gmail.com',
                'role' => 'student',
                'created_at' => now(),
            ]
        ];
    }

    public function getAllStudents()
    {
        if ($this->firestore) {
            try {
                $studentsCollection = $this->firestore->collection('students')->documents();
                $students = [];
                
                foreach ($studentsCollection as $student) {
                    if ($student->exists()) {
                        $data = $student->data();
                        $students[] = [
                            'id' => $student->id(),
                            'student_id' => $student->id(),
                            'name' => $data['name'] ?? 'Không có tên',
                            'class_id' => $data['class_id'] ?? 'Không có lớp',
                            'course_enrollments' => $data['course_enrollments'] ?? 'Không có môn học',
                            'date_of_birth' => $data['date_of_birth'] ?? 'Không có ngày sinh',
                            'field_of_study' => $data['field_of_study'] ?? 'Không có chuyên ngành',
                            'hometown' => $data['hometown'] ?? 'Không có quê quán',
                            'created_at' => isset($data['created_at']) ? $data['created_at'] : now(),
                        ];
                    }
                }
                
                \Log::info('🔥 Loaded ' . count($students) . ' students from Firebase');
                
                if (!empty($students)) {
                    return $students;
                }
            } catch (\Exception $e) {
                \Log::error('Firebase error: ' . $e->getMessage());
            }
        }
        
        return [
            [
                'id' => '2151060244',
                'student_id' => '2151060244',
                'name' => 'Đặng Hải Sơn',
                'class_id' => '63CNTT.VA',
                'course_enrollments' => 'CSE205, CSE204, MATH333',
                'date_of_birth' => '02/04/2003',
                'field_of_study' => 'Công nghệ thông tin',
                'hometown' => 'Hà Nội',
                'created_at' => now(),
            ],
            [
                'id' => '2151060245',
                'student_id' => '2151060245',
                'name' => 'Lê Thị Mai',
                'class_id' => '63CNTT.VA',
                'course_enrollments' => 'CSE205, CSE204, MATH333',
                'date_of_birth' => '15/08/2003',
                'field_of_study' => 'Công nghệ thông tin',
                'hometown' => 'Hồ Chí Minh',
                'created_at' => now(),
            ]
        ];
    }

    public function getAllSubjects()
    {
        if ($this->firestore) {
            try {
                $subjectsCollection = $this->firestore->collection('courses')->documents();
                $subjects = [];
                
                foreach ($subjectsCollection as $subject) {
                    if ($subject->exists()) {
                        $data = $subject->data();
                        $subjects[] = [
                            'id' => $subject->id(),
                            'name' => $data['course_name'] ?? $data['name'] ?? 'Không có tên',
                            'code' => $data['course_code'] ?? $data['code'] ?? 'Không có mã',
                            'credits' => $data['credits'] ?? 3,
                            'description' => $data['description'] ?? 'Không có mô tả',
                            'created_at' => isset($data['created_at']) ? $data['created_at'] : now(),
                        ];
                    }
                }
                
                \Log::info('🔥 Loaded ' . count($subjects) . ' subjects from Firebase');
                
                if (!empty($subjects)) {
                    return $subjects;
                }
            } catch (\Exception $e) {
                \Log::error('Firebase error: ' . $e->getMessage());
            }
        }
        
        return [
            [
                'id' => 'LP_WEB',
                'name' => 'Lập trình Web',
                'code' => 'IT4552',
                'credits' => 3,
                'description' => 'Môn học về phát triển ứng dụng web',
                'created_at' => now(),
            ],
            [
                'id' => 'CSDL',
                'name' => 'Cơ sở dữ liệu',
                'code' => 'IT3080',
                'credits' => 3,
                'description' => 'Môn học về thiết kế và quản lý cơ sở dữ liệu',
                'created_at' => now(),
            ]
        ];
    }

    public function getAllClassRooms()
    {
        if ($this->firestore) {
            try {
                $classesCollection = $this->firestore->collection('classes')->documents();
                $classrooms = [];
                
                foreach ($classesCollection as $class) {
                    if ($class->exists()) {
                        $data = $class->data();
                        $classrooms[] = [
                            'id' => $class->id(),
                            'name' => $data['class_name'] ?? $data['name'] ?? 'Không có tên',
                            'subject' => $data['subject'] ?? 'Không có môn học',
                            'teacher' => $data['teacher'] ?? $data['teacher_id'] ?? 'Không có giáo viên',
                            'room' => $data['room'] ?? 'Không có phòng',
                            'schedule' => $data['schedule'] ?? 'Không có lịch học',
                            'students_count' => isset($data['student_ids']) ? count($data['student_ids']) : 0,
                            'created_at' => isset($data['created_at']) ? $data['created_at'] : now(),
                        ];
                    }
                }
                
                \Log::info('🔥 Loaded ' . count($classrooms) . ' classrooms from Firebase');
                
                if (!empty($classrooms)) {
                    return $classrooms;
                }
                
            } catch (\Exception $e) {
                \Log::error('Firebase error: ' . $e->getMessage());
            }
        }
        
        return [
            [
                'id' => '63CNTT.NB',
                'name' => 'K63 Công nghệ thông tin Việt-Nhật',
                'subject' => 'Công nghệ thông tin',
                'teacher' => 'TS. Nguyễn Văn A',
                'room' => 'B301',
                'schedule' => 'Thứ 2, 4, 6 - 7:00-9:25',
                'students_count' => 35,
                'created_at' => now(),
            ],
            [
                'id' => '63CNTT.VA',
                'name' => 'K63 Công nghệ thông tin Việt-Anh',
                'subject' => 'Công nghệ thông tin',
                'teacher' => 'TS. Trần Thị B',
                'room' => 'B302',
                'schedule' => 'Thứ 3, 5, 7 - 9:30-11:55',
                'students_count' => 40,
                'created_at' => now(),
            ]
        ];
    }

    public function getUserByEmail($email)
    {
        if ($this->firestore) {
            try {
                $usersQuery = $this->firestore->collection('users')
                    ->where('email', '=', $email)
                    ->documents();
                
                foreach ($usersQuery as $user) {
                    $userData = $user->data();
                    $userData['id'] = $user->id();
                    return $userData;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to get user by email from Firebase: ' . $e->getMessage());
            }
        }
        
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

    public function testConnection()
    {
        if ($this->firestore) {
            try {
                $testQuery = $this->firestore->collection('users')->limit(1)->documents();
                
                $count = 0;
                foreach ($testQuery as $doc) {
                    $count++;
                }
                
                return [
                    'status' => 'success',
                    'message' => '🔥 Firebase connection successful!',
                    'documents_found' => $count
                ];
            } catch (\Exception $e) {
                return [
                    'status' => 'error',
                    'message' => 'Firebase connection failed: ' . $e->getMessage()
                ];
            }
        }
        
        return [
            'status' => 'error',
            'message' => 'Firebase not initialized'
        ];
    }
    
    public function getAllSchedules()
    {
        if ($this->firestore) {
            try {
                $schedulesCollection = $this->firestore->collection('schedules')->documents();
                $schedules = [];
                
                foreach ($schedulesCollection as $schedule) {
                    if ($schedule->exists()) {
                        $scheduleData = $schedule->data();
                        $scheduleData['id'] = $schedule->id();
                        $schedules[] = $scheduleData;
                    }
                }
                
                \Log::info('🔥 Firebase: Retrieved ' . count($schedules) . ' schedules');
                return $schedules;
                
            } catch (\Exception $e) {
                \Log::error('Firebase getAllSchedules failed: ' . $e->getMessage());
                return $this->getMockSchedules();
            }
        }
        
        return $this->getMockSchedules();
    }
    
    public function getAllClasses()
    {
        if ($this->firestore) {
            try {
                $classesCollection = $this->firestore->collection('classes')->documents();
                $classes = [];
                
                foreach ($classesCollection as $class) {
                    if ($class->exists()) {
                        $classData = $class->data();
                        $classData['id'] = $class->id();
                        $classes[] = $classData;
                    }
                }
                
                \Log::info('🔥 Firebase: Retrieved ' . count($classes) . ' classes');
                return $classes;
                
            } catch (\Exception $e) {
                \Log::error('Firebase getAllClasses failed: ' . $e->getMessage());
                return $this->getMockClasses();
            }
        }
        
        return $this->getMockClasses();
    }
    
    public function getAllAttendances()
    {
        if ($this->firestore) {
            try {
                $attendancesCollection = $this->firestore->collection('attendances')->documents();
                $attendances = [];
                
                foreach ($attendancesCollection as $attendance) {
                    if ($attendance->exists()) {
                        $attendanceData = $attendance->data();
                        $attendanceData['id'] = $attendance->id();
                        $attendances[] = $attendanceData;
                    }
                }
                
                \Log::info('🔥 Firebase: Retrieved ' . count($attendances) . ' attendances');
                return $attendances;
                
            } catch (\Exception $e) {
                \Log::error('Firebase getAllAttendances failed: ' . $e->getMessage());
                return $this->getMockAttendances();
            }
        }
        
        return $this->getMockAttendances();
    }
    
    private function getMockClasses()
    {
        return [
            ['id' => 'class1', 'name' => 'Lớp 12A1', 'course_id' => 'course1'],
            ['id' => 'class2', 'name' => 'Lớp 12A2', 'course_id' => 'course2'],
            ['id' => 'class3', 'name' => 'Lớp 11B1', 'course_id' => 'course1'],
            ['id' => 'class4', 'name' => 'Lớp 11B2', 'course_id' => 'course3'],
        ];
    }
    
    private function getMockAttendances()
    {
        return [
            ['id' => 'att1', 'student_id' => '2151060244', 'class_id' => '63CNTLVA', 'date' => '2025-08-23', 'status' => 'present', 'check_in_time' => '08:30:00'],
            ['id' => 'att2', 'student_id' => '2151062753', 'class_id' => '63CNTLVA', 'date' => '2025-08-23', 'status' => 'present', 'check_in_time' => '08:32:00'],
            ['id' => 'att3', 'student_id' => '2151062754', 'class_id' => '63CNTLVA', 'date' => '2025-08-23', 'status' => 'late', 'check_in_time' => '08:45:00'],
            ['id' => 'att4', 'student_id' => '2151062755', 'class_id' => '63CNTLVA', 'date' => '2025-08-23', 'status' => 'absent', 'check_in_time' => null],
        ];
    }
    
    private function getMockSchedules()
    {
        return [
            [
                'id' => 'sch1',
                'class_id' => '63CNTLVA',
                'subject_id' => 'CSE205',
                'date' => '2025-08-23',
                'start_time' => '08:30:00',
                'end_time' => '10:30:00',
                'room' => 'P301',
                'instructor' => 'TS. Nguyễn Văn A'
            ],
            [
                'id' => 'sch2',
                'class_id' => '63CNTLVA',
                'subject_id' => 'CSE204',
                'date' => '2025-08-24',
                'start_time' => '10:30:00',
                'end_time' => '12:30:00',
                'room' => 'P302',
                'instructor' => 'TS. Trần Thị B'
            ],
            [
                'id' => 'sch3',
                'class_id' => '63CNTLVA',
                'subject_id' => 'MATH333',
                'date' => '2025-08-25',
                'start_time' => '13:30:00',
                'end_time' => '15:30:00',
                'room' => 'P303',
                'instructor' => 'PGS.TS. Lê Văn C'
            ]
        ];
    }
}
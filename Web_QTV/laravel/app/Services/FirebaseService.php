<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $projectId;
    protected $baseUrl;
    protected $serviceAccountData;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
        
        // Load service account for authentication
        $serviceAccountPath = storage_path('app/firebase-service-account.json');
        if (file_exists($serviceAccountPath)) {
            $this->serviceAccountData = json_decode(file_get_contents($serviceAccountPath), true);
        }
    }

    // Courses Management (Quản lý môn học)
    public function getCourses()
    {
        $courses = [];
        try {
            $url = "{$this->baseUrl}/courses";
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $document) {
                        $docData = $this->parseFirestoreDocument($document);
                        $courses[] = [
                            'id' => $docData['id'],
                            'code' => $docData['course_code'] ?? '',
                            'name' => $docData['course_name'] ?? '',
                            'teacher' => $docData['teacher_name'] ?? '',
                            'credits' => $docData['credit'] ?? 3, // Sử dụng field credit thực từ Firebase
                            'department' => $this->getDepartmentFromCourseCode($docData['course_code'] ?? ''),
                            'status' => 'active'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Firebase getCourses error: ' . $e->getMessage());
        }
        
        return $courses;
    }

    public function createCourse($data)
    {
        try {
            $url = "{$this->baseUrl}/courses";
            $courseData = [
                'fields' => [
                    'course_code' => ['stringValue' => $data['code']],
                    'course_name' => ['stringValue' => $data['name']],
                    'teacher_name' => ['stringValue' => $data['teacher'] ?? ''],
                    'created_at' => ['stringValue' => now()->toISOString()]
                ]
            ];
            
            $response = Http::post($url, $courseData);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Firebase createCourse error: ' . $e->getMessage());
            return false;
        }
    }

    // Classes Management (Quản lý lớp học phần)
    public function getClasses()
    {
        $classes = [];
        try {
            $url = "{$this->baseUrl}/classes";
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $document) {
                        $docData = $this->parseFirestoreDocument($document);
                        $studentIds = $docData['student_ids'] ?? [];
                        $classes[] = [
                            'id' => $docData['id'],
                            'code' => $docData['id'], // Sử dụng document ID làm mã lớp
                            'subject' => $docData['class_name'] ?? '', // Đúng field name từ Firebase
                            'instructor' => $this->getTeacherName($docData['teacher_id'] ?? ''),
                            'room' => 'TBA', // Chưa có field này trong Firebase
                            'schedule' => 'Chưa xếp lịch', // Chưa có field này trong Firebase  
                            'students' => is_array($studentIds) ? count($studentIds) : 0,
                            'max_students' => 50, // Default value
                            'status' => 'active' // Default status
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Firebase getClasses error: ' . $e->getMessage());
        }
        
        return $classes;
    }

    // Students Management
    public function getStudents()
    {
        $students = [];
        try {
            $url = "{$this->baseUrl}/students";
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $document) {
                        $docData = $this->parseFirestoreDocument($document);
                        $students[] = [
                            'id' => $docData['id'],
                            'name' => $docData['name'] ?? '',
                            'email' => $docData['id'] . '@htd.edu.vn',
                            'class_id' => $docData['class_id'] ?? '',
                            'field_of_study' => $docData['field_of_study'] ?? '',
                            'hometown' => $docData['hometown'] ?? '',
                            'date_of_birth' => $docData['date_of_birth'] ?? '',
                            'role' => 'student',
                            'status' => 'active'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Firebase getStudents error: ' . $e->getMessage());
        }
        
        return $students;
    }

    // Users Management
    public function getUsers()
    {
        $users = [];
        try {
            $url = "{$this->baseUrl}/users";
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $document) {
                        $docData = $this->parseFirestoreDocument($document);
                        $users[] = [
                            'id' => $docData['id'],
                            'name' => $docData['name'] ?? '',
                            'email' => $docData['email'] ?? '',
                            'role' => 'admin',
                            'status' => 'active'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Firebase getUsers error: ' . $e->getMessage());
        }
        
        return $users;
    }

    // Schedules Management
    public function getSchedules()
    {
        $schedules = [];
        try {
            $url = "{$this->baseUrl}/schedules";
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $document) {
                        $docData = $this->parseFirestoreDocument($document);
                        
                        // Handle nested schedule_sessions array
                        $scheduleSessions = $docData['schedule_sessions'] ?? [];
                        
                        // Create a schedule entry for each session in the array
                        if (is_array($scheduleSessions) && !empty($scheduleSessions)) {
                            foreach ($scheduleSessions as $index => $session) {
                                $schedules[] = [
                                    'id' => $docData['id'] . '_' . $index,
                                    'course_code' => $session['course_code'] ?? '',
                                    'course_name' => $session['course_name'] ?? '',
                                    'class_id' => $session['class_id'] ?? '',
                                    'classroom' => $session['classroom'] ?? '',
                                    'date' => $session['date'] ?? '',
                                    'start_time' => $session['start_time'] ?? '',
                                    'schedule_sessions' => [$session] // Wrap single session in array
                                ];
                            }
                        } else {
                            // Fallback for documents without schedule_sessions
                            $schedules[] = [
                                'id' => $docData['id'],
                                'course_code' => $docData['course_code'] ?? '',
                                'course_name' => $docData['course_name'] ?? '',
                                'class_id' => $docData['class_id'] ?? '',
                                'classroom' => $docData['classroom'] ?? '',
                                'date' => $docData['date'] ?? '',
                                'start_time' => $docData['start_time'] ?? '',
                                'schedule_sessions' => []
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Firebase getSchedules error: ' . $e->getMessage());
        }
        
        return $schedules;
    }

    // Helper method to parse Firestore document
    private function parseFirestoreDocument($document)
    {
        $data = ['id' => basename($document['name'])];
        
        if (isset($document['fields'])) {
            foreach ($document['fields'] as $key => $value) {
                if (isset($value['stringValue'])) {
                    $data[$key] = $value['stringValue'];
                } elseif (isset($value['integerValue'])) {
                    $data[$key] = (int)$value['integerValue'];
                } elseif (isset($value['booleanValue'])) {
                    $data[$key] = $value['booleanValue'];
                } elseif (isset($value['arrayValue']['values'])) {
                    // Handle array of objects (like schedule_sessions)
                    $arrayData = [];
                    foreach ($value['arrayValue']['values'] as $arrayItem) {
                        if (isset($arrayItem['mapValue']['fields'])) {
                            // Parse nested object
                            $nestedData = [];
                            foreach ($arrayItem['mapValue']['fields'] as $nestedKey => $nestedValue) {
                                if (isset($nestedValue['stringValue'])) {
                                    $nestedData[$nestedKey] = $nestedValue['stringValue'];
                                } elseif (isset($nestedValue['integerValue'])) {
                                    $nestedData[$nestedKey] = (int)$nestedValue['integerValue'];
                                } elseif (isset($nestedValue['booleanValue'])) {
                                    $nestedData[$nestedKey] = $nestedValue['booleanValue'];
                                }
                            }
                            $arrayData[] = $nestedData;
                        } elseif (isset($arrayItem['stringValue'])) {
                            // Simple string array
                            $arrayData[] = $arrayItem['stringValue'];
                        }
                    }
                    $data[$key] = $arrayData;
                }
            }
        }
        
        return $data;
    }

    // Helper methods
    private function getDepartmentFromCourseCode($courseCode)
    {
        $code = strtoupper($courseCode);
        
        if (strpos($code, 'CSE') === 0 || strpos($code, 'CS') === 0 || strpos($code, 'IT') === 0) {
            return 'Khoa CNTT';
        } elseif (strpos($code, 'MATH') === 0 || strpos($code, 'MT') === 0) {
            return 'Khoa Toán';
        } elseif (strpos($code, 'ENG') === 0 || strpos($code, 'EN') === 0) {
            return 'Khoa Ngoại ngữ';
        } elseif (strpos($code, 'PHYS') === 0 || strpos($code, 'PH') === 0) {
            return 'Khoa Vật lý';
        } elseif (strpos($code, 'HYDRO') === 0 || strpos($code, 'WR') === 0) {
            return 'Khoa Thủy lợi';
        } elseif (strpos($code, 'MECH') === 0 || strpos($code, 'ME') === 0) {
            return 'Khoa Cơ khí';
        } elseif (strpos($code, 'ECON') === 0 || strpos($code, 'EC') === 0) {
            return 'Khoa Kinh tế';
        } elseif (strpos($code, 'ENV') === 0 || strpos($code, 'EN') === 0) {
            return 'Khoa Môi trường';
        } else {
            return 'Khoa Khác';
        }
    }

    private function getCreditsFromCourseCode($courseCode)
    {
        $credits = [2, 3, 4, 5];
        return $credits[array_rand($credits)];
    }

    private function getTeacherName($teacherId)
    {
        if (empty($teacherId)) {
            return 'Chưa phân công';
        }
        return 'GV.' . $teacherId;
    }
}

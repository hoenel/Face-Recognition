<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ClassRoomController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->middleware('auth');
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        try {
            $classRoomsCollection = $this->firebaseService->getAllClassRooms();
            $classRooms = [];
            
            foreach ($classRoomsCollection as $classRoom) {
                $classRoomData = $classRoom->data();
                $classRoomData['id'] = $classRoom->id();
                $classRooms[] = $classRoomData;
            }
            
            return view('classrooms.index', compact('classRooms'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải danh sách lớp học: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Lấy danh sách môn học và giáo viên
            $subjectsCollection = $this->firebaseService->getAllSubjects();
            $subjects = [];
            foreach ($subjectsCollection as $subject) {
                $subjectData = $subject->data();
                $subjectData['id'] = $subject->id();
                $subjects[] = $subjectData;
            }

            $teachersCollection = $this->firebaseService->getFirestore()
                ->collection('users')
                ->where('role', '=', 'teacher')
                ->documents();
            $teachers = [];
            foreach ($teachersCollection as $teacher) {
                $teacherData = $teacher->data();
                $teacherData['id'] = $teacher->id();
                $teachers[] = $teacherData;
            }
            
            return view('classrooms.create', compact('subjects', 'teachers'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|string',
            'teacher_id' => 'required|string',
            'semester' => 'required|string',
            'year' => 'required|integer|min:2000|max:2100',
            'schedule' => 'required|array',
        ]);

        try {
            $classRoomData = [
                'name' => $request->name,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'semester' => $request->semester,
                'year' => $request->year,
                'schedule' => $request->schedule,
                'students' => [],
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ];

            $this->firebaseService->createClassRoom($classRoomData);

            return redirect()->route('classrooms.index')->with('success', 'Lớp học đã được tạo thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tạo lớp học: ' . $e->getMessage());
        }
    }

    public function show($classId)
    {
        try {
            $classRoomDoc = $this->firebaseService->getClassRoom($classId);
            if (!$classRoomDoc->exists()) {
                return redirect()->route('classrooms.index')->with('error', 'Không tìm thấy lớp học!');
            }
            
            $classRoom = $classRoomDoc->data();
            $classRoom['id'] = $classRoomDoc->id();
            
            // Lấy thông tin môn học và giáo viên
            $subjectDoc = $this->firebaseService->getSubject($classRoom['subject_id']);
            $subject = $subjectDoc->exists() ? $subjectDoc->data() : null;
            
            $teacherDoc = $this->firebaseService->getUser($classRoom['teacher_id']);
            $teacher = $teacherDoc->exists() ? $teacherDoc->data() : null;
            
            return view('classrooms.show', compact('classRoom', 'subject', 'teacher'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải thông tin lớp học: ' . $e->getMessage());
        }
    }

    public function edit($classId)
    {
        try {
            $classRoomDoc = $this->firebaseService->getClassRoom($classId);
            if (!$classRoomDoc->exists()) {
                return redirect()->route('classrooms.index')->with('error', 'Không tìm thấy lớp học!');
            }
            
            $classRoom = $classRoomDoc->data();
            $classRoom['id'] = $classRoomDoc->id();
            
            // Lấy danh sách môn học và giáo viên
            $subjectsCollection = $this->firebaseService->getAllSubjects();
            $subjects = [];
            foreach ($subjectsCollection as $subject) {
                $subjectData = $subject->data();
                $subjectData['id'] = $subject->id();
                $subjects[] = $subjectData;
            }

            $teachersCollection = $this->firebaseService->getFirestore()
                ->collection('users')
                ->where('role', '=', 'teacher')
                ->documents();
            $teachers = [];
            foreach ($teachersCollection as $teacher) {
                $teacherData = $teacher->data();
                $teacherData['id'] = $teacher->id();
                $teachers[] = $teacherData;
            }
            
            return view('classrooms.edit', compact('classRoom', 'subjects', 'teachers'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải thông tin lớp học: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $classId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|string',
            'teacher_id' => 'required|string',
            'semester' => 'required|string',
            'year' => 'required|integer|min:2000|max:2100',
            'schedule' => 'required|array',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'semester' => $request->semester,
                'year' => $request->year,
                'schedule' => $request->schedule,
                'updated_at' => new \DateTime(),
            ];

            $this->firebaseService->updateClassRoom($classId, $updateData);

            return redirect()->route('classrooms.index')->with('success', 'Lớp học đã được cập nhật!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể cập nhật lớp học: ' . $e->getMessage());
        }
    }

    public function destroy($classId)
    {
        try {
            $this->firebaseService->deleteClassRoom($classId);
            return redirect()->route('classrooms.index')->with('success', 'Lớp học đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa lớp học: ' . $e->getMessage());
        }
    }
}

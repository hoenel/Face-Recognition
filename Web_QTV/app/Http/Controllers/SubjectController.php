<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class SubjectController extends Controller
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
            $subjectsCollection = $this->firebaseService->getAllSubjects();
            $subjects = [];
            
            foreach ($subjectsCollection as $subject) {
                $subjectData = $subject->data();
                $subjectData['id'] = $subject->id();
                $subjects[] = $subjectData;
            }
            
            return view('subjects.index', compact('subjects'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải danh sách môn học: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'credits' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
        ]);

        try {
            $subjectData = [
                'name' => $request->name,
                'code' => $request->code,
                'credits' => $request->credits,
                'description' => $request->description,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ];

            $this->firebaseService->createSubject($subjectData);

            return redirect()->route('subjects.index')->with('success', 'Môn học đã được tạo thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tạo môn học: ' . $e->getMessage());
        }
    }

    public function edit($subjectId)
    {
        try {
            $subjectDoc = $this->firebaseService->getSubject($subjectId);
            if (!$subjectDoc->exists()) {
                return redirect()->route('subjects.index')->with('error', 'Không tìm thấy môn học!');
            }
            
            $subject = $subjectDoc->data();
            $subject['id'] = $subjectDoc->id();
            
            return view('subjects.edit', compact('subject'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải thông tin môn học: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $subjectId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'credits' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'code' => $request->code,
                'credits' => $request->credits,
                'description' => $request->description,
                'updated_at' => new \DateTime(),
            ];

            $this->firebaseService->updateSubject($subjectId, $updateData);

            return redirect()->route('subjects.index')->with('success', 'Môn học đã được cập nhật!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể cập nhật môn học: ' . $e->getMessage());
        }
    }

    public function destroy($subjectId)
    {
        try {
            $this->firebaseService->deleteSubject($subjectId);
            return redirect()->route('subjects.index')->with('success', 'Môn học đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa môn học: ' . $e->getMessage());
        }
    }
}

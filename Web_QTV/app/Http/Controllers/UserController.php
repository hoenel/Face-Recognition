<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class UserController extends Controller
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
            $usersCollection = $this->firebaseService->getAllUsers();
            $users = [];
            
            foreach ($usersCollection as $user) {
                $userData = $user->data();
                $userData['id'] = $user->id();
                $users[] = $userData;
            }
            
            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải danh sách người dùng: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student',
            'student_id' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'student_id' => $request->student_id,
                'phone' => $request->phone,
                'face_data' => null,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ];

            $this->firebaseService->createUser($userData);

            return redirect()->route('users.index')->with('success', 'Tài khoản đã được tạo thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tạo tài khoản: ' . $e->getMessage());
        }
    }

    public function edit($userId)
    {
        try {
            $userDoc = $this->firebaseService->getUser($userId);
            if (!$userDoc->exists()) {
                return redirect()->route('users.index')->with('error', 'Không tìm thấy người dùng!');
            }
            
            $user = $userDoc->data();
            $user['id'] = $userDoc->id();
            
            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải thông tin người dùng: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $userId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|in:admin,teacher,student',
            'student_id' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'student_id' => $request->student_id,
                'phone' => $request->phone,
                'updated_at' => new \DateTime(),
            ];

            $this->firebaseService->updateUser($userId, $updateData);

            return redirect()->route('users.index')->with('success', 'Tài khoản đã được cập nhật!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể cập nhật tài khoản: ' . $e->getMessage());
        }
    }

    public function destroy($userId)
    {
        try {
            $this->firebaseService->deleteUser($userId);
            return redirect()->route('users.index')->with('success', 'Tài khoản đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa tài khoản: ' . $e->getMessage());
        }
    }
}

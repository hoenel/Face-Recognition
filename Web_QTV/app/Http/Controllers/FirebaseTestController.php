<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;

class FirebaseTestController extends Controller
{
    protected $firestore;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firestore = $firebaseService->getFirestore();
    }

    public function addUser()
    {
        $collection = $this->firestore->collection('users');
        $docRef = $collection->add([
            'name' => 'Hoàn Lê',
            'email' => 'hoanleculoc@gmail.com',
            'created_at' => now(),
        ]);

        return "User created with ID: " . $docRef->id();
    }

    public function getUsers()
    {
        $users = $this->firestore->collection('users')->documents();
        $data = [];
        foreach ($users as $user) {
            $data[] = $user->data();
        }

        return response()->json($data);
    }
}

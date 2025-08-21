<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseTestController;

Route::get('/firestore/add', [FirebaseTestController::class, 'addUser']);
Route::get('/firestore/get', [FirebaseTestController::class, 'getUsers']);

Route::get('/', function () {
    return view('welcome');
});

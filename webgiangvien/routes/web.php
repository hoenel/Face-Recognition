<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dangnhap', function () {
    return view('dangnhap');
});

Route::get('/tongquan', function () {
    return view('tongquan');
});
Route::get('/lich', function () {
    return view('lich');
});
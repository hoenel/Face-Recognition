<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        return view('dashboard.index', compact('user'));
    }

    public function profile()
    {
        $user = Session::get('user');
        return view('dashboard.profile', compact('user'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.superadmin.dashboard', compact('users'));
    }
}
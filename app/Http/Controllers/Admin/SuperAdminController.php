<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SuperAdminController extends Controller
{
    public function index()
    {
        return view('admin.superadmin.dashboard');
    }
}
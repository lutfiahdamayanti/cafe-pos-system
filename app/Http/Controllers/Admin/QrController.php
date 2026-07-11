<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class QrController extends Controller
{
    public function index()
    {
        $tables = [
            'A01',
            'A02',
            'A03',
            'A04',
            'A05',
            'A06',
            'A07',
            'A08'
        ];

        return view('admin.qr.index', compact('tables'));
    }
}
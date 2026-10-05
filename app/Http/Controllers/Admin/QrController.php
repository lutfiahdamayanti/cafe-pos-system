<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrTable;
use Illuminate\Http\Request;
class QrController extends Controller
{
    public function index()
    {
        $tables = QrTable::orderBy('table_number')->get();
        return view('admin.qr.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:50|unique:qr_tables,table_number',
        ]);
        QrTable::create([
            'table_number' => $request->table_number,
        ]);
        return redirect()
            ->route('admin.qr.index')
            ->with('success', 'QR meja berhasil dibuat.');
    }

    public function print()
    {
        $tables = QrTable::orderBy('table_number')->get();
        return view('admin.qr.print', compact('tables'));
    }

    public function destroy(QrTable $qrTable)
    {
        $qrTable->delete();
        return redirect()
            ->route('admin.qr.index')
            ->with('success', 'QR meja berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
// use App\Models\Order;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::latest()->paginate(10);

        return view('admin.audit.index', compact('logs'));
    }

    public function backup()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host     = env('DB_HOST');
        $port     = env('DB_PORT');

        $filename = 'backup_' . now()->format('Ymd_His') . '.sql';
        $path = storage_path('app\\' . $filename);

        $mysqldump = 'D:\APLIKASI\xampp\\mysql\\bin\\mysqldump.exe';

        $command = "\"$mysqldump\" "
            . "--host=$host "
            . "--port=$port "
            . "--user=$username ";

        if ($password != '') {
            $command .= "--password=$password ";
        }

        $command .= "--result-file=\"$path\" $database";

        exec($command, $output, $result);

        if ($result == 0 && file_exists($path)) {
            return response()->download($path)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Backup database gagal.');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'database' => 'required|mimes:sql'
        ]);

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $port = env('DB_PORT');

        $mysql = 'C:\\xampp\\mysql\\bin\\mysql.exe';

        $file = $request->file('database');
        $path = $file->getRealPath();

        $command = "\"$mysql\" -h$host -P$port -u$username";

        if ($password != '') {
            $command .= " -p$password";
        }

        $command .= " $database < \"$path\"";

        exec($command, $output, $result);

        if ($result == 0) {
            return redirect()->back()
                ->with('success', 'Database berhasil direstore.');
        }

        return redirect()->back()
            ->with('error', 'Restore database gagal.');
    }
}
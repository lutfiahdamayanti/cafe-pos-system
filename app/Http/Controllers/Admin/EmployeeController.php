<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeKpi;
use App\Models\EmployeeSchedule;
use App\Models\LeaveRequest;
use App\Models\Outlet;
use App\Models\Payroll;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * =========================================================================
     * 1. ABSENSI MASUK & PULANG
     * URL: /admin/hr/absensi
     * =========================================================================
     */
    public function absensi(Request $request)
    {
        $selectedDate = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();
        $dateStr = $selectedDate->toDateString();

        $query = Employee::with(['attendances' => function ($q) use ($dateStr) {
            $q->where('date', $dateStr)->with('shift');
        }, 'outlet']);

        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $employees = $query->where('status', 'active')->orderBy('name')->get();

        $allOutlets = Outlet::where('status', 'active')->orderBy('name')->get();
        $allShifts = Shift::all();

        // Metrics for selected date
        $totalEmployees = $employees->count();
        $attendedCount = 0;
        $clockedOutCount = 0;
        $lateCount = 0;
        $absentCount = 0;

        foreach ($employees as $emp) {
            $att = $emp->attendances->first();
            if ($att && $att->status === 'Hadir') {
                $attendedCount++;
                if ($att->clock_out) {
                    $clockedOutCount++;
                }
                if ($att->late_minutes > 0) {
                    $lateCount++;
                }
            } elseif ($att && in_array($att->status, ['Izin', 'Sakit', 'Cuti', 'Alpa'])) {
                $absentCount++;
            }
        }

        return view('admin.hr.absensi', compact(
            'employees',
            'selectedDate',
            'allOutlets',
            'allShifts',
            'totalEmployees',
            'attendedCount',
            'clockedOutCount',
            'lateCount',
            'absentCount'
        ));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $today = Carbon::today();
        $now = Carbon::now();

        $shift = $request->shift_id ? Shift::find($request->shift_id) : Shift::first();
        $lateMinutes = 0;

        if ($shift) {
            $shiftStartTime = Carbon::parse($today->toDateString() . ' ' . $shift->start_time);
            if ($now->greaterThan($shiftStartTime)) {
                $lateMinutes = $now->diffInMinutes($shiftStartTime);
            }
        }

        Attendance::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => $today->toDateString()],
            [
                'shift_id' => $shift?->id,
                'outlet_id' => $employee->outlet_id,
                'clock_in' => $now,
                'status' => 'Hadir',
                'late_minutes' => $lateMinutes,
                'notes' => $lateMinutes > 0 ? "Terlambat {$lateMinutes} menit" : "Tepat waktu",
            ]
        );

        return back()->with('success', "Clock-in berhasil untuk {$employee->name} pada {$now->format('H:i:s')} WIB.");
    }

    public function clockOut(Request $request, Attendance $attendance)
    {
        $now = Carbon::now();
        $attendance->update([
            'clock_out' => $now,
        ]);

        return back()->with('success', "Clock-out berhasil untuk {$attendance->employee->name} pada {$now->format('H:i:s')} WIB.");
    }

    public function storeManualAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Cuti,Alpa',
            'shift_id' => 'nullable|exists:shifts,id',
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'notes' => 'nullable|string',
        ]);

        $emp = Employee::findOrFail($request->employee_id);
        $clockIn = $request->filled('clock_in') ? Carbon::parse($request->date . ' ' . $request->clock_in) : null;
        $clockOut = $request->filled('clock_out') ? Carbon::parse($request->date . ' ' . $request->clock_out) : null;

        Attendance::updateOrCreate(
            ['employee_id' => $emp->id, 'date' => $request->date],
            [
                'shift_id' => $request->shift_id,
                'outlet_id' => $emp->outlet_id,
                'status' => $request->status,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'notes' => $request->notes,
            ]
        );

        return back()->with('success', "Data absensi {$emp->name} tanggal {$request->date} berhasil disimpan!");
    }

    /**
     * =========================================================================
     * 2. STATUS KEHADIRAN (Hadir · Izin · Sakit · Cuti · Alpa)
     * URL: /admin/hr/status-kehadiran
     * =========================================================================
     */
    public function statusKehadiran(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $query = Attendance::with(['employee.outlet', 'shift'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $attendances = $query->orderByDesc('date')->orderBy('employee_id')->get();

        // Breakdown stats
        $allMonthAttendances = Attendance::whereMonth('date', $month)->whereYear('date', $year)->get();
        $countHadir = $allMonthAttendances->where('status', 'Hadir')->count();
        $countIzin = $allMonthAttendances->where('status', 'Izin')->count();
        $countSakit = $allMonthAttendances->where('status', 'Sakit')->count();
        $countCuti = $allMonthAttendances->where('status', 'Cuti')->count();
        $countAlpa = $allMonthAttendances->where('status', 'Alpa')->count();
        $totalRecords = $allMonthAttendances->count();

        $disciplineRate = $totalRecords > 0 ? round(($countHadir / $totalRecords) * 100, 1) : 0;

        $allOutlets = Outlet::where('status', 'active')->orderBy('name')->get();
        $allEmployees = Employee::where('status', 'active')->orderBy('name')->get();

        return view('admin.hr.status_kehadiran', compact(
            'attendances',
            'month',
            'year',
            'countHadir',
            'countIzin',
            'countSakit',
            'countCuti',
            'countAlpa',
            'disciplineRate',
            'allOutlets',
            'allEmployees'
        ));
    }

    public function updateStatusKehadiran(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Cuti,Alpa',
            'notes' => 'nullable|string',
        ]);

        $attendance->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', "Status absensi berhasil diubah menjadi {$request->status}!");
    }

    /**
     * =========================================================================
     * 3. SHIFT KERJA
     * URL: /admin/hr/shift
     * =========================================================================
     */
    public function shiftKerja(Request $request)
    {
        $shifts = Shift::withCount('schedules')->get();

        $startOfWeek = $request->filled('week_start') 
            ? Carbon::parse($request->week_start)->startOfWeek() 
            : Carbon::now()->startOfWeek();
        
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        // Weekly schedule roster
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $startOfWeek->copy()->addDays($i);
        }

        $employeesQuery = Employee::with(['outlet', 'schedules' => function ($q) use ($startOfWeek, $endOfWeek) {
            $q->whereBetween('schedule_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])->with('shift');
        }])->where('status', 'active');

        if ($request->filled('outlet_id')) {
            $employeesQuery->where('outlet_id', $request->outlet_id);
        }

        $employees = $employeesQuery->orderBy('name')->get();
        $allOutlets = Outlet::where('status', 'active')->orderBy('name')->get();

        return view('admin.hr.shift_kerja', compact(
            'shifts',
            'startOfWeek',
            'endOfWeek',
            'weekDays',
            'employees',
            'allOutlets'
        ));
    }

    public function storeShift(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'color' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Shift::create($request->all());

        return back()->with('success', "Shift baru '{$request->name}' berhasil dibuat!");
    }

    public function updateShift(Request $request, Shift $shift)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'color' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $shift->update($request->all());

        return back()->with('success', "Shift '{$shift->name}' berhasil diperbarui!");
    }

    public function destroyShift(Shift $shift)
    {
        $shift->delete();
        return back()->with('success', "Shift berhasil dihapus!");
    }

    public function assignSchedule(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'schedule_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $emp = Employee::findOrFail($request->employee_id);

        EmployeeSchedule::updateOrCreate(
            ['employee_id' => $emp->id, 'schedule_date' => $request->schedule_date],
            [
                'shift_id' => $request->shift_id,
                'outlet_id' => $emp->outlet_id,
                'notes' => $request->notes,
            ]
        );

        return back()->with('success', "Jadwal shift untuk {$emp->name} tanggal {$request->schedule_date} berhasil ditetapkan!");
    }

    /**
     * =========================================================================
     * 4. GAJI & PAYROLL BULANAN
     * URL: /admin/hr/payroll
     * =========================================================================
     */
    public function payroll(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $query = Payroll::with(['employee.outlet'])
            ->where('month', $month)
            ->where('year', $year);

        if ($request->filled('outlet_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('outlet_id', $request->outlet_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->get();

        $totalExpense = $payrolls->sum('net_salary');
        $totalBase = $payrolls->sum('base_salary');
        $totalAllowanceBonus = $payrolls->sum('allowance') + $payrolls->sum('bonus') + $payrolls->sum('overtime_pay');
        $paidCount = $payrolls->where('status', 'Paid')->count();
        $totalCount = $payrolls->count();

        $allOutlets = Outlet::where('status', 'active')->orderBy('name')->get();
        $allEmployees = Employee::where('status', 'active')->get();

        return view('admin.hr.payroll', compact(
            'payrolls',
            'month',
            'year',
            'totalExpense',
            'totalBase',
            'totalAllowanceBonus',
            'paidCount',
            'totalCount',
            'allOutlets',
            'allEmployees'
        ));
    }

    public function generatePayroll(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $employees = Employee::where('status', 'active')->get();
        $count = 0;

        foreach ($employees as $emp) {
            // Hitung kehadiran di bulan tersebut
            $attendancesCount = Attendance::where('employee_id', $emp->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'Hadir')
                ->count();

            // Default allowance 24 hari atau berdasarkan kehadiran aktual jika ada data
            $workDays = $attendancesCount > 0 ? $attendancesCount : 24;
            $allowance = $emp->daily_allowance * $workDays;
            $overtime = 0;
            $bonus = 0;
            $deductions = 0;
            $netSalary = $emp->base_salary + $allowance + $overtime + $bonus - $deductions;

            $payrollCode = 'PAY-' . $year . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($emp->id, 3, '0', STR_PAD_LEFT);

            Payroll::updateOrCreate(
                ['employee_id' => $emp->id, 'month' => $month, 'year' => $year],
                [
                    'payroll_code' => $payrollCode,
                    'base_salary' => $emp->base_salary,
                    'allowance' => $allowance,
                    'overtime_pay' => $overtime,
                    'bonus' => $bonus,
                    'deductions' => $deductions,
                    'net_salary' => $netSalary,
                    'status' => 'Draft',
                    'payment_method' => 'Bank Transfer',
                    'notes' => 'Payroll periode ' . Carbon::create($year, $month, 1)->format('F Y'),
                ]
            );
            $count++;
        }

        return back()->with('success', "Payroll {$count} karyawan untuk periode " . Carbon::create($year, $month, 1)->format('F Y') . " berhasil digenerate!");
    }

    public function updatePayrollStatus(Request $request, Payroll $payroll)
    {
        $request->validate([
            'status' => 'required|in:Draft,Approved,Paid',
        ]);

        $update = ['status' => $request->status];
        if ($request->status === 'Paid') {
            $update['paid_at'] = now();
        }

        $payroll->update($update);

        return back()->with('success', "Status payroll #{$payroll->payroll_code} berhasil diperbarui menjadi '{$request->status}'!");
    }

    /**
     * =========================================================================
     * 5. KPI KARYAWAN
     * URL: /admin/hr/kpi
     * =========================================================================
     */
    public function kpi(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $query = EmployeeKpi::with(['employee.outlet', 'evaluator'])
            ->where('period_month', $month)
            ->where('period_year', $year);

        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $kpis = $query->orderByDesc('final_score')->get();

        $avgScore = $kpis->avg('final_score') ?? 0;
        $countGradeA = $kpis->where('grade', 'A')->count();
        $countGradeB = $kpis->where('grade', 'B')->count();
        $countGradeCD = $kpis->whereIn('grade', ['C', 'D'])->count();
        $topPerformer = $kpis->first();

        $allEmployees = Employee::where('status', 'active')->orderBy('name')->get();

        return view('admin.hr.kpi', compact(
            'kpis',
            'month',
            'year',
            'avgScore',
            'countGradeA',
            'countGradeB',
            'countGradeCD',
            'topPerformer',
            'allEmployees'
        ));
    }

    public function storeOrUpdateKpi(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_month' => 'required|integer|between:1,12',
            'period_year' => 'required|integer',
            'attendance_score' => 'required|numeric|between:0,100',
            'service_speed_score' => 'required|numeric|between:0,100',
            'sop_compliance_score' => 'required|numeric|between:0,100',
            'teamwork_score' => 'required|numeric|between:0,100',
            'evaluation_notes' => 'nullable|string',
        ]);

        // Weighted Score: Attendance 25%, Service 25%, SOP 25%, Teamwork 25%
        $finalScore = round(
            ($request->attendance_score * 0.25) +
            ($request->service_speed_score * 0.25) +
            ($request->sop_compliance_score * 0.25) +
            ($request->teamwork_score * 0.25),
            1
        );

        $grade = match(true) {
            $finalScore >= 90 => 'A',
            $finalScore >= 80 => 'B',
            $finalScore >= 70 => 'C',
            default => 'D',
        };

        EmployeeKpi::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'period_month' => $request->period_month,
                'period_year' => $request->period_year
            ],
            [
                'attendance_score' => $request->attendance_score,
                'service_speed_score' => $request->service_speed_score,
                'sop_compliance_score' => $request->sop_compliance_score,
                'teamwork_score' => $request->teamwork_score,
                'final_score' => $finalScore,
                'grade' => $grade,
                'evaluated_by' => Auth::id(),
                'evaluation_notes' => $request->evaluation_notes,
            ]
        );

        return back()->with('success', "Penilaian KPI berhasil disimpan dengan Skor Akhir {$finalScore} (Grade {$grade})!");
    }

    /**
     * =========================================================================
     * 6. APPROVAL CUTI
     * URL: /admin/hr/cuti
     * =========================================================================
     */
    public function approvalCuti(Request $request)
    {
        $query = LeaveRequest::with(['employee.outlet', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->orderByDesc('id')->get();

        $pendingCount = LeaveRequest::where('status', 'Pending')->count();
        $approvedCount = LeaveRequest::where('status', 'Approved')->count();
        $rejectedCount = LeaveRequest::where('status', 'Rejected')->count();
        $totalCount = LeaveRequest::count();

        $allEmployees = Employee::where('status', 'active')->orderBy('name')->get();

        return view('admin.hr.cuti', compact(
            'leaveRequests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount',
            'allEmployees'
        ));
    }

    public function storeLeaveRequest(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $daysCount = $start->diffInDays($end) + 1;

        LeaveRequest::create([
            'employee_id' => $request->employee_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_count' => $daysCount,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return back()->with('success', "Pengajuan cuti ({$daysCount} hari) berhasil didaftarkan dan menunggu persetujuan!");
    }

    public function approveLeave(Request $request, LeaveRequest $leave)
    {
        $leave->update([
            'status' => 'Approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes ?? 'Disetujui oleh manajemen.',
        ]);

        // Catat absensi Cuti otomatis pada tanggal cuti
        $start = Carbon::parse($leave->start_date);
        $end = Carbon::parse($leave->end_date);
        $days = $start->diffInDays($end) + 1;

        $statusToRecord = str_contains(strtolower($leave->leave_type), 'sakit') ? 'Sakit' : 'Cuti';

        for ($i = 0; $i < $days; $i++) {
            $curDate = $start->copy()->addDays($i)->toDateString();
            Attendance::updateOrCreate(
                ['employee_id' => $leave->employee_id, 'date' => $curDate],
                [
                    'status' => $statusToRecord,
                    'notes' => "Cuti Disetujui: {$leave->leave_type} ({$leave->reason})",
                ]
            );
        }

        return back()->with('success', "Pengajuan cuti #{$leave->id} berhasil DISETUJUI.");
    }

    public function rejectLeave(Request $request, LeaveRequest $leave)
    {
        $leave->update([
            'status' => 'Rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes ?? 'Pengajuan cuti ditolak.',
        ]);

        return back()->with('success', "Pengajuan cuti #{$leave->id} telah DITOLAK.");
    }
}

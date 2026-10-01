<?php

namespace Database\Seeders;

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
use Illuminate\Database\Seeder;

class EmployeeManagementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SHIFTS
        $shiftPagi = Shift::updateOrCreate(
            ['name' => 'Shift Pagi'],
            [
                'start_time' => '07:00:00',
                'end_time' => '15:30:00',
                'color' => '#0d6efd',
                'description' => 'Mempersiapkan opening gerai, kalibrasi espresso grinder, dan layanan pagi.'
            ]
        );

        $shiftSore = Shift::updateOrCreate(
            ['name' => 'Shift Sore'],
            [
                'start_time' => '14:30:00',
                'end_time' => '23:00:00',
                'color' => '#fd7e14',
                'description' => 'Layanan peak hour malam, closing gerai, dan deep cleaning espresso machine.'
            ]
        );

        $shiftMiddle = Shift::updateOrCreate(
            ['name' => 'Shift Middle / Full'],
            [
                'start_time' => '10:00:00',
                'end_time' => '18:30:00',
                'color' => '#198754',
                'description' => 'Membantu jam sibuk makan siang dan restok display bakery/pastry.'
            ]
        );

        // 2. OUTLETS
        $outlet1 = Outlet::where('code', 'OUT-001')->first() ?? Outlet::first();
        $outlet2 = Outlet::where('code', 'OUT-002')->first() ?? $outlet1;
        $outlet3 = Outlet::where('code', 'OUT-003')->first() ?? $outlet1;

        $userCashier = User::where('role', 'cashier')->first();
        $userKitchen = User::where('role', 'kitchen')->first();
        $userAdmin = User::first();

        // 3. EMPLOYEES
        $employeesData = [
            [
                'employee_code' => 'EMP-001',
                'name' => 'Dimas Anggara',
                'email' => 'dimas.barista@cafe.com',
                'phone' => '081234567801',
                'position' => 'Senior Head Barista',
                'department' => 'Bar & Beverage',
                'join_date' => '2024-01-15',
                'employment_status' => 'Permanent',
                'base_salary' => 5200000,
                'daily_allowance' => 35000,
                'outlet_id' => $outlet1?->id,
                'user_id' => null,
            ],
            [
                'employee_code' => 'EMP-002',
                'name' => 'Sarah Cashier',
                'email' => 'cashier@cafe.com',
                'phone' => '081234567802',
                'position' => 'Senior Cashier & Front Desk',
                'department' => 'Front of House',
                'join_date' => '2024-03-01',
                'employment_status' => 'Permanent',
                'base_salary' => 4500000,
                'daily_allowance' => 30000,
                'outlet_id' => $outlet1?->id,
                'user_id' => $userCashier?->id,
            ],
            [
                'employee_code' => 'EMP-003',
                'name' => 'Chef Hendra Pratama',
                'email' => 'kitchen@cafe.com',
                'phone' => '081234567803',
                'position' => 'Head Chef',
                'department' => 'Kitchen & Food',
                'join_date' => '2023-11-10',
                'employment_status' => 'Permanent',
                'base_salary' => 6000000,
                'daily_allowance' => 40000,
                'outlet_id' => $outlet1?->id,
                'user_id' => $userKitchen?->id,
            ],
            [
                'employee_code' => 'EMP-004',
                'name' => 'Nabila Putri',
                'email' => 'nabila.barista@cafe.com',
                'phone' => '081234567804',
                'position' => 'Junior Barista',
                'department' => 'Bar & Beverage',
                'join_date' => '2024-06-20',
                'employment_status' => 'Contract',
                'base_salary' => 3800000,
                'daily_allowance' => 25000,
                'outlet_id' => $outlet2?->id,
                'user_id' => null,
            ],
            [
                'employee_code' => 'EMP-005',
                'name' => 'Reza Kurniawan',
                'email' => 'reza.cook@cafe.com',
                'phone' => '081234567805',
                'position' => 'Line Cook / Pastry',
                'department' => 'Kitchen & Food',
                'join_date' => '2024-05-10',
                'employment_status' => 'Contract',
                'base_salary' => 4000000,
                'daily_allowance' => 25000,
                'outlet_id' => $outlet2?->id,
                'user_id' => null,
            ],
            [
                'employee_code' => 'EMP-006',
                'name' => 'Alisha Zahra',
                'email' => 'alisha.spv@cafe.com',
                'phone' => '081234567806',
                'position' => 'Floor Supervisor',
                'department' => 'Operations',
                'join_date' => '2023-09-01',
                'employment_status' => 'Permanent',
                'base_salary' => 5500000,
                'daily_allowance' => 35000,
                'outlet_id' => $outlet3?->id,
                'user_id' => null,
            ],
        ];

        $createdEmployees = [];
        foreach ($employeesData as $empData) {
            $createdEmployees[] = Employee::updateOrCreate(
                ['employee_code' => $empData['employee_code']],
                $empData
            );
        }

        // 4. ATTENDANCES (Today & Recent Days)
        $today = Carbon::today();
        $statuses = ['Hadir', 'Hadir', 'Hadir', 'Izin', 'Sakit', 'Hadir'];

        foreach ($createdEmployees as $idx => $emp) {
            $status = $statuses[$idx % count($statuses)];
            $shift = ($idx % 2 === 0) ? $shiftPagi : $shiftSore;

            $clockIn = null;
            $clockOut = null;
            $lateMinutes = 0;

            if ($status === 'Hadir') {
                $clockIn = Carbon::parse($today->toDateString() . ' ' . $shift->start_time)->addMinutes(($idx % 3) * 5);
                $lateMinutes = max(0, $clockIn->diffInMinutes(Carbon::parse($today->toDateString() . ' ' . $shift->start_time), false) * -1);
                $clockOut = Carbon::parse($today->toDateString() . ' ' . $shift->end_time);
            }

            Attendance::updateOrCreate(
                ['employee_id' => $emp->id, 'date' => $today->toDateString()],
                [
                    'shift_id' => $shift->id,
                    'outlet_id' => $emp->outlet_id,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'notes' => $status === 'Izin' ? 'Keperluan keluarga mendesak' : ($status === 'Sakit' ? 'Demam dan flu' : 'Kehadiran normal on-time'),
                ]
            );

            // Seed Yesterday as well
            $yesterday = $today->copy()->subDay();
            Attendance::updateOrCreate(
                ['employee_id' => $emp->id, 'date' => $yesterday->toDateString()],
                [
                    'shift_id' => $shift->id,
                    'outlet_id' => $emp->outlet_id,
                    'clock_in' => Carbon::parse($yesterday->toDateString() . ' ' . $shift->start_time),
                    'clock_out' => Carbon::parse($yesterday->toDateString() . ' ' . $shift->end_time),
                    'status' => 'Hadir',
                    'late_minutes' => 0,
                    'notes' => 'Shift berjalan lancar',
                ]
            );
        }

        // 5. EMPLOYEE SCHEDULES (Weekly Roster)
        for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
            $scheduleDate = $today->copy()->startOfWeek()->addDays($dayOffset);
            foreach ($createdEmployees as $idx => $emp) {
                $shiftToAssign = (($idx + $dayOffset) % 2 === 0) ? $shiftPagi : $shiftSore;
                EmployeeSchedule::updateOrCreate(
                    ['employee_id' => $emp->id, 'schedule_date' => $scheduleDate->toDateString()],
                    [
                        'shift_id' => $shiftToAssign->id,
                        'outlet_id' => $emp->outlet_id,
                        'notes' => 'Jadwal reguler mingguan',
                    ]
                );
            }
        }

        // 6. LEAVE REQUESTS
        LeaveRequest::updateOrCreate(
            ['employee_id' => $createdEmployees[0]->id, 'start_date' => $today->copy()->addDays(5)->toDateString()],
            [
                'leave_type' => 'Cuti Tahunan',
                'end_date' => $today->copy()->addDays(7)->toDateString(),
                'days_count' => 3,
                'reason' => 'Libur tahunan keluarga ke luar kota',
                'status' => 'Pending',
                'approved_by' => null,
                'approved_at' => null,
                'admin_notes' => null,
            ]
        );

        LeaveRequest::updateOrCreate(
            ['employee_id' => $createdEmployees[1]->id, 'start_date' => $today->copy()->subDays(10)->toDateString()],
            [
                'leave_type' => 'Izin Sakit',
                'end_date' => $today->copy()->subDays(9)->toDateString(),
                'days_count' => 2,
                'reason' => 'Rawat jalan radang tenggorokan (ada surat dokter)',
                'status' => 'Approved',
                'approved_by' => $userAdmin?->id,
                'approved_at' => $today->copy()->subDays(10),
                'admin_notes' => 'Disetujui. Surat dokter valid terlampir.',
            ]
        );

        LeaveRequest::updateOrCreate(
            ['employee_id' => $createdEmployees[3]->id, 'start_date' => $today->copy()->subDays(3)->toDateString()],
            [
                'leave_type' => 'Cuti Tahunan',
                'end_date' => $today->copy()->subDays(2)->toDateString(),
                'days_count' => 2,
                'reason' => 'Acara wisuda saudara',
                'status' => 'Approved',
                'approved_by' => $userAdmin?->id,
                'approved_at' => $today->copy()->subDays(4),
                'admin_notes' => 'Disetujui. Shift pengganti telah dialihkan.',
            ]
        );

        // 7. PAYROLLS (Current & Last Month)
        $currentMonth = $today->month;
        $currentYear = $today->year;

        foreach ($createdEmployees as $idx => $emp) {
            $allowance = $emp->daily_allowance * 24; // 24 hari kerja
            $overtime = ($idx % 2 === 0) ? 250000 : 0;
            $bonus = ($idx === 0 || $idx === 2) ? 400000 : 0;
            $deductions = ($idx === 3) ? 100000 : 50000; // BPJS / Kasbon
            $netSalary = $emp->base_salary + $allowance + $overtime + $bonus - $deductions;

            Payroll::updateOrCreate(
                ['employee_id' => $emp->id, 'month' => $currentMonth, 'year' => $currentYear],
                [
                    'payroll_code' => 'PAY-' . $currentYear . str_pad($currentMonth, 2, '0', STR_PAD_LEFT) . '-' . str_pad($emp->id, 3, '0', STR_PAD_LEFT),
                    'base_salary' => $emp->base_salary,
                    'allowance' => $allowance,
                    'overtime_pay' => $overtime,
                    'bonus' => $bonus,
                    'deductions' => $deductions,
                    'net_salary' => $netSalary,
                    'status' => $idx < 3 ? 'Paid' : 'Approved',
                    'paid_at' => $idx < 3 ? $today->copy()->startOfMonth()->addDays(25) : null,
                    'payment_method' => 'BCA Payroll Transfer',
                    'notes' => 'Gaji periode ' . Carbon::create($currentYear, $currentMonth, 1)->format('F Y'),
                ]
            );
        }

        // 8. EMPLOYEE KPIS
        $kpiGrades = ['A', 'A', 'B', 'B', 'C', 'A'];
        $kpiScores = [
            ['att' => 95, 'spd' => 92, 'sop' => 90, 'team' => 95, 'final' => 93.0, 'grade' => 'A'],
            ['att' => 98, 'spd' => 94, 'sop' => 92, 'team' => 96, 'final' => 95.0, 'grade' => 'A'],
            ['att' => 88, 'spd' => 85, 'sop' => 89, 'team' => 86, 'final' => 87.0, 'grade' => 'B'],
            ['att' => 82, 'spd' => 80, 'sop' => 85, 'team' => 84, 'final' => 82.7, 'grade' => 'B'],
            ['att' => 75, 'spd' => 72, 'sop' => 78, 'team' => 76, 'final' => 75.2, 'grade' => 'C'],
            ['att' => 96, 'spd' => 95, 'sop' => 94, 'team' => 97, 'final' => 95.5, 'grade' => 'A'],
        ];

        foreach ($createdEmployees as $idx => $emp) {
            $score = $kpiScores[$idx % count($kpiScores)];
            EmployeeKpi::updateOrCreate(
                ['employee_id' => $emp->id, 'period_month' => $currentMonth, 'period_year' => $currentYear],
                [
                    'attendance_score' => $score['att'],
                    'service_speed_score' => $score['spd'],
                    'sop_compliance_score' => $score['sop'],
                    'teamwork_score' => $score['team'],
                    'final_score' => $score['final'],
                    'grade' => $score['grade'],
                    'evaluated_by' => $userAdmin?->id,
                    'evaluation_notes' => 'Performa kerja sangat memuaskan, konsisten menjaga standar kualitas sajian dan disiplin kehadiran.',
                ]
            );
        }
    }
}

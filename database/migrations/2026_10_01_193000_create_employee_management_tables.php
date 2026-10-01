<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. EMPLOYEES TABLE
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('position'); // Barista, Kasir, Kitchen, Supervisor, dll.
            $table->string('department')->default('Operations');
            $table->date('join_date');
            $table->enum('employment_status', ['Permanent', 'Contract', 'Probation', 'Part-Time'])->default('Contract');
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->decimal('daily_allowance', 12, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. SHIFTS TABLE
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Shift Pagi, Shift Sore, Shift Full
            $table->time('start_time');
            $table->time('end_time');
            $table->string('color')->default('#0d6efd');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. EMPLOYEE SCHEDULES (Shift Kerja Roster)
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->cascadeOnDelete();
            $table->foreignId('outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
            $table->date('schedule_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'schedule_date']);
        });

        // 4. ATTENDANCES (Absensi & Status: Hadir, Izin, Sakit, Cuti)
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->foreignId('outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
            $table->date('date');
            $table->dateTime('clock_in')->nullable();
            $table->dateTime('clock_out')->nullable();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Cuti', 'Alpa'])->default('Hadir');
            $table->integer('late_minutes')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });

        // 5. LEAVE REQUESTS (Approval Cuti)
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('leave_type'); // Cuti Tahunan, Izin Sakit, Izin Khusus, Cuti Menikah
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_count')->default(1);
            $table->text('reason');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 6. PAYROLLS (Gaji & Payroll Bulanan)
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->integer('month'); // 1 - 12
            $table->integer('year');
            $table->string('payroll_code')->unique();
            $table->decimal('base_salary', 12, 2);
            $table->decimal('allowance', 12, 2)->default(0);
            $table->decimal('overtime_pay', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2);
            $table->enum('status', ['Draft', 'Approved', 'Paid'])->default('Draft');
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_method')->default('Bank Transfer');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year']);
        });

        // 7. EMPLOYEE KPIS (KPI Karyawan)
        Schema::create('employee_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->integer('period_month');
            $table->integer('period_year');
            $table->decimal('attendance_score', 5, 2)->default(0); // 0 - 100
            $table->decimal('service_speed_score', 5, 2)->default(0);
            $table->decimal('sop_compliance_score', 5, 2)->default(0);
            $table->decimal('teamwork_score', 5, 2)->default(0);
            $table->decimal('final_score', 5, 2)->default(0);
            $table->enum('grade', ['A', 'B', 'C', 'D'])->default('B');
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('evaluation_notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'period_month', 'period_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_kpis');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('employee_schedules');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('employees');
    }
};

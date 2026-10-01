@extends('layouts.admin')
@section('title', 'SDM: Shift Kerja & Jadwal')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-clock-history text-warning"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Shift Kerja & Roster Jadwal Karyawan</h4>
                <small class="text-muted">Kelola master jam kerja shift (pagi, sore, full) dan atur penugasan jadwal roster mingguan barista & kitchen.</small>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahShift">
                <i class="bi bi-plus-circle me-1"></i> Tambah Master Shift
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAssignJadwal">
                <i class="bi bi-calendar-plus me-1"></i> Tetapkan Jadwal Shift
            </button>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- MASTER SHIFT CARDS --}}
    <div class="row g-3 mb-4">
        @foreach($shifts as $shift)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4" style="border-left-color: {{ $shift->color }} !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0" style="color: {{ $shift->color }};">{{ $shift->name }}</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEditShift{{ $shift->id }}">
                                            <i class="bi bi-pencil me-2 text-primary"></i> Edit Shift
                                        </button>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.hr.shift.destroy', $shift) }}" method="POST" onsubmit="return confirm('Hapus shift ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-dark border fs-6">
                                <i class="bi bi-clock me-1"></i> {{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}
                            </span>
                        </div>

                        <p class="small text-muted mb-2">{{ $shift->description ?? 'Tidak ada deskripsi khusus.' }}</p>

                        <div class="small fw-semibold text-secondary">
                            <i class="bi bi-calendar-check me-1"></i> {{ $shift->schedules_count }} Jadwal Terjadwalkan
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL EDIT SHIFT --}}
            <div class="modal fade" id="modalEditShift{{ $shift->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('admin.hr.shift.update', $shift) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Edit Shift: {{ $shift->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nama Shift</label>
                                    <input type="text" name="name" class="form-control" value="{{ $shift->name }}" required>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Jam Mulai</label>
                                        <input type="time" name="start_time" class="form-control" value="{{ substr($shift->start_time, 0, 5) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Jam Selesai</label>
                                        <input type="time" name="end_time" class="form-control" value="{{ substr($shift->end_time, 0, 5) }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Warna Label</label>
                                    <input type="color" name="color" class="form-control form-control-color w-100" value="{{ $shift->color }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Deskripsi Tugas</label>
                                    <textarea name="description" class="form-control" rows="2">{{ $shift->description }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- NAVIGASI MINGGUAN ROSTER JADWAL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-calendar3 me-2 text-warning"></i> Roster Jadwal Mingguan: {{ $startOfWeek->translatedFormat('d M') }} - {{ $endOfWeek->translatedFormat('d M Y') }}
                </h6>
                <small class="text-muted">Jadwal penugasan shift harian karyawan di seluruh cabang.</small>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.hr.shift', ['week_start' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-chevron-left me-1"></i> Minggu Lalu
                </a>
                <a href="{{ route('admin.hr.shift', ['week_start' => now()->startOfWeek()->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-primary">
                    Minggu Ini
                </a>
                <a href="{{ route('admin.hr.shift', ['week_start' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-secondary">
                    Minggu Depan <i class="bi bi-chevron-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start ps-3" style="width: 220px;">Karyawan</th>
                            @foreach($weekDays as $day)
                                <th class="{{ $day->isToday() ? 'table-warning' : '' }}">
                                    <div>{{ $day->translatedFormat('D') }}</div>
                                    <small class="fw-normal text-muted">{{ $day->format('d M') }}</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            <tr>
                                <td class="text-start ps-3">
                                    <span class="fw-bold text-dark d-block">{{ $emp->name }}</span>
                                    <small class="text-muted">{{ $emp->position }}</small>
                                    <small class="text-primary d-block font-monospace">{{ $emp->outlet->name ?? 'HQ' }}</small>
                                </td>
                                @foreach($weekDays as $day)
                                    @php
                                        $dateStr = $day->toDateString();
                                        $sched = $emp->schedules->firstWhere('schedule_date', $day);
                                    @endphp
                                    <td class="{{ $day->isToday() ? 'bg-warning bg-opacity-10' : '' }}">
                                        @if($sched && $sched->shift)
                                            <span class="badge py-2 px-2 d-block shadow-sm" style="background-color: {{ $sched->shift->color }}; color: #fff; font-size: 0.75rem;">
                                                {{ $sched->shift->name }}
                                                <div class="fw-normal" style="font-size: 0.65rem;">
                                                    {{ substr($sched->shift->start_time, 0, 5) }} - {{ substr($sched->shift->end_time, 0, 5) }}
                                                </div>
                                            </span>
                                        @else
                                            <span class="text-muted small fst-italic">Libur / Off</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada karyawan aktif untuk jadwal shift.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL TAMBAH MASTER SHIFT --}}
<div class="modal fade" id="modalTambahShift" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.hr.shift.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i> Tambah Master Shift Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Shift <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Shift Malam / Closing" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control" value="07:00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control" value="15:30" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Warna Label</label>
                        <input type="color" name="color" class="form-control form-control-color w-100" value="#0d6efd" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi Tugas</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Tanggung jawab utama selama shift ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Simpan Shift</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ASSIGN JADWAL KARYAWAN --}}
<div class="modal fade" id="modalAssignJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.hr.shift.schedule') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus text-primary me-2"></i> Tetapkan Jadwal Shift Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Karyawan <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Karyawan...</option>
                            @foreach($employees as $e)
                                <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->position }} - {{ $e->outlet->name ?? 'HQ' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Dinas <span class="text-danger">*</span></label>
                        <input type="date" name="schedule_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Shift Kerja <span class="text-danger">*</span></label>
                        <select name="shift_id" class="form-select" required>
                            @foreach($shifts as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ substr($s->start_time, 0, 5) }} - {{ substr($s->end_time, 0, 5) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan Penugasan</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Barista utama espresso bar">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Terapkan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

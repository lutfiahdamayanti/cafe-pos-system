@extends('layouts.admin')
@section('title', 'SDM: Absensi Masuk & Pulang')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3 fs-5">
                <i class="bi bi-fingerprint text-primary"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Absensi Masuk & Pulang Karyawan</h4>
                <small class="text-muted">Pencatatan real-time kehadiran jam masuk (clock-in), jam pulang (clock-out), dan keterlambatan.</small>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalManualAbsensi">
                <i class="bi bi-pencil-square me-1"></i> Catat Manual
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalQuickClockIn">
                <i class="bi bi-box-arrow-in-right me-1"></i> Clock-In Karyawan
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

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Karyawan</span>
                    <i class="bi bi-people fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $totalEmployees }}</h3>
                <small class="text-muted">Staff aktif terdaftar</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Sudah Hadir (Clock-In)</span>
                    <i class="bi bi-box-arrow-in-right fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $attendedCount }}</h3>
                <small class="text-muted">{{ $clockedOutCount }} sudah clock-out pulang</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Terlambat Masuk</span>
                    <i class="bi bi-stopwatch fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $lateCount }}</h3>
                <small class="text-muted">Melewati jadwal shift</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Izin / Sakit / Cuti</span>
                    <i class="bi bi-person-x fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ $absentCount }}</h3>
                <small class="text-muted">Tidak hadir dengan keterangan</small>
            </div>
        </div>
    </div>

    {{-- FILTER TANGGAL & OUTLET --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.hr.absensi') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" name="date" class="form-control" value="{{ $selectedDate->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="outlet_id" class="form-select">
                        <option value="">Semua Cabang Gerai</option>
                        @foreach($allOutlets as $o)
                            <option value="{{ $o->id }}" {{ request('outlet_id') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau jabatan..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL ABSENSI HARI INI --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-clock-history me-2 text-primary"></i> Daftar Presensi: {{ $selectedDate->translatedFormat('l, d F Y') }}
            </h6>
            <span class="badge bg-light text-dark">{{ $employees->count() }} Karyawan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Karyawan</th>
                            <th>Jabatan & Cabang</th>
                            <th>Jadwal Shift</th>
                            <th>Jam Masuk (Clock-In)</th>
                            <th>Jam Pulang (Clock-Out)</th>
                            <th>Status Kehadiran</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            @php
                                $att = $emp->attendances->first();
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($emp->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $emp->name }}</span>
                                            <span class="badge bg-secondary font-monospace" style="font-size: 0.7rem;">{{ $emp->employee_code }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $emp->position }}</span>
                                    <small class="text-muted"><i class="bi bi-shop me-1"></i> {{ $emp->outlet->name ?? 'Semua Gerai' }}</small>
                                </td>
                                <td>
                                    @if($att && $att->shift)
                                        <span class="badge" style="background-color: {{ $att->shift->color }}; color: #fff;">
                                            {{ $att->shift->name }} ({{ substr($att->shift->start_time, 0, 5) }} - {{ substr($att->shift->end_time, 0, 5) }})
                                        </span>
                                    @else
                                        <span class="text-muted small">Shift Reguler</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att && $att->clock_in)
                                        <span class="fw-bold text-success fs-6">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> {{ $att->clock_in->format('H:i') }}
                                        </span>
                                        @if($att->late_minutes > 0)
                                            <span class="badge bg-warning text-dark d-block mt-1" style="max-width: 130px;">
                                                <i class="bi bi-exclamation-circle me-1"></i> Terlambat {{ $att->late_minutes }}m
                                            </span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success d-block mt-1" style="max-width: 100px;">
                                                <i class="bi bi-check me-1"></i> Tepat Waktu
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic">Belum Masuk</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att && $att->clock_out)
                                        <span class="fw-bold text-primary fs-6">
                                            <i class="bi bi-box-arrow-right me-1"></i> {{ $att->clock_out->format('H:i') }}
                                        </span>
                                    @elseif($att && $att->clock_in)
                                        <span class="badge bg-info text-dark">
                                            <i class="bi bi-arrow-repeat me-1"></i> Sedang Bekerja
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att)
                                        @if($att->status === 'Hadir')
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Hadir</span>
                                        @elseif($att->status === 'Izin')
                                            <span class="badge bg-info text-dark"><i class="bi bi-info-circle me-1"></i> Izin</span>
                                        @elseif($att->status === 'Sakit')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-bandaid me-1"></i> Sakit</span>
                                        @elseif($att->status === 'Cuti')
                                            <span class="badge bg-primary"><i class="bi bi-calendar-check me-1"></i> Cuti</span>
                                        @else
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Alpa</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-secondary border">Belum Absen</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    @if($att && $att->clock_in && !$att->clock_out)
                                        <form action="{{ route('admin.hr.absensi.clock-out', $att) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Catat Jam Pulang">
                                                <i class="bi bi-box-arrow-right me-1"></i> Clock-Out
                                            </button>
                                        </form>
                                    @elseif(!$att || !$att->clock_in)
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalQuickIn{{ $emp->id }}">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                                        </button>
                                    @else
                                        <span class="text-success small fw-semibold"><i class="bi bi-check-all"></i> Selesai</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- MODAL QUICK IN PER KARYAWAN --}}
                            <div class="modal fade" id="modalQuickIn{{ $emp->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.hr.absensi.clock-in') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Clock-In: {{ $emp->name }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label small fw-bold">Pilih Shift</label>
                                                <select name="shift_id" class="form-select mb-2">
                                                    @foreach($allShifts as $sh)
                                                        <option value="{{ $sh->id }}">{{ $sh->name }} ({{ substr($sh->start_time, 0, 5) }} - {{ substr($sh->end_time, 0, 5) }})</option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Waktu clock-in otomatis dicatat saat tombol ditekan.</small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success btn-sm w-100">Konfirmasi Jam Masuk</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-person-x fs-2 d-block mb-2"></i> Tidak ada data karyawan pada filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL QUICK CLOCK-IN GLOBAL --}}
<div class="modal fade" id="modalQuickClockIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.hr.absensi.clock-in') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-box-arrow-in-right text-primary me-2"></i> Clock-In Presensi Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Karyawan <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Karyawan...</option>
                            @foreach($employees as $e)
                                <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->position }} - {{ $e->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Shift Kerja</label>
                        <select name="shift_id" class="form-select">
                            @foreach($allShifts as $sh)
                                <option value="{{ $sh->id }}">{{ $sh->name }} ({{ substr($sh->start_time, 0, 5) }} - {{ substr($sh->end_time, 0, 5) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="p-2 bg-light rounded text-center small text-muted">
                        Jam Masuk akan dicatat otomatis pada <strong>{{ now()->format('H:i') }} WIB</strong>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Rekam Clock-In</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL CATAT MANUAL ABSENSI --}}
<div class="modal fade" id="modalManualAbsensi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.hr.absensi.manual') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i> Catat Presensi Manual / Koreksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Karyawan <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Karyawan...</option>
                            @foreach($employees as $e)
                                <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->position }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Kehadiran <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Alpa">Alpa</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jam Masuk (Clock-In)</label>
                            <input type="time" name="clock_in" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jam Pulang (Clock-Out)</label>
                            <input type="time" name="clock_out" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keterangan / Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan khusus, alasan izin/sakit, dll..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Presensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

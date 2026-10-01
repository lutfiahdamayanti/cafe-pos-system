@extends('layouts.admin')
@section('title', 'SDM: Status Kehadiran')
@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                <i class="bi bi-person-check-fill text-success"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Status Kehadiran Karyawan</h4>
                <small class="text-muted">Rekapitulasi status presensi: Hadir, Izin, Sakit, Cuti, dan Alpa serta rasio kedisiplinan tim.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Rekap Kehadiran
        </button>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Hadir</span>
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $countHadir }}</h3>
                <small class="text-muted">Presensi masuk</small>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Izin</span>
                    <i class="bi bi-info-circle fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ $countIzin }}</h3>
                <small class="text-muted">Keperluan khusus</small>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Sakit</span>
                    <i class="bi bi-bandaid fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $countSakit }}</h3>
                <small class="text-muted">Kondisi kesehatan</small>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Cuti</span>
                    <i class="bi bi-calendar-check fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $countCuti }}</h3>
                <small class="text-muted">Cuti resmi disetujui</small>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="dashboard-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Alpa</span>
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <h3 class="mb-0 fw-bold text-danger">{{ $countAlpa }}</h3>
                <small class="text-muted">Tanpa kabar/keterangan</small>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="dashboard-card p-3 border-start border-4 border-dark">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Disiplin Tim</span>
                    <i class="bi bi-award fs-4 text-dark"></i>
                </div>
                <h3 class="mb-0 fw-bold text-dark">{{ $disciplineRate }}%</h3>
                <small class="text-muted">Rasio kehadiran</small>
            </div>
        </div>
    </div>

    {{-- FILTER BULAN & STATUS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.hr.status-kehadiran') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <select name="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="year" class="form-select">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Hadir" {{ request('status') === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Izin" {{ request('status') === 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ request('status') === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Cuti" {{ request('status') === 'Cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="Alpa" {{ request('status') === 'Alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="outlet_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach($allOutlets as $o)
                            <option value="{{ $o->id }}" {{ request('outlet_id') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL REKAP KEHADIRAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-task me-2 text-success"></i> Log Riwayat Status Kehadiran</h6>
            <span class="badge bg-light text-dark">{{ $attendances->count() }} Catatan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tanggal</th>
                            <th>Karyawan & Kode</th>
                            <th>Cabang</th>
                            <th>Jam Masuk & Pulang</th>
                            <th>Status Kehadiran</th>
                            <th>Keterangan / Alasan</th>
                            <th class="text-end pe-3">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold text-dark d-block">{{ $att->date ? $att->date->translatedFormat('d M Y') : '-' }}</span>
                                    <small class="text-muted">{{ $att->date ? $att->date->translatedFormat('l') : '' }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $att->employee->name }}</span>
                                    <small class="text-muted">{{ $att->employee->position }} • {{ $att->employee->employee_code }}</small>
                                </td>
                                <td>
                                    <small class="text-dark">{{ $att->employee->outlet->name ?? 'Head Office' }}</small>
                                </td>
                                <td>
                                    @if($att->clock_in)
                                        <small class="d-block text-success">In: <strong>{{ $att->clock_in->format('H:i') }}</strong></small>
                                    @else
                                        <small class="d-block text-muted">In: -</small>
                                    @endif
                                    @if($att->clock_out)
                                        <small class="d-block text-primary">Out: <strong>{{ $att->clock_out->format('H:i') }}</strong></small>
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <small class="text-muted">{{ $att->notes ?? '-' }}</small>
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditStatus{{ $att->id }}">
                                        <i class="bi bi-pencil me-1"></i> Ubah
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL UBAH STATUS KEHADIRAN --}}
                            <div class="modal fade" id="modalEditStatus{{ $att->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.hr.status-kehadiran.update', $att) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Ubah Status: {{ $att->employee->name }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold">Status Kehadiran</label>
                                                    <select name="status" class="form-select">
                                                        <option value="Hadir" {{ $att->status === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                                        <option value="Izin" {{ $att->status === 'Izin' ? 'selected' : '' }}>Izin</option>
                                                        <option value="Sakit" {{ $att->status === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                                        <option value="Cuti" {{ $att->status === 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                                        <option value="Alpa" {{ $att->status === 'Alpa' ? 'selected' : '' }}>Alpa</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold">Catatan / Alasan</label>
                                                    <textarea name="notes" class="form-control" rows="2">{{ $att->notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary btn-sm w-100">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada rekaman kehadiran pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

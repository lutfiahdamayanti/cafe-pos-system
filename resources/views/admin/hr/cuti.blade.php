@extends('layouts.admin')
@section('title', 'SDM: Approval Cuti')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-calendar2-check-fill text-warning"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Approval Cuti Karyawan</h4>
                <small class="text-muted">Pusat persetujuan permohonan cuti tahunan, izin sakit, dan cuti khusus karyawan kafe.</small>
            </div>
        </div>

        <button type="button" class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#modalAjukanCuti">
            <i class="bi bi-plus-circle me-1"></i> Buat Pengajuan Cuti
        </button>
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
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Menunggu Approval</span>
                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $pendingCount }}</h3>
                <small class="text-muted">Perlu tindakan persetujuan</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Cuti Disetujui</span>
                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $approvedCount }}</h3>
                <small class="text-muted">Status: Approved</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Pengajuan Ditolak</span>
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <h3 class="mb-0 fw-bold text-danger">{{ $rejectedCount }}</h3>
                <small class="text-muted">Status: Rejected</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Pengajuan</span>
                    <i class="bi bi-calendar3 fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $totalCount }}</h3>
                <small class="text-muted">Keseluruhan riwayat</small>
            </div>
        </div>
    </div>

    {{-- FILTER STATUS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.hr.cuti') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-10">
                    <select name="status" class="form-select">
                        <option value="">Semua Status Pengajuan</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending (Menunggu Persetujuan)</option>
                        <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                        <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
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

    {{-- TABEL PENGAJUAN CUTI --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-warning"></i> Daftar Pengajuan Cuti & Izin</h6>
            <span class="badge bg-light text-dark">{{ $leaveRequests->count() }} Pengajuan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Karyawan</th>
                            <th>Jenis Cuti</th>
                            <th>Periode Cuti</th>
                            <th>Durasi</th>
                            <th>Alasan Pengajuan</th>
                            <th>Status Cuti</th>
                            <th class="text-end pe-3">Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $leave)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-warning bg-opacity-10 text-dark fw-bold d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($leave->employee->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $leave->employee->name }}</span>
                                            <small class="text-muted">{{ $leave->employee->position }} • {{ $leave->employee->outlet->name ?? 'HQ' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $leave->leave_type }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block">
                                        {{ $leave->start_date ? $leave->start_date->format('d M Y') : '-' }} s/d {{ $leave->end_date ? $leave->end_date->format('d M Y') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark fs-6">{{ $leave->days_count }} Hari</span>
                                </td>
                                <td>
                                    <small class="text-muted d-block" style="max-width: 220px;">
                                        "{{ $leave->reason }}"
                                    </small>
                                    @if($leave->admin_notes)
                                        <small class="text-primary d-block mt-1"><strong>Reviewer:</strong> {{ $leave->admin_notes }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->status === 'Approved')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Disetujui</span>
                                    @elseif($leave->status === 'Rejected')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Menunggu Approval</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    @if($leave->status === 'Pending')
                                        <div class="btn-group btn-group-sm">
                                            <form action="{{ route('admin.hr.cuti.approve', $leave) }}" method="POST" onsubmit="return confirm('Setujui permohonan cuti ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success" title="Setujui Cuti">
                                                    <i class="bi bi-check2"></i> Setujui
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolakCuti{{ $leave->id }}" title="Tolak Cuti">
                                                <i class="bi bi-x"></i> Tolak
                                            </button>
                                        </div>
                                    @else
                                        <small class="text-muted">
                                            Diproses oleh: <strong>{{ $leave->approver->name ?? 'Admin' }}</strong>
                                        </small>
                                    @endif
                                </td>
                            </tr>

                            {{-- MODAL TOLAK CUTI --}}
                            <div class="modal fade" id="modalTolakCuti{{ $leave->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.hr.cuti.reject', $leave) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold text-danger">Tolak Cuti: {{ $leave->employee->name }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label small fw-bold">Alasan Penolakan</label>
                                                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Contoh: Operasional gerai sedang padat, silakan ajukan di tanggal lain..." required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak Cuti</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada pengajuan cuti yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL AJUKAN CUTI BARU --}}
<div class="modal fade" id="modalAjukanCuti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.hr.cuti.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus text-warning me-2"></i> Form Permohonan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Karyawan <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Karyawan...</option>
                            @foreach($allEmployees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->position }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Jenis Cuti / Izin <span class="text-danger">*</span></label>
                        <select name="leave_type" class="form-select" required>
                            <option value="Cuti Tahunan">Cuti Tahunan</option>
                            <option value="Izin Sakit">Izin Sakit</option>
                            <option value="Izin Khusus / Keperluan Mendesak">Izin Khusus / Keperluan Mendesak</option>
                            <option value="Cuti Menikah">Cuti Menikah</option>
                            <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alasan / Keterangan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Jelaskan keperluan cuti..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold"><i class="bi bi-send me-1"></i> Kirim Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

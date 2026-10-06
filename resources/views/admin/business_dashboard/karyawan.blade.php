@extends('layouts.admin')
@section('title', 'Dashboard Bisnis: Analitik Karyawan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-3 fs-5">
                <i class="bi bi-person-badge-fill text-info"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Dashboard Bisnis: Analitik Karyawan & SDM</h4>
                <small class="text-muted">Analisis produktivitas tim: tingkat kedisiplinan absensi, evaluasi rata-rata skor KPI, dan peringkat staf terbaik.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan Karyawan
        </button>
    </div>

    {{-- FILTER BULAN & TAHUN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.business-dashboard.karyawan') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <select name="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-5">
                    <select name="year" class="form-select">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
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

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Karyawan Aktif</span>
                    <i class="bi bi-people fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ $totalEmployees }} Orang</h3>
                <small class="text-muted">Barista, kitchen, kasir, spv</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Tingkat Disiplin Tepat Waktu</span>
                    <i class="bi bi-clock-check fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $disciplinePct }}%</h3>
                <small class="text-muted">Presensi masuk on-time</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Skor KPI</span>
                    <i class="bi bi-speedometer2 fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ round($avgKpiScore, 1) }}</h3>
                <small class="text-muted">Skala 0 - 100</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Karyawan Grade A</span>
                    <i class="bi bi-trophy fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $countGradeA }} Orang</h3>
                <small class="text-muted">{{ $countGradeB }} Grade B • {{ $countGradeCD }} Grade C/D</small>
            </div>
        </div>
    </div>

    {{-- TOP PERFORMER HIGHLIGHT --}}
    @if($topPerformerKpi)
        <div class="card shadow-sm border-0 border-start border-4 border-warning bg-warning bg-opacity-10 mb-4">
            <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning text-dark p-3 fs-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark mb-1">Top Employee of the Month</span>
                        <h5 class="fw-bold text-dark mb-0">{{ $topPerformerKpi->employee->name }}</h5>
                        <small class="text-muted">{{ $topPerformerKpi->employee->position }} • {{ $topPerformerKpi->employee->outlet->name ?? 'HQ' }}</small>
                    </div>
                </div>
                <div class="text-md-end">
                    <span class="text-muted small d-block">Skor Akhir KPI:</span>
                    <h3 class="fw-bold text-success mb-0">{{ $topPerformerKpi->final_score }} / 100</h3>
                    <span class="badge bg-success">Grade {{ $topPerformerKpi->grade }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- TABEL ANALITIK PRODUKTIVITAS KARYAWAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-person-lines-fill me-2 text-info"></i> Tabel Performa & Kedisiplinan Staf ({{ Carbon\Carbon::create(null, $month, 1)->translatedFormat('F') }} {{ $year }})
            </h6>
            <a href="{{ route('admin.hr.kpi') }}" class="btn btn-sm btn-outline-primary">
                Kelola KPI Karyawan <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">Rank</th>
                            <th>Karyawan</th>
                            <th>Jabatan</th>
                            <th>Gerai / Outlet</th>
                            <th>Kehadiran Masuk</th>
                            <th>Terlambat</th>
                            <th>Skor KPI</th>
                            <th class="pe-3">Grade Evaluasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employeeStats as $idx => $st)
                            <tr>
                                <td class="ps-3">
                                    @if($idx === 0 && $st->kpi_score)
                                        <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">🥇 1</span>
                                    @elseif($idx === 1 && $st->kpi_score)
                                        <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">🥈 2</span>
                                    @elseif($idx === 2 && $st->kpi_score)
                                        <span class="badge bg-danger bg-opacity-75 text-white rounded-circle p-2 fs-6">🥉 3</span>
                                    @else
                                        <span class="fw-bold text-muted ps-2">#{{ $idx + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $st->employee->name }}</span>
                                    <small class="text-muted">{{ $st->employee->employee_code }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $st->employee->position }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $st->employee->outlet->name ?? 'HQ' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success border fs-6">
                                        {{ $st->hadir_count }} Hari Hadir
                                    </span>
                                </td>
                                <td>
                                    @if($st->late_count > 0)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-exclamation-circle me-1"></i> {{ $st->late_count }}x Telat
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success">0x (Disiplin)</span>
                                    @endif
                                </td>
                                <td>
                                    @if($st->kpi_score)
                                        <span class="fw-bold fs-6 text-primary">{{ $st->kpi_score }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="pe-3">
                                    @if($st->grade !== '-')
                                        <span class="badge 
                                            @if($st->grade === 'A') bg-success 
                                            @elseif($st->grade === 'B') bg-primary 
                                            @elseif($st->grade === 'C') bg-warning text-dark 
                                            @else bg-danger 
                                            @endif fs-6">
                                            Grade {{ $st->grade }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-secondary border">Belum Dievaluasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data staf.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

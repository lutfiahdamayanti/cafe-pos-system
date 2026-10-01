@extends('layouts.admin')
@section('title', 'SDM: KPI Karyawan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger bg-opacity-10 text-danger p-2 rounded-3 fs-5">
                <i class="bi bi-graph-up-arrow text-danger"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">KPI Karyawan (Key Performance Indicators)</h4>
                <small class="text-muted">Evaluasi performa berkala: kedisiplinan absensi, kecepatan layanan, kepatuhan SOP kebersihan, dan kerjasama tim.</small>
            </div>
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInputKpi">
            <i class="bi bi-plus-circle me-1"></i> Input / Evaluasi KPI Karyawan
        </button>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TOP PERFORMER & KPI SUMMARY --}}
    <div class="row g-3 mb-4">
        @if($topPerformer)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm border-top border-4 border-warning bg-warning bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-trophy-fill me-1"></i> Top Performer Bulan Ini</span>
                            <span class="fw-bold fs-4 text-warning">Grade {{ $topPerformer->grade }}</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">{{ $topPerformer->employee->name }}</h4>
                        <p class="text-muted small mb-2">{{ $topPerformer->employee->position }} • {{ $topPerformer->employee->outlet->name ?? 'HQ' }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Skor Akhir:</span>
                            <span class="fw-bold text-success fs-5">{{ $topPerformer->final_score }} / 100</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-{{ $topPerformer ? '8' : '12' }}">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="dashboard-card p-3 border-start border-4 border-primary">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small">Rata-rata Skor</span>
                            <i class="bi bi-speedometer2 fs-4 text-primary"></i>
                        </div>
                        <h3 class="mb-0 fw-bold text-primary">{{ round($avgScore, 1) }}</h3>
                        <small class="text-muted">Skala 0 - 100</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="dashboard-card p-3 border-start border-4 border-success">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small">Grade A</span>
                            <i class="bi bi-star-fill fs-4 text-success"></i>
                        </div>
                        <h3 class="mb-0 fw-bold text-success">{{ $countGradeA }}</h3>
                        <small class="text-muted">Sangat Memuaskan</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="dashboard-card p-3 border-start border-4 border-info">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small">Grade B</span>
                            <i class="bi bi-check-circle fs-4 text-info"></i>
                        </div>
                        <h3 class="mb-0 fw-bold text-info">{{ $countGradeB }}</h3>
                        <small class="text-muted">Baik / Standar</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="dashboard-card p-3 border-start border-4 border-danger">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small">Grade C / D</span>
                            <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
                        </div>
                        <h3 class="mb-0 fw-bold text-danger">{{ $countGradeCD }}</h3>
                        <small class="text-muted">Perlu Pembinaan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER BULAN & GRADE --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.hr.kpi') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <select name="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="year" class="form-select">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="grade" class="form-select">
                        <option value="">Semua Grade Evaluasi</option>
                        <option value="A" {{ request('grade') === 'A' ? 'selected' : '' }}>Grade A (Sangat Memuaskan)</option>
                        <option value="B" {{ request('grade') === 'B' ? 'selected' : '' }}>Grade B (Baik)</option>
                        <option value="C" {{ request('grade') === 'C' ? 'selected' : '' }}>Grade C (Cukup)</option>
                        <option value="D" {{ request('grade') === 'D' ? 'selected' : '' }}>Grade D (Kurang)</option>
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

    {{-- TABEL EVALUASI KPI --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-award me-2 text-danger"></i> Hasil Penilaian KPI Periode: {{ Carbon\Carbon::create(null, $month, 1)->translatedFormat('F') }} {{ $year }}
            </h6>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Laporan KPI
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">Rank</th>
                            <th>Karyawan</th>
                            <th>Jabatan & Gerai</th>
                            <th>Kehadiran (25%)</th>
                            <th>Layanan (25%)</th>
                            <th>SOP & Higienis (25%)</th>
                            <th>Teamwork (25%)</th>
                            <th>Skor Akhir</th>
                            <th>Grade</th>
                            <th class="pe-3">Catatan Reviewer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kpis as $idx => $kpi)
                            <tr>
                                <td class="ps-3">
                                    @if($idx === 0)
                                        <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">🥇 1</span>
                                    @elseif($idx === 1)
                                        <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">🥈 2</span>
                                    @elseif($idx === 2)
                                        <span class="badge bg-danger bg-opacity-75 text-white rounded-circle p-2 fs-6">🥉 3</span>
                                    @else
                                        <span class="fw-bold text-muted ps-2">#{{ $idx + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $kpi->employee->name }}</span>
                                    <small class="text-muted">{{ $kpi->employee->employee_code }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $kpi->employee->position }}</span>
                                    <small class="text-muted">{{ $kpi->employee->outlet->name ?? 'HQ' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $kpi->attendance_score }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $kpi->service_speed_score }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $kpi->sop_compliance_score }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $kpi->teamwork_score }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 text-primary">{{ $kpi->final_score }}</span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($kpi->grade === 'A') bg-success 
                                        @elseif($kpi->grade === 'B') bg-primary 
                                        @elseif($kpi->grade === 'C') bg-warning text-dark 
                                        @else bg-danger 
                                        @endif fs-6">
                                        Grade {{ $kpi->grade }}
                                    </span>
                                </td>
                                <td class="pe-3">
                                    <small class="text-muted d-block" style="max-width: 250px;">
                                        {{ $kpi->evaluation_notes ?? '-' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada evaluasi KPI pada bulan ini. Klik tombol input KPI di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL INPUT KPI KARYAWAN --}}
<div class="modal fade" id="modalInputKpi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.hr.kpi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-star text-warning me-2"></i> Form Evaluasi KPI Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Karyawan <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Karyawan...</option>
                            @foreach($allEmployees as $e)
                                <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->position }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Bulan Evaluasi</label>
                            <select name="period_month" class="form-select" required>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tahun</label>
                            <input type="number" name="period_year" class="form-control" value="{{ $year }}" required>
                        </div>
                    </div>

                    <h6 class="fw-bold border-bottom pb-2 mb-2">Bobot Penilaian (Skala 0 - 100)</h6>

                    <div class="mb-2">
                        <label class="form-label small fw-bold">1. Kedisiplinan & Kehadiran (25%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" min="0" max="100" name="attendance_score" class="form-control" placeholder="Nilai 0 - 100 (misal: 95)" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold">2. Kecepatan & Ketepatan Layanan (25%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" min="0" max="100" name="service_speed_score" class="form-control" placeholder="Nilai 0 - 100 (misal: 90)" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold">3. Kepatuhan Resep & SOP Higienis (25%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" min="0" max="100" name="sop_compliance_score" class="form-control" placeholder="Nilai 0 - 100 (misal: 88)" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">4. Kerjasama Tim & Sikap Kerja (25%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" min="0" max="100" name="teamwork_score" class="form-control" placeholder="Nilai 0 - 100 (misal: 95)" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan / Umpan Balik Manager</label>
                        <textarea name="evaluation_notes" class="form-control" rows="2" placeholder="Kelebihan kerja, pencapaian target, area perbaikan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Penilaian KPI</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

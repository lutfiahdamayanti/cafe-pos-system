@extends('layouts.admin')
@section('title', 'SDM: Gaji & Payroll Bulanan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-3 fs-5">
                <i class="bi bi-cash-stack text-info"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Gaji & Payroll Bulanan Karyawan</h4>
                <small class="text-muted">Perhitungan gaji pokok, tunjangan kehadiran, uang lembur, potongan kasbon, dan pencetakan slip gaji.</small>
            </div>
        </div>

        <form action="{{ route('admin.hr.payroll.generate') }}" method="POST" onsubmit="return confirm('Generate payroll untuk semua karyawan aktif pada periode yang dipilih?')">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="year" value="{{ $year }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-magic me-1"></i> Generate Payroll Periode Ini
            </button>
        </form>
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
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Beban Gaji</span>
                    <i class="bi bi-wallet2 fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                <small class="text-muted">{{ Carbon\Carbon::create(null, $month, 1)->translatedFormat('F') }} {{ $year }}</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Gaji Pokok Total</span>
                    <i class="bi bi-cash fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($totalBase, 0, ',', '.') }}</h3>
                <small class="text-muted">Akumulasi gaji pokok</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Tunjangan, Lembur & Bonus</span>
                    <i class="bi bi-gift fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">Rp {{ number_format($totalAllowanceBonus, 0, ',', '.') }}</h3>
                <small class="text-muted">Benefit & insentif lembur</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Realisasi Pembayaran</span>
                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $paidCount }} / {{ $totalCount }}</h3>
                <small class="text-muted">Slip gaji berstatus Paid</small>
            </div>
        </div>
    </div>

    {{-- FILTER BULAN & STATUS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.hr.payroll') }}" method="GET" class="row g-2 align-items-center">
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
                        <option value="">Semua Status Pembayaran</option>
                        <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved (Siap Dibayar)</option>
                        <option value="Paid" {{ request('status') === 'Paid' ? 'selected' : '' }}>Paid (Sudah Ditransfer)</option>
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
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL PAYROLL BULANAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-info"></i> Daftar Slip Gaji Periode {{ Carbon\Carbon::create(null, $month, 1)->translatedFormat('F') }} {{ $year }}</h6>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Seluruh Slip
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode & Karyawan</th>
                            <th>Jabatan & Gerai</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Lembur / Bonus</th>
                            <th>Potongan</th>
                            <th>Gaji Bersih (THP)</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $pay)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold text-dark d-block">{{ $pay->employee->name }}</span>
                                    <span class="badge bg-secondary font-monospace" style="font-size: 0.7rem;">{{ $pay->payroll_code }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $pay->employee->position }}</span>
                                    <small class="text-muted">{{ $pay->employee->outlet->name ?? 'HQ' }}</small>
                                </td>
                                <td>Rp {{ number_format($pay->base_salary, 0, ',', '.') }}</td>
                                <td>
                                    <span class="text-dark">Rp {{ number_format($pay->allowance, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @if($pay->overtime_pay > 0 || $pay->bonus > 0)
                                        <span class="text-success fw-semibold">
                                            +Rp {{ number_format($pay->overtime_pay + $pay->bonus, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pay->deductions > 0)
                                        <span class="text-danger">-Rp {{ number_format($pay->deductions, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">Rp {{ number_format($pay->net_salary, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @if($pay->status === 'Paid')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Paid</span>
                                    @elseif($pay->status === 'Approved')
                                        <span class="badge bg-info text-dark"><i class="bi bi-check me-1"></i> Approved</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-clock me-1"></i> Draft</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Kelola
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalSlip{{ $pay->id }}">
                                                    <i class="bi bi-file-earmark-text me-2 text-primary"></i> Lihat Slip Gaji
                                                </button>
                                            </li>
                                            @if($pay->status === 'Draft')
                                                <li>
                                                    <form action="{{ route('admin.hr.payroll.status', $pay) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="Approved">
                                                        <button type="submit" class="dropdown-item text-info">
                                                            <i class="bi bi-check-circle me-2"></i> Setujui (Approved)
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            @if($pay->status !== 'Paid')
                                                <li>
                                                    <form action="{{ route('admin.hr.payroll.status', $pay) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="Paid">
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="bi bi-cash-coin me-2"></i> Tandai Sudah Ditransfer (Paid)
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL CETAK SLIP GAJI --}}
                            <div class="modal fade" id="modalSlip{{ $pay->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4" id="printableSlip{{ $pay->id }}">
                                            <div class="text-center border-bottom pb-3 mb-3">
                                                <h5 class="fw-bold mb-0">KOPI KITA - CAFE & ROASTERY</h5>
                                                <small class="text-muted">SLIP GAJI KARYAWAN (PAYSLIP)</small>
                                                <div class="fw-bold font-monospace mt-1">{{ $pay->payroll_code }}</div>
                                            </div>

                                            <div class="row g-2 mb-3 small">
                                                <div class="col-6">
                                                    <span class="text-muted d-block">Nama Karyawan:</span>
                                                    <strong>{{ $pay->employee->name }}</strong>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="text-muted d-block">Periode Gaji:</span>
                                                    <strong>{{ Carbon\Carbon::create(null, $pay->month, 1)->translatedFormat('F') }} {{ $pay->year }}</strong>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted d-block">Jabatan:</span>
                                                    <span>{{ $pay->employee->position }}</span>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="text-muted d-block">Gerai / Cabang:</span>
                                                    <span>{{ $pay->employee->outlet->name ?? 'Head Office' }}</span>
                                                </div>
                                            </div>

                                            <table class="table table-sm table-bordered mb-3 small">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Komponen Penghasilan</th>
                                                        <th class="text-end">Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Gaji Pokok</td>
                                                        <td class="text-end">Rp {{ number_format($pay->base_salary, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tunjangan Kehadiran / Makan</td>
                                                        <td class="text-end">Rp {{ number_format($pay->allowance, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Uang Lembur (Overtime)</td>
                                                        <td class="text-end">Rp {{ number_format($pay->overtime_pay, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Bonus Kinerja / Insentif</td>
                                                        <td class="text-end">Rp {{ number_format($pay->bonus, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr class="table-light">
                                                        <th>Potongan (Kasbon / BPJS)</th>
                                                        <th class="text-end text-danger">-Rp {{ number_format($pay->deductions, 0, ',', '.') }}</th>
                                                    </tr>
                                                    <tr class="table-success">
                                                        <th class="fs-6">GAJI BERSIH (TAKE HOME PAY)</th>
                                                        <th class="text-end fs-6 text-success">Rp {{ number_format($pay->net_salary, 0, ',', '.') }}</th>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="d-flex justify-content-between text-center mt-4 small pt-3 border-top">
                                                <div>
                                                    <p class="mb-4">Diserahkan Oleh,</p>
                                                    <strong>( Finance / HR )</strong>
                                                </div>
                                                <div>
                                                    <p class="mb-4">Diterima Oleh,</p>
                                                    <strong>( {{ $pay->employee->name }} )</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                                                <i class="bi bi-printer me-1"></i> Cetak Slip Ini
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-cash-coin fs-2 d-block mb-2"></i> Belum ada payroll untuk periode ini. Klik "Generate Payroll Periode Ini" di atas.
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

@extends('layouts.admin')
@section('title', 'Program Cashback Kafe')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-3 fs-5">
                <i class="bi bi-cash-coin"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Program Cashback Pelanggan</h4>
                <small class="text-muted">Kelola skema pengembalian cashback (persen/nominal) ke dalam saldo poin loyalty atau potongan transaksi.</small>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#grantCashbackModal">
                <i class="bi bi-gift me-1"></i> Berikan Cashback Manual
            </button>
            <button type="button" class="btn btn-info text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#createRuleModal">
                <i class="bi bi-plus-circle me-1"></i> Buat Program Cashback
            </button>
        </div>
    </div>

    {{-- KPI STATISTIK CASHBACK --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Program Cashback Aktif</span>
                    <i class="bi bi-sliders fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ number_format($totalRules) }} Program</h3>
                <small class="text-muted">Skema cashback yang berjalan</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Cashback Diberikan</span>
                    <i class="bi bi-cash-stack fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($totalCashbackGiven, 0, ',', '.') }}</h3>
                <small class="text-muted">Akumulasi nilai cashback</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Poin Cashback Member</span>
                    <i class="bi bi-coin fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ number_format($totalCashbackPoints) }} Pts</h3>
                <small class="text-muted">Poin dikreditkan ke member</small>
            </div>
        </div>
    </div>

    {{-- DAFTAR SKEMA / ATURAN CASHBACK --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-card-checklist text-primary me-2"></i>Skema & Program Cashback Berjalan
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($rules as $rule)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border rounded-3 h-100 shadow-none hover-shadow" style="border-left: 4px solid {{ $rule->is_active ? '#0dcaf0' : '#6c757d' }} !important;">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge {{ $rule->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $rule->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border-0 p-1" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                <li>
                                                    <form action="{{ route('admin.promo.cashback.toggle', $rule->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-power me-2"></i> {{ $rule->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.promo.cashback.destroy', $rule->id) }}" method="POST" onsubmit="return confirm('Hapus program cashback {{ $rule->name }}?')">
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

                                    <h5 class="fw-bold mb-1 text-dark">{{ $rule->name }}</h5>
                                    <div class="mb-3">
                                        <span class="fw-bold text-info fs-4">
                                            Cashback {{ $rule->type === 'percentage' ? (int)$rule->value . '%' : 'Rp ' . number_format($rule->value, 0, ',', '.') }}
                                        </span>
                                        <span class="badge bg-light text-dark border ms-1">
                                            Reward: {{ $rule->reward_as === 'points' ? 'Poin Loyalty' : 'Potongan Saldo' }}
                                        </span>
                                    </div>

                                    <div class="p-2 bg-light rounded small mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Min. Belanja:</span>
                                            <strong>Rp {{ number_format($rule->min_spending, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Maks. Cashback:</span>
                                            <strong>{{ $rule->max_cashback ? 'Rp ' . number_format($rule->max_cashback, 0, ',', '.') : 'Tak Terbatas' }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Target Member:</span>
                                            <span class="badge bg-secondary">{{ $rule->tier_eligibility }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">
                        Belum ada skema cashback. Buat program cashback seperti <strong>Cashback 10% Member Gold & Platinum</strong>!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- LOG RIWAYAT CASHBACK PELANGGAN --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-clock-history text-primary me-2"></i>Log Riwayat Pemberian Cashback
                </h6>

                <form action="{{ route('admin.promo.cashback') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/HP member..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Waktu</th>
                            <th>Pelanggan</th>
                            <th>Program Cashback</th>
                            <th>Nominal Cashback</th>
                            <th>Poin Diberikan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cashbackLogs as $cl)
                            <tr>
                                <td class="ps-3"><small class="text-muted">{{ $cl->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="fw-bold">{{ $cl->customer->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $cl->customer->phone ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $cl->rule->name ?? 'Cashback Langsung' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($cl->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-warning">
                                        +{{ number_format($cl->points_rewarded) }} Pts
                                    </span>
                                </td>
                                <td>{{ $cl->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat cashback yang diberikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($cashbackLogs->hasPages())
                <div class="p-3 border-top">
                    {{ $cashbackLogs->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

{{-- MODAL BUAT PROGRAM CASHBACK --}}
<div class="modal fade" id="createRuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2 text-info"></i>Buat Skema Cashback Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promo.cashback.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Program Cashback <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Cashback 10% Weekend" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tipe Cashback <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal Tetap (Rp)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Nilai Cashback <span class="text-danger">*</span></label>
                            <input type="number" name="value" class="form-control" placeholder="Contoh: 10 atau 5000" min="1" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Bentuk Reward <span class="text-danger">*</span></label>
                            <select name="reward_as" class="form-select" required>
                                <option value="points">Poin Loyalty Member</option>
                                <option value="balance_discount">Potongan Transaksi</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Target Membership Tier</label>
                            <select name="tier_eligibility" class="form-select">
                                <option value="All">Semua Member</option>
                                <option value="Silver,Gold,Platinum">Silver, Gold, Platinum</option>
                                <option value="Gold,Platinum">Khusus Gold & Platinum</option>
                                <option value="Platinum">Khusus VIP Platinum</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Min. Belanja (Rp)</label>
                            <input type="number" name="min_spending" class="form-control" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Maks. Cashback (Rp)</label>
                            <input type="number" name="max_cashback" class="form-control" placeholder="Opsional (khusus persen)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white fw-semibold">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL BERIKAN CASHBACK MANUAL --}}
<div class="modal fade" id="grantCashbackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-gift me-2 text-info"></i>Berikan Cashback ke Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promo.cashback.grant') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Member / Pelanggan <span class="text-danger">*</span></label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }}) [{{ $c->tier }}]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Skema Cashback (Opsional)</label>
                        <select name="cashback_rule_id" class="form-select">
                            <option value="">-- Cashback Bebas / Khusus --</option>
                            @foreach($rules as $r)
                                <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->type == 'percentage' ? (int)$r->value . '%' : 'Rp ' . number_format($r->value) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal Cashback (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" placeholder="Contoh: 15000" min="1000" required>
                        <small class="text-muted">Setiap Rp 1.000 cashback akan otomatis menambah 1 Poin Loyalty ke member.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan / Catatan</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Cashback pembelian paket katering">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Berikan Cashback
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

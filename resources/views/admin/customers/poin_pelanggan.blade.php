@extends('layouts.admin')
@section('title', 'Poin Pelanggan & Loyalty Rewards')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-coin"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Poin Pelanggan & Loyalty Rewards</h4>
                <small class="text-muted">Kelola akumulasi poin member, penukaran hadiah, dan log mutasi loyalty kafe.</small>
            </div>
        </div>

        <button type="button" class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#adjustPointModal">
            <i class="bi bi-plus-slash-minus me-1"></i> Penyesuaian Poin Manual
        </button>
    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Poin Aktif Beredar</span>
                    <i class="bi bi-wallet2 fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ number_format($totalCirculating) }} Pts</h3>
                <small class="text-muted">Saldo poin milik seluruh member</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Poin Diperoleh</span>
                    <i class="bi bi-arrow-up-circle fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ number_format($totalEarned) }} Pts</h3>
                <small class="text-muted">Diberikan dari belanja pesanan</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Poin Telah Ditukarkan</span>
                    <i class="bi bi-arrow-down-circle fs-4 text-danger"></i>
                </div>
                <h3 class="mb-0 fw-bold text-danger">{{ number_format($totalRedeemed) }} Pts</h3>
                <small class="text-muted">Klaim reward/promo</small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- TOP 5 LEADERBOARD POIN --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-trophy-fill text-warning me-2"></i>Top 5 Pemilik Poin Terbanyak
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($topMembers as $index => $tm)
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge rounded-circle p-2 {{ $index == 0 ? 'bg-warning text-dark' : ($index == 1 ? 'bg-secondary text-white' : ($index == 2 ? 'bg-dark text-white' : 'bg-light text-muted border')) }}" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <a href="{{ route('admin.customers.show', $tm->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                            {{ $tm->name }}
                                        </a>
                                        <small class="text-muted">{{ $tm->tier }} • {{ $tm->phone }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold text-warning fs-6">
                                    {{ number_format($tm->points) }} Pts
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">Belum ada data member.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- RIWAYAT LOG MUTASI POIN --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Seluruh Mutasi Poin</h6>

                        <form action="{{ route('admin.customers.poin-pelanggan') }}" method="GET" class="d-flex gap-2">
                            <select name="type" class="form-select form-select-sm">
                                <option value="">Semua Tipe</option>
                                <option value="earned" {{ request('type') == 'earned' ? 'selected' : '' }}>Perolehan (Earned)</option>
                                <option value="redeemed" {{ request('type') == 'redeemed' ? 'selected' : '' }}>Penukaran (Redeemed)</option>
                                <option value="adjusted" {{ request('type') == 'adjusted' ? 'selected' : '' }}>Penyesuaian Manual</option>
                            </select>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/HP..." value="{{ request('search') }}">
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
                                    <th>Tipe Mutasi</th>
                                    <th>Jumlah Poin</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pointLogs as $log)
                                    <tr>
                                        <td class="ps-3">
                                            <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            @if($log->customer)
                                                <a href="{{ route('admin.customers.show', $log->customer->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $log->customer->name }}
                                                </a>
                                                <div class="small text-muted">{{ $log->customer->phone }}</div>
                                            @else
                                                <span class="text-muted">Pelanggan Dihapus</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->type === 'earned')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Perolehan</span>
                                            @elseif($log->type === 'redeemed')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Penukaran</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">Koreksi Manual</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold fs-6 {{ $log->points > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $log->points > 0 ? '+' : '' }}{{ number_format($log->points) }} Pts
                                            </span>
                                        </td>
                                        <td>{{ $log->description ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada log mutasi poin yang tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pointLogs->hasPages())
                        <div class="p-3 border-top">
                            {{ $pointLogs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL PENYESUAIAN POIN CEPAT --}}
<div class="modal fade" id="adjustPointModal" tabindex="-1" aria-labelledby="adjustPointModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="adjustPointModalLabel">
                    <i class="bi bi-coin text-warning me-2"></i>Penyesuaian Poin Member
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adjustPointForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Pelanggan <span class="text-danger">*</span></label>
                        <select id="customerSelect" class="form-select" required onchange="updateFormAction(this.value)">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customersList as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->name }} ({{ $c->phone }}) - Saldo: {{ $c->points }} Poin
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipe Penyesuaian <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="earned">Tambah Poin (Reward / Bonus Loyalty)</option>
                            <option value="redeemed">Tukar Poin (Redeem Hadiah / Diskon)</option>
                            <option value="adjusted">Koreksi Poin (Penyesuaian Saldo)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Poin <span class="text-danger">*</span></label>
                        <input type="number" name="points" class="form-control" placeholder="Contoh: 50" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan / Alasan <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control" placeholder="Contoh: Bonus Ulang Tahun / Redeem Tumbler" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Simpan Mutasi Poin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateFormAction(customerId) {
    const form = document.getElementById('adjustPointForm');
    if (customerId) {
        form.action = `/admin/customers/${customerId}/adjust-points`;
    } else {
        form.action = '';
    }
}
</script>
@endpush

@endsection

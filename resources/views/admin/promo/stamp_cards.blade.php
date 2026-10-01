@extends('layouts.admin')
@section('title', 'Stamp Card Digital')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Stamp Card Digital (Loyalty Punch Card)</h4>
                <small class="text-muted">Kartu stempel digital pelanggan: kumpulkan stempel setiap pembelian untuk mendapatkan hadiah gratis.</small>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editProgramModal">
                <i class="bi bi-gear me-1"></i> Pengaturan Target Stamp
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStampModal">
                <i class="bi bi-patch-plus-fill me-1"></i> Beri Stamp Pelanggan
            </button>
        </div>
    </div>

    {{-- KPI STATISTIK STAMP --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Kartu Stamp Member Aktif</span>
                    <i class="bi bi-card-checklist fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold">{{ number_format($totalActiveCards) }}</h3>
                <small class="text-muted">Pelanggan sedang mengumpulkan</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Stamp Diberikan</span>
                    <i class="bi bi-patch-check-fill fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalStampsIssued) }} Stempel</h3>
                <small class="text-muted">Akumulasi stempel tercatat</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Kartu Selesai / Reward</span>
                    <i class="bi bi-trophy-fill fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ number_format($totalCompletedRewards) }}x Klaim</h3>
                <small class="text-muted">Reward stamp penuh yang berhasil diraih</small>
            </div>
        </div>
    </div>

    {{-- PROGRAM RULES BANNER --}}
    <div class="alert alert-light border shadow-sm d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-cup-hot-fill fs-1 text-success"></i>
            <div>
                <h6 class="fw-bold mb-0">{{ $program->name }}</h6>
                <small class="text-muted">
                    Kumpulkan <strong>{{ $program->target_stamps }} Stempel</strong> (min. belanja Rp {{ number_format($program->min_purchase, 0, ',', '.') }} per stempel) &rarr; Hadiah: <strong>{{ $program->reward_description }}</strong>
                </small>
            </div>
        </div>
        <span class="badge bg-success px-3 py-2">Program Berjalan</span>
    </div>

    {{-- DAFTAR KARTU STAMP PELANGGAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-people-fill text-success me-2"></i>Progress Kartu Stamp Pelanggan
                </h6>

                <form action="{{ route('admin.promo.stamp-cards') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/HP member..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                @forelse($customerStamps as $cs)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border rounded-3 h-100 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $cs->customer->name }}</h6>
                                        <small class="text-muted">{{ $cs->customer->phone }} • {{ $cs->customer->tier }}</small>
                                    </div>
                                    @if($cs->total_completed_cards > 0)
                                        <span class="badge bg-warning text-dark animate-pulse">
                                            🎁 {{ $cs->total_completed_cards }} Hadiah Siap Klaim!
                                        </span>
                                    @endif
                                </div>

                                {{-- VISUAL 10-SLOT STAMP CARD --}}
                                <div class="p-3 bg-light rounded-3 my-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="fw-bold text-muted">Progres: {{ $cs->current_stamps }} / {{ $program->target_stamps }}</small>
                                        <small class="text-success fw-semibold">
                                            {{ round(($cs->current_stamps / $program->target_stamps) * 100) }}%
                                        </small>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        @for($i = 1; $i <= $program->target_stamps; $i++)
                                            @if($i <= $cs->current_stamps)
                                                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; background: #198754; color: #fff;" title="Stamp ke-{{ $i }} Terisi!">
                                                    <i class="bi bi-cup-hot-fill fs-6"></i>
                                                </div>
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center border" style="width: 38px; height: 38px; background: #fff; color: #adb5bd;" title="Slot {{ $i }}">
                                                    <span class="small fw-semibold">{{ $i }}</span>
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <small class="text-muted">Total Selesai: <strong>{{ $cs->total_completed_cards }}x</strong></small>
                                    <div class="d-flex gap-1">
                                        @if($cs->total_completed_cards > 0)
                                            <form action="{{ route('admin.promo.stamp-cards.redeem', $cs->id) }}" method="POST" onsubmit="return confirm('Klaim reward kartu stamp untuk {{ $cs->customer->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold">
                                                    <i class="bi bi-gift-fill me-1"></i> Klaim Reward
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="openAddStampFor('{{ $cs->customer_id }}', '{{ $cs->customer->name }}')">
                                            <i class="bi bi-plus-lg"></i> Stamp
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="bi bi-grid-3x3-gap display-4 d-block mb-3 opacity-25"></i>
                        Belum ada pelanggan yang memiliki kartu stamp. Klik tombol <strong>Beri Stamp Pelanggan</strong> untuk memulai!
                    </div>
                @endforelse
            </div>

            @if($customerStamps->hasPages())
                <div class="mt-4">
                    {{ $customerStamps->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- RIWAYAT LOG STAMP --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i>Log Riwayat Pemberian & Penukaran Stamp
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Waktu</th>
                            <th>Pelanggan</th>
                            <th>Aksi</th>
                            <th>Jumlah Stempel</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stampLogs as $sl)
                            <tr>
                                <td class="ps-3"><small class="text-muted">{{ $sl->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="fw-bold">{{ $sl->customer->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $sl->customer->phone ?? '' }}</small>
                                </td>
                                <td>
                                    @if($sl->action === 'add')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">Beri Stamp</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning text-dark border border-warning">Klaim Hadiah</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold {{ $sl->stamps > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $sl->stamps > 0 ? '+' : '' }}{{ $sl->stamps }} Stamp
                                    </span>
                                </td>
                                <td>{{ $sl->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat aktivitas stempel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL BERI STAMP --}}
<div class="modal fade" id="addStampModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-patch-plus-fill me-2 text-success"></i>Beri Stamp ke Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promo.stamp-cards.add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Pelanggan <span class="text-danger">*</span></label>
                        <select name="customer_id" id="modalCustomerSelect" class="form-select" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }}) [{{ $c->tier }}]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Stamp <span class="text-danger">*</span></label>
                        <input type="number" name="stamps" class="form-control" value="1" min="1" max="10" required>
                        <small class="text-muted">Standar 1 stamp per minuman/kunjungan.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan (Opsional)</label>
                        <input type="text" name="description" class="form-control" placeholder="Contoh: Pembelian 1 Cup Caramel Latte">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Simpan Stamp
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL PENGATURAN PROGRAM STAMP --}}
<div class="modal fade" id="editProgramModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-gear me-2 text-primary"></i>Pengaturan Program Stamp Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promo.stamp-cards.program.update', $program->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Program Stamp <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $program->name) }}" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Target Stamp Penuh <span class="text-danger">*</span></label>
                            <input type="number" name="target_stamps" class="form-control" value="{{ old('target_stamps', $program->target_stamps) }}" min="3" max="20" required>
                            <small class="text-muted">Misal: 10 stamp</small>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Min. Belanja per Stamp</label>
                            <input type="number" name="min_purchase" class="form-control" value="{{ old('min_purchase', $program->min_purchase) }}" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Hadiah Kartu Penuh <span class="text-danger">*</span></label>
                        <input type="text" name="reward_description" class="form-control" value="{{ old('reward_description', $program->reward_description) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openAddStampFor(customerId, customerName) {
    const select = document.getElementById('modalCustomerSelect');
    if (select) {
        select.value = customerId;
    }
    const modal = new bootstrap.Modal(document.getElementById('addStampModal'));
    modal.show();
}
</script>
@endpush

@endsection

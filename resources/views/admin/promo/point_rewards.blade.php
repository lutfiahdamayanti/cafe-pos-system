@extends('layouts.admin')
@section('title', 'Point Reward & Katalog Hadiah')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3 fs-5">
                <i class="bi bi-gift-fill"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Point Reward (Katalog Penukaran Hadiah)</h4>
                <small class="text-muted">Kelola katalog produk, merchandise, dan voucher diskon yang dapat ditukarkan member dengan poin loyalitas.</small>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#redeemRewardModal">
                <i class="bi bi-arrow-repeat me-1"></i> Tukarkan Poin Member
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRewardModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Item Reward
            </button>
        </div>
    </div>

    {{-- KPI STATISTIK POINT REWARD --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Item Reward</span>
                    <i class="bi bi-box-seam fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold">{{ number_format($totalItems) }}</h3>
                <small class="text-muted">Variasi hadiah di katalog</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Klaim Hadiah</span>
                    <i class="bi bi-bag-check fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ number_format($totalClaims) }}x</h3>
                <small class="text-muted">Hadiah berhasil ditukarkan</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Poin Ditukarkan</span>
                    <i class="bi bi-coin fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ number_format($totalPointsSpent) }} Pts</h3>
                <small class="text-muted">Total poin yang diredeem</small>
            </div>
        </div>
    </div>

    {{-- KATALOG ITEM REWARD --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Katalog Hadiah Point Reward Aktif
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($rewards as $r)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border rounded-3 h-100 shadow-none hover-shadow">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge {{ $r->reward_type === 'product' ? 'bg-success' : ($r->reward_type === 'merchandise' ? 'bg-primary' : 'bg-info text-dark') }}">
                                            {{ ucfirst($r->reward_type) }}
                                        </span>
                                        <span class="badge {{ $r->stock > 0 ? 'bg-light text-dark border' : 'bg-danger' }}">
                                            Stok: {{ $r->stock }}
                                        </span>
                                    </div>

                                    <div class="d-flex gap-3 align-items-center mb-3">
                                        @if($r->image)
                                            <img src="{{ asset('storage/' . $r->image) }}" class="rounded-3 object-fit-cover" style="width: 70px; height: 70px;">
                                        @else
                                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border" style="width: 70px; height: 70px;">
                                                <i class="bi bi-gift fs-2 text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">{{ $r->name }}</h6>
                                            <span class="fw-bold text-warning fs-5">
                                                <i class="bi bi-coin me-1"></i>{{ number_format($r->points_required) }} Pts
                                            </span>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3">{{ $r->description ?? 'Tukarkan poin loyalty Anda untuk mendapatkan reward spesial ini.' }}</p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="badge {{ $r->is_active ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary' }}">
                                        {{ $r->is_active ? 'Tersedia' : 'Nonaktif' }}
                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRewardModal{{ $r->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.promo.point-rewards.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus item reward {{ $r->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL EDIT REWARD --}}
                    <div class="modal fade" id="editRewardModal{{ $r->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Item Reward: {{ $r->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.promo.point-rewards.update', $r->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nama Item Reward <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $r->name) }}" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-semibold">Poin Diperlukan <span class="text-danger">*</span></label>
                                                <input type="number" name="points_required" class="form-control" value="{{ old('points_required', $r->points_required) }}" min="1" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold">Stok Hadiah <span class="text-danger">*</span></label>
                                                <input type="number" name="stock" class="form-control" value="{{ old('stock', $r->stock) }}" min="0" required>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-semibold">Tipe Hadiah</label>
                                                <select name="reward_type" class="form-select" required>
                                                    <option value="product" {{ $r->reward_type == 'product' ? 'selected' : '' }}>Produk Minuman / Makanan</option>
                                                    <option value="discount" {{ $r->reward_type == 'discount' ? 'selected' : '' }}>Voucher Diskon</option>
                                                    <option value="merchandise" {{ $r->reward_type == 'merchandise' ? 'selected' : '' }}>Merchandise / Souvenir</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold">Status</label>
                                                <select name="is_active" class="form-select">
                                                    <option value="1" {{ $r->is_active ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ !$r->is_active ? 'selected' : '' }}>Nonaktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Ganti Gambar / Foto (Opsional)</label>
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Deskripsi Hadiah</label>
                                            <textarea name="description" class="form-control" rows="2">{{ old('description', $r->description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">
                        Belum ada item hadiah di katalog. Tambahkan reward seperti Free Coffee, Tumbler, atau Voucher Diskon!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- RIWAYAT KLAIM POINT REWARD --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i>Log Riwayat Klaim Penukaran Hadiah
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode Klaim</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Item Hadiah</th>
                            <th>Poin Digunakan</th>
                            <th>Status Klaim</th>
                            <th class="text-end pe-3">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $c)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold font-monospace text-primary fs-6">{{ $c->claim_code }}</span>
                                </td>
                                <td><small class="text-muted">{{ $c->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="fw-semibold">{{ $c->customer->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $c->customer->phone ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border p-2">
                                        <i class="bi bi-gift text-primary me-1"></i>{{ $c->reward->name ?? 'Reward Dihapus' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger">-{{ number_format($c->points_spent) }} Pts</span>
                                </td>
                                <td>
                                    @php
                                        $claimBadge = match($c->status) {
                                            'used' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-warning text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $claimBadge }}">
                                        {{ ucfirst($c->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    @if($c->status === 'claimed')
                                        <form action="{{ route('admin.promo.point-rewards.claim-status', $c->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="used">
                                            <button type="submit" class="btn btn-sm btn-success" title="Tandai Sudah Diambil / Digunakan">
                                                <i class="bi bi-check-lg me-1"></i> Selesai Ambil
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Tuntas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat penukaran hadiah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($claims->hasPages())
                <div class="p-3 border-top">
                    {{ $claims->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

{{-- MODAL TAMBAH REWARD --}}
<div class="modal fade" id="createRewardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Item Hadiah Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promo.point-rewards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Item Hadiah <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Free Iced Americano / Tumbler Stainless" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Poin Diperlukan <span class="text-danger">*</span></label>
                            <input type="number" name="points_required" class="form-control" placeholder="Contoh: 30" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Jumlah Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" placeholder="Contoh: 50" min="0" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tipe Hadiah <span class="text-danger">*</span></label>
                            <select name="reward_type" class="form-select" required>
                                <option value="product">Produk Minuman / Makanan</option>
                                <option value="discount">Voucher Potongan Diskon</option>
                                <option value="merchandise">Merchandise / Hadiah Fisik</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Nilai Diskon (Jika Voucher)</label>
                            <input type="number" name="discount_value" class="form-control" placeholder="Contoh: 25000">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto / Gambar Hadiah (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi / Syarat Penukaran</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Keterangan penukaran hadiah..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambahkan ke Katalog</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TUKARKAN POIN MEMBER (REDEEM) --}}
<div class="modal fade" id="redeemRewardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-arrow-repeat me-2 text-warning"></i>Proses Penukaran Poin Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promo.point-rewards.claim') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Member / Pelanggan <span class="text-danger">*</span></label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->name }} ({{ $c->phone }}) — Saldo: {{ $c->points }} Poin [{{ $c->tier }}]
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Item Hadiah Yang Ditukar <span class="text-danger">*</span></label>
                        <select name="point_reward_id" class="form-select" required>
                            <option value="">-- Pilih Hadiah --</option>
                            @foreach($rewards as $r)
                                <option value="{{ $r->id }}" {{ $r->stock < 1 ? 'disabled' : '' }}>
                                    {{ $r->name }} — Butuh {{ $r->points_required }} Pts (Stok: {{ $r->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan / Keterangan (Opsional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Klaim langsung di kasir">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Konfirmasi Penukaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

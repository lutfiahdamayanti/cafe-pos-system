@extends('layouts.admin')
@section('title', 'Voucher & Kupon Promo')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-ticket-perforated-fill"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Voucher & Kupon Promo</h4>
                <small class="text-muted">Kelola voucher diskon persen/nominal, batas kuota, syarat minimal belanja, dan masa berlaku.</small>
            </div>
        </div>

        <button type="button" class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#createVoucherModal">
            <i class="bi bi-plus-circle me-1"></i> Buat Voucher Baru
        </button>
    </div>

    {{-- KPI STATISTIK VOUCHER --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Voucher</span>
                    <i class="bi bi-ticket-detailed fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold">{{ number_format($totalVouchers) }}</h3>
                <small class="text-muted">Kupon terdaftar di sistem</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Voucher Aktif</span>
                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ number_format($activeVouchers) }}</h3>
                <small class="text-muted">Sedang berlaku saat ini</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Pemakaian</span>
                    <i class="bi bi-cart-check fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalUsedCount) }}x</h3>
                <small class="text-muted">Klaim transaksi oleh pelanggan</small>
            </div>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.promo.vouchers') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kode voucher atau nama promo..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">Semua Tipe Diskon</option>
                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Sedang Aktif</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Dinonaktifkan</option>
                    </select>
                </div>

                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-warning text-dark w-100" title="Filter Voucher">
                        <i class="bi bi-funnel"></i>
                    </button>
                    @if(request()->anyFilled(['search', 'type', 'status']))
                        <a href="{{ route('admin.promo.vouchers') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- DAFTAR KARTU VOUCHER --}}
    <div class="row g-3 mb-4">
        @forelse($vouchers as $v)
            @php
                $statusBadge = $v->status_badge;
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100 position-relative overflow-hidden" style="border-left: 5px solid {{ $v->is_active ? '#ffc107' : '#6c757d' }} !important;">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge {{ $statusBadge['class'] }} px-2 py-1">
                                    {{ $statusBadge['label'] }}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0 p-1" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editVoucherModal{{ $v->id }}">
                                                <i class="bi bi-pencil me-2 text-primary"></i> Edit Voucher
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.promo.vouchers.toggle', $v->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-power me-2 text-warning"></i> {{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.promo.vouchers.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Hapus voucher {{ $v->code }}?')">
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

                            <h5 class="fw-bold mb-1 text-dark">{{ $v->name }}</h5>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-dark font-monospace fs-6 px-3 py-2 text-warning tracking-wider">
                                    {{ $v->code }}
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ $v->code }}'); alert('Kode voucher {{ $v->code }} disalin!');" title="Salin Kode">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>

                            <div class="p-2 bg-light rounded mb-3 small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Besaran Diskon:</span>
                                    <strong class="text-success">
                                        @if($v->type === 'percentage')
                                            {{ (int)$v->discount_value }}%
                                            @if($v->max_discount) (Maks Rp {{ number_format($v->max_discount, 0, ',', '.') }}) @endif
                                        @else
                                            Rp {{ number_format($v->discount_value, 0, ',', '.') }}
                                        @endif
                                    </strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Min. Belanja:</span>
                                    <strong>Rp {{ number_format($v->min_spending, 0, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Masa Berlaku:</span>
                                    <span>{{ $v->start_date->format('d/m/Y') }} - {{ $v->end_date->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center small text-muted mb-1">
                                <span>Kuota Pemakaian:</span>
                                <span>{{ $v->used_count }} / {{ $v->usage_limit ?? '∞' }} terpakai</span>
                            </div>
                            @if($v->usage_limit)
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: {{ min(100, ($v->used_count / $v->usage_limit) * 100) }}%;"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL EDIT VOUCHER --}}
            <div class="modal fade" id="editVoucherModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold"><i class="bi bi-pencil me-2 text-primary"></i>Edit Voucher: {{ $v->code }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('admin.promo.vouchers.update', $v->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Kode Voucher <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control text-uppercase font-monospace" value="{{ old('code', $v->code) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Promo / Keterangan <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $v->name) }}" required>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Tipe Diskon</label>
                                        <select name="type" class="form-select" required>
                                            <option value="percentage" {{ $v->type == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                            <option value="fixed" {{ $v->type == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Nilai Diskon</label>
                                        <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value', $v->discount_value) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Maks. Diskon (Rp)</label>
                                        <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $v->max_discount) }}" placeholder="Opsional (khusus persen)">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Min. Belanja (Rp)</label>
                                        <input type="number" name="min_spending" class="form-control" value="{{ old('min_spending', $v->min_spending) }}" min="0">
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $v->start_date->format('Y-m-d')) }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Tanggal Berakhir</label>
                                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $v->end_date->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Batas Kuota Pemakaian (Opsional)</label>
                                    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $v->usage_limit) }}" placeholder="Kosongkan jika tak terbatas">
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
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-ticket-perforated display-4 d-block mb-3 opacity-25"></i>
                Belum ada voucher promo yang terdaftar. Buat voucher pertama sekarang!
            </div>
        @endforelse
    </div>

    @if($vouchers->hasPages())
        <div class="mb-4">
            {{ $vouchers->links() }}
        </div>
    @endif

    {{-- RIWAYAT PENGGUNAAN VOUCHER TERAKHIR --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Penggunaan Voucher Terkini
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Waktu</th>
                            <th>Kode Voucher</th>
                            <th>Pelanggan</th>
                            <th>No Order</th>
                            <th class="text-end pe-3">Nominal Potongan Diskon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsages as $u)
                            <tr>
                                <td class="ps-3"><small class="text-muted">{{ $u->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <span class="badge bg-dark text-warning font-monospace">{{ $u->voucher->code ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $u->customer->name ?? 'Tamu / Umum' }}</span>
                                </td>
                                <td>
                                    <span class="font-monospace text-muted">{{ $u->order->order_number ?? '-' }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <span class="fw-bold text-success">- Rp {{ number_format($u->discount_amount, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat pemakaian voucher transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL TAMBAH VOUCHER BARU --}}
<div class="modal fade" id="createVoucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2 text-warning"></i>Buat Voucher Promo Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promo.vouchers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Voucher <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase font-monospace" placeholder="Contoh: KOPIHEMAT20" required>
                        <small class="text-muted">Kode unik yang diinput saat checkout.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Promo <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Diskon Kopi 20% Gajian" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tipe Diskon <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal Tetap (Rp)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Nilai Diskon <span class="text-danger">*</span></label>
                            <input type="number" name="discount_value" class="form-control" placeholder="Contoh: 20 atau 10000" min="1" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Maks. Diskon (Rp)</label>
                            <input type="number" name="max_discount" class="form-control" placeholder="Contoh: 25000">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Min. Belanja (Rp)</label>
                            <input type="number" name="min_spending" class="form-control" placeholder="Contoh: 50000" value="0">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tanggal Berakhir <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Batas Kuota Pemakaian (Opsional)</label>
                        <input type="number" name="usage_limit" class="form-control" placeholder="Contoh: 100 (kosongkan jika tak terbatas)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi / Syarat Ketentuan</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Catatan syarat ketentuan voucher..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-save me-1"></i> Terbitkan Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

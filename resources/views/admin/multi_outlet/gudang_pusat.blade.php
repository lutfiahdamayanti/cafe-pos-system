@extends('layouts.admin')
@section('title', 'Multi-Outlet: Gudang Pusat')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger bg-opacity-10 text-danger p-2 rounded-3 fs-5">
                <i class="bi bi-box-seam-fill text-danger"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Gudang Pusat (Central Warehouse & Supply Hub)</h4>
                <small class="text-muted">Inventori terpusat bahan baku, manajemen safety stock, nilai aset persediaan, dan distribusi ke gerai cabang.</small>
            </div>
        </div>

        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTambahItemGudang">
            <i class="bi bi-plus-circle me-1"></i> Tambah Item Inventori
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
            <div class="dashboard-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Varian Barang</span>
                    <i class="bi bi-boxes fs-4 text-danger"></i>
                </div>
                <h3 class="mb-0 fw-bold text-danger">{{ $totalItems }} Item</h3>
                <small class="text-muted">Katalog bahan baku</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Nilai Aset Gudang</span>
                    <i class="bi bi-cash-coin fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</h3>
                <small class="text-muted">Valuasi stok berjalan</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Stok Kritis / Menipis</span>
                    <i class="bi bi-exclamation-triangle fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $lowStockCount }} Item</h3>
                <small class="text-muted">Perlu reorder supplier</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Kategori Bahan</span>
                    <i class="bi bi-tags fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $categories->count() }} Kategori</h3>
                <small class="text-muted">Pengelompokan barang</small>
            </div>
        </div>
    </div>

    {{-- FILTER & PENCARIAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.multi-outlet.gudang-pusat') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama bahan, kode SKU, atau supplier..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="filter" class="form-select">
                        <option value="">Semua Stok</option>
                        <option value="low_stock" {{ request('filter') === 'low_stock' ? 'selected' : '' }}>⚠️ Stok Kritis Saja</option>
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

    {{-- TABEL INVENTORI GUDANG PUSAT --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-danger"></i> Daftar Stok Bahan Baku di Gudang Pusat</h6>
            <span class="badge bg-light text-dark">{{ $items->count() }} Bahan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">SKU & Nama Bahan</th>
                            <th>Kategori</th>
                            <th>Stok Tersedia</th>
                            <th>Batas Min. Stok</th>
                            <th>Harga Beli / Satuan</th>
                            <th>Total Nilai Stok</th>
                            <th>Supplier Utama</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $stock)
                            @php
                                $isLow = $stock->stock_quantity <= $stock->min_stock;
                                $itemAssetVal = $stock->stock_quantity * $stock->unit_cost;
                            @endphp
                            <tr class="{{ $isLow ? 'table-warning bg-opacity-10' : '' }}">
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 {{ $isLow ? 'bg-warning text-dark' : 'bg-light text-primary' }} p-2 me-2">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $stock->item_name }}</span>
                                            <span class="badge bg-secondary font-monospace">{{ $stock->sku }}</span>
                                            @if($isLow)
                                                <span class="badge bg-danger">⚠️ Stok Kritis</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $stock->category }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 {{ $isLow ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($stock->stock_quantity, 2) }} {{ $stock->unit }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ number_format($stock->min_stock, 2) }} {{ $stock->unit }}</span>
                                </td>
                                <td>
                                    <span>Rp {{ number_format($stock->unit_cost, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">Rp {{ number_format($itemAssetVal, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <small class="text-dark d-block">{{ $stock->supplier ?? '-' }}</small>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRestok{{ $stock->id }}" title="Restok Bahan">
                                            <i class="bi bi-plus-lg me-1"></i> Restok
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $stock->id }}" title="Edit Item">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.multi-outlet.gudang-pusat.destroy', $stock) }}" method="POST" onsubmit="return confirm('Hapus item ini dari gudang pusat?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL RESTOK ITEM --}}
                            <div class="modal fade" id="modalRestok{{ $stock->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.multi-outlet.gudang-pusat.restock', $stock) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-success me-2"></i> Restok Bahan Masuk: {{ $stock->item_name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="p-2 mb-3 bg-light rounded small">
                                                    Stok Saat Ini: <strong>{{ number_format($stock->stock_quantity, 2) }} {{ $stock->unit }}</strong>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Jumlah Bahan Masuk ({{ $stock->unit }}) <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" name="added_quantity" class="form-control" placeholder="Contoh: 50" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Harga Beli Satuan Baru (Rp)</label>
                                                    <input type="number" name="unit_cost" class="form-control" value="{{ (int)$stock->unit_cost }}">
                                                    <small class="text-muted">Biarkan jika harga beli tetap sama.</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Supplier</label>
                                                    <input type="text" name="supplier" class="form-control" value="{{ $stock->supplier }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success"><i class="bi bi-check2 me-1"></i> Konfirmasi Restok</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- MODAL EDIT ITEM --}}
                            <div class="modal fade" id="modalEdit{{ $stock->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.multi-outlet.gudang-pusat.update', $stock) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Data Item Gudang</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label small fw-bold">Nama Bahan Baku</label>
                                                        <input type="text" name="item_name" class="form-control" value="{{ $stock->item_name }}" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-bold">Kode SKU</label>
                                                        <input type="text" name="sku" class="form-control" value="{{ $stock->sku }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Kategori</label>
                                                        <input type="text" name="category" class="form-control" value="{{ $stock->category }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Satuan</label>
                                                        <input type="text" name="unit" class="form-control" value="{{ $stock->unit }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Stok Saat Ini</label>
                                                        <input type="number" step="0.01" name="stock_quantity" class="form-control" value="{{ $stock->stock_quantity }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Batas Min. Stok</label>
                                                        <input type="number" step="0.01" name="min_stock" class="form-control" value="{{ $stock->min_stock }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Harga Beli / Satuan (Rp)</label>
                                                        <input type="number" name="unit_cost" class="form-control" value="{{ (int)$stock->unit_cost }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Supplier</label>
                                                        <input type="text" name="supplier" class="form-control" value="{{ $stock->supplier }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-bold">Catatan / Spesifikasi</label>
                                                        <textarea name="notes" class="form-control" rows="2">{{ $stock->notes }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-box-seam fs-2 d-block mb-2"></i> Belum ada bahan baku di Gudang Pusat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- LOG PENGIRIMAN KELUAR TERAKHIR DARI GUDANG PUSAT --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Pengiriman Terakhir dari Gudang Pusat ke Cabang</h6>
            <a href="{{ route('admin.multi-outlet.transfer-stok') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua Transfer <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No. Transfer</th>
                            <th>Tujuan Gerai</th>
                            <th>Tanggal</th>
                            <th>Item Dikirim</th>
                            <th class="pe-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransfers as $rt)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold font-monospace">{{ $rt->transfer_number }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-primary">{{ $rt->toOutlet->name }}</span>
                                </td>
                                <td>{{ $rt->transfer_date ? $rt->transfer_date->format('d M Y') : '-' }}</td>
                                <td>
                                    <small>{{ $rt->items->pluck('item_name')->join(', ') }}</small>
                                </td>
                                <td class="pe-3">
                                    <span class="badge {{ $rt->status === 'Completed' ? 'bg-success' : ($rt->status === 'In Transit' ? 'bg-info text-dark' : 'bg-warning text-dark') }}">
                                        {{ $rt->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Belum ada riwayat pengiriman dari Gudang Pusat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL TAMBAH ITEM GUDANG PUSAT --}}
<div class="modal fade" id="modalTambahItemGudang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.multi-outlet.gudang-pusat.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-danger me-2"></i> Tambah Item ke Gudang Pusat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nama Bahan Baku <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" class="form-control" placeholder="Contoh: Biji Kopi Flores Bajawa" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control" placeholder="WH-COF-003" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="category" class="form-control" placeholder="Bahan Baku Kopi / Dairy / Kemasan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="unit" class="form-control" placeholder="kg, liter, kotak, pcs, pack" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="stock_quantity" class="form-control" placeholder="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Batas Min. Stok (Safety Stock) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="min_stock" class="form-control" placeholder="20" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Harga Beli / Satuan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="unit_cost" class="form-control" placeholder="120000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Supplier</label>
                            <input type="text" name="supplier" class="form-control" placeholder="PT Supplier Sejahtera">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Catatan / Spesifikasi Penyimpanan</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Suhu ruangan sejuk, jauhkan dari sinar matahari langsung..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-check2 me-1"></i> Simpan ke Inventori</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

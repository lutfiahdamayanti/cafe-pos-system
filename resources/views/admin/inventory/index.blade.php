@extends('layouts.admin')
@section('title', 'Inventory Bahan Baku')
@section('content')

<div class="container-fluid inventory-page">
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="inventory-stat-card inventory-stat-success">
                <div class="inventory-stat-content">
                    <div class="inventory-stat-label">Total Bahan</div>
                    <div class="inventory-stat-value">{{ $totalBahan }}</div>
                    <div class="inventory-stat-description">Bahan terdaftar</div>
                </div>
                <div class="inventory-stat-icon inventory-icon-success">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="inventory-stat-card inventory-stat-warning">
                <div class="inventory-stat-content">
                    <div class="inventory-stat-label">Stok Menipis</div>
                    <div class="inventory-stat-value">{{ $stokMenipis }}</div>
                    <div class="inventory-stat-description">Bahan perlu diperhatikan</div>
                </div>
                <div class="inventory-stat-icon inventory-icon-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="inventory-stat-card inventory-stat-danger">
                <div class="inventory-stat-content">
                    <div class="inventory-stat-label">Stok Habis</div>
                    <div class="inventory-stat-value">{{ $stokHabis }}</div>
                    <div class="inventory-stat-description">Stok saat ini kosong</div>
                </div>
                <div class="inventory-stat-icon inventory-icon-danger">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cari Bahan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari nama bahan...">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status Stok</label>
                    <select class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aman">Aman</option>
                        <option value="menipis">Menipis</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="mb-1 fw-bold">Daftar Bahan Baku</h5>
                    <small class="text-muted">Daftar bahan yang tersedia di inventory cafe.</small>
                </div>
                <a href="{{ route('admin.inventory.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Bahan
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th><th>Bahan Baku</th><th>Kategori</th><th>Satuan</th>
                            <th>Stok</th><th>Minimum Stok</th><th>Status</th><th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td>{{ $item->category ?? '-' }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ number_format($item->stock, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->minimum_stock, 2, ',', '.') }}</td>
                                <td>
                                    @if($item->stock <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($item->stock <= $item->minimum_stock)
                                        <span class="badge bg-warning text-dark">Menipis</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.inventory.edit', $item) }}"
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.inventory.destroy', $item) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus bahan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="mb-3"><i class="bi bi-box-seam text-muted fs-1"></i></div>
                                    <h6 class="fw-bold">Belum ada bahan baku</h6>
                                    <p class="text-muted small mb-3">
                                        Tambahkan bahan baku pertama untuk mulai mengelola inventory cafe.
                                    </p>
                                    <a href="{{ route('admin.inventory.create') }}" class="btn btn-success btn-sm">
                                        <i class="bi bi-plus-lg"></i> Tambah Bahan
                                    </a>
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
@extends('layouts.admin')
@section('title', 'Alert Stok Menipis')
@section('content')

<div class="container-fluid inventory-page">
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="inventory-stat-card inventory-stat-warning h-100">
                <div class="inventory-stat-content">
                    <div class="inventory-stat-label">Stok Menipis</div>
                    <div class="inventory-stat-value">{{ $totalMenipis }}</div>
                    <div class="inventory-stat-description">Stok masih tersedia tetapi sudah mencapai batas minimum.</div>
                </div>
                <div class="inventory-stat-icon inventory-icon-warning"><i class="bi bi-exclamation-triangle-fill"></i></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="inventory-stat-card inventory-stat-danger h-100">
                <div class="inventory-stat-content">
                    <div class="inventory-stat-label">Stok Habis</div>
                    <div class="inventory-stat-value">{{ $totalHabis }}</div>
                    <div class="inventory-stat-description">Bahan baku yang saat ini sudah tidak tersedia.</div>
                </div>
                <div class="inventory-stat-icon inventory-icon-danger"><i class="bi bi-x-circle-fill"></i></div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm border-0 inventory-alert-card">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1 fw-bold">Bahan Perlu Diperhatikan</h5>
                    <small class="text-muted">Daftar bahan dengan stok menipis atau sudah habis.</small>
                </div>
                <div class="inventory-alert-summary"><i class="bi bi-bell-fill me-1"></i>{{ $totalMenipis + $totalHabis }} bahan</div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 inventory-alert-table">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Bahan Baku</th>
                            <th>Kategori</th>
                            <th>Stok Saat Ini</th>
                            <th>Minimum Stok</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($inventoryItems as $item)
                            <tr>
                                <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                                <td><strong class="text-dark">{{ $item->name }}</strong></td>
                                <td><span class="text-muted">{{ $item->category ?? '-' }}</span></td>
                                <td><span class="fw-bold {{ $item->stock <= 0 ? 'text-danger' : 'text-warning' }}">{{ number_format($item->stock, 2, ',', '.') }}</span></td>
                                <td><span class="text-dark">{{ number_format($item->minimum_stock, 2, ',', '.') }}</span></td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    @if($item->stock <= 0)
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Habis</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i>Menipis</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.inventory.edit', $item) }}" class="btn btn-sm btn-outline-primary" title="Edit bahan"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="border-0">
                                    <div class="inventory-empty-alert">
                                        <div class="inventory-empty-icon"><i class="bi bi-check-circle-fill"></i></div>
                                        <h6 class="fw-bold mb-1">Semua stok masih aman</h6>
                                        <p class="text-muted mb-3">Tidak ada bahan yang sedang menipis atau habis.</p>
                                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-success btn-sm"><i class="bi bi-box-seam me-1"></i>Lihat Stok Bahan</a>
                                    </div>
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
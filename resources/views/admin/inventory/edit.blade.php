@extends('layouts.admin')
@section('title', 'Edit Bahan Baku')
@section('content')

<div class="container-fluid inventory-page inventory-form-page">
    <div class="card inventory-form-card border-0 shadow-sm">
        <div class="inventory-form-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="inventory-form-section-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <h5 class="mb-1 fw-bold">Data Bahan Baku</h5>
                    <small class="text-muted">Ubah informasi bahan yang diperlukan.</small>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-lg-5">
            @if ($errors->any())
                <div class="alert alert-danger inventory-error-alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-circle-fill mt-1"></i>
                        <div>
                            <strong>Data belum diperbarui.</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.inventory.update', $inventoryItem) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="inventory-form-section">
                    <div class="inventory-form-section-title">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Informasi Dasar</span>
                    </div>
                    <div class="inventory-form-section-line"></div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="inventory-form-label">Nama Bahan</label>
                            <input type="text" name="name" class="form-control inventory-form-control" value="{{ old('name', $inventoryItem->name) }}" placeholder="Contoh: Tepung Terigu" required>
                            <small class="inventory-form-help">Nama bahan yang akan ditampilkan di inventory.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="inventory-form-label">Kategori</label>
                            <input type="text" name="category" class="form-control inventory-form-control" value="{{ old('category', $inventoryItem->category) }}" placeholder="Contoh: Tepung">
                            <small class="inventory-form-help">Kelompok bahan untuk memudahkan pengelolaan.</small>
                        </div>
                    </div>
                </div>

                <div class="inventory-form-section mt-5">
                    <div class="inventory-form-section-title">
                        <i class="bi bi-boxes"></i>
                        <span>Persediaan & Satuan</span>
                    </div>
                    <div class="inventory-form-section-line"></div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="inventory-form-label">Satuan</label>
                            <select name="unit" class="form-select inventory-form-control" required>
                                <option value="">Pilih satuan</option>
                                <option value="gram" {{ old('unit', $inventoryItem->unit) == 'gram' ? 'selected' : '' }}>Gram</option>
                                <option value="kg" {{ old('unit', $inventoryItem->unit) == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                <option value="ml" {{ old('unit', $inventoryItem->unit) == 'ml' ? 'selected' : '' }}>Mililiter</option>
                                <option value="liter" {{ old('unit', $inventoryItem->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                                <option value="pcs" {{ old('unit', $inventoryItem->unit) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                            </select>
                            <small class="inventory-form-help">Satuan penyimpanan stok bahan.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="inventory-form-label">Stok</label>
                            <div class="input-group inventory-input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-box-seam"></i>
                                </span>
                                <input type="number" name="stock" class="form-control inventory-form-control" min="0" step="0.01" value="{{ old('stock', $inventoryItem->stock) }}" required>
                            </div>
                            <small class="inventory-form-help">Jumlah stok bahan saat ini.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="inventory-form-label">Minimum Stok</label>
                            <div class="input-group inventory-input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </span>
                                <input type="number" name="minimum_stock" class="form-control inventory-form-control" min="0" step="0.01" value="{{ old('minimum_stock', $inventoryItem->minimum_stock) }}" required>
                            </div>
                            <small class="inventory-form-help">Batas stok untuk status menipis.</small>
                        </div>
                    </div>
                </div>

                <div class="inventory-form-section mt-5">
                    <div class="inventory-form-section-title">
                        <i class="bi bi-cash-stack"></i>
                        <span>Harga Bahan</span>
                    </div>
                    <div class="inventory-form-section-line"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="inventory-form-label">Harga per Satuan</label>
                            <div class="input-group inventory-input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="cost_per_unit" class="form-control inventory-form-control" min="0" step="0.01" value="{{ old('cost_per_unit', $inventoryItem->cost_per_unit) }}" required>
                            </div>
                            <small class="inventory-form-help">Harga untuk satu satuan yang dipilih. Contoh: Rp30.000 per kg.</small>
                        </div>
                    </div>
                </div>

                <div class="inventory-form-footer">
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-light inventory-cancel-btn">Batal</a>
                    <button type="submit" class="btn btn-success inventory-save-btn">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('title', 'Tambah Bahan Baku')
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
                    <small class="text-muted">Isi informasi bahan yang akan disimpan.</small>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-lg-5">
            @if ($errors->any())
                <div class="alert alert-danger inventory-error-alert mb-4">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-circle-fill mt-1"></i>
                        <div>
                            <strong>Data belum tersimpan.</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.inventory.store') }}" method="POST">
                @csrf

                <div class="inventory-form-section">
                    <div class="inventory-form-section-title">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Informasi Dasar</span>
                    </div>
                    <div class="inventory-form-section-line"></div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="inventory-form-label">Nama Bahan</label>
                            <input type="text" name="name" class="form-control inventory-form-control" value="{{ old('name') }}" placeholder="Contoh: Tepung Terigu" required>
                            <small class="inventory-form-help">Nama bahan yang akan ditampilkan di inventory.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="inventory-form-label">Kategori</label>
                            <input type="text" name="category" class="form-control inventory-form-control" value="{{ old('category') }}" placeholder="Contoh: Tepung">
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
                                <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Mililiter</option>
                                <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                                <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                            </select>
                            <small class="inventory-form-help">Satuan yang digunakan untuk menyimpan stok.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="inventory-form-label">Stok Awal</label>
                            <div class="input-group inventory-input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-box-seam"></i>
                                </span>
                                <input type="number" name="stock" class="form-control inventory-form-control" min="0" step="0.01" value="{{ old('stock') }}" placeholder="Contoh: 10" required>
                            </div>
                            <small class="inventory-form-help">Jumlah stok awal bahan.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="inventory-form-label">Minimum Stok</label>
                            <div class="input-group inventory-input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </span>
                                <input type="number" name="minimum_stock" class="form-control inventory-form-control" min="0" step="0.01" value="{{ old('minimum_stock') }}" placeholder="Contoh: 1" required>
                            </div>
                            <small class="inventory-form-help">Batas untuk menentukan stok menipis.</small>
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
                                <input type="number" name="cost_per_unit" class="form-control inventory-form-control" min="0" step="0.01" value="{{ old('cost_per_unit') }}" placeholder="Contoh: 30000" required>
                            </div>
                            <small class="inventory-form-help">Harga untuk satu satuan yang dipilih. Contoh: Rp30.000 per kg.</small>
                        </div>
                    </div>
                </div>

                <div class="inventory-form-footer">
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-light inventory-cancel-btn">Batal</a>
                    <button type="submit" class="btn btn-success inventory-save-btn">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Bahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
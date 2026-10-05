@extends('layouts.admin')
@section('title', 'Buat Kategori')
@section('content')

<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2"><i class="bi bi-tags fs-5"></i></div>
                <div>
                    <h5 class="fw-bold mb-0">Informasi Kategori</h5>
                    <small class="text-muted">Masukkan nama dan icon kategori.</small>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('admin.category.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control rounded-3" value="{{ old('name') }}" placeholder="Contoh: Food, Beverage, Dessert" required>
                    <small class="text-muted">Masukkan nama kategori menu.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Icon Kategori</label>
                    <select name="icon" class="form-select rounded-3" required>
                        <option value="">-- Pilih Icon --</option>
                        <option value="bi-basket-fill" {{ old('icon') == 'bi-basket-fill' ? 'selected' : '' }}>🍽️ Food</option>
                        <option value="bi-cup-straw" {{ old('icon') == 'bi-cup-straw' ? 'selected' : '' }}>🥤 Drink</option>
                        <option value="bi-cup-hot-fill" {{ old('icon') == 'bi-cup-hot-fill' ? 'selected' : '' }}>☕ Coffee</option>
                        <option value="bi-cake2-fill" {{ old('icon') == 'bi-cake2-fill' ? 'selected' : '' }}>🍰 Dessert</option>
                    </select>
                    <small class="text-muted">Pilih icon yang sesuai dengan kategori menu.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Contoh Tampilan</label>
                    <div class="bg-light rounded-3 p-3 d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-3"><i class="bi bi-tags fs-4"></i></div>
                        <div>
                            <strong>Kategori Menu</strong>
                            <small class="text-muted d-block">Icon kategori akan tampil di halaman menu.</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-success rounded-3 px-4"><i class="bi bi-check-circle me-1"></i> Simpan</button>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary rounded-3 px-4"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
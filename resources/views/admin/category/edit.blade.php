@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('content')

<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2"><i class="bi bi-tags fs-5"></i></div>
                <div>
                    <h5 class="fw-bold mb-0">Informasi Kategori</h5>
                    <small class="text-muted">Ubah nama dan icon kategori.</small>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('admin.category.update',$category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control rounded-3" value="{{ old('name',$category->name) }}" placeholder="Contoh: Food, Beverage, Dessert" required>
                    <small class="text-muted">Masukkan nama kategori menu.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Icon Kategori</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi {{ $category->icon }}"></i></span>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon',$category->icon) }}" placeholder="Contoh: bi-cup-hot">
                    </div>
                    <small class="text-muted">Gunakan nama icon dari Bootstrap Icons, misalnya <strong>bi-cup-hot</strong>.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Preview Icon</label>
                    <div class="bg-light rounded-3 p-3 d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3"><i class="bi {{ $category->icon }} fs-4"></i></div>
                        <div>
                            <strong>{{ $category->name }}</strong>
                            <small class="text-muted d-block">{{ $category->icon }}</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-success rounded-3 px-4"><i class="bi bi-check-circle me-1"></i> Update</button>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary rounded-3 px-4"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
@extends('layouts.admin')
@section('title','Buat Kategori')
@section('content')

<div class="container py-4">
    <form action="{{ route('admin.category.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Kategori</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Icon</label>
            <select name="icon" class="form-select" required>
                <option value="">-- Pilih Icon --</option>
                <option value="bi-basket-fill">🍽️ Food</option>
                <option value="bi-cup-straw">🥤 Drink</option>
                <option value="bi-cup-hot-fill">☕ Coffee</option>
                <option value="bi-cake2-fill">🍰 Dessert</option>
            </select>
        </div>

        <button class="btn btn-success">Simpan</button>
    </form>
</div>

@endsection
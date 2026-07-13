@extends('layouts.admin')

@section('content')

<div class="container py-4">

    <h3 class="fw-bold mb-4">
        Tambah Menu
    </h3>

    <form action="{{ route('admin.menu.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="card shadow border-0 rounded-4">

            <div class="card-body">

                {{-- Nama Menu --}}
                <div class="mb-3">

                    <label class="form-label">
                        Nama Menu
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        required>

                </div>

                {{-- Kategori --}}
                <div class="mb-3">

                    <label class="form-label">
                        Kategori
                    </label>

                    <select
                        name="category_id"
                        class="form-select"
                        required>

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">

                    <label class="form-label">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control"></textarea>

                </div>

                {{-- Harga --}}
                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">
                            Harga Regular
                        </label>

                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Tambahan Harga Large
                        </label>

                        <input
                            type="number"
                            name="large_price"
                            value="5000"
                            class="form-control">

                        <small class="text-muted">
                            Tambahan harga ukuran Large.
                        </small>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Stok
                        </label>

                        <input
                            type="number"
                            name="stock"
                            value="0"
                            class="form-control"
                            required>

                    </div>

                </div>

                {{-- Informasi Tambahan --}}
                <div class="row mt-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Waktu Pembuatan (Menit)
                        </label>

                        <input
                            type="number"
                            name="preparation_time"
                            class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Rating
                        </label>

                        <input
                            type="number"
                            name="rating"
                            min="1"
                            max="5"
                            step="0.1"
                            class="form-control"
                            required>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Kalori (kcal)
                        </label>

                        <input
                            type="number"
                            name="calories"
                            class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Informasi Alergen
                        </label>

                        <input
                            type="text"
                            name="allergen"
                            class="form-control"
                            placeholder="Contoh : Susu, Gluten">

                    </div>

                </div>

                {{-- Upload Foto --}}
                <div class="mt-4">

                    <label class="form-label">
                        Foto Menu
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        required>

                </div>

                <hr>

                {{-- Status Menu --}}
                <h5 class="fw-bold mb-3">
                    Status Menu
                </h5>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="promo"
                        value="1">

                    <label class="form-check-label">

                        Promo

                    </label>

                </div>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="best_seller"
                        value="1">

                    <label class="form-check-label">

                        Best Seller

                    </label>

                </div>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="new"
                        value="1">

                    <label class="form-check-label">

                        Menu Baru

                    </label>

                </div>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="is_active"
                        value="1"
                        checked>

                    <label class="form-check-label">

                        Menu Aktif

                    </label>

                </div>

            </div>

            <div class="card-footer bg-white text-end">

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="bi bi-check-circle"></i>

                    Simpan Menu

                </button>

                <a href="{{ route('admin.menu.index') }}"
                   class="btn btn-secondary">

                    Batal

                </a>

            </div>

        </div>

    </form>

</div>

@endsection
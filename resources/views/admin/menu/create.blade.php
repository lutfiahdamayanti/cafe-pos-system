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
                <div class="mb-3">
                    <label>Nama Menu</label>
                    <input type="text"
                        name="name"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>Kategori</label>

                    <select
                        name="category_id"
                        class="form-select">

                        @foreach($categories as $category)

                        <option value="{{ $category->id }}">

                            {{ $category->name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label>Deskripsi</label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control"></textarea>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <label>Harga</label>

                        <input
                            type="number"
                            name="price"
                            class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label>Stok</label>

                        <input
                            type="number"
                            name="stock"
                            class="form-control">

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <label>Waktu Pembuatan (Menit)</label>

                        <input
                            type="number"
                            name="preparation_time"
                            class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label>Rating</label>

                        <input
                            type="number"
                            name="rating"
                            step="0.1"
                            min="1"
                            max="5"
                            class="form-control">

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <label>Kalori (kcal)</label>

                        <input
                            type="number"
                            name="calories"
                            class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label>Informasi Alergen</label>

                        <input
                            type="text"
                            name="allergen"
                            class="form-control"
                            placeholder="Contoh : Susu, Gluten">

                    </div>

                </div>

                <div class="mt-3">

                    <label>Gambar</label>

                    <input
                        type="file"
                        name="image"
                        class="form-control">

                </div>

                <div class="form-check mt-3">

                    <input
                        type="checkbox"
                        name="promo"
                        class="form-check-input">

                    <label class="form-check-label">

                        Promo

                    </label>

                </div>

                <div class="form-check">

                    <input
                        type="checkbox"
                        name="best_seller"
                        class="form-check-input">

                    <label class="form-check-label">

                        Best Seller

                    </label>

                </div>

                <div class="form-check">

                    <input
                        type="checkbox"
                        name="new"
                        class="form-check-input">

                    <label class="form-check-label">

                        Menu Baru

                    </label>

                </div>
        </div>
        <div class="card-footer bg-white text-end">

    <button type="submit" class="btn btn-success px-4">
        <i class="bi bi-check-circle me-1"></i>
        Simpan Menu
    </button>

    <a href="{{ route('admin.menu.index') }}"
       class="btn btn-secondary">
        Batal
    </a>

</div>

</div> {{-- penutup card --}}

</form>

</div>
@endsection
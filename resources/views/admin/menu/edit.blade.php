@extends('layouts.admin')

@section('content')

<div class="container py-4">

    <h3 class="fw-bold mb-4">Edit Menu</h3>

    <form action="{{ route('admin.menu.update', $menu->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card shadow border-0 rounded-4">

            <div class="card-body">

                <div class="mb-3">
                    <label>Nama Menu</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $menu->name) }}">
                </div>

                <div class="mb-3">
                    <label>Kategori</label>

                    <select name="category_id" class="form-select">

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}"
                                {{ $menu->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>

                    <textarea name="description"
                              class="form-control"
                              rows="4">{{ old('description', $menu->description) }}</textarea>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <label>Harga</label>
                        <input type="number"
                               name="price"
                               class="form-control"
                               value="{{ old('price', $menu->price) }}">
                    </div>

                    <div class="col-md-6">
                        <label>Stok</label>
                        <input type="number"
                               name="stock"
                               class="form-control"
                               value="{{ old('stock', $menu->stock) }}">
                    </div>

                </div>

                <div class="mt-3">
                    <label>Waktu Pembuatan</label>
                    <input type="number"
                           name="preparation_time"
                           class="form-control"
                           value="{{ old('preparation_time', $menu->preparation_time) }}">
                </div>

                <div class="mt-3">
                    <label>Gambar</label>

                    @if($menu->image)
                        <div class="mb-2">
                            <img src="{{ asset('images/'.$menu->image) }}"
                                 width="120"
                                 class="rounded border">
                        </div>
                    @endif

                    <input type="file"
                           name="image"
                           class="form-control">
                </div>

                <div class="form-check mt-3">
                    <input type="checkbox"
                           name="promo"
                           class="form-check-input"
                           {{ $menu->promo ? 'checked' : '' }}>

                    <label class="form-check-label">Promo</label>
                </div>

                <div class="form-check">
                    <input type="checkbox"
                           name="best_seller"
                           class="form-check-input"
                           {{ $menu->best_seller ? 'checked' : '' }}>

                    <label class="form-check-label">Best Seller</label>
                </div>

            </div>

            <div class="card-footer bg-white">

                <button class="btn btn-primary">
                    Update
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
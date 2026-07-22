@extends('layouts.admin')
@section('title','Update Menu')
@section('content')

<div class="container py-4">
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

                <div class="mb-3">

                    <label>Komposisi / Bahan</label>
                    <textarea
                        name="ingredients"
                        class="form-control"
                        rows="5"
                        placeholder="Contoh:
                        Roti Burger
                        Daging Sapi
                        Keju Cheddar
                        Selada
                        Tomat
                        Mayonaise">{{ old('ingredients', $menu->ingredients) }}
                    </textarea>
                    <small class="text-muted">
                        Pisahkan setiap bahan dengan baris baru.
                    </small>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Harga</label>
                        <input type="number"
                            name="price"
                            class="form-control"
                            value="{{ old('price', $menu->price) }}">
                    </div>

                    <input
                        type="hidden"
                        name="large_price"
                        value="{{ old('large_price', $menu->large_price) }}">

                    <div class="col-md-4">
                        <label>Stok</label>
                        <input type="number"
                            name="stock"
                            class="form-control"
                            value="{{ old('stock', $menu->stock) }}">
                    </div>
                </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Waktu Pembuatan (Menit)</label>
                            <input type="number"
                                name="preparation_time"
                                class="form-control"
                                value="{{ old('preparation_time', $menu->preparation_time) }}">
                        </div>

                        <div class="col-md-6">
                            <label>Rating</label>
                            <input type="number"
                                name="rating"
                                min="1"
                                max="5"
                                step="0.1"
                                class="form-control"
                                value="{{ old('rating', $menu->rating) }}">
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Kalori (kkal)</label>
                                <input
                                    type="number"
                                    name="calories"
                                    class="form-control"
                                    value="{{ old('calories',$menu->calories) }}">
                            </div>

                            <div class="col-md-6">
                                <label>Alergen</label>
                                <input
                                    type="text"
                                    name="allergen"
                                    class="form-control"
                                    placeholder="Contoh : Gluten, Susu, Kacang"
                                    value="{{ old('allergen',$menu->allergen) }}">
                            </div>
                        </div>
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

                <hr>
                <h5 class="fw-bold">
                    Pilihan Menu
                </h5>
                <div id="option-wrapper">
                    <button
                        type="button"
                        id="add-option"
                        class="btn btn-success mt-3">
                        + Tambah Pilihan
                    </button>
                    @foreach($menu->options as $i=>$option)

                    <div class="card p-3 mt-3 option-item">
                        <div class="mb-3">
                            <label>Nama Pilihan</label>
                            <input
                                type="text"
                                class="form-control"
                                name="options[{{ $i }}][name]"
                                value="{{ $option->name }}">
                        </div>

                        <div class="values">
                            @foreach($option->values as $j=>$value)
                            <<div class="row mt-2">
                                <div class="col-md-5">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="options[{{ $i }}][values][{{ $j }}][value]"
                                        value="{{ $value->value }}">
                                </div>

                                <div class="col-md-5">
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="options[{{ $i }}][values][{{ $j }}][price]"
                                        value="{{ $value->extra_price }}">
                                </div>

                                <div class="col-md-2">
                                    <button
                                        type="button"
                                        class="btn btn-danger remove-value">
                                        X
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm mt-2 add-value">
                            + Tambah Nilai
                        </button>

                        <button
                            type="button"
                            class="btn btn-danger btn-sm mt-2 remove-option">
                            Hapus Pilihan
                        </button>
                    </div>
                    @endforeach
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

@push('scripts')
<script>
let optionIndex = {{ $menu->options->count() }};
document.getElementById('add-option').addEventListener('click', function(){
    document.getElementById('option-wrapper').insertAdjacentHTML('beforeend',`
        <div class="card p-3 mt-3 option-item">
            <div class="mb-3">
                <label>Nama Pilihan</label>
                <input
                    type="text"
                    class="form-control"
                    name="options[${optionIndex}][name]">
            </div>

            <div class="values"></div>
            <button
                type="button"
                class="btn btn-primary btn-sm mt-2 add-value">
                + Tambah Nilai
            </button>
            <button
                type="button"
                class="btn btn-danger btn-sm mt-2 remove-option">
                Hapus Pilihan
            </button>
        </div>
    `);
    optionIndex++;
});
document.addEventListener('click', function(e){
    // Tambah Value
    if(e.target.classList.contains('add-value')){
        let card = e.target.closest('.option-item');
        let values = card.querySelector('.values');
        let index = card.querySelector('input').name.match(/\d+/)[0];
        let total = values.children.length;
        values.insertAdjacentHTML('beforeend',`
            <div class="row mt-2">
                <div class="col-md-5">
                    <input
                        type="text"
                        class="form-control"
                        name="options[${index}][values][${total}][value]">
                </div>
                <div class="col-md-5">
                    <input
                        type="number"
                        class="form-control"
                        value="0"
                        name="options[${index}][values][${total}][price]">
                </div>
                <div class="col-md-2">
                    <button
                        type="button"
                        class="btn btn-danger remove-value">
                        X
                    </button>
                </div>
            </div>
        `);
    }
    // Hapus Option
    if(e.target.classList.contains('remove-option')){
        e.target.closest('.option-item').remove();
    }
    // Hapus Value
    if(e.target.classList.contains('remove-value')){
        e.target.closest('.row').remove();
    }
});
</script>
@endpush
@endsection
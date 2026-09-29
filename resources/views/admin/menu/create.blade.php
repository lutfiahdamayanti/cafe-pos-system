@extends('layouts.admin')
@section('title','Create Menu')
@section('content')

<div class="container py-4">
    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card shadow border-0 rounded-4">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Komposisi Menu</label>
                    <small class="text-muted d-block mb-2">Komposisi yang akan ditampilkan kepada customer.</small>
                    <textarea name="ingredients" rows="4" class="form-control" placeholder="Contoh : Roti Burger Daging Sapi Keju Selada Tomat Mayonaise"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Harga Regular</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estimasi Food Cost</label>
                        <input type="number" name="food_cost" class="form-control" value="0" readonly>
                        <small class="text-muted">Perkiraan biaya bahan untuk 1 porsi.</small>
                    </div>
                    <input type="hidden" name="large_price" value="0">
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" value="0" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Waktu Pembuatan (Menit)</label>
                        <input type="number" name="preparation_time" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rating</label>
                        <input type="number" name="rating" min="1" max="5" step="0.1" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Kalori (kcal)</label>
                        <input type="number" name="calories" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Informasi Alergen</label>
                        <input type="text" name="allergen" class="form-control" placeholder="Contoh : Susu, Gluten">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="form-label">Foto Menu</label>
                    <input type="file" name="image" class="form-control" required>
                </div>

                <hr>

                <h5 class="fw-bold mb-3">Status Menu</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="promo" value="1">
                    <label class="form-check-label">Promo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="best_seller" value="1">
                    <label class="form-check-label">Best Seller</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="new" value="1">
                    <label class="form-check-label">Menu Baru</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                    <label class="form-check-label">Menu Aktif</label>
                </div>
            </div>

            <hr>

            <h5 class="fw-bold mb-3 ms-3">Resep / Bahan Menu</h5>
            <div id="ingredient-wrapper"></div>
            <button type="button" id="add-ingredient" class="btn btn-outline-success mt-3 w-100">+ Tambah Bahan</button>

            <hr>

            <h5 class="fw-bold mb-3 ms-3">Pilihan Menu</h5>
            <div id="option-container"></div>
            <button type="button" class="btn btn-outline-success" id="add-option">+ Tambah Pilihan</button>
            <div class="card-footer bg-white text-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Simpan Menu
                </button>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let optionIndex = 0;
document.getElementById("add-option").onclick = function(){
    let html = `
        <div class="card mt-3 option-card">
            <div class="card-body">
                <div class="mb-3">
                    <label>Nama Pilihan</label>
                    <input type="text" name="options[${optionIndex}][name]" class="form-control" placeholder="Contoh : Ukuran">
                </div>
                <div class="values mb-3"></div>
                <button type="button" class="btn btn-sm btn-success add-value">+ Tambah Nilai</button>
            </div>
        </div>
    `;
    document.getElementById("option-container").insertAdjacentHTML("beforeend",html);
    optionIndex++;
}

document.addEventListener("click",function(e){
    if(e.target.classList.contains("add-value")){
        let values = e.target.previousElementSibling;
        let count = values.children.length;
        let card = e.target.closest(".option-card");
        let optionName = card.querySelector("input").name;
        let index = optionName.match(/\d+/)[0];
        values.insertAdjacentHTML("beforeend",`
            <div class="row mt-2">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Nilai" name="options[${index}][values][${count}][value]">
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control" placeholder="Harga Tambahan" name="options[${index}][values][${count}][price]">
                </div>
            </div>
        `);
    }
});

let ingredientIndex = 0;
document.getElementById('add-ingredient').addEventListener('click', function () {
    document.getElementById('ingredient-wrapper').insertAdjacentHTML('beforeend', `
        <div class="row mt-2 ingredient-item">
            <div class="col-md-4">
                <input type="text" name="recipe_ingredients[${ingredientIndex}][name]" class="form-control" placeholder="Nama bahan">
            </div>
            <div class="col-md-3">
                <input type="number" name="recipe_ingredients[${ingredientIndex}][quantity]" class="form-control" placeholder="Jumlah" step="0.01">
            </div>

            <div class="col-md-2">
                <input type="text" name="recipe_ingredients[${ingredientIndex}][unit]" class="form-control" placeholder="Satuan">
            </div>
            <div class="col-md-2">
                <input type="number" name="recipe_ingredients[${ingredientIndex}][cost]" class="form-control" placeholder="Biaya" step="0.01">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-ingredient">X</button>
            </div>
        </div>
    `);
    ingredientIndex++;
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-ingredient')) {
        e.target.closest('.ingredient-item').remove();
    }
});
</script>
@endpush
@endsection
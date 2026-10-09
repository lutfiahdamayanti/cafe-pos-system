@extends('layouts.admin')
@section('title','Update Menu')
@section('content')

<div class="container-fluid py-4 menu-edit-page">
    <div class="menu-edit-header mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="menu-edit-title-icon"><i class="bi bi-pencil-square"></i></div>
                <h2 class="fw-bold mb-0">Update Menu</h2>
            </div>
            <p class="text-muted mb-0">Ubah informasi menu, resep, food cost, stok, dan pilihan menu.</p>
        </div>
        <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary menu-back-btn"><i class="bi bi-arrow-left me-1"></i>Kembali ke Menu</a>
    </div>

    <form action="{{ route('admin.menu.update',$menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="menu-edit-card mb-4">
            <div class="menu-edit-card-header">
                <div><h5 class="fw-bold mb-1"><i class="bi bi-info-circle-fill text-success me-2"></i>Informasi Menu</h5><small class="text-muted">Informasi utama menu cafe</small></div>
            </div>
            <div class="menu-edit-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="menu-form-label">Nama Menu</label>
                        <input type="text" name="name" class="form-control menu-form-control" value="{{ old('name',$menu->name) }}" placeholder="Contoh: Beef Burger">
                    </div>
                    <div class="col-md-6">
                        <label class="menu-form-label">Kategori</label>
                        <select name="category_id" class="form-select menu-form-control">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $menu->category_id==$category->id?'selected':'' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="menu-form-label">Deskripsi</label>
                        <textarea name="description" class="form-control menu-form-control" rows="4" placeholder="Jelaskan menu secara singkat...">{{ old('description',$menu->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="menu-form-label">Komposisi / Bahan</label>
                        <textarea name="ingredients" class="form-control menu-form-control" rows="4" placeholder="Contoh:
                        Roti Burger
                        Daging Sapi
                        Keju Cheddar
                        Selada
                        Tomat
                        Mayonaise">{{ old('ingredients',$menu->ingredients) }}</textarea>
                        <small class="text-muted">Pisahkan setiap bahan dengan baris baru.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-edit-card mb-4">
            <div class="menu-edit-card-header">
                <div><h5 class="fw-bold mb-1"><i class="bi bi-cash-stack text-success me-2"></i>Harga & Stok</h5><small class="text-muted">Atur harga jual, food cost, dan persediaan menu.</small></div>
            </div>
            <div class="menu-edit-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="menu-form-label">Harga Jual</label>
                        <div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="price" class="form-control menu-form-control" value="{{ old('price',$menu->price) }}"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="menu-form-label">Estimasi Food Cost</label>
                        <div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="food_cost" class="form-control menu-form-control" value="{{ old('food_cost',$menu->food_cost) }}" readonly></div>
                        <small class="text-muted">Perkiraan biaya bahan untuk 1 porsi.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="menu-form-label">Stok</label>
                        <input type="number" name="stock" class="form-control menu-form-control" value="{{ old('stock',$menu->stock) }}" min="0">
                    </div>
                    <input type="hidden" name="large_price" value="{{ old('large_price',$menu->large_price) }}">
                </div>
            </div>
        </div>

        <div class="menu-edit-card mb-4">
            <div class="menu-edit-card-header">
                <div><h5 class="fw-bold mb-1"><i class="bi bi-sliders text-primary me-2"></i>Detail Tambahan</h5><small class="text-muted">Informasi tambahan mengenai menu.</small></div>
            </div>
            <div class="menu-edit-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="menu-form-label">Waktu Pembuatan</label>
                        <div class="input-group"><input type="number" name="preparation_time" class="form-control menu-form-control" value="{{ old('preparation_time',$menu->preparation_time) }}"><span class="input-group-text">Menit</span></div>
                    </div>
                    <div class="col-md-4">
                        <label class="menu-form-label">Rating</label>
                        <input type="number" name="rating" min="1" max="5" step="0.1" class="form-control menu-form-control" value="{{ old('rating',$menu->rating) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="menu-form-label">Kalori</label>
                        <div class="input-group"><input type="number" name="calories" class="form-control menu-form-control" value="{{ old('calories',$menu->calories) }}"><span class="input-group-text">kkal</span></div>
                    </div>
                    <div class="col-12">
                        <label class="menu-form-label">Alergen</label>
                        <input type="text" name="allergen" class="form-control menu-form-control" placeholder="Contoh: Gluten, Susu, Kacang" value="{{ old('allergen',$menu->allergen) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-edit-card mb-4">
            <div class="menu-edit-card-header">
                <div><h5 class="fw-bold mb-1"><i class="bi bi-image-fill text-info me-2"></i>Gambar Menu</h5><small class="text-muted">Ganti gambar menu jika diperlukan.</small></div>
            </div>
            <div class="menu-edit-card-body">
                @if($menu->image)
                    <div class="current-menu-image mb-3">
                        <img src="{{ asset('images/'.$menu->image) }}" alt="{{ $menu->name }}">
                        <div><strong>Gambar saat ini</strong><small class="text-muted d-block">Pilih file baru jika ingin menggantinya.</small></div>
                    </div>
                @endif
                <input type="file" name="image" class="form-control menu-form-control">
            </div>
        </div>

        <div class="menu-edit-card mb-4">
            <div class="menu-edit-card-header">
                <div><h5 class="fw-bold mb-1"><i class="bi bi-toggle-on text-success me-2"></i>Status Menu</h5><small class="text-muted">Tandai menu sebagai promo atau best seller.</small></div>
            </div>
            <div class="menu-edit-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="menu-check-card">
                            <input type="checkbox" name="promo" class="form-check-input" {{ $menu->promo?'checked':'' }}>
                            <span class="menu-check-icon promo-icon"><i class="bi bi-tag-fill"></i></span>
                            <span><strong>Promo</strong><small>Tandai menu sebagai menu promo.</small></span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="menu-check-card">
                            <input type="checkbox" name="best_seller" class="form-check-input" {{ $menu->best_seller?'checked':'' }}>
                            <span class="menu-check-icon seller-icon"><i class="bi bi-star-fill"></i></span>
                            <span><strong>Best Seller</strong><small>Tandai menu sebagai menu terlaris.</small></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-edit-card mb-4">
            <div class="menu-edit-card-header">
                <div><h5 class="fw-bold mb-1"><i class="bi bi-journal-text text-success me-2"></i>Resep / Bahan Menu</h5><small class="text-muted">Masukkan bahan, jumlah, satuan, dan biaya bahan.</small></div>
            </div>
            <div class="menu-edit-card-body">
                <div id="ingredient-wrapper">
                    @foreach($menu->recipeIngredients as $i=>$ingredient)
                        <div class="ingredient-item">
                            <div class="ingredient-number">{{ $i+1 }}</div>
                           <div class="ingredient-field">
                                <label>Nama Bahan</label>
                                <select name="recipe_ingredients[{{ $i }}][inventory_item_id]" class="form-select menu-form-control">
                                    <option value="">Pilih bahan</option>
                                    @foreach($inventoryItems as $inventoryItem)
                                        <option value="{{ $inventoryItem->id }}" {{ $ingredient->inventory_item_id == $inventoryItem->id ? 'selected' : '' }}>{{ $inventoryItem->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="ingredient-field">
                                <label>Jumlah</label>
                                <input type="number" name="recipe_ingredients[{{ $i }}][quantity]" class="form-control menu-form-control" placeholder="0" step="0.01" value="{{ $ingredient->quantity }}">
                            </div>
                            <div class="ingredient-field">
                                <label>Satuan</label>
                                <select name="recipe_ingredients[{{ $i }}][unit]" class="form-select menu-form-control">
                                    <option value="">Pilih satuan</option>
                                    <option value="gram" {{ $ingredient->unit === 'gram' ? 'selected' : '' }}>Gram</option>
                                    <option value="kg" {{ $ingredient->unit === 'kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="ml" {{ $ingredient->unit === 'ml' ? 'selected' : '' }}>Ml</option>
                                    <option value="liter" {{ $ingredient->unit === 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="pcs" {{ $ingredient->unit === 'pcs' ? 'selected' : '' }}>Pcs</option>
                                </select>
                            </div>
                            <div class="ingredient-field">
                                <label>Biaya</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="recipe_ingredients[{{ $i }}][cost]" class="form-control menu-form-control ingredient-cost" value="{{ $ingredient->cost }}" step="0.01" min="0">
                                </div>
                            </div>
                            <button type="button" class="btn remove-ingredient" title="Hapus bahan"><i class="bi bi-trash"></i></button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-ingredient" class="btn btn-outline-success mt-3"><i class="bi bi-plus-circle me-1"></i>Tambah Bahan</button>
            </div>
        </div>

        <div class="menu-edit-card mb-4">
            <div class="menu-edit-card-header">
                <div><h5 class="fw-bold mb-1"><i class="bi bi-sliders2 text-primary me-2"></i>Pilihan Menu</h5><small class="text-muted">Tambahkan pilihan ukuran, level, topping, dan lainnya.</small></div>
            </div>
            <div class="menu-edit-card-body">
                <div id="option-wrapper">
                    @foreach($menu->options as $i=>$option)
                        <div class="menu-option-card option-item">
                            <div class="menu-option-header">
                                <div class="menu-option-title"><i class="bi bi-ui-checks-grid"></i>Pilihan Menu</div>
                                <button type="button" class="btn remove-option"><i class="bi bi-trash"></i> Hapus</button>
                            </div>
                            <div class="mb-3">
                                <label class="menu-form-label">Nama Pilihan</label>
                                <input type="text" class="form-control menu-form-control" name="options[{{ $i }}][name]" value="{{ $option->name }}" placeholder="Contoh: Ukuran">
                            </div>
                            <div class="values">
                                @foreach($option->values as $j=>$value)
                                    <div class="option-value-row">
                                        <div class="option-value-number">{{ $j+1 }}</div>
                                        <div class="flex-grow-1">
                                            <label>Nilai Pilihan</label>
                                            <input type="text" class="form-control menu-form-control" name="options[{{ $i }}][values][{{ $j }}][value]" value="{{ $value->value }}" placeholder="Contoh: Large">
                                        </div>
                                        <div class="option-price">
                                            <label>Tambahan Harga</label>
                                            <div class="input-group"><span class="input-group-text">Rp</span><input type="number" class="form-control menu-form-control" name="options[{{ $i }}][values][{{ $j }}][price]" value="{{ $value->extra_price }}"></div>
                                        </div>
                                        <button type="button" class="btn remove-value" title="Hapus nilai"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-3 add-value"><i class="bi bi-plus-circle me-1"></i>Tambah Nilai</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-option" class="btn btn-outline-primary mt-3"><i class="bi bi-plus-circle me-1"></i>Tambah Pilihan</button>
            </div>
        </div>

        <div class="menu-edit-footer">
            <a href="{{ route('admin.menu.index') }}" class="btn btn-light menu-cancel-btn">Batal</a>
            <button type="submit" class="btn btn-success menu-save-btn"><i class="bi bi-check-circle me-1"></i>Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let optionIndex={{ $menu->options->count() }};
document.getElementById('add-option').addEventListener('click',function(){
    document.getElementById('option-wrapper').insertAdjacentHTML('beforeend',`
        <div class="menu-option-card option-item">
            <div class="menu-option-header"><div class="menu-option-title"><i class="bi bi-ui-checks-grid"></i>Pilihan Menu</div><button type="button" class="btn remove-option"><i class="bi bi-trash"></i> Hapus</button></div>
            <div class="mb-3"><label class="menu-form-label">Nama Pilihan</label><input type="text" class="form-control menu-form-control" name="options[${optionIndex}][name]" placeholder="Contoh: Ukuran"></div>
            <div class="values"></div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-3 add-value"><i class="bi bi-plus-circle me-1"></i>Tambah Nilai</button>
        </div>`);
    optionIndex++;
});

document.addEventListener('click',function(e){
    const addValue=e.target.closest('.add-value'),removeOption=e.target.closest('.remove-option'),removeValue=e.target.closest('.remove-value'),removeIngredient=e.target.closest('.remove-ingredient');
    if(addValue){
        const card=addValue.closest('.option-item'),values=card.querySelector('.values'),index=card.querySelector('input').name.match(/\d+/)[0],total=values.children.length;
        values.insertAdjacentHTML('beforeend',`
            <div class="option-value-row">
                <div class="option-value-number">${total+1}</div>
                <div class="flex-grow-1"><label>Nilai Pilihan</label><input type="text" class="form-control menu-form-control" name="options[${index}][values][${total}][value]" placeholder="Contoh: Large"></div>
                <div class="option-price"><label>Tambahan Harga</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" class="form-control menu-form-control" value="0" name="options[${index}][values][${total}][price]"></div></div>
                <button type="button" class="btn remove-value"><i class="bi bi-x-lg"></i></button>
            </div>`);
    }
    if(removeOption) removeOption.closest('.option-item').remove();
    if(removeValue) removeValue.closest('.option-value-row').remove();
    if(removeIngredient) removeIngredient.closest('.ingredient-item').remove();
});

const inventoryItems=@json($inventoryItems->values());
function calculateIngredientCost(quantity, recipeUnit, inventoryUnit, costPerUnit) {
    quantity = parseFloat(quantity) || 0;
    costPerUnit = parseFloat(costPerUnit) || 0;
    recipeUnit = (recipeUnit || '').toLowerCase().trim();
    inventoryUnit = (inventoryUnit || '').toLowerCase().trim();
    if (quantity <= 0 || costPerUnit < 0) {return 0;}
    if (recipeUnit === inventoryUnit) {return quantity * costPerUnit;}
    if (recipeUnit === 'gram' && inventoryUnit === 'kg') {return (quantity / 1000) * costPerUnit;}
    if (recipeUnit === 'kg' && inventoryUnit === 'gram') {return (quantity * 1000) * costPerUnit;}
    if (recipeUnit === 'ml' && inventoryUnit === 'liter') {return (quantity / 1000) * costPerUnit;}
    if (recipeUnit === 'liter' && inventoryUnit === 'ml') { return (quantity * 1000) * costPerUnit;}
    return 0;
}


function updateIngredientFields(item) {
    const select = item.querySelector('select[name*="[inventory_item_id]"]');
    const quantityInput = item.querySelector('input[name*="[quantity]"]');
    const unitInput = item.querySelector('select[name*="[unit]"]');
    const costInput = item.querySelector('input[name*="[cost]"]');
    if (!select || !quantityInput || !unitInput || !costInput) {
        return;
    }
    const inventoryItem = inventoryItems.find(
        inventory =>
            String(inventory.id) === String(select.value)
    );
    if (!inventoryItem) {
        return;
    }
    const quantity = parseFloat(quantityInput.value) || 0;
    const cost = calculateIngredientCost(quantity,unitInput.value,inventoryItem.unit,inventoryItem.cost_per_unit);
    costInput.value = cost.toFixed(2);
}

document.addEventListener('change',function(e){
    if(e.target.matches('select[name*="[inventory_item_id]"]')) updateIngredientFields(e.target.closest('.ingredient-item'));
});

document.addEventListener('input',function(e){
    if(e.target.matches('input[name*="[quantity]"]')) updateIngredientFields(e.target.closest('.ingredient-item'));
});

let ingredientIndex={{ $menu->recipeIngredients->count() }};
document.getElementById('add-ingredient').addEventListener('click',function(){
    document.getElementById('ingredient-wrapper').insertAdjacentHTML('beforeend',`
        <div class="ingredient-item">
            <div class="ingredient-number">${ingredientIndex+1}</div>
            <div class="ingredient-field"><label>Nama Bahan</label><select name="recipe_ingredients[${ingredientIndex}][inventory_item_id]" class="form-select menu-form-control"><option value="">Pilih bahan</option>@foreach($inventoryItems as $inventoryItem)<option value="{{ $inventoryItem->id }}">{{ $inventoryItem->name }}</option>@endforeach</select></div>
            <div class="ingredient-field"><label>Jumlah</label><input type="number" name="recipe_ingredients[${ingredientIndex}][quantity]" class="form-control menu-form-control" placeholder="0" step="0.01"></div>
            <div class="ingredient-field"><label>Satuan</label><select name="recipe_ingredients[${ingredientIndex}][unit]" class="form-select menu-form-control"><option value="">Pilih satuan</option><option value="gram">Gram</option><option value="kg">Kg</option><option value="ml">Ml</option><option value="liter">Liter</option><option value="pcs">Pcs</option></select></div>
            <div class="ingredient-field"><label>Biaya</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="recipe_ingredients[${ingredientIndex}][cost]" class="form-control menu-form-control ingredient-cost" value="0" step="0.01" min="0"></div></div>
            <button type="button" class="btn remove-ingredient"><i class="bi bi-trash"></i></button>
        </div>`);
    ingredientIndex++;
});
</script>
@endpush
@endsection
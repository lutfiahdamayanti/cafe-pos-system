@extends('layouts.admin')
@section('title','Create Menu')
@section('content')

<div class="container-fluid py-4 menu-create-page">
    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card menu-create-card mb-4">
            <div class="card-header menu-section-header menu-green">
                <div class="d-flex align-items-center gap-3">
                    <div class="menu-section-icon"><i class="bi bi-cup-hot-fill"></i></div>
                    <div><h5 class="mb-1 fw-bold">Informasi Menu</h5><small class="text-muted">Informasi dasar menu yang akan ditampilkan.</small></div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">Nama Menu</label><input type="text" name="name" class="form-control form-control-lg" placeholder="Contoh: Caramel Latte" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Kategori</label><select name="category_id" class="form-select form-select-lg" required>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
                    <div class="col-12"><label class="form-label fw-semibold">Deskripsi</label><textarea name="description" rows="4" class="form-control" placeholder="Deskripsikan menu..."></textarea></div>
                    <div class="col-12"><label class="form-label fw-semibold">Komposisi Menu</label><small class="text-muted d-block mb-2">Komposisi yang akan ditampilkan kepada customer.</small><textarea name="ingredients" rows="4" class="form-control" placeholder="Contoh: Roti Burger, Daging Sapi, Keju, Selada, Tomat, Mayonaise"></textarea></div>
                </div>
            </div>
        </div>

        <div class="card menu-create-card mb-4">
            <div class="card-header menu-section-header menu-blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="menu-section-icon"><i class="bi bi-cash-stack"></i></div>
                    <div><h5 class="mb-1 fw-bold">Harga & Stok</h5><small class="text-muted">Atur harga jual, estimasi food cost, dan stok.</small></div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4"><label class="form-label fw-semibold">Harga</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="price" class="form-control" placeholder="0" required></div></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Estimasi Food Cost</label><div class="input-group"><span class="input-group-text"><i class="bi bi-calculator"></i></span><input type="number" name="food_cost" class="form-control" value="0" readonly></div><small class="text-muted">Perkiraan biaya bahan untuk 1 porsi.</small></div>
                    <input type="hidden" name="large_price" value="0">
                    <div class="col-md-4"><label class="form-label fw-semibold">Stok</label><div class="input-group"><span class="input-group-text"><i class="bi bi-box-seam"></i></span><input type="number" name="stock" value="0" class="form-control" required></div></div>
                </div>
            </div>
        </div>

        <div class="card menu-create-card mb-4">
            <div class="card-header menu-section-header menu-purple">
                <div class="d-flex align-items-center gap-3"><div class="menu-section-icon"><i class="bi bi-info-circle-fill"></i></div><div><h5 class="mb-1 fw-bold">Detail Menu</h5><small class="text-muted">Informasi tambahan mengenai menu.</small></div></div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">Waktu Pembuatan</label><div class="input-group"><input type="number" name="preparation_time" class="form-control" placeholder="Contoh: 10"><span class="input-group-text">Menit</span></div></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Rating</label><div class="input-group"><span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span><input type="number" name="rating" min="1" max="5" step="0.1" class="form-control" placeholder="1 - 5" required></div></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Kalori</label><div class="input-group"><input type="number" name="calories" class="form-control" placeholder="Contoh: 250"><span class="input-group-text">kcal</span></div></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Informasi Alergen</label><input type="text" name="allergen" class="form-control" placeholder="Contoh: Susu, Gluten"></div>
                </div>
            </div>
        </div>

        {{-- FOTO MENU --}}
        <div class="card menu-create-card mb-4">
            <div class="card-header menu-section-header menu-orange">
                <div class="d-flex align-items-center gap-3"><div class="menu-section-icon"><i class="bi bi-image-fill"></i></div><div><h5 class="mb-1 fw-bold">Foto Menu</h5><small class="text-muted">Upload foto untuk tampilan menu.</small></div></div>
            </div>
            <div class="card-body p-4"><label class="form-label fw-semibold">Foto Menu</label><input type="file" name="image" class="form-control" required><small class="text-muted">Gunakan gambar dengan kualitas yang jelas.</small></div>
        </div>

        <div class="card menu-create-card mb-4">
            <div class="card-header menu-section-header menu-red">
                <div class="d-flex align-items-center gap-3"><div class="menu-section-icon"><i class="bi bi-toggles"></i></div><div><h5 class="mb-1 fw-bold">Status Menu</h5><small class="text-muted">Tentukan status dan label menu.</small></div></div>
            </div>
            <div class="card-body p-4"><div class="row g-3">
                <div class="col-md-6"><label class="menu-check-card"><input class="form-check-input" type="checkbox" name="promo" value="1"><div><strong>Promo</strong><small>Menu sedang memiliki promo.</small></div><i class="bi bi-tag-fill text-danger"></i></label></div>
                <div class="col-md-6"><label class="menu-check-card"><input class="form-check-input" type="checkbox" name="best_seller" value="1"><div><strong>Best Seller</strong><small>Menu favorit atau paling laris.</small></div><i class="bi bi-fire text-warning"></i></label></div>
                <div class="col-md-6"><label class="menu-check-card"><input class="form-check-input" type="checkbox" name="new" value="1"><div><strong>Menu Baru</strong><small>Tandai menu sebagai menu baru.</small></div><i class="bi bi-stars text-primary"></i></label></div>
                <div class="col-md-6"><label class="menu-check-card"><input class="form-check-input" type="checkbox" name="is_active" value="1" checked><div><strong>Menu Aktif</strong><small>Menu tersedia untuk transaksi.</small></div><i class="bi bi-check-circle-fill text-success"></i></label></div>
            </div></div>
        </div>

        <div class="menu-edit-card mb-4">
            <div class="menu-edit-card-header"><div><h5 class="fw-bold mb-1"><i class="bi bi-journal-text text-success me-2"></i>Resep / Bahan Menu</h5><small class="text-muted">Masukkan bahan, jumlah, satuan, dan biaya bahan.</small></div></div>
            <div class="menu-edit-card-body"><div id="ingredient-wrapper"></div><button type="button" id="add-ingredient" class="btn btn-outline-success mt-3"><i class="bi bi-plus-circle me-1"></i>Tambah Bahan</button></div>
        </div>

        <div class="card menu-create-card mb-4">
            <div class="card-header menu-section-header menu-cyan">
                <div class="d-flex align-items-center gap-3"><div class="menu-section-icon"><i class="bi bi-sliders"></i></div><div><h5 class="mb-1 fw-bold">Pilihan Menu</h5><small class="text-muted">Contoh: ukuran, level gula, atau tambahan topping.</small></div></div>
            </div>
            <div class="card-body p-4"><div id="option-container"></div><button type="button" class="btn btn-outline-success rounded-3 mt-3" id="add-option"><i class="bi bi-plus-circle"></i> Tambah Pilihan</button></div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-5"><a href="{{ route('admin.menu.index') }}" class="btn btn-light border px-4 py-2 rounded-3">Batal</a><button type="submit" class="btn btn-success px-4 py-2 rounded-3"><i class="bi bi-check-circle"></i> Simpan Menu</button></div>
    </form>
</div>

@push('scripts')
<script>
let optionIndex=0;
let ingredientIndex=0;
document.getElementById('add-option').addEventListener('click',function(){
    let html=`
        <div class="card mt-3 option-card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3"><strong><i class="bi bi-sliders text-success"></i> Pilihan Menu</strong></div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Pilihan</label>
                    <input type="text" name="options[${optionIndex}][name]" class="form-control" placeholder="Contoh: Ukuran">
                </div>
                <div class="values mb-3"></div>
                <button type="button" class="btn btn-sm btn-success add-value rounded-3"><i class="bi bi-plus-circle"></i> Tambah Nilai</button>
            </div>
        </div>
    `;
    document.getElementById('option-container').insertAdjacentHTML('beforeend',html);
    optionIndex++;
});

document.addEventListener('click',function(e){
    if(e.target.classList.contains('add-value')){
        let values=e.target.previousElementSibling;
        let count=values.children.length;
        let card=e.target.closest('.option-card');
        let index=card.querySelector('input[name*="[name]"]').name.match(/\d+/)[0];
        values.insertAdjacentHTML('beforeend',`
            <div class="row g-2 mt-2 option-value-row">
                <div class="col-md-6"><input type="text" class="form-control" placeholder="Nilai" name="options[${index}][values][${count}][value]"></div>
                <div class="col-md-4">
                    <input type="number" class="form-control" placeholder="Harga Tambahan" name="options[${index}][values][${count}][price]">
                </div>
            </div>
        `);
    }
});

const inventoryItems=@json($inventoryItems);

function calculateIngredientCost(quantity,recipeUnit,inventoryUnit,costPerUnit){
    quantity=parseFloat(quantity)||0;
    costPerUnit=parseFloat(costPerUnit)||0;
    recipeUnit=(recipeUnit||'').toLowerCase().trim();
    inventoryUnit=(inventoryUnit||'').toLowerCase().trim();
    if(quantity<=0||costPerUnit<0)return 0;
    if(recipeUnit===inventoryUnit)return quantity*costPerUnit;
    if(recipeUnit==='gram'&&inventoryUnit==='kg')return(quantity/1000)*costPerUnit;
    if(recipeUnit==='kg'&&inventoryUnit==='gram')return(quantity*1000)*costPerUnit;
    if(recipeUnit==='ml'&&inventoryUnit==='liter')return(quantity/1000)*costPerUnit;
    if(recipeUnit==='liter'&&inventoryUnit==='ml')return(quantity*1000)*costPerUnit;
    return 0;
}

function updateIngredientFields(item){
    const select=item.querySelector('select[name*="[inventory_item_id]"]');
    const quantityInput=item.querySelector('input[name*="[quantity]"]');
    const unitInput=item.querySelector('select[name*="[unit]"]');
    const costInput=item.querySelector('input[name*="[cost]"]');
    if(!select||!quantityInput||!unitInput||!costInput)return;
    const inventoryItem=inventoryItems.find(inventory=>String(inventory.id)===String(select.value));
    if(!inventoryItem){costInput.value='0';updateFoodCost();return;}
    const quantity=parseFloat(quantityInput.value)||0;
    const cost=calculateIngredientCost(quantity,unitInput.value,inventoryItem.unit,inventoryItem.cost_per_unit);
    costInput.value=cost.toFixed(2);
    updateFoodCost();
}

function updateFoodCost(){
    const costInputs=document.querySelectorAll('input[name*="[cost]"]');
    let total=0;
    costInputs.forEach(function(input){
        total+=parseFloat(input.value)||0;
    });
    const foodCostInput=document.querySelector('input[name="food_cost"]');
    if(foodCostInput)foodCostInput.value=total.toFixed(2);
}

document.addEventListener('change',function(e){
    if(e.target.matches('select[name*="[inventory_item_id]"]')||e.target.matches('select[name*="[unit]"]')){
        const item=e.target.closest('.ingredient-item');
        if(item)updateIngredientFields(item);
    }
});

document.addEventListener('input',function(e){
    if(e.target.matches('input[name*="[quantity]"]')){
        const item=e.target.closest('.ingredient-item');
        if(item)updateIngredientFields(item);
    }
    if(e.target.matches('input[name*="[cost]"]'))updateFoodCost();
});

document.getElementById('add-ingredient').addEventListener('click',function(){
    document.getElementById('ingredient-wrapper').insertAdjacentHTML('beforeend',`
        <div class="ingredient-item">
            <div class="ingredient-number">${ingredientIndex+1}</div>
            <div class="ingredient-field">
                <label>Nama Bahan</label>
                <select name="recipe_ingredients[${ingredientIndex}][inventory_item_id]" class="form-select menu-form-control">
                    <option value="">Pilih bahan</option>
                    @foreach($inventoryItems as $inventoryItem)<option value="{{ $inventoryItem->id }}">{{ $inventoryItem->name }}</option>@endforeach
                </select>
            </div>
            <div class="ingredient-field"><label>Jumlah</label><input type="number" name="recipe_ingredients[${ingredientIndex}][quantity]" class="form-control menu-form-control" placeholder="0" step="0.01" min="0"></div>
            <div class="ingredient-field">
                <label>Satuan</label>
                <select name="recipe_ingredients[${ingredientIndex}][unit]" class="form-select menu-form-control">
                    <option value="">Pilih satuan</option>
                    <option value="gram">Gram</option>
                    <option value="kg">Kg</option>
                    <option value="ml">Ml</option>
                    <option value="liter">Liter</option>
                    <option value="pcs">Pcs</option>
                </select>
            </div>
            <div class="ingredient-field"><label>Biaya</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="recipe_ingredients[${ingredientIndex}][cost]" class="form-control menu-form-control ingredient-cost" value="0" step="0.01" min="0"></div></div>
            <button type="button" class="btn remove-ingredient" title="Hapus bahan"><i class="bi bi-trash"></i></button>
        </div>
    `);
    ingredientIndex++;
});

document.addEventListener('click',function(e){
    const button=e.target.closest('.remove-ingredient');
    if(button){
        button.closest('.ingredient-item').remove();
        updateFoodCost();
    }
});
</script>
@endpush
@endsection
@extends('layouts.admin')
@section('title', 'Restock Bahan Baku')
@section('content')

<div class="container-fluid inventory-page inventory-restock-page">
    @if(session('success'))
        <div class="alert alert-success inventory-restock-alert mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="restock-card">
        <div class="restock-card-header">
            <h5 class="mb-1">Tambah Stok</h5>
            <small>Pilih bahan dan masukkan jumlah stok yang baru diterima.</small>
        </div>

        <div class="restock-card-body">
            <form action="{{ route('admin.inventory.store-restock') }}" method="POST">
                @csrf
                <div class="restock-section-title">
                    <div class="restock-section-heading"><i class="bi bi-box-seam"></i><span>Informasi Restock</span></div>
                    <div class="restock-section-line"></div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="restock-label">Bahan Baku</label>
                        <select name="inventory_item_id" id="inventory_item_id" class="form-select restock-control" required>
                            <option value="">Pilih bahan baku</option>
                            @foreach($inventoryItems as $item)
                                <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-unit="{{ $item->unit }}" data-suppliers="{{ $item->suppliers->map(fn ($supplier) => [ 'id' => $supplier->id, 'name' => $supplier->name ])->values()->toJson() }}"> {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('inventory_item_id')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        <small class="restock-help">Pilih bahan yang stoknya ingin ditambahkan.</small>
                    </div>

                    <div class="col-md-4">
                        <label for="supplier_id" class="restock-label">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-select restock-control"><option value="">Pilih bahan terlebih dahulu</option></select>
                        @error('supplier_id')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        <small class="restock-help">Pilih pemasok yang mengirim bahan ini.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="restock-label">Jumlah Restock</label>
                        <div class="input-group restock-input-group">
                            <input type="number" name="quantity" id="restock_quantity" class="form-control restock-control" placeholder="0" min="0" step="0.01" required>
                            <span class="input-group-text" id="restock_unit">Satuan</span>
                        </div>
                        @error('quantity')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        <small class="restock-help">Masukkan jumlah bahan yang baru masuk.</small>
                    </div>

                    <div class="col-12">
                        <div class="restock-current-stock">
                            <div class="restock-info-icon"><i class="bi bi-box-seam-fill"></i></div>
                            <div>
                                <small>Stok Saat Ini</small>
                                <strong id="current_stock">Pilih bahan terlebih dahulu</strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div id="restock_preview" class="restock-preview d-none">
                            <div>
                                <small>Stok Setelah Restock</small>
                                <span>Jumlah stok setelah penambahan.</span>
                            </div>
                            <strong id="new_stock">0</strong>
                        </div>
                    </div>
                </div>

                <div class="restock-footer">
                    <a href="{{ route('admin.inventory.index') }}" class="btn restock-cancel-btn">Batal</a>
                    <button type="submit" class="btn restock-save-btn"><i class="bi bi-plus-circle me-1"></i>Simpan Restock</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const inventorySelect = document.getElementById('inventory_item_id');
const restockQuantity = document.getElementById('restock_quantity');
const currentStock = document.getElementById('current_stock');
const restockUnit = document.getElementById('restock_unit');
const restockPreview = document.getElementById('restock_preview');
const newStock = document.getElementById('new_stock');
const supplierSelect = document.getElementById('supplier_id');

function updateSupplierOptions() {
    const option = inventorySelect.options[inventorySelect.selectedIndex];
    supplierSelect.innerHTML = '';
    if (!option || !option.value) {
        supplierSelect.add(new Option('Pilih bahan terlebih dahulu', ''));
        supplierSelect.disabled = true;
        return;
    }
    let suppliers = [];
    try {
        suppliers = JSON.parse(option.dataset.suppliers || '[]');
    } catch (error) {
        suppliers = [];
    }
    if (suppliers.length === 0) {
        supplierSelect.add(new Option('Belum ada supplier terhubung', ''));
        supplierSelect.disabled = true;
        return;
    }
    supplierSelect.disabled = false;
    supplierSelect.add(new Option('Pilih supplier', ''));
    suppliers.forEach(supplier => supplierSelect.add(new Option(supplier.name, supplier.id)));
}

function updateRestockPreview() {
    const option = inventorySelect.options[inventorySelect.selectedIndex];
    if (!option || !option.value) {
        currentStock.textContent = 'Pilih bahan terlebih dahulu';
        restockUnit.textContent = 'Satuan';
        restockPreview.classList.add('d-none');
        return;
    }
    const stock = parseFloat(option.dataset.stock) || 0;
    const unit = option.dataset.unit || '';
    const quantity = parseFloat(restockQuantity.value) || 0;
    const formatNumber = value => new Intl.NumberFormat('id-ID').format(value);
    currentStock.textContent = formatNumber(stock) + ' ' + unit;
    restockUnit.textContent = unit;
    newStock.textContent = formatNumber(stock + quantity) + ' ' + unit;
    restockPreview.classList.remove('d-none');
}

inventorySelect.addEventListener('change', function () {
    updateSupplierOptions();
    updateRestockPreview();
});
restockQuantity.addEventListener('input', updateRestockPreview);

updateSupplierOptions();
updateRestockPreview();
</script>
@endpush
@endsection
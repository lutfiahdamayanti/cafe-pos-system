@extends('layouts.admin')
@section('title', 'Edit Supplier')
@section('content')

<div class="container-fluid inventory-page supplier-edit-page">
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show se-alert" role="alert">
        <strong><i class="bi bi-exclamation-circle-fill me-2"></i>Periksa kembali data yang kamu masukkan.</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <form action="{{ route('admin.inventory.suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')
        <section class="se-panel">
            <div class="se-panel-header">
                <div class="se-panel-icon"><i class="bi bi-person-vcard"></i></div>
                <div class="se-panel-heading">
                    <h5>Informasi Supplier</h5>
                    <p>Ubah identitas, kontak, alamat, dan catatan pemasok.</p>
                </div>
                <span class="se-section-number">01</span>
            </div>

            <div class="se-panel-body">
                <div class="se-section-caption"><i class="bi bi-person-lines-fill"></i> Data Identitas</div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="se-label"><i class="bi bi-building se-field-icon"></i> Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" class="form-control se-control @error('name') is-invalid @enderror" maxlength="255" placeholder="Contoh: CV Pangan Sejahtera" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contact_person" class="se-label"><i class="bi bi-person se-field-icon"></i> Nama Kontak / PIC</label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" class="form-control se-control" maxlength="255" placeholder="Nama orang yang bisa dihubungi">
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="se-label"><i class="bi bi-telephone se-field-icon"></i> Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" class="form-control se-control" maxlength="30" placeholder="Contoh: 081234567890">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="se-label"><i class="bi bi-envelope se-field-icon"></i> Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}" class="form-control se-control @error('email') is-invalid @enderror" maxlength="255" placeholder="supplier@email.com">
                        @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="address" class="se-label"><i class="bi bi-geo-alt se-field-icon"></i> Alamat Supplier</label>
                        <textarea name="address" id="address" rows="3" class="form-control se-control" placeholder="Masukkan alamat lengkap supplier">{{ old('address', $supplier->address) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="notes" class="se-label"><i class="bi bi-sticky se-field-icon"></i> Catatan Tambahan</label>
                        <textarea name="notes" id="notes" rows="3" maxlength="1000" class="form-control se-control" placeholder="Contoh: Jadwal pengiriman bahan baku">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </section>

        <section class="se-panel se-material-panel">
            <div class="se-panel-header">
                <div class="se-panel-icon"><i class="bi bi-box-seam"></i></div>
                <div class="se-panel-heading">
                    <h5>Bahan Baku yang Dipasok</h5>
                    <p>Pilih bahan dan atur jumlah pasokan supplier.</p>
                </div>
                <span class="se-material-count">{{ $inventoryItems->count() }} bahan</span>
            </div>

            <div class="se-panel-body">
                <div class="se-info-note">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Jumlah pasokan adalah informasi dari supplier dan tidak otomatis mengubah stok Inventory.</span>
                </div>

                @error('supplies')
                <div class="alert alert-danger py-2 mt-3">{{ $message }}</div>
                @enderror

                @php
                    $oldSupplies = old('supplies');
                    $hasOldSupplies = is_array($oldSupplies);
                @endphp

                <div class="row g-3 mt-1">
                    @forelse($inventoryItems as $item)
                        @php
                            $linkedItem = $supplier->inventoryItems->firstWhere('id', $item->id);
                            $supplyData = $hasOldSupplies ? ($oldSupplies[$item->id] ?? []) : [];
                            $isSelected = $hasOldSupplies ? !empty($supplyData['selected']) : $linkedItem !== null;
                            $quantityValue = $hasOldSupplies ? ($supplyData['quantity'] ?? '') : ($linkedItem ? $linkedItem->pivot->supply_quantity : '');
                            $unitValue = $hasOldSupplies ? ($supplyData['unit'] ?? '') : ($linkedItem ? $linkedItem->pivot->supply_unit : '');
                        @endphp

                        <div class="col-md-6 col-xl-4">
                            <div class="se-material-card {{ $isSelected ? 'is-selected' : '' }}" data-edit-supply-card>
                                <div class="se-material-top">
                                    <label for="supply_item_{{ $item->id }}" class="se-material-name">
                                        <input type="checkbox" id="supply_item_{{ $item->id }}" class="form-check-input se-material-checkbox" name="supplies[{{ $item->id }}][selected]" value="1" {{ $isSelected ? 'checked' : '' }}>
                                        <span>{{ $item->name }}</span>
                                    </label>
                                    <span class="se-material-icon"><i class="bi bi-box2"></i></span>
                                </div>

                                <div class="row g-2">
                                    <div class="col-7">
                                        <label for="supply_quantity_{{ $item->id }}" class="se-label"><i class="bi bi-calculator se-field-icon"></i> Jumlah Pasokan</label>
                                        <input type="number" id="supply_quantity_{{ $item->id }}" name="supplies[{{ $item->id }}][quantity]" class="form-control se-control" min="0.01" step="0.01" value="{{ $quantityValue }}" placeholder="Contoh: 2">
                                    </div>

                                    <div class="col-5">
                                        <label for="supply_unit_{{ $item->id }}" class="se-label"><i class="bi bi-rulers se-field-icon"></i> Satuan</label>
                                        <select id="supply_unit_{{ $item->id }}" name="supplies[{{ $item->id }}][unit]" class="form-select se-control">
                                            <option value="">Pilih</option>
                                            @foreach(['gram' => 'Gram', 'kg' => 'Kg', 'ml' => 'Ml', 'liter' => 'Liter', 'pcs' => 'Pcs', 'pack' => 'Pack', 'dus' => 'Dus', 'karung' => 'Karung', 'botol' => 'Botol', 'sak' => 'Sak'] as $value => $label)
                                            <option value="{{ $value }}" {{ $unitValue == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="se-stock-info">
                                    <i class="bi bi-archive"></i>
                                    <span>Stok saat ini: {{ number_format((float) $item->stock, 2, ',', '.') }} {{ $item->unit }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="se-empty">
                                <i class="bi bi-box-seam"></i>
                                <strong>Belum ada bahan baku</strong>
                                <span>Tambahkan bahan melalui menu Inventory terlebih dahulu.</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="se-panel se-bottom-panel">
            <div class="se-panel-header">
                <div class="se-panel-icon"><i class="bi bi-toggle-on"></i></div>
                <div class="se-panel-heading">
                    <h5>Status Supplier</h5>
                    <p>Atur apakah supplier masih digunakan.</p>
                </div>
            </div>

            <div class="se-panel-body">
                <label class="se-active-card">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ session()->hasOldInput() ? (old('is_active') ? 'checked' : '') : ($supplier->is_active ? 'checked' : '') }}>
                    <span class="se-active-icon"><i class="bi bi-check-circle-fill"></i></span>
                    <span class="se-active-text">
                        <strong>Supplier Aktif</strong>
                        <small>Supplier masih digunakan untuk memasok bahan baku.</small>
                    </span>
                </label>

                <div class="se-form-actions">
                    <a href="{{ route('admin.inventory.suppliers.index') }}" class="btn se-cancel-btn">Batal</a>
                    <button type="submit" class="btn se-save-btn"><i class="bi bi-check-circle-fill me-2"></i>Simpan Perubahan</button>
                </div>
            </div>
        </section>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-edit-supply-card]').forEach(function (card) {
        const checkbox = card.querySelector('.se-material-checkbox');
        function updateCard() {
            card.classList.toggle('is-selected', checkbox.checked);
        }
        checkbox.addEventListener('change', updateCard);
        updateCard();
    });
});
</script>
@endpush
@endsection
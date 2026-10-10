@extends('layouts.admin')
@section('title', 'Tambah Supplier')
@section('content')

<div class="container-fluid inventory-page supplier-create-page">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-exclamation-circle-fill me-2"></i>Periksa kembali data yang kamu masukkan.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <form action="{{ route('admin.inventory.suppliers.store') }}" method="POST">
        @csrf
        <div class="sc-form-card">
            <div class="sc-card-heading">
                <div class="sc-section-icon"><i class="bi bi-card-list"></i></div>
                <div><h5>Informasi Supplier</h5><p>Lengkapi identitas, kontak, dan bahan baku pemasok.</p></div>
            </div>

            <div class="sc-form-body">
                <div class="sc-section-title"><i class="bi bi-person-vcard"></i><span>Informasi Umum</span></div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="sc-label">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control sc-control @error('name') is-invalid @enderror" placeholder="Contoh: CV Pangan Sejahtera" maxlength="255" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact_person" class="sc-label">Nama Kontak / PIC</label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" class="form-control sc-control" placeholder="Nama orang yang bisa dihubungi" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="sc-label">Nomor Telepon</label>
                        <div class="input-group sc-input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control sc-control" placeholder="081234567890" maxlength="30">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="sc-label">Email</label>
                        <div class="input-group sc-input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control sc-control @error('email') is-invalid @enderror" placeholder="supplier@email.com" maxlength="255">
                        </div>
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label for="address" class="sc-label">Alamat Supplier</label>
                        <div class="sc-textarea-wrap">
                            <i class="bi bi-geo-alt"></i>
                            <textarea name="address" id="address" rows="3" class="form-control sc-control" placeholder="Masukkan alamat lengkap supplier">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="notes" class="sc-label">Catatan Tambahan</label>
                        <div class="sc-textarea-wrap">
                            <i class="bi bi-sticky"></i>
                            <textarea name="notes" id="notes" rows="3" maxlength="1000" class="form-control sc-control" placeholder="Contoh: Pengiriman setiap Senin dan Kamis">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="sc-section-title sc-section-divider">
                    <i class="bi bi-box-seam"></i><span>Bahan Baku yang Dipasok</span>
                    <span class="sc-section-count">{{ $inventoryItems->count() }} bahan</span>
                </div>
                <p class="sc-section-description">Pilih bahan yang dipasok dan tentukan jumlah beserta satuannya. Jumlah pasokan ini terpisah dari stok Inventory.</p>
                @error('supplies') <div class="alert alert-danger py-2">{{ $message }}</div> @enderror

                <div class="row g-3">
                    @forelse($inventoryItems as $item)
                        <div class="col-md-6 col-xl-4">
                            <div class="sc-supply-card" data-supply-card>
                                <div class="sc-supply-card-header">
                                    <div class="form-check">
                                        <input type="checkbox" id="supply_item_{{ $item->id }}" class="form-check-input sc-supply-checkbox" name="supplies[{{ $item->id }}][selected]" value="1" {{ old('supplies.' . $item->id . '.selected') ? 'checked' : '' }}>
                                        <label for="supply_item_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                                    </div>
                                    <div class="sc-supply-icon"><i class="bi bi-box2"></i></div>
                                </div>

                                <div class="sc-supply-fields">
                                    <div>
                                        <label for="supply_quantity_{{ $item->id }}" class="sc-label">Jumlah Pasokan</label>
                                        <input type="number" id="supply_quantity_{{ $item->id }}" name="supplies[{{ $item->id }}][quantity]" class="form-control sc-control" min="0.01" step="0.01" value="{{ old('supplies.' . $item->id . '.quantity') }}" placeholder="Contoh: 2">
                                    </div>
                                    <div>
                                        <label for="supply_unit_{{ $item->id }}" class="sc-label">Satuan</label>
                                        <select id="supply_unit_{{ $item->id }}" name="supplies[{{ $item->id }}][unit]" class="form-select sc-control">
                                            <option value="">Pilih satuan</option>
                                            @foreach(['gram' => 'Gram', 'kg' => 'Kg', 'ml' => 'Ml', 'liter' => 'Liter', 'pcs' => 'Pcs', 'pack' => 'Pack', 'dus' => 'Dus', 'karung' => 'Karung', 'botol' => 'Botol', 'sak' => 'Sak'] as $value => $label)
                                                <option value="{{ $value }}" {{ old('supplies.' . $item->id . '.unit') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="sc-supply-help"><i class="bi bi-info-circle"></i> Jumlah pasokan terpisah dari stok saat ini.</div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="sc-empty-material">
                                <div class="sc-empty-material-icon"><i class="bi bi-box-seam"></i></div>
                                <strong>Belum ada bahan baku</strong>
                                <span>Tambahkan bahan melalui menu Inventory terlebih dahulu.</span>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="sc-section-title sc-section-divider"><i class="bi bi-toggle-on"></i><span>Status Supplier</span></div>
                <label class="sc-active-card">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <span class="sc-active-icon"><i class="bi bi-check-circle-fill"></i></span>
                    <span class="sc-active-text">
                        <strong>Supplier Aktif</strong>
                        <small>Supplier masih digunakan untuk memasok bahan baku.</small>
                    </span>
                </label>

                <div class="sc-form-actions">
                    <a href="{{ route('admin.inventory.suppliers.index') }}" class="btn sc-cancel-btn"></i>Batal</a>
                    <button type="submit" class="btn btn-success sc-save-btn"><i class="bi bi-check-circle-fill me-2"></i>Simpan Supplier</button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-supply-card]').forEach(function (card) {
        const checkbox = card.querySelector('.sc-supply-checkbox');
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
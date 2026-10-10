@extends('layouts.admin')
@section('title', 'Stok Opname')
@section('content')

<div class="container-fluid inventory-page inventory-opname-page">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show opname-alert" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show opname-alert" role="alert">
            <strong><i class="bi bi-exclamation-circle me-1"></i>Data belum berhasil disimpan.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="row g-4 align-items-start">
        <div class="col-xl-4 col-lg-5">
            <div class="card opname-card opname-form-card">
                <div class="opname-card-heading">
                    <div class="d-flex align-items-center gap-3">
                        <div class="opname-heading-icon"><i class="bi bi-box-seam"></i></div>
                        <div>
                            <h5 class="mb-1">Catat Stok Fisik</h5>
                            <p class="mb-0">Masukkan hasil perhitungan bahan.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body opname-form-body">
                    <form action="{{ route('admin.inventory.opname.store') }}" method="POST">
                        @csrf
                        <div class="opname-form-group">
                            <label for="inventory_item_id" class="opname-label">Bahan Baku <span class="text-danger">*</span></label>
                            <select name="inventory_item_id" id="inventory_item_id" class="form-select opname-control" required>
                                <option value="">Pilih bahan baku</option>
                                @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-unit="{{ $item->unit }}" {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="opname-system-panel">
                            <div class="opname-system-icon"><i class="bi bi-database-check"></i></div>
                            <div class="flex-grow-1">
                                <span class="opname-panel-label">Stok Menurut Sistem</span>
                                <strong id="opname_system_stock">Pilih bahan dahulu</strong>
                            </div>
                        </div>

                        <div class="opname-form-group mt-4">
                            <label for="actual_stock" class="opname-label">Hasil Stok Fisik <span class="text-danger">*</span></label>
                            <div class="input-group opname-input-group">
                                <input type="number" name="actual_stock" id="actual_stock" class="form-control opname-control" min="0" step="0.01" value="{{ old('actual_stock') }}" placeholder="Masukkan jumlah fisik" required>
                                <span class="input-group-text" id="opname_unit">Satuan</span>
                            </div>
                            <small class="opname-help">Masukkan jumlah bahan yang benar-benar tersedia.</small>
                        </div>

                        <div class="opname-difference-panel" id="opname_preview">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="opname-difference-icon" id="opname_difference_icon"><i class="bi bi-calculator"></i></div>
                                <span class="opname-difference-title">Perkiraan Selisih</span>
                            </div>
                            <strong class="opname-difference-value" id="opname_difference">—</strong>
                            <p class="opname-difference-description mb-0" id="opname_difference_label">Pilih bahan dan masukkan stok fisik.</p>
                        </div>

                        <div class="opname-form-group mt-4">
                            <label for="notes" class="opname-label">Catatan <span class="text-muted fw-normal">(Opsional)</span></label>
                            <textarea name="notes" id="notes" rows="3" maxlength="1000" class="form-control opname-control opname-textarea" placeholder="Contoh: Selisih karena bahan rusak">{{ old('notes') }}</textarea>
                            <small class="opname-help">Catat alasan jika terdapat selisih stok.</small>
                        </div>

                        <div class="opname-form-actions">
                            <a href="{{ route('admin.inventory.index') }}" class="btn opname-cancel-btn">Batal</a>
                            <button type="submit" class="btn opname-submit-btn"><i class="bi bi-check-circle me-1"></i>Simpan Opname</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card opname-card opname-history-card">
                <div class="opname-card-heading">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="opname-heading-icon"><i class="bi bi-clock-history"></i></div>
                            <div>
                                <h5 class="mb-1">Riwayat Stok Opname</h5>
                                <p class="mb-0">Catatan pemeriksaan stok yang telah disimpan.</p>
                            </div>
                        </div>
                        <span class="opname-record-count"><i class="bi bi-journal-check me-1"></i>{{ $opnames->total() }} catatan</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 opname-table">
                        <thead>
                            <tr>
                                <th>No.</th><th>Tanggal</th><th>Bahan Baku</th><th>Stok Sistem</th><th>Stok Fisik</th><th>Selisih</th><th>Status / Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($opnames as $opname)
                                @php $difference = (float) $opname->difference; @endphp
                                <tr>
                                    <td class="opname-number">{{ $opnames->firstItem() + $loop->index }}</td>
                                    <td class="text-nowrap">
                                        <span class="opname-date">{{ $opname->created_at->format('d/m/Y') }}</span>
                                        <small class="opname-time"><i class="bi bi-clock me-1"></i>{{ $opname->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="opname-material-cell">
                                        <strong>{{ $opname->item_name }}</strong>
                                        <small class="opname-unit">{{ $opname->unit }}</small>
                                        @if($opname->notes)
                                            <small class="opname-note"><i class="bi bi-chat-left-text me-1"></i>{{ $opname->notes }}</small>
                                        @endif
                                    </td>
                                    <td class="opname-quantity">{{ number_format((float) $opname->system_stock, 2, ',', '.') }}</td>
                                    <td class="opname-quantity">{{ number_format((float) $opname->actual_stock, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="opname-difference-number {{ $difference > 0 ? 'difference-more' : ($difference < 0 ? 'difference-less' : 'difference-equal') }}">
                                            {{ $difference > 0 ? '+' : '' }}{{ number_format($difference, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="opname-status-actions">
                                            @if($difference > 0)
                                                <span class="opname-status status-more"><i class="bi bi-arrow-up-right me-1"></i>Lebih</span>
                                            @elseif($difference < 0)
                                                <span class="opname-status status-less"><i class="bi bi-arrow-down-right me-1"></i>Kurang</span>
                                            @else
                                                <span class="opname-status status-equal"><i class="bi bi-check-circle me-1"></i>Sesuai</span>
                                            @endif
                                            <form action="{{ route('admin.inventory.opname.destroy', $opname->id) }}" method="POST" class="opname-delete-form" onsubmit="return confirm('Yakin ingin menghapus riwayat opname {{ $opname->item_name }} ini? Stok Inventory tidak akan berubah.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="opname-delete-btn" title="Hapus riwayat" aria-label="Hapus riwayat opname {{ $opname->item_name }}"><i class="bi bi-trash3"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="opname-empty-state">
                                            <div class="opname-empty-icon"><i class="bi bi-clipboard2"></i></div>
                                            <h6>Belum Ada Riwayat</h6>
                                            <p>Hasil stok opname yang disimpan akan ditampilkan di sini.</p>
                                            <span><i class="bi bi-info-circle me-1"></i>Mulai dengan mencatat stok fisik.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($opnames->hasPages())
                    <div class="opname-pagination">{{ $opnames->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemSelect = document.getElementById('inventory_item_id');
    const actualInput = document.getElementById('actual_stock');
    const systemStock = document.getElementById('opname_system_stock');
    const unitLabel = document.getElementById('opname_unit');
    const differenceValue = document.getElementById('opname_difference');
    const differenceLabel = document.getElementById('opname_difference_label');
    const preview = document.getElementById('opname_preview');
    const differenceIcon = document.getElementById('opname_difference_icon');
    const formatNumber = value => new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(value);

    function updateOpnamePreview() {
        const option = itemSelect.options[itemSelect.selectedIndex];
        preview.classList.remove('is-more', 'is-less', 'is-equal');

        if (!option || !option.value) {
            systemStock.textContent = 'Pilih bahan dahulu';
            unitLabel.textContent = 'Satuan';
            differenceValue.textContent = '—';
            differenceLabel.textContent = 'Pilih bahan dan masukkan stok fisik.';
            differenceIcon.innerHTML = '<i class="bi bi-calculator"></i>';
            return;
        }

        const stock = Number(option.dataset.stock || 0);
        const unit = option.dataset.unit || '';
        systemStock.textContent = formatNumber(stock) + ' ' + unit;
        unitLabel.textContent = unit;

        if (actualInput.value.trim() === '') {
            differenceValue.textContent = '—';
            differenceLabel.textContent = 'Masukkan stok fisik untuk menghitung selisih.';
            differenceIcon.innerHTML = '<i class="bi bi-calculator"></i>';
            return;
        }

        const actual = Number(actualInput.value);
        if (!Number.isFinite(actual) || actual < 0) {
            differenceValue.textContent = '—';
            differenceLabel.textContent = 'Masukkan jumlah nol atau lebih.';
            return;
        }

        const difference = actual - stock;
        differenceValue.textContent = (difference > 0 ? '+' : '') + formatNumber(difference) + ' ' + unit;

        if (difference > 0) {
            preview.classList.add('is-more');
            differenceIcon.innerHTML = '<i class="bi bi-arrow-up-right-circle"></i>';
            differenceLabel.textContent = 'Stok fisik lebih banyak dari stok sistem.';
        } else if (difference < 0) {
            preview.classList.add('is-less');
            differenceIcon.innerHTML = '<i class="bi bi-arrow-down-right-circle"></i>';
            differenceLabel.textContent = 'Stok fisik lebih sedikit dari stok sistem.';
        } else {
            preview.classList.add('is-equal');
            differenceIcon.innerHTML = '<i class="bi bi-check-circle"></i>';
            differenceLabel.textContent = 'Stok fisik sesuai dengan stok sistem.';
        }
    }

    itemSelect.addEventListener('change', updateOpnamePreview);
    actualInput.addEventListener('input', updateOpnamePreview);
    updateOpnamePreview();
});
</script>
@endpush
@endsection
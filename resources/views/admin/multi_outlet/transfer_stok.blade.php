@extends('layouts.admin')
@section('title', 'Multi-Outlet: Transfer Stok Antar Cabang')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-arrow-left-right text-warning"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Transfer Stok Antar Cabang</h4>
                <small class="text-muted">Kelola distribusi bahan baku dan perlengkapan dari Gudang Pusat atau transfer darurat antar gerai.</small>
            </div>
        </div>

        <button type="button" class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTransferStok">
            <i class="bi bi-plus-circle me-1"></i> Buat Transfer Stok
        </button>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Pengiriman</span>
                    <i class="bi bi-box-arrow-right fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $totalTransfers }}</h3>
                <small class="text-muted">Semua status riwayat</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Menunggu Review</span>
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $pendingCount }}</h3>
                <small class="text-muted">Status: Pending</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Dalam Pengiriman</span>
                    <i class="bi bi-truck fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ $inTransitCount }}</h3>
                <small class="text-muted">Status: In Transit</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Selesai Diterima</span>
                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $completedCount }}</h3>
                <small class="text-muted">Status: Completed</small>
            </div>
        </div>
    </div>

    {{-- FILTER RIWAYAT TRANSFER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.multi-outlet.transfer-stok') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <select name="status" class="form-select">
                        <option value="">Semua Status Pengiriman</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending (Menunggu Persetujuan)</option>
                        <option value="In Transit" {{ request('status') === 'In Transit' ? 'selected' : '' }}>In Transit (Sedang Dikirim)</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed (Telah Diterima)</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <select name="outlet_id" class="form-select">
                        <option value="">Semua Outlet Terkait</option>
                        @foreach($allOutlets as $o)
                            <option value="{{ $o->id }}" {{ request('outlet_id') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL RIWAYAT TRANSFER STOK --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-warning"></i> Log Riwayat Transfer Bahan Antar Cabang</h6>
            <span class="badge bg-light text-dark">{{ $transfers->count() }} Pengiriman</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No. Transfer & Tanggal</th>
                            <th>Asal Pengiriman</th>
                            <th>Cabang Tujuan</th>
                            <th>Item & Kuantitas Bahan</th>
                            <th>Status Transfer</th>
                            <th>Pemohon & Catatan</th>
                            <th class="text-end pe-3">Update Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $trf)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold text-dark font-monospace d-block">{{ $trf->transfer_number }}</span>
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $trf->transfer_date ? $trf->transfer_date->format('d M Y') : '-' }}</small>
                                </td>
                                <td>
                                    @if($trf->fromOutlet)
                                        <span class="fw-semibold text-dark d-block">{{ $trf->fromOutlet->name }}</span>
                                        <small class="text-muted">{{ $trf->fromOutlet->code }}</small>
                                    @else
                                        <span class="badge bg-dark text-white"><i class="bi bi-box-seam me-1"></i> Gudang Pusat (HQ)</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-primary d-block">{{ $trf->toOutlet->name }}</span>
                                    <small class="text-muted">{{ $trf->toOutlet->code }}</small>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($trf->items as $item)
                                            <li>
                                                <i class="bi bi-dot text-primary"></i>
                                                <strong>{{ $item->item_name }}</strong>:
                                                <span class="badge bg-light text-dark border">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @if($trf->status === 'Pending')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Menunggu Review</span>
                                    @elseif($trf->status === 'In Transit')
                                        <span class="badge bg-info text-dark"><i class="bi bi-truck me-1"></i> Dalam Pengiriman</span>
                                    @elseif($trf->status === 'Completed')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Selesai Diterima</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-dark d-block">Diajukan: <strong>{{ $trf->requester->name ?? 'Admin' }}</strong></small>
                                    @if($trf->notes)
                                        <small class="text-muted fst-italic d-block" style="max-width: 220px;">"{{ $trf->notes }}"</small>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Ubah Status
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            @if($trf->status === 'Pending')
                                                <li>
                                                    <form action="{{ route('admin.multi-outlet.transfer-stok.update-status', $trf) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="In Transit">
                                                        <button type="submit" class="dropdown-item text-info">
                                                            <i class="bi bi-truck me-2"></i> Kirim (In Transit)
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.multi-outlet.transfer-stok.update-status', $trf) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="Completed">
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="bi bi-check-circle me-2"></i> Setujui & Selesai Langsung
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.multi-outlet.transfer-stok.update-status', $trf) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="Cancelled">
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-x-circle me-2"></i> Tolak / Batalkan
                                                        </button>
                                                    </form>
                                                </li>
                                            @elseif($trf->status === 'In Transit')
                                                <li>
                                                    <form action="{{ route('admin.multi-outlet.transfer-stok.update-status', $trf) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="Completed">
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="bi bi-check2-circle me-2"></i> Konfirmasi Diterima di Cabang
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.multi-outlet.transfer-stok.update-status', $trf) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="Cancelled">
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-x-circle me-2"></i> Batalkan Pengiriman
                                                        </button>
                                                    </form>
                                                </li>
                                            @else
                                                <li><span class="dropdown-item-text text-muted small">Status telah final</span></li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-arrow-left-right fs-2 d-block mb-2"></i> Belum ada aktivitas transfer stok bahan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL BUAT TRANSFER STOK --}}
<div class="modal fade" id="modalTransferStok" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.multi-outlet.transfer-stok.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-arrow-left-right text-warning me-2"></i> Form Permintaan Transfer Stok Bahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Dari (Asal Pengiriman)</label>
                            <select name="from_outlet_id" class="form-select">
                                <option value="">🏢 Gudang Pusat (Central Warehouse HQ)</option>
                                @foreach($allOutlets as $out)
                                    <option value="{{ $out->id }}">{{ $out->name }} ({{ $out->code }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih Gudang Pusat atau cabang lain sebagai sumber barang.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kepada (Cabang Tujuan) <span class="text-danger">*</span></label>
                            <select name="to_outlet_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Cabang Penerima...</option>
                                @foreach($allOutlets as $out)
                                    <option value="{{ $out->id }}">{{ $out->name }} ({{ $out->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Pengiriman <span class="text-danger">*</span></label>
                            <input type="date" name="transfer_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Catatan / Ekspedisi</label>
                            <input type="text" name="notes" class="form-control" placeholder="Contoh: Dikirim via mobil pick-up internal / kurir instan">
                        </div>
                    </div>

                    <h6 class="fw-bold border-bottom pb-2 mb-3">Daftar Bahan / Barang yang Ditransfer</h6>

                    <div id="transferItemsContainer">
                        <div class="row g-2 align-items-center mb-2 transfer-row">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold d-md-none">Pilih Item</label>
                                <select class="form-select item-select" onchange="onSelectItem(this)">
                                    <option value="">-- Pilih dari Gudang Pusat atau Ketik Manual --</option>
                                    @foreach($warehouseItems as $whItem)
                                        <option value="{{ $whItem->id }}" data-name="{{ $whItem->item_name }}" data-unit="{{ $whItem->unit }}">
                                            {{ $whItem->item_name }} (Stok WH: {{ $whItem->stock_quantity }} {{ $whItem->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="warehouse_stock_ids[]" class="item-wh-id" value="">
                                <input type="text" name="item_names[]" class="form-control form-control-sm mt-1 item-name-input" placeholder="Nama item / bahan baku" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold d-md-none">Jumlah</label>
                                <input type="number" step="0.01" name="quantities[]" class="form-control" placeholder="Kuantitas" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold d-md-none">Satuan</label>
                                <input type="text" name="units[]" class="form-control item-unit-input" placeholder="kg, liter, pcs, pack" required>
                            </div>
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeTransferRow(this)" title="Hapus baris">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addTransferRow()">
                        <i class="bi bi-plus me-1"></i> Tambah Item Lain
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold"><i class="bi bi-send me-1"></i> Buat Pengiriman Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function onSelectItem(selectElem) {
        const selectedOption = selectElem.options[selectElem.selectedIndex];
        const row = selectElem.closest('.transfer-row');
        const whIdInput = row.querySelector('.item-wh-id');
        const nameInput = row.querySelector('.item-name-input');
        const unitInput = row.querySelector('.item-unit-input');

        if (selectedOption && selectedOption.value) {
            whIdInput.value = selectedOption.value;
            nameInput.value = selectedOption.getAttribute('data-name');
            unitInput.value = selectedOption.getAttribute('data-unit');
        } else {
            whIdInput.value = '';
        }
    }

    function addTransferRow() {
        const container = document.getElementById('transferItemsContainer');
        const firstRow = container.querySelector('.transfer-row');
        const newRow = firstRow.cloneNode(true);

        // Reset values
        newRow.querySelector('.item-select').selectedIndex = 0;
        newRow.querySelector('.item-wh-id').value = '';
        newRow.querySelector('.item-name-input').value = '';
        newRow.querySelector('input[name="quantities[]"]').value = '';
        newRow.querySelector('.item-unit-input').value = '';

        container.appendChild(newRow);
    }

    function removeTransferRow(btn) {
        const container = document.getElementById('transferItemsContainer');
        if (container.querySelectorAll('.transfer-row').length > 1) {
            btn.closest('.transfer-row').remove();
        } else {
            alert('Minimal satu item harus ada dalam transfer stok.');
        }
    }
</script>
@endpush

@endsection

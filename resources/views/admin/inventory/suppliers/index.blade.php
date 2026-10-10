
@extends('layouts.admin')

@section('title', 'Data Supplier')

@section('content')
<div class="container-fluid supplier-simple-page">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- FILTER SUPPLIER --}}
    <div class="supplier-simple-card supplier-simple-filter">
        <div class="supplier-simple-filter-grid">

            <div>
                <label for="supplierSearch"
                       class="supplier-simple-label">
                    Cari Supplier
                </label>

                <div class="input-group supplier-simple-input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>

                    <input type="text"
                           id="supplierSearch"
                           class="form-control"
                           placeholder="Cari nama supplier..."
                           autocomplete="off">
                </div>
            </div>

            <div>
                <label for="supplierStatusFilter"
                       class="supplier-simple-label">
                    Status Supplier
                </label>

                <select id="supplierStatusFilter"
                        class="form-select supplier-simple-control">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="supplier-simple-reset-wrap">
                <button type="button"
                        id="resetSupplierSearch"
                        class="btn btn-outline-secondary supplier-simple-reset">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reset Filter
                </button>
            </div>

        </div>
    </div>

    {{-- DAFTAR SUPPLIER --}}
    <div class="supplier-simple-card supplier-simple-list">

        <div class="supplier-simple-heading">
            <div>
                <h4>Daftar Supplier</h4>
                <p>Daftar pemasok dan bahan baku yang disediakan.</p>
            </div>

            <a href="{{ route('admin.inventory.suppliers.create') }}"
               class="btn btn-success supplier-simple-add">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Supplier
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 supplier-simple-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Supplier</th>
                        <th>Kontak</th>
                        <th>Bahan Dipasok</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr class="supplier-simple-row"
                            data-search="{{ strtolower($supplier->name . ' ' . ($supplier->contact_person ?? '') . ' ' . ($supplier->phone ?? '') . ' ' . ($supplier->email ?? '') . ' ' . ($supplier->address ?? '') . ' ' . $supplier->inventoryItems->pluck('name')->implode(' ')) }}"
                            data-status="{{ $supplier->is_active ? 'aktif' : 'nonaktif' }}">

                            <td>
                                {{ $suppliers->firstItem() + $loop->index }}
                            </td>

                            {{-- SUPPLIER --}}
                            <td>
                                <div class="supplier-simple-identity">
                                    <strong>{{ $supplier->name }}</strong>
                                    <span>
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $supplier->address ?: 'Alamat belum diisi' }}
                                    </span>
                                </div>
                            </td>

                            {{-- KONTAK --}}
                            <td>
                                <div class="supplier-simple-contact">
                                    <div>
                                        <i class="bi bi-person"></i>
                                        <span>{{ $supplier->contact_person ?: '-' }}</span>
                                    </div>

                                    <div>
                                        <i class="bi bi-telephone"></i>
                                        <span>{{ $supplier->phone ?: '-' }}</span>
                                    </div>

                                    @if($supplier->email)
                                        <div>
                                            <i class="bi bi-envelope"></i>
                                            <span>{{ $supplier->email }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- BAHAN DIPASOK --}}
                            <td>
                                <div class="supplier-simple-materials">
                                    @forelse($supplier->inventoryItems as $item)
                                        <div class="supplier-simple-material">
                                            <strong>{{ $item->name }}</strong>
                                            <span>
                                                {{ number_format((float) $item->pivot->supply_quantity, 2, ',', '.') }}
                                                {{ $item->pivot->supply_unit }}
                                            </span>
                                        </div>
                                    @empty
                                        <span class="supplier-simple-unlinked">
                                            Belum dihubungkan
                                        </span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($supplier->is_active)
                                    <span class="supplier-simple-status is-active">
                                        Aktif
                                    </span>
                                @else
                                    <span class="supplier-simple-status is-inactive">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="supplier-simple-actions">
                                    <a href="{{ route('admin.inventory.suppliers.edit', $supplier) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit supplier"
                                       aria-label="Edit supplier">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.inventory.suppliers.destroy', $supplier) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Hapus supplier"
                                                aria-label="Hapus supplier">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="supplier-simple-empty">
                                    <i class="bi bi-truck"></i>
                                    <strong>Belum ada data supplier</strong>
                                    <span>Tambahkan supplier untuk mulai mengelola pemasok.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="supplierNoResult" style="display:none;">
                        <td colspan="6">
                            <div class="supplier-simple-empty">
                                <i class="bi bi-search"></i>
                                <strong>Supplier tidak ditemukan</strong>
                                <span>Coba ubah kata kunci atau status filter.</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($suppliers->hasPages())
            <div class="supplier-simple-pagination">
                {{ $suppliers->links() }}
            </div>
        @endif

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('supplierSearch');
    const statusFilter = document.getElementById('supplierStatusFilter');
    const reset = document.getElementById('resetSupplierSearch');
    const rows = document.querySelectorAll('.supplier-simple-row');
    const noResult = document.getElementById('supplierNoResult');

    function filterSuppliers() {
        const keyword = search.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;
        let visible = 0;

        rows.forEach(function (row) {
            const text = row.dataset.search || '';
            const status = row.dataset.status || '';

            const matchesSearch = text.includes(keyword);
            const matchesStatus =
                selectedStatus === '' || status === selectedStatus;

            const matches = matchesSearch && matchesStatus;

            row.style.display = matches ? '' : 'none';

            if (matches) visible++;
        });

        if (noResult) {
            noResult.style.display =
                rows.length > 0 && visible === 0 ? '' : 'none';
        }
    }

    search.addEventListener('input', filterSuppliers);
    statusFilter.addEventListener('change', filterSuppliers);

    reset.addEventListener('click', function () {
        search.value = '';
        statusFilter.value = '';
        filterSuppliers();
    });
});
</script>
@endpush

@endsection

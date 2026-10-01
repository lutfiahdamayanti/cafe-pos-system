@extends('layouts.admin')
@section('title', 'Multi-Outlet: Kelola Banyak Cabang')
@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3 fs-5">
                <i class="bi bi-shop text-primary"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Kelola Banyak Cabang (Multi-Outlet Management)</h4>
                <small class="text-muted">Kelola data seluruh gerai kafe, jam operasional, manager cabang, dan status operasional.</small>
            </div>
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahCabang">
            <i class="bi bi-plus-circle me-1"></i> Tambah Cabang Baru
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
                    <span class="text-muted small">Total Gerai</span>
                    <i class="bi bi-buildings fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $totalOutlets }}</h3>
                <small class="text-muted">Cabang terdaftar</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Cabang Aktif</span>
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $activeOutlets }}</h3>
                <small class="text-muted">Beroperasi normal</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Gudang Pusat / Hub</span>
                    <i class="bi bi-box-seam fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $warehouseCount }}</h3>
                <small class="text-muted">Titik distribusi utama</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Cakupan Wilayah</span>
                    <i class="bi bi-geo-alt fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ $totalCities }} Kota</h3>
                <small class="text-muted">Sebaran operasional</small>
            </div>
        </div>
    </div>

    {{-- FILTER & PENCARIAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.multi-outlet.cabang') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama gerai, kode outlet, kota, atau manager..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua Status Operasional</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
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

    {{-- TABEL DAFTAR CABANG --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-shop me-2 text-primary"></i> Daftar Seluruh Cabang & Outlet</h6>
            <span class="badge bg-light text-dark">{{ $outlets->count() }} Cabang</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode & Nama Gerai</th>
                            <th>Kota & Alamat</th>
                            <th>Kontak & Jam Buka</th>
                            <th>Manager Cabang</th>
                            <th>SDM & Order</th>
                            <th>Status Gerai</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($outlets as $outlet)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 me-2 fs-5">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $outlet->name }}</span>
                                            <span class="badge bg-secondary font-monospace">{{ $outlet->code }}</span>
                                            @if($outlet->is_central_warehouse)
                                                <span class="badge bg-warning text-dark"><i class="bi bi-building-check me-1"></i> Hub / Gudang Pusat</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $outlet->city ?? '-' }}</span>
                                    <small class="text-muted d-block" style="max-width: 250px;">{{ $outlet->address ?? '-' }}</small>
                                </td>
                                <td>
                                    <small class="text-dark d-block"><i class="bi bi-telephone text-muted me-1"></i> {{ $outlet->phone ?? '-' }}</small>
                                    <small class="text-muted d-block"><i class="bi bi-clock text-muted me-1"></i> {{ $outlet->operating_hours ?? '08:00 - 22:00' }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $outlet->manager_name ?? 'Belum Ditentukan' }}</span>
                                    @if($outlet->email)
                                        <small class="text-muted d-block">{{ $outlet->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border me-1"><i class="bi bi-people me-1"></i> {{ $outlet->users_count }} Staff</span>
                                    <span class="badge bg-light text-primary border"><i class="bi bi-receipt me-1"></i> {{ $outlet->orders_count }} Order</span>
                                </td>
                                <td>
                                    @if($outlet->status === 'active')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Kelola
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEditCabang{{ $outlet->id }}">
                                                    <i class="bi bi-pencil me-2 text-primary"></i> Edit Data Cabang
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.multi-outlet.cabang.toggle', $outlet) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        @if($outlet->status === 'active')
                                                            <i class="bi bi-pause-circle me-2 text-warning"></i> Nonaktifkan Gerai
                                                        @else
                                                            <i class="bi bi-play-circle me-2 text-success"></i> Aktifkan Gerai
                                                        @endif
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.multi-outlet.cabang.destroy', $outlet) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i> Hapus Cabang
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT CABANG --}}
                            <div class="modal fade" id="modalEditCabang{{ $outlet->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.multi-outlet.cabang.update', $outlet) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Data Cabang</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label small fw-bold">Nama Cabang / Gerai</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $outlet->name }}" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-bold">Kode Outlet</label>
                                                        <input type="text" name="code" class="form-control" value="{{ $outlet->code }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Kota</label>
                                                        <input type="text" name="city" class="form-control" value="{{ $outlet->city }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Manager Cabang</label>
                                                        <input type="text" name="manager_name" class="form-control" value="{{ $outlet->manager_name }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-bold">Alamat Lengkap</label>
                                                        <textarea name="address" class="form-control" rows="2">{{ $outlet->address }}</textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">No. Telepon</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $outlet->phone }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Email Outlet</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $outlet->email }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Jam Operasional</label>
                                                        <input type="text" name="operating_hours" class="form-control" value="{{ $outlet->operating_hours }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Status Operasional</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="active" {{ $outlet->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                                            <option value="inactive" {{ $outlet->status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-check form-switch mt-2">
                                                            <input class="form-check-input" type="checkbox" name="is_central_warehouse" id="whSwitch{{ $outlet->id }}" {{ $outlet->is_central_warehouse ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="whSwitch{{ $outlet->id }}">
                                                                Tetapkan sebagai Gudang Pusat / Hub Utama Distribusi
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-shop fs-2 d-block mb-2"></i> Belum ada cabang yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL TAMBAH CABANG BARU --}}
<div class="modal fade" id="modalTambahCabang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.multi-outlet.cabang.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i> Tambah Cabang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nama Cabang / Gerai <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Kopi Kita - Kemang" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kode Outlet <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="OUT-004" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kota</label>
                            <input type="text" name="city" class="form-control" placeholder="Jakarta Selatan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Manager Cabang</label>
                            <input type="text" name="manager_name" class="form-control" placeholder="Nama Penanggung Jawab">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Jl. Kemang Raya No. 10..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">No. Telepon Gerai</label>
                            <input type="text" name="phone" class="form-control" placeholder="021-xxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Gerai</label>
                            <input type="email" name="email" class="form-control" placeholder="kemang@kopikita.id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jam Operasional</label>
                            <input type="text" name="operating_hours" class="form-control" value="08:00 - 22:00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Operasional</label>
                            <select name="status" class="form-select" required>
                                <option value="active" selected>Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_central_warehouse" id="whSwitchNew">
                                <label class="form-check-label" for="whSwitchNew">
                                    Tetapkan sebagai Gudang Pusat / Hub Utama Distribusi
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Simpan Cabang</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

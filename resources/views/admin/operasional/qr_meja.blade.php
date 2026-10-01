@extends('layouts.admin')
@section('title', 'Operasional: QR Table Ordering')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                <i class="bi bi-qr-code-scan text-success"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">QR Table Ordering (Pemesanan Meja Mandiri)</h4>
                <small class="text-muted">Kelola nomor meja, pantau status keterisian gerai secara real-time, dan unduh/cetak stand QR meja pelanggan.</small>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Semua Stand Meja
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahMeja">
                <i class="bi bi-plus-circle me-1"></i> Tambah Meja Baru
            </button>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Meja Kafe</span>
                    <i class="bi bi-grid-3x3 fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $totalTables }} Meja</h3>
                <small class="text-muted">{{ $totalSeatingCapacity }} kapasitas kursi</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Meja Tersedia (Kosong)</span>
                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $availableCount }}</h3>
                <small class="text-muted">Siap ditempati pengunjung</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Meja Terisi (Occupied)</span>
                    <i class="bi bi-people-fill fs-4 text-danger"></i>
                </div>
                <h3 class="mb-0 fw-bold text-danger">{{ $occupiedCount }}</h3>
                <small class="text-muted">Sedang bersantap / dine-in</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Meja Reservasi</span>
                    <i class="bi bi-bookmark-star fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $reservedCount }}</h3>
                <small class="text-muted">Booking waktu tertentu</small>
            </div>
        </div>
    </div>

    {{-- FILTER ZONE & STATUS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.operasional.qr-meja') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor meja (misal: A01)..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="zone" class="form-select">
                        <option value="">Semua Zona / Area</option>
                        @foreach($zones as $z)
                            <option value="{{ $z }}" {{ request('zone') === $z ? 'selected' : '' }}>{{ $z }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia (Kosong)</option>
                        <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Terisi (Occupied)</option>
                        <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Direservasi</option>
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

    {{-- GRID KARTU MEJA DENGAN QR CODE --}}
    <div class="row g-3 mb-4">
        @forelse($tables as $table)
            @php
                $qrUrl = $table->qr_url;
                $qrImgSrc = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrUrl);
            @endphp
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm border-top border-4 {{ $table->status === 'available' ? 'border-success' : ($table->status === 'occupied' ? 'border-danger' : 'border-warning') }}">
                    <div class="card-body p-3 text-center">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt me-1"></i> {{ $table->zone }}</span>
                            @if($table->status === 'available')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($table->status === 'occupied')
                                <span class="badge bg-danger">Terisi</span>
                            @else
                                <span class="badge bg-warning text-dark">Reservasi</span>
                            @endif
                        </div>

                        <h4 class="fw-bold mb-1 text-dark">{{ $table->table_number }}</h4>
                        <small class="text-muted d-block mb-2">Kapasitas: <strong>{{ $table->capacity }} Orang</strong></small>

                        {{-- QR CODE PREVIEW --}}
                        <div class="bg-light p-2 rounded d-inline-block shadow-sm mb-2">
                            <img src="{{ $qrImgSrc }}" alt="QR {{ $table->table_number }}" style="width: 110px; height: 110px;" class="img-fluid">
                        </div>

                        @if($table->current_customer)
                            <div class="small fw-semibold text-primary mb-2">
                                <i class="bi bi-person-fill me-1"></i> {{ $table->current_customer }}
                            </div>
                        @else
                            <div class="small text-muted mb-2">Siap untuk pesanan baru</div>
                        @endif

                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTent{{ $table->id }}" title="Lihat & Cetak Stand Meja">
                                <i class="bi bi-qr-code me-1"></i> Stand Meja
                            </button>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Status
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <form action="{{ route('admin.operasional.qr-meja.update-status', $table) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="available">
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="bi bi-check2-circle me-2"></i> Kosongkan Meja (Tersedia)
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.operasional.qr-meja.update-status', $table) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="occupied">
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-people-fill me-2"></i> Set Terisi (Occupied)
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.operasional.qr-meja.update-status', $table) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="reserved">
                                            <button type="submit" class="dropdown-item text-warning">
                                                <i class="bi bi-bookmark-fill me-2"></i> Set Reservasi
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.operasional.qr-meja.destroy', $table) }}" method="POST" onsubmit="return confirm('Hapus meja ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Hapus Meja
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL PRINTABLE TENT CARD STAND MEJA --}}
            <div class="modal fade" id="modalTent{{ $table->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body p-4 text-center" id="printableTent{{ $table->id }}">
                            <div class="border border-3 border-dark rounded-4 p-4 shadow-sm bg-white">
                                <h5 class="fw-bold text-dark mb-0">KOPI KITA</h5>
                                <small class="text-muted font-monospace text-uppercase" style="letter-spacing: 2px;">Cafe & Roastery</small>
                                
                                <div class="my-3 py-2 bg-dark text-white rounded-3">
                                    <h3 class="fw-bold mb-0">{{ $table->table_number }}</h3>
                                    <small>{{ $table->zone }}</small>
                                </div>

                                <img src="{{ $qrImgSrc }}" alt="QR" class="img-fluid my-2 border p-2 rounded" style="width: 150px; height: 150px;">

                                <h6 class="fw-bold mt-2 mb-1">SCAN UNTUK PESAN</h6>
                                <p class="small text-muted mb-0" style="font-size: 0.75rem;">
                                    Buka kamera ponsel Anda, arahkan ke QR untuk memesan menu & langsung bayar di meja.
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Cetak Stand Meja Ini
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-qr-code-scan fs-1 d-block mb-2"></i> Belum ada meja kafe terdaftar. Klik "Tambah Meja Baru".
            </div>
        @endforelse
    </div>

</div>

{{-- MODAL TAMBAH MEJA BARU --}}
<div class="modal fade" id="modalTambahMeja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.operasional.qr-meja.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-success me-2"></i> Tambah Meja QR Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nomor / Nama Meja <span class="text-danger">*</span></label>
                            <input type="text" name="table_number" class="form-control" placeholder="Contoh: Meja A09" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kapasitas Kursi <span class="text-danger">*</span></label>
                            <input type="number" name="capacity" class="form-control" value="4" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Zona / Area Meja <span class="text-danger">*</span></label>
                            <input type="text" name="zone" class="form-control" placeholder="Indoor AC / Outdoor Terrace / VIP" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Awal</label>
                            <select name="status" class="form-select" required>
                                <option value="available" selected>Tersedia (Kosong)</option>
                                <option value="occupied">Terisi (Occupied)</option>
                                <option value="reserved">Direservasi</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Cabang / Gerai</label>
                            <select name="outlet_id" class="form-select">
                                <option value="">Semua Cabang / Kantor Pusat</option>
                                @foreach($allOutlets as $o)
                                    <option value="{{ $o->id }}">{{ $o->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check2 me-1"></i> Simpan Meja</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

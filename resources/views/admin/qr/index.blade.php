@extends('layouts.admin')
@section('title', 'QR Pemesanan')
@section('content')

<div class="container-fluid">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3"><i class="bi bi-qr-code fs-3"></i></div>
                <div>
                    <h5 class="fw-bold mb-1">Buat QR Meja</h5>
                    <p class="text-muted mb-0 small">Masukkan nomor atau nama meja untuk membuat QR Code.</p>
                </div>
            </div>

            <form action="{{ route('admin.qr.store') }}" method="POST">
                @csrf
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor / Nama Meja</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-table"></i></span>
                            <input type="text" name="table_number" class="form-control" placeholder="Contoh: A01" required>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-qr-code me-1"></i>Buat QR</button>
                        <button type="button" onclick="window.print()" class="btn btn-success"><i class="bi bi-printer me-1"></i>Print QR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if($tables->count())
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-1">QR Meja</h5>
                <p class="text-muted small mb-0">QR Code yang sudah dibuat</p>
            </div>
            <span class="badge bg-primary rounded-pill px-3 py-2"><i class="bi bi-grid me-1"></i>{{ $tables->count() }} Meja</span>
        </div>
        <div class="row qr-print-area">
            @foreach($tables as $table)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2"><i class="bi bi-table fs-5"></i></div>
                                <div>
                                    <small class="text-muted">Nomor Meja</small>
                                    <h5 class="fw-bold mb-0">{{ $table->table_number }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="bg-light rounded-3 p-3 mb-3">
                                {!! QrCode::size(180)->generate(url('/menu?table='.$table->table_number)) !!}
                            </div>
                            <div class="alert alert-info py-2 mb-3 small">
                                <i class="bi bi-phone me-1"></i>Scan untuk melakukan pemesanan
                            </div>
                            <form action="{{ route('admin.qr.destroy', $table->id) }}" method="POST" class="no-print" onsubmit="return confirm('Yakin ingin menghapus QR meja ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i>Hapus QR</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3"><i class="bi bi-qr-code fs-1 text-secondary"></i></div>
                <h5 class="fw-bold">Belum ada QR meja</h5>
                <p class="text-muted mb-0">Masukkan nomor meja di atas untuk membuat QR Code.</p>
            </div>
        </div>
    @endif
</div>
@endsection
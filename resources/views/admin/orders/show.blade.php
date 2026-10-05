@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.receipt',$order->id) }}" class="btn btn-success rounded-3">
                <i class="bi bi-printer me-1"></i> Struk Pelanggan
            </a>
            <a href="{{ route('admin.orders.kitchen-ticket',$order->id) }}" class="btn btn-warning rounded-3">
                <i class="bi bi-cup-hot me-1"></i> Tiket Dapur
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="bi bi-person fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Informasi Pelanggan</h5>
                            <small class="text-muted">Data pemesan</small>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="border-bottom py-3">
                        <small class="text-muted">No Order</small>
                        <div class="fw-bold text-primary">{{ $order->order_number }}</div>
                    </div>
                    <div class="border-bottom py-3">
                        <small class="text-muted">Nama Pelanggan</small>
                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                    </div>
                    <div class="border-bottom py-3">
                        <small class="text-muted">No HP</small>
                        <div class="fw-semibold">
                            <i class="bi bi-telephone text-success me-1"></i>{{ $order->phone }}
                        </div>
                    </div>
                    <div class="border-bottom py-3">
                        <small class="text-muted">Nomor Meja</small>
                        <div class="fw-semibold">
                            <i class="bi bi-table text-warning me-1"></i>{{ $order->table_number ?? '-' }}
                        </div>
                    </div>
                    <div class="border-bottom py-3">
                        <small class="text-muted">Tipe Pesanan</small>
                        <div class="fw-semibold">
                            <span class="badge bg-info-subtle text-info">{{ $order->visit_type }}</span>
                        </div>
                    </div>
                    <div class="border-bottom py-3">
                        <small class="text-muted">Pembayaran</small>
                        <div class="fw-semibold">
                            <i class="bi bi-credit-card text-success me-1"></i>{{ $order->payment }}
                        </div>
                    </div>
                    <div class="py-3">
                        <small class="text-muted">Catatan</small>
                        <div class="fw-semibold">{{ $order->note ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="bi bi-cart-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Detail Menu</h5>
                            <small class="text-muted">Menu yang dipesan pelanggan</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Menu</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order->details as $detail)
                                <tr>
                                    <td class="px-4">
                                        <strong>{{ $detail->menu->name }}</strong>
                                        @if($detail->options)
                                            @foreach($detail->options as $option)
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-dot"></i>{{ $option }}
                                                </small>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $detail->qty }}x</span>
                                    </td>
                                    <td>Rp {{ number_format($detail->price,0,',','.') }}</td>
                                    <td>
                                        <strong class="text-success">
                                            Rp {{ number_format($detail->total,0,',','.') }}
                                        </strong>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Total Pesanan</span>
                                <h4 class="fw-bold text-success mb-0">
                                    Rp {{ number_format($order->total,0,',','.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                            <i class="bi bi-arrow-repeat fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Perbarui Status</h5>
                            <small class="text-muted">Ubah status proses pesanan</small>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <form action="{{ route('admin.orders.status',$order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Status Pesanan</label>
                                <select name="status" class="form-select">
                                    @foreach(['Pending','Accepted','Processing','Ready','Completed','Cancelled'] as $status)
                                        <option value="{{ $status }}" {{ $order->status==$status?'selected':'' }}>
                                            {{ statusIndonesia($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-success w-100" type="submit">
                                    <i class="bi bi-check-circle me-1"></i> Perbarui Status
                                </button>
                            </div>
                            @if($order->status == 'Void')
                                <div class="col-12">
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Status Tindakan : <strong>Void</strong>
                                    </div>
                                </div>
                            @endif
                            @if($order->status == 'Refund')
                                <div class="col-12">
                                    <div class="alert alert-danger mb-0">
                                        <i class="bi bi-cash-stack me-1"></i>
                                        Status Tindakan : <strong>Pengembalian Dana</strong>
                                    </div>
                                </div>
                            @endif
                            @if($order->status == 'Cancelled')
                                <div class="col-12">
                                    <div class="alert alert-secondary mb-0">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Status Tindakan : <strong>Pembatalan</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                            <i class="bi bi-exclamation-octagon fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Pengembalian Dana / Void / Pembatalan</h5>
                            <small class="text-muted">Tindakan khusus untuk pesanan</small>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('admin.orders.refund',$order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Tindakan</label>
                            <select name="refund_type" class="form-select" required>
                                <option value="">Pilih tindakan</option>
                                <option value="Refund" {{ $order->action_status == 'Refund' ? 'selected' : '' }}>Pengembalian Dana</option>
                                <option value="Void" {{ $order->action_status == 'Void' ? 'selected' : '' }}>Void</option>
                                <option value="Cancel" {{ $order->action_status == 'Cancel' ? 'selected' : '' }}>Pembatalan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alasan</label>
                            <textarea name="refund_reason" rows="4" class="form-control" placeholder="Masukkan alasan tindakan..." required></textarea>
                        </div>
                        <button class="btn btn-danger" type="submit">
                            <i class="bi bi-exclamation-triangle me-1"></i> Proses Tindakan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('title', 'Manajemen Pesanan')
@section('content')

<div class="container-fluid">
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="order-summary-card order-card-blue h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="order-card-label">Total Pesanan</div>
                        <div class="order-card-value text-primary">{{ $orders->count() }}</div>
                        <small class="text-muted">Semua pesanan</small>
                    </div>
                    <div class="order-summary-icon"><i class="bi bi-receipt"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="order-summary-card order-card-yellow h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="order-card-label">Menunggu</div>
                        <div class="order-card-value text-warning">{{ $orders->where('status', 'Pending')->count() }}</div>
                        <small class="text-muted">Menunggu diproses</small>
                    </div>
                    <div class="order-summary-icon"><i class="bi bi-hourglass-split"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="order-summary-card order-card-red h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="order-card-label">Diproses</div>
                        <div class="order-card-value text-danger">{{ $orders->where('status', 'Processing')->count() }}</div>
                        <small class="text-muted">Sedang dikerjakan</small>
                    </div>
                    <div class="order-summary-icon"><i class="bi bi-fire"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="order-summary-card order-card-green h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="order-card-label">Selesai</div>
                        <div class="order-card-value text-success">{{ $orders->where('status', 'Completed')->count() }}</div>
                        <small class="text-muted">Pesanan selesai</small>
                    </div>
                    <div class="order-summary-icon"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-2"><i class="bi bi-list-check fs-5"></i></div>
                <div>
                    <h5 class="fw-bold mb-0">Daftar Pesanan</h5>
                    <small class="text-muted">Informasi pesanan pelanggan</small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">No Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-2"><i class="bi bi-receipt"></i></div>
                                    <div>
                                        <strong>{{ $order->order_number }}</strong>
                                        <small class="text-muted d-block">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                                <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i>{{ $order->phone }}</small>
                            </td>
                            <td>
                                @foreach($order->details as $detail)
                                    <div class="mb-1">
                                        <span class="badge bg-light text-dark border">{{ $detail->qty }}x</span>
                                        {{ $detail->menu->name }}
                                    </div>
                                @endforeach
                            </td>
                            <td><strong class="text-success">Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-credit-card me-1"></i>{{ $order->payment }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $color = match($order->status){
                                        'Pending' => 'warning',
                                        'Accepted' => 'info',
                                        'Processing' => 'primary',
                                        'Ready' => 'success',
                                        'Completed' => 'dark',
                                        'Cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge bg-{{ $color }} rounded-pill px-3 py-2">
                                    @switch($order->status)
                                        @case('Pending')
                                            <i class="bi bi-clock me-1"></i>Menunggu
                                            @break
                                        @case('Accepted')
                                            <i class="bi bi-check me-1"></i>Diterima
                                            @break
                                        @case('Processing')
                                            <i class="bi bi-fire me-1"></i>Diproses
                                            @break
                                        @case('Ready')
                                            <i class="bi bi-check-circle me-1"></i>Siap Disajikan
                                            @break
                                        @case('Completed')
                                            <i class="bi bi-check2-all me-1"></i>Selesai
                                            @break
                                        @case('Cancelled')
                                            <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                            @break
                                        @default
                                            {{ statusIndonesia($order->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm rounded-3 px-3">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-5">
                                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3"><i class="bi bi-receipt fs-1 text-secondary"></i></div>
                                    <h5 class="fw-bold">Belum ada pesanan</h5>
                                    <p class="text-muted mb-0">Pesanan pelanggan akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
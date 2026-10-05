@extends('layouts.admin')
@section('title', 'Riwayat Pesanan')
@section('content')

<div class="container-fluid">
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="history-summary-card history-blue">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="history-card-label">Total Riwayat</div>
                        <div class="history-card-value">{{ $orders->count() }}</div>
                        <small class="text-muted">Semua riwayat pesanan</small>
                    </div>
                    <div class="history-card-icon"><i class="bi bi-receipt"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="history-summary-card history-green">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="history-card-label">Pesanan Selesai</div>
                        <div class="history-card-value">{{ $orders->where('status','Completed')->count() }}</div>
                        <small class="text-muted">Pesanan berhasil</small>
                    </div>
                    <div class="history-card-icon"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="history-summary-card history-red">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="history-card-label">Dibatalkan</div>
                        <div class="history-card-value">{{ $orders->where('status','Cancelled')->count() }}</div>
                        <small class="text-muted">Pesanan dibatalkan</small>
                    </div>
                    <div class="history-card-icon"><i class="bi bi-x-circle"></i></div>
                </div>
            </div>
        </div>

        {{-- VOID / REFUND --}}
        <div class="col-xl-3 col-md-6">
            <div class="history-summary-card history-orange">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="history-card-label">Void / Refund</div>
                        <div class="history-card-value">{{ $orders->whereIn('status',['Void','Refund','Refunded'])->count() }}</div>
                        <small class="text-muted">Tindakan khusus</small>
                    </div>
                    <div class="history-card-icon"><i class="bi bi-arrow-counterclockwise"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-3"><i class="bi bi-clock-history fs-4"></i></div>
                <div>
                    <h5 class="fw-bold mb-0">Daftar Riwayat Pesanan</h5>
                    <small class="text-muted">Semua pesanan yang sudah masuk riwayat</small>
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
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-4"><div class="fw-bold text-primary">{{ $order->order_number }}</div></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2"><i class="bi bi-person"></i></div>
                                        <div>
                                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                                            <small class="text-muted">{{ $order->phone ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><strong class="text-success">Rp {{ number_format($order->total,0,',','.') }}</strong></td>
                                <td>
                                    @php
                                        $color = match($order->status){
                                            'Completed' => 'success',
                                            'Cancelled' => 'danger',
                                            'Refunded', 'Refund' => 'warning',
                                            'Void' => 'secondary',
                                            default => 'secondary'
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $color }} rounded-pill px-3 py-2">
                                        <i class="@if($order->status == 'Completed') bi bi-check-circle @elseif($order->status == 'Cancelled') bi bi-x-circle @elseif(in_array($order->status,['Refund','Refunded'])) bi bi-arrow-counterclockwise @elseif($order->status == 'Void') bi bi-slash-circle @else bi bi-clock @endif me-1"></i>
                                        @switch($order->status)
                                            @case('Completed') Selesai @break
                                            @case('Cancelled') Dibatalkan @break
                                            @case('Refund')
                                            @case('Refunded') Pengembalian Dana @break
                                            @case('Void') Void @break
                                            @default {{ statusIndonesia($order->status) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $order->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.orders.show',$order->id) }}" class="btn btn-primary btn-sm rounded-3 px-3">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <div class="bg-light rounded-circle d-inline-flex p-3 mb-3"><i class="bi bi-clock-history fs-3"></i></div>
                                        <h6 class="fw-bold">Belum Ada Riwayat</h6>
                                        <p class="mb-0">Belum ada pesanan yang masuk ke riwayat.</p>
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
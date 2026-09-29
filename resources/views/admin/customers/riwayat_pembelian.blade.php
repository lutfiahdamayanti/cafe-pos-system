@extends('layouts.admin')
@section('title', 'Riwayat Pembelian Pelanggan')
@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3 fs-5">
                <i class="bi bi-clock-history"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Riwayat Pembelian Pelanggan</h4>
                <small class="text-muted">Pantau seluruh riwayat transaksi pesanan yang dilakukan oleh pelanggan/member kafe.</small>
            </div>
        </div>

        <a href="{{ route('admin.customers.export.csv') }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Ekspor Laporan
        </a>
    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Transaksi</span>
                    <i class="bi bi-receipt fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold">{{ number_format($totalTransactions) }}</h3>
                <small class="text-muted">Total order tercatat</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Belanja Member</span>
                    <i class="bi bi-cash-stack fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <small class="text-muted">Akumulasi omzet pelanggan</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Order (AOV)</span>
                    <i class="bi bi-graph-up-arrow fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">Rp {{ number_format($avgSpending, 0, ',', '.') }}</h3>
                <small class="text-muted">Rata-rata belanja per transaksi</small>
            </div>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.customers.riwayat-pembelian') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari No Order / Nama / HP..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="customer_phone" class="form-select">
                        <option value="">Semua Pelanggan</option>
                        @foreach($customersList as $cust)
                            <option value="{{ $cust->phone }}" {{ request('customer_phone') == $cust->phone ? 'selected' : '' }}>
                                {{ $cust->name }} ({{ $cust->phone }}) [{{ $cust->tier }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Ready" {{ request('status') == 'Ready' ? 'selected' : '' }}>Ready</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-1">
                    <input type="date" name="date_from" class="form-control" title="Dari Tanggal" value="{{ request('date_from') }}">
                    <span class="align-self-center">-</span>
                    <input type="date" name="date_to" class="form-control" title="Sampai Tanggal" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100" title="Filter Riwayat">
                        <i class="bi bi-funnel"></i>
                    </button>
                    @if(request()->anyFilled(['search', 'customer_phone', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('admin.customers.riwayat-pembelian') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL RIWAYAT TRANSAKSI --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No Order</th>
                            <th>Tanggal & Waktu</th>
                            <th>Pelanggan</th>
                            <th>Tipe & Meja</th>
                            <th>Rincian Menu</th>
                            <th>Total Belanja</th>
                            <th>Poin Diperoleh</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold font-monospace text-dark">{{ $order->order_number }}</span>
                                </td>
                                <td>
                                    <small class="text-muted d-block">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                    <small class="text-secondary opacity-75">{{ $order->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $order->customer_name }}</div>
                                    <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $order->phone }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $order->visit_type }}
                                        @if($order->table_number) (Meja {{ $order->table_number }}) @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="small" style="max-width: 250px;">
                                        @foreach($order->details as $d)
                                            <div class="text-truncate">• {{ $d->menu->name ?? 'Menu' }} <span class="text-muted">({{ $d->qty }}x)</span></div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-25 text-dark fw-bold">
                                        +{{ (int) floor($order->total / 10000) }} Pts
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $order->payment }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($order->status) {
                                            'Completed' => 'bg-success',
                                            'Cancelled' => 'bg-danger',
                                            'Processing', 'Ready' => 'bg-info text-dark',
                                            default => 'bg-warning text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-receipt display-4 d-block mb-3 opacity-25"></i>
                                    Tidak ada data transaksi yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} transaksi
                    </span>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

@extends('layouts.admin')
@section('title','Dashboard')
@section('content')

<div class="container-fluid">
    <div class="mb-4">
        <h5 class="fw-bold">
            Selamat Datang, {{ Auth::user()->name }} 👋
        </h5>

        <p class="text-muted mb-0">
            Berikut ringkasan aktivitas Cafe POS hari ini.
        </p>
    </div>

    {{-- ================= METRIC ================= --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Total Pesanan</h6>
                <h3>{{ $totalOrders }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Pendapatan</h6>
                <h3>Rp {{ number_format($revenue,0,',','.') }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Menunggu</h6>
                <h3>{{ $pending }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Diproses</h6>
                <h3>{{ $processing }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Siap Disajikan</h6>
                <h3>{{ $ready }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Batal</h6>
                <h3>{{ $cancel }}</h3>
            </div>
        </div>
    </div>

    {{-- ================= LIVE ORDER ================= --}}
    <div class="card shadow border-0 mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    Pesanan Aktif
                </h4>

                <span class="badge bg-success">
                    {{ $orders->count() }} Pesanan Aktif
                </span>
            </div>

            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari Nomor Order / Nama Pelanggan"
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>

                            <option value="Pending"
                                {{ request('status')=='Pending' ? 'selected' : '' }}>
                                Menunggu
                            </option>

                            <option value="Accepted"
                                {{ request('status')=='Accepted' ? 'selected' : '' }}>
                                Diterima
                            </option>

                            <option value="Processing"
                                {{ request('status')=='Processing' ? 'selected' : '' }}>
                                Diproses
                            </option>

                            <option value="Ready"
                                {{ request('status')=='Ready' ? 'selected' : '' }}>
                                Siap Disajikan
                            </option>

                            <option value="Completed"
                                {{ request('status')=='Completed' ? 'selected' : '' }}>
                                Selesai
                            </option>

                            <option value="Cancelled"
                                {{ request('status')=='Cancelled' ? 'selected' : '' }}>
                                Dibatalkan
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-success w-100">
                            Cari
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Catatan</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($orders as $order)

                        <tr>
                            <td>
                                {{ $order->order_number }}
                            </td>

                            <td>
                                <strong>{{ $order->customer_name }}</strong>

                                <br>

                                <small class="text-muted">
                                    {{ $order->phone }}
                                </small>
                            </td>

                            <td>
                                @foreach($order->details as $detail)
                                    {{ $detail->menu->name }}
                                    x{{ $detail->qty }}
                                    <br>
                                @endforeach
                            </td>

                            <td>
                                {{ $order->note ?? '-' }}
                            </td>

                            <td>
                                {{ $order->payment }}
                            </td>

                            <td>
                                Rp {{ number_format($order->total,0,',','.') }}
                            </td>

                            <td>
                                <form
                                    action="{{ route('admin.orders.status',$order->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <div class="d-flex gap-2">
                                        <select
                                            name="status"
                                            class="form-select form-select-sm">

                                            <option value="Pending"
                                                {{ $order->status=='Pending' ? 'selected' : '' }}>
                                                Menunggu
                                            </option>

                                            <option value="Accepted"
                                                {{ $order->status=='Accepted' ? 'selected' : '' }}>
                                                Diterima
                                            </option>

                                            <option value="Processing"
                                                {{ $order->status=='Processing' ? 'selected' : '' }}>
                                                Diproses
                                            </option>

                                            <option value="Ready"
                                                {{ $order->status=='Ready' ? 'selected' : '' }}>
                                                Siap Disajikan
                                            </option>

                                            <option value="Completed"
                                                {{ $order->status=='Completed' ? 'selected' : '' }}>
                                                Selesai
                                            </option>

                                            <option value="Cancelled"
                                                {{ $order->status=='Cancelled' ? 'selected' : '' }}>
                                                Dibatalkan
                                            </option>
                                        </select>

                                        <button
                                            type="submit"
                                            class="btn btn-success btn-sm">
                                            Perbarui
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada pesanan.
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
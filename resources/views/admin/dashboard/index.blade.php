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
                <h6>Total Order</h6>
                <h3>{{ $totalOrders }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Revenue</h6>
                <h3>Rp {{ number_format($revenue,0,',','.') }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Pending</h6>
                <h3>{{ $pending }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Processing</h6>
                <h3>{{ $processing }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Ready</h6>
                <h3>{{ $ready }}</h3>
            </div>
        </div>

        <div class="col-lg-2 col-md-4">
            <div class="dashboard-card">
                <h6>Cancelled</h6>
                <h3>{{ $cancel }}</h3>
            </div>
        </div>
    </div>

    {{-- ================= LIVE ORDER ================= --}}
    <div class="card shadow border-0 mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    Live Orders
                </h4>

                <span class="badge bg-success">
                    {{ $orders->count() }} Active Orders
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari Nomor Order / Nama Pelanggan">
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option>Semua Status</option>
                        <option>Pending</option>
                        <option>Accepted</option>
                        <option>Processing</option>
                        <option>Ready</option>
                        <option>Completed</option>
                        <option>Cancelled</option>
                    </select>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No Order</th>
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
                                                Pending
                                            </option>

                                            <option value="Accepted"
                                                {{ $order->status=='Accepted' ? 'selected' : '' }}>
                                                Accepted
                                            </option>

                                            <option value="Processing"
                                                {{ $order->status=='Processing' ? 'selected' : '' }}>
                                                Processing
                                            </option>

                                            <option value="Ready"
                                                {{ $order->status=='Ready' ? 'selected' : '' }}>
                                                Ready
                                            </option>

                                            <option value="Completed"
                                                {{ $order->status=='Completed' ? 'selected' : '' }}>
                                                Completed
                                            </option>

                                            <option value="Cancelled"
                                                {{ $order->status=='Cancelled' ? 'selected' : '' }}>
                                                Cancelled
                                            </option>
                                        </select>

                                        <button
                                            type="submit"
                                            class="btn btn-success btn-sm">
                                            Update
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
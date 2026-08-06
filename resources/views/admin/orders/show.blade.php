@extends('layouts.admin')
@section('title','Detail Pesanan')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- <h2 class="fw-bold">
            Detail Pesanan
        </h2> -->

        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.receipt',$order->id) }}"
                class="btn btn-success">
                <i class="bi bi-printer"></i>
                Struk Pelanggan
            </a>

            <a href="{{ route('admin.orders.kitchen-ticket',$order->id) }}"
                class="btn btn-warning">
                <i class="bi bi-cup-hot"></i>
                Tiket Dapur
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="row">
        {{-- ================= CUSTOMER ================= --}}
        <div class="col-lg-4">
            <div class="card shadow border-0 rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Informasi Pelanggan
                    </h5>
                    <p>
                        <strong>No Order</strong><br>
                        {{ $order->order_number }}
                    </p>
                    <p>
                        <strong>Nama</strong><br>
                        {{ $order->customer_name }}
                    </p>
                    <p>
                        <strong>No HP</strong><br>
                        {{ $order->phone }}
                    </p>
                    <p>
                        <strong>Nomor Meja</strong><br>
                        {{ $order->table_number ?? '-' }}
                    </p>
                    <p>
                        <strong>Tipe</strong><br>
                        {{ $order->visit_type }}
                    </p>
                    <p>
                        <strong>Pembayaran</strong><br>
                        {{ $order->payment }}
                    </p>
                    <p>
                        <strong>Catatan</strong><br>
                        {{ $order->note ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ================= ORDER ================= --}}
        <div class="col-lg-8">
            <div class="card shadow border-0 rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Detail Menu
                    </h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($order->details as $detail)
                        <tr>
                            <td>
                                <strong>{{ $detail->menu->name }}</strong>
                                @if($detail->options)
                                    @foreach($detail->options as $option)
                                        <br>
                                        <small class="text-muted">
                                            • {{ $option }}
                                        </small>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                {{ $detail->qty }}
                            </td>
                            <td>
                                Rp {{ number_format($detail->price,0,',','.') }}
                            </td>
                            <td>
                                Rp {{ number_format($detail->total,0,',','.') }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <div class="text-end">
                        <h4 class="fw-bold text-success">
                            Total :
                            Rp {{ number_format($order->total,0,',','.') }}
                        </h4>
                    </div>
                </div>
            </div>

            {{-- ================= STATUS ================= --}}
            <div class="card shadow border-0 rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Perbarui Status
                    </h5>
                    <form
                        action="{{ route('admin.orders.status',$order->id) }}"
                        method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-8">
                                <select
                                    name="status"
                                    class="form-select">
                                    @foreach([
                                        'Pending',
                                        'Accepted',
                                        'Processing',
                                        'Ready',
                                        'Completed',
                                        'Cancelled'
                                    ] as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ $order->status==$status?'selected':'' }}>
                                        {{ statusIndonesia($status) }}
                                    </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-4">
                                <button
                                    class="btn btn-success w-100">
                                    Perbarui Status
                                </button>
                            </div>

                            @if($order->status == 'Void')
                            <div class="alert alert-warning mt-3">
                                Status Tindakan : <strong>Void</strong>
                            </div>
                            @endif

                            @if($order->status == 'Refund')
                            <div class="alert alert-danger mt-3">
                                Status Tindakan : <strong>Pengembalian Dana</strong>
                            </div>
                            @endif

                            @if($order->status == 'Cancelled')
                            <div class="alert alert-secondary mt-3">
                                Status Tindakan : <strong>Pembatalan</strong>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow border-0 rounded-4 mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Pengembalian Dana / Void / Pembatalan
                    </h5>
                    <form
                        action="{{ route('admin.orders.refund',$order->id) }}"
                        method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">
                                Jenis Tindakan
                            </label>
                            <select
                                name="refund_type"
                                class="form-select"
                                required>
                                <option value="">Pilih</option>
                                <option value="Refund"
                                    {{ $order->action_status == 'Refund' ? 'selected' : '' }}>
                                    Pengembalian Dana
                                </option>
                                <option value="Void"
                                    {{ $order->action_status == 'Void' ? 'selected' : '' }}>
                                    Void
                                </option>
                                <option value="Cancel"
                                    {{ $order->action_status == 'Cancel' ? 'selected' : '' }}>
                                    Pembatalan
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Alasan
                            </label>
                            <textarea
                                name="refund_reason"
                                rows="4"
                                class="form-control"
                                required></textarea>
                        </div>

                        <button
                            class="btn btn-danger">
                            Proses
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
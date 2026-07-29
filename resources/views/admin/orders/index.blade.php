@extends('layouts.admin')
@section('title','Manajemen Pesanan')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="badge bg-success fs-6">
            {{ $orders->count() }} Pesanan
        </span>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($orders as $order)

                        <tr>

                            <td>
                                <strong>{{ $order->order_number }}</strong>
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
                                    • {{ $detail->menu->name }}
                                    x{{ $detail->qty }}
                                    <br>
                                @endforeach
                            </td>

                            <td>
                                Rp {{ number_format($order->total,0,',','.') }}
                            </td>

                            <td>
                                {{ $order->payment }}
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

                                <span class="badge bg-{{ $color }}">
                                    @switch($order->status)

                                        @case('Pending')
                                            Menunggu
                                            @break

                                        @case('Accepted')
                                            Diterima
                                            @break

                                        @case('Processing')
                                            Diproses
                                            @break

                                        @case('Ready')
                                            Siap Disajikan
                                            @break

                                        @case('Completed')
                                            Selesai
                                            @break

                                        @case('Cancelled')
                                            Dibatalkan
                                            @break

                                        @default
                                            {{ statusIndonesia($order->status) }}

                                    @endswitch
                                </span>

                            </td>

                            <td>
                                <a href="{{ route('admin.orders.show',$order->id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i>
                                    Detail
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center text-muted">
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
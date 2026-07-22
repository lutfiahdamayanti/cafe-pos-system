@extends('layouts.admin')

@section('title','Riwayat Pesanan')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <span class="badge bg-success">

            {{ $orders->count() }} Data

        </span>

    </div>

    <div class="card shadow border-0 rounded-4">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>No Order</th>

                        <th>Pelanggan</th>

                        <th>Total</th>

                        <th>Status</th>

                        <th>Tanggal</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($orders as $order)

                <tr>

                    <td>

                        {{ $order->order_number }}

                    </td>

                    <td>

                        {{ $order->customer_name }}

                    </td>

                    <td>

                        Rp {{ number_format($order->total,0,',','.') }}

                    </td>

                    <td>

                        @php

                            $color = match($order->status){

                                'Completed' => 'success',

                                'Cancelled' => 'danger',

                                'Refunded' => 'warning',

                                default => 'secondary'

                            };

                        @endphp

                        <span class="badge bg-{{ $color }}">

                            {{ $order->status }}

                        </span>

                    </td>

                    <td>

                        {{ $order->created_at->format('d M Y H:i') }}

                    </td>

                    <td>

                        <a href="{{ route('admin.orders.show',$order->id) }}"
                           class="btn btn-primary btn-sm">

                            Detail

                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center">

                        Belum ada riwayat.

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
@extends('layouts.admin')

@section('title', 'Akses Ditolak')

@section('content')
<div class="container py-5 text-center">

    <i class="bi bi-shield-lock-fill text-danger" style="font-size:80px;"></i>

    <h2 class="mt-3">Akses Ditolak</h2>

    <p class="text-muted mb-4">
        Maaf, Anda tidak memiliki hak akses ke halaman ini.
    </p>

    @if(auth()->check())

        @if(auth()->user()->role == 'owner' || auth()->user()->role == 'manager')

            <a href="{{ route('admin.dashboard') }}"
                class="btn btn-success rounded-pill px-4">
                <i class="bi bi-house-door-fill"></i>
                Kembali ke Dashboard
            </a>

        @elseif(auth()->user()->role == 'cashier')

            <a href="{{ route('admin.orders.index') }}"
                class="btn btn-success rounded-pill px-4">
                <i class="bi bi-receipt"></i>
                Kembali ke Halaman Pesanan
            </a>

        @elseif(auth()->user()->role == 'kitchen')

            <a href="{{ route('admin.kitchen.index') }}"
                class="btn btn-success rounded-pill px-4">
                <i class="bi bi-cup-hot-fill"></i>
                Kembali ke Halaman Kitchen
            </a>

        @endif

    @endif

</div>
@endsection
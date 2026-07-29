@extends('layouts.app')
@section('title', 'Checkout')
@section('content')

<section class="py-5">
    <div class="container">
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row g-4">

                {{-- ================= LEFT ================= --}}
                <div class="col-lg-8">
                    {{-- Data Pelanggan --}}
                    <div class="card shadow border-0 rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                Data Pelanggan
                            </h4>

                            <div class="mb-3">
                                <label class="form-label">
                                    Nama Pelanggan
                                </label>

                                <input
                                    type="text"
                                    name="customer_name"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Nomor HP / WhatsApp
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Nomor Meja
                                </label>

                                @if(session('table_number'))

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ session('table_number') }}"
                                        readonly>

                                    <input
                                        type="hidden"
                                        name="table_number"
                                        value="{{ session('table_number') }}">

                                @else
                                    <input
                                        type="text"
                                        name="table_number"
                                        class="form-control"
                                        placeholder="Contoh : A01">
                                @endif

                            </div>

                            <div>
                                <label class="form-label">
                                    Catatan Pesanan
                                </label>

                                <textarea
                                    name="note"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Tambahkan catatan jika ada..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Tipe Kunjungan --}}
                    <div class="card shadow border-0 rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">
                                Tipe Kunjungan
                            </h4>

                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="visit_type"
                                    value="Dine In"
                                    id="dinein"
                                    {{ session('table_number') ? 'checked' : '' }}
                                    required>

                                <label class="form-check-label" for="dinein">
                                    Makan Di Tempat
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="visit_type"
                                    value="Take Away"
                                    id="takeaway"
                                    {{ session('table_number') ? 'disabled' : '' }}>

                                <label class="form-check-label" for="takeaway">
                                    Bawa Pulang
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    <div class="card shadow border-0 rounded-4">
                        <div class="card-body p-4">

                            <h4 class="fw-bold mb-3">
                                Metode Pembayaran
                            </h4>

                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment"
                                    value="QRIS"
                                    id="qris"
                                    required>

                                <label class="form-check-label" for="qris">
                                    QRIS
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment"
                                    value="Virtual Account"
                                    id="va">

                                <label class="form-check-label" for="va">
                                    Virtual Account
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment"
                                    value="E-Wallet"
                                    id="ewallet">

                                <label class="form-check-label" for="ewallet">
                                    E-Wallet
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment"
                                    value="Cash"
                                    id="cash">

                                <label class="form-check-label" for="cash">
                                    Cash ke Kasir
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= RIGHT ================= --}}
                <div class="col-lg-4">
                    <div class="card shadow border-0 rounded-4">
                        <div class="card-body p-4">

                            <h4 class="fw-bold mb-4">
                                Ringkasan Pesanan
                            </h4>

                            @foreach($carts as $cart)
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <strong>{{ $cart->menu->name }}</strong><br>
                                        <small class="text-muted">
                                            Qty : {{ $cart->qty }}
                                        </small>
                                    </div>

                                    <div class="fw-semibold">
                                        Rp {{ number_format($cart->total,0,',','.') }}
                                    </div>
                                </div>
                            @endforeach

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>
                                    Rp {{ number_format($subtotal,0,',','.') }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Pajak (11%)</span>
                                <span>
                                    Rp {{ number_format($tax,0,',','.') }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Service</span>
                                <span>
                                    Rp {{ number_format($service,0,',','.') }}
                                </span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                                <span>Total</span>
                                <span class="text-success">
                                    Rp {{ number_format($grandTotal,0,',','.') }}
                                </span>
                            </div>

                            <div class="d-grid">
                                <button
                                    type="submit"
                                    class="btn btn-success btn-lg rounded-pill">
                                    Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
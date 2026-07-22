@extends('layouts.app')
@section('content')
@php
    $subtotal = $carts->sum('total');
    $tax = $subtotal * 0.11;
    $service = 3000;
    $grandTotal = $subtotal + $tax + $service;
@endphp
<section class="cart-hero">
    <div class="container text-center">
        <h1>Keranjang Pesanan</h1>
        <p>Periksa kembali pesanan kamu sebelum checkout</p>
    </div>
</section>

<section class="cart-section py-5">
    <div class="container">
        <div class="row g-4">

            {{-- CART ITEM --}}
            <div class="col-lg-8">
                @forelse($carts as $cart)
                    <div class="cart-item">
                        <img src="{{ asset('images/'.$cart->menu->image) }}"
                            alt="{{ $cart->menu->name }}">

                        <div class="cart-info">
                            <h5>{{ $cart->menu->name }}</h5>
                            <small>
                                {{ $cart->menu->category?->name }}
                            </small>

                            @if($cart->options)
                            <div class="mt-2">
                                @foreach($cart->options as $optionName => $value)
                                    <div class="text-muted small">
                                        <strong>{{ $optionName }}</strong> :
                                        {{ $value }}

                                    </div>
                                @endforeach
                            </div>
                            @endif

                            @if($cart->note)
                                <p class="text-muted mb-1">
                                    Catatan : {{ $cart->note }}
                                </p>
                            @endif
                            <div class="price">
                                Rp {{ number_format($cart->price,0,',','.') }}
                            </div>
                        </div>

                        <div class="cart-action">
                            <form action="{{ route('cart.qty', $cart->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="qty" value="{{ max(1,$cart->qty-1) }}">
                                <button type="submit">−</button>
                            </form>

                            <span>{{ $cart->qty }}</span>

                            <form action="{{ route('cart.qty', $cart->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="qty" value="{{ $cart->qty+1 }}">
                                <button type="submit">+</button>
                            </form>
                        </div>

                        <div class="cart-total">
                            Rp {{ number_format($cart->total,0,',','.') }}
                        </div>
                    </div>
                    @empty

                <div class="alert alert-warning text-center">
                    Keranjang masih kosong.
                </div>
                @endforelse
            </div>

            {{-- SUMMARY --}}
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4>Ringkasan Pesanan</h4>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>
                            Rp {{ number_format($subtotal,0,',','.') }}
                        </span>
                    </div>

                    <div class="summary-row">
                        <span>Pajak (11%)</span>
                        <span>
                            Rp {{ number_format($tax,0,',','.') }}
                        </span>
                    </div>

                    <div class="summary-row">
                        <span>Service</span>
                        <span>
                            Rp {{ number_format($service,0,',','.') }}
                        </span>
                    </div>
                    <hr>

                    <div class="summary-total">
                        <span>Total</span>
                        <span class="fw-bold text-success">
                            Rp {{ number_format($grandTotal,0,',','.') }}
                        </span>
                    </div>

                    <a href="{{ route('checkout') }}" class="btn btn-success w-100 mt-5">
                        Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@extends('layouts.app')
@section('content')

@php
    $subtotal=$carts->sum('total');
    $tax=$subtotal*0.11;
    $service=3000;
    $grandTotal=$subtotal+$tax+$service;
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
                    <div class="cart-item position-relative">

                        {{-- CHECKBOX PILIH --}}
                        <div class="form-check me-3">
                            <input class="form-check-input cart-checkbox" type="checkbox" name="selected_carts[]" value="{{ $cart->id }}" id="cart{{ $cart->id }}" checked>
                        </div>

                        {{-- GAMBAR --}}
                        <img src="{{ asset('images/'.$cart->menu->image) }}" alt="{{ $cart->menu->name }}">

                        {{-- INFO --}}
                        <div class="cart-info">
                            <h5>{{ $cart->menu->name }}</h5>
                            <small>{{ $cart->menu->category?->name }}</small>

                            @if($cart->options)
                                <div class="mt-2">
                                    @foreach($cart->options as $optionName => $value)
                                        <div class="text-muted small">
                                            <strong>{{ $optionName }}</strong> : {{ $value }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if($cart->note)
                                <p class="text-muted mb-1">Catatan : {{ $cart->note }}</p>
                            @endif

                            <div class="price">Rp {{ number_format($cart->price,0,',','.') }}</div>
                        </div>

                        {{-- QTY --}}
                        <div class="cart-action">
                            <button type="button" class="qty-btn minus-btn" data-id="{{ $cart->id }}">−</button>
                            <span class="qty-value" id="qty-{{ $cart->id }}">{{ $cart->qty }}</span>
                            <button type="button" class="qty-btn plus-btn" data-id="{{ $cart->id }}">+</button>
                        </div>

                        {{-- TOTAL --}}
                        <div class="cart-total">Rp {{ number_format($cart->total,0,',','.') }}</div>

                        {{-- HAPUS --}}
                        <div class="ms-3">
                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" onsubmit="return confirm('Hapus {{ $cart->menu->name }} dari keranjang?')">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-cart-btn" data-id="{{ $cart->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning text-center">Keranjang masih kosong.</div>
                @endforelse
            </div>

            {{-- SUMMARY --}}
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4>Ringkasan Pesanan</h4>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal,0,',','.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Pajak (11%)</span>
                        <span>Rp {{ number_format($tax,0,',','.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Service</span>
                        <span>Rp {{ number_format($service,0,',','.') }}</span>
                    </div>

                    <hr>

                    <div class="summary-total">
                        <span>Total</span>
                        <span class="fw-bold text-success">Rp {{ number_format($grandTotal,0,',','.') }}</span>
                    </div>

                    <a href="{{ route('checkout') }}" class="btn btn-success w-100 mt-5">Checkout</a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('.delete-cart-btn').forEach(button=>{
        button.addEventListener('click',function(){
            const cartId=this.dataset.id;
            const cartItem=this.closest('.cart-item');

            fetch(`/cart/${cartId}`,{
                method:'DELETE',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Accept':'application/json'
                }
            })
            .then(response=>{
                if(!response.ok) throw new Error('Gagal menghapus item');
                return response.json();
            })
            .then(data=>{
                if(data.success) cartItem.remove();
            })
            .catch(error=>{
                console.error(error);
                alert('Gagal menghapus pesanan.');
            });
        });
    });
});
</script>
@endpush
@endsection
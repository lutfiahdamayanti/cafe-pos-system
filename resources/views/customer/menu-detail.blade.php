@extends('layouts.app')
@section('title','Detail Menu')
@section('content')

<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-start">
            {{-- IMAGE --}}
            <div class="col-lg-5">
                <div class="detail-image shadow rounded-4 overflow-hidden">
                    <img src="{{ asset('images/'.$menu->image) }}"
                         class="img-fluid w-100"
                         alt="{{ $menu->name }}">
                </div>
            </div>

            {{-- DETAIL --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow rounded-4">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <input type="hidden" name="qty" id="qty-input" value="1">

                        <div class="card-body p-4">
                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                {{ $menu->category?->name }}
                            </span>

                            <h2 class="fw-bold mt-3">
                                {{ $menu->name }}
                            </h2>

                            <div class="d-flex flex-wrap gap-4 text-secondary mb-3">

                                <span>
                                    ⭐ {{ number_format($menu->rating,1) }}
                                </span>

                                <span>
                                    ⏱ {{ $menu->preparation_time }} Menit
                                </span>

                                @if($menu->calories)
                                <span>
                                    🔥 {{ $menu->calories }} kcal
                                </span>
                                @endif

                                @if($menu->allergen)
                                <span>
                                    ⚠ {{ $menu->allergen }}
                                </span>
                                @endif

                                <span>
                                    @if($menu->stock > 0)
                                        📦 Stok {{ $menu->stock }}
                                    @else
                                        <span class="text-danger fw-semibold">
                                            Sold Out
                                        </span>
                                    @endif
                                </span>
                            </div>

                            <p class="text-muted mb-1">
                                Harga
                            </p>

                            <h2
                                class="fw-bold text-success mb-4"
                                id="price-display">

                                Rp {{ number_format($menu->price,0,',','.') }}

                            </h2>

                            @if($menu->ingredients)
                                <div class="mt-4">
                                    <h5 class="fw-bold">
                                        🥬 Komposisi
                                    </h5>

                                    <div class="card border-0 bg-light rounded-4">
                                        <ul class="list-group list-group-flush">

                                            @foreach(explode("\n",$menu->ingredients) as $item)

                                            <li class="list-group-item">

                                                🟢 {{ $item }}

                                            </li>

                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            @endif
                            <hr>

                            @foreach($menu->options as $option)

                            <h5 class="fw-bold mt-4">
                                {{ $option->name }}
                            </h5>

                            <div class="option-group">
                                @foreach($option->values as $value)
                                <label class="option-card">
                                    <input
                                        type="radio"
                                        name="options[{{ $option->id }}]"
                                        value="{{ $value->id }}"
                                        data-price="{{ $value->extra_price }}">
                                    <span>
                                        {{ $value->value }}
                                        @if($value->extra_price>0)
                                            (+Rp{{ number_format($value->extra_price,0,',','.') }})
                                        @endif
                                    </span>
                                </label>
                                @endforeach
                            </div>

                            @endforeach

                            {{-- CATATAN --}}
                            <h5 class="fw-bold mt-4">
                                Catatan
                            </h5>
                            <textarea
                                name="note"
                                class="form-control rounded-4"
                                rows="3"
                                placeholder="Contoh : Jangan terlalu pedas...">
                            </textarea>

                            {{-- QTY --}}
                            <div class="d-flex justify-content-between align-items-center mt-4">

                                <div class="qty-box">

                                    <button type="button" id="minus">−</button>

                                    <span id="qty">1</span>

                                    <button type="button" id="plus">+</button>

                                </div>

                                <div class="text-end">

                                    <small class="text-muted">
                                        Total
                                    </small>

                                    <h3
                                        id="total-price"
                                        class="text-success fw-bold mb-0">

                                        Rp {{ number_format($menu->price,0,',','.') }}

                                    </h3>

                                </div>

                            </div>
                        </div>
                        
                        @if($menu->stock > 0)
                        <button
                            class="btn btn-success w-100 py-3 rounded-4 mt-4">

                            <i class="bi bi-cart-plus"></i>

                            Tambah ke Keranjang

                        </button>
                        @else
                        <button
                            class="btn btn-secondary w-100 py-3 rounded-4 mt-4"
                            disabled>

                            Stok Habis

                        </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MENU LAINNYA --}}
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold mb-4">
            Menu Lainnya
        </h3>

        <div class="row g-4">
            @foreach($recommended as $item)
            <div class="col-lg-3 col-md-6">
                <div class="menu-card h-100">
                    <div class="menu-image">
                        <img src="{{ asset('images/'.$item->image) }}"
                             class="img-fluid"
                             alt="{{ $item->name }}">
                    </div>

                    <div class="menu-body">
                        <span class="category">
                            {{ $item->category?->name }}
                        </span>
                        <h5>
                            {{ $item->name }}
                        </h5>
                        <p>
                            {{ Str::limit($item->description,55) }}
                        </p>

                        <div class="menu-bottom">
                            <h5>
                                Rp {{ number_format($item->price,0,',','.') }}
                            </h5>
                            <a href="{{ route('menu.detail',$item->id) }}"
                               class="btn btn-order">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
const basePrice = {{ $menu->price }};
let qty = 1;
const qtyText = document.getElementById("qty");
const totalText = document.getElementById("total-price");
function formatRupiah(number){
    return "Rp " + number.toLocaleString("id-ID");
}

function calculateExtra(){
    let extra = 0;
    document.querySelectorAll('input[type="radio"]:checked').forEach(function(item){
        extra += Number(item.dataset.price);
    });
    return extra;
}

function updateTotal(){
    document.getElementById("qty-input").value = qty;
    qtyText.innerHTML = qty;
    let total = (basePrice + calculateExtra()) * qty;
    totalText.innerHTML = formatRupiah(total);
}

document.getElementById("plus").onclick = function(){
    qty++;
    updateTotal();
}

document.getElementById("minus").onclick = function(){
    if(qty>1){
        qty--;
        updateTotal();
    }
}

document.querySelectorAll('input[type="radio"]').forEach(function(item){
    item.addEventListener("change", updateTotal);
});
updateTotal();
</script>
@endsection

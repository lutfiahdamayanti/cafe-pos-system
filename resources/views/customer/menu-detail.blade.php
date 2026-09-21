@extends('layouts.app')
@section('title','Detail Menu')
@section('content')

<section class="py-5">
    <div class="container">
        {{-- ================= DETAIL MENU ================= --}}
        <div class="row gx-4 gy-5 align-items-start">

            {{-- ================= KOLOM KIRI ================= --}}
            <div class="col-lg-5">
                {{-- GAMBAR --}}
                <div class="detail-image shadow rounded-4 overflow-hidden">
                    <img src="{{ asset('images/'.$menu->image) }}" alt="{{ $menu->name }}">
                </div>

                {{-- DESKRIPSI --}}
                <div class="mt-4">
                    <h5 class="fw-bold"><i class="bi bi-info-circle-fill text-success me-2"></i>Deskripsi</h5>
                    <p class="text-muted lh-lg mb-0">{{ $menu->description }}</p>
                </div>

                {{-- KOMPOSISI --}}
                @if($menu->ingredients)
                    <div class="mt-4">
                        <h5 class="fw-bold"><i class="bi bi-basket-fill text-success me-2"></i>Komposisi</h5>
                        <div class="composition-box mt-3">
                            @foreach(explode("\n", $menu->ingredients) as $item)
                                @if(trim($item))
                                    <div class="composition-item">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span>{{ trim($item) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ================= KOLOM KANAN ================= --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <input type="hidden" name="qty" id="qty-input" value="1">

                        <div class="card-body p-4 p-lg-5">
                            {{-- CATEGORY --}}
                            <span class="badge bg-success px-3 py-2 rounded-pill">{{ $menu->category?->name }}</span>

                            {{-- NAMA --}}
                            <h2 class="fw-bold mt-3 mb-3">{{ $menu->name }}</h2>

                            {{-- INFO MENU --}}
                            <div class="d-flex flex-wrap gap-3 text-secondary mb-4">
                                <span><i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($menu->rating,1) }}</span>
                                <span><i class="bi bi-clock-fill text-success me-1"></i>{{ $menu->preparation_time }} Menit</span>

                                @if($menu->calories)
                                    <span><i class="bi bi-fire text-danger me-1"></i>{{ $menu->calories }} kcal</span>
                                @endif

                                @if($menu->allergen)
                                    <span><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>{{ $menu->allergen }}</span>
                                @endif

                                <span>
                                    @if($menu->stock > 0)
                                        <i class="bi bi-box-seam-fill text-success me-1"></i>Stok {{ $menu->stock }}
                                    @else
                                        <span class="text-danger fw-semibold"><i class="bi bi-x-circle-fill me-1"></i>Sold Out</span>
                                    @endif
                                </span>
                            </div>

                            {{-- HARGA --}}
                            <p class="text-muted mb-1">Harga</p>
                            <h2 class="fw-bold text-success mb-4" id="price-display">Rp {{ number_format($menu->price,0,',','.') }}</h2>

                            <hr>

                            {{-- ================= OPTIONS ================= --}}
                            @foreach($menu->options as $option)
                                <h5 class="fw-bold mt-4 mb-3">{{ $option->name }}</h5>
                                <div class="option-group">
                                    @foreach($option->values as $value)
                                        <label class="option-card">
                                            <input type="radio" name="options[{{ $option->id }}]" value="{{ $value->id }}" data-price="{{ $value->extra_price }}">
                                            <span>
                                                {{ $value->value }}
                                                @if($value->extra_price > 0)
                                                    <span class="text-success">+Rp{{ number_format($value->extra_price,0,',','.') }}</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach

                            {{-- ================= CATATAN ================= --}}
                            <h5 class="fw-bold mt-4 mb-3"><i class="bi bi-pencil-square text-success me-2"></i>Catatan</h5>
                            <textarea name="note" class="form-control rounded-4" rows="3" placeholder="Contoh : Jangan terlalu pedas..."></textarea>

                            {{-- ================= QTY + TOTAL ================= --}}
                            <div class="d-flex justify-content-between align-items-end mt-4">
                                <div>
                                    <small class="text-muted d-block mb-2">Jumlah</small>
                                    <div class="qty-box">
                                        <button type="button" id="minus">−</button>
                                        <span id="qty">1</span>
                                        <button type="button" id="plus">+</button>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <small class="text-muted">Total</small>
                                    <h3 id="total-price" class="text-success fw-bold mb-0">Rp {{ number_format($menu->price,0,',','.') }}</h3>
                                </div>
                            </div>
                        </div>

                        {{-- ================= BUTTON ================= --}}
                        @if($menu->stock > 0)
                            <div class="px-4 pb-4 px-lg-5 pb-lg-5">
                                <button type="submit" class="btn btn-success w-100 py-3 rounded-4">
                                    <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        @else
                            <div class="px-4 pb-4 px-lg-5 pb-lg-5">
                                <button type="button" class="btn btn-secondary w-100 py-3 rounded-4" disabled>
                                    <i class="bi bi-x-circle me-2"></i>Stok Habis
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= MENU LAINNYA ================= --}}
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold mb-4">Menu Lainnya</h3>
        <div class="row g-4">
            @foreach($recommended as $item)
                <div class="col-lg-3 col-md-6">
                    <div class="menu-card h-100">
                        <div class="menu-image">
                            <img src="{{ asset('images/'.$item->image) }}" class="img-fluid" alt="{{ $item->name }}">
                        </div>

                        <div class="menu-body">
                            <span class="category">{{ $item->category?->name }}</span>
                            <h5>{{ $item->name }}</h5>
                            <p>{{ Str::limit($item->description,55) }}</p>

                            <div class="menu-bottom">
                                <h5>Rp {{ number_format($item->price,0,',','.') }}</h5>
                                <a href="{{ route('menu.detail',$item->id) }}" class="btn btn-order">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= JAVASCRIPT ================= --}}
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
};

document.getElementById("minus").onclick = function(){
    if(qty > 1){
        qty--;
        updateTotal();
    }
};

document.querySelectorAll('input[type="radio"]').forEach(function(item){
    item.addEventListener("change", updateTotal);
});

updateTotal();
</script>
@endsection
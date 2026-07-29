@extends('layouts.app')
@section('title', 'Menu')
@section('content')

<!-- ================= HERO ================= -->
<section class="menu-hero">
    <div class="hero-content">
        <h1>Menu Kami</h1>
        <p>Nikmati berbagai pilihan menu favorit kami.</p>
    </div>
</section>

@if(session('table_number'))
    <div class="container mt-4">
        <div class="alert alert-success shadow-sm">
            <i class="bi bi-qr-code"></i>
            Anda sedang memesan untuk
            <strong>Meja {{ session('table_number') }}</strong>
        </div>
    </div>
@endif

<!-- ================= MENU ================= -->
<section class="menu-section">
    <div class="container">
        <!-- Search -->
        <form action="{{ route('menu') }}" method="GET">
            <div class="search-box mb-5">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari menu favorit..."
                    value="{{ request('search') }}">
            </div>
        </form>

        <!-- Category -->
        <div class="category-list mb-5">
            <a href="{{ route('menu') }}"
                class="{{ request('category') ? '' : 'active' }}">
                Semua
            </a>
            @foreach($categories as $category)
                <a
                    href="{{ route('menu',[
                        'category'=>$category->id,
                        'search'=>request('search')
                    ]) }}"
                    class="{{ request('category')==$category->id ? 'active' : '' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Menu -->
        <div class="row g-4">
        @forelse($menus as $menu)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="menu-card h-100">
            <!-- IMAGE -->
            <div class="menu-image">

                <img
                    src="{{ asset('images/'.$menu->image) }}"
                    alt="{{ $menu->name }}">

                @if($menu->best_seller)
                    <span class="badge-best">Best Seller</span>
                @endif

                @if($menu->promo)
                    <span class="badge-promo">Promo</span>
                @endif

                @if($menu->is_new)
                    <span class="badge-new">New</span>
                @endif

                @if($menu->stock == 0)
                    <span class="badge-sold">Sold Out</span>
                @endif

                    <button
                        type="button"
                        class="favorite-btn"
                        data-id="{{ $menu->id }}">

                        @if(in_array($menu->id, $favorites))
                            <i class="bi bi-heart-fill text-danger"></i>
                        @else
                            <i class="bi bi-heart"></i>
                        @endif
                    </button>
            </div>

            <!-- BODY -->
            <div class="menu-body d-flex flex-column">
                <span class="category">
                    {{ $menu->category->name }}
                </span>

                <h5>{{ $menu->name }}</h5>

                <p>
                    {{ Str::limit($menu->description,65) }}
                </p>

                <div class="menu-info">
                    <span>⭐ {{ number_format($menu->rating,1) }}</span>
                    <span>•</span>
                    <span>⏱ {{ $menu->preparation_time }} Menit</span>
                </div>

                @if($menu->calories)
                <div class="menu-info mt-2">
                    🔥 {{ $menu->calories }} kcal
                </div>
                @endif

                @if($menu->allergen)
                <div class="menu-info">
                    ⚠ {{ $menu->allergen }}
                </div>
                @endif

                <div class="menu-bottom mt-auto">
                    <h5>
                        Rp {{ number_format($menu->price,0,',','.') }}
                    </h5>

                    <a href="{{ route('menu.detail',$menu->id) }}"
                    class="btn btn-order">
                        Detail
                    </a>
                </div>
            </div>
        </div>
    </div>

    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-search display-3 text-secondary"></i>
            <h3 class="mt-3">
                Menu Tidak Ditemukan
            </h3>

            <p class="text-muted">
                Coba gunakan kata kunci lain.
            </p>
        </div>
    @endforelse
</section>
@endsection
@push('scripts')
<script>

document.addEventListener("DOMContentLoaded", function(){

    document.querySelectorAll(".favorite-btn").forEach(function(button){

        button.addEventListener("click", function(){

            let id = this.dataset.id;
            let icon = this.querySelector("i");

            fetch("{{ url('/favorite') }}/"+id,{
                method:"POST",
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token() }}",
                    "Accept":"application/json"
                }
            })
            .then(response=>response.json())
            .then(data=>{

                if(data.status=="added"){

                    icon.classList.remove("bi-heart");
                    icon.classList.add("bi-heart-fill");
                    icon.classList.add("text-danger");

                }else{

                    icon.classList.remove("bi-heart-fill");
                    icon.classList.remove("text-danger");
                    icon.classList.add("bi-heart");

                }

            });

        });

    });

});

</script>
@endpush
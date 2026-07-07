@extends('layouts.app')

@section('title', 'Home')

@section('content')

<!-- ================= HERO ================= -->
<section class="hero py-5 home-hero">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="badge rounded-pill px-3 py-2 mb-3"
                    style="background:#E7F3EE;color:#2E5E4E;">
                    ☕ Premium Cafe & Restaurant
                </span>

                <h1 class="display-4 fw-bold mb-4" style="color:#1F2937;">
                    Nikmati Makanan Lezat & Kopi Premium dalam Satu Tempat
                </h1>

                <p class="lead text-secondary mb-4">
                    Temukan berbagai pilihan makanan, minuman, dessert, dan kopi premium.
                    Pesan lebih mudah menggunakan QR Code dengan pelayanan cepat dan nyaman.
                </p>

                <div class="d-flex flex-wrap gap-3">

                    <a href="/menu" class="btn btn-success btn-lg rounded-pill px-4">
                        Lihat Menu
                    </a>

                    <a href="about" class="btn btn-outline-success btn-lg rounded-pill px-4">
                        Tentang Kami
                    </a>

                </div>

            </div>

            <div class="col-lg-6 text-center">

                <div class="hero-image-wrapper">
                    <img src="{{ asset('images/cafe.jpeg') }}"
                    class="hero-image"
                    alt="Hero Image">
                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= KATEGORI ================= -->

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">Kategori Menu</h2>

            <p class="text-secondary">
                Pilih menu favoritmu sesuai selera.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-3">

                <div class="card hover-card shadow-sm rounded-4 text-center p-4 h-100">

                    <div class="icon fs-1">🍔</div>

                    <h5 class="fw-bold mt-3">
                        Food
                    </h5>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card hover-card shadow-sm rounded-4 text-center p-4 h-100">

                    <div class="icon fs-1">☕</div>

                    <h5 class="fw-bold mt-3">
                        Coffee
                    </h5>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card hover-card shadow-sm rounded-4 text-center p-4 h-100">

                    <div class="icon fs-1">🥤</div>

                    <h5 class="fw-bold mt-3">
                        Drink
                    </h5>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card hover-card shadow-sm rounded-4 text-center p-4 h-100">

                    <div class="icon fs-1">🍰</div>

                    <h5 class="fw-bold mt-3">
                        Dessert
                    </h5>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= MENU FAVORIT ================= -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Menu Favorit
            </h2>

            <p class="text-secondary">
                Menu yang paling banyak dipesan pelanggan.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="card menu-card hover-card shadow rounded-4">

                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=900"
                        class="card-img-top"
                        alt="Pizza">

                    <div class="card-body">

                        <h5 class="fw-bold">
                            Cheese Pizza
                        </h5>

                        <p class="text-secondary">
                            Pizza dengan topping keju premium yang lezat.
                        </p>

                        <h4 class="text-success fw-bold">
                            Rp45.000
                        </h4>

                        <a href="#" class="btn btn-success rounded-pill w-100 mt-3">
                            Order Sekarang
                        </a>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card menu-card hover-card shadow rounded-4">

                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=900"
                        class="card-img-top"
                        alt="Steak">

                    <div class="card-body">

                        <h5 class="fw-bold">
                            Beef Steak
                        </h5>

                        <p class="text-secondary">
                            Daging sapi pilihan dengan saus spesial.
                        </p>

                        <h4 class="text-success fw-bold">
                            Rp75.000
                        </h4>

                        <a href="#" class="btn btn-success rounded-pill w-100 mt-3">
                            Order Sekarang
                        </a>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card menu-card hover-card shadow rounded-4">

                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900"
                        class="card-img-top"
                        alt="Coffee">

                    <div class="card-body">

                        <h5 class="fw-bold">
                            Cappuccino
                        </h5>

                        <p class="text-secondary">
                            Kopi premium dengan foam yang lembut.
                        </p>

                        <h4 class="text-success fw-bold">
                            Rp28.000
                        </h4>

                        <a href="#" class="btn btn-success rounded-pill w-100 mt-3">
                            Order Sekarang
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= KEUNGGULAN ================= -->

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Kenapa Memilih Kami?
            </h2>

        </div>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="card hover-card shadow-sm rounded-4 text-center p-4 h-100">

                    <i class="bi bi-cup-hot-fill icon text-success" style="font-size:55px;"></i>

                    <h4 class="fw-bold mt-3">
                        Premium Coffee
                    </h4>

                    <p class="text-secondary">
                        Menggunakan biji kopi pilihan dengan kualitas terbaik.
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card hover-card shadow-sm rounded-4 text-center p-4 h-100">

                    <i class="bi bi-egg-fried icon text-success" style="font-size:55px;"></i>

                    <h4 class="fw-bold mt-3">
                        Fresh Food
                    </h4>

                    <p class="text-secondary">
                        Semua makanan dimasak saat dipesan sehingga selalu fresh.
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card hover-card shadow-sm rounded-4 text-center p-4 h-100">

                    <i class="bi bi-qr-code-scan icon text-success" style="font-size:55px;"></i>

                    <h4 class="fw-bold mt-3">
                        QR Ordering
                    </h4>

                    <p class="text-secondary">
                        Scan QR Code, pilih menu, lalu pesan tanpa perlu antre.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= PROMO ================= -->
<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Promo Spesial</h2>
            <p class="text-secondary">Penawaran terbaik untuk kamu hari ini</p>
        </div>

        <div class="row g-4">

            @foreach($promotions as $promo)

            <div class="col-md-4">

                <div class="promo-card h-100">

                    <div class="promo-image">

                        <img src="{{ asset('storage/'.$promo->image) }}"
                            alt="{{ $promo->name }}">

                        <span class="badge-promo">
                            Promo
                        </span>

                    </div>

                    <div class="promo-content">

                        <h4>{{ $promo->name }}</h4>

                        <p class="text-muted">
                            {{ Str::limit($promo->description,80) }}
                        </p>

                        <h5 class="text-success fw-bold">
                            Rp {{ number_format($promo->price,0,',','.') }}
                        </h5>

                    </div>

                </div>
            </div>

            @endforeach

        </div>

    </div>

</section>

@endsection
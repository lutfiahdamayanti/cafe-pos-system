@extends('layouts.app')

@section('title','Fasilitas')

@section('content')

<!-- ================= HERO ================= -->
<section class="facilities-hero">

    <div class="hero-content">

        <h1>Fasilitas Kami</h1>

        <p>
            Nikmati berbagai fasilitas yang membuat pengalaman Anda semakin nyaman.
        </p>

    </div>

</section>

<!-- ================= FASILITAS ================= -->
<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Fasilitas Unggulan
            </h2>

            <p class="text-secondary">
                Semua fasilitas dirancang agar pelanggan merasa nyaman.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-4 col-md-6">

                <div class="facility-card">

                    <div class="facility-icon">
                        <i class="bi bi-wifi"></i>
                    </div>

                    <h4>Free WiFi</h4>

                    <p>
                        Internet cepat untuk bekerja maupun bersantai.
                    </p>

                </div>

            </div>

            <div class="col-lg-4 col-md-6">

                <div class="facility-card">

                    <div class="facility-icon">
                        <i class="bi bi-car-front-fill"></i>
                    </div>

                    <h4>Area Parkir</h4>

                    <p>
                        Tempat parkir luas dan aman untuk kendaraan.
                    </p>

                </div>

            </div>

            <div class="col-lg-4 col-md-6">

                <div class="facility-card">

                    <div class="facility-icon">
                        <i class="bi bi-plug-fill"></i>
                    </div>

                    <h4>Charging Station</h4>

                    <p>
                        Stop kontak tersedia di setiap area meja.
                    </p>

                </div>

            </div>

            <div class="col-lg-4 col-md-6">

                <div class="facility-card">

                    <div class="facility-icon">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>

                    <h4>Premium Coffee</h4>

                    <p>
                        Biji kopi pilihan dengan kualitas terbaik.
                    </p>

                </div>

            </div>

            <div class="col-lg-4 col-md-6">

                <div class="facility-card">

                    <div class="facility-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h4>Meeting Area</h4>

                    <p>
                        Ruangan nyaman untuk meeting maupun diskusi.
                    </p>

                </div>

            </div>

            <div class="col-lg-4 col-md-6">

                <div class="facility-card">

                    <div class="facility-icon">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>

                    <h4>QR Ordering</h4>

                    <p>
                        Pesan makanan hanya dengan scan QR Code.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= GALLERY ================= -->
<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Suasana Cafe
            </h2>

            <p class="text-secondary">
                Nikmati suasana nyaman untuk bekerja,
                berkumpul bersama teman maupun keluarga.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-4 col-md-6">

                <img
                    src="{{ asset('images/sk1.jpeg') }}"
                    alt="Gallery 1"
                    class="gallery-img">

            </div>

            <div class="col-lg-4 col-md-6">

                <img
                    src="{{ asset('images/sk2.jpeg') }}"
                    alt="Gallery 2"
                    class="gallery-img">

            </div>

            <div class="col-lg-4 col-md-6">

                <img
                    src="{{ asset('images/sk3.jpeg') }}"
                    alt="Gallery 3"
                    class="gallery-img">

            </div>

        </div>

    </div>

</section>

<!-- ================= QR ORDERING ================= -->
<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Cara Kerja QR Ordering
            </h2>

            <p class="text-secondary">
                Nikmati pengalaman memesan yang lebih cepat tanpa perlu menunggu pelayan.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-3">

                <div class="facility-card text-center h-100">

                    <div class="facility-icon">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>

                    <h5>1. Scan QR</h5>

                    <p class="text-secondary">
                        Scan QR Code yang tersedia di setiap meja.
                    </p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="facility-card text-center h-100">

                    <div class="facility-icon">
                        <i class="bi bi-list-check"></i>
                    </div>

                    <h5>2. Pilih Menu</h5>

                    <p class="text-secondary">
                        Jelajahi menu dan pilih makanan atau minuman favoritmu.
                    </p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="facility-card text-center h-100">

                    <div class="facility-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>

                    <h5>3. Checkout</h5>

                    <p class="text-secondary">
                        Masukkan pesanan ke keranjang lalu lakukan checkout.
                    </p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="facility-card text-center h-100">

                    <div class="facility-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>

                    <h5>4. Pesanan Diproses</h5>

                    <p class="text-secondary">
                        Tim kami akan segera menyiapkan pesanan untuk meja Anda.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
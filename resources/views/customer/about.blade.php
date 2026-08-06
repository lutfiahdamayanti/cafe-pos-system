@extends('layouts.app')
@section('title','Tentang Kami')
@section('content')

<!-- ================= HERO ================= -->
<section class="about-hero">
    <div class="container text-center">
        <h1>Tentang Kami</h1>
        <p>
            Menghadirkan pengalaman bersantap yang nyaman melalui
            perpaduan cita rasa terbaik, suasana yang hangat,
            dan teknologi pemesanan modern berbasis QR Code.
        </p>
    </div>
</section>

<!-- ================= STORY ================= -->
<section class="about-section py-5">
    <div class="container">
        <div class="row g-5 align-items-stretch">
            {{-- IMAGE --}}
            <div class="col-lg-6">
                <div class="about-image h-100">
                    <img
                        src="{{ asset('images/cafe.jpeg') }}"
                        alt="Cafe & Restaurant">
                </div>
            </div>

            {{-- TEXT --}}
            <div class="col-lg-6">
                <div class="about-content h-100">
                    <span class="badge bg-success rounded-pill px-3 py-2 mb-3">
                        Tentang Cafe & Restaurant
                    </span>
                    <h2 class="fw-bold mb-4">
                        Lebih dari Sekadar Tempat Menikmati Kopi
                    </h2>
                    <p>
                        Cafe & Restaurant hadir sebagai tempat yang mengutamakan
                        kualitas makanan, minuman, dan pelayanan.
                        Kami percaya bahwa setiap hidangan yang disajikan harus
                        memberikan pengalaman yang berkesan bagi setiap pelanggan.
                    </p>
                    <p>
                        Dengan konsep modern dan suasana yang nyaman,
                        kami menghadirkan berbagai pilihan menu mulai dari
                        kopi premium, makanan utama, dessert,
                        hingga minuman segar yang dibuat menggunakan
                        bahan-bahan berkualitas.
                    </p>
                    <p>
                        Untuk memberikan pelayanan yang lebih cepat dan praktis,
                        kami juga menerapkan sistem pemesanan berbasis
                        QR Code sehingga pelanggan dapat memesan langsung
                        dari meja tanpa perlu menunggu pelayan.
                    </p>
                    <a href="{{ route('menu') }}"
                       class="btn btn-success rounded-pill px-4 mt-3">
                        <i class="bi bi-arrow-right me-2"></i>
                        Jelajahi Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= VISION MISSION ================= -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="facility-card h-100">
                    <div class="facility-icon">
                        <i class="bi bi-eye-fill"></i>
                    </div>

                    <h3 class="fw-bold">
                        Visi
                    </h3>

                    <p>
                        Menjadi cafe dan restaurant pilihan utama
                        yang dikenal melalui kualitas makanan,
                        pelayanan terbaik,
                        serta inovasi teknologi dalam pengalaman
                        bersantap pelanggan.
                    </p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="facility-card h-100">
                    <div class="facility-icon">
                        <i class="bi bi-bullseye"></i>
                    </div>

                    <h3 class="fw-bold">
                        Misi
                    </h3>

                    <ul class="text-muted">
                        <li>Menyajikan makanan dan minuman berkualitas.</li>
                        <li>Memberikan pelayanan yang cepat dan ramah.</li>
                        <li>Menciptakan suasana cafe yang nyaman.</li>
                        <li>Menghadirkan sistem pemesanan digital yang praktis.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= WHY US ================= -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">
                Kenapa Memilih Kami?
            </h2>

            <p class="text-secondary">
                Kami berkomitmen memberikan pengalaman terbaik
                bagi setiap pelanggan.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="facility-card h-100">
                    <div class="facility-icon">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>

                    <h4>
                        Premium Coffee
                    </h4>

                    <p>
                        Menggunakan biji kopi pilihan dengan cita rasa terbaik.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="facility-card h-100">
                    <div class="facility-icon">
                        <i class="bi bi-egg-fried"></i>
                    </div>

                    <h4>
                        Fresh Food
                    </h4>

                    <p>
                        Seluruh menu dibuat menggunakan bahan berkualitas.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="facility-card h-100">
                    <div class="facility-icon">
                        <i class="bi bi-house-heart-fill"></i>
                    </div>

                    <h4>
                        Suasana Nyaman
                    </h4>

                    <p>
                        Cocok untuk bekerja,
                        belajar,
                        maupun berkumpul bersama keluarga.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="facility-card h-100">
                    <div class="facility-icon">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>

                    <h4>
                        QR Ordering
                    </h4>

                    <p>
                        Sistem pemesanan digital yang cepat,
                        mudah,
                        dan efisien.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= CTA =================
<section class="py-5 bg-success text-white">

    <div class="container text-center">

        <h2 class="fw-bold">

            Siap Menikmati Pengalaman Bersantap Bersama Kami?

        </h2>

        <p class="mt-3">

            Jelajahi berbagai pilihan menu favorit dan lakukan
            pemesanan dengan mudah melalui sistem QR Ordering.

        </p>

        <a href="{{ route('menu') }}"
           class="btn btn-light rounded-pill px-5 mt-3">

            Lihat Menu

        </a>

    </div>

</section> -->

@endsection
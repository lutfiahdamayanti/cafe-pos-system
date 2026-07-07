@extends('layouts.app')

@section('content')

<!-- HERO ABOUT -->
<section class="about-hero">

    <div class="container text-center">

        <h1>Tentang Kami</h1>

        <p>
            Kenali lebih dekat perjalanan, visi, dan komitmen kami
            dalam menghadirkan pengalaman terbaik untuk setiap pelanggan.
        </p>

    </div>

</section>

<!-- ABOUT CONTENT -->
<section class="about-section py-5">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- IMAGE -->
            <div class="col-md-6">

                <div class="about-image">

                    <img src="{{ asset('images/cafe.jpeg') }}" alt="Cafe">

                </div>

            </div>

            <!-- TEXT -->
            <div class="col-md-6">

                <h2 class="fw-bold mb-3">
                    Kopi, Suasana, dan Cerita
                </h2>

                <p class="text-muted">
                    Coffee Shop kami berdiri dengan tujuan menghadirkan pengalaman minum kopi yang tidak hanya sekadar minuman,
                    tetapi juga momen yang berkesan. Kami menggunakan biji kopi pilihan terbaik dari berbagai daerah di Indonesia.
                </p>

                <p class="text-muted">
                    Dengan suasana modern dan nyaman, kami ingin menjadi tempat terbaik untuk bekerja, bersantai, maupun berkumpul bersama orang terdekat.
                </p>

                <div class="mt-4">

                    <a href="{{ route('menu') }}" class="btn btn-outline-success px-4 py-2 rounded-pill">
                        Lihat Menu
                    </a>

                </div>

            </div>

        </div>

        <!-- FEATURE -->
        <div class="row mt-5 g-4">

    <div class="col-lg-4">

        <div class="facility-card">

            <div class="facility-icon">
                <i class="bi bi-cup-hot-fill"></i>
            </div>

            <h4>Kopi Premium</h4>

            <p>
                Biji kopi pilihan dengan kualitas terbaik.
            </p>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="facility-card">

            <div class="facility-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <h4>Suasana Nyaman</h4>

            <p>
                Tempat ideal untuk bekerja dan bersantai.
            </p>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="facility-card">

            <div class="facility-icon">
                <i class="bi bi-qr-code-scan"></i>
            </div>

            <h4>Order Mudah</h4>

            <p>
                Pemesanan cepat melalui sistem QR.
            </p>

        </div>

    </div>

</div>

    </div>

</section>

@endsection
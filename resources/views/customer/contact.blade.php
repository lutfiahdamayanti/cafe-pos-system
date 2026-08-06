@extends('layouts.app')
@section('content')

<!-- HERO -->
<section class="contact-hero">
    <div class="container text-center">
        <h1>Hubungi Kami</h1>
        <p>
            Kami siap melayani Anda setiap hari.
        </p>
    </div>
</section>

<section class="contact-section py-5">
    <div class="container">
        <div class="row g-5 align-items-stretch">
            <!-- INFORMASI -->
            <div class="col-lg-5">
                <div class="contact-card h-100">
                    <h3 class="fw-bold mb-4">
                        Informasi Cafe
                    </h3>

                    <div class="mb-4">
                        <p>
                            <i class="bi bi-geo-alt-fill text-success me-2"></i>
                            Jl. Malioboro, Yogyakarta
                        </p>
                        <p>
                            <i class="bi bi-telephone-fill text-success me-2"></i>
                            0812-3456-7890
                        </p>
                        <p>
                            <i class="bi bi-envelope-fill text-success me-2"></i>
                            cafeandresto@gmail.com
                        </p>
                    </div>

                    <hr>

                    <h5 class="fw-bold mt-4">
                        Jam Operasional
                    </h5>
                    <p class="mb-1">
                        Senin - Minggu
                    </p>
                    <p>
                        08.00 - 22.00 WIB
                    </p>

                    <hr>

                    <h5 class="fw-bold mt-4">
                        Ikuti Kami
                    </h5>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="social-box">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-box">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-box">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <a href="#" class="social-box">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- MAP -->
            <div class="col-lg-7">
                <div class="map-box h-100">
                    <iframe
                        src="https://www.google.com/maps?q=Jl.+Malioboro,+Yogyakarta&output=embed"
                        width="100%"
                        height="100%"
                        style="border:0;border-radius:20px;min-height:450px;"
                        loading="lazy"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
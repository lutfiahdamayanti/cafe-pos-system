@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="contact-hero">

    <div class="container text-center">

        <h1>Hubungi Kami</h1>

        <p>
            Kami siap membantu dan menjawab pertanyaan Anda kapan saja.
        </p>

    </div>

</section>

<!-- CONTACT SECTION -->
<section class="contact-section py-5">

    <div class="container">

        <div class="row g-5">

            <!-- FORM -->
            <div class="col-md-6">

                <div class="contact-card">

                    <h4 class="mb-3">Kirim Pesan</h4>

                    <form>

                        <div class="mb-3">

                            <label>Nama</label>

                            <input type="text" class="form-control" placeholder="Nama kamu">

                        </div>

                        <div class="mb-3">

                            <label>Email</label>

                            <input type="email" class="form-control" placeholder="email@gmail.com">

                        </div>

                        <div class="mb-3">

                            <label>Pesan</label>

                            <textarea class="form-control" rows="5" placeholder="Tulis pesan..."></textarea>

                        </div>

                        <button class="btn btn-success w-100 rounded-pill">
                            Kirim Pesan
                        </button>

                    </form>

                </div>

            </div>

            <!-- INFO -->
            <div class="col-md-6">

                <div class="contact-card h-100">

                    <h4 class="mb-3">Informasi</h4>

                    <p>📍 Jl. Malioboro, Yogyakarta</p>

                    <p>📞 0812-3456-7890</p>

                    <p>✉️ cafeandresto@gmail.com</p>

                    <hr>

                    <h5>Jam Operasional</h5>

                    <p>Senin - Minggu</p>

                    <p>08:00 - 22:00</p>

                    <hr>

                    <h5>Follow Kami</h5>

                    <div class="d-flex gap-2">

                        <a href="#" class="social-box">
                            <i class="bi bi-instagram"></i>
                        </a>

                        <a href="#" class="social-box">
                            <i class="bi bi-whatsapp"></i>
                        </a>

                        <a href="#" class="social-box">
                            <i class="bi bi-facebook"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <!-- MAP -->
        <div class="mt-5">

            <div class="map-box">

                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18..."
                    width="100%"
                    height="350"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">

                </iframe>

            </div>

        </div>

    </div>

</section>

@endsection
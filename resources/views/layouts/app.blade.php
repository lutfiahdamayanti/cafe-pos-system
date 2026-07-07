<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') | Cafe & Restaurant</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg sticky-top">

    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center gap-3" href="/">

            <img src="{{ asset('images/logo.png') }}" width="45" height="45" alt="logo">

            <div class="lh-sm">
                <div class="fw-bold" style="color:#2E5E4E;">
                    Cafe & Restaurant
                </div>
            </div>

        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="/">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="menu">Menu</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="facilities">Fasilitas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="about">Tentang</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contact">Kontak</a>
                </li>

            </ul>

            <!-- RIGHT ACTION -->
            <div class="d-flex align-items-center gap-3">

                <a href="#" class="text-dark fs-5" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bi bi-search"></i>
                </a>

                <a href="cart" class="text-dark fs-5 position-relative">
                    <i class="bi bi-cart3"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge bg-warning">
                        2
                    </span>
                </a>

                <a href="login" class="btn btn-outline-success rounded-pill px-3">
                    Login
                </a>

                <a href="menu" class="btn btn-success rounded-pill px-3">
                    Order Now
                </a>

            </div>

        </div>

    </div>

</nav>

<!-- ================= CONTENT ================= -->
<main>
    @yield('content')
</main>

<!-- ================= FOOTER ================= -->
<footer class="bg-dark text-white mt-5">

    <div class="container py-5">

        <div class="row g-4">

            <div class="col-lg-4">
                <h4 class="fw-bold">Cafe & Restaurant</h4>
                <p class="text-light mt-3">
                    Fresh food, premium coffee, and seamless QR ordering experience.
                </p>
            </div>

            <div class="col-lg-2">
                <h6>Navigation</h6>
                <ul class="list-unstyled mt-3">
                    <li><a href="/" class="text-white text-decoration-none">Home</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Menu</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Tentang</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h6>Opening Hours</h6>
                <p class="mt-3">
                    Everyday<br>
                    08:00 - 22:00
                </p>
            </div>

            <div class="col-lg-3">
                <h6>Newsletter</h6>

                <div class="input-group mt-3">
                    <input type="email" class="form-control" placeholder="Email">
                    <button class="btn btn-success">Subscribe</button>
                </div>
            </div>

        </div>

        <hr class="border-secondary">

        <p class="text-center mb-0">
            © {{ date('Y') }} Cafe & Restaurant. All rights reserved.
        </p>

    </div>

</footer>

<!-- SEARCH POPUP MODAL -->
<div class="modal fade" id="searchModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content search-popup">

            <div class="modal-header border-0">

                <h5 class="modal-title">Cari Menu</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                <div class="search-input-box">

                    <i class="bi bi-search"></i>

                    <input type="text" placeholder="Cari kopi, makanan, dessert...">

                </div>

                <!-- RESULT MINI -->
                <div class="search-result mt-3">

                    <div class="search-item">
                        <img src="{{ asset('images/americano.jpg') }}">
                        <div>
                            <h6>Americano</h6>
                            <small>Coffee</small>
                        </div>
                        <span>25K</span>
                    </div>

                    <div class="search-item">
                        <img src="{{ asset('images/latte.jpg') }}">
                        <div>
                            <h6>Latte</h6>
                            <small>Coffee</small>
                        </div>
                        <span>30K</span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
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
<nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
    <div class="container">
        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center gap-3" href="/">
            <img src="{{ asset('images/hot.png') }}" width="45" height="45" alt="logo">
            <div class="lh-sm">
                <div class="fw-bold" style="color:#2E5E4E;">Cafe & Restaurant</div>
            </div>
        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <!-- <li class="nav-item">
                    <a class="nav-link active" href="/">Home</a>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('menu*') ? 'active' : '' }}" href="{{ route('menu') }}">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('facilities') ? 'active' : '' }}" href="{{ route('facilities') }}">Fasilitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                </li>
            </ul>

            <!-- RIGHT ACTION -->
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-dark fs-5" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bi bi-search"></i>
                </a>

                @php $cartCount = \App\Models\Cart::sum('qty'); @endphp

                <a href="{{ route('cart.index') }}" class="text-dark fs-5 position-relative">
                    <i class="bi bi-cart3"></i>
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- <a href="{{ route('login') }}" class="btn btn-outline-success rounded-pill px-3">
                    Login
                </a>
                <a href="menu" class="btn btn-success rounded-pill px-3">
                    Order Now
                </a> --}}
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
                <p class="text-light mt-3">Menyajikan makanan segar, kopi berkualitas, dan pengalaman pemesanan yang mudah melalui QR Code.</p>
            </div>

            <div class="col-lg-2">
                <h6>Navigasi</h6>
                <ul class="list-unstyled mt-3">
                    <!-- <li>
                        <a href="{{ route('home') }}" class="text-white text-decoration-none">
                            Home
                        </a>
                    </li> -->
                    <li><a href="{{ route('menu') }}" class="text-white text-decoration-none">Menu</a></li>
                    <li><a href="{{ route('facilities') }}" class="text-white text-decoration-none">Fasilitas</a></li>
                    <li><a href="{{ route('about') }}" class="text-white text-decoration-none">Tentang</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h6>Jam Operasional</h6>
                <p class="mt-3">Setiap Hari<br>08:00 - 22:00</p>
            </div>

            <div class="col-lg-3">
                <h6>Ikuti Kami</h6>
                <p class="mt-3 text-light">Ikuti kami di media sosial untuk mendapatkan promo terbaru.</p>
                <div class="d-flex gap-3 fs-3 mt-3">
                    <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-tiktok"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary">

        <p class="text-center mb-0">
            © {{ date('Y') }} Cafe & Restaurant
            <span class="mx-2">•</span>
            Created by <strong>lupii</strong>
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
                    <input type="text" id="searchMenu" placeholder="Cari kopi, makanan, dessert...">
                </div>
                <div class="search-result mt-3" id="searchResult">
                    <p class="text-center text-muted">Mulai ketik nama menu...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')

@if(session('success'))
<script>
Swal.fire({
    toast:true,
    position:'top-end',
    icon:'success',
    title:'{{ session("success") }}',
    showConfirmButton:false,
    timer:2500,
    timerProgressBar:true,
    didOpen:(toast)=>{
        toast.style.marginTop='80px';
    }
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    toast:true,
    position:'top-end',
    icon:'error',
    title:'{{ session("error") }}',
    showConfirmButton:false,
    timer:2500,
    timerProgressBar:true,
    didOpen:(toast)=>{
        toast.style.marginTop='80px';
    }
});
</script>
@endif

<script>
document.addEventListener("DOMContentLoaded",function(){
    const input=document.getElementById("searchMenu");
    const result=document.getElementById("searchResult");

    input.addEventListener("keyup",function(){
        let keyword=this.value;

        if(keyword.length==0){
            result.innerHTML=`
                <p class="text-center text-muted">
                    Mulai ketik nama menu...
                </p>
            `;
            return;
        }

        fetch("/search-menu?keyword="+keyword)
        .then(response=>response.json())
        .then(data=>{
            let html="";

            if(data.length==0){
                html=`
                    <p class="text-center text-muted">
                        Menu tidak ditemukan
                    </p>
                `;
            }

            data.forEach(menu=>{
                html+=`
                <div class="search-item mb-3 d-flex align-items-center"
                    onclick="window.location.href='{{ url('/menu') }}/${menu.id}'"
                    style="cursor:pointer;">

                    <img src="/images/${menu.image}"
                        width="60"
                        height="60"
                        class="rounded me-3">

                    <div class="flex-grow-1">
                        <h6 class="mb-0">${menu.name}</h6>
                        <small>${menu.category.name}</small>
                    </div>

                    <span class="fw-bold text-success">
                        Rp ${Number(menu.price).toLocaleString('id-ID')}
                    </span>
                </div>
                `;
            });

            result.innerHTML=html;
        });
    });
});
</script>

</body>
</html>
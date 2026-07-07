@extends('layouts.app')

@section('title','Fasilitas')

@section('content')

<!-- HERO -->
<section class="facilities-hero">

    <div class="hero-content">

        <h1>Fasilitas Kami</h1>

        <p>
            Nikmati berbagai fasilitas yang membuat pengalaman Anda semakin nyaman.
        </p>

    </div>

</section>

<!-- FASILITAS -->
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

<div class="col-lg-4">

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

<div class="col-lg-4">

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

<div class="col-lg-4">

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

<div class="col-lg-4">

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

<div class="col-lg-4">

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

<div class="col-lg-4">

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


<!-- GALLERY -->

<section class="py-5 bg-soft">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">
Suasana Cafe
</h2>

</div>

<div class="row g-4">

<div class="col-lg-4">

<img src="{{ asset('images/gallery1.jpg') }}"
class="gallery-img">

</div>

<div class="col-lg-4">

<img src="{{ asset('images/gallery2.jpg') }}"
class="gallery-img">

</div>

<div class="col-lg-4">

<img src="{{ asset('images/gallery3.jpg') }}"
class="gallery-img">

</div>

</div>

</div>

</section>


<!-- QR ORDERING -->

<section class="py-5">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<img src="{{ asset('images/qr-order.png') }}"
class="img-fluid">

</div>

<div class="col-lg-6">

<h2 class="fw-bold">

Pesan Lebih Mudah

</h2>

<p class="text-secondary mt-3">

Scan QR Code pada meja,
pilih menu favorit,
lakukan pembayaran,
dan pesanan langsung diproses.

</p>

<div class="mt-4">

<a href="/menu" class="btn btn-success rounded-pill px-4">

Lihat Menu

</a>

</div>

</div>

</div>

</div>

</section>

@endsection
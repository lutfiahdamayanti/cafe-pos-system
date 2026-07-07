@extends('layouts.auth')

@section('title','Register')

@section('content')

<div class="auth-section">

    <div class="container">

        <div class="row auth-card">

            <!-- LEFT -->
            <div class="col-lg-6 auth-left">

                <a href="/" class="logo">

                    <img src="{{ asset('images/logo.png') }}" width="55">

                    <h4>Cafe & Restaurant</h4>

                </a>

                <h2>Buat Akun Baru ✨</h2>

                <p>
                    Daftar sekarang dan nikmati pengalaman memesan makanan & minuman melalui QR Ordering.
                </p>

                <form>

                    <!-- Nama -->
                    <div class="mb-3">

                        <label>Nama Lengkap</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Masukkan nama lengkap">

                        </div>

                    </div>

                    <!-- Email -->
                    <div class="mb-3">

                        <label>Email</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input
                                type="email"
                                class="form-control"
                                placeholder="Masukkan email">

                        </div>

                    </div>

                    <!-- No HP -->
                    <div class="mb-3">

                        <label>Nomor HP</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-telephone"></i>
                            </span>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="08xxxxxxxxxx">

                        </div>

                    </div>

                    <!-- Password -->
                    <div class="mb-3">

                        <label>Password</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input
                                type="password"
                                class="form-control"
                                placeholder="Masukkan password">

                            <button type="button" class="input-group-text">
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>

                    <!-- Konfirmasi -->
                    <div class="mb-3">

                        <label>Konfirmasi Password</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-shield-lock"></i>
                            </span>

                            <input
                                type="password"
                                class="form-control"
                                placeholder="Ulangi password">

                            <button type="button" class="input-group-text">
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>

                    <!-- Checkbox -->
                    <div class="form-check mb-4">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="agree">

                        <label class="form-check-label" for="agree">

                            Saya menyetujui
                            <a href="#">Syarat & Ketentuan</a>

                        </label>

                    </div>

                    <!-- Button -->
                    <button class="btn btn-login w-100">

                        Daftar Sekarang

                    </button>

                </form>

                <div class="auth-footer">

                    Sudah punya akun?

                    <a href="{{ route('login') }}">
                        Login
                    </a>

                </div>

            </div>

            <!-- RIGHT -->

            <div class="col-lg-6 auth-right">

                <img
                    src="{{ asset('images/register.png') }}"
                    class="img-fluid"
                    alt="Register">

                <div class="floating-card top">

                    👤 5000+ Members

                </div>

                <div class="floating-card bottom">

                    ☕ Join & Get Special Promo

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
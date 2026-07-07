@extends('layouts.auth')

@section('title','Admin Login')

@section('content')

<div class="auth-section">

    <div class="container">

        <div class="row auth-card">

            <!-- LEFT -->
            <div class="col-lg-5 auth-left admin-left">

                <div class="admin-badge">

                    <i class="bi bi-shield-lock-fill"></i>

                    Administrator Panel

                </div>

                <h2>Welcome Admin 👋</h2>

                <p>
                    Silakan login untuk mengelola menu, pesanan, kategori, promo, dan seluruh aktivitas Cafe & Restaurant POS System.
                </p>

                <form>

                    <!-- Email -->
                    <div class="mb-3">

                        <label>Email</label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-envelope-fill"></i>

                            </span>

                            <input
                                type="email"
                                class="form-control"
                                placeholder="admin@email.com">

                        </div>

                    </div>

                    <!-- Password -->
                    <div class="mb-4">

                        <label>Password</label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-lock-fill"></i>

                            </span>

                            <input
                                type="password"
                                class="form-control"
                                placeholder="********">

                            <button
                                class="input-group-text"
                                type="button">

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>

                    <button class="btn btn-login w-100">

                        <i class="bi bi-box-arrow-in-right me-2"></i>

                        Login Admin

                    </button>

                </form>

                <div class="auth-footer mt-4">

                    <a href="{{ route('login') }}">

                        ← Kembali ke Login Customer

                    </a>

                </div>

            </div>

            <!-- RIGHT -->

            <div class="col-lg-7 auth-right">

                <img
                    src="{{ asset('images/admin-login.png') }}"
                    class="img-fluid"
                    alt="Admin">

                <div class="floating-card top">

                    📊 Dashboard

                </div>

                <div class="floating-card middle">

                    📦 Orders

                </div>

                <div class="floating-card bottom">

                    ☕ Menu Management

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
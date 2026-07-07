@extends('layouts.auth')

@section('title','Login')

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

                <h2>Welcome Back 👋</h2>

                <p>
                    Login untuk mulai menikmati pengalaman QR Ordering yang cepat dan mudah.
                </p>

                <form>

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

                            <button class="input-group-text">
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>

                    <div class="d-flex justify-content-between mb-4">

                        <div>

                            <input type="checkbox">

                            Remember Me

                        </div>

                        <a href="#">
                            Forgot Password?
                        </a>

                    </div>

                    <button class="btn btn-login w-100">

                        Login

                    </button>

                </form>

                <div class="auth-footer">

                    Belum punya akun?

                    <a href="{{ route('register') }}">

                        Register

                    </a>

                </div>

                <div class="admin-link">

                    Administrator?

                    <a href="{{ route('admin.login') }}">

                        Login Admin →

                    </a>

                </div>

            </div>

            <!-- RIGHT -->

            <div class="col-lg-6 auth-right">

                <img
                    src="{{ asset('images/login.png') }}"
                    class="img-fluid">

                <div class="floating-card top">

                    ⭐ 4.9 Rating

                </div>

                <div class="floating-card bottom">

                    🍽️ 1200+ Orders

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
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

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('admin.authenticate') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">

                        <label>Email</label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-envelope-fill"></i>

                            </span>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="admin@email.com"
                                value="{{ old('email') }}"
                                required>

                            @error('email')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                            @enderror
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
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="********"
                                required>
                            <button
                                type="button"
                                class="input-group-text"
                                id="togglePassword">

                                <i class="bi bi-eye" id="eyeIcon"></i>

                            </button>

                        </div>

                    </div>

                    <button type="submit" class="btn btn-login w-100">

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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const password = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');
    const eye = document.getElementById('eyeIcon');

    if (toggle && password) {

        toggle.addEventListener('click', function () {

            if (password.type === 'password') {

                password.type = 'text';
                eye.classList.remove('bi-eye');
                eye.classList.add('bi-eye-slash');

            } else {

                password.type = 'password';
                eye.classList.remove('bi-eye-slash');
                eye.classList.add('bi-eye');

            }

        });

    }

});
</script>

@endsection
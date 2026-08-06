@extends('layouts.auth')
@section('title','Login Admin')
@section('content')

<div class="login-page">
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="{{ asset('images/hot.png') }}"
                width="80"
                class="mb-3">

            <h2 class="fw-bold">
                Administrator Panel
            </h2>

            <p class="text-muted">
                Silakan login untuk mengakses Dashboard Cafe POS
            </p>
        </div>

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
            <div class="mb-3">
                <label class="form-label">
                    Email
                </label>

                <div class="input-group modern-input">
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
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    Password
                </label>

                <div class="input-group modern-input">
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
                        class="input-group-text"
                        type="button"
                        id="togglePassword">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Login
            </button>
        </form>

        <div class="login-footer">
            Cafe & Restaurant POS System
        </div>
    </div>
</div>

<script>
const password=document.getElementById('password');
const toggle=document.getElementById('togglePassword');
const eye=document.getElementById('eyeIcon');
toggle.onclick=function(){
    if(password.type==="password"){
        password.type="text";
        eye.classList.replace("bi-eye","bi-eye-slash");
    }else{
        password.type="password";
        eye.classList.replace("bi-eye-slash","bi-eye");
    }
}
</script>
@endsection
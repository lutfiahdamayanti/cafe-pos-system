@extends('layouts.admin')
@section('title','Dashboard Super Admin')

@section('content')

<div class="container-fluid">

    {{-- Hero --}}
    <div class="super-hero mb-5">

        <div class="hero-content">

            <div>

                <h2>
                    Selamat Datang, {{ Auth::user()->name }} 👋
                </h2>

                <p>
                    Anda login sebagai <strong>Super Admin</strong>.
                    Seluruh akun pengguna Cafe POS dapat dikelola melalui menu
                    <b>Kelola User</b>.
                </p>

            </div>

            <div class="hero-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

        </div>

    </div>


    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="super-card">

                <div class="super-user-icon">

                    <i class="bi bi-people-fill"></i>

                </div>

                <h3>
                    Kelola User
                </h3>

                <p>
                    Tambahkan akun baru, ubah data pengguna,
                    hapus akun yang tidak digunakan,
                    serta atur hak akses Owner,
                    Manager, Cashier.
                </p>

                <a href="{{ route('superadmin.users.index') }}"
                   class="btn btn-super">
                    <i class="bi bi-arrow-right-circle me-2"></i>
                    Masuk ke Kelola User
                </a>

            </div>

        </div>

    </div>

</div>

@endsection
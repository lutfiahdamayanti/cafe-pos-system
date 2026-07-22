@extends('layouts.admin')
@section('title','Dashboard Super Admin')
@section('content')

<div class="container-fluid">
    <h2 class="mb-3">
        Dashboard Super Admin
    </h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4>Selamat Datang 👋</h4>
            <p>
                Anda login sebagai
                <strong>Super Admin</strong>.
            </p>

            <p>
                Gunakan menu <b>Kelola User</b>
                untuk membuat akun baru Owner,
                Manager, Cashier, maupun Kitchen.
            </p>
        </div>
    </div>
</div>
@endsection
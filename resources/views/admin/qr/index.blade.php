@extends('layouts.admin')

@section('title','QR Ordering')

@section('content')

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        QR Ordering Meja
    </h2>

    <!-- TAMBAHKAN DI SINI -->
    <div class="mb-4">

        <a href="{{ route('admin.qr.print') }}"
           target="_blank"
           class="btn btn-success">

            <i class="bi bi-printer"></i>
            Print Semua QR

        </a>

    </div>

    <div class="row">

        @foreach($tables as $table)

        <div class="col-lg-3 col-md-4 mb-4">

            <div class="card shadow border-0">

                <div class="card-body text-center">

                    <h5 class="fw-bold">
                        Meja {{ $table }}
                    </h5>

                    {!! QrCode::size(180)->generate(url('/menu?table='.$table)) !!}

                    <p class="small text-primary mt-2">
                        {{ url('/menu?table='.$table) }}
                    </p>

                    <p class="mt-3 text-muted">
                        Scan untuk melakukan pemesanan
                    </p>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection
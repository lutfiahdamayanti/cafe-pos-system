@extends('layouts.admin')

@section('title','Kitchen Display')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <span class="badge bg-success fs-6">
            {{ $orders->count() }} Pesanan
        </span>

    </div>

    <div class="row">

    @forelse($orders as $index => $order)

        <div class="col-lg-4 mb-4">

            <div class="card shadow rounded-4

            @if($order->created_at->diffInMinutes(now()) >= 10)

                border border-3 border-danger

            @else

                border-0

            @endif

            ">

                <div class="card-header bg-dark text-white">

                    <h6 class="mb-1">
                        Antrian #{{ $index + 1 }}
                    </h6>

                    <strong>
                        {{ $order->order_number }}
                    </strong>

                </div>

                <div class="card-body">

                    <h5 class="fw-bold">
                        {{ $order->customer_name }}
                    </h5>

                    <p class="mb-1">
                        <strong>Meja :</strong>
                        {{ $order->table_number ?? '-' }}
                    </p>

                    <p class="mb-1">
                        <strong>Tipe :</strong>
                        {{ $order->visit_type }}
                    </p>

                    <p class="mb-1">
                        <strong>Jam Masuk :</strong>
                        {{ $order->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                    </p>

                    <p class="text-danger fw-bold">
                        {{ $order->created_at->diffForHumans() }}
                    </p>

                    <p class="mb-1">
                        <strong>Total Item :</strong>
                        {{ $order->details->sum('qty') }}
                    </p>

                        @php

                        $estimate = $order->details->max(function($detail){

                            return $detail->menu->preparation_time ?? 0;

                        });

                        @endphp

                        <p class="mb-2">

                            <strong>Estimasi Selesai :</strong>

                            {{ $estimate }} menit

                        </p>

                        @if($order->cooking_started_at)

                        @php

                        $duration = floor(
                            \Carbon\Carbon::parse($order->cooking_started_at)
                                ->diffInSeconds(now()) / 60
                        );

                        @endphp

                        <p class="text-primary fw-bold">

                            ⏱ Durasi Memasak :
                            {{ $duration }} menit

                        </p>

                        @endif

                    <hr>

                    <h6 class="fw-bold mb-3">
                        Daftar Pesanan
                    </h6>

                    @foreach($order->details as $detail)

                        <div class="d-flex justify-content-between mb-2">

                            <span>

                                {{ $detail->menu->name }}

                            </span>

                            <strong>

                                x{{ $detail->qty }}

                            </strong>

                        </div>

                    @endforeach

                    @if($order->note)

                        <hr>

                        <div class="alert alert-warning py-2 mb-3">

                            <strong>Catatan:</strong>

                            <br>

                            {{ $order->note }}

                        </div>

                    @endif

                    <hr>

                    @if($order->status=='Accepted')

                        <span class="badge bg-warning text-dark">

                            Accepted

                        </span>

                    @elseif($order->status=='Processing')

                        <span class="badge bg-primary">

                            Processing

                        </span>

                    @elseif($order->status=='Ready')

                        <span class="badge bg-success">

                            Ready

                        </span>

                    @endif

                </div>

                <div class="card-footer bg-white">

                    @if($order->status=='Accepted')

                        <form action="{{ route('admin.orders.status',$order->id) }}"
                              method="POST">

                            @csrf
                            @method('PATCH')

                            <input
                                type="hidden"
                                name="status"
                                value="Processing">

                            <button
                                class="btn btn-primary w-100">

                                🍳 Cooking

                            </button>

                        </form>

                    @elseif($order->status=='Processing')

                        <form action="{{ route('admin.orders.status',$order->id) }}"
                              method="POST">

                            @csrf
                            @method('PATCH')

                            <input
                                type="hidden"
                                name="status"
                                value="Ready">

                            <button
                                class="btn btn-success w-100">

                                ✅ Done

                            </button>

                        </form>

                    @else

                        <button
                            class="btn btn-secondary w-100"
                            disabled>

                            {{ $order->status }}

                        </button>

                    @endif

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info">

                Belum ada pesanan yang harus diproses.

            </div>

        </div>

    @endforelse

    </div>

</div>

<audio id="newOrderSound">

    <source src="{{ asset('sound/notifikasi.mp3') }}" type="audio/mpeg">

</audio>

<audio id="newOrderSound">
    <source src="{{ asset('sound/notifikasi.mp3') }}" type="audio/mpeg">
</audio>

<script>

setInterval(function(){

    fetch("{{ route('admin.kitchen.check') }}")
    .then(res => res.json())
    .then(data => {

        if(data.new){

            document.getElementById('newOrderSound').play();

            alert("🔔 Pesanan Baru : " + data.order);

            location.reload();

        }

    });

},5000);

</script>

<script>

let lastOrderCount = {{ $orders->count() }};

setInterval(function(){

    fetch("{{ route('admin.kitchen.index') }}")

    .then(response => response.text())

    .then(html => {

        let parser = new DOMParser();

        let doc = parser.parseFromString(html, "text/html");

        let currentOrderCount = doc.querySelectorAll(".card.shadow").length;

        if(currentOrderCount > lastOrderCount){

            document.getElementById("newOrderSound").play();

            setTimeout(function(){

                location.reload();

            },1000);

        }else{

            location.reload();

        }

        lastOrderCount = currentOrderCount;

    });

},10000);

</script>

@endsection
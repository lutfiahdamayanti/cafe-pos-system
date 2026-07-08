@extends('layouts.app')

@section('title','Tracking Pesanan')

@section('content')

@php

$steps = [
    'Pending',
    'Accepted',
    'Processing',
    'Ready',
    'Completed'
];

$currentStep = array_search($order->status,$steps);

if($currentStep === false){
    $currentStep = 0;
}

@endphp

<section class="py-5">
     <div class="container">
          <div class="text-center mb-5">

<h2 class="fw-bold">
Tracking Pesanan
</h2>

<p class="text-muted">
Pantau status pesananmu secara real-time.
</p>

</div>

<div class="card shadow border-0 rounded-4 p-4 mx-auto" style="max-width:800px;">

<div class="mb-4">

<h5 class="fw-bold">

Nomor Pesanan

<span class="text-success">

#{{ $order->order_number }}

</span>

</h5>

<p class="mb-1">

<strong>Nama Pelanggan :</strong>

{{ $order->customer_name }}

</p>

<p class="mb-1">

<strong>No HP :</strong>

{{ $order->phone }}

</p>

<p class="mb-1">

<strong>Metode Pembayaran :</strong>

{{ $order->payment }}

</p>

<p class="mb-0">

<strong>Total :</strong>

<span class="text-success fw-bold">

Rp {{ number_format($order->total,0,',','.') }}

</span>

</p>

</div>

<hr>

<div class="tracking mt-4">

@foreach($steps as $index => $step)

<div class="tracking-step {{ $index <= $currentStep ? 'active' : '' }}">

<div class="circle">

@if($index < $currentStep)

✓

@else

{{ $index+1 }}

@endif

</div>

<div>

<h6 class="mb-1">

{{ $step }}

</h6>

<small class="text-muted">

@if($step=='Pending')

Pesanan berhasil dibuat.

@elseif($step=='Accepted')

Pesanan diterima oleh cafe.

@elseif($step=='Processing')

Pesanan sedang dibuat.

@elseif($step=='Ready')

Pesanan siap diambil.

@elseif($step=='Completed')

Pesanan selesai.

@endif

</small>

</div>

</div>

@if(!$loop->last)

<div class="line {{ $index < $currentStep ? 'active' : '' }}"></div>

@endif

@endforeach

</div>

<hr class="my-5">

<h5 class="fw-bold mb-4">

Detail Pesanan

</h5>

@foreach($order->details as $detail)

<div class="d-flex justify-content-between align-items-center mb-3">

<div>

<strong>

{{ $detail->menu->name }}

</strong>

<br>

<small class="text-muted">

Qty : {{ $detail->qty }}

@if($detail->size==5000)

• Large

@else

• Regular

@endif

</small>

</div>

<div class="fw-bold">

Rp {{ number_format($detail->total,0,',','.') }}

</div>

</div>

@endforeach

<hr>

<div class="d-flex justify-content-between fw-bold fs-5">

<span>Total Pembayaran</span>

<span class="text-success">

Rp {{ number_format($order->total,0,',','.') }}

</span>

</div>

<div class="text-center mt-5">

<a href="{{ route('menu') }}" class="btn btn-success rounded-pill px-5">

Pesan Lagi

</a>

</div>

</div>

</div>

</section>

@endsection
@extends('layouts.app')
@section('title','Tracking Pesanan')
@section('content')

@php
$steps=['Pending','Accepted','Processing','Ready','Completed'];
$currentStep=array_search($order->status,$steps);
if($currentStep===false){$currentStep=0;}
@endphp

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Tracking Pesanan</h2>
            <p class="text-muted">Pantau status pesanan Anda secara real-time.</p>
        </div>

        <div class="card shadow border-0 rounded-4 p-4 mx-auto" style="max-width:800px;">
            {{-- Informasi Pesanan --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <small class="text-muted"><i class="bi bi-receipt me-1"></i> Nomor Pesanan</small>
                        <h6 class="fw-bold text-success mt-1">#{{ $order->order_number }}</h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <small class="text-muted"><i class="bi bi-ticket-perforated me-1"></i> Nomor Antrean</small>
                        <h6 class="fw-bold mt-1">{{ str_pad($order->queue_number,3,'0',STR_PAD_LEFT) }}</h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <small class="text-muted"><i class="bi bi-clock me-1"></i> Estimasi Waktu</small>
                        <h6 class="fw-bold mt-1">± {{ $order->estimated_time }} menit</h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <small class="text-muted"><i class="bi bi-clipboard-check me-1"></i> Status</small>
                        <h6 class="mt-1"><span class="badge bg-success" id="statusBadge">{{ statusIndonesia($order->status) }}</span></h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <small class="text-muted"><i class="bi bi-person me-1"></i> Pelanggan</small>
                        <h6 class="fw-bold mt-1">{{ $order->customer_name }}</h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <small class="text-muted"><i class="bi bi-telephone me-1"></i> Nomor HP</small>
                        <h6 class="fw-bold mt-1">{{ $order->phone }}</h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <small class="text-muted"><i class="bi bi-credit-card me-1"></i> Pembayaran</small>
                        <h6 class="fw-bold mt-1">{{ $order->payment }}</h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <small class="text-muted"><i class="bi bi-cash-coin me-1"></i> Total</small>
                        <h6 class="fw-bold text-success mt-1">Rp {{ number_format($order->total,0,',','.') }}</h6>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Tracking --}}
            <div class="tracking mt-4" id="trackingTimeline">
                @foreach($steps as $index => $step)
                    <div class="tracking-step {{ $index <= $currentStep ? 'active' : '' }}">
                        <div class="circle">
                            @if($index <= $currentStep)
                                ✓
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>

                        <div>
                            <h6 class="mb-1">{{ statusIndonesia($step) }}</h6>
                            <small class="text-muted">
                                @switch($step)
                                    @case('Pending')
                                        Pesanan berhasil dibuat dan sedang menunggu konfirmasi.
                                        @break
                                    @case('Accepted')
                                        Pesanan telah diterima oleh pihak kafe.
                                        @break
                                    @case('Processing')
                                        Pesanan sedang diproses oleh dapur.
                                        @break
                                    @case('Ready')
                                        Pesanan telah selesai dibuat dan siap disajikan atau diambil.
                                        @break
                                    @case('Completed')
                                        Pesanan telah selesai. Terima kasih telah berkunjung.
                                        @break
                                @endswitch
                            </small>
                        </div>
                    </div>

                    @if(!$loop->last)
                        <div class="line {{ $index < $currentStep ? 'active' : '' }}"></div>
                    @endif
                @endforeach
            </div>

            <hr class="my-5">

            {{-- Detail Pesanan --}}
            <h5 class="fw-bold mb-4">Detail Pesanan</h5>

            @foreach($order->details as $detail)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>{{ $detail->menu->name }}</strong><br>
                        <small class="text-muted">
                            Jumlah : {{ $detail->qty }}
                            @if($detail->size) • {{ $detail->size }} @endif
                        </small>

                        @if($detail->options)
                            @foreach($detail->options as $key => $value)
                                <br>
                                <small class="text-muted">{{ $key }} : {{ $value }}</small>
                            @endforeach
                        @endif

                        @if($detail->note)
                            <br>
                            <small class="text-danger">Catatan : {{ $detail->note }}</small>
                        @endif
                    </div>

                    <div class="fw-bold">Rp {{ number_format($detail->total,0,',','.') }}</div>
                </div>
            @endforeach

            <hr>

            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Total Pembayaran</span>
                <span class="text-success">Rp {{ number_format($order->total,0,',','.') }}</span>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('menu') }}" class="btn btn-success rounded-pill px-5">Pesan Lagi</a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
let currentStatus="{{ $order->status }}";

const statusLabels={
    Pending:"Menunggu",
    Accepted:"Diterima",
    Processing:"Sedang Diproses",
    Ready:"Siap Disajikan",
    Completed:"Selesai"
};

setInterval(function(){
    fetch("{{ route('tracking.status',$order->id) }}")
    .then(response=>response.json())
    .then(data=>{
        if(data.status!==currentStatus){
            // Simpan status baru sebelum reload
            localStorage.setItem('tracking_status_toast',data.status);
            currentStatus=data.status;
            location.reload();
        }
    })
    .catch(error=>{
        console.error('Gagal mengecek status pesanan:',error);
    });
},3000);

// Tampilkan toast setelah halaman selesai reload
const newStatus=localStorage.getItem('tracking_status_toast');

if(newStatus){
    localStorage.removeItem('tracking_status_toast');

    Swal.fire({
        toast:true,
        position:'top-end',
        icon:'success',
        title:'Status Pesanan Diperbarui',
        text:'Pesanan kamu sekarang: '+(statusLabels[newStatus] ?? newStatus),
        showConfirmButton:false,
        timer:3500,
        timerProgressBar:true,
        didOpen:(toast)=>{
            toast.style.marginTop='80px';
        }
    });
}
</script>
@endpush
@endsection
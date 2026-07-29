@extends('layouts.admin')
@section('title','Laporan & Analisis')
@section('content')

<div class="container-fluid">
    
    {{-- FILTER PERIODE --}}
    <div class="mb-4">

        <a href="{{ route('admin.reports.index',['period'=>'daily']) }}"
            class="btn {{ $period == 'daily' ? 'btn-success' : 'btn-outline-success' }}">
            Hari Ini
        </a>

        <a href="{{ route('admin.reports.index',['period'=>'weekly']) }}"
            class="btn {{ $period == 'weekly' ? 'btn-success' : 'btn-outline-success' }}">
            Minggu Ini
        </a>

        <a href="{{ route('admin.reports.index',['period'=>'monthly']) }}"
            class="btn {{ $period == 'monthly' ? 'btn-success' : 'btn-outline-success' }}">
            Bulan Ini
        </a>

        <a href="{{ route('admin.reports.index',['period'=>'yearly']) }}"
            class="btn {{ $period == 'yearly' ? 'btn-success' : 'btn-outline-success' }}">
            Tahun Ini
        </a>
    </div>

    {{-- RINGKASAN --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Total Pesanan</h6>
                <h3>{{ $orders->count() }}</h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Pendapatan Kotor</h6>
                <h3 class="text-success">
                    Rp {{ number_format($grossRevenue,0,',','.') }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Pendapatan Bersih</h6>
                <h3>
                    Rp {{ number_format($netRevenue,0,',','.') }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Total Pajak</h6>
                <h3>
                    Rp {{ number_format($tax,0,',','.') }}
                </h3>
            </div>
        </div>
    </div>

    {{-- STATUS --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Biaya Layanan</h6>
                <h3>
                    Rp {{ number_format($service,0,',','.') }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Selesai</h6>
                <h3 class="text-success">
                    {{ $completed }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Dibatalkan</h6>
                <h3 class="text-danger">
                    {{ $cancelled }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Pengembalian Dana</h6>
                <h3 class="text-warning">
                    {{ $refund }}
                </h3>
            </div>
        </div>
    </div>

    {{-- TABEL ORDER --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h4 class="fw-bold mb-4">
                Daftar Pesanan
            </h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                {{ $order->order_number }}
                            </td>

                            <td>
                                {{ $order->customer_name }}
                            </td>

                            <td>
                                {{ $order->payment }}
                            </td>

                            <td>
                                @if($order->status == 'Pending')
                                    <span class="badge bg-warning">
                                        Menunggu
                                    </span>

                                @elseif($order->status == 'Accepted')
                                    <span class="badge bg-primary">
                                        Diterima
                                    </span>

                                @elseif($order->status == 'Processing')
                                    <span class="badge bg-info">
                                        Diproses
                                    </span>

                                @elseif($order->status == 'Ready')
                                    <span class="badge bg-secondary">
                                        Siap Disajikan
                                    </span>

                                @elseif($order->status == 'Completed')
                                    <span class="badge bg-success">
                                        Selesai
                                    </span>

                                @else
                                    <span class="badge bg-danger">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>

                            <td>
                                Rp {{ number_format($order->total,0,',','.') }}
                            </td>

                            <td>
                                {{ $order->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada data.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= GRAFIK PENDAPATAN ================= --}}
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h4 class="fw-bold mb-4">
                Grafik Pendapatan
            </h4>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h4 class="fw-bold mb-4">
                Statistik Metode Pembayaran
            </h4>

            <div class="d-flex justify-content-center">
                <div style="width:350px; height:350px;">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h4 class="fw-bold mb-4">
                10 Menu Best Seller
            </h4>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Total Terjual</th>
                    </tr>
                </thead>
                <tbody>

                @foreach($bestSeller as $item)
                    <tr>
                        <td>{{ $item->menu->name ?? '-' }}</td>
                        <td>{{ $item->total_qty }}</td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h4 class="fw-bold mb-4">
                10 Menu Kurang Laris
            </h4>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Total Terjual</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($worstSeller as $item)
                        <tr>
                            <td>{{ $item->menu->name ?? '-' }}</td>
                            <td>{{ $item->total_qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h4 class="fw-bold mb-4">
                Jam Tersibuk
            </h4>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Jam</th>
                        <th>Jumlah Pesanan</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($peakHours as $hour)
                        <tr>
                            <td>{{ sprintf('%02d:00',$hour->hour) }}</td>
                            <td>{{ $hour->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h4 class="fw-bold mb-4">
                Hari Tersibuk
            </h4>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jumlah Pesanan</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($peakDays as $day)
                        <tr>
                            <td>{{ $day->day }}</td>
                            <td>{{ $day->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h4 class="fw-bold mb-4">
                Pelanggan Lama vs Pelanggan Baru
            </h4>

            <div class="d-flex justify-content-center">
                <div style="width:350px; height:350px;">
                    <canvas id="customerChart"></canvas>
                    <div class="mt-3 text-center">
                        <p>Pelanggan Lama: <strong>{{ $returning }}</strong></p>
                        <p>Pelanggan Baru: <strong>{{ $newCustomer }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const canvas = document.getElementById('revenueChart');
console.log(canvas);
const ctx = canvas.getContext('2d');
new Chart(ctx,{
    type:'bar',
    data:{
        labels:@json($labels),
        datasets:[{
            label:'Revenue',
            data:@json($data),
            borderWidth:1
        }]
    },

    options:{
        responsive:true,
        scales:{
            y:{
                beginAtZero:true
            }
        }
    }
});
</script>
<script>
const paymentCtx = document
    .getElementById('paymentChart')
    .getContext('2d');
new Chart(paymentCtx, {
    type: 'pie',
    data: {
        labels: @json($paymentLabels),
        datasets: [{
            data: @json($paymentData)
        }]
    },

    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
<script>
const customerCtx = document
    .getElementById('customerChart')
    .getContext('2d');
new Chart(customerCtx, {
    type: 'doughnut',
    data: {
        labels: ['Returning', 'New'],
        datasets: [{
            data: [
                {{ $returning }},
                {{ $newCustomer }}
            ]
        }]
    },

    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endpush
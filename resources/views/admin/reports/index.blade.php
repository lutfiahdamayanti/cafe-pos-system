@extends('layouts.admin')
@section('title','Laporan & Analisis')
@section('content')

<div class="container-fluid report-page">

    <div class="report-period mb-4">
        <a href="{{ route('admin.reports.index',['period'=>'daily']) }}" class="btn {{ $period == 'daily' ? 'btn-success' : 'btn-outline-success' }}">Hari Ini</a>
        <a href="{{ route('admin.reports.index',['period'=>'weekly']) }}" class="btn {{ $period == 'weekly' ? 'btn-success' : 'btn-outline-success' }}">Minggu Ini</a>
        <a href="{{ route('admin.reports.index',['period'=>'monthly']) }}" class="btn {{ $period == 'monthly' ? 'btn-success' : 'btn-outline-success' }}">Bulan Ini</a>
        <a href="{{ route('admin.reports.index',['period'=>'yearly']) }}" class="btn {{ $period == 'yearly' ? 'btn-success' : 'btn-outline-success' }}">Tahun Ini</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6"><div class="report-card report-blue"><div class="report-card-icon"><i class="bi bi-receipt"></i></div>
            <h6>@if($period == 'daily') Jumlah Transaksi Hari Ini @elseif($period == 'weekly') Jumlah Transaksi Minggu Ini @elseif($period == 'monthly') Jumlah Transaksi Bulan Ini @elseif($period == 'yearly') Jumlah Transaksi Tahun Ini @endif</h6>
            <h3>{{ $orders->count() }}</h3>
        </div></div>

        <div class="col-lg-3 col-md-6"><div class="report-card report-green"><div class="report-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <h6>@if($period == 'daily') Penjualan Hari Ini @elseif($period == 'weekly') Penjualan Minggu Ini @elseif($period == 'monthly') Penjualan Bulan Ini @elseif($period == 'yearly') Penjualan Tahun Ini @endif</h6>
            <h3>Rp {{ number_format($grossRevenue,0,',','.') }}</h3>
        </div></div>

        <div class="col-lg-3 col-md-6"><div class="report-card report-purple"><div class="report-card-icon"><i class="bi bi-calculator"></i></div>
            <h6>Rata-rata Order (AOV)</h6><h3>Rp {{ number_format($aov,0,',','.') }}</h3>
        </div></div>

        <div class="col-lg-3 col-md-6"><div class="report-card report-orange"><div class="report-card-icon"><i class="bi bi-wallet2"></i></div>
            <h6>Pendapatan Bersih</h6><h3>Rp {{ number_format($netRevenue,0,',','.') }}</h3>
        </div></div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6"><div class="report-card report-red"><div class="report-card-icon"><i class="bi bi-receipt-cutoff"></i></div>
            <h6>Total Pajak</h6><h3>Rp {{ number_format($tax,0,',','.') }}</h3>
        </div></div>

        <div class="col-lg-3 col-md-6"><div class="report-card report-cyan"><div class="report-card-icon"><i class="bi bi-gear"></i></div>
            <h6>Biaya Layanan</h6><h3>Rp {{ number_format($service,0,',','.') }}</h3>
        </div></div>

        <div class="col-lg-3 col-md-6"><div class="report-card report-success"><div class="report-card-icon"><i class="bi bi-check-circle"></i></div>
            <h6>Selesai</h6><h3>{{ $completed }}</h3>
        </div></div>

        <div class="col-lg-3 col-md-6"><div class="report-card report-danger"><div class="report-card-icon"><i class="bi bi-x-circle"></i></div>
            <h6>Dibatalkan</h6><h3>{{ $cancelled }}</h3>
        </div></div>
    </div>

    <div class="report-section">
        <div class="report-section-header"><div class="report-section-icon blue"><i class="bi bi-list-ul"></i></div><h4>Daftar Pesanan</h4></div>
        <div class="report-section-body"><div class="table-responsive"><table class="table report-table">
            <thead><tr><th>No Pesanan</th><th>Pelanggan</th><th>Pembayaran</th><th>Status</th><th>Total</th><th>Tanggal</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td><td>{{ $order->customer_name }}</td><td>{{ $order->payment }}</td>
                        <td>
                            @if($order->status == 'Pending') <span class="badge bg-warning">Menunggu</span>
                            @elseif($order->status == 'Accepted') <span class="badge bg-primary">Diterima</span>
                            @elseif($order->status == 'Processing') <span class="badge bg-info">Diproses</span>
                            @elseif($order->status == 'Ready') <span class="badge bg-secondary">Siap Disajikan</span>
                            @elseif($order->status == 'Completed') <span class="badge bg-success">Selesai</span>
                            @else <span class="badge bg-danger">Dibatalkan</span>
                            @endif
                        </td>
                        <td><strong>Rp {{ number_format($order->total,0,',','.') }}</strong></td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table></div></div>
    </div>

    <div class="report-section">
        <div class="report-section-header"><div class="report-section-icon green"><i class="bi bi-bar-chart-fill"></i></div><h4>Grafik Pendapatan</h4></div>
        <div class="report-section-body"><div class="report-chart"><canvas id="revenueChart"></canvas></div></div>
    </div>

    <div class="report-section">
        <div class="report-section-header"><div class="report-section-icon purple"><i class="bi bi-credit-card-fill"></i></div><h4>Statistik Metode Pembayaran</h4></div>
        <div class="report-section-body"><div class="report-pie-chart"><canvas id="paymentChart"></canvas></div></div>
    </div>

    <div class="report-section">
        <div class="report-section-header"><div class="report-section-icon orange"><i class="bi bi-trophy-fill"></i></div><h4>10 Menu Best Seller</h4></div>
        <div class="report-section-body"><div class="table-responsive"><table class="table report-table">
            <thead><tr><th>Menu</th><th>Total Terjual</th></tr></thead>
            <tbody>
                @foreach($bestSeller as $item)
                    <tr><td><strong>{{ $item->menu->name ?? '-' }}</strong></td><td><span class="badge bg-success">{{ $item->total_qty }} terjual</span></td></tr>
                @endforeach
            </tbody>
        </table></div></div>
    </div>

    <div class="report-section">
        <div class="report-section-header"><div class="report-section-icon red"><i class="bi bi-graph-down-arrow"></i></div><h4>10 Menu Kurang Laris</h4></div>
        <div class="report-section-body"><div class="table-responsive"><table class="table report-table">
            <thead><tr><th>Menu</th><th>Total Terjual</th></tr></thead>
            <tbody>
                @foreach($worstSeller as $item)
                    <tr><td><strong>{{ $item->name }}</strong></td><td><span class="badge bg-danger">{{ $item->total_qty }} terjual</span></td></tr>
                @endforeach
            </tbody>
        </table></div></div>
    </div>

    <div class="report-section">
        <div class="report-section-header"><div class="report-section-icon cyan"><i class="bi bi-clock-fill"></i></div><h4>Jam Teramai</h4></div>
        <div class="report-section-body"><div class="table-responsive"><table class="table report-table">
            <thead><tr><th>Jam</th><th>Jumlah Pesanan</th></tr></thead>
            <tbody>
                @foreach($peakHours as $hour)
                    <tr><td><strong>{{ sprintf('%02d:00',$hour->hour) }}</strong></td><td><span class="badge bg-info">{{ $hour->total }} pesanan</span></td></tr>
                @endforeach
            </tbody>
        </table></div></div>
    </div>

    <div class="report-section">
        <div class="report-section-header">
            <div class="report-section-icon blue"><i class="bi bi-calendar-week-fill"></i></div>
            <h4>@if($period == 'weekly') Hari Tersibuk @elseif($period == 'monthly') Minggu Tersibuk @elseif($period == 'yearly') Bulan Tersibuk @endif</h4>
        </div>
        <div class="report-section-body"><div class="table-responsive"><table class="table report-table">
            <thead><tr>
                <th>@if($period == 'weekly') Hari @elseif($period == 'monthly') Minggu @elseif($period == 'yearly') Bulan @endif</th>
                <th>Jumlah Pesanan</th>
            </tr></thead>
            <tbody>
                @foreach($peakDays as $day)
                    <tr>
                        <td><strong>
                            @if($period == 'weekly') {{ $day->day }}
                            @elseif($period == 'monthly') Minggu ke-{{ $day->week_number }}
                            @elseif($period == 'yearly') {{ date('F',mktime(0,0,0,$day->month_number,1)) }}
                            @endif
                        </strong></td>
                        <td><span class="badge bg-primary">{{ $day->total }} pesanan</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table></div></div>
    </div>

    <div class="report-section">
        <div class="report-section-header"><div class="report-section-icon pink"><i class="bi bi-people-fill"></i></div><h4>Pelanggan Lama vs Pelanggan Baru</h4></div>
        <div class="report-section-body">
            <div class="report-customer-chart"><canvas id="customerChart"></canvas></div>
            <div class="customer-summary">
                <div class="customer-item customer-old"><span>Pelanggan Lama</span><strong>{{ $returning }}</strong></div>
                <div class="customer-item customer-new"><span>Pelanggan Baru</span><strong>{{ $newCustomer }}</strong></div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')

<script>
const revenueCtx=document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx,{type:'bar',data:{labels:@json($labels),datasets:[{label:'Revenue',data:@json($data),borderWidth:1,borderRadius:8}]},options:{responsive:true,maintainAspectRatio:false,scales:{y:{beginAtZero:true}}}});
</script>

<script>
const paymentCtx=document.getElementById('paymentChart').getContext('2d');
new Chart(paymentCtx,{type:'pie',data:{labels:@json($paymentLabels),datasets:[{data:@json($paymentData),borderWidth:2}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}}}});
</script>

<script>
const customerCtx=document.getElementById('customerChart').getContext('2d');
new Chart(customerCtx,{type:'doughnut',data:{labels:['Returning','New'],datasets:[{data:[{{ $returning }},{{ $newCustomer }}],borderWidth:3}]},options:{responsive:true,maintainAspectRatio:false,cutout:'65%',plugins:{legend:{position:'bottom'}}}});
</script>

@endpush
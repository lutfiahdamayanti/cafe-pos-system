@extends('layouts.admin')
@section('title','Reporting & Analytics')
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
                <h6>Total Order</h6>
                <h3>{{ $orders->count() }}</h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Gross Revenue</h6>
                <h3 class="text-success">
                    Rp {{ number_format($grossRevenue,0,',','.') }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Net Revenue</h6>
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
                <h6>Service Charge</h6>
                <h3>
                    Rp {{ number_format($service,0,',','.') }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Completed</h6>
                <h3 class="text-success">
                    {{ $completed }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Cancelled</h6>
                <h3 class="text-danger">
                    {{ $cancelled }}
                </h3>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <h6>Refund</h6>
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
                Daftar Order
            </h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No Order</th>
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
                                        Pending
                                    </span>

                                @elseif($order->status == 'Accepted')
                                    <span class="badge bg-primary">
                                        Accepted
                                    </span>

                                @elseif($order->status == 'Processing')
                                    <span class="badge bg-info">
                                        Processing
                                    </span>

                                @elseif($order->status == 'Ready')
                                    <span class="badge bg-secondary">
                                        Ready
                                    </span>

                                @elseif($order->status == 'Completed')
                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @else
                                    <span class="badge bg-danger">
                                        Cancelled
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
                Top 10 Best Seller
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
                Top 10 Worst Seller
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
                Peak Hours
            </h4>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Jam</th>
                        <th>Jumlah Order</th>
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
                Peak Days
            </h4>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jumlah Order</th>
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
                Returning Customer vs New Customer
            </h4>

            <div class="d-flex justify-content-center">
                <div style="width:350px; height:350px;">
                    <canvas id="customerChart"></canvas>
                    <div class="mt-3 text-center">
                        <p>Returning Customer: <strong>{{ $returning }}</strong></p>
                        <p>New Customer: <strong>{{ $newCustomer }}</strong></p>
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
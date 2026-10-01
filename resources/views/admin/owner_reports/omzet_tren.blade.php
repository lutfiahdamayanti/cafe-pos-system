@extends('layouts.admin')
@section('title', 'Laporan Owner: Omzet & Tren Penjualan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & PERIODE FILTER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                <i class="bi bi-graph-up-arrow"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Omzet & Tren Penjualan</h4>
                <small class="text-muted">Analisis pendapatan kotor, omzet bersih, volume transaksi, serta tren pertumbuhan penjualan kafe.</small>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.owner-reports.omzet-tren') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.owner-reports.omzet-tren', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-success' : 'btn-outline-secondary' }}">Hari Ini</a>
                    <a href="{{ route('admin.owner-reports.omzet-tren', ['period' => 'yesterday']) }}" class="btn btn-sm {{ $period === 'yesterday' ? 'btn-success' : 'btn-outline-secondary' }}">Kemarin</a>
                    <a href="{{ route('admin.owner-reports.omzet-tren', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-success' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.owner-reports.omzet-tren', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-success' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.owner-reports.omzet-tren', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-success' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.owner-reports.omzet-tren', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-success' : 'btn-outline-secondary' }}">Tahun Ini</a>
                </div>

                <div class="col-md-5 d-flex gap-2 align-items-center">
                    <input type="hidden" name="period" value="custom">
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date', $start->format('Y-m-d')) }}">
                    <span>-</span>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date', $end->format('Y-m-d')) }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- PERIODE ACTIVE BADGE --}}
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-light text-dark border px-3 py-2 fs-6">
            <i class="bi bi-calendar-check me-1 text-success"></i> Periode Laporan: <strong>{{ $periodLabel }}</strong>
        </span>
    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Omzet Bersih (Net Sales)</span>
                    <i class="bi bi-wallet2 fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($netSales, 0, ',', '.') }}</h3>
                <small class="text-muted">Di luar pajak & service fee</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Omzet Kotor (Gross Sales)</span>
                    <i class="bi bi-cash-stack fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($grossSales, 0, ',', '.') }}</h3>
                <small class="text-muted">Total penerimaan kasir</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Transaksi Selesai</span>
                    <i class="bi bi-receipt fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold">{{ number_format($totalTransactions) }} Order</h3>
                <small class="text-muted">{{ $cancelledCount }} pesanan dibatalkan</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Order (AOV)</span>
                    <i class="bi bi-calculator fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h3>
                <small class="text-muted">Nilai belanja rata-rata per nota</small>
            </div>
        </div>
    </div>

    {{-- GRAFIK TREN PENJUALAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-bar-chart-line-fill text-success me-2"></i>Grafik Tren Pendapatan Penjualan
            </h6>
            <small class="text-muted">Data berdasarkan {{ $periodLabel }}</small>
        </div>
        <div class="card-body">
            <div style="height: 320px;">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- BREAKDOWN METODE PEMBAYARAN & TIPE KUNJUNGAN --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-credit-card-2-front text-primary me-2"></i>Distribusi Metode Pembayaran
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Metode</th>
                                    <th>Jumlah Order</th>
                                    <th class="text-end pe-3">Total Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentBreakdown as $pb)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="badge bg-light text-dark border fs-6">
                                                {{ $pb->payment }}
                                            </span>
                                        </td>
                                        <td>{{ $pb->total_orders }} transaksi</td>
                                        <td class="text-end pe-3 fw-bold text-success">
                                            Rp {{ number_format($pb->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada data transaksi pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-shop text-warning me-2"></i>Distribusi Tipe Kunjungan
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Tipe Kunjungan</th>
                                    <th>Jumlah Order</th>
                                    <th class="text-end pe-3">Total Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($visitBreakdown as $vb)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="badge {{ $vb->visit_type === 'Dine In' ? 'bg-primary' : 'bg-warning text-dark' }} fs-6">
                                                {{ $vb->visit_type }}
                                            </span>
                                        </td>
                                        <td>{{ $vb->total_orders }} transaksi</td>
                                        <td class="text-end pe-3 fw-bold text-success">
                                            Rp {{ number_format($vb->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada data kunjungan pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL TRANSAKSI LENGKAP PADA PERIODE INI --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-list-check text-primary me-2"></i>Rincian Transaksi Selesai Periode Terpilih
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No Order</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Tipe</th>
                            <th>Metode Bayar</th>
                            <th>Subtotal</th>
                            <th>Pajak & Service</th>
                            <th class="text-end pe-3">Total Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold font-monospace text-dark">{{ $order->order_number }}</span>
                                </td>
                                <td><small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                                    <small class="text-muted">{{ $order->phone }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $order->visit_type }}
                                        @if($order->table_number) (Meja {{ $order->table_number }}) @endif
                                    </span>
                                </td>
                                <td><span class="badge bg-secondary">{{ $order->payment }}</span></td>
                                <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                <td><small class="text-muted">Rp {{ number_format($order->tax + $order->service, 0, ',', '.') }}</small></td>
                                <td class="text-end pe-3 fw-bold text-success fs-6">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data transaksi pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($recentOrders->hasPages())
                <div class="p-3 border-top">
                    {{ $recentOrders->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('salesTrendChart').getContext('2d');
    const labels = @json($chartLabels);
    const dataSales = @json($chartSalesData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: dataSales,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#198754',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value/1000000) + ' Jt';
                            if (value >= 1000) return 'Rp ' + (value/1000) + ' Rb';
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@endsection

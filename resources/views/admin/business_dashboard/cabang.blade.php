@extends('layouts.admin')
@section('title', 'Dashboard Bisnis: Analitik per Cabang')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3 fs-5">
                <i class="bi bi-shop text-primary"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Dashboard Bisnis: Analitik per Cabang</h4>
                <small class="text-muted">Analisis performa gerai kafe: omzet bersih, volume transaksi, jam sibuk (peak hour), dan menu unggulan tiap cabang.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan Cabang
        </button>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.business-dashboard.cabang') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.business-dashboard.cabang', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.business-dashboard.cabang', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.business-dashboard.cabang', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-primary' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.business-dashboard.cabang', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-primary' : 'btn-outline-secondary' }}">Tahun Ini</a>
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

    {{-- SUMMARY KPI METRICS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Omzet Seluruh Cabang</span>
                    <i class="bi bi-cash-stack fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($totalSystemGross, 0, ',', '.') }}</h3>
                <small class="text-muted">{{ $periodLabel }}</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Transaksi</span>
                    <i class="bi bi-receipt fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ number_format($totalSystemOrders) }} Order</h3>
                <small class="text-muted">Pesanan selesai</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Order (AOV)</span>
                    <i class="bi bi-calculator fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">Rp {{ number_format($systemAov, 0, ',', '.') }}</h3>
                <small class="text-muted">Per transaksi pelanggan</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Jumlah Gerai Teranalisis</span>
                    <i class="bi bi-buildings fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ count($branchStats) }} Gerai</h3>
                <small class="text-muted">Seluruh outlet aktif</small>
            </div>
        </div>
    </div>

    {{-- GRAFIK KOMPARASI CABANG --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill me-2 text-primary"></i> Perbandingan Omzet & Volume Pesanan Antar Cabang</h6>
            <span class="badge bg-light text-dark">Data Visual</span>
        </div>
        <div class="card-body">
            <div style="height: 300px;">
                <canvas id="branchAnalyticsChart"></canvas>
            </div>
        </div>
    </div>

    {{-- TABEL ANALITIK DETAIL PER CABANG --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-table me-2 text-primary"></i> Rincian Performa & Operasional Cabang</h6>
            <span class="badge bg-light text-dark">Diurutkan Berdasarkan Omzet</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">Rank</th>
                            <th>Nama Cabang & Kode</th>
                            <th>Kota & Manager</th>
                            <th>Total Pesanan</th>
                            <th>Omzet Kotor</th>
                            <th>Omzet Bersih</th>
                            <th>AOV</th>
                            <th>Jam Sibuk (Peak Hour)</th>
                            <th class="pe-3">Menu Terlaris</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branchStats as $idx => $b)
                            <tr>
                                <td class="ps-3">
                                    @if($idx === 0 && $b->gross_sales > 0)
                                        <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">🥇 1</span>
                                    @elseif($idx === 1 && $b->gross_sales > 0)
                                        <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">🥈 2</span>
                                    @elseif($idx === 2 && $b->gross_sales > 0)
                                        <span class="badge bg-danger bg-opacity-75 text-white rounded-circle p-2 fs-6">🥉 3</span>
                                    @else
                                        <span class="fw-bold text-muted ps-2">#{{ $idx + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $b->outlet->name }}</span>
                                    <span class="badge bg-secondary font-monospace">{{ $b->outlet->code }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $b->outlet->city ?? '-' }}</span>
                                    <small class="text-muted"><i class="bi bi-person me-1"></i> {{ $b->outlet->manager_name ?? 'Staff' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fs-6">{{ number_format($b->order_count) }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">Rp {{ number_format($b->gross_sales, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">Rp {{ number_format($b->net_revenue, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border">
                                        Rp {{ number_format($b->aov, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-10 text-dark border">
                                        <i class="bi bi-clock me-1"></i> {{ $b->peak_hour }}
                                    </span>
                                </td>
                                <td class="pe-3">
                                    <span class="small fw-semibold text-primary">{{ $b->top_menu }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada data analitik cabang pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('branchAnalyticsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Omzet Kotor (Rp)',
                            data: {!! json_encode($chartGross) !!},
                            backgroundColor: '#0d6efd',
                            borderRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Volume Order',
                            data: {!! json_encode($chartOrders) !!},
                            type: 'line',
                            borderColor: '#ffc107',
                            backgroundColor: 'rgba(255, 193, 7, 0.2)',
                            pointRadius: 5,
                            borderWidth: 2.5,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            ticks: {
                                callback: function(val) {
                                    return 'Rp ' + (val / 1000).toLocaleString('id-ID') + 'k';
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

@endsection

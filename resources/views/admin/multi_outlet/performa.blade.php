@extends('layouts.admin')
@section('title', 'Multi-Outlet: Performa & Laporan per Cabang')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                <i class="bi bi-bar-chart-line-fill text-success"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Performa & Laporan per Cabang</h4>
                <small class="text-muted">Komparasi pendapatan, kontribusi omzet, volume pesanan, dan menu terlaris di setiap gerai.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan Performa
        </button>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.multi-outlet.performa') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.multi-outlet.performa', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.multi-outlet.performa', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.multi-outlet.performa', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-primary' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.multi-outlet.performa', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-primary' : 'btn-outline-secondary' }}">Tahun Ini</a>
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

    {{-- AGGREGATE SYSTEM METRICS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Omzet Sistem</span>
                    <i class="bi bi-cash-stack fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($totalSystemGross, 0, ',', '.') }}</h3>
                <small class="text-muted">{{ $periodLabel }}</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Pendapatan Bersih</span>
                    <i class="bi bi-wallet2 fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">Rp {{ number_format($totalSystemNet, 0, ',', '.') }}</h3>
                <small class="text-muted">Di luar pajak & service</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Volume Pesanan</span>
                    <i class="bi bi-receipt fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalSystemOrders) }} Order</h3>
                <small class="text-muted">Total transaksi selesai</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Order (AOV)</span>
                    <i class="bi bi-calculator fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">
                    Rp {{ $totalSystemOrders > 0 ? number_format(round($totalSystemGross / $totalSystemOrders), 0, ',', '.') : '0' }}
                </h3>
                <small class="text-muted">Rata-rata belanja per struk</small>
            </div>
        </div>
    </div>

    {{-- GRAFIK KOMPARASI PERFORMA CABANG --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill me-2 text-primary"></i> Grafik Komparasi Omzet Antar Cabang</h6>
            <span class="badge bg-light text-dark">{{ count($performanceData) }} Gerai Teranalisis</span>
        </div>
        <div class="card-body">
            <div style="height: 300px;">
                <canvas id="branchPerformanceChart"></canvas>
            </div>
        </div>
    </div>

    {{-- TABEL PERBANDINGAN PERFORMA PER CABANG --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-trophy-fill text-warning me-2"></i> Peringkat & Rincian Performa Gerai</h6>
            <span class="badge bg-light text-dark">Diurutkan dari Omzet Tertinggi</span>
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
                            <th>Dine In / Takeaway</th>
                            <th>Omzet Kotor</th>
                            <th>Omzet Bersih</th>
                            <th>AOV</th>
                            <th>Pangsa Omzet</th>
                            <th class="pe-3">Menu Terlaris</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($performanceData as $index => $row)
                            @php
                                $sharePct = $totalSystemGross > 0 ? round(($row->gross_sales / $totalSystemGross) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    @if($index === 0 && $row->gross_sales > 0)
                                        <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">🥇 1</span>
                                    @elseif($index === 1 && $row->gross_sales > 0)
                                        <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">🥈 2</span>
                                    @elseif($index === 2 && $row->gross_sales > 0)
                                        <span class="badge bg-danger bg-opacity-75 text-white rounded-circle p-2 fs-6">🥉 3</span>
                                    @else
                                        <span class="fw-bold text-muted ps-2">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 bg-light p-2 me-2">
                                            <i class="bi bi-shop text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $row->outlet->name }}</span>
                                            <span class="badge bg-secondary font-monospace">{{ $row->outlet->code }}</span>
                                            @if($row->outlet->is_central_warehouse)
                                                <span class="badge bg-warning text-dark">Hub Pusat</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $row->outlet->city ?? '-' }}</span>
                                    <small class="text-muted"><i class="bi bi-person me-1"></i> {{ $row->outlet->manager_name ?? 'Staff' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fs-6">{{ number_format($row->order_count) }}</span>
                                </td>
                                <td>
                                    <small class="d-block text-muted">Dine-in: <strong class="text-dark">{{ $row->dine_in_count }}</strong></small>
                                    <small class="d-block text-muted">Takeaway: <strong class="text-dark">{{ $row->takeaway_count }}</strong></small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">Rp {{ number_format($row->gross_sales, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">Rp {{ number_format($row->net_revenue, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border">
                                        Rp {{ number_format($row->aov, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="fw-bold">{{ $sharePct }}%</span>
                                        <div class="progress flex-grow-1" style="width: 50px; height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $sharePct }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="pe-3">
                                    <span class="small fw-semibold text-dark">{{ $row->top_item }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada data transaksi outlet pada periode ini.
                                </td>
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
        const ctx = document.getElementById('branchPerformanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Omzet Kotor (Rp)',
                            data: {!! json_encode($chartSales) !!},
                            backgroundColor: '#0d6efd',
                            borderRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Volume Transaksi',
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

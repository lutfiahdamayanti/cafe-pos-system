@extends('layouts.admin')
@section('title', 'Dashboard Bisnis: Analitik Produk')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-box-seam text-warning"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Dashboard Bisnis: Analitik Produk</h4>
                <small class="text-muted">Analisis performa item menu: volume penjualan, kontribusi pendapatan, margin keuntungan, dan pangsa kategori.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan Produk
        </button>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.business-dashboard.produk') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.business-dashboard.produk', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.business-dashboard.produk', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.business-dashboard.produk', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-primary' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.business-dashboard.produk', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-primary' : 'btn-outline-secondary' }}">Tahun Ini</a>
                </div>

                <div class="col-md-3">
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">Semua Kategori Menu</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="hidden" name="period" value="custom">
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date', $start->format('Y-m-d')) }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Porsi Terjual</span>
                    <i class="bi bi-basket fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ number_format($totalUnitsSold) }} Porsi</h3>
                <small class="text-muted">{{ $periodLabel }}</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Penjualan Produk</span>
                    <i class="bi bi-cash-stack fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($totalProductRevenue, 0, ',', '.') }}</h3>
                <small class="text-muted">Akumulasi pendapatan</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Produk Terlaris (#1)</span>
                    <i class="bi bi-trophy fs-4 text-primary"></i>
                </div>
                <h4 class="mb-0 fw-bold text-primary text-truncate">{{ $topProduct ? $topProduct->menu_name : '-' }}</h4>
                <small class="text-muted">{{ $topProduct ? number_format($topProduct->total_qty) . ' porsi terjual' : '-' }}</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Kategori Menu</span>
                    <i class="bi bi-tags fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ $categoryBreakdown->count() }} Kategori</h3>
                <small class="text-muted">Varian kelompok produk</small>
            </div>
        </div>
    </div>

    {{-- GRAFIK KATEGORI PENJUALAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart-fill me-2 text-warning"></i> Distribusi Penjualan per Kategori Menu</h6>
            <span class="badge bg-light text-dark">Data Visual</span>
        </div>
        <div class="card-body">
            <div style="height: 280px;">
                <canvas id="categorySalesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- TABEL ANALITIK DETAIL PRODUK --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-stars me-2 text-warning"></i> Peringkat Penjualan & Profitabilitas Produk</h6>
            <span class="badge bg-light text-dark">{{ $items->count() }} Produk Teranalisis</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">Rank</th>
                            <th>Menu</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th>HPP / Porsi</th>
                            <th>Porsi Terjual</th>
                            <th>Pangsa Volume</th>
                            <th>Total Pendapatan</th>
                            <th>Total Laba</th>
                            <th class="pe-3">Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $idx => $item)
                            <tr>
                                <td class="ps-3">
                                    @if($idx === 0 && $item->total_qty > 0)
                                        <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">🥇 1</span>
                                    @elseif($idx === 1 && $item->total_qty > 0)
                                        <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">🥈 2</span>
                                    @elseif($idx === 2 && $item->total_qty > 0)
                                        <span class="badge bg-danger bg-opacity-75 text-white rounded-circle p-2 fs-6">🥉 3</span>
                                    @else
                                        <span class="fw-bold text-muted ps-2">#{{ $idx + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/40x40?text=Menu'">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <i class="bi bi-cup-hot text-muted"></i>
                                            </div>
                                        @endif
                                        <span class="fw-bold text-dark">{{ $item->menu_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $item->category_name }}</span>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="text-danger">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fs-6">{{ number_format($item->total_qty) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <span>{{ $item->volume_share }}%</span>
                                        <div class="progress flex-grow-1" style="width: 40px; height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $item->volume_share }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">Rp {{ number_format($item->total_profit, 0, ',', '.') }}</span>
                                </td>
                                <td class="pe-3">
                                    <span class="badge {{ $item->margin_pct >= 60 ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $item->margin_pct }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">Belum ada data penjualan produk pada periode ini.</td>
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
        const ctx = document.getElementById('categorySalesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($catLabels) !!},
                    datasets: [{
                        label: 'Porsi Terjual',
                        data: {!! json_encode($catQty) !!},
                        backgroundColor: '#ffc107',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

@endsection

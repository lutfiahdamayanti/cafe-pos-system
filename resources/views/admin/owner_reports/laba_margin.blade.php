@extends('layouts.admin')
@section('title', 'Laporan Owner: Laba Kotor & Margin')
@section('content')

<div class="container-fluid">

    {{-- HEADER & PERIODE FILTER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3 fs-5">
                <i class="bi bi-pie-chart-fill"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Laba Kotor & Margin (Gross Profit & Margin)</h4>
                <small class="text-muted">Ikhtisar laba operasional kafe: pendapatan bersih dikurangi beban pokok bahan baku (COGS).</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan P&L
        </button>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.owner-reports.laba-margin') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.owner-reports.laba-margin', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.owner-reports.laba-margin', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.owner-reports.laba-margin', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-primary' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.owner-reports.laba-margin', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-primary' : 'btn-outline-secondary' }}">Tahun Ini</a>
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

    {{-- EXECUTIVE PROFIT & LOSS (P&L) SUMMARY --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Pendapatan Bersih</span>
                    <i class="bi bi-cash-stack fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">Rp {{ number_format($netRevenue, 0, ',', '.') }}</h3>
                <small class="text-muted">Net sales</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Beban Pokok (COGS)</span>
                    <i class="bi bi-box-seam fs-4 text-danger"></i>
                </div>
                <h3 class="mb-0 fw-bold text-danger">Rp {{ number_format($totalCogs, 0, ',', '.') }}</h3>
                <small class="text-muted">Total biaya bahan baku</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Laba Kotor (Gross Profit)</span>
                    <i class="bi bi-graph-up-arrow fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($grossProfit, 0, ',', '.') }}</h3>
                <small class="text-muted">Pendapatan - COGS</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Margin Laba Kotor (%)</span>
                    <i class="bi bi-percent fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $grossMarginPct }}%</h3>
                <small class="text-muted">Tingkat profitabilitas usaha</small>
            </div>
        </div>
    </div>

    {{-- GRAFIK PERBANDINGAN REVENUE VS COGS VS PROFIT --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-bar-chart-line text-primary me-2"></i>Grafik Perbandingan Omzet vs COGS vs Laba Kotor
            </h6>
            <small class="text-muted">Periode: {{ $periodLabel }}</small>
        </div>
        <div class="card-body">
            <div style="height: 320px;">
                <canvas id="profitComparisonChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ANALISIS PROFITABILITAS PER KATEGORI --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-tags-fill text-warning me-2"></i>Profitabilitas & Margin per Kategori Produk
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kategori Menu</th>
                            <th>Total Omzet</th>
                            <th>Beban COGS</th>
                            <th>Laba Kotor (Rp)</th>
                            <th>Margin Laba (%)</th>
                            <th class="pe-3" style="width: 250px;">Indikator Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categoryBreakdown as $cat)
                            <tr>
                                <td class="ps-3 fw-bold text-dark fs-6">{{ $cat->category_name }}</td>
                                <td>Rp {{ number_format($cat->revenue, 0, ',', '.') }}</td>
                                <td><span class="text-danger">Rp {{ number_format($cat->cogs, 0, ',', '.') }}</span></td>
                                <td><span class="fw-bold text-success">Rp {{ number_format($cat->profit, 0, ',', '.') }}</span></td>
                                <td>
                                    <span class="badge {{ $cat->margin_pct >= 65 ? 'bg-success' : ($cat->margin_pct >= 55 ? 'bg-primary' : 'bg-warning text-dark') }} fs-6">
                                        {{ $cat->margin_pct }}%
                                    </span>
                                </td>
                                <td class="pe-3">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar {{ $cat->margin_pct >= 65 ? 'bg-success' : ($cat->margin_pct >= 55 ? 'bg-primary' : 'bg-warning') }}" style="width: {{ min(100, $cat->margin_pct) }}%;"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data pesanan pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('profitComparisonChart').getContext('2d');
    const labels = @json($chartLabels);
    const revenueData = @json($chartRevenue);
    const cogsData = @json($chartCogs);
    const profitData = @json($chartProfit);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Omzet Bersih (Rp)',
                    data: revenueData,
                    backgroundColor: 'rgba(13, 202, 240, 0.7)',
                    borderColor: '#0dcaf0',
                    borderWidth: 1,
                },
                {
                    label: 'Beban COGS (Rp)',
                    data: cogsData,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: '#dc3545',
                    borderWidth: 1,
                },
                {
                    label: 'Laba Kotor (Rp)',
                    data: profitData,
                    backgroundColor: 'rgba(25, 135, 84, 0.85)',
                    borderColor: '#198754',
                    borderWidth: 1,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) {
                            if (val >= 1000000) return 'Rp ' + (val/1000000) + ' Jt';
                            if (val >= 1000) return 'Rp ' + (val/1000) + ' Rb';
                            return 'Rp ' + val;
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

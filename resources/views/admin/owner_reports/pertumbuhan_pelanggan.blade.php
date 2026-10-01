@extends('layouts.admin')
@section('title', 'Laporan Owner: Pertumbuhan Pelanggan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTION --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                <i class="bi bi-person-lines-fill text-success"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Pertumbuhan Pelanggan (Customer Growth & Retention)</h4>
                <small class="text-muted">Pantau laju akuisisi pelanggan baru, tingkat retensi (repeat order), dan distribusi membership tier.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.owner-reports.pertumbuhan-pelanggan') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.owner-reports.pertumbuhan-pelanggan', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.owner-reports.pertumbuhan-pelanggan', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.owner-reports.pertumbuhan-pelanggan', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-primary' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.owner-reports.pertumbuhan-pelanggan', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-primary' : 'btn-outline-secondary' }}">Tahun Ini</a>
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

    {{-- KPI METRICS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Database Pelanggan</span>
                    <i class="bi bi-people fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalCustomers) }}</h3>
                <small class="text-muted">Keseluruhan kontak terdaftar</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Pelanggan Baru</span>
                    <i class="bi bi-person-plus fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">+{{ number_format($newCustomers) }}</h3>
                <small class="text-muted">Terdaftar pada {{ $periodLabel }}</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Pelanggan Bertransaksi</span>
                    <i class="bi bi-bag-check fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ number_format($transactingCustomersCount) }}</h3>
                <small class="text-muted">Aktif belanja periode ini</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Tingkat Retensi (Repeat)</span>
                    <i class="bi bi-arrow-repeat fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $retentionRate }}%</h3>
                <small class="text-muted">{{ number_format($repeatCustomersCount) }} pelanggan belanja > 1 kali</small>
            </div>
        </div>
    </div>

    {{-- GRAFIK AKUISISI & DISTRIBUSI TIER --}}
    <div class="row g-4 mb-4">
        {{-- GRAFIK AKUISISI PELANGGAN BARU BULANAN --}}
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-graph-up me-2 text-primary"></i> Tren Akuisisi Pelanggan Baru ({{ date('Y') }})</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">Tahunan</span>
                </div>
                <div class="card-body">
                    <div style="height: 280px;">
                        <canvas id="customerGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- DISTRIBUSI TIER MEMBERSHIP --}}
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-award me-2 text-warning"></i> Distribusi Membership Tier</h6>
                </div>
                <div class="card-body">
                    @php
                        $tierColors = [
                            'Bronze' => ['bg' => 'secondary', 'icon' => 'bi-shield', 'color' => '#6c757d'],
                            'Silver' => ['bg' => 'info', 'icon' => 'bi-shield-check', 'color' => '#0dcaf0'],
                            'Gold' => ['bg' => 'warning', 'icon' => 'bi-award-fill', 'color' => '#ffc107'],
                            'Platinum' => ['bg' => 'dark', 'icon' => 'bi-gem', 'color' => '#212529'],
                        ];
                    @endphp

                    <div class="d-flex flex-column gap-3">
                        @foreach($tierBreakdown as $tierName => $count)
                            @php
                                $pct = $totalCustomers > 0 ? round(($count / $totalCustomers) * 100, 1) : 0;
                                $tColor = $tierColors[$tierName] ?? ['bg' => 'primary', 'icon' => 'bi-shield', 'color' => '#0d6efd'];
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold">
                                        <i class="bi {{ $tColor['icon'] }} text-{{ $tColor['bg'] }} me-1"></i> Tier {{ $tierName }}
                                    </span>
                                    <span class="text-muted small"><strong>{{ number_format($count) }}</strong> anggota ({{ $pct }}%)</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $tColor['bg'] }}" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 p-3 bg-light rounded text-center">
                        <small class="text-muted d-block mb-1">Strategi Loyalty</small>
                        <span class="small fw-semibold text-dark">
                            Dorong promosi tiering untuk mengubah member Bronze menjadi Silver/Gold demi menaikkan Customer Lifetime Value (CLV).
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOP 10 SPENDER (VIP CUSTOMERS) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-star-fill text-warning me-2"></i> Top 10 Pelanggan dengan Nilai Belanja Tertinggi (VIP)</h6>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-primary">
                Kelola CRM Pelanggan <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 60px;">Rank</th>
                            <th>Pelanggan</th>
                            <th>No. Telepon / Email</th>
                            <th>Tier</th>
                            <th>Total Kunjungan</th>
                            <th>Poin Loyalty</th>
                            <th>Total Belanja</th>
                            <th class="pe-3">Kunjungan Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSpenders as $index => $c)
                            <tr>
                                <td class="ps-3">
                                    @if($index === 0)
                                        <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">🥇 1</span>
                                    @elseif($index === 1)
                                        <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">🥈 2</span>
                                    @elseif($index === 2)
                                        <span class="badge bg-danger bg-opacity-75 text-white rounded-circle p-2 fs-6">🥉 3</span>
                                    @else
                                        <span class="fw-bold text-muted ps-2">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($c->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $c->name }}</span>
                                            <small class="text-muted">Sejak: {{ $c->created_at ? $c->created_at->format('d M Y') : '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div><i class="bi bi-telephone text-muted me-1"></i> {{ $c->phone }}</div>
                                    @if($c->email)
                                        <small class="text-muted"><i class="bi bi-envelope me-1"></i> {{ $c->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($c->tier === 'Platinum') bg-dark 
                                        @elseif($c->tier === 'Gold') bg-warning text-dark 
                                        @elseif($c->tier === 'Silver') bg-info text-dark 
                                        @else bg-secondary 
                                        @endif">
                                        {{ $c->tier ?? 'Bronze' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ number_format($c->visit_count ?? 1) }}x</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-coin me-1"></i> {{ number_format($c->loyalty_points ?? 0) }} Poin
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">Rp {{ number_format($c->total_spending ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="pe-3">
                                    <small class="text-muted">{{ $c->last_visit_at ? \Carbon\Carbon::parse($c->last_visit_at)->diffForHumans() : '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-person-x fs-2 d-block mb-2"></i> Belum ada data pelanggan di sistem.
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
        const ctxGrowth = document.getElementById('customerGrowthChart');
        if (ctxGrowth) {
            new Chart(ctxGrowth, {
                type: 'line',
                data: {
                    labels: {!! json_encode($growthLabels) !!},
                    datasets: [{
                        label: 'Pelanggan Baru',
                        data: {!! json_encode($growthData) !!},
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#198754'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

@endsection

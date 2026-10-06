@extends('layouts.admin')
@section('title', 'Dashboard Bisnis: Analitik Pelanggan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                <i class="bi bi-people-fill text-success"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Dashboard Bisnis: Analitik Pelanggan</h4>
                <small class="text-muted">Analisis perilaku pelanggan: Customer Lifetime Value (CLV), tingkat repeat order, segmentasi loyalty tier, dan retensi.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan Pelanggan
        </button>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.business-dashboard.pelanggan') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.business-dashboard.pelanggan', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.business-dashboard.pelanggan', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.business-dashboard.pelanggan', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-primary' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.business-dashboard.pelanggan', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-primary' : 'btn-outline-secondary' }}">Tahun Ini</a>
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

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Database Pelanggan</span>
                    <i class="bi bi-people fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ number_format($totalCustomers) }}</h3>
                <small class="text-muted">+{{ number_format($newCustomers) }} baru pada {{ $periodLabel }}</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Spending (CLV)</span>
                    <i class="bi bi-cash-coin fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format(round($avgSpending), 0, ',', '.') }}</h3>
                <small class="text-muted">Nilai seumur hidup member</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Tingkat Repeat Order</span>
                    <i class="bi bi-arrow-repeat fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $repeatRate }}%</h3>
                <small class="text-muted">Pelanggan belanja > 1 kali</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Pelanggan Sangat Loyal</span>
                    <i class="bi bi-award fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ number_format($loyalCustomersCount) }}</h3>
                <small class="text-muted">Kunjungan >= 5 kali</small>
            </div>
        </div>
    </div>

    {{-- DISTRIBUSI TIER MEMBERSHIP & SEGMENTASI --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-shield-shaded me-2 text-primary"></i> Distribusi Anggota Membership Tier</h6>
                </div>
                <div class="card-body">
                    @php
                        $tierBadges = [
                            'Bronze' => ['badge' => 'secondary', 'icon' => 'bi-shield'],
                            'Silver' => ['badge' => 'info', 'icon' => 'bi-shield-check'],
                            'Gold' => ['badge' => 'warning text-dark', 'icon' => 'bi-award-fill'],
                            'Platinum' => ['badge' => 'dark', 'icon' => 'bi-gem'],
                        ];
                    @endphp

                    <div class="d-flex flex-column gap-3">
                        @foreach($tierCounts as $tName => $count)
                            @php
                                $pct = $totalCustomers > 0 ? round(($count / $totalCustomers) * 100, 1) : 0;
                                $tStyle = $tierBadges[$tName] ?? ['badge' => 'primary', 'icon' => 'bi-shield'];
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold">
                                        <span class="badge bg-{{ $tStyle['badge'] }} me-1"><i class="bi {{ $tStyle['icon'] }}"></i> {{ $tName }}</span>
                                    </span>
                                    <span class="text-muted small"><strong>{{ number_format($count) }}</strong> member ({{ $pct }}%)</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-{{ explode(' ', $tStyle['badge'])[0] }}" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-activity me-2 text-danger"></i> Kesehatan Retensi & Risiko Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded text-center">
                                <span class="badge bg-success mb-2">Pelanggan Aktif / Loyal</span>
                                <h3 class="fw-bold text-success mb-1">{{ number_format($loyalCustomersCount) }}</h3>
                                <small class="text-muted">Kunjungan rutin dan konsisten</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded text-center">
                                <span class="badge bg-danger mb-2">At-Risk (>45 Hari)</span>
                                <h3 class="fw-bold text-danger mb-1">{{ number_format($atRiskCount) }}</h3>
                                <small class="text-muted">Perlu promo win-back / voucher</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0 small">
                        <i class="bi bi-lightbulb-fill me-1"></i> <strong>Rekomendasi CRM:</strong> Berikan voucher personal atau promo cashback kepada segmen <em>At-Risk</em> agar mereka kembali bertransaksi di kafe Anda.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL TOP 10 VIP CUSTOMERS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-trophy-fill text-warning me-2"></i> Peringkat 10 Pelanggan dengan Belanja Tertinggi (VIP Spenders)</h6>
            <a href="{{ route('admin.customers.database') }}" class="btn btn-sm btn-outline-primary">
                Buka CRM Pelanggan <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">Rank</th>
                            <th>Pelanggan</th>
                            <th>No. Telepon / Email</th>
                            <th>Tier</th>
                            <th>Total Kunjungan</th>
                            <th>Poin Loyalty</th>
                            <th>Akumulasi Belanja</th>
                            <th class="pe-3">Kunjungan Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSpenders as $idx => $c)
                            <tr>
                                <td class="ps-3">
                                    @if($idx === 0)
                                        <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">🥇 1</span>
                                    @elseif($idx === 1)
                                        <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">🥈 2</span>
                                    @elseif($idx === 2)
                                        <span class="badge bg-danger bg-opacity-75 text-white rounded-circle p-2 fs-6">🥉 3</span>
                                    @else
                                        <span class="fw-bold text-muted ps-2">#{{ $idx + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $c->name }}</span>
                                    <small class="text-muted">ID: #{{ $c->id }}</small>
                                </td>
                                <td>
                                    <div>{{ $c->phone }}</div>
                                    @if($c->email)
                                        <small class="text-muted">{{ $c->email }}</small>
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
                                    <span class="badge bg-light text-primary border">
                                        <i class="bi bi-coin me-1"></i> {{ number_format($c->points ?? 0) }} Poin
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">Rp {{ number_format($c->total_spending ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="pe-3">
                                    <small class="text-muted">{{ $c->last_visit ? \Carbon\Carbon::parse($c->last_visit)->diffForHumans() : '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

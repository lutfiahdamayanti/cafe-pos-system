@extends('layouts.admin')
@section('title', 'Laporan Owner: Produk Paling Menguntungkan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTION --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-trophy-fill text-warning"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Produk Paling Menguntungkan (Menu Engineering Matrix)</h4>
                <small class="text-muted">Analisis kontribusi laba bersih per menu dan klasifikasi matriks F&B (Stars, Puzzles, Plowhorses, Dogs).</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.owner-reports.produk-menguntungkan') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.owner-reports.produk-menguntungkan', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.owner-reports.produk-menguntungkan', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-primary' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.owner-reports.produk-menguntungkan', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-primary' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.owner-reports.produk-menguntungkan', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-primary' : 'btn-outline-secondary' }}">Tahun Ini</a>
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

    {{-- METRIC SUMMARY CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Laba Penjualan</span>
                    <i class="bi bi-cash-coin fs-4 text-success"></i>
                </div>
                <h4 class="mb-0 fw-bold text-success">Rp {{ number_format($totalOverallProfit, 0, ',', '.') }}</h4>
                <small class="text-muted">{{ $periodLabel }}</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Margin Menu</span>
                    <i class="bi bi-pie-chart fs-4 text-info"></i>
                </div>
                <h4 class="mb-0 fw-bold text-info">{{ round($avgMargin, 1) }}%</h4>
                <small class="text-muted">Threshold profitabilitas</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Penjualan</span>
                    <i class="bi bi-basket fs-4 text-primary"></i>
                </div>
                <h4 class="mb-0 fw-bold text-primary">{{ round($avgQty, 1) }} porsi</h4>
                <small class="text-muted">Threshold popularitas menu</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Jumlah Menu Teranalisis</span>
                    <i class="bi bi-grid fs-4 text-warning"></i>
                </div>
                <h4 class="mb-0 fw-bold text-warning">{{ $rankedProducts->count() }} Menu</h4>
                <small class="text-muted">Katalog menu aktif</small>
            </div>
        </div>
    </div>

    {{-- MENU ENGINEERING 4 QUADRANTS EXPLANATION --}}
    @php
        $stars = $rankedProducts->where('matrix_class', 'Stars (Bintang)');
        $puzzles = $rankedProducts->where('matrix_class', 'Puzzles (Teka-Teki)');
        $plowhorses = $rankedProducts->where('matrix_class', 'Plowhorses (Pekerja Keras)');
        $dogs = $rankedProducts->where('matrix_class', 'Dogs (Kurang Profit)');
    @endphp

    <div class="row g-3 mb-4">
        {{-- STARS --}}
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm border-top border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-star-fill me-1"></i> Stars (Bintang)</span>
                        <span class="fw-bold fs-5 text-warning">{{ $stars->count() }}</span>
                    </div>
                    <p class="small text-muted mb-2"><strong>Penjualan Tinggi & Margin Tinggi.</strong> Menu juara yang paling menyumbang laba.</p>
                    <div class="bg-light p-2 rounded small text-dark">
                        <i class="bi bi-check2-circle text-success me-1"></i> Tindakan: Pertahankan kualitas konsisten & resep terbaik.
                    </div>
                </div>
            </div>
        </div>

        {{-- PUZZLES --}}
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm border-top border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-info text-dark px-2 py-1"><i class="bi bi-puzzle-fill me-1"></i> Puzzles (Teka-Teki)</span>
                        <span class="fw-bold fs-5 text-info">{{ $puzzles->count() }}</span>
                    </div>
                    <p class="small text-muted mb-2"><strong>Penjualan Rendah & Margin Tinggi.</strong> Potensi profit besar bila dipromosikan.</p>
                    <div class="bg-light p-2 rounded small text-dark">
                        <i class="bi bi-lightbulb text-info me-1"></i> Tindakan: Tampilkan di rekomendasi kasir & bundling promo.
                    </div>
                </div>
            </div>
        </div>

        {{-- PLOWHORSES --}}
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm border-top border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary px-2 py-1"><i class="bi bi-fire me-1"></i> Plowhorses (Pekerja Keras)</span>
                        <span class="fw-bold fs-5 text-primary">{{ $plowhorses->count() }}</span>
                    </div>
                    <p class="small text-muted mb-2"><strong>Penjualan Tinggi & Margin Rendah.</strong> Sangat digemari pelanggan tapi untung tipis.</p>
                    <div class="bg-light p-2 rounded small text-dark">
                        <i class="bi bi-arrow-up-right text-primary me-1"></i> Tindakan: Negosiasi bahan baku atau naikkan harga bertahap.
                    </div>
                </div>
            </div>
        </div>

        {{-- DOGS --}}
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm border-top border-4 border-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-secondary px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i> Dogs (Kurang Profit)</span>
                        <span class="fw-bold fs-5 text-secondary">{{ $dogs->count() }}</span>
                    </div>
                    <p class="small text-muted mb-2"><strong>Penjualan Rendah & Margin Rendah.</strong> Menu membebani stok dan minim keuntungan.</p>
                    <div class="bg-light p-2 rounded small text-dark">
                        <i class="bi bi-trash text-danger me-1"></i> Tindakan: Evaluasi resep, kurangi porsi, atau ganti menu baru.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PERINGKAT LABA PRODUK --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-stars me-2"></i> Peringkat Menu Berdasarkan Kontribusi Laba Bersih</h6>
            <span class="badge bg-light text-dark">Diurutkan dari Profit Terbesar</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 60px;">Rank</th>
                            <th>Menu</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th>HPP / Porsi</th>
                            <th>Laba / Porsi</th>
                            <th>Terjual</th>
                            <th>Total Kontribusi Laba</th>
                            <th>Margin</th>
                            <th>Klasifikasi</th>
                            <th class="pe-3">Rekomendasi Owner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rankedProducts as $index => $item)
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
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/40x40?text=Menu'">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <i class="bi bi-cup-hot text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $item->menu_name }}</span>
                                            <small class="text-muted">ID: #{{ $item->menu_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border">{{ $item->category_name }}</span>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="text-danger fw-semibold">Rp {{ number_format($item->effective_cost, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">Rp {{ number_format($item->profit_per_unit, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fs-6">{{ number_format($item->total_qty) }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">Rp {{ number_format($item->total_profit, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="fw-semibold">{{ $item->margin_pct }}%</span>
                                        <div class="progress flex-grow-1" style="width: 50px; height: 6px;">
                                            <div class="progress-bar {{ $item->margin_pct >= 60 ? 'bg-success' : ($item->margin_pct >= 40 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ min(100, $item->margin_pct) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $item->matrix_badge }}">
                                        <i class="bi {{ $item->matrix_icon }} me-1"></i> {{ $item->matrix_class }}
                                    </span>
                                </td>
                                <td class="pe-3">
                                    <small class="text-muted d-block" style="max-width: 250px;">{{ $item->action_hint }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada data penjualan menu pada rentang waktu ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@extends('layouts.admin')
@section('title', 'Laporan Owner: Food Cost & COGS')
@section('content')

<div class="container-fluid">

    {{-- HEADER & PERIODE FILTER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-calculator-fill"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Food Cost & COGS (Harga Pokok Penjualan)</h4>
                <small class="text-muted">Analisis biaya bahan baku, rasio HPP terhadap harga jual, dan efisiensi pengeluaran resep menu.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#updateAllCostModal">
            <i class="bi bi-pencil-square me-1"></i> Kelola HPP Semua Menu
        </button>
    </div>

    {{-- FILTER PERIODE WAKTU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.owner-reports.food-cost-cogs') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.owner-reports.food-cost-cogs', ['period' => 'this_month']) }}" class="btn btn-sm {{ $period === 'this_month' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.owner-reports.food-cost-cogs', ['period' => 'last_month']) }}" class="btn btn-sm {{ $period === 'last_month' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}">Bulan Lalu</a>
                    <a href="{{ route('admin.owner-reports.food-cost-cogs', ['period' => 'this_week']) }}" class="btn btn-sm {{ $period === 'this_week' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.owner-reports.food-cost-cogs', ['period' => 'this_year']) }}" class="btn btn-sm {{ $period === 'this_year' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}">Tahun Ini</a>
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

    {{-- KPI METRIK FOOD COST & COGS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Penjualan Menu</span>
                    <i class="bi bi-receipt fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($totalSalesRevenue, 0, ',', '.') }}</h3>
                <small class="text-muted">Omzet produk terjual</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total COGS (Beban HPP)</span>
                    <i class="bi bi-box-seam fs-4 text-danger"></i>
                </div>
                <h3 class="mb-0 fw-bold text-danger">Rp {{ number_format($totalCOGS, 0, ',', '.') }}</h3>
                <small class="text-muted">Biaya bahan baku terpakai</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rasio Food Cost (%)</span>
                    <i class="bi bi-percent fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-dark">{{ $overallFoodCostPercentage }}%</h3>
                <small class="text-muted">Target industri kafe: 28% - 35%</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card p-3 border-start border-4 {{ $overallFoodCostPercentage <= 35 ? 'border-success' : 'border-danger' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Status Efisiensi Bahan</span>
                    <i class="bi bi-shield-check fs-4 {{ $costHealth['class'] }}"></i>
                </div>
                <h4 class="mb-0 fw-bold {{ $costHealth['class'] }}">{{ $costHealth['status'] }}</h4>
                <small class="text-muted">{{ $costHealth['desc'] }}</small>
            </div>
        </div>
    </div>

    {{-- TABEL ANALISIS FOOD COST PER MENU --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-table text-warning me-2"></i>Rincian Food Cost & COGS per Menu
            </h6>
            <small class="text-muted">Periode: {{ $periodLabel }}</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Menu & Kategori</th>
                            <th>Harga Jual</th>
                            <th>Food Cost (HPP) / Porsi</th>
                            <th>Porsi Terjual</th>
                            <th>Total Omzet</th>
                            <th>Total Biaya COGS</th>
                            <th>% Food Cost</th>
                            <th class="text-end pe-3">Update HPP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderItems as $item)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded object-fit-cover" style="width: 38px; height: 38px;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center border" style="width: 38px; height: 38px;">
                                                <i class="bi bi-cup-hot text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item->menu_name }}</div>
                                            <small class="badge bg-light text-muted border">{{ $item->category_name ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="fw-bold text-danger">Rp {{ number_format($item->effective_food_cost, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fs-6">{{ $item->total_qty }} porsi</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger">Rp {{ number_format($item->item_cogs, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $item->cost_ratio <= 35 ? 'bg-success' : ($item->cost_ratio <= 40 ? 'bg-warning text-dark' : 'bg-danger') }} fs-6">
                                        {{ $item->cost_ratio }}%
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <form action="{{ route('admin.owner-reports.menu-food-cost.update', $item->menu_id) }}" method="POST" class="d-inline-flex gap-1 align-items-center justify-content-end">
                                        @csrf
                                        <input type="number" name="food_cost" class="form-control form-control-sm" style="width: 100px;" value="{{ $item->effective_food_cost }}" min="0" required>
                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Simpan HPP">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    Belum ada data pesanan pada periode ini untuk kalkulasi COGS.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL UPDATE HPP SEMUA MENU --}}
<div class="modal fade" id="updateAllCostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Kelola Food Cost (HPP) Seluruh Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-3">Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga Jual (Rp)</th>
                                <th>Food Cost / HPP Saat Ini</th>
                                <th class="text-end pe-3">Aksi Simpan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allMenus as $m)
                                <tr>
                                    <td class="ps-3 fw-bold">{{ $m->name }}</td>
                                    <td><span class="badge bg-light text-muted border">{{ $m->category->name ?? '-' }}</span></td>
                                    <td>Rp {{ number_format($m->price, 0, ',', '.') }}</td>
                                    <td>
                                        <form id="formCost{{ $m->id }}" action="{{ route('admin.owner-reports.menu-food-cost.update', $m->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                            @csrf
                                            <input type="number" name="food_cost" class="form-control form-control-sm" value="{{ $m->food_cost > 0 ? (int)$m->food_cost : (int)round($m->price * 0.32) }}" min="0" required>
                                        </form>
                                    </td>
                                    <td class="text-end pe-3">
                                        <button type="submit" form="formCost{{ $m->id }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-save me-1"></i> Simpan
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

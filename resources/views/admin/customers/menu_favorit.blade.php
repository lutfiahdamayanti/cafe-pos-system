@extends('layouts.admin')
@section('title', 'Menu Favorit Pelanggan')
@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger bg-opacity-10 text-danger p-2 rounded-3 fs-5">
                <i class="bi bi-heart-fill"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Menu Favorit Pelanggan</h4>
                <small class="text-muted">Analitik preferensi produk terlaris di kalangan member & preferensi spesifik tiap pelanggan.</small>
            </div>
        </div>
    </div>

    {{-- TOP 3 MENU PALING DICINTAI MEMBER --}}
    <div class="row g-3 mb-4">
        @foreach($topMenus->take(3) as $idx => $tm)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 position-relative overflow-hidden">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="position-relative">
                            @if($tm->image)
                                <img src="{{ asset('storage/' . $tm->image) }}" alt="{{ $tm->name }}" class="rounded-3 object-fit-cover" style="width: 75px; height: 75px;">
                            @else
                                <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border" style="width: 75px; height: 75px;">
                                    <i class="bi bi-cup-hot fs-2 text-muted"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 start-0 translate-middle badge rounded-circle {{ $idx == 0 ? 'bg-warning text-dark' : ($idx == 1 ? 'bg-secondary text-white' : 'bg-dark text-white') }} p-2" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; font-size: 11px;">
                                #{{ $idx + 1 }}
                            </span>
                        </div>

                        <div class="flex-grow-1">
                            <span class="badge bg-light text-muted border small mb-1">{{ $tm->category_name ?? 'Menu' }}</span>
                            <h6 class="fw-bold mb-1 text-dark">{{ $tm->name }}</h6>
                            <div class="small text-muted mb-2">Rp {{ number_format($tm->price, 0, ',', '.') }}</div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-primary">
                                    <i class="bi bi-cart-check me-1"></i>{{ $tm->total_sold }}x dipesan
                                </span>
                                <span class="badge bg-success">
                                    Rp {{ number_format($tm->total_revenue, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- TABEL TOP 10 MENU FAVORIT MEMBER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-star-fill text-warning me-2"></i>Peringkat Menu Paling Banyak Dipesan oleh Pelanggan
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 60px;">Rank</th>
                            <th>Menu</th>
                            <th>Kategori</th>
                            <th>Harga Satuan</th>
                            <th>Total Porsi Dipesan</th>
                            <th>Jumlah Member Pemesan</th>
                            <th class="text-end pe-3">Total Omzet Member</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topMenus as $rank => $item)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge rounded-pill {{ $rank == 0 ? 'bg-warning text-dark' : ($rank == 1 ? 'bg-secondary text-white' : ($rank == 2 ? 'bg-dark text-white' : 'bg-light text-dark border')) }}">
                                        {{ $rank + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded object-fit-cover" style="width: 40px; height: 40px;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-cup-hot text-muted"></i>
                                            </div>
                                        @endif
                                        <span class="fw-bold text-dark">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $item->category_name ?? '-' }}</span></td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="fw-bold text-primary fs-6">{{ $item->total_sold }} porsi</span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-people me-1"></i>{{ $item->unique_customers }} pelanggan
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada data pesanan menu oleh member.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MATRIKS PREFERENSI MENU PER PELANGGAN --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-person-heart text-danger me-2"></i>Preferensi Menu Favorit Setiap Pelanggan
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Pelanggan</th>
                            <th>Tier</th>
                            <th>Total Kunjungan</th>
                            <th>Menu Paling Sering Dipesan (Top Favorites)</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $c)
                            @php
                                $favs = $customerFavorites[$c->id] ?? collect();
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('admin.customers.show', $c->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $c->name }}
                                    </a>
                                    <div class="small text-muted">{{ $c->phone }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ match($c->tier) {'Platinum'=>'badge-tier-platinum','Gold'=>'badge-tier-gold','Silver'=>'badge-tier-silver',default=>'badge-tier-bronze'} }}">
                                        {{ $c->tier }}
                                    </span>
                                </td>
                                <td>{{ $c->visit_count }}x transaksi</td>
                                <td>
                                    @if($favs->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($favs as $f)
                                                <span class="badge bg-light text-dark border p-2">
                                                    <i class="bi bi-heart-fill text-danger me-1"></i>
                                                    {{ $f->name }}
                                                    <span class="badge bg-primary ms-1">{{ $f->total_qty }}x</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted small">Belum ada riwayat pesanan</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.customers.show', $c->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail Profil
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada pelanggan terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="p-3 border-top">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

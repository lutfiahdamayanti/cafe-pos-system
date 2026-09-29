@extends('layouts.admin')
@section('title', 'Database Pelanggan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                    <i class="bi bi-people-fill"></i>
                </span>
                <div>
                    <h4 class="fw-bold mb-0">Database Pelanggan</h4>
                    <small class="text-muted">Direktori seluruh data pelanggan, kontak, profil loyalitas, dan status membership.</small>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.customers.export.csv') }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Ekspor CSV
            </a>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus-fill me-1"></i> Tambah Pelanggan Baru
            </a>
        </div>
    </div>

    {{-- QUICK STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Pelanggan</span>
                    <i class="bi bi-person-badge fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold">{{ number_format($totalCustomers) }}</h3>
                <small class="text-muted">Member terdaftar</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Kontak WhatsApp</span>
                    <i class="bi bi-whatsapp fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ number_format($totalWithPhone) }}</h3>
                <small class="text-muted">Memiliki nomor aktif</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Email Terdaftar</span>
                    <i class="bi bi-envelope-check fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold">{{ number_format($totalWithEmail) }}</h3>
                <small class="text-muted">Untuk newsletter/promo</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Poin Member</span>
                    <i class="bi bi-coin fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ number_format($totalPoints) }}</h3>
                <small class="text-muted">Poin beredar</small>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.customers.database') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama, no HP/WhatsApp, atau email..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="tier" class="form-select">
                        <option value="">Semua Membership Tier</option>
                        <option value="Bronze" {{ request('tier') == 'Bronze' ? 'selected' : '' }}>Bronze</option>
                        <option value="Silver" {{ request('tier') == 'Silver' ? 'selected' : '' }}>Silver</option>
                        <option value="Gold" {{ request('tier') == 'Gold' ? 'selected' : '' }}>Gold</option>
                        <option value="Platinum" {{ request('tier') == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Pendaftaran Terbaru</option>
                        <option value="spending_desc" {{ request('sort') == 'spending_desc' ? 'selected' : '' }}>Belanja Tertinggi</option>
                        <option value="points_desc" {{ request('sort') == 'points_desc' ? 'selected' : '' }}>Poin Terbanyak</option>
                        <option value="visits_desc" {{ request('sort') == 'visits_desc' ? 'selected' : '' }}>Kunjungan Terbanyak</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                    </select>
                </div>

                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-success w-100" title="Filter Data">
                        <i class="bi bi-funnel"></i>
                    </button>
                    @if(request()->anyFilled(['search', 'tier', 'sort']))
                        <a href="{{ route('admin.customers.database') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL DATABASE PELANGGAN --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">No</th>
                            <th>Pelanggan</th>
                            <th>Tier</th>
                            <th>Poin Loyalty</th>
                            <th>Kunjungan</th>
                            <th>Total Belanja</th>
                            <th>Tgl Lahir</th>
                            <th>Terakhir Kunjung</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            @php
                                $tier = $customer->tier;
                                $badgeClass = match($tier) {
                                    'Platinum' => 'badge-tier-platinum',
                                    'Gold' => 'badge-tier-gold',
                                    'Silver' => 'badge-tier-silver',
                                    default => 'badge-tier-bronze'
                                };
                            @endphp
                            <tr>
                                <td class="ps-3 text-muted">{{ $customers->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle" style="width: 38px; height: 38px; font-size: 16px;">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="fw-bold text-decoration-none text-dark">
                                                {{ $customer->name }}
                                            </a>
                                            <div class="small text-muted">
                                                <i class="bi bi-whatsapp text-success me-1"></i>{{ $customer->phone }}
                                                @if($customer->email)
                                                    <span class="mx-1">•</span><i class="bi bi-envelope me-1"></i>{{ $customer->email }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        @if($tier === 'Platinum') <i class="bi bi-gem me-1"></i>
                                        @elseif($tier === 'Gold') <i class="bi bi-award-fill me-1"></i>
                                        @elseif($tier === 'Silver') <i class="bi bi-shield-shaded me-1"></i>
                                        @else <i class="bi bi-shield me-1"></i>
                                        @endif
                                        {{ $tier }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-warning">
                                        <i class="bi bi-coin me-1"></i>{{ number_format($customer->points) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $customer->visit_count }}x
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($customer->total_spending, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if($customer->birth_date)
                                        <span>{{ $customer->birth_date->format('d M Y') }}</span>
                                        @if($customer->isBirthdayToday())
                                            <span class="badge bg-danger ms-1">🎂 Hari Ini</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $customer->last_visit ? $customer->last_visit->diffForHumans() : '-' }}
                                    </small>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-outline-primary" title="Lihat Profil 360">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-outline-secondary" title="Edit Data">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelanggan {{ $customer->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-people display-4 d-block mb-3 opacity-25"></i>
                                    Belum ada data pelanggan yang sesuai dengan pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        Menampilkan {{ $customers->firstItem() }} - {{ $customers->lastItem() }} dari {{ $customers->total() }} pelanggan
                    </span>
                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

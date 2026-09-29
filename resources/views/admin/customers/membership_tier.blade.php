@extends('layouts.admin')
@section('title', 'Membership Tier Pelanggan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-award-fill"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Membership Tier (Bronze · Silver · Gold · Platinum)</h4>
                <small class="text-muted">Kelola jenjang keanggotaan pelanggan, kriteria belanja, dan penyesuaian level member.</small>
            </div>
        </div>

        <form action="{{ route('admin.customers.recalculate-tiers') }}" method="POST" onsubmit="return confirm('Kalkulasi ulang seluruh membership tier pelanggan berdasarkan total belanja & kunjungan?')">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-arrow-repeat me-1"></i> Sinkronisasi Tier Otomatis
            </button>
        </form>
    </div>

    {{-- 4 KARTU TIER LEVEL --}}
    <div class="row g-3 mb-4">
        {{-- BRONZE --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 position-relative overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-tier-bronze"><i class="bi bi-shield me-1"></i>Bronze</span>
                        <span class="fs-4 fw-bold text-dark">{{ $tierCounts['Bronze'] }}</span>
                    </div>
                    <h6 class="fw-bold mb-1">Level Pemula</h6>
                    <small class="text-muted d-block mb-2">Belanja &lt; Rp 500.000</small>
                    <div class="p-2 bg-light rounded small">
                        <div>• 1x Poin Loyalty</div>
                        <div>• Akses promo reguler</div>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" style="width: {{ $totalMembers > 0 ? ($tierCounts['Bronze']/$totalMembers)*100 : 0 }}%; background: #cd7f32;"></div>
                </div>
            </div>
        </div>

        {{-- SILVER --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 position-relative overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-tier-silver"><i class="bi bi-shield-shaded me-1"></i>Silver</span>
                        <span class="fs-4 fw-bold text-dark">{{ $tierCounts['Silver'] }}</span>
                    </div>
                    <h6 class="fw-bold mb-1">Level Menengah</h6>
                    <small class="text-muted d-block mb-2">Belanja &ge; Rp 500rb / 5x Visit</small>
                    <div class="p-2 bg-light rounded small">
                        <div>• Diskon 5% Semua Minuman</div>
                        <div>• 1.2x Poin Loyalty</div>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" style="width: {{ $totalMembers > 0 ? ($tierCounts['Silver']/$totalMembers)*100 : 0 }}%; background: #adb5bd;"></div>
                </div>
            </div>
        </div>

        {{-- GOLD --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 position-relative overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-tier-gold"><i class="bi bi-award-fill me-1"></i>Gold</span>
                        <span class="fs-4 fw-bold text-dark">{{ $tierCounts['Gold'] }}</span>
                    </div>
                    <h6 class="fw-bold mb-1">Level Loyal</h6>
                    <small class="text-muted d-block mb-2">Belanja &ge; Rp 1,5 Juta / 15x Visit</small>
                    <div class="p-2 bg-light rounded small">
                        <div>• Diskon 10% Semua Menu</div>
                        <div>• 1.5x Poin Loyalty & Welcome Drink</div>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" style="width: {{ $totalMembers > 0 ? ($tierCounts['Gold']/$totalMembers)*100 : 0 }}%; background: #ffd700;"></div>
                </div>
            </div>
        </div>

        {{-- PLATINUM --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 position-relative overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-tier-platinum"><i class="bi bi-gem me-1"></i>Platinum</span>
                        <span class="fs-4 fw-bold text-dark">{{ $tierCounts['Platinum'] }}</span>
                    </div>
                    <h6 class="fw-bold mb-1">Level VIP</h6>
                    <small class="text-muted d-block mb-2">Belanja &ge; Rp 3 Juta / 30x Visit</small>
                    <div class="p-2 bg-light rounded small">
                        <div>• Diskon 15% + Free Birthday Treat</div>
                        <div>• 2x Poin Loyalty & Reservasi Meja VIP</div>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" style="width: {{ $totalMembers > 0 ? ($tierCounts['Platinum']/$totalMembers)*100 : 0 }}%; background: #7952b3;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB TIER FILTER & TABEL MEMBER --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <ul class="nav nav-pills gap-1">
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.membership-tier', ['tier' => 'All']) }}" class="nav-link btn-sm {{ $selectedTier == 'All' ? 'active' : '' }}">
                            Semua Tier ({{ $totalMembers }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.membership-tier', ['tier' => 'Bronze']) }}" class="nav-link btn-sm {{ $selectedTier == 'Bronze' ? 'active' : '' }}">
                            Bronze ({{ $tierCounts['Bronze'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.membership-tier', ['tier' => 'Silver']) }}" class="nav-link btn-sm {{ $selectedTier == 'Silver' ? 'active' : '' }}">
                            Silver ({{ $tierCounts['Silver'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.membership-tier', ['tier' => 'Gold']) }}" class="nav-link btn-sm {{ $selectedTier == 'Gold' ? 'active' : '' }}">
                            Gold ({{ $tierCounts['Gold'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.membership-tier', ['tier' => 'Platinum']) }}" class="nav-link btn-sm {{ $selectedTier == 'Platinum' ? 'active' : '' }}">
                            Platinum ({{ $tierCounts['Platinum'] }})
                        </a>
                    </li>
                </ul>

                <form action="{{ route('admin.customers.membership-tier') }}" method="GET" class="d-flex gap-2">
                    <input type="hidden" name="tier" value="{{ $selectedTier }}">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari member..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Nama Pelanggan</th>
                            <th>No HP</th>
                            <th>Membership Tier</th>
                            <th>Total Belanja</th>
                            <th>Kunjungan</th>
                            <th>Poin Loyalty</th>
                            <th>Ubah Tier Manual</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $m)
                            <tr>
                                <td class="ps-3 text-muted">{{ $members->firstItem() + $loop->index }}</td>
                                <td>
                                    <a href="{{ route('admin.customers.show', $m->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $m->name }}
                                    </a>
                                </td>
                                <td><i class="bi bi-whatsapp text-success me-1"></i>{{ $m->phone }}</td>
                                <td>
                                    <span class="badge {{ match($m->tier) {'Platinum'=>'badge-tier-platinum','Gold'=>'badge-tier-gold','Silver'=>'badge-tier-silver',default=>'badge-tier-bronze'} }}">
                                        {{ $m->tier }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($m->total_spending, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>{{ $m->visit_count }}x</td>
                                <td><span class="fw-bold text-warning">{{ number_format($m->points) }} Pts</span></td>
                                <td>
                                    <form action="{{ route('admin.customers.update-tier', $m->id) }}" method="POST" class="d-flex gap-1 align-items-center">
                                        @csrf
                                        <select name="tier" class="form-select form-select-sm" style="width: 110px;">
                                            <option value="Bronze" {{ $m->tier == 'Bronze' ? 'selected' : '' }}>Bronze</option>
                                            <option value="Silver" {{ $m->tier == 'Silver' ? 'selected' : '' }}>Silver</option>
                                            <option value="Gold" {{ $m->tier == 'Gold' ? 'selected' : '' }}>Gold</option>
                                            <option value="Platinum" {{ $m->tier == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Simpan Perubahan Tier">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.customers.show', $m->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i> Profil
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    Tidak ada data pelanggan di tier ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($members->hasPages())
                <div class="p-3 border-top">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

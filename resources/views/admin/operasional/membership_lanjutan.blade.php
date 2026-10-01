@extends('layouts.admin')
@section('title', 'Operasional: Level Membership Lanjutan')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-3 fs-5">
                <i class="bi bi-gem text-info"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Level Membership Lanjutan (Advanced Tiering & Privileges)</h4>
                <small class="text-muted">Konfigurasi kriteria kenaikan kelas member, pengganda poin loyalitas, diskon eksklusif, dan hak istimewa VIP.</small>
            </div>
        </div>

        <form action="{{ route('admin.operasional.membership-lanjutan.recalculate') }}" method="POST" onsubmit="return confirm('Jalankan audit dan rekalkulasi otomatis tier seluruh database pelanggan?')">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-arrow-repeat me-1"></i> Audit & Rekalkulasi Tier Member
            </button>
        </form>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 4 KARTU TIER RULES & PRIVILEGES --}}
    @php
        $tierStyles = [
            'Bronze' => ['border' => 'border-secondary', 'badge' => 'bg-secondary', 'icon' => 'bi-shield', 'color' => '#6c757d'],
            'Silver' => ['border' => 'border-info', 'badge' => 'bg-info text-dark', 'icon' => 'bi-shield-check', 'color' => '#0dcaf0'],
            'Gold' => ['border' => 'border-warning', 'badge' => 'bg-warning text-dark', 'icon' => 'bi-award-fill', 'color' => '#ffc107'],
            'Platinum' => ['border' => 'border-dark', 'badge' => 'bg-dark', 'icon' => 'bi-gem', 'color' => '#212529'],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach($tierRules as $rule)
            @php
                $style = $tierStyles[$rule->tier_name] ?? ['border' => 'border-primary', 'badge' => 'bg-primary', 'icon' => 'bi-shield', 'color' => '#0d6efd'];
                $memberCount = $tierCounts[$rule->tier_name] ?? 0;
                $memberPct = $totalMembers > 0 ? round(($memberCount / $totalMembers) * 100, 1) : 0;
            @endphp
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm border-top border-4 {{ $style['border'] }}">
                    <div class="card-body d-flex flex-column justify-content-between p-3">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge {{ $style['badge'] }} px-2 py-1 fs-6">
                                    <i class="bi {{ $style['icon'] }} me-1"></i> {{ $rule->tier_name }}
                                </span>
                                <span class="badge bg-light text-dark border">
                                    {{ $memberCount }} Member ({{ $memberPct }}%)
                                </span>
                            </div>

                            <div class="my-3">
                                <small class="text-muted d-block">Syarat Kualifikasi:</small>
                                <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($rule->min_spending, 0, ',', '.') }}</h5>
                                <small class="text-muted">Min. Belanja & {{ $rule->min_orders }}x Kunjungan</small>
                            </div>

                            <div class="bg-light p-2 rounded mb-3 small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Poin Multiplier:</span>
                                    <strong class="text-primary">{{ $rule->point_multiplier }}x Poin</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Diskon Transaksi:</span>
                                    <strong class="text-success">{{ $rule->discount_percent }}% OFF</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Masa Aktif Tier:</span>
                                    <span class="text-dark">{{ $rule->validity_months }} Bulan</span>
                                </div>
                            </div>

                            <h6 class="fw-bold small mb-2 text-dark">Hak Istimewa (Privileges):</h6>
                            <ul class="list-unstyled small mb-3">
                                <li class="mb-1 {{ $rule->free_birthday_drink ? 'text-success fw-semibold' : 'text-muted' }}">
                                    <i class="bi {{ $rule->free_birthday_drink ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' }} me-1"></i>
                                    Free Birthday Drink
                                </li>
                                <li class="mb-1 {{ $rule->priority_table ? 'text-success fw-semibold' : 'text-muted' }}">
                                    <i class="bi {{ $rule->priority_table ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' }} me-1"></i>
                                    Prioritas Reservasi Meja VIP
                                </li>
                                <li class="mb-1 {{ $rule->free_upsize ? 'text-success fw-semibold' : 'text-muted' }}">
                                    <i class="bi {{ $rule->free_upsize ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' }} me-1"></i>
                                    Free Upsize Cup
                                </li>
                            </ul>
                        </div>

                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalEditTier{{ $rule->id }}">
                                <i class="bi bi-gear me-1"></i> Atur Kriteria & Benefit
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL EDIT TIER RULE --}}
            <div class="modal fade" id="modalEditTier{{ $rule->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('admin.operasional.membership-lanjutan.update', $rule) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-gem text-info me-2"></i> Konfigurasi Tier: {{ $rule->tier_name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Min. Akumulasi Belanja (Rp)</label>
                                        <input type="number" name="min_spending" class="form-control" value="{{ (int)$rule->min_spending }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Min. Jumlah Kunjungan</label>
                                        <input type="number" name="min_orders" class="form-control" value="{{ $rule->min_orders }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Pengganda Poin (Multiplier)</label>
                                        <input type="number" step="0.05" name="point_multiplier" class="form-control" value="{{ $rule->point_multiplier }}" required>
                                        <small class="text-muted">Misal 1.5 untuk 1.5x lipat poin.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Diskon Belanja (%)</label>
                                        <input type="number" step="0.5" name="discount_percent" class="form-control" value="{{ $rule->discount_percent }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold">Masa Aktif Tier (Bulan)</label>
                                        <input type="number" name="validity_months" class="form-control" value="{{ $rule->validity_months }}" required>
                                    </div>
                                </div>

                                <h6 class="fw-bold border-bottom pb-2 mb-2">Hak Istimewa & Privilese Tambahan</h6>

                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="free_birthday_drink" id="bdDrink{{ $rule->id }}" {{ $rule->free_birthday_drink ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="bdDrink{{ $rule->id }}">
                                        Gratis 1 Minuman Pilihan di Hari Ulang Tahun
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="priority_table" id="prioTable{{ $rule->id }}" {{ $rule->priority_table ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="prioTable{{ $rule->id }}">
                                        Prioritas Reservasi Meja VIP & Bebas Minimum Spend
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="free_upsize" id="freeUpsize{{ $rule->id }}" {{ $rule->free_upsize ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="freeUpsize{{ $rule->id }}">
                                        Gratis Upsize Minuman dari Regular ke Large
                                    </label>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Deskripsi Penjelasan Member</label>
                                    <textarea name="description" class="form-control" rows="2">{{ $rule->description }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Aturan Tier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- TABEL PELANGGAN VIP BERDASARKAN TIER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-people-fill me-2 text-primary"></i> Pantauan Member Pelanggan & Kualifikasi Kenaikan Tier</h6>
            <a href="{{ route('admin.customers.membership-tier') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua Pelanggan CRM <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Pelanggan</th>
                            <th>No. Telepon / Email</th>
                            <th>Tier Saat Ini</th>
                            <th>Total Kunjungan</th>
                            <th>Poin Terkumpul</th>
                            <th>Total Belanja Sepanjang Waktu</th>
                            <th class="pe-3">Status Kualifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $c)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($c->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $c->name }}</span>
                                            <small class="text-muted">Member ID: #{{ $c->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span>{{ $c->phone }}</span>
                                    @if($c->email)
                                        <small class="text-muted d-block">{{ $c->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($c->tier === 'Platinum') bg-dark 
                                        @elseif($c->tier === 'Gold') bg-warning text-dark 
                                        @elseif($c->tier === 'Silver') bg-info text-dark 
                                        @else bg-secondary 
                                        @endif fs-6">
                                        {{ $c->tier ?? 'Bronze' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $c->visit_count ?? 1 }}x Kunjungan</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border">
                                        <i class="bi bi-coin me-1"></i> {{ number_format($c->loyalty_points ?? 0) }} Poin
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">Rp {{ number_format($c->total_spending ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="pe-3">
                                    @if($c->tier === 'Platinum')
                                        <span class="badge bg-success bg-opacity-10 text-success border">
                                            <i class="bi bi-check2-all me-1"></i> Tier Tertinggi (Top VIP)
                                        </span>
                                    @else
                                        <small class="text-muted">
                                            Menuju Tier Berikutnya
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i> Belum ada data pelanggan.
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

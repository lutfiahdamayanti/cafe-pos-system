@extends('layouts.admin')
@section('title', 'Detail CRM Pelanggan: ' . $customer->name)
@section('content')

<div class="container-fluid">

    {{-- BREADCRUMB & TOP ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke CRM
            </a>
            <h4 class="fw-bold mb-0">Profil 360 Pelanggan</h4>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ $waGreetingUrl }}" target="_blank" class="btn btn-outline-success">
                <i class="bi bi-whatsapp me-1"></i> Hubungi WA
            </a>
            @if($customer->birth_date)
                <a href="{{ $waBirthdayUrl }}" target="_blank" class="btn btn-warning text-dark">
                    <i class="bi bi-cake2 me-1"></i> Kirim Ucapan Ultah
                </a>
            @endif
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- SISI KIRI: KARTU MEMBER VIP & INFORMASI PROFIL --}}
        <div class="col-lg-4">
            @php
                $tier = $customer->tier;
                $tierClass = match($tier) {
                    'Platinum' => 'tier-platinum',
                    'Gold' => 'tier-gold',
                    'Silver' => 'tier-silver',
                    default => 'tier-bronze'
                };
            @endphp

            {{-- KARTU MEMBERSHIP VIP DIGITAL --}}
            <div class="member-card-vip {{ $tierClass }} mb-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="text-uppercase small tracking-wide opacity-75 d-block">Membership Card</span>
                        <h4 class="fw-bold mb-0 text-white">{{ $customer->name }}</h4>
                    </div>
                    <span class="badge {{ match($tier) {'Platinum'=>'bg-light text-dark','Gold'=>'bg-dark text-warning','Silver'=>'bg-dark text-light',default=>'bg-dark text-white'} }} px-3 py-2 fs-6">
                        @if($tier === 'Platinum') <i class="bi bi-gem me-1"></i>
                        @elseif($tier === 'Gold') <i class="bi bi-award-fill me-1"></i>
                        @elseif($tier === 'Silver') <i class="bi bi-shield-shaded me-1"></i>
                        @else <i class="bi bi-shield me-1"></i>
                        @endif
                        {{ $tier }}
                    </span>
                </div>

                <div class="mb-3">
                    <small class="opacity-75 d-block">Nomor Member (HP)</small>
                    <h5 class="fw-bold font-monospace mb-0">{{ $customer->phone }}</h5>
                </div>

                <div class="d-flex justify-content-between align-items-end pt-3 border-top border-white border-opacity-25">
                    <div>
                        <small class="opacity-75 d-block">Saldo Poin Loyalty</small>
                        <h4 class="fw-bold mb-0 text-warning"><i class="bi bi-coin me-1"></i>{{ number_format($customer->points) }} Pts</h4>
                    </div>
                    <div class="text-end">
                        <small class="opacity-75 d-block">Total Belanja</small>
                        <span class="fw-bold fs-6">Rp {{ number_format($customer->total_spending, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- DATA DETAIL PELANGGAN --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-person-lines-fill me-2 text-success"></i>Informasi Kontak & Profil</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 pb-2 border-bottom">
                            <span class="text-muted small d-block">Email</span>
                            <span class="fw-semibold">{{ $customer->email ?? 'Belum ada email' }}</span>
                        </li>
                        <li class="mb-3 pb-2 border-bottom">
                            <span class="text-muted small d-block">Tanggal Lahir</span>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-semibold">
                                    {{ $customer->birth_date ? $customer->birth_date->format('d F Y') : 'Belum diisi' }}
                                </span>
                                @if($customer->isBirthdayToday())
                                    <span class="badge bg-danger">🎂 Ulang Tahun Hari Ini!</span>
                                @endif
                            </div>
                        </li>
                        <li class="mb-3 pb-2 border-bottom">
                            <span class="text-muted small d-block">Total Kunjungan</span>
                            <span class="fw-semibold">{{ $customer->visit_count }} kali pemesanan</span>
                        </li>
                        <li class="mb-3 pb-2 border-bottom">
                            <span class="text-muted small d-block">Kunjungan Terakhir</span>
                            <span class="fw-semibold">
                                {{ $customer->last_visit ? $customer->last_visit->format('d M Y, H:i') . ' (' . $customer->last_visit->diffForHumans() . ')' : '-' }}
                            </span>
                        </li>
                        <li class="mb-3 pb-2 border-bottom">
                            <span class="text-muted small d-block">Alamat</span>
                            <span class="fw-semibold">{{ $customer->address ?? '-' }}</span>
                        </li>
                        <li>
                            <span class="text-muted small d-block">Catatan Preferensi</span>
                            <div class="p-2 bg-light rounded mt-1 small">
                                {{ $customer->notes ?? 'Tidak ada catatan preferensi pelanggan.' }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- FORM PENYESUAIAN POIN (TAMBAH / KURANG) --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-coin me-2 text-warning"></i>Kelola Poin Loyalty</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.customers.adjust-points', $customer->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Tipe Aksi</label>
                            <select name="type" class="form-select form-select-sm" required>
                                <option value="earned">Tambah Poin (Bonus / Promo)</option>
                                <option value="redeemed">Tukar Poin (Redeem Hadiah)</option>
                                <option value="adjusted">Koreksi Poin (Penyesuaian)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Jumlah Poin</label>
                            <input type="number" name="points" class="form-control form-control-sm" placeholder="Contoh: 50" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Keterangan / Alasan</label>
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="Contoh: Bonus Ulang Tahun / Penukaran Kopi" required>
                        </div>
                        <button type="submit" class="btn btn-sm btn-warning text-dark w-100 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i> Simpan Mutasi Poin
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: TABS RIWAYAT PEMBELIAN, MENU FAVORIT & LOG MUTASI POIN --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
                    <ul class="nav nav-tabs card-header-tabs" id="crmTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-semibold" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-pane" type="button" role="tab">
                                <i class="bi bi-receipt me-1"></i> Riwayat Pembelian ({{ $orders->total() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" id="favorites-tab" data-bs-toggle="tab" data-bs-target="#favorites-pane" type="button" role="tab">
                                <i class="bi bi-heart-fill text-danger me-1"></i> Menu Favorit ({{ count($favoriteMenus) }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" id="points-tab" data-bs-toggle="tab" data-bs-target="#points-pane" type="button" role="tab">
                                <i class="bi bi-clock-history me-1"></i> Mutasi Poin ({{ $pointLogs->total() }})
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="crmTabsContent">

                        {{-- TAB 1: RIWAYAT PEMBELIAN (ORDER HISTORY) --}}
                        <div class="tab-pane fade show active" id="orders-pane" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No Order</th>
                                            <th>Tanggal</th>
                                            <th>Tipe / Meja</th>
                                            <th>Item Menu</th>
                                            <th>Total</th>
                                            <th>Pembayaran</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold text-dark font-monospace">{{ $order->order_number }}</span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $order->visit_type }}
                                                        @if($order->table_number) (Meja {{ $order->table_number }}) @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        @foreach($order->details as $detail)
                                                            <div>• {{ $detail->menu->name ?? 'Menu' }} <span class="text-muted">({{ $detail->qty }}x)</span></div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-success">
                                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $order->payment }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = match($order->status) {
                                                            'Completed' => 'bg-success',
                                                            'Cancelled' => 'bg-danger',
                                                            'Processing', 'Ready' => 'bg-info text-dark',
                                                            default => 'bg-warning text-dark'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    Belum ada riwayat transaksi untuk pelanggan ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($orders->hasPages())
                                <div class="mt-3">
                                    {{ $orders->links() }}
                                </div>
                            @endif
                        </div>

                        {{-- TAB 2: MENU FAVORIT --}}
                        <div class="tab-pane fade" id="favorites-pane" role="tabpanel">
                            <p class="text-muted small mb-3">Menu yang paling sering dipesan oleh {{ $customer->name }} berdasarkan akumulasi pesanan.</p>

                            <div class="row g-3">
                                @forelse($favoriteMenus as $fav)
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded d-flex align-items-center gap-3 bg-light">
                                            @if($fav->image)
                                                <img src="{{ asset('storage/' . $fav->image) }}" alt="{{ $fav->name }}" class="rounded object-fit-cover" style="width: 60px; height: 60px;">
                                            @else
                                                <div class="rounded bg-white d-flex align-items-center justify-content-center border" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-cup-hot text-muted fs-4"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1">{{ $fav->name }}</h6>
                                                <div class="small text-muted mb-1">Rp {{ number_format($fav->price, 0, ',', '.') }}</div>
                                                <div class="d-flex gap-2">
                                                    <span class="badge bg-primary">
                                                        <i class="bi bi-cart-check me-1"></i>Dipesan {{ $fav->total_qty }}x
                                                    </span>
                                                    <span class="badge bg-success">
                                                        Total: Rp {{ number_format($fav->total_spent, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-4">
                                        <i class="bi bi-cup-hot display-5 d-block mb-2 opacity-25"></i>
                                        Pelanggan ini belum memiliki riwayat menu favorit.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- TAB 3: RIWAYAT MUTASI POIN --}}
                        <div class="tab-pane fade" id="points-pane" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Tipe</th>
                                            <th>Mutasi Poin</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pointLogs as $log)
                                            <tr>
                                                <td><small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small></td>
                                                <td>
                                                    @if($log->type === 'earned')
                                                        <span class="badge bg-success">Perolehan Poin</span>
                                                    @elseif($log->type === 'redeemed')
                                                        <span class="badge bg-danger">Penukaran Hadiah</span>
                                                    @else
                                                        <span class="badge bg-secondary">Penyesuaian Manual</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="fw-bold fs-6 {{ $log->points > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $log->points > 0 ? '+' : '' }}{{ number_format($log->points) }} Poin
                                                    </span>
                                                </td>
                                                <td>{{ $log->description ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    Belum ada riwayat mutasi poin loyalty.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($pointLogs->hasPages())
                                <div class="mt-3">
                                    {{ $pointLogs->links() }}
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

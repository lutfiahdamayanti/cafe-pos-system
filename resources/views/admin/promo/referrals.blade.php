@extends('layouts.admin')
@section('title', 'Referral Program (Member-Get-Member)')
@section('content')

<div class="container-fluid">

    {{-- HEADER & ACTIONS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger bg-opacity-10 text-danger p-2 rounded-3 fs-5">
                <i class="bi bi-person-plus-fill"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Referral Program (Member-Get-Member)</h4>
                <small class="text-muted">Dorong pertumbuhan pelanggan baru melalui program ajak teman dengan reward poin ganda untuk pengajak dan teman baru.</small>
            </div>
        </div>

        <button type="button" class="btn btn-danger text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#registerReferralModal">
            <i class="bi bi-person-check-fill me-1"></i> Daftarkan Member via Referral
        </button>
    </div>

    {{-- KPI STATISTIK REFERRAL --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Referral Sukses</span>
                    <i class="bi bi-share-fill fs-4 text-danger"></i>
                </div>
                <h3 class="mb-0 fw-bold text-danger">{{ number_format($totalReferrals) }}x</h3>
                <small class="text-muted">Ajak teman berhasil</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Member Baru via Referral</span>
                    <i class="bi bi-people-fill fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalReferrals) }} Orang</h3>
                <small class="text-muted">Pelanggan baru terdaftar</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Poin Reward Dibagikan</span>
                    <i class="bi bi-coin fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ number_format($totalReferralPointsGiven) }} Pts</h3>
                <small class="text-muted">Total bonus loyalty referral</small>
            </div>
        </div>
    </div>

    {{-- SKEMA REWARD REFERRAL BANNER --}}
    <div class="alert alert-light border shadow-sm d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle">
                <i class="bi bi-gift-fill fs-3"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1">Skema Bonus Referral Aktif:</h6>
                <div class="small text-muted">
                    • <strong>Pengajak (Referrer)</strong>: Mendapatkan <span class="badge bg-warning text-dark">+50 Poin</span> setiap teman berhasil mendaftar.<br>
                    • <strong>Teman Baru (Referee)</strong>: Langsung mendapatkan <span class="badge bg-success text-white">+25 Welcome Poin</span> saat pendaftaran pertama.
                </div>
            </div>
        </div>
        <span class="badge bg-success px-3 py-2">Reward Otomatis Aktif</span>
    </div>

    <div class="row g-4 mb-4">
        {{-- TOP REFERRERS LEADERBOARD --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-trophy-fill text-warning me-2"></i>Top Member Paling Banyak Mengajak
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($topReferrers as $idx => $tr)
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge rounded-circle p-2 {{ $idx == 0 ? 'bg-warning text-dark' : ($idx == 1 ? 'bg-secondary text-white' : 'bg-dark text-white') }}" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        {{ $idx + 1 }}
                                    </span>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $tr->name }}</div>
                                        <small class="text-muted">{{ $tr->phone }} • {{ $tr->tier }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-danger px-2 py-1 fs-6">
                                    {{ $tr->referrals_count }} Teman Diajak
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">Belum ada member yang mengajak teman.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- DAFTAR KODE REFERRAL MEMBER & SHARE WA --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-qr-code text-primary me-2"></i>Direktori Kode Referral Member & Tautan WhatsApp
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-3">Member</th>
                                    <th>Kode Referral Unik</th>
                                    <th>Saldo Poin</th>
                                    <th class="text-end pe-3">Bagikan Ajakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $cust)
                                    @php
                                        $refCode = $cust->getReferralCode();
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $cust->phone);
                                        $waPhone = str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone;
                                        $msg = urlencode("Halo! Yuk ngopi bareng di Cafe POS ☕ Gunakan kode referral saya [{$refCode}] saat mendaftar/pesan untuk dapatkan bonus 25 Poin Loyalty & promo spesial!");
                                        $waUrl = "https://wa.me/{$waPhone}?text={$msg}";
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold">{{ $cust->name }}</div>
                                            <small class="text-muted">{{ $cust->phone }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark font-monospace border px-2 py-1 fs-6">
                                                {{ $refCode }}
                                            </span>
                                            <button type="button" class="btn btn-sm btn-link text-muted p-0 ms-1" onclick="navigator.clipboard.writeText('{{ $refCode }}'); alert('Kode referral {{ $refCode }} disalin!');" title="Salin Kode">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </td>
                                        <td><span class="fw-bold text-warning">{{ number_format($cust->points) }} Pts</span></td>
                                        <td class="text-end pe-3">
                                            <a href="{{ $waUrl }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-whatsapp me-1"></i> Kirim WA
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL RIWAYAT REFERRAL --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i>Log Riwayat Referral Pelanggan
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Waktu</th>
                            <th>Pengajak (Referrer)</th>
                            <th>Member Baru Diajak (Referee)</th>
                            <th>Bonus Pengajak</th>
                            <th>Bonus Member Baru</th>
                            <th>Status Reward</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $ref)
                            <tr>
                                <td class="ps-3"><small class="text-muted">{{ $ref->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $ref->referrer->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $ref->referrer->phone ?? '' }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $ref->referee->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $ref->referee->phone ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-25 text-dark fw-bold">
                                        +{{ $ref->referrer_points_rewarded }} Pts
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-25 text-success fw-bold">
                                        +{{ $ref->referee_points_rewarded }} Pts
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>{{ ucfirst($ref->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat referral tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($referrals->hasPages())
                <div class="p-3 border-top">
                    {{ $referrals->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

{{-- MODAL DAFTARKAN REFERRAL --}}
<div class="modal fade" id="registerReferralModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-check-fill me-2 text-danger"></i>Daftarkan Member via Referral</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.promo.referrals.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Referral Pengajak <span class="text-danger">*</span></label>
                        <select name="referrer_code" class="form-select font-monospace" required>
                            <option value="">-- Pilih Kode Referral Pengajak --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->getReferralCode() }}">
                                    {{ $c->getReferralCode() }} ({{ $c->name }} - {{ $c->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3 text-primary">Data Teman Baru (Referee):</h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap Teman <span class="text-danger">*</span></label>
                        <input type="text" name="referee_name" class="form-control" placeholder="Contoh: Dion Pratama" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="text" name="referee_phone" class="form-control" placeholder="Contoh: 081299887766" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Email (Opsional)</label>
                        <input type="email" name="referee_email" class="form-control" placeholder="Contoh: dion@gmail.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Proses & Berikan Bonus Poin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

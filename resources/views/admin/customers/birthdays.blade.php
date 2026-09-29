@extends('layouts.admin')
@section('title', 'Birthday Reminder Pelanggan')
@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke CRM
            </a>
            <div>
                <h4 class="fw-bold mb-1"><i class="bi bi-cake2-fill text-warning me-2"></i>Birthday Reminder & Loyalty Greetings</h4>
                <p class="text-muted mb-0">Pantau ulang tahun pelanggan setia dan kirimkan ucapan serta penawaran spesial via WhatsApp.</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <span class="badge bg-light text-dark border p-2 fs-6">
                <i class="bi bi-calendar3 me-1 text-primary"></i> Hari ini: {{ $today->isoFormat('D MMMM Y') }}
            </span>
        </div>
    </div>

    {{-- SECTION 1: ULANG TAHUN HARI INI --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning bg-opacity-25 py-3 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-gift-fill text-danger me-2"></i>Ulang Tahun Hari Ini ({{ $birthdaysToday->count() }})
                </h5>
                <span class="badge bg-danger">Prioritas Hari Ini</span>
            </div>
        </div>
        <div class="card-body">
            @if($birthdaysToday->count() > 0)
                <div class="row g-3">
                    @foreach($birthdaysToday as $cust)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $cust->phone);
                            $waPhone = str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone;
                            $msg = urlencode("Selamat Ulang Tahun Kak {$cust->name}! 🎂🎉\n\nTerima kasih telah menjadi bagian dari keluarga Cafe POS (Tier: {$cust->tier}). Di hari spesial ini, kami mengundang Kakak untuk menikmati traktiran spesial: Diskon 20% + Free Birthday Pastry/Drink untuk kunjungan hari ini!\n\nTunjukkan pesan ini kepada barista/kasir kami ya. Have a wonderful birthday! ☕✨");
                            $waUrl = "https://wa.me/{$waPhone}?text={$msg}";
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 border rounded shadow-sm bg-white position-relative">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0 text-dark">{{ $cust->name }}</h5>
                                    <span class="badge {{ match($cust->tier) {'Platinum'=>'badge-tier-platinum','Gold'=>'badge-tier-gold','Silver'=>'badge-tier-silver',default=>'badge-tier-bronze'} }}">
                                        {{ $cust->tier }}
                                    </span>
                                </div>
                                <div class="small text-muted mb-2">
                                    <i class="bi bi-whatsapp text-success me-1"></i>{{ $cust->phone }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mb-3 small">
                                    <span>Poin: <strong class="text-warning">{{ number_format($cust->points) }}</strong></span>
                                    <span>Total Belanja: <strong class="text-success">Rp {{ number_format($cust->total_spending, 0, ',', '.') }}</strong></span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ $waUrl }}" target="_blank" class="btn btn-success btn-sm w-100 fw-semibold">
                                        <i class="bi bi-whatsapp me-1"></i> Kirim Ucapan & Voucher
                                    </a>
                                    <a href="{{ route('admin.customers.show', $cust->id) }}" class="btn btn-outline-secondary btn-sm" title="Lihat Profil">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-emoji-smile display-5 d-block mb-2 opacity-25"></i>
                    Tidak ada pelanggan yang berulang tahun hari ini.
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION 2: 7 HARI MENDATANG --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i>Ulang Tahun 7 Hari Kedepan ({{ $upcomingBirthdays->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($upcomingBirthdays->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Ultah</th>
                                <th>Pelanggan</th>
                                <th>Tier</th>
                                <th>Poin</th>
                                <th>Kunjungan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingBirthdays as $cust)
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $cust->phone);
                                    $waPhone = str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone;
                                    $msgEarly = urlencode("Halo Kak {$cust->name}! Sebentar lagi Kakak berulang tahun nih 🎉 Kami dari Cafe POS ingin menyapa lebih awal dan menyiapkan promo spesial untuk hari bahagia Kakak nanti. Sampai jumpa di kafe!");
                                    $waUrl = "https://wa.me/{$waPhone}?text={$msgEarly}";
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border fs-6">
                                            <i class="bi bi-calendar-event me-1 text-primary"></i>
                                            {{ $cust->birth_date->format('d M') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $cust->name }}</div>
                                        <small class="text-muted">{{ $cust->phone }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ match($cust->tier) {'Platinum'=>'badge-tier-platinum','Gold'=>'badge-tier-gold','Silver'=>'badge-tier-silver',default=>'badge-tier-bronze'} }}">
                                            {{ $cust->tier }}
                                        </span>
                                    </td>
                                    <td><span class="fw-bold text-warning">{{ number_format($cust->points) }}</span></td>
                                    <td>{{ $cust->visit_count }}x</td>
                                    <td class="text-end">
                                        <a href="{{ $waUrl }}" target="_blank" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-whatsapp me-1"></i> Sapa Awal
                                        </a>
                                        <a href="{{ route('admin.customers.show', $cust->id) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    Tidak ada pelanggan yang berulang tahun dalam 7 hari kedepan.
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION 3: SEMUA ULANG TAHUN BULAN INI --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-calendar-month text-info me-2"></i>Daftar Ulang Tahun Bulan {{ $today->isoFormat('MMMM') }} ({{ $birthdaysThisMonth->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($birthdaysThisMonth->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hari / Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Tier</th>
                                <th>Total Belanja</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($birthdaysThisMonth as $cust)
                                <tr>
                                    <td>
                                        <strong>{{ $cust->birth_date->format('d F') }}</strong>
                                        @if($cust->isBirthdayToday())
                                            <span class="badge bg-danger ms-1">🎂 Hari Ini!</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $cust->name }}</div>
                                        <small class="text-muted">{{ $cust->phone }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ match($cust->tier) {'Platinum'=>'badge-tier-platinum','Gold'=>'badge-tier-gold','Silver'=>'badge-tier-silver',default=>'badge-tier-bronze'} }}">
                                            {{ $cust->tier }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($cust->total_spending, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.customers.show', $cust->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i> Lihat Profil
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    Belum ada pelanggan dengan data tanggal lahir di bulan ini.
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

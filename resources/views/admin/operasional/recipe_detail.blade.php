@extends('layouts.admin')
@section('title', 'Resep: ' . $menu->name)
@section('content')

<div class="container-fluid">

    {{-- HEADER & NAVIGATION --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.operasional.resep') }}" class="btn btn-outline-secondary btn-sm me-1">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div>
                <h4 class="fw-bold mb-0">Kartu Resep & SOP: {{ $menu->name }}</h4>
                <small class="text-muted">Kategori: {{ $menu->category->name ?? 'Menu' }} • Standar Barista & Kitchen Station</small>
            </div>
        </div>

        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Kartu Resep Barista
        </button>
    </div>

    <div class="row g-4 mb-4">
        {{-- PROFIL SAJIAN & METRIK FINANSIAL --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" class="rounded shadow-sm mb-3" style="width: 140px; height: 140px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/140x140?text=Menu'">
                    @else
                        <div class="bg-light rounded mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                            <i class="bi bi-cup-hot text-muted fs-1"></i>
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1">{{ $menu->name }}</h5>
                    <span class="badge bg-light text-dark border">{{ $menu->category->name ?? 'General' }}</span>

                    <hr>

                    <div class="row g-2 text-start small">
                        <div class="col-6">
                            <span class="text-muted d-block">Harga Jual:</span>
                            <strong class="text-dark fs-6">Rp {{ number_format($menu->price, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-6 text-end">
                            <span class="text-muted d-block">Estimasi HPP:</span>
                            <strong class="text-danger fs-6">Rp {{ number_format($effectiveCost, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Gross Margin:</span>
                            <span class="badge bg-success fs-6">{{ $marginPct }}%</span>
                        </div>
                        <div class="col-6 text-end">
                            <span class="text-muted d-block">Waktu Pembuatan:</span>
                            <strong class="text-dark">{{ $menu->preparation_time ?? '3-5' }} Menit</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-thermometer-half text-danger me-2"></i> Standar Penyajian</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted small d-block">Suhu & Cup Saji:</span>
                        <strong class="text-dark">{{ $menu->serving_temp ?? 'Panas / Dingin Sesuai Pesanan' }}</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small d-block">Profil Rasa:</span>
                        <strong class="text-primary">{{ $menu->taste_notes ?? 'Seimbang, Khas Racikan Kopi Kita' }}</strong>
                    </div>
                    @if($menu->allergen)
                        <div>
                            <span class="text-muted small d-block">Peringatan Alergen:</span>
                            <span class="badge bg-danger bg-opacity-10 text-danger border">{{ $menu->allergen }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RESEP KOMPOSISI & LANGKAH PEMBUATAN --}}
        <div class="col-md-8">
            {{-- KOMPOSISI BAHAN --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-basket me-2 text-primary"></i> Komposisi Takaran Bahan Baku per Porsi</h6>
                    <span class="badge bg-light text-dark">{{ $menu->recipeIngredients->count() }} Bahan</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Nama Bahan Baku</th>
                                    <th>Takaran Standar</th>
                                    <th class="text-end pe-3">Estimasi Biaya Bahan (HPP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menu->recipeIngredients as $ing)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark">{{ $ing->name }}</td>
                                        <td>
                                            <span class="badge bg-light text-primary border fs-6">
                                                {{ $ing->quantity }} {{ $ing->unit }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3 text-danger fw-semibold">
                                            Rp {{ number_format($ing->cost, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">Belum ada rincian bahan baku.</td>
                                    </tr>
                                @endforelse
                                <tr class="table-light">
                                    <th class="ps-3">TOTAL HPP BAHAN RESEP</th>
                                    <th></th>
                                    <th class="text-end pe-3 text-danger fs-6">
                                        Rp {{ number_format($effectiveCost, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- LANGKAH PEMBUATAN / BREWING SOP --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-list-ol me-2 text-warning"></i> Panduan Langkah Pembuatan (SOP Barista / Kitchen)</h6>
                </div>
                <div class="card-body p-4">
                    @if($menu->recipe_steps)
                        <div class="p-3 bg-light rounded text-dark lh-lg" style="white-space: pre-line; font-size: 0.95rem;">
                            {{ $menu->recipe_steps }}
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i> Belum ada panduan langkah pembuatan (SOP) untuk menu ini. Silakan tambahkan pada halaman daftar resep.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

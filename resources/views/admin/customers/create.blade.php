@extends('layouts.admin')
@section('title', 'Tambah Pelanggan Baru - CRM')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h4 class="fw-bold mb-0">Tambah Pelanggan Baru (CRM)</h4>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.customers.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Jessica Angeline" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Contoh: 081234567890" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Contoh: jessica@gmail.com" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir <small class="text-muted">(Untuk Birthday Reminder)</small></label>
                                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Membership Tier <span class="text-danger">*</span></label>
                                <select name="tier" class="form-select @error('tier') is-invalid @enderror" required>
                                    <option value="Bronze" {{ old('tier', 'Bronze') == 'Bronze' ? 'selected' : '' }}>Bronze (Default / &lt; Rp 500rb)</option>
                                    <option value="Silver" {{ old('tier') == 'Silver' ? 'selected' : '' }}>Silver (&ge; Rp 500rb)</option>
                                    <option value="Gold" {{ old('tier') == 'Gold' ? 'selected' : '' }}>Gold (&ge; Rp 1,5 Juta)</option>
                                    <option value="Platinum" {{ old('tier') == 'Platinum' ? 'selected' : '' }}>Platinum (&ge; Rp 3 Juta)</option>
                                </select>
                                @error('tier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Saldo Poin Awal</label>
                                <input type="number" name="initial_points" class="form-control @error('initial_points') is-invalid @enderror" placeholder="0" value="{{ old('initial_points', 0) }}" min="0">
                                @error('initial_points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Domisili</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="Alamat pelanggan...">{{ old('address') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Catatan Preferensi / Alergi / Kebiasaan</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Suka less sugar, alergi kacang, sering pesan tempat di outdoor...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Simpan Pelanggan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

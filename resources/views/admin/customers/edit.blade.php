@extends('layouts.admin')
@section('title', 'Edit Pelanggan: ' . $customer->name)
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h4 class="fw-bold mb-0">Edit Data Pelanggan</h4>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $customer->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir <small class="text-muted">(Untuk Birthday Reminder)</small></label>
                                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $customer->birth_date ? $customer->birth_date->format('Y-m-d') : '') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Membership Tier <span class="text-danger">*</span></label>
                                <select name="tier" class="form-select @error('tier') is-invalid @enderror" required>
                                    <option value="Bronze" {{ old('tier', $customer->tier) == 'Bronze' ? 'selected' : '' }}>Bronze (&lt; Rp 500rb)</option>
                                    <option value="Silver" {{ old('tier', $customer->tier) == 'Silver' ? 'selected' : '' }}>Silver (&ge; Rp 500rb)</option>
                                    <option value="Gold" {{ old('tier', $customer->tier) == 'Gold' ? 'selected' : '' }}>Gold (&ge; Rp 1,5 Juta)</option>
                                    <option value="Platinum" {{ old('tier', $customer->tier) == 'Platinum' ? 'selected' : '' }}>Platinum (&ge; Rp 3 Juta)</option>
                                </select>
                                @error('tier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Saldo Poin Saat Ini</label>
                                <input type="text" class="form-control bg-light" value="{{ number_format($customer->points) }} Poin" disabled readonly>
                                <small class="text-muted">Untuk menambah / menukar poin gunakan fitur di halaman detail profil.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Domisili</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Catatan Preferensi / Alergi / Kebiasaan</label>
                                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $customer->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Perbarui Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

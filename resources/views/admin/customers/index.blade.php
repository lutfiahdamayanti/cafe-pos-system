@extends('layouts.admin')
@section('title','Database Pelanggan')
@section('content')

<div class="container-fluid">
    
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1">Data Pelanggan</h4>
            <p class="text-muted mb-0">
                Data pelanggan yang pernah melakukan pemesanan.
            </p>
        </div>
        <a href="{{ route('admin.customers.export.csv') }}"
           class="btn btn-success">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>
            Ekspor CSV
        </a>
    </div>

    {{-- DATABASE CUSTOMER --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Total Kunjungan</th>
                        <th>Total Belanja</th>
                        <th>Terakhir Datang</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $customer->name }}
                        </td>

                        <td>
                            {{ $customer->phone }}
                        </td>

                        <td>
                            {{ $customer->visit_count }}
                        </td>

                        <td>
                            Rp {{ number_format($customer->total_spending,0,',','.') }}
                        </td>

                        <td>
                            {{ $customer->last_visit
                                ? \Carbon\Carbon::parse($customer->last_visit)->format('d M Y H:i')
                                : '-' }}
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data customer.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
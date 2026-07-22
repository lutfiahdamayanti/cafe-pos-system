@extends('layouts.admin')
@section('title','Database Customer')
@section('content')

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Data Customer</h5>

            <table class="table table-bordered">
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
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->visit_count }}</td>
                        <td>
                            Rp {{ number_format($customer->total_spending,0,',','.') }}
                        </td>
                        <td>
                            {{ $customer->last_visit }}
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
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
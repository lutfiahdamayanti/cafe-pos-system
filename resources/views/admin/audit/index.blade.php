@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Audit Logs
    </h2>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>No</th>

                        <th>User</th>

                        <th>Activity</th>

                        <th>Waktu</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($logs as $log)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $log->user }}</td>

                        <td>{{ $log->activity }}</td>

                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center">

                            Belum ada aktivitas.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

            <div class="mt-3">

                {{ $logs->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
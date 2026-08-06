@extends('layouts.admin')
@section('title','Log Audit')
@section('content')

<div class="container-fluid">
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.audit.backup') }}"
        class="btn btn-primary">
            <i class="bi bi-download"></i>
            Cadangkan Database
        </a>

        <form action="{{ route('admin.audit.restore') }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <input
                    type="file"
                    name="database"
                    class="form-control"
                    accept=".sql"
                    required>

                <button class="btn btn-danger">
                    <i class="bi bi-upload"></i>
                    Pulihkan Database
                </button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
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
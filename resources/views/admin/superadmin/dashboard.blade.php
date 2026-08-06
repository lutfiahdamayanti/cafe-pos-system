@extends('layouts.admin')
@section('title','Dashboard Super Admin')
@section('content')

<div class="container-fluid">
    {{-- HEADER --}}
    <div class="mb-5">
        <h2 class="fw-bold">
            Super Admin
        </h2>

        <p class="text-muted mb-0">
            Kelola seluruh akun yang memiliki akses ke sistem Cafe POS.
        </p>
    </div>

    {{-- STATISTIK USER --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Total User
                        </small>

                        <h2 class="fw-bold mt-2">
                            {{ $users->count() }}
                        </h2>
                    </div>
                    <i class="bi bi-people-fill fs-1 text-success"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <small class="text-muted">
                    Owner
                </small>

                <h2 class="fw-bold mt-2">
                    {{ $users->where('role','owner')->count() }}
                </h2>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <small class="text-muted">
                    Manager
                </small>

                <h2 class="fw-bold mt-2">
                    {{ $users->where('role','manager')->count() }}
                </h2>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <small class="text-muted">
                    Cashier
                </small>

                <h2 class="fw-bold mt-2">
                    {{
                        $users->where('role','cashier')->count()
                        +
                        $users->where('role','kitchen')->count()
                    }}
                </h2>
            </div>
        </div>
    </div>

    {{-- USER TERBARU --}}
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0">
                    User Terdaftar
                </h5>

                <small class="text-muted">
                    Daftar akun yang memiliki akses ke sistem.
                </small>
            </div>

            <a href="{{ route('superadmin.users.index') }}"
               class="btn btn-success rounded-pill">
                <i class="bi bi-gear-fill me-1"></i>
                Kelola User
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($users->take(5) as $user)
                    <tr>
                        <td class="fw-semibold">
                            {{ $user->name }}
                        </td>

                        <td>
                            {{ $user->email }}
                        </td>

                        <td>
                            <span class="badge
                                @if($user->role == 'super_admin')
                                    bg-danger
                                @elseif($user->role == 'owner')
                                    badge-owner
                                @elseif($user->role == 'manager')
                                    bg-primary
                                @elseif($user->role == 'cashier')
                                    bg-success
                                @endif
                            ">
                                {{ ucfirst(str_replace('_',' ', $user->role)) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
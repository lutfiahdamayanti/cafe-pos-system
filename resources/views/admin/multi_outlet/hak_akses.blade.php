@extends('layouts.admin')
@section('title', 'Multi-Outlet: Hak Akses per Cabang')
@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-3 fs-5">
                <i class="bi bi-person-badge-fill text-info"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Hak Akses per Cabang (Branch Access Control)</h4>
                <small class="text-muted">Atur pembatasan akses data kasir, dapur, dan supervisor gerai agar hanya dapat melihat outlet yang ditugaskan.</small>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Akun Pengguna</span>
                    <i class="bi bi-people fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $totalUsers }}</h3>
                <small class="text-muted">Akun staff terdaftar</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Ditugaskan ke Cabang</span>
                    <i class="bi bi-geo-alt fs-4 text-info"></i>
                </div>
                <h3 class="mb-0 fw-bold text-info">{{ $assignedUsersCount }}</h3>
                <small class="text-muted">Akses dibatasi ke 1 gerai</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Akses Semua Cabang (HQ)</span>
                    <i class="bi bi-globe fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">{{ $allBranchAccessCount }}</h3>
                <small class="text-muted">Owner / Kantor Pusat</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Gerai Aktif</span>
                    <i class="bi bi-shop fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $allOutlets->count() }}</h3>
                <small class="text-muted">Pilihan penugasan cabang</small>
            </div>
        </div>
    </div>

    {{-- FILTER ROLE & CABANG --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.multi-outlet.hak-akses') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <select name="role" class="form-select">
                        <option value="">Semua Role / Jabatan</option>
                        <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="cashier" {{ request('role') === 'cashier' ? 'selected' : '' }}>Kasir (Cashier)</option>
                        <option value="kitchen" {{ request('role') === 'kitchen' ? 'selected' : '' }}>Dapur (Kitchen)</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <select name="outlet_id" class="form-select">
                        <option value="">Semua Penugasan Cabang</option>
                        <option value="all" {{ request('outlet_id') === 'all' ? 'selected' : '' }}>🌐 Semua Cabang (Akses HQ/Pusat)</option>
                        @foreach($allOutlets as $out)
                            <option value="{{ $out->id }}" {{ request('outlet_id') == $out->id ? 'selected' : '' }}>{{ $out->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL PENGGUNA & HAK AKSES CABANG --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2 text-info"></i> Daftar Pengguna & Penugasan Gerai</h6>
            <span class="badge bg-light text-dark">{{ $users->count() }} Pengguna</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Nama Pengguna</th>
                            <th>Email Akun</th>
                            <th>Role / Peran</th>
                            <th>Penugasan Cabang</th>
                            <th>Cakupan Hak Akses</th>
                            <th class="text-end pe-3">Atur Penugasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $user->name }}</span>
                                            @if($user->id === Auth::id())
                                                <span class="badge bg-secondary font-monospace" style="font-size: 0.7rem;">Akun Anda</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span>{{ $user->email }}</span>
                                </td>
                                <td>
                                    @if($user->role === 'owner')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-crown me-1"></i> Owner</span>
                                    @elseif($user->role === 'manager')
                                        <span class="badge bg-primary"><i class="bi bi-briefcase me-1"></i> Manager</span>
                                    @elseif($user->role === 'cashier')
                                        <span class="badge bg-success"><i class="bi bi-cart me-1"></i> Cashier</span>
                                    @elseif($user->role === 'kitchen')
                                        <span class="badge bg-danger"><i class="bi bi-fire me-1"></i> Kitchen</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $user->role }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->outlet)
                                        <span class="fw-bold text-primary d-block">
                                            <i class="bi bi-shop me-1"></i> {{ $user->outlet->name }}
                                        </span>
                                        <small class="text-muted">{{ $user->outlet->code }} • {{ $user->outlet->city }}</small>
                                    @else
                                        <span class="badge bg-dark">
                                            <i class="bi bi-globe me-1"></i> Akses Semua Cabang (Kantor Pusat)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->outlet_id)
                                        <span class="badge bg-info bg-opacity-10 text-info border">
                                            <i class="bi bi-lock me-1"></i> Terkunci di 1 Cabang
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border">
                                            <i class="bi bi-unlock me-1"></i> Global / Multi-Outlet
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAssign{{ $user->id }}">
                                        <i class="bi bi-gear me-1"></i> Ubah Cabang
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL UBAH PENUGASAN CABANG --}}
                            <div class="modal fade" id="modalAssign{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.multi-outlet.hak-akses.assign', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-shield-check text-info me-2"></i> Atur Hak Akses Cabang</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="p-3 bg-light rounded mb-3">
                                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }} • Role: {{ ucfirst($user->role) }}</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Pilih Cabang Operasional Pengguna</label>
                                                    <select name="outlet_id" class="form-select">
                                                        <option value="" {{ is_null($user->outlet_id) ? 'selected' : '' }}>
                                                            🌐 Akses Semua Cabang (Kantor Pusat / Owner / Superadmin)
                                                        </option>
                                                        @foreach($allOutlets as $o)
                                                            <option value="{{ $o->id }}" {{ $user->outlet_id == $o->id ? 'selected' : '' }}>
                                                                🏬 {{ $o->name }} ({{ $o->code }} - {{ $o->city }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted mt-1 d-block">
                                                        Jika dipilih cabang spesifik, staf hanya akan melihat order dan transaksi yang terjadi di cabang tersebut.
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-info text-white"><i class="bi bi-check2 me-1"></i> Simpan Penugasan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i> Tidak ada pengguna yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

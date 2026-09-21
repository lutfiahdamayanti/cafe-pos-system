<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Cafe POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
<div class="admin-wrapper">
    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="logo"><h3>Cafe POS</h3></div>

        <ul>
            {{-- SUPER ADMIN --}}
            @if(Auth::user()->role == 'super_admin')
                <li>
                    <a href="{{ route('superadmin.users.index') }}">
                        <i class="bi bi-people-fill"></i> Kelola User
                    </a>
                </li>
            @endif

            {{-- OWNER & MANAGER --}}
            @if(in_array(Auth::user()->role,['owner','manager']))
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}">
                        <i class="bi bi-bar-chart"></i> Laporan
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.customers.index') }}">
                        <i class="bi bi-people"></i> Pelanggan
                    </a>
                </li>
            @endif

            {{-- OWNER --}}
            @if(Auth::user()->role == 'owner')
                <li>
                    <a href="{{ route('admin.menu.index') }}">
                        <i class="bi bi-cup"></i> Menu
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.category.index') }}">
                        <i class="bi bi-tags"></i> Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.audit.index') }}">
                        <i class="bi bi-shield-check"></i> Log Audit
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.qr.index') }}">
                        <i class="bi bi-qr-code"></i> QR Pemesanan
                    </a>
                </li>
            @endif

            {{-- OWNER, MANAGER, CASHIER --}}
            @if(in_array(Auth::user()->role,['owner','manager','cashier']))
                <li>
                    <a href="{{ route('admin.pos.index') }}">
                        <i class="bi bi-cart-plus"></i> Pesanan Baru
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-receipt"></i> Pesanan
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kitchen.index') }}">
                        <i class="bi bi-cup-hot"></i> Proses Pesanan
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.history') }}">
                        <i class="bi bi-clock-history"></i> Riwayat Pesanan
                    </a>
                </li>
            @endif

            <!-- {{-- OWNER, MANAGER, KITCHEN --}}
            @if(in_array(Auth::user()->role,['owner','manager','kitchen']))
                <li>
                    <a href="{{ route('admin.kitchen.index') }}">
                        <i class="bi bi-cup-hot"></i>
                        Kitchen
                    </a>
                </li>
            @endif -->
        </ul>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-4">
            @csrf
            <button class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </aside>

    {{-- Content --}}
    <main class="content">
        <nav class="topbar">
            <div>
                <h4 class="mb-0 fw-bold text-white">@yield('title')</h4>
                <small class="text-light">Cafe POS Management System</small>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light" id="darkModeToggle">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle"><i class="bi bi-person-fill"></i></div>

                        <div class="ms-3 text-start">
                            <div class="fw-semibold text-white">{{ Auth::user()->name }}</div>
                            <small class="text-light">{{ ucfirst(str_replace('_',' ',Auth::user()->role)) }}</small>
                        </div>

                        <i class="bi bi-chevron-down text-white ms-2"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown shadow border-0">
                        <li class="px-3 py-3">
                            <div class="fw-bold">{{ Auth::user()->name }}</div>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')

<script>
const toggle=document.getElementById("darkModeToggle");

if(localStorage.getItem("darkMode")==="true"){
    document.body.classList.add("dark-mode");
}

toggle.addEventListener("click",function(){
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("darkMode",document.body.classList.contains("dark-mode"));
});
</script>

</body>
</html>
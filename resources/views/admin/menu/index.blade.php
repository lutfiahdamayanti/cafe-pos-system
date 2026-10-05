@extends('layouts.admin')
@section('title', 'Daftar Menu')
@section('content')

<div class="container-fluid py-4 menu-page">
    <div class="menu-header mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="menu-title-icon"><i class="bi bi-cup-hot-fill"></i></div>
                <h2 class="fw-bold mb-0">Daftar Menu</h2>
            </div>
            <p class="text-muted mb-0">Kelola menu, harga, resep, food cost, dan stok cafe.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.menu.create') }}" class="btn btn-success menu-action-btn"><i class="bi bi-plus-circle me-1"></i> Tambah Menu</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="menu-summary-card menu-summary-green">
                <div>
                    <small>Total Menu</small>
                    <h3>{{ $menus->count() }}</h3>
                    <span>Menu terdaftar</span>
                </div>
                <div class="menu-summary-icon"><i class="bi bi-cup-hot-fill"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="menu-summary-card menu-summary-orange">
                <div>
                    <small>Stok Menipis</small>
                    <h3>{{ $menus->where('stock', '>', 0)->where('stock', '<=', 5)->count() }}</h3>
                    <span>Menu perlu diperhatikan</span>
                </div>
                <div class="menu-summary-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="menu-summary-card menu-summary-red">
                <div>
                    <small>Menu Habis</small>
                    <h3>{{ $menus->where('stock', 0)->count() }}</h3>
                    <span>Stok saat ini kosong</span>
                </div>
                <div class="menu-summary-icon"><i class="bi bi-x-circle-fill"></i></div>
            </div>
        </div>
    </div>

    <div class="card menu-table-card border-0">
        <div class="card-body p-0">
            <div class="menu-table-header">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-list-ul text-success me-2"></i>Data Menu</h5>
                    <small class="text-muted">Daftar seluruh menu cafe</small>
                </div>
                <span class="menu-total-badge">{{ $menus->count() }} Menu</span>
            </div>

            <div class="table-responsive">
                <table class="table menu-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="90">Gambar</th>
                            <th>Nama Menu</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Food Cost</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th width="260">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                        <tr>
                            <td>
                                <div class="menu-image-wrapper">
                                    @if($menu->image)
                                        <img src="{{ asset('images/'.$menu->image) }}" alt="{{ $menu->name }}" class="menu-image">
                                    @else
                                        <div class="menu-image-placeholder"><i class="bi bi-cup-hot"></i></div>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="menu-name">{{ $menu->name }}</div>
                                @if($menu->description)
                                    <small class="text-muted">{{ Str::limit($menu->description, 45) }}</small>
                                @endif
                            </td>

                            <td>
                                <span class="category-badge"><i class="bi bi-tag-fill"></i> {{ $menu->category->name ?? '-' }}</span>
                            </td>

                            <td><strong class="price-text">Rp {{ number_format($menu->price, 0, ',', '.') }}</strong></td>

                            <td><span class="food-cost-badge">Rp {{ number_format($menu->food_cost ?? 0, 0, ',', '.') }}</span></td>

                            <td>
                                @if($menu->stock == 0)
                                    <span class="stock-badge stock-empty"><i class="bi bi-x-circle-fill"></i> Habis</span>
                                @elseif($menu->stock <= 5)
                                    <span class="stock-badge stock-low"><i class="bi bi-exclamation-circle-fill"></i> {{ $menu->stock }}</span>
                                @else
                                    <span class="stock-badge stock-safe"><i class="bi bi-check-circle-fill"></i> {{ $menu->stock }}</span>
                                @endif
                            </td>

                            <td>
                                @if($menu->stock > 0)
                                    <span class="status-badge status-active"><span class="status-dot"></span> Tersedia</span>
                                @else
                                    <span class="status-badge status-inactive"><span class="status-dot"></span> Tidak Tersedia</span>
                                @endif
                            </td>

                            <td>
                                <div class="menu-actions">
                                    <a href="{{ route('admin.menu.recipe', $menu->id) }}" class="btn menu-btn-recipe" title="Lihat resep"><i class="bi bi-journal-text"></i> Resep</a>
                                    <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn menu-btn-edit" title="Edit menu"><i class="bi bi-pencil"></i> Edit</a>
                                    <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn menu-btn-delete" title="Hapus menu"><i class="bi bi-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="menu-empty">
                                    <div class="menu-empty-icon"><i class="bi bi-cup-hot"></i></div>
                                    <h5 class="fw-bold mt-3">Belum ada menu</h5>
                                    <p class="text-muted mb-3">Tambahkan menu pertama untuk cafe kamu.</p>
                                    <a href="{{ route('admin.menu.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Tambah Menu</a>
                                </div>
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
@extends('layouts.admin')
@section('title','Daftar Menu')
@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Menu</h2>
            <p class="text-muted mb-0">Kelola seluruh menu cafe.</p>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah Menu</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($menus as $menu)
                    <tr>
                        <td><img src="{{ asset('images/'.$menu->image) }}" width="70" class="rounded-3"></td>
                        <td>{{ $menu->name }}</td>
                        <td>{{ $menu->category->name }}</td>
                        <td>Rp {{ number_format($menu->price,0,',','.') }}</td>
                        <td>
                            @if($menu->stock == 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($menu->stock <= 5)
                                <span class="badge bg-warning text-dark">{{ $menu->stock }} Tersisa</span>
                            @else
                                <span class="badge bg-success">{{ $menu->stock }} Tersisa</span>
                            @endif
                        </td>
                        <td>
                            @if($menu->stock == 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($menu->stock <= 5)
                                <span class="badge bg-warning text-dark">Hampir Habis</span>
                            @else
                                <span class="badge bg-success">Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                            <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data menu.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@extends('layouts.admin')
@section('title', 'Kategori')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2"><i class="bi bi-tags-fill fs-5"></i></div>
                <h4 class="fw-bold mb-0">Daftar Kategori</h4>
            </div>
            <p class="text-muted mb-0">Kelola kategori menu cafe.</p>
        </div>
        <a href="{{ route('admin.category.create') }}" class="btn btn-success rounded-3 px-3"><i class="bi bi-plus-circle me-1"></i> Tambah Kategori</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="category-summary-card category-green">
                <div>
                    <div class="category-label">Total Kategori</div>
                    <div class="category-value">{{ $categories->count() }}</div>
                    <small class="text-muted">Kategori menu terdaftar</small>
                </div>
                <div class="category-icon"><i class="bi bi-tags-fill"></i></div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="category-summary-card category-blue">
                <div>
                    <div class="category-label">Kategori Aktif</div>
                    <div class="category-value">{{ $categories->count() }}</div>
                    <small class="text-muted">Digunakan pada menu</small>
                </div>
                <div class="category-icon"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="category-summary-card category-orange">
                <div>
                    <div class="category-label">Icon Kategori</div>
                    <div class="category-value">{{ $categories->whereNotNull('icon')->count() }}</div>
                    <small class="text-muted">Kategori memiliki icon</small>
                </div>
                <div class="category-icon"><i class="bi bi-image-fill"></i></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-2"><i class="bi bi-list-ul fs-5"></i></div>
                    <div>
                        <h5 class="fw-bold mb-0">Data Kategori</h5>
                        <small class="text-muted">Daftar kategori menu cafe</small>
                    </div>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">{{ $categories->count() }} Kategori</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4" width="80">No</th>
                            <th>Nama Kategori</th>
                            <th>Icon</th>
                            <th class="text-center" width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="px-4"><span class="category-number">{{ $loop->iteration }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="category-row-icon"><i class="bi {{ $category->icon }}"></i></div>
                                        <div>
                                            <strong>{{ $category->name }}</strong>
                                            <small class="text-muted d-block">Kategori menu</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                        <i class="bi {{ $category->icon }} me-1"></i>{{ $category->icon }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.category.edit',$category->id) }}" class="btn btn-warning btn-sm rounded-3 me-1"><i class="bi bi-pencil-square me-1"></i> Edit</a>
                                    <form action="{{ route('admin.category.destroy',$category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-3" onclick="return confirm('Yakin ingin menghapus kategori ini?')"><i class="bi bi-trash me-1"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="text-center py-5">
                                        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3"><i class="bi bi-tags fs-1 text-secondary"></i></div>
                                        <h5 class="fw-bold">Belum Ada Kategori</h5>
                                        <p class="text-muted mb-3">Belum ada kategori menu yang dibuat.</p>
                                        <a href="{{ route('admin.category.create') }}" class="btn btn-success rounded-3"><i class="bi bi-plus-circle me-1"></i> Tambah Kategori</a>
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
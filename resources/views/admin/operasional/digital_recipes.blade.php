@extends('layouts.admin')
@section('title', 'Operasional: Digital Recipe Management')
@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                <i class="bi bi-book-half text-warning"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-0">Digital Recipe Management & SOP Pembuatan</h4>
                <small class="text-muted">Standarisasi resep digital, takaran bahan baku (gram/ml), panduan brewing step-by-step, dan konsistensi rasa antar cabang.</small>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Buku Resep Kafe
        </button>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- SUMMARY KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Varian Menu</span>
                    <i class="bi bi-cup-hot fs-4 text-primary"></i>
                </div>
                <h3 class="mb-0 fw-bold text-primary">{{ $totalMenus }} Menu</h3>
                <small class="text-muted">Katalog menu aktif</small>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Resep Digital Lengkap</span>
                    <i class="bi bi-journal-check fs-4 text-success"></i>
                </div>
                <h3 class="mb-0 fw-bold text-success">{{ $completeRecipesCount }}</h3>
                <small class="text-muted">Lengkap bahan & langkah SOP</small>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="dashboard-card p-3 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Rata-rata Biaya Bahan</span>
                    <i class="bi bi-calculator fs-4 text-warning"></i>
                </div>
                <h3 class="mb-0 fw-bold text-warning">Rp {{ number_format($avgRecipeCost, 0, ',', '.') }}</h3>
                <small class="text-muted">HPP bahan per porsi</small>
            </div>
        </div>
    </div>

    {{-- FILTER KATEGORI & PENCARIAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.operasional.resep') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama menu, rasa, atau bahan..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori Menu</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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

    {{-- TABEL RESEP DIGITAL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-warning"></i> Katalog Resep & Standar Operasional Prosedur (SOP)</h6>
            <span class="badge bg-light text-dark">{{ $menus->count() }} Menu</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Menu</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th>Estimasi Biaya Bahan</th>
                            <th>Margin</th>
                            <th>Suhu & Karakter Rasa</th>
                            <th>Status SOP</th>
                            <th class="text-end pe-3">Detail & Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        @if($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" class="rounded me-2" style="width: 42px; height: 42px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/42x42?text=Menu'">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 42px; height: 42px;">
                                                <i class="bi bi-cup-hot text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $menu->name }}</span>
                                            <small class="text-muted">{{ $menu->recipeIngredients->count() }} Bahan Terpakai</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $menu->category->name ?? 'Menu' }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold">Rp {{ number_format($menu->calculated_cost, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $menu->margin_pct >= 60 ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $menu->margin_pct }}%
                                    </span>
                                </td>
                                <td>
                                    <small class="fw-semibold text-primary d-block">{{ $menu->serving_temp ?? 'Sesuai Pesanan' }}</small>
                                    <small class="text-muted d-block" style="max-width: 200px;">{{ $menu->taste_notes ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($menu->has_sop)
                                        <span class="badge bg-success bg-opacity-10 text-success border">
                                            <i class="bi bi-check-circle-fill me-1"></i> SOP Lengkap
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum Lengkap
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.operasional.resep.show', $menu) }}" class="btn btn-outline-secondary" title="Lihat Kartu Resep">
                                            <i class="bi bi-eye me-1"></i> Resep
                                        </a>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditRecipe{{ $menu->id }}" title="Edit SOP & Bahan">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT RECIPE & INGREDIENTS --}}
                            <div class="modal fade" id="modalEditRecipe{{ $menu->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.operasional.resep.update', $menu) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-book-half text-warning me-2"></i> Edit Resep & SOP: {{ $menu->name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Suhu & Wadah Saji</label>
                                                        <input type="text" name="serving_temp" class="form-control" value="{{ $menu->serving_temp }}" placeholder="Contoh: Iced (4°C) / Cup 16oz">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold">Standar Karakter Rasa (Taste Profile)</label>
                                                        <input type="text" name="taste_notes" class="form-control" value="{{ $menu->taste_notes }}" placeholder="Contoh: Sweet, Nutty, Creamy Milk">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-bold">Panduan Langkah Pembuatan / Brewing SOP (Step-by-Step)</label>
                                                        <textarea name="recipe_steps" class="form-control" rows="5" placeholder="1. Timbang bahan...&#10;2. Ekstraksi espresso...&#10;3. Tuang susu dan beri topping...">{!! $menu->recipe_steps !!}</textarea>
                                                        <small class="text-muted">Tulis langkah pembuatan yang jelas agar dipatuhi barista & tim kitchen di semua cabang.</small>
                                                    </div>
                                                </div>

                                                <h6 class="fw-bold border-bottom pb-2 mb-3">Komposisi Takaran Bahan Baku</h6>

                                                <div id="recipeIngredientsContainer{{ $menu->id }}">
                                                    @forelse($menu->recipeIngredients as $idx => $ing)
                                                        <div class="row g-2 align-items-center mb-2 ingredient-row">
                                                            <div class="col-md-5">
                                                                <input type="text" name="ingredients[{{ $idx }}][name]" class="form-control form-control-sm" value="{{ $ing->name }}" placeholder="Nama Bahan" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="number" step="0.01" name="ingredients[{{ $idx }}][quantity]" class="form-control form-control-sm" value="{{ $ing->quantity }}" placeholder="Takaran" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="text" name="ingredients[{{ $idx }}][unit]" class="form-control form-control-sm" value="{{ $ing->unit }}" placeholder="gram/ml/pcs" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="number" step="1" name="ingredients[{{ $idx }}][cost]" class="form-control form-control-sm" value="{{ (int)$ing->cost }}" placeholder="HPP (Rp)">
                                                            </div>
                                                            <div class="col-md-1 text-center">
                                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.ingredient-row').remove()">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="row g-2 align-items-center mb-2 ingredient-row">
                                                            <div class="col-md-5">
                                                                <input type="text" name="ingredients[0][name]" class="form-control form-control-sm" placeholder="Nama Bahan (misal: Biji Kopi House Blend)" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="number" step="0.01" name="ingredients[0][quantity]" class="form-control form-control-sm" placeholder="18" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="text" name="ingredients[0][unit]" class="form-control form-control-sm" placeholder="gram/ml/pcs" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="number" step="1" name="ingredients[0][cost]" class="form-control form-control-sm" placeholder="Biaya (Rp)">
                                                            </div>
                                                            <div class="col-md-1 text-center">
                                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.ingredient-row').remove()">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforelse
                                                </div>

                                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addIngredientRow('{{ $menu->id }}')">
                                                    <i class="bi bi-plus me-1"></i> Tambah Bahan Baku
                                                </button>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan SOP & Resep</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-cup fs-2 d-block mb-2"></i> Belum ada menu terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function addIngredientRow(menuId) {
        const container = document.getElementById('recipeIngredientsContainer' + menuId);
        const count = container.querySelectorAll('.ingredient-row').length;
        const newDiv = document.createElement('div');
        newDiv.className = 'row g-2 align-items-center mb-2 ingredient-row';
        newDiv.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="ingredients[${count}][name]" class="form-control form-control-sm" placeholder="Nama Bahan" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="ingredients[${count}][quantity]" class="form-control form-control-sm" placeholder="Takaran" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="ingredients[${count}][unit]" class="form-control form-control-sm" placeholder="gram/ml/pcs" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="1" name="ingredients[${count}][cost]" class="form-control form-control-sm" placeholder="HPP (Rp)">
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.ingredient-row').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newDiv);
    }
</script>
@endpush

@endsection

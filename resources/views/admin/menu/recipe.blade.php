@extends('layouts.admin')
@section('title','Resep Menu')
@section('content')

@php
$totalFoodCost=$menu->recipeIngredients->sum('cost');
$foodCostPercentage=$menu->price>0?($totalFoodCost/$menu->price)*100:0;
@endphp

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill"><i class="bi bi-journal-text"></i> Resep Menu</span>
            </div>
            <h2 class="fw-bold mb-1">{{ $menu->name }}</h2>
            <p class="text-muted mb-0">Daftar bahan dan estimasi food cost menu.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 recipe-info-card recipe-blue">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2"><i class="bi bi-tag-fill"></i> Harga Jual</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($menu->price,0,',','.') }}</h3>
                        </div>
                        <div class="recipe-icon bg-primary-subtle text-primary"><i class="bi bi-cash-stack"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 recipe-info-card recipe-orange">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2"><i class="bi bi-calculator-fill"></i> Total Food Cost</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($totalFoodCost,0,',','.') }}</h3>
                        </div>
                        <div class="recipe-icon bg-warning-subtle text-warning"><i class="bi bi-calculator"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 recipe-info-card recipe-green">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2"><i class="bi bi-pie-chart-fill"></i> Food Cost %</p>
                            <h3 class="fw-bold mb-0">{{ number_format($foodCostPercentage,1) }}%</h3>
                        </div>
                        <div class="recipe-icon bg-success-subtle text-success"><i class="bi bi-percent"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 px-4 pt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-list-ul text-success"></i> Bahan yang Digunakan</h5>
                    <p class="text-muted small mb-0">{{ $menu->recipeIngredients->count() }} bahan dalam resep</p>
                </div>
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">{{ $menu->recipeIngredients->count() }} Bahan</span>
            </div>
        </div>

        <div class="card-body p-4">
            @if($menu->recipeIngredients->count())
                <div class="table-responsive">
                    <table class="table recipe-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="8%">#</th>
                                <th>Nama Bahan</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th class="text-end">Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menu->recipeIngredients as $index=>$ingredient)
                                <tr>
                                    <td><span class="recipe-number">{{ $index+1 }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="ingredient-icon"><i class="bi bi-box-seam"></i></div>
                                            <div>
                                                <div class="fw-semibold">{{ $ingredient->name }}</div>
                                                <small class="text-muted">Bahan resep</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border px-3 py-2">{{ $ingredient->quantity }}</span></td>
                                    <td><span class="text-muted">{{ $ingredient->unit }}</span></td>
                                    <td class="text-end"><span class="fw-semibold text-success">Rp {{ number_format($ingredient->cost,0,',','.') }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Food Cost</td>
                                <td class="text-end"><span class="fs-5 fw-bold text-success">Rp {{ number_format($totalFoodCost,0,',','.') }}</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-recipe-icon mb-3"><i class="bi bi-journal-x"></i></div>
                    <h5 class="fw-bold">Belum Ada Resep</h5>
                    <p class="text-muted mb-4">Menu ini belum memiliki bahan resep.</p>
                    <a href="{{ route('admin.menu.edit',$menu->id) }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambahkan Resep</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
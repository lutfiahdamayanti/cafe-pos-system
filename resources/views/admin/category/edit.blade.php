@extends('layouts.admin')
@section('title','Edit Kategori')
@section('content')

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4>Edit Kategori</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.category.update',$category->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Nama Kategori</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name',$category->name) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label>Icon (Bootstrap Icon)</label>

                    <input type="text"
                           name="icon"
                           class="form-control"
                           value="{{ old('icon',$category->icon) }}">
                </div>

                <button class="btn btn-success">
                    Update
                </button>

                <a href="{{ route('admin.category.index') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')

<div class="container py-4">

    <h3>Tambah Kategori</h3>

    <form action="{{ route('admin.category.store') }}"
          method="POST">

        @csrf

        <div class="mb-3">

            <label>Nama Kategori</label>

            <input
                type="text"
                name="name"
                class="form-control">

        </div>

        <div class="mb-3">

            <label>Icon</label>

            <input
                type="text"
                name="icon"
                class="form-control"
                placeholder="☕">

        </div>

        <button class="btn btn-success">

            Simpan

        </button>

    </form>

</div>

@endsection
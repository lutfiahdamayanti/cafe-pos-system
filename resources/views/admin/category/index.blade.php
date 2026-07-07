@extends('layouts.admin')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">

        <h3>Kategori</h3>

        <a href="{{ route('admin.category.create') }}"
           class="btn btn-success">

            Tambah Kategori

        </a>

    </div>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>No</th>

                <th>Nama</th>

                <th>Icon</th>

            </tr>

        </thead>

        <tbody>

            @foreach($categories as $category)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $category->name }}</td>

                <td>{{ $category->icon }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection
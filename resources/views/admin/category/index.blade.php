@extends('layouts.admin')
@section('title','Category')
@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">

        <a href="{{ route('admin.category.create') }}"
           class="btn btn-success">
           <i class="bi bi-plus-circle"></i>

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

                <td>
                    <i class="bi {{ $category->icon }} me-2"></i>
                    {{ $category->icon }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection
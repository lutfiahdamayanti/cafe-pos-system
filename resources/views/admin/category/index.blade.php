@extends('layouts.admin')
@section('title','Kategori')
@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.category.create') }}"
           class="btn btn-success">
            <i class="bi bi-plus-circle"></i>
            Tambah Kategori
        </a>
    </div>

    <table class="table table-bordered align-middle">

        <thead>
            <tr>
                <th width="70">No</th>
                <th>Nama</th>
                <th>Icon</th>
                <th width="180">Aksi</th>
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

                <td>

                    <a href="{{ route('admin.category.edit',$category->id) }}"
                       class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form action="{{ route('admin.category.destroy',$category->id) }}"
                          method="POST"
                          class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus kategori ini?')">

                            <i class="bi bi-trash"></i>

                        </button>

                    </form>

                </td>

            </tr>
            @endforeach
        </tbody>

    </table>

</div>

@endsection
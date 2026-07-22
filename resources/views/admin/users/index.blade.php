@extends('layouts.admin')

@section('title','Kelola User')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <a href="{{ route('superadmin.users.create') }}"
        class="btn btn-success">

        <i class="bi bi-plus-circle"></i>

        Tambah User

    </a>

</div>

<div class="card">

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>No</th>

<th>Nama</th>

<th>Email</th>

<th>Role</th>

<th width="170">Aksi</th>

</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $user->name }}</td>

<td>{{ $user->email }}</td>

<td>

<span class="badge bg-primary">

{{ ucfirst($user->role) }}

</span>

</td>

<td>

<a href="{{ route('superadmin.users.edit',$user) }}"
class="btn btn-warning btn-sm">

Edit

</a>

<form
action="{{ route('superadmin.users.destroy',$user) }}"
method="POST"
class="d-inline">

@csrf
@method('DELETE')

<button
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus user?')">

Hapus

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection
@extends('layouts.admin')

@section('title','Tambah User')

@section('content')

<div class="card">

<div class="card-body">

<form action="{{ route('superadmin.users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="owner">Owner</option>
            <option value="manager">Manager</option>
            <option value="cashier">Cashier</option>
            <option value="kitchen">Kitchen</option>
        </select>
    </div>

    <button class="btn btn-success">
        Simpan
    </button>

</form>
</div>

</div>

@endsection
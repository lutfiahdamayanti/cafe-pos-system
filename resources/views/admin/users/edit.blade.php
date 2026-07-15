@extends('layouts.admin')

@section('title','Edit User')

@section('content')

<div class="card">

<div class="card-body">

<form action="{{ route('superadmin.users.update',$user) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">

<label>Nama</label>

<input
type="text"
name="name"
class="form-control"
value="{{ $user->name }}">

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="{{ $user->email }}">

</div>

<div class="mb-3">

<label>Password Baru</label>

<input
type="password"
name="password"
class="form-control">

<small>Kosongkan jika tidak diganti</small>

</div>

<div class="mb-3">

<label>Role</label>

<select name="role" class="form-control">

<option value="owner" {{ $user->role=='owner'?'selected':'' }}>Owner</option>

<option value="manager" {{ $user->role=='manager'?'selected':'' }}>Manager</option>

<option value="cashier" {{ $user->role=='cashier'?'selected':'' }}>Cashier</option>

<option value="kitchen" {{ $user->role=='kitchen'?'selected':'' }}>Kitchen</option>

</select>

</div>

<button class="btn btn-primary">

Update

</button>

<a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</div>

@endsection
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
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password">
                    <button type="button" class="input-group-text" id="togglePassword">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
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
            <button class="btn btn-success">Simpan</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    const password=document.getElementById('password');
    const toggle=document.getElementById('togglePassword');
    const eye=document.getElementById('eyeIcon');
    toggle.addEventListener('click',function(){
        if(password.type==='password'){
            password.type='text';
            eye.classList.replace('bi-eye','bi-eye-slash');
        }else{
            password.type='password';
            eye.classList.replace('bi-eye-slash','bi-eye');
        }
    });
});
</script>
@endsection
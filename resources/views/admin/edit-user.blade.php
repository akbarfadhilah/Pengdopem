@extends('layouts.app')

@section('title', 'Edit User')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Edit User</h1>
        <p>Perbarui informasi akun pengguna</p>
    </div>
    <a href="{{ route('admin.users') }}" class="btn btn-outline">Kembali</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Role</label>
            <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
            <p class="text-sm text-light" style="margin-top: 0.5rem;">Role tidak dapat diubah setelah user dibuat.</p>
        </div>

        @if($user->role === 'dosen' && $user->lecturer)
        <div class="form-group">
            <label class="form-label">NIP</label>
            <input type="text" class="form-control" value="{{ $user->lecturer->nip }}" disabled>
        </div>
        @endif

        @if($user->role === 'mahasiswa' && $user->student)
        <div class="form-group">
            <label class="form-label">NIM</label>
            <input type="text" class="form-control" value="{{ $user->student->nim }}" disabled>
        </div>
        @endif

        <div class="form-actions" style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

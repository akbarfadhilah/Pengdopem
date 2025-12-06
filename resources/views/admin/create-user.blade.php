@extends('layouts.app')

@section('title', 'Tambah User Baru')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Tambah User Baru</h1>
        <p>Buat akun baru untuk Admin, Dosen, atau Mahasiswa</p>
    </div>
    <a href="{{ route('admin.users') }}" class="btn btn-outline">Kembali</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required onchange="toggleRoleFields(this.value)">
                <option value="">Pilih Role...</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin / Ka.Prodi</option>
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
            @error('role')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div id="dosen-fields" style="display: none;">
            <div class="form-group">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}">
                @error('nip')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div id="mahasiswa-fields" style="display: none;">
            <div class="form-group">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}">
                @error('nim')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan User</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleRoleFields(role) {
        document.getElementById('dosen-fields').style.display = role === 'dosen' ? 'block' : 'none';
        document.getElementById('mahasiswa-fields').style.display = role === 'mahasiswa' ? 'block' : 'none';
        
        // Update required attributes
        document.getElementById('nip').required = role === 'dosen';
        document.getElementById('nim').required = role === 'mahasiswa';
    }

    // Run on load if old value exists
    document.addEventListener('DOMContentLoaded', function() {
        const role = document.getElementById('role').value;
        if(role) toggleRoleFields(role);
    });
</script>
@endpush
@endsection

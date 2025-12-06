@extends('layouts.app')

@section('title', 'Edit Kuota Dosen')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Kuota Dosen</h1>
        <p>Perbarui jumlah kuota bimbingan untuk {{ $lecturer->user->name }}</p>
    </div>
    <a href="{{ route('admin.lecturers') }}" class="btn btn-outline">Kembali</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('admin.lecturers.quota.update', $lecturer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama Dosen</label>
            <input type="text" class="form-control" value="{{ $lecturer->user->name }}" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">NIP</label>
            <input type="text" class="form-control" value="{{ $lecturer->nip }}" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">Kuota Saat Ini</label>
            <div class="quota-info" style="display: flex; gap: 2rem; margin-bottom: 1rem; padding: 1rem; background: var(--bg-body); border-radius: var(--radius);">
                <div>
                    <span class="text-light text-sm">Terpakai</span>
                    <div class="font-medium">{{ $lecturer->used_quota }}</div>
                </div>
                <div>
                    <span class="text-light text-sm">Sisa</span>
                    <div class="font-medium">{{ $lecturer->available_quota }}</div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="quota" class="form-label">Total Kuota Baru</label>
            <input type="number" name="quota" id="quota" class="form-control @error('quota') is-invalid @enderror" value="{{ old('quota', $lecturer->quota) }}" min="0" required>
            <p class="text-sm text-light" style="margin-top: 0.5rem;">
                Mengubah kuota akan mengirimkan notifikasi otomatis kepada dosen yang bersangkutan.
            </p>
            @error('quota')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions" style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

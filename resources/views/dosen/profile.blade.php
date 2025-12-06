@extends('layouts.app')

@section('title', 'Edit Profil Dosen')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dosen-dashboard.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Profil</h1>
        <p>Perbarui informasi keahlian dan profil akademik Anda</p>
    </div>
    <a href="{{ route('dosen.dashboard') }}" class="btn btn-outline">Kembali</a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('dosen.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">NIP</label>
            <input type="text" class="form-control" value="{{ $lecturer->nip }}" disabled>
        </div>

        <div class="form-group">
            <label for="specialization" class="form-label">Spesialisasi Utama</label>
            <input type="text" name="specialization" id="specialization" class="form-control @error('specialization') is-invalid @enderror" value="{{ old('specialization', $lecturer->specialization) }}" placeholder="Contoh: Artificial Intelligence">
            @error('specialization')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="expertise" class="form-label">Bidang Keahlian (Skills)</label>
            <textarea name="expertise" id="expertise" rows="3" class="form-control @error('expertise') is-invalid @enderror" placeholder="Contoh: Machine Learning, Data Mining, Python">{{ old('expertise', $lecturer->expertise) }}</textarea>
            <p class="text-sm text-light" style="margin-top: 0.5rem;">Pisahkan dengan koma.</p>
            @error('expertise')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="bio" class="form-label">Bio Singkat</label>
            <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror" placeholder="Deskripsi singkat tentang latar belakang akademik dan penelitian Anda">{{ old('bio', $lecturer->bio) }}</textarea>
            @error('bio')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="quota" class="form-label">Target Kuota Bimbingan</label>
            <input type="number" name="quota" id="quota" class="form-control @error('quota') is-invalid @enderror" value="{{ old('quota', $lecturer->quota) }}" min="0">
            <p class="text-sm text-light" style="margin-top: 0.5rem;">
                Anda dapat mengatur target kuota sendiri. Admin juga dapat mengubah ini sewaktu-waktu.
            </p>
            @error('quota')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions" style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan Profil</button>
        </div>
    </form>
</div>
@endsection

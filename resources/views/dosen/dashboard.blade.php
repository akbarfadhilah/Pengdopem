@extends('layouts.app')

@section('title', 'Dosen Dashboard')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dosen-dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Dashboard Dosen</h1>
        <p>Selamat datang, {{ auth()->user()->name }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('dosen.profile') }}" class="btn btn-outline">
            <span>⚙️</span> Edit Profil
        </a>
    </div>
</div>

<div class="quota-card">
    <div class="quota-header">
        <h2>Status Kuota Bimbingan</h2>
        <span class="badge badge-{{ $lecturer->available_quota > 0 ? 'success' : 'danger' }}">
            {{ $lecturer->available_quota > 0 ? 'Tersedia' : 'Penuh' }}
        </span>
    </div>
    <div class="quota-progress-container">
        <div class="quota-stats">
            <div class="stat-item">
                <span class="label">Total Kuota</span>
                <span class="value">{{ $lecturer->quota }}</span>
            </div>
            <div class="stat-item">
                <span class="label">Terisi</span>
                <span class="value">{{ $lecturer->used_quota }}</span>
            </div>
            <div class="stat-item">
                <span class="label">Sisa Slot</span>
                <span class="value highlight">{{ $lecturer->available_quota }}</span>
            </div>
        </div>
        <div class="progress-bar">
            @php
                $percentage = $lecturer->quota > 0 ? ($lecturer->used_quota / $lecturer->quota) * 100 : 100;
            @endphp
            <div class="progress-fill" style="width: {{ $percentage }}%"></div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Permintaan Masuk</h2>
            @if($pendingSubmissions->count() > 0)
                <span class="badge badge-warning">{{ $pendingSubmissions->count() }} Baru</span>
            @endif
        </div>
        
        @if($pendingSubmissions->count() > 0)
            <div class="list-group">
                @foreach($pendingSubmissions->take(5) as $submission)
                <div class="list-item">
                    <div class="item-content">
                        <div class="item-title">{{ $submission->student->user->name }}</div>
                        <div class="item-subtitle">{{ Str::limit($submission->research_topic, 60) }}</div>
                        <div class="item-meta">
                            <span class="badge badge-info">Prioritas {{ $submission->priority }}</span>
                            <span class="date">{{ $submission->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="item-actions">
                        <a href="{{ route('dosen.requests') }}" class="btn btn-sm btn-outline">Review</a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-footer">
                <a href="{{ route('dosen.requests') }}" class="link-primary">Lihat Semua Permintaan &rarr;</a>
            </div>
        @else
            <div class="empty-state">
                <p>Belum ada permintaan bimbingan baru.</p>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Mahasiswa Bimbingan</h2>
        </div>
        
        @if($approvedSubmissions->count() > 0)
            <div class="list-group">
                @foreach($approvedSubmissions->take(5) as $submission)
                <div class="list-item">
                    <div class="item-content">
                        <div class="item-title">{{ $submission->student->user->name }}</div>
                        <div class="item-subtitle">{{ $submission->student->nim }}</div>
                    </div>
                    <div class="item-actions">
                        <span class="badge badge-success">Aktif</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-footer">
                <a href="{{ route('dosen.students') }}" class="link-primary">Lihat Semua Mahasiswa &rarr;</a>
            </div>
        @else
            <div class="empty-state">
                <p>Belum ada mahasiswa bimbingan.</p>
            </div>
        @endif
    </div>
</div>
@endsection

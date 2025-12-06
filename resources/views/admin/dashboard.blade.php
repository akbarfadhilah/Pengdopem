@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Dashboard Admin</h1>
        <p>Selamat datang kembali, {{ auth()->user()->name }}</p>
    </div>
    <div class="header-actions">
        <span class="date-badge">{{ now()->format('l, d F Y') }}</span>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon users">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Total Users</h3>
            <p class="stat-value">{{ $stats['total_users'] }}</p>
            <p class="stat-desc">{{ $stats['total_dosen'] }} Dosen, {{ $stats['total_mahasiswa'] }} Mahasiswa</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon submissions">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Total Pengajuan</h3>
            <p class="stat-value">{{ $stats['total_submissions'] }}</p>
            <p class="stat-desc">Semua pengajuan masuk</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon pending">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Menunggu Persetujuan</h3>
            <p class="stat-value">{{ $stats['pending_submissions'] }}</p>
            <p class="stat-desc">Perlu tindakan dosen</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon approved">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Disetujui</h3>
            <p class="stat-value">{{ $stats['approved_submissions'] }}</p>
            <p class="stat-desc">Mahasiswa mendapat pembimbing</p>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Aksi Cepat</h2>
        </div>
        <div class="quick-actions">
            <a href="{{ route('admin.users.create') }}" class="action-btn">
                <span class="icon">+</span>
                Tambah User Baru
            </a>
            <a href="{{ route('admin.lecturers') }}" class="action-btn">
                <span class="icon">✎</span>
                Kelola Kuota Dosen
            </a>
            <a href="{{ route('admin.submissions') }}" class="action-btn">
                <span class="icon">👁</span>
                Lihat Semua Pengajuan
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Informasi Sistem</h2>
        </div>
        <div class="system-info">
            <div class="info-item">
                <span class="label">Status Sistem</span>
                <span class="badge badge-success">Online</span>
            </div>
            <div class="info-item">
                <span class="label">Versi Aplikasi</span>
                <span class="value">v1.0.0</span>
            </div>
            <div class="info-item">
                <span class="label">Database</span>
                <span class="value">MySQL</span>
            </div>
        </div>
    </div>
</div>
@endsection

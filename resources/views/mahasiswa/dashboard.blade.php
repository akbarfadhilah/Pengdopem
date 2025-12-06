@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/mahasiswa-dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Dashboard Mahasiswa</h1>
        <p>Selamat datang, {{ auth()->user()->name }}</p>
    </div>
    <div class="header-actions">
        <span class="badge badge-info">{{ $student->study_program }}</span>
    </div>
</div>

<div class="status-card">
    @if($approvedAdvisor)
        <div class="status-content success">
            <div class="status-icon">🎉</div>
            <div class="status-text">
                <h2>Selamat! Anda sudah memiliki Dosen Pembimbing</h2>
                <p>Pengajuan Anda kepada <strong>{{ $approvedAdvisor->lecturer->user->name }}</strong> telah disetujui.</p>
                <div class="advisor-details">
                    <div class="detail-item">
                        <span class="label">Topik Penelitian</span>
                        <span class="value">{{ $approvedAdvisor->research_topic }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Tanggal Disetujui</span>
                        <span class="value">{{ $approvedAdvisor->approved_at->format('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="status-content info">
            <div class="status-text">
                <h2>Status Pengajuan Dosen Pembimbing</h2>
                <p>Anda dapat mengajukan maksimal 3 dosen pembimbing.</p>
                
                <div class="submission-stats">
                    <div class="stat-box">
                        <span class="count">{{ $submissions->count() }}</span>
                        <span class="label">Diajukan</span>
                    </div>
                    <div class="stat-box">
                        <span class="count">{{ 3 - $submissions->count() }}</span>
                        <span class="label">Sisa Slot</span>
                    </div>
                </div>

                @if($canSubmitMore)
                    <a href="{{ route('mahasiswa.browse') }}" class="btn btn-primary mt-4">
                        Cari & Ajukan Dosen
                    </a>
                @else
                    <div class="alert alert-warning mt-4">
                        Anda telah mencapai batas maksimal pengajuan. Tunggu respon dari dosen.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Riwayat Pengajuan</h2>
        </div>
        @if($submissions->count() > 0)
            <div class="list-group">
                @foreach($submissions as $submission)
                <div class="list-item">
                    <div class="item-content">
                        <div class="item-title">{{ $submission->lecturer->user->name }}</div>
                        <div class="item-meta">
                            <span class="badge badge-info">Prioritas {{ $submission->priority }}</span>
                            <span class="badge badge-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>
                    </div>
                    @if($submission->status === 'rejected')
                        <div class="rejection-note">
                            <strong>Alasan Penolakan:</strong> {{ $submission->rejection_reason }}
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>Belum ada pengajuan.</p>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Notifikasi Terbaru</h2>
        </div>
        @if($notifications->count() > 0)
            <div class="list-group">
                @foreach($notifications as $notification)
                <div class="list-item {{ $notification->is_read ? '' : 'unread' }}">
                    <div class="item-content">
                        <div class="item-title">{{ $notification->message }}</div>
                        <div class="item-meta">
                            <span class="date">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-footer">
                <a href="{{ route('mahasiswa.notifications') }}" class="link-primary">Lihat Semua Notifikasi &rarr;</a>
            </div>
        @else
            <div class="empty-state">
                <p>Tidak ada notifikasi baru.</p>
            </div>
        @endif
    </div>
</div>
@endsection

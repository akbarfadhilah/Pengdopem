@extends('layouts.app')

@section('title', 'Pengajuan Saya')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/mahasiswa-dashboard.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Pengajuan Saya</h1>
        <p>Status pengajuan dosen pembimbing Anda</p>
    </div>
    @if(auth()->user()->student->canSubmitMore())
        <a href="{{ route('mahasiswa.browse') }}" class="btn btn-primary">
            <span>+</span> Ajukan Baru
        </a>
    @endif
</div>

<div class="card">
    @if($submissions->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Dosen Tujuan</th>
                        <th>Topik Penelitian</th>
                        <th>Prioritas</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                    <tr>
                        <td>
                            <div class="user-cell" style="display:flex; align-items:center; gap:0.75rem;">
                                <div class="avatar-sm" style="width:32px; height:32px; background:var(--primary-light); color:white; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                    {{ substr($submission->lecturer->user->name, 0, 1) }}
                                </div>
                                <span class="font-medium">{{ $submission->lecturer->user->name }}</span>
                            </div>
                        </td>
                        <td style="max-width: 300px;">
                            <div class="text-sm">{{ $submission->research_topic }}</div>
                        </td>
                        <td>
                            <span class="badge badge-info">Pilihan {{ $submission->priority }}</span>
                        </td>
                        <td>{{ $submission->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </td>
                        <td>
                            @if($submission->status === 'rejected')
                                <span class="text-sm text-error">{{ $submission->rejection_reason }}</span>
                            @elseif($submission->status === 'approved')
                                <span class="text-sm text-success">Disetujui pada {{ $submission->approved_at->format('d/m/Y') }}</span>
                            @else
                                <span class="text-sm text-light">Menunggu respon</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <p>Belum ada pengajuan dosen pembimbing.</p>
            <a href="{{ route('mahasiswa.browse') }}" class="btn btn-outline mt-4">Cari Dosen</a>
        </div>
    @endif
</div>
@endsection

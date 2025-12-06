@extends('layouts.app')

@section('title', 'Monitoring Pengajuan')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Monitoring Pengajuan</h1>
        <p>Pantau semua pengajuan dosen pembimbing dari mahasiswa</p>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Dosen Tujuan</th>
                    <th>Topik Penelitian</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="avatar-sm">{{ substr($submission->student->user->name, 0, 1) }}</div>
                            <div>
                                <div class="font-medium">{{ $submission->student->user->name }}</div>
                                <div class="text-sm text-light">{{ $submission->student->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="font-medium">{{ $submission->lecturer->user->name }}</div>
                    </td>
                    <td>
                        <span class="text-sm">{{ Str::limit($submission->research_topic, 50) }}</span>
                    </td>
                    <td>
                        <span class="badge badge-info">Pilihan {{ $submission->priority }}</span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($submission->status) }}
                        </span>
                    </td>
                    <td>{{ $submission->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="pagination-container">
        {{ $submissions->links() }}
    </div>
</div>
@endsection

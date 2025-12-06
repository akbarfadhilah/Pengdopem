@extends('layouts.app')

@section('title', 'Mahasiswa Bimbingan')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dosen-dashboard.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Mahasiswa Bimbingan</h1>
        <p>Daftar mahasiswa yang sedang Anda bimbing</p>
    </div>
</div>

<div class="card">
    @if($students->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Topik Penelitian</th>
                        <th>Tanggal Disetujui</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $submission)
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
                        <td>{{ $submission->student->study_program }}</td>
                        <td style="max-width: 300px;">
                            <div class="text-sm">{{ $submission->research_topic }}</div>
                        </td>
                        <td>{{ $submission->approved_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge-success">Aktif</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <p>Belum ada mahasiswa bimbingan yang aktif.</p>
        </div>
    @endif
</div>
@endsection

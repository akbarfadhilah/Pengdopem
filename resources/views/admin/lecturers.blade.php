@extends('layouts.app')

@section('title', 'Kelola Kuota Dosen')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Kuota Dosen</h1>
        <p>Atur kuota bimbingan untuk setiap dosen</p>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Dosen</th>
                    <th>NIP</th>
                    <th>Keahlian</th>
                    <th>Kuota Terpakai</th>
                    <th>Total Kuota</th>
                    <th>Sisa Slot</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lecturers as $lecturer)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="avatar-sm">{{ substr($lecturer->user->name, 0, 1) }}</div>
                            <span class="font-medium">{{ $lecturer->user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $lecturer->nip }}</td>
                    <td>
                        <span class="text-sm text-light">{{ Str::limit($lecturer->expertise, 50) }}</span>
                    </td>
                    <td>{{ $lecturer->used_quota }}</td>
                    <td>
                        <span class="font-medium">{{ $lecturer->quota }}</span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $lecturer->available_quota > 0 ? 'success' : 'danger' }}">
                            {{ $lecturer->available_quota }} Slot
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.lecturers.quota', $lecturer->id) }}" class="btn btn-sm btn-outline">
                            Atur Kuota
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="pagination-container">
        {{ $lecturers->links() }}
    </div>
</div>
@endsection

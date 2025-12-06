@extends('layouts.app')

@section('title', 'Kelola Users')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Users</h1>
        <p>Manajemen data pengguna sistem (Admin, Dosen, Mahasiswa)</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <span>+</span> Tambah User
    </a>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Detail Info</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="avatar-sm">{{ substr($user->name, 0, 1) }}</div>
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'dosen' ? 'info' : 'success') }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>
                        @if($user->role === 'dosen' && $user->lecturer)
                            <span class="text-sm text-light">NIP: {{ $user->lecturer->nip }}</span>
                        @elseif($user->role === 'mahasiswa' && $user->student)
                            <span class="text-sm text-light">NIM: {{ $user->student->nim }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon edit" title="Edit">
                                ✎
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete" title="Hapus">
                                    🗑
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="pagination-container">
        {{ $users->links() }}
    </div>
</div>
@endsection

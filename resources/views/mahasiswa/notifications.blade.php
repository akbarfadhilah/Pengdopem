@extends('layouts.app')

@section('title', 'Notifikasi')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/mahasiswa-dashboard.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Notifikasi</h1>
        <p>Pemberitahuan dari sistem dan dosen</p>
    </div>
</div>

<div class="card">
    @if($notifications->count() > 0)
        <div class="list-group">
            @foreach($notifications as $notification)
            <div class="list-item {{ $notification->is_read ? '' : 'unread' }}" style="padding: 1.5rem; {{ $notification->is_read ? '' : 'background-color: #f8fafc;' }}">
                <div class="item-content" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span class="badge badge-{{ str_contains($notification->type, 'approved') ? 'success' : (str_contains($notification->type, 'rejected') ? 'danger' : 'info') }}">
                            {{ str_replace('_', ' ', ucfirst($notification->type)) }}
                        </span>
                        <span class="text-sm text-light">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="item-title" style="margin-bottom: 0.5rem;">{{ $notification->message }}</div>
                    
                    @if(!$notification->is_read)
                        <form action="{{ route('mahasiswa.notifications.read', $notification->id) }}" method="POST" style="margin-top: 1rem;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline">Tandai sudah dibaca</button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination-container">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="empty-state">
            <p>Tidak ada notifikasi.</p>
        </div>
    @endif
</div>
@endsection

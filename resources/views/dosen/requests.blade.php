@extends('layouts.app')

@section('title', 'Permintaan Bimbingan')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dosen-dashboard.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Permintaan Bimbingan</h1>
        <p>Daftar pengajuan mahasiswa yang masuk</p>
    </div>
</div>

<div class="card">
    @if($submissions->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Topik Penelitian</th>
                        <th>Prioritas</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            @if($submission->status === 'pending')
                                <div class="action-buttons">
                                    <form action="{{ route('dosen.requests.approve', $submission->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Setujui pengajuan ini?')">Terima</button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal({{ $submission->id }})">Tolak</button>
                                </div>
                            @else
                                <span class="text-sm text-light">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $submissions->links() }}
        </div>
    @else
        <div class="empty-state">
            <p>Tidak ada data permintaan bimbingan.</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
    <div class="modal-content" style="background:white; padding:2rem; border-radius:8px; width:100%; max-width:500px;">
        <h3>Tolak Pengajuan</h3>
        <p style="margin-bottom:1rem; color:#64748b;">Berikan alasan penolakan untuk mahasiswa.</p>
        
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Alasan penolakan..."></textarea>
            </div>
            <div class="form-actions" style="display:flex; justify-content:flex-end; gap:1rem;">
                <button type="button" class="btn btn-outline" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function showRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/dosen/requests/${id}/reject`;
        modal.style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }
</script>
@endpush
@endsection

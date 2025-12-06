@extends('layouts.app')

@section('title', 'Cari Dosen Pembimbing')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/mahasiswa-dashboard.css') }}">
    <style>
        .lecturer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .lecturer-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .lecturer-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        .lecturer-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .lecturer-avatar {
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .lecturer-info h3 {
            font-size: 1.1rem;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }
        .lecturer-info p {
            font-size: 0.85rem;
            color: var(--text-light);
        }
        .lecturer-body {
            flex: 1;
        }
        .expertise-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        .tag {
            background: #f1f5f9;
            color: #475569;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .quota-status {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-box {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        .search-input {
            width: 100%;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            border: 1px solid var(--border);
            font-size: 1rem;
            box-shadow: var(--shadow);
        }
        .search-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
<div class="page-header" style="text-align: center; margin-bottom: 3rem;">
    <h1 style="font-size: 2rem; margin-bottom: 1rem;">Cari Dosen Pembimbing</h1>
    <p style="color: var(--text-light);">Temukan dosen yang sesuai dengan topik penelitian Anda</p>
</div>

<form action="{{ route('mahasiswa.browse') }}" method="GET" class="search-box">
    <input type="text" name="search" class="search-input" placeholder="Cari berdasarkan keahlian atau nama..." value="{{ request('search') }}">
    <button type="submit" class="search-btn">Cari</button>
</form>

<div class="lecturer-grid">
    @forelse($lecturers as $lecturer)
    <div class="lecturer-card">
        <div class="lecturer-header">
            <div class="lecturer-avatar">{{ substr($lecturer->user->name, 0, 1) }}</div>
            <div class="lecturer-info">
                <h3>{{ $lecturer->user->name }}</h3>
                <p>{{ $lecturer->nip }}</p>
            </div>
        </div>
        
        <div class="lecturer-body">
            <div class="text-sm text-light" style="margin-bottom: 0.5rem;">Spesialisasi:</div>
            <div class="font-medium">{{ $lecturer->specialization ?? '-' }}</div>
            
            <div class="expertise-tags">
                @foreach(explode(',', $lecturer->expertise) as $skill)
                    @if(trim($skill))
                        <span class="tag">{{ trim($skill) }}</span>
                    @endif
                @endforeach
            </div>
            
            <p class="text-sm text-light" style="margin-top: 1rem;">
                {{ Str::limit($lecturer->bio, 100) }}
            </p>
        </div>

        <div class="quota-status">
            <div>
                <span class="text-sm text-light">Sisa Kuota</span>
                <div class="font-medium" style="color: {{ $lecturer->available_quota > 0 ? 'var(--success)' : 'var(--error)' }}">
                    {{ $lecturer->available_quota }} Slot
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary" 
                onclick="openSubmitModal({{ $lecturer->id }}, '{{ $lecturer->user->name }}')"
                {{ $lecturer->available_quota == 0 ? 'disabled' : '' }}>
                Ajukan
            </button>
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; text-align: center; padding: 3rem;">
        <p class="text-light">Tidak ditemukan dosen dengan kriteria tersebut.</p>
    </div>
    @endforelse
</div>

<!-- Submission Modal -->
<div id="submitModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
    <div class="modal-content" style="background:white; padding:2rem; border-radius:8px; width:100%; max-width:500px;">
        <h3>Ajukan Bimbingan</h3>
        <p style="margin-bottom:1.5rem; color:#64748b;">Kepada: <span id="lecturerName" style="font-weight:600; color:var(--primary);"></span></p>
        
        <form action="{{ route('mahasiswa.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="lecturer_id" id="lecturerId">
            
            <div class="form-group">
                <label class="form-label">Topik Penelitian</label>
                <textarea name="research_topic" class="form-control" rows="4" required placeholder="Jelaskan secara singkat topik penelitian yang ingin Anda ajukan..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Prioritas</label>
                <select name="priority" class="form-control" required>
                    <option value="1">Pilihan 1 (Utama)</option>
                    <option value="2">Pilihan 2</option>
                    <option value="3">Pilihan 3</option>
                </select>
            </div>

            <div class="form-actions" style="display:flex; justify-content:flex-end; gap:1rem; margin-top: 2rem;">
                <button type="button" class="btn btn-outline" onclick="closeSubmitModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openSubmitModal(id, name) {
        document.getElementById('lecturerId').value = id;
        document.getElementById('lecturerName').textContent = name;
        document.getElementById('submitModal').style.display = 'flex';
    }

    function closeSubmitModal() {
        document.getElementById('submitModal').style.display = 'none';
    }
</script>
@endpush
@endsection

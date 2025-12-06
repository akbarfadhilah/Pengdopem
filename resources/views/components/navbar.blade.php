<nav class="navbar">
    <div class="nav-container">
        <a href="{{ route('dashboard') }}" class="nav-brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Sistem<span>Dospem</span>
        </a>

        @auth
        <div class="nav-links">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">Users</a>
                <a href="{{ route('admin.lecturers') }}" class="nav-link {{ request()->routeIs('admin.lecturers*') ? 'active' : '' }}">Dosen & Kuota</a>
                <a href="{{ route('admin.submissions') }}" class="nav-link {{ request()->routeIs('admin.submissions*') ? 'active' : '' }}">Pengajuan</a>
            @elseif(auth()->user()->isDosen())
                <a href="{{ route('dosen.dashboard') }}" class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('dosen.requests') }}" class="nav-link {{ request()->routeIs('dosen.requests*') ? 'active' : '' }}">Permintaan</a>
                <a href="{{ route('dosen.students') }}" class="nav-link {{ request()->routeIs('dosen.students*') ? 'active' : '' }}">Mahasiswa Saya</a>
            @elseif(auth()->user()->isMahasiswa())
                <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('mahasiswa.browse') }}" class="nav-link {{ request()->routeIs('mahasiswa.browse*') ? 'active' : '' }}">Cari Dosen</a>
                <a href="{{ route('mahasiswa.submissions') }}" class="nav-link {{ request()->routeIs('mahasiswa.submissions*') ? 'active' : '' }}">Pengajuan Saya</a>
            @endif
        </div>

        <div class="user-menu">
            <div class="user-menu-btn">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ auth()->user()->role }}</span>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </div>
            <div class="user-dropdown">
                @if(auth()->user()->isDosen())
                    <a href="{{ route('dosen.profile') }}" class="dropdown-item">Edit Profil</a>
                    <div class="dropdown-divider"></div>
                @endif
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item" style="color: var(--error);">Logout</button>
                </form>
            </div>
        </div>
        @else
        <div class="nav-links">
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
        </div>
        @endauth
    </div>
</nav>

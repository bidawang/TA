@extends('layout')

@section('content')
<div class="container py-3">

    <h4 class="text-center fw-bold mb-3">Daftar Pengguna</h4>

    <!-- Search Input -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control rounded-pill shadow-sm" placeholder="Cari nama pengguna...">
    </div>

    <!-- Role Filter Tabs -->
    <ul class="nav nav-tabs justify-content-center mb-3" id="roleTabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-role="all">Semua</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-role="user">User</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-role="admin">Admin</a>
        </li>
        {{-- Developer tab dihilangkan --}}
    </ul>

    <!-- User Cards -->
    <div id="userList">
        @foreach ($users as $user)
            <div class="user-card card mb-2 shadow-sm border-0 rounded-4" data-role="{{ strtolower($user->role) }}">
                <div class="card-body d-flex align-items-start gap-3">

                    <!-- Avatar -->
                    <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle mt-1" width="50" height="50" referrerpolicy="no-referrer">

                    <!-- User Info -->
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-semibold">{{ $user->name }}</h6>
                        <small class="text-muted d-block">{{ ucfirst($user->role) }} - {{ ucfirst($user->status) }}</small>
                        <small class="text-muted d-block">{{ $user->email }}</small>
                    </div>

                    <!-- Dropdown Action -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary px-2 py-1" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item small" href="{{ route('user.show', $user->google_id) }}"><i class="bi bi-eye me-2"></i>Detail</a></li>
                            <li><a class="dropdown-item small" href="{{ route('user.edit', $user->google_id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                            <li>
                                <form action="{{ route('user.destroy', $user->google_id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger small" type="submit"><i class="bi bi-trash me-2"></i>Hapus</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Search & Filter Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const tabs = document.querySelectorAll('#roleTabs .nav-link');
        const cards = document.querySelectorAll('.user-card');

        let activeRole = 'all';

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                activeRole = this.dataset.role;
                filterUsers();
            });
        });

        searchInput.addEventListener('input', filterUsers);

        function filterUsers() {
            const keyword = searchInput.value.toLowerCase();
            cards.forEach(card => {
                const name = card.querySelector('h6').innerText.toLowerCase();
                const role = card.dataset.role;
                const matchKeyword = name.includes(keyword);
                const matchRole = (activeRole === 'all' || role === activeRole);
                card.style.display = (matchKeyword && matchRole) ? '' : 'none';
            });
        }
    });
</script>
@endsection

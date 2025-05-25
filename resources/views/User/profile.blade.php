@extends('layout')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 text-center">Profil Pengguna</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
            <!-- Avatar -->
            <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle mx-auto mx-md-0" style="width: 100px; height: 100px; object-fit: cover;" referrerpolicy="no-referrer">

            <!-- Info Section -->
            <div class="flex-grow-1">
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-1"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $user->phone ?? 'Tidak ada' }}</p>
                <p class="mb-1"><strong>Address:</strong> {{ $user->address ?? 'Tidak tersedia' }}</p>
            </div>
        </div>
    </div>

    <div class="mb-4 text-center">
        <a href="{{ route('user.edit', $user->google_id) }}" class="btn btn-warning px-4">
            <i class="bi bi-pencil-square me-1"></i> Edit Profil
        </a>
    </div>

    {{-- Untuk Admin --}}
    @if(Auth::user()->role == 'admin')
    <div class="card shadow-sm">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="mb-2 mb-md-0">Metode Pembayaran</h5>
            <a href="{{ route('wallet.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Metode
            </a>
        </div>
        <div class="card-body p-0">
            @if($wallets->isEmpty())
                <p class="text-center p-3 mb-0 text-muted">Belum ada metode pembayaran.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Provider</th>
                                <th>Kode Provider</th>
                                <th>ID Rental</th>
                                <th class="text-center" style="min-width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wallets as $wallet)
                            <tr>
                                <td class="text-capitalize">{{ $wallet->provider }}</td>
                                <td>{{ $wallet->kode_provider }}</td>
                                <td>{{ $wallet->id_rental }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="{{ route('wallet.edit', $wallet->id_wallet) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('wallet.destroy', $wallet->id_wallet) }}" method="POST" onsubmit="return confirm('Hapus metode ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

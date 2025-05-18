@extends('layout')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 text-center">Profil Pengguna</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-column flex-sm-row align-items-center gap-3">
            <!-- Avatar -->
            <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" referrerpolicy="no-referrer">

            <!-- Info Section -->
            <div class="flex-grow-1">
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-1"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $user->phone ?? 'No phone number' }}</p>
                <p class="mb-1"><strong>Address:</strong> {{ $user->address ?? 'No address provided' }}</p>
            </div>
        </div>
    </div>

    <div class="mb-4 text-center">
        <a href="{{ route('user.edit', $user->google_id) }}" class="btn btn-warning px-4">Edit Profil</a>
    </div>

    {{-- List Metode Pembayaran --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Metode Pembayaran</h5>
            <a href="{{ route('wallet.create') }}" class="btn btn-primary btn-sm">Tambah Metode</a>
        </div>
        <div class="card-body p-0">
            @if($wallets->isEmpty())
                <p class="text-center p-3 mb-0 text-muted">Belum ada metode pembayaran yang terdaftar.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Provider</th>
                                <th>Kode Provider</th>
                                <th>ID Rental</th>
                                <th class="text-center" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wallets as $wallet)
                            <tr>
                                <td class="text-capitalize">{{ $wallet->provider }}</td>
                                <td>{{ $wallet->kode_provider }}</td>
                                <td>{{ $wallet->id_rental }}</td>
                                <td class="text-center">
                                    <a href="{{ route('wallet.edit', $wallet->id_wallet) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('wallet.destroy', $wallet->id_wallet) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus metode pembayaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

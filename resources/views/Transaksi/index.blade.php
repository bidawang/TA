@extends('layout')

@section('content')
<div class="container py-3">

    <!-- Profile Section -->
     @if(Auth::user()->role=="user")
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle me-3" width="70" height="70" referrerpolicy="no-referrer">
            <div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="mb-0 text-muted small">{{ $user->email }}</p>
                <p class="mb-0 text-muted small">{{ ucfirst($user->role ?? '-') }}</p>
            </div>
        </div>
    </div>
    @endif
    <h4 class="text-center mb-4 fw-bold">Riwayat Booking</h4>

    <!-- Transaksi Loop -->
    @forelse ($trans as $t)
        <div class="card border-0 shadow-sm mb-3 rounded-4">
            <div class="card-body py-3 px-3">

                <!-- Header: Jenis, Tanggal, Total -->
                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                    @if (Auth::user()->role === 'user')
                        <span class="badge rounded-pill text-bg-light text-dark">
                            {{ ucfirst($t->jenis) }}
                        </span>
                    @endif

                    <span class="text-muted small">
                        {{ \Carbon\Carbon::parse($t->jam_mulai)->format('d M Y') }}
                    </span>
                    <span class="fw-semibold text-success small">
                        Rp {{ number_format($t->total, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Main Info -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold mb-0 small">{{ $t->rental->nama ?? 'Rental' }}</h6>
                    <div class="text-secondary small">
                        {{ \Carbon\Carbon::parse($t->jam_mulai)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($t->jam_selesai)->format('H:i') }} ({{ $t->jumlah_jam }} jam)
                    </div>
                </div>

                <!-- Keterangan (user only) -->
                @if ($t->keterangan && Auth::user()->role === 'user')
                                <div class="d-flex justify-content-between align-items-center mb-1">

                    <div class="text-secondary small mt-1">
                        {{ $t->keterangan }}
                    </div>
                @endif

                <!-- Tombol Lihat Rental (user only) -->
                @if (Auth::user()->role === 'user')
                    <div class="text-end mt-2">
                        <a href="{{ route('rental.show', $t->id_rental) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Lihat Rental
                        </a>
                    </div>
                    </div>

                @endif

                <!-- Info Penyewa (admin/developer only) -->
                @if (Auth::user()->role !== 'user')
                    <div class="text-secondary small mt-2">
                        {{ $t->user->name ?? '-' }}<br>
                        {{ $t->user->email ?? '-' }}
                    </div>
                @endif

            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            Belum ada riwayat penyewaan.
        </div>
    @endforelse

    <!-- Pagination -->
    @if ($trans->hasPages())
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">
                    Halaman {{ $trans->currentPage() }} / {{ $trans->lastPage() }}
                </small>
            </div>
            <div class="btn-group" role="group" aria-label="Pagination">
                @if (!$trans->onFirstPage())
                    <a href="{{ $trans->previousPageUrl() }}" class="btn btn-outline-secondary btn-sm">Sebelumnya</a>
                @endif

                @if ($trans->hasMorePages())
                    <a href="{{ $trans->nextPageUrl() }}" class="btn btn-outline-secondary btn-sm">Berikutnya</a>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection

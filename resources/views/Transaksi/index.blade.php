@extends('layout')

@section('content')
<div class="container py-3">

    {{-- Profil User --}}
    @if(Auth::user()->role === 'user')
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle me-3" width="70" height="70" referrerpolicy="no-referrer">
            <div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="mb-0 text-muted small">{{ $user->email }}</p>
                <p class="mb-0 text-muted small">{{ ucfirst($user->role) }}</p>
            </div>
        </div>
    </div>
    @endif
@if ($errors->has('tripay'))
    <div class="alert alert-danger">
        {{ $errors->first('tripay') }}
    </div>
@endif

    <h4 class="text-center mb-4 fw-bold">Riwayat Booking</h4>
{{-- Filter Status untuk Admin & Developer --}}
@if(in_array(Auth::user()->role, ['admin', 'developer']))
    @php
        $statusFilter = request('filter'); // ambil filter dari URL
        $statuses = ['semua' => 'Semua', 'pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'batal' => 'Batal'];
    @endphp

    <ul class="nav nav-tabs mb-3">
        @foreach ($statuses as $key => $label)
            <li class="nav-item">
<a class="nav-link {{ ($statusFilter === $key || (!$statusFilter && $key === 'semua')) ? 'active' : '' }}"
   href="{{ request()->fullUrlWithQuery(['filter' => $key === 'semua' ? null : $key]) }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
@endif

    {{-- Transaksi Loop --}}
    @forelse ($trans as $t)
    <div class="card border-0 shadow-sm mb-3 rounded-4">
        <div class="card-body px-3 py-3">

            {{-- Header --}}
            <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                <span class="badge text-bg-light text-dark">{{ ucfirst($t->jenis) }}</span>
                <span class="text-muted small">{{ \Carbon\Carbon::parse($t->jam_mulai)->format('d M Y') }}</span>
                <span class="fw-semibold text-success small">Rp {{ number_format($t->total, 0, ',', '.') }}</span>
            </div>

            {{-- Nama Rental dan Jam --}}
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                <h6 class=" mb-0">{{ $t->rental->nama ?? 'Rental' }}</h6>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($t->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($t->jam_selesai)->format('H:i') }}
                    ({{ $t->jumlah_jam }} jam)
                </small>
            </div>

            {{-- Status --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <div>
                    <strong class="small">Status:</strong>
                    <span class="badge
                        @switch($t->status)
                            @case('pending') bg-warning text-dark @break
                            @case('diterima') bg-success @break
                            @case('ditolak') bg-danger @break
                            @case('batal') bg-secondary @break
                            @default bg-info
                        @endswitch
                    ">
                        {{ ucfirst($t->status ?? 'pending') }}
                    </span>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex flex-wrap gap-2">
                    @if($t->status === 'disetujui' && $t->jenis === 'booking')
    <form action="{{ route('transaksi.bayar', $t->id_transaksi) }}" method="POST" onsubmit="return confirm('Lanjut ke pembayaran?')">
        @csrf
        <button class="btn btn-sm btn-outline-primary">Bayar Sekarang</button>
    </form>
@endif

                    @if(Auth::user()->role === 'user' && $t->status === 'pending')
           
                        <form action="{{ route('transaksi.updateStatus', $t->id_transaksi) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="batal">
                            <button class="btn btn-sm btn-outline-secondary">Batalkan</button>
                        </form>
                    @elseif(in_array(Auth::user()->role, ['admin', 'developer']) && $t->status === 'pending')
                        <form action="{{ route('transaksi.updateStatus', $t->id_transaksi) }}" method="POST" onsubmit="return confirm('Setujui transaksi ini?')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="disetujui">
                            <button class="btn btn-sm btn-success">Terima</button>
                        </form>
                        <form action="{{ route('transaksi.updateStatus', $t->id_transaksi) }}" method="POST" onsubmit="return confirm('Tolak transaksi ini?')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="ditolak">
                            <button class="btn btn-sm btn-danger">Tolak</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Keterangan --}}
            @if ($t->keterangan && Auth::user()->role === 'user')
                <div class="text-secondary small mb-2">
                    {{ $t->keterangan }}
                </div>
            @endif

            {{-- Lihat Rental (user) --}}
            @if (Auth::user()->role === 'user')
                <div class="text-end">
                    <a href="{{ route('rental.show', $t->id_rental) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Lihat Rental
                    </a>
                </div>
            @endif

            {{-- Info Penyewa (admin/dev) --}}
            @if (Auth::user()->role !== 'user')
                <div class="text-secondary small mt-2">
                    <strong>{{ $t->user->name ?? '-' }}</strong><br>
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

    {{-- Pagination --}}
    @if ($trans->hasPages())
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Halaman {{ $trans->currentPage() }} / {{ $trans->lastPage() }}
        </small>
        <div class="btn-group">
            @if (!$trans->onFirstPage())
<a href="{{ request()->fullUrlWithQuery(['page' => $trans->currentPage() - 1]) }}" class="btn btn-sm btn-outline-secondary">Sebelumnya</a>
            @endif
            @if ($trans->hasMorePages())
<a href="{{ request()->fullUrlWithQuery(['page' => $trans->currentPage() + 1]) }}" class="btn btn-sm btn-outline-secondary">Berikutnya</a>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection

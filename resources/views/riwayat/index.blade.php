@extends('layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Riwayat Transaksi</h3>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tampilan versi card untuk layar kecil --}}
    <div class="d-md-none">
        @forelse ($transaksi as $tx)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-1">Transaksi #{{ $tx->id_transaksi }}</h6>
                    <p class="mb-2 text-muted small">{{ $tx->jenis }} - {{ $tx->jam_mulai }} s/d {{ $tx->jam_selesai }}</p>
                    <p class="mb-2">Total: <strong>Rp{{ number_format($tx->total, 0, ',', '.') }}</strong></p>
                    <p class="mb-2">
                        Status lokal:
                        <span class="badge 
                            @if($tx->pembayaran?->status == 'PAID') bg-success
                            @elseif($tx->pembayaran?->status == 'UNPAID') bg-warning text-dark
                            @else bg-secondary @endif">
                            {{ $tx->pembayaran->status ?? 'Belum Dibayar' }}
                        </span>
                    </p>

                    {{-- Data dari Tripay --}}
                    @if(isset($transaksiTripay[$tx->id_transaksi]))
                        <p class="mb-2">
                            Status Tripay:
                            <span class="badge
                                @if($transaksiTripay[$tx->id_transaksi]['status'] == 'PAID') bg-success
                                @elseif($transaksiTripay[$tx->id_transaksi]['status'] == 'UNPAID') bg-warning text-dark
                                @else bg-secondary @endif">
                                {{ $transaksiTripay[$tx->id_transaksi]['status'] }}
                            </span>
                        </p>
                        <p class="mb-2">Jumlah: Rp{{ number_format($transaksiTripay[$tx->id_transaksi]['amount'], 0, ',', '.') }}</p>
                    @endif

                    <p class="mb-2">
                        Metode: <strong>{{ $tx->pembayaran->payment_method ?? '-' }}</strong><br>
                        Dibayar: {{ $tx->pembayaran->paid_at ?? '-' }}
                    </p>

                    <a href="{{ route('riwayat.show', $tx->id_transaksi) }}" class="btn btn-primary btn-sm">Detail</a>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Belum ada riwayat transaksi.</p>
        @endforelse
    </div>

    {{-- Tampilan tabel untuk layar sedang ke atas --}}
    <div class="table-responsive d-none d-md-block shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Jenis</th>
                    <th>Jam</th>
                    <th>Total</th>
                    <th>Status Lokal</th>
                    <th>Status Tripay</th>
                    <th>Jumlah Tripay</th>
                    <th>Metode</th>
                    <th>Dibayar Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $tx)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tx->jenis }}<br><small class="text-muted">{{ $tx->keterangan }}</small></td>
                        <td>{{ $tx->jam_mulai }} - {{ $tx->jam_selesai }}</td>
                        <td>Rp{{ number_format($tx->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge 
                                @if($tx->pembayaran?->status == 'PAID') bg-success
                                @elseif($tx->pembayaran?->status == 'UNPAID') bg-warning text-dark
                                @else bg-secondary @endif">
                                {{ $tx->pembayaran->status ?? 'Belum Dibayar' }}
                            </span>
                        </td>
                        <td>
                            @if(isset($transaksiTripay[$tx->id_transaksi]))
                                <span class="badge 
                                    @if($transaksiTripay[$tx->id_transaksi]['status'] == 'PAID') bg-success
                                    @elseif($transaksiTripay[$tx->id_transaksi]['status'] == 'UNPAID') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ $transaksiTripay[$tx->id_transaksi]['status'] }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(isset($transaksiTripay[$tx->id_transaksi]))
                                Rp{{ number_format($transaksiTripay[$tx->id_transaksi]['amount'], 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $tx->pembayaran->payment_method ?? '-' }}</td>
                        <td>{{ $tx->pembayaran->paid_at ?? '-' }}</td>
                        <td><a href="{{ route('riwayat.show', $tx->id_transaksi) }}" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

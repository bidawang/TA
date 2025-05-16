@extends('layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Riwayat Transaksi</h3>
        <input type="text" id="searchInput" class="form-control w-auto" placeholder="Cari transaksi..." onkeyup="filterTransaksi()">
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Mobile view --}}
    <div class="d-md-none" id="mobileView">
        @forelse ($transaksi as $tx)
            @php
                $tripay = $transaksiTripay[$tx->id_transaksi] ?? null;
                $paidAt = $tripay['paid_at'] ?? null;
            @endphp
            <div class="card mb-3 shadow-sm transaksi-item">
                <div class="card-body">
                    <h6 class="card-title mb-1">Transaksi #{{ $tx->id_transaksi }}</h6>
                    <p class="mb-2 text-muted small">{{ $tx->jenis }} - {{ $tx->jam_mulai }} s/d {{ $tx->jam_selesai }}</p>
                    <p class="mb-2">Total: <strong>Rp{{ number_format($tx->total, 0, ',', '.') }}</strong></p>

                    @if($tripay)
                        <p class="mb-2">
                            Status:
                            <span class="badge
                                @if($tripay['status'] == 'PAID') bg-success
                                @elseif($tripay['status'] == 'UNPAID') bg-warning text-dark
                                @else bg-secondary @endif">
                                {{ $tripay['status'] }}
                            </span>
                        </p>
                        <p class="mb-2">Metode: <strong>{{ $tripay['payment_method'] ?? '-' }}</strong></p>

                        @if(is_numeric($paidAt))
                            <p class="mb-2">
                                <strong>Dibayar:</strong><br>
                                {{ \Carbon\Carbon::createFromTimestamp($paidAt)->translatedFormat('l, d-m-Y') }}<br>
                                {{ \Carbon\Carbon::createFromTimestamp($paidAt)->format('H:i:s') }}
                            </p>
                        @else
                            <p class="mb-2"><strong>Dibayar:</strong> -</p>
                        @endif

                        <p class="mb-2">Jumlah: Rp{{ number_format($tripay['amount'], 0, ',', '.') }}</p>
                    @endif

                    <a href="{{ route('riwayat.show', $tx->id_transaksi) }}" class="btn btn-primary btn-sm">Detail</a>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Belum ada riwayat transaksi.</p>
        @endforelse
    </div>

    {{-- Desktop view --}}
    <div class="table-responsive d-none d-md-block shadow-sm rounded">
        <table class="table table-hover align-middle mb-0" id="desktopTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Jenis</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Metode</th>
                    <th>Dibayar Pada</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $tx)
                    @php
                        $tripay = $transaksiTripay[$tx->id_transaksi] ?? null;
                        $paidAt = $tripay['paid_at'] ?? null;
                    @endphp
                    <tr class="transaksi-item">
                        <td>
                            {{ $loop->iteration }}
                            <br>{{ $tx->setRental->name }}
                            <br>{{ $tx->setRental->rental->nama }}
                        </td>
                        <td>{{ $tx->jenis }}<br><small class="text-muted">{{ $tx->keterangan }}</small></td>
                        <td>{{ $tx->jam_mulai }} - {{ $tx->jam_selesai }}</td>
                        <td>
                            @if($tripay)
                                <span class="badge 
                                    @if($tripay['status'] == 'PAID') bg-success
                                    @elseif($tripay['status'] == 'UNPAID') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ $tripay['status'] }}
                                </span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $tripay['payment_method'] ?? '-' }}<br>
                            {{ $tripay['payment_name'] ?? '-' }}
                        </td>
                        <td>
                            @if(is_numeric($paidAt))
                                {{ \Carbon\Carbon::createFromTimestamp($paidAt)->translatedFormat('l, d-m-Y') }}<br>
                                {{ \Carbon\Carbon::createFromTimestamp($paidAt)->format('H:i:s') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($tripay)
                                Rp{{ number_format($tripay['amount'], 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('riwayat.show', $tx->id_transaksi) }}" class="btn btn-sm btn-primary">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Script pencarian --}}
<script>
    function filterTransaksi() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const items = document.querySelectorAll('.transaksi-item');

        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(input) ? '' : 'none';
        });
    }
</script>
@endsection

{{-- Mobile --}}
<div id="mobileView">
    @forelse ($transaksi as $tx)
    @php
    $tripay = $transaksiTripay[$tx->id_transaksi] ?? null;
    $paidAt = $tripay['paid_at'] ?? null;
    @endphp
    <div class="card mb-2 border-0 border-start border-4 border-primary shadow-sm transaksi-item">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="fw-semibold small">#{{ $tx->id_transaksi }}</div>
                <div class="text-muted small">
                    {{ \Carbon\Carbon::parse($tx->jam_mulai)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($tx->jam_selesai)->format('H:i') }}
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="small"><strong>Rp{{ number_format($tx->total, 0, ',', '.') }}</strong></div>
                @if($tripay)
                <div class="d-flex align-items-center gap-1">
                    <span class="badge
                        @if($tripay['status'] == 'PAID') bg-success
                        @elseif($tripay['status'] == 'UNPAID') bg-warning text-dark
                        @else bg-secondary @endif">
                        {{ $tripay['status'] }}
                    </span>
                    <span class="text-muted small">{{ $tripay['payment_method'] ?? '-' }}</span>
                </div>
                @else
                <span class="text-muted small">-</span>
                @endif
                <a href="{{ route('riwayat.show', $tx->id_transaksi) }}" class="btn btn-sm btn-outline-primary">Detail</a>
            </div>
            <div class="text-muted small">
                @if(is_numeric($paidAt))
                {{ \Carbon\Carbon::createFromTimestamp($paidAt)->format('H:i d/m/Y') }}
                @else
                -
                @endif

            </div>

        </div>
    </div>

    @empty
    <p class="text-muted text-center small">Tidak ada transaksi.</p>
    @endforelse
</div>
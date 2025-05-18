@extends('layout')

@section('content')
<div class="container py-4" style="max-width: 480px;">
    <h3 class="text-center mb-4 fw-bold">Detail Transaksi #{{ $transaksi->id_transaksi }}</h3>

    {{-- Card Data Lokal --}}
    <div class="card shadow-sm mb-4 border-primary border-start border-4 rounded-3">
        <div class="card-body px-4 py-3">
            <h5 class="card-title fw-semibold mb-3 text-primary">Data Lokal</h5>
            <ul class="list-unstyled small mb-0">
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span>Jenis</span>
                    <span class="fw-semibold text-capitalize">{{ $transaksi->jenis }}</span>
                </li>
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span>Keterangan</span>
                    <span class="text-muted">{{ $transaksi->keterangan ?? '-' }}</span>
                </li>
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span>Jam</span>
                    <span>{{ $transaksi->jam_mulai }} - {{ $transaksi->jam_selesai }} ({{ $transaksi->jumlah_jam }} jam)</span>
                </li>
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span>Total</span>
                    <span class="fw-bold text-success">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                </li>
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span>Merchant Ref</span>
                    <span class="text-muted">{{ optional($transaksi->pembayaran)->merchant_ref ?? '-' }}</span>
                </li>
                <li class="d-flex justify-content-between py-1">
                    <span>Reference</span>
                    <span class="text-muted">{{ optional($transaksi->pembayaran)->reference ?? '-' }}</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Card Data Tripay --}}
    @if($tripayData)
    <div class="card shadow-sm mb-4 border-info border-start border-4 rounded-3">
        <div class="card-body px-4 py-3 d-flex flex-column">
            <h5 class="card-title fw-semibold mb-3 text-info">Data dari Tripay</h5>
            <ul class="list-unstyled small mb-3 flex-grow-1">
                <li class="d-flex justify-content-between align-items-center py-1 border-bottom">
                    <span>Status</span>
                    <span class="badge 
                        @if(data_get($tripayData, 'status') == 'PAID') bg-success
                        @elseif(data_get($tripayData, 'status') == 'UNPAID') bg-warning text-dark
                        @else bg-secondary @endif
                        px-3 py-1 rounded-pill">
                        {{ data_get($tripayData, 'status', '-') }}
                    </span>
                </li>
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span>Method</span>
                    <span class="text-capitalize">{{ data_get($tripayData, 'payment_method', '-') }}</span>
                </li>
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span>Amount</span>
                    <span class="fw-bold text-success">Rp {{ number_format(data_get($tripayData, 'amount', 0), 0, ',', '.') }}</span>
                </li>
                <li class="py-1 border-bottom">
                    <div class="fw-semibold mb-1">Dibayar Pada</div>
                    @php $paidAt = data_get($tripayData, 'paid_at'); @endphp
                    @if($paidAt && is_numeric($paidAt))
                        <div class="small text-muted">{{ \Carbon\Carbon::createFromTimestamp($paidAt)->translatedFormat('l, d-m-Y') }}</div>
                        <div class="small text-muted">{{ \Carbon\Carbon::createFromTimestamp($paidAt)->format('H:i:s') }}</div>
                    @else
                        <div class="small text-muted">-</div>
                    @endif
                </li>
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span>Customer Name</span>
                    <span class="text-capitalize">{{ data_get($tripayData, 'customer_name', '-') }}</span>
                </li>
                <li class="d-flex justify-content-between py-1">
                    <span>Customer Email</span>
                    <span class="text-muted text-truncate" style="max-width: 180px;">{{ data_get($tripayData, 'customer_email', '-') }}</span>
                </li>
            </ul>

            @if(!empty(optional($transaksi->pembayaran)->checkout_url))
<a href="{{ $transaksi->pembayaran->checkout_url }}" target="_blank" rel="noopener"
   class="btn btn-outline-info shadow-sm d-inline-flex align-items-center gap-1 py-1 px-3"
   style="font-size: 0.875rem;">
    <i class="bi bi-receipt fs-5"></i> 
    <span>Lihat Detail</span>
</a>
@endif

        </div>
    </div>
    @else
    <div class="alert alert-warning text-center">Data transaksi dari Tripay tidak ditemukan.</div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('riwayat.index') }}" class="btn px-5 py-2 btn-outline-primary shadow-sm">
            Kembali
        </a>
    </div>
</div>
@endsection

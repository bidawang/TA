@extends('layout')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 text-center">Pendapatan Rental</h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 d-flex align-items-center">
                <i class="bi bi-shop me-2"></i> {{ $rental->nama }}
            </h5>
            <p><strong>Filter:</strong> {{ $filterLabel }}</p>
            <h4 class="text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>

            <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('pendapatan', $rental->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1 flex-sm-grow-0 text-center">
                    Hari Ini
                </a>

                <form action="{{ route('pendapatan', $rental->id) }}" method="GET" class="d-flex flex-wrap gap-2 flex-grow-1 flex-sm-grow-0">
                    <input type="date" name="start" class="form-control form-control-sm flex-grow-1" required>
                    <input type="date" name="end" class="form-control form-control-sm flex-grow-1" required>
                    <button type="submit" class="btn btn-sm btn-outline-secondary flex-shrink-0">
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- List Transaksi --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <h6 class="mb-3">Detail Transaksi</h6>
            @forelse ($transaksis as $trx)
                <div class="border-bottom mb-2 pb-2">
                    <strong>{{ $trx->jenis }}</strong> <br>
                    <small class="text-muted">{{ $trx->created_at->format('d M Y H:i') }}</small> <br>
                    Total: Rp {{ number_format($trx->total, 0, ',', '.') }}
                </div>
            @empty
                <p class="text-muted">Tidak ada transaksi pada periode ini.</p>
            @endforelse
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <a href="{{ route('rental.show', $rental->id) }}" class="btn btn-secondary w-100">
        <i class="bi bi-arrow-left"></i> Kembali ke Detail Rental
    </a>
</div>
@endsection

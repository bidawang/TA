@extends('layout')

@section('content')
<div class="container py-4">
    <h3>Detail Transaksi #{{ $transaksi->id_transaksi }}</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Data Lokal</h5>
            <p><strong>Jenis:</strong> {{ $transaksi->jenis }}</p>
            <p><strong>Jam:</strong> {{ $transaksi->jam_mulai }} - {{ $transaksi->jam_selesai }}</p>
            <p><strong>Total:</strong> Rp{{ number_format($transaksi->total, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> {{ $transaksi->pembayaran->status ?? 'Belum Dibayar' }}</p>
            <p><strong>Metode:</strong> {{ $transaksi->pembayaran->payment_method ?? '-' }}</p>
            <p><strong>Dibayar Pada:</strong> {{ $transaksi->pembayaran->paid_at ?? '-' }}</p>
            <p><strong>Merchant Ref:</strong> {{ $transaksi->pembayaran->merchant_ref ?? '-' }}</p>
        </div>
    </div>

    @if($tripayData)
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Data dari Tripay</h5>
                <p><strong>Reference:</strong> {{ $tripayData['reference'] }}</p>
                <p><strong>Merchant Ref:</strong> {{ $tripayData['merchant_ref'] }}</p>
                <p><strong>Amount:</strong> Rp{{ number_format($tripayData['amount'], 0, ',', '.') }}</p>
                <p><strong>Status:</strong> {{ $tripayData['status'] }}</p>
                <p><strong>Method:</strong> {{ $tripayData['method'] }}</p>
                <p><strong>Created At:</strong> {{ $tripayData['created_at'] }}</p>
                <p><strong>Paid At:</strong> {{ $tripayData['paid_at'] ?? '-' }}</p>
                <p><strong>Customer Name:</strong> {{ $tripayData['customer_name'] ?? '-' }}</p>
                <p><strong>Customer Email:</strong> {{ $tripayData['customer_email'] ?? '-' }}</p>
                {{-- Tambah info lain dari response Tripay sesuai kebutuhan --}}
            </div>
        </div>
    @else
        <div class="alert alert-warning">Data transaksi dari Tripay tidak ditemukan.</div>
    @endif

    <a href="{{ route('riwayat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection

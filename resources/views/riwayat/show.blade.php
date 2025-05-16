@extends('layout')

@section('content')
<div class="container py-4">
    <h3>Detail Transaksi #{{ $transaksi->id_transaksi }}</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Data Lokal</h5>
            <p><strong>Jenis:</strong> {{ $transaksi->jenis }}</p>
            <p><strong>Keterangan:</strong> {{ $transaksi->keterangan ?? '-' }}</p>
            <p><strong>Jam:</strong> {{ $transaksi->jam_mulai }} - {{ $transaksi->jam_selesai }} ({{ $transaksi->jumlah_jam }} jam)</p>
            <p><strong>Total:</strong> Rp{{ number_format($transaksi->total, 0, ',', '.') }}</p>
            <p><strong>Merchant Ref:</strong> {{ optional($transaksi->pembayaran)->merchant_ref ?? '-' }}</p>
            <p><strong>Reference:</strong> {{ optional($transaksi->pembayaran)->reference ?? '-' }}</p>
        </div>
    </div>

    @if($tripayData)
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Data dari Tripay</h5>
                <p><strong>Status:</strong> {{ data_get($tripayData, 'status', '-') }}</p>
                <p><strong>Method:</strong> {{ data_get($tripayData, 'payment_method', '-') }}</p>
                <p><strong>Amount:</strong> Rp{{ number_format(data_get($tripayData, 'amount', 0), 0, ',', '.') }}</p>
<p><strong>Dibayar Pada</strong></p>
@php
    $paidAt = data_get($tripayData, 'paid_at');
@endphp

@if($paidAt && is_numeric($paidAt))
    <p><strong>Hari : </strong>{{ \Carbon\Carbon::createFromTimestamp($paidAt)->translatedFormat('l, d-m-Y') }}</p>
    <p><strong>Jam : </strong>{{ \Carbon\Carbon::createFromTimestamp($paidAt)->format('H:i:s') }}</p>
@else
    <p>-</p>
@endif
                <p><strong>Customer Name:</strong> {{ data_get($tripayData, 'customer_name', '-') }}</p>
                <p><strong>Customer Email:</strong> {{ data_get($tripayData, 'customer_email', '-') }}</p>
            </div>
        </div>
    @else
        <div class="alert alert-warning">Data transaksi dari Tripay tidak ditemukan.</div>
    @endif

    <a href="{{ route('riwayat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection

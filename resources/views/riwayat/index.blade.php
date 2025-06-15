@extends('layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h3 class="mb-0">Riwayat Transaksi</h3>
        <input type="text" id="searchInput" class="form-control w-auto" placeholder="Cari transaksi..." onkeyup="filterTransaksi()">
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="transaksiTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="nonbooking-tab" data-bs-toggle="tab" data-bs-target="#nonbooking" type="button" role="tab">Non-Booking</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="booking-tab" data-bs-toggle="tab" data-bs-target="#booking" type="button" role="tab">Booking</button>
        </li>
    </ul>

    <div class="tab-content" id="transaksiTabContent">
        {{-- Non-Booking Tab --}}
        <div class="tab-pane fade show active" id="nonbooking" role="tabpanel">
            @include('riwayat.filter', ['transaksi' => $transaksi->where('jenis', 'bukan'), 'transaksiTripay' => $transaksiTripay])
        </div>

        {{-- Booking Tab --}}
        <div class="tab-pane fade" id="booking" role="tabpanel">
            @include('riwayat.filter', ['transaksi' => $transaksi->where('jenis', 'booking'), 'transaksiTripay' => $transaksiTripay])
        </div>
    </div>
</div>

{{-- Script filter --}}
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
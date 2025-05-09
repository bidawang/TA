@extends('layout')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 text-center">Detail Rental</h4>

    {{-- Info Rental --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 d-flex align-items-center">
                <i class="bi bi-shop me-2"></i> {{ $rental->nama }}
            </h5>

            <p><strong>NIK:</strong> {{ $rental->nik }}</p>
            <p><strong>Deskripsi:</strong><br>{{ $rental->deskripsi }}</p>

            <p><strong>Rating:</strong>
    @php
        $averageRating = round($rental->ratings_avg_rating);
    @endphp

    @for ($i = 1; $i <= 5; $i++)
        <i class="bi bi-star{{ $i <= $averageRating ? '-fill text-warning' : '' }}"></i>
    @endfor
    <small>({{ number_format($rental->ratings_avg_rating, 1) }})</small>
</p>


            <a href="{{ route('rental.edit', $rental->id) }}" class="btn btn-warning w-100 mt-3">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>

    {{-- Alamat Rental --}}
    <h5 class="mb-3">Alamat Lengkap</h5>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Alamat:</strong> {{ $rental->alamat->alamat_lengkap }}</p>
            <div class="row">
                <div class="col-6 mb-2"><strong>Provinsi:</strong><br>{{ $rental->alamat->provinsi }}</div>
                <div class="col-6 mb-2"><strong>Kota:</strong><br>{{ $rental->alamat->kota }}</div>
                <div class="col-6 mb-2"><strong>Kecamatan:</strong><br>{{ $rental->alamat->kecamatan }}</div>
                <div class="col-6 mb-2"><strong>Kelurahan:</strong><br>{{ $rental->alamat->kelurahan }}</div>
                <div class="col-6 mb-2"><strong>RT/RW:</strong><br>{{ $rental->alamat->rt }}/{{ $rental->alamat->rw }}</div>
                <div class="col-6 mb-2"><strong>Kode Pos:</strong><br>{{ $rental->alamat->kode_pos }}</div>
            </div>
        </div>
    </div>

    {{-- Bottom Navigation --}}
<div class="bottom-action d-flex justify-content-between gap-2 bg-white py-3 px-3 border-top rounded shadow-sm">
    <a href="{{ route('setrental.index', ['rental_id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-controller me-1"></i> Set Rental
    </a>
    <a href="{{ route('fasilitas.index', ['rental_id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-sliders me-1"></i> Fasilitas
    </a>
    <a href="{{ route('galeri.index', ['rental_id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-images me-1"></i> Galeri
    </a>
</div>

</div>
@endsection

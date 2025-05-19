@extends('layout')

@section('content')

<div class="container py-2">

    <h5 class="mb-3 text-center fw-bold">Top Rental PS</h5>

    {{-- Carousel Top Rentals --}}
    <div id="topRentalCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded shadow-sm overflow-hidden" style="height: 220px;">
            @foreach ($topRentals as $index => $rental)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ $rental->logo ? asset('storage/' . $rental->logo) : 'https://via.placeholder.com/600x220?text=' . urlencode($rental->nama) }}"
                         class="d-block w-100" style="object-fit: cover; height: 220px;" alt="{{ $rental->nama }}">
                    <div class="carousel-caption bg-dark bg-opacity-75 p-2 rounded-bottom">
                        <h5 class="mb-1">{{ $rental->nama }}</h5>
                        <p class="mb-0 small">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($rental->ratings_avg_rating) ? '-fill text-warning' : '' }}"></i>
                            @endfor
                            <span>({{ number_format($rental->ratings_avg_rating ?? 0, 1) }})</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#topRentalCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#topRentalCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
        </button>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
        <div class="input-group">
            <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama rental...">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    <h5 class="mb-3 text-center fw-bold">Semua Rental PS</h5>

@forelse ($rentals as $rental)
    <div class="card mb-3 shadow-sm rounded-3" style="overflow: hidden;">
        <div class="row g-0 align-items-center">
            <div class="col-5">
                <img src="{{ $rental->logo ? asset('storage/' . $rental->logo) : 'https://via.placeholder.com/400x250?text=' . urlencode($rental->nama) }}"
                     alt="Logo {{ $rental->nama }}" class="img-fluid rounded-start" style="height: 140px; object-fit: cover; width: 100%;">
            </div>
            <div class="col-7 p-3">
                <h6 class="fw-bold mb-1 text-truncate" title="{{ $rental->nama }}">{{ $rental->nama }}</h6>
                <p class="text-muted small mb-1 text-truncate" title="{{ $rental->alamat->alamat_lengkap }}">
                    {{ $rental->alamat->alamat_lengkap }}
                </p>
                <p class="mb-2">
                    @php
                        $rating = round($rental->ratings_avg_rating ?? 0);
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $rating ? '-fill text-warning' : '' }}"></i>
                    @endfor
                    <small>({{ number_format($rental->ratings_avg_rating ?? 0, 1) }})</small>
                </p>
                <a href="{{ route('rental.show', $rental->id) }}" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
@empty
    <p class="text-center text-muted mt-5">Belum ada rental tersedia.</p>
@endforelse


</div>

@endsection

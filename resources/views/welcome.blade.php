@extends('layout')

@section('content')

<div class="container py-4">

    <h5 class="mb-3">Top Rental</h5>

    {{-- Slide show rental dengan rating tertinggi --}}
    <div id="topRentalCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($topRentals as $index => $rental)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ $rental->logo ? asset('storage/' . $rental->logo) : 'https://via.placeholder.com/800x300?text=' . urlencode($rental->nama) }}"
                         class="d-block w-100" style="object-fit: cover;" alt="{{ $rental->nama }}">
                    <div class="carousel-caption bg-dark bg-opacity-50 rounded">
                        <h5>{{ $rental->nama }}</h5>
                        <p>
                            Rating:
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($rental->ratings_avg_rating) ? '-fill text-warning' : '' }}"></i>
                            @endfor
                            ({{ number_format($rental->ratings_avg_rating ?? 0, 1) }})
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#topRentalCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#topRentalCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    {{-- Daftar semua rental --}}
    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
    <div class="row g-2">
        <div class="col-md-10">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama rental...">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
    </div>
</form>
    <h5 class="mb-3">Semua Rental PS</h5>
    <div class="row">
        @forelse ($rentals as $rental)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $rental->logo ? asset('storage/' . $rental->logo) : 'https://via.placeholder.com/400x250?text=' . urlencode($rental->nama) }}"
                         alt="Logo Rental" class="img-fluid">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $rental->nama }}</h5>
                        <p class="mb-1 text-muted">{{ $rental->alamat->alamat_lengkap }}</p>
                        <p class="mb-2">
                            Rating:
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($rental->ratings_avg_rating) ? '-fill text-warning' : '' }}"></i>
                            @endfor
                            ({{ number_format($rental->ratings_avg_rating ?? 0, 1) }})
                        </p>
                        <a href="{{ route('rental.show', $rental->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada rental tersedia.</p>
        @endforelse
    </div>

</div>
@endsection

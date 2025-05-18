@extends('layout')

@section('content')
<div class="container py-3">

    {{-- Info Rental --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 d-flex align-items-center">
                <i class="bi bi-shop me-2 fs-4"></i> 
                <span class="fs-6 fw-semibold">{{ $rental->nama }}</span>
            </h5>

            <p class="mb-1"><strong>NIK:</strong> {{ $rental->nik }}</p>
            <p class="mb-3"><strong>Deskripsi:</strong><br>{{ $rental->deskripsi }}</p>

            <p class="mb-0"><strong>Rating:</strong>
                @php
                    $averageRating = round($rental->ratings_avg_rating);
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $averageRating ? '-fill text-warning' : '' }}"></i>
                @endfor
                <small class="text-muted">({{ number_format($rental->ratings_avg_rating, 1) }})</small>
            </p>

            <a href="{{ route('rental.edit', $rental->id) }}" class="btn btn-warning w-100 mt-3">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>

    {{-- Carousel Section --}}
    <div class="row g-3 mb-4">
        {{-- Carousel Fasilitas --}}
        <div class="col-12 col-md-6">
            <div id="carouselLeft" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded shadow-sm">
                    @foreach ($carousel1 as $i => $item)
                        <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $item->foto_fasilitas) }}" 
                                class="d-block w-100 carousel-img rounded" 
                                alt="{{ $item->nama_fasilitas }}">
                            <div class="carousel-caption bg-dark bg-opacity-50 rounded px-2 py-1">
                                <h6 class="mb-0">{{ $item->nama_fasilitas }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselLeft" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselLeft" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        {{-- Carousel Galeri --}}
        <div class="col-12 col-md-6">
            <div id="carouselRight" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded shadow-sm">
                    @foreach ($carousel2 as $i => $item)
                        <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $item->foto_fasilitas) }}" 
                                class="d-block w-100 carousel-img rounded" 
                                alt="{{ $item->nama_fasilitas }}">
                            <div class="carousel-caption bg-dark bg-opacity-50 rounded px-2 py-1">
                                <h6 class="mb-0">{{ $item->nama_foto }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselRight" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselRight" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Alamat Rental --}}
    <h5 class="mb-3">Alamat Lengkap</h5>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p class="mb-2"><strong>Alamat:</strong> {{ $rental->alamat->alamat_lengkap }}</p>
            <div class="row text-small">
                @php use Illuminate\Support\Str; @endphp
                <div class="col-6 mb-1"><strong>Provinsi:</strong><br>{{ Str::after($rental->alamat->provinsi, '|') }}</div>
                <div class="col-6 mb-1"><strong>Kota:</strong><br>{{ Str::after($rental->alamat->kota, '|') }}</div>
                <div class="col-6 mb-1"><strong>Kecamatan:</strong><br>{{ Str::after($rental->alamat->kecamatan, '|') }}</div>
                <div class="col-6 mb-1"><strong>Kelurahan:</strong><br>{{ Str::after($rental->alamat->kelurahan, '|') }}</div>
                <div class="col-6 mb-1"><strong>RT/RW:</strong><br>{{ $rental->alamat->rt }}/{{ $rental->alamat->rw }}</div>
                <div class="col-6 mb-1"><strong>Kode Pos:</strong><br>{{ $rental->alamat->kode_pos }}</div>
            </div>
        </div>
    </div>

    {{-- Komentar dan Rating --}}
    <h5 class="mb-3 mt-4">Ulasan Pengguna</h5>

    @php
        $userGoogleId = auth()->check() ? auth()->user()->google_id : null;
        $userAlreadyRated = $ratings->first(function ($rating) use ($userGoogleId, $rental) {
            return $rating->user_id === $userGoogleId && $rating->rental_id === $rental->id;
        });
    @endphp

    @if (auth()->check())
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                @if (!$userAlreadyRated)
                    <form action="{{ route('rating.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select name="rating" id="rating" class="form-select" required>
                                <option value="">Pilih bintang</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} Bintang</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="komentar" class="form-label">Komentar</label>
                            <textarea name="komentar" id="komentar" class="form-control" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-send"></i> Kirim Ulasan
                        </button>
                    </form>
                @else
                    <form action="{{ route('rating.updateByUser', $userAlreadyRated->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Edit Rating</label>
                            <select name="rating" id="rating" class="form-select" required>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $userAlreadyRated->rating == $i ? 'selected' : '' }}>
                                        {{ $i }} Bintang
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="komentar" class="form-label">Edit Komentar</label>
                            <textarea name="komentar" id="komentar" class="form-control" rows="2" required>{{ $userAlreadyRated->komentar }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Update Ulasan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    <div class="card shadow-sm mb-5">
        <div class="card-body p-3">
            @forelse ($ratings as $komentar)
                <div class="mb-3">
                    <h6 class="fw-bold mb-1">
                        {{ $komentar->user->name ?? 'Pengguna' }}
                        <small class="text-muted float-end">{{ $komentar->created_at->format('d M Y H:i') }}</small>
                    </h6>

                    <div class="mb-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $komentar->rating ? '-fill text-warning' : '' }}"></i>
                        @endfor
                    </div>

                    <p class="mb-0">{{ $komentar->komentar }}</p>
                </div>
                <hr class="my-2">
            @empty
                <p class="text-muted text-center mb-0">Belum ada ulasan dari pengguna lain.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Bottom Navigation Fixed for Mobile --}}

{{-- Bottom Navigation --}}
<div class="bottom-action d-flex justify-content-between gap-2 bg-white py-3 px-3 border-top rounded shadow-sm">
    @if(auth()->user()->role === 'developer')
        <a href="{{ route('setrental.index', ['rental_id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-gear-wide-connected me-1 fs-6"></i> Set Rental
        </a>
        <a href="{{ route('fasilitas.index', ['rental_id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-sliders2 me-1 fs-6"></i> Fasilitas
        </a>
        <a href="{{ route('galeri.index', ['rental_id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
            <i class="bi bi-images me-1 fs-6"></i> Galeri
        </a>
        <a href="{{ route('pendapatan', ['id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-currency-dollar me-1 fs-6"></i> Pendapatan
        </a>
    @elseif(auth()->user()->role === 'admin')
        <a href="{{ route('fasilitas.index', ['rental_id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-sliders2 me-1 fs-6"></i> Fasilitas
        </a>
        <a href="{{ route('galeri.index', ['rental_id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-images me-1 fs-6"></i> Galeri
        </a>
    @endif
</div>


@endsection

@push('styles')
<style>
    .carousel-img {
        height: 250px;
        object-fit: cover;
    }

    @media (min-width: 768px) {
        .carousel-img {
            height: 300px;
        }
    }
</style>
@endpush

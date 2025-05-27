@extends('layout')

@section('content')
<div class="container py-3">

    <div class="card shadow-sm mb-4">
    <div class="card-body d-flex align-items-start gap-3">
        {{-- Gambar di kiri --}}
        <div style="flex-shrink: 0; width: 140px;">
            <img src="{{ asset('storage/' . $rental->logo) }}" alt="{{ $rental->nama }}" class="img-fluid rounded" style="object-fit: cover; height: 90px; width: 140px;">
            {{-- Rating kecil di bawah gambar --}}
            @php
                $averageRating = round($rental->ratings_avg_rating);
            @endphp
            <div class="mt-2 d-flex align-items-center justify-content-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $averageRating ? '-fill text-warning' : '' }}" style="font-size: 0.8rem;"></i>
                @endfor
                <small class="text-muted" style="font-size: 0.75rem;">({{ number_format($rental->ratings_avg_rating, 1) }})</small>
            </div>
        </div>

        {{-- Data kanan --}}
        <div class="flex-grow-1 d-flex flex-column justify-content-between">
            <div>
                <h5 class="card-title mb-3 fw-bold" style="font-size: 1.25rem;">{{ $rental->nama }}</h5>
            </div>

            @if(auth()->check() && auth()->user()->role == 'developer' || auth()->user()->role == 'admin' && $rental->google_id == auth()->user()->google_id)
                <a href="{{ route('rental.edit', $rental->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            @elseif(auth()->check() && auth()->user()->role == 'user' || auth()->check() && auth()->user()->role == 'admin')
                <a href="{{ route('setrental.index', ['rental_id' => $rental->id]) }}" class="btn btn-outline-danger w-100 mb-2">
                    <i class="bi bi-gear-wide-connected me-1 fs-6"></i> Set Rental
                </a>
            @endif

            {{-- Deskripsi di bawah tombol --}}
            <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $rental->deskripsi }}</p>
        </div>
    </div>
</div>


    {{-- Carousel Section --}}
<div class="row g-3 mb-4">

    {{-- Carousel Fasilitas --}}
    <div class="col-12 col-md-6">
        <div id="carouselLeft" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner rounded shadow-sm overflow-hidden" style="height: 200px;">
                @foreach ($carousel1 as $i => $item)
                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $item->foto_fasilitas) }}" 
                            class="d-block w-100 h-100" 
                            style="object-fit: cover;" 
                            alt="{{ $item->nama_fasilitas }}">
                        <div class="carousel-caption bg-dark bg-opacity-50 px-2 py-1 rounded">
                            <small class="text-white fw-semibold">{{ $item->nama_fasilitas }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselLeft" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselLeft" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    {{-- Carousel Galeri (reverse direction) --}}
    <div class="col-12 col-md-6">
        <div id="carouselRight" class="carousel slide carousel-fade" data-bs-ride="carousel" dir="rtl">
            <div class="carousel-inner rounded shadow-sm overflow-hidden" style="height: 200px;">
                @foreach ($carousel2 as $i => $item)
                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $item->foto_fasilitas) }}" 
                            class="d-block w-100 h-100" 
                            style="object-fit: cover;" 
                            alt="{{ $item->nama_foto }}">
                        <div class="carousel-caption bg-dark bg-opacity-50 px-2 py-1 rounded">
                            <small class="text-white fw-semibold">{{ $item->nama_foto }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselRight" data-bs-slide="next">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselRight" data-bs-slide="prev">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
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

@auth
    @php
        $userGoogleId = auth()->user()->google_id;
        $userAlreadyRated = $ratings->firstWhere('user_id', $userGoogleId);
    @endphp

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @if (!$userAlreadyRated)
                {{-- Form Kirim Ulasan --}}
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
                {{-- Form Edit Ulasan --}}
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
@endauth

{{-- Tampilkan semua komentar --}}
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
@if(auth()->check())
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
@endif

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

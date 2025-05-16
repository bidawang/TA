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
<div class="row">
    {{-- Carousel 1: Fasilitas --}}
    <div class="col-md-6 mb-4">
        <div id="carouselLeft" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($carousel1 as $i => $item)
                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $item->foto_fasilitas) }}"
class="carousel-img d-block rounded mx-auto" 
     style="width: 600px; height: 300px; object-fit: cover;" 
     alt="{{ $item->nama_fasilitas }}">                        <div class="carousel-caption bg-dark bg-opacity-50 rounded">
                            <h5>{{ $item->nama_fasilitas }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Carousel 2: Galeri --}}
    <div class="col-md-6 mb-4">
        <div id="carouselRight" class="carousel slide carousel-reverse" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($carousel2 as $i => $item)
                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $item->foto_fasilitas) }}"
class="carousel-img d-block rounded mx-auto" 
     style="width: 600px; height: 300px; object-fit: cover;" 
     alt="{{ $item->nama_fasilitas }}">
                             <div class="carousel-caption bg-dark bg-opacity-50 rounded">
                            <h5>{{ $item->nama_foto }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

    {{-- Alamat Rental --}}
    <h5 class="mb-3">Alamat Lengkap</h5>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Alamat:</strong> {{ $rental->alamat->alamat_lengkap }}</p>
            <div class="row">
                @php
    use Illuminate\Support\Str;
@endphp

                <div class="col-6 mb-2"><strong>Provinsi:</strong><br>{{ Str::after($rental->alamat->provinsi, '|') }}</div>
<div class="col-6 mb-2"><strong>Kota:</strong><br>{{ Str::after($rental->alamat->kota, '|') }}</div>
<div class="col-6 mb-2"><strong>Kecamatan:</strong><br>{{ Str::after($rental->alamat->kecamatan, '|') }}</div>
<div class="col-6 mb-2"><strong>Kelurahan:</strong><br>{{ Str::after($rental->alamat->kelurahan, '|') }}</div>

                <div class="col-6 mb-2"><strong>RT/RW:</strong><br>{{ $rental->alamat->rt }}/{{ $rental->alamat->rw }}</div>
                <div class="col-6 mb-2"><strong>Kode Pos:</strong><br>{{ $rental->alamat->kode_pos }}</div>
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
                {{-- Form store rating --}}
                <form action="{{ route('rating.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                    <div class="form-group mb-2">
                        <label for="rating">Rating</label>
                        <select name="rating" class="form-control" required>
                            <option value="">Pilih bintang</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} Bintang</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="komentar">Komentar</label>
                        <textarea name="komentar" class="form-control" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-send"></i> Kirim Ulasan
                    </button>
                </form>
            @else
                {{-- Form update rating --}}
                <form action="{{ route('rating.updateByUser', $userAlreadyRated->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                    <div class="form-group mb-2">
                        <label for="rating">Edit Rating</label>
                        <select name="rating" class="form-control" required>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $userAlreadyRated->rating == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="komentar">Edit Komentar</label>
                        <textarea name="komentar" class="form-control" rows="2" required>{{ $userAlreadyRated->komentar }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> Update Ulasan
                    </button>
                </form>
            @endif
        </div>
    </div>
@endif
<div class="card shadow-sm mb-4">
    <div class="card-body p-3">
        @forelse ($ratings as $komentar)
            <div class="media mb-3">
                <div class="media-body">
                    <h6 class="mt-0 mb-1 font-weight-bold">
                        {{ $komentar->user->name ?? 'Pengguna' }}
                        <small class="text-muted float-right">{{ $komentar->created_at->format('d M Y H:i') }}</small>
                    </h6>

                    {{-- Star Rating --}}
                    <div class="mb-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $komentar->rating ? '-fill text-warning' : '' }}"></i>
                        @endfor
                    </div>

                    <p>{{ $komentar->komentar }}</p>
                </div>
            </div>
            <hr>
        @empty
            <p class="text-muted text-center">Belum ada ulasan dari pengguna lain.</p>
        @endforelse
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
<a href="{{ route('pendapatan', ['id' => $rental->id]) }}" class="btn-sm btn btn-outline-danger flex-fill text-center">
        <i class="bi bi-images me-1"></i> Galeri
    </a>
</div>

</div>
@endsection

<!-- resources/views/galeri/show.blade.php -->
@extends('layout')

@section('content')
    <h1>Detail Galeri</h1>

    <div class="card">
        <img src="{{ asset('storage/' . $galeri->carousel) }}" class="card-img-top" alt="Galeri Image">
        <div class="card-body">
            <h5 class="card-title">{{ $galeri->nama_foto }}</h5>
            <p class="card-text">{{ $galeri->deskripsi }}</p>
            <p><strong>Google ID:</strong> {{ $galeri->google_id }}</p>
            <p><strong>Rental ID:</strong> {{ $galeri->rental_id }}</p>
            <p><strong>Created At:</strong> {{ $galeri->created_at }}</p>
            <p><strong>Updated At:</strong> {{ $galeri->updated_at }}</p>
        </div>
    </div>

    <a href="{{ route('galeri.index', ['rental_id' => $galeri->rental_id]) }}" class="btn btn-secondary mt-3">Kembali ke Daftar Galeri</a>
@endsection

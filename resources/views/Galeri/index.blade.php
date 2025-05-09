<!-- resources/views/galeri/index.blade.php -->
@extends('layout')

@section('content')
    <h1>Galeri Rental</h1>

    <div class="mb-3">
        <a href="{{ route('galeri.create', ['rental_id' => $rental_id]) }}" class="btn btn-primary">Tambah Galeri</a>
    </div>

    @foreach ($galeris as $galeri)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $galeri->nama_foto }}</h5>
                <p class="card-text">{{ $galeri->deskripsi }}</p>
                <a href="{{ route('galeri.show', $galeri->id) }}" class="btn btn-info">Lihat Detail</a>
                <a href="{{ route('galeri.edit', $galeri->id) }}" class="btn btn-warning">Edit</a>

                <form action="{{ route('galeri.destroy', $galeri->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    @endforeach
@endsection

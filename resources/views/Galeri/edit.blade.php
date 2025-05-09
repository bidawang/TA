<!-- resources/views/galeri/edit.blade.php -->
@extends('layout')

@section('content')
    <h1>Edit Galeri</h1>

    <form action="{{ route('galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="nama_foto" class="form-label">Nama Foto</label>
            <input type="text" class="form-control" id="nama_foto" name="nama_foto" value="{{ $galeri->nama_foto }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $galeri->deskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label for="carousel" class="form-label">Carousel</label>
            <input type="file" class="form-control" id="carousel" name="carousel" accept="image/*">
            @if($galeri->carousel)
                <img src="{{ asset('storage/' . $galeri->carousel) }}" class="mt-2" width="100" alt="Current Image">
            @endif
        </div>

        <input type="hidden" name="rental_id" value="{{ $galeri->rental_id }}">

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
@endsection

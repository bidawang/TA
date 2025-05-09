<!-- resources/views/galeri/create.blade.php -->
@extends('layout')

@section('content')
    <h1>Tambah Galeri</h1>

    <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>
        
        <div class="mb-3">
            <label for="carousel" class="form-label">Foto</label>
            <input type="file" class="form-control" id="carousel" name="nama_foto" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="nama_foto" class="form-label">Carousel</label>
            <input type="text" class="form-control" id="nama_foto" name="carousel" required>
        </div>

        <input type="hidden" name="rental_id" value="{{ $rental_id }}">

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection

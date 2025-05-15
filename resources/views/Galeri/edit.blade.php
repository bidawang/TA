@extends('layout')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Galeri</h1>

    <form action="{{ route('galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_foto" class="form-label">Nama Foto</label>
            <input type="text" class="form-control" id="nama_foto" name="nama_foto" value="{{ $galeri->nama_foto }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ $galeri->deskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label for="foto_fasilitas" class="form-label">Ganti Foto (Opsional)</label>
            <input type="file" class="form-control" id="foto_fasilitas" name="foto_fasilitas" accept="image/*">
            @if($galeri->foto_fasilitas)
                <img src="{{ asset('storage/' . $galeri->foto_fasilitas) }}" class="mt-2" style="max-height: 200px;" alt="Foto Galeri">
            @endif
        </div>

        <div class="mb-3">
            <label for="carousel" class="form-label">Tampilkan di Carousel?</label>
            <select class="form-select" id="carousel" name="carousel" required>
                <option value="1" {{ $galeri->carousel ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !$galeri->carousel ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <input type="hidden" name="rental_id" value="{{ $galeri->id_rental }}">

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection

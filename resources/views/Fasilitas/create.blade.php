@extends('layout')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Fasilitas</h1>

    <form action="{{ route('fasilitas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Hidden field untuk rental_id -->
        <input type="hidden" name="id_rental" value="{{ $rental_id }}">

        <div class="mb-3">
            <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
            <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas" required>
        </div>

        <div class="mb-3">
            <label for="foto_fasilitas" class="form-label">Foto Fasilitas</label>
            <input type="file" class="form-control" id="foto_fasilitas" name="foto_fasilitas" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
@extends('layout')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Fasilitas</h1>

    <form action="{{ route('fasilitas.update', $fasilitas->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
            <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
        </div>

        <div class="mb-3">
            <label for="id_rental" class="form-label">Pilih Rental</label>
            <select class="form-control" id="id_rental" name="id_rental" required>
                @foreach ($rentals as $rental)
                <option value="{{ $rental->id }}" @selected($fasilitas->id_rental == $rental->id)>{{ $rental->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="foto_fasilitas" class="form-label">Foto Fasilitas</label>
            <input type="file" class="form-control" id="foto_fasilitas" name="foto_fasilitas" accept="image/*">
            @if ($fasilitas->foto_fasilitas)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $fasilitas->foto_fasilitas) }}" class="img-thumbnail" width="100" alt="Foto Fasilitas">
            </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="google_id" class="form-label">Google ID (optional)</label>
            <input type="text" class="form-control" id="google_id" name="google_id" value="{{ old('google_id', $fasilitas->google_id) }}">
        </div>

        <button type="submit" class="btn btn-success">Perbarui</button>
        <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

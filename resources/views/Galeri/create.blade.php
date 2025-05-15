@extends('layout')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Tambah Galeri</h1>

    <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nama_foto" class="form-label">Nama Foto</label>
            <input type="text" class="form-control" id="nama_foto" name="nama_foto" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="foto_fasilitas" class="form-label">Upload Foto</label>
            <input type="file" class="form-control" id="foto_fasilitas" name="foto_fasilitas" accept="image/*" required>
            <img id="preview" class="img-fluid mt-2" style="max-height: 200px; display: none;">
        </div>

        <div class="mb-3">
            <label for="carousel" class="form-label">Tampilkan di Carousel?</label>
            <select class="form-select" id="carousel" name="carousel" required>
                <option value="1">Ya</option>
                <option value="0" selected>Tidak</option>
            </select>
        </div>

        <input type="hidden" name="rental_id" value="{{ $rental_id }}">

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
document.getElementById('foto_fasilitas').addEventListener('change', function(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endsection

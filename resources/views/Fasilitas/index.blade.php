@extends('layout')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Fasilitas</h1>

    <!-- Tombol untuk menambah fasilitas -->
    <a href="{{ route('fasilitas.create',['rental_id' => $rental_id]) }}" class="btn btn-primary mb-3">Tambah Fasilitas</a>

    <!-- Jika ada rental_id, tampilkan filter -->

    <!-- Menampilkan daftar fasilitas -->
    <div class="row">
        @foreach ($fasilitas as $fasilitasItem)
        <div class="col-md-4 mb-3">
            <div class="card">
                <img src="{{ asset('storage/' . $fasilitasItem->foto_fasilitas) }}" class="card-img-top" alt="{{ $fasilitasItem->nama_fasilitas }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $fasilitasItem->nama_fasilitas }}</h5>
                    <p class="card-text">Rental: {{ $fasilitasItem->rental->name }}</p>
                    <a href="{{ route('fasilitas.show', $fasilitasItem->id) }}" class="btn btn-info">Lihat Detail</a>
                    <a href="{{ route('fasilitas.edit', $fasilitasItem->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('fasilitas.destroy', $fasilitasItem->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

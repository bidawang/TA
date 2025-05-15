@extends('layout')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Galeri Rental</h1>

    <div class="mb-3">
        <a href="{{ route('galeri.create', ['rental_id' => $rental_id]) }}" class="btn btn-primary">Tambah Galeri</a>
    </div>

    @forelse ($galeris as $galeri)
        <div class="card mb-3">
            <div class="row g-0">
                @if($galeri->foto_fasilitas)
                <div class="col-md-4">
                    <img src="{{ asset('storage/' . $galeri->foto_fasilitas) }}" class="img-fluid rounded-start" alt="{{ $galeri->nama_foto }}">
                </div>
                @endif
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ $galeri->nama_foto }}</h5>
                        <p class="card-text">{{ $galeri->deskripsi }}</p>
                        <p class="card-text"><small class="text-muted">Tampilkan di carousel: {{ $galeri->carousel ? 'Ya' : 'Tidak' }}</small></p>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('galeri.show', $galeri->id) }}" class="btn btn-info btn-sm">Lihat Detail</a>
                            <a href="{{ route('galeri.edit', $galeri->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('galeri.destroy', $galeri->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus galeri ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Belum ada galeri untuk rental ini.</div>
    @endforelse
</div>
@endsection

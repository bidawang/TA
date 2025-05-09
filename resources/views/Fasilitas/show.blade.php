@extends('layout')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Fasilitas</h1>

    <div class="card">
        <img src="{{ asset('storage/' . $fasilitas->foto_fasilitas) }}" class="card-img-top" alt="{{ $fasilitas->nama_fasilitas }}">
        <div class="card-body">
            <h5 class="card-title">{{ $fasilitas->nama_fasilitas }}</h5>
            <p class="card-text">Rental: {{ $fasilitas->rental->name }}</p>
            <p class="card-text">Google ID: {{ $fasilitas->google_id ?? 'N/A' }}</p>
            <a href="{{ route('fasilitas.edit', $fasilitas->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection

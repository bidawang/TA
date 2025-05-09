@extends('layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Daftar Rental</h3>
        <a href="{{ route('rental.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Rental
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tampilan versi card untuk layar kecil --}}
    <div class="d-md-none">
        @forelse ($rentals as $rental)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $rental->nama }}</h6>
                    <p class="mb-2 text-muted small">{{ $rental->nik }}</p>
                    <p class="mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($rental->ratings_avg_rating) ? '-fill text-warning' : '' }}"></i>
                        @endfor
                        <small>({{ number_format($rental->ratings_avg_rating, 1) }})</small>
                    </p>
                    <div class="d-flex justify-content-start gap-1">
                        <a href="{{ route('rental.show', $rental->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('rental.edit', $rental->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('rental.destroy', $rental->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Belum ada data rental.</p>
        @endforelse
    </div>

    {{-- Tabel hanya untuk layar sedang ke atas --}}
    <div class="table-responsive d-none d-md-block shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Rating</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rentals as $rental)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $rental->nama }}<br><small class="text-muted">{{ $rental->nik }}</small></td>
                        <td>
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($rental->ratings_avg_rating) ? '-fill text-warning' : '' }}"></i>
                            @endfor
                            <small>({{ number_format($rental->ratings_avg_rating, 1) }})</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('rental.show', $rental->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('rental.edit', $rental->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('rental.destroy', $rental->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

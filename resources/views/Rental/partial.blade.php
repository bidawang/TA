{{-- Tampilan versi card untuk layar kecil --}}
<div class="d-md-none">
    @forelse ($rentals as $rental)
    <div class="card mb-3 shadow-sm">
    <div class="card-body">
        {{-- Header nama dan status --}}
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                <h6 class="card-title mb-0">{{ $rental->nama }}</h6>
                <p class="text-muted small mb-1">{{ $rental->nik }}</p>
                <p class="mb-2">
            @for ($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $i <= round($rental->ratings_avg_rating) ? '-fill text-warning' : '' }}"></i>
            @endfor
            <small>({{ number_format($rental->ratings_avg_rating, 1) }})</small>
        </p>
            </div>

            <div style="min-width: 100px;">
            <div class="mt-2">
    <form action="{{ route('rental.update', $rental->id) }}" method="POST" onchange="this.submit()">
        @csrf
        @method('PUT')
        <select name="status" class="form-select form-select-sm">
            <option value="aktif" {{ $rental->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="off" {{ $rental->status === 'off' ? 'selected' : '' }}>Off</option>
        </select>
    </form>
</div>

                <div class="d-flex justify-content-start gap-1 mt-2">
            <a href="{{ route('rental.show', $rental->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
            <a href="{{ route('rental.edit', $rental->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('rental.destroy', $rental->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </form>
        </div>
            </div>
        </div>

        {{-- Rating --}}
        
    </div>
</div>

    @empty
        <p class="text-center text-muted">Belum ada data rental.</p>
    @endforelse
</div>

{{-- Tabel untuk layar sedang ke atas --}}
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
                            <a href="{{ route('rental.show', $rental->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('rental.edit', $rental->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('rental.destroy', $rental->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@extends('layout')

@section('content')
<div class="container">
    <h1>Daftar TV</h1>
    <a href="{{ route('tv.create') }}" class="btn btn-primary mb-3">Tambah TV</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive small">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Merek</th>
                    <th>Ukuran</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tvList as $index => $tv)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $tv->merek }}</td>
                        <td>{{ $tv->ukuran }}"</td>
                        <td>
                            <a href="{{ route('tv.edit', $tv->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('tv.destroy', $tv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data TV.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

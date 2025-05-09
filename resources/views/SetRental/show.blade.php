@extends('layout')

@section('content')
<h4>Detail Set Rental</h4>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $setRental->name }}</h5>
        <p class="card-text">
            <strong>TV:</strong> {{ $setRental->tv->merek ?? '-' }}<br>
            <strong>PS:</strong> {{ $setRental->ps->model_ps ?? '-' }}
        </p>
        <a href="{{ route('setrental.edit', $setRental->id) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('setrental.destroy', $setRental->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Hapus</button>
        </form>
        <a href="{{ route('setrental.index', ['rental_id' => $setRental->rental_id]) }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection

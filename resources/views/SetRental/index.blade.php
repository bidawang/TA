@extends('layout')

@section('content')
  <h1>Daftar SetRental</h1>
  
  <a href="{{ route('setrental.create', ['rental_id' => $rental_id]) }}" class="btn btn-primary mb-3">Tambah SetRental</a>

  <div class="row">
    @foreach ($setRentals as $setRental)
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">{{ $setRental->name }}</h5>
            <p class="card-text">TV: {{ $setRental->tv->merek ?? 'Tidak ada TV' }}</p>
            <p class="card-text">PS: {{ $setRental->ps->model_ps ?? 'Tidak ada PS' }}</p> <a href="{{route('ps.show', $setRental->ps_id)}}" class="btn btn-sm btn-success">Game</a><br>
            <a href="{{ route('setrental.show', $setRental->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('setrental.edit', $setRental->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('setrental.destroy', $setRental->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  
@endsection

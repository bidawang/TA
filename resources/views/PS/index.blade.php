@extends('layout')

@section('content')
<div class="container">
  <h4>Daftar PS</h4>
  <a href="{{ route('ps.create') }}" class="btn btn-primary mb-3">Tambah PS</a>

  @foreach ($psList as $ps)
    <div class="card mb-2">
      <div class="card-body">
        <h5>{{ $ps->model_ps }} - {{ $ps->seri }}</h5>
        <p>Tipe: {{ $ps->tipe }} | Storage: {{ $ps->storage }}</p>
        @if($ps->foto)
          <img src="{{ asset('storage/' . $ps->foto) }}" alt="Foto PS" style="max-width: 100px;">
        @endif
        <div class="mt-2">
          <a href="{{ route('ps.show', $ps->id) }}" class="btn btn-sm btn-info">Detail</a>
          <a href="{{ route('ps.edit', $ps->id) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('ps.destroy', $ps->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
          </form>
          <a href="#" class="btn btn-sm btn-info">Game</a>

        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection

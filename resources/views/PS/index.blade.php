@extends('layout')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar PS</h5>
    <a href="{{ route('ps.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
  </div>

  @forelse ($psList as $ps)
  <div class="card mb-2 shadow-sm">
    <div class="card-body p-3">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <strong>{{ $ps->model_ps }} - {{ $ps->seri }}</strong><br>
          <small class="text-muted">Storage: {{ $ps->storage }}</small><br>

          @if($ps->setrental)
          <span class="badge bg-success mt-1">{{ $ps->setrental->name }}</span>
          @else
          <span class="badge bg-secondary mt-1">Belum digunakan</span>
          @endif
        </div>

        <div class="text-end">
          <a href="{{ route('ps.show', $ps->id) }}" class="btn btn-sm btn-outline-info mb-1">👁</a>
          <a href="{{ route('ps.edit', $ps->id) }}" class="btn btn-sm btn-outline-warning mb-1">✏️</a>
          <form action="{{ route('ps.destroy', $ps->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Hapus PS ini?')" class="btn btn-sm btn-outline-danger">🗑</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <p class="text-muted text-center">Belum ada data PS.</p>
  @endforelse
</div>
@endsection
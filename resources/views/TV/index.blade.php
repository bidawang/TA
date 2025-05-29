@extends('layout')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar TV</h5>
    <a href="{{ route('tv.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @forelse ($tvList as $tv)
    <div class="card mb-2 shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <strong>{{ $tv->merek }}</strong><br>
          <small class="text-muted">Ukuran: {{ $tv->ukuran }}″</small>
        </div>
        @if($tv->setrental)
  <span class="badge bg-success">{{ $tv->setrental->name }}</span>
@else
  <span class="badge bg-secondary">Belum dipakai</span>
@endif


        <div class="text-end">
          <a href="{{ route('tv.edit', $tv->id) }}" class="btn btn-sm btn-outline-warning mb-1">✏️</a>
          <form action="{{ route('tv.destroy', $tv->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Hapus TV ini?')" class="btn btn-sm btn-outline-danger">🗑</button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <p class="text-muted text-center">Belum ada data TV.</p>
  @endforelse
</div>
@endsection

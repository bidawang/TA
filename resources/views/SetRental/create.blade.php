@extends('layout')

@section('content')
  <h1>Tambah SetRental</h1>

  <form action="{{ route('setrental.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="name" class="form-label">Nama SetRental</label>
      <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
      <label for="tv_id" class="form-label">Pilih TV</label>
      <select id="tv_id" name="tv_id" class="form-control select2" required>
        <option value="">Pilih TV</option>
        @foreach ($tvs as $tv)
          <option value="{{ $tv->id }}">{{ $tv->merek }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="ps_id" class="form-label">Pilih PS</label>
      <select id="ps_id" name="ps_id" class="form-control select2" required>
        <option value="">Pilih PS</option>
        @foreach ($ps as $ps)
          <option value="{{ $ps->id }}">{{ $ps->model_ps }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
        <input type="number" name="harga_per_jam" id="">
    </div>
    <input type="hidden" name="rental_id" value="{{ $rental_id }}">

    <button type="submit" class="btn btn-primary">Simpan</button>
  </form>
@endsection

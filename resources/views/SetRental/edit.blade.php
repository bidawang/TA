@extends('layout')

@section('content')
  <h1>Edit SetRental: {{ $setRental->name }}</h1>

  <form action="{{ route('setrental.update', $setRental->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label for="name" class="form-label">Nama SetRental</label>
      <input type="text" class="form-control" id="name" name="name" value="{{ $setRental->name }}" required>
    </div>

    <div class="mb-3">
      <label for="tv_id" class="form-label">Pilih TV</label>
      <select id="tv_id" name="tv_id" class="form-control select2" required>
        <option value="">Pilih TV</option>
        @foreach ($tvs as $tv)
          <option value="{{ $tv->id }}" {{ $tv->id == $setRental->tv_id ? 'selected' : '' }}>{{ $tv->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="ps_id" class="form-label">Pilih PS</label>
      <select id="ps_id" name="ps_id" class="form-control select2" required>
        <option value="">Pilih PS</option>
        @foreach ($ps as $ps)
          <option value="{{ $ps->id }}" {{ $ps->id == $setRental->ps_id ? 'selected' : '' }}>{{ $ps->name }}</option>
        @endforeach
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
  </form>
@endsection

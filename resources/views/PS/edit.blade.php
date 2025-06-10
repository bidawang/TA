@extends('layout')

@section('content')
<div class="container">
  <h4>Edit PS</h4>
  <form action="{{ route('ps.update', $ps->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label>Model PS</label>
      <input type="text" name="model_ps" class="form-control" value="{{ old('model_ps', $ps->model_ps) }}" required>
    </div>
    <div class="mb-3">
      <label>Storage</label>
      <input type="text" name="storage" class="form-control" value="{{ old('storage', $ps->storage) }}" required>
    </div>
    <div class="mb-3">
      <label>Seri</label>
      <input type="text" name="seri" class="form-control" value="{{ old('seri', $ps->seri) }}" required>
    </div>
    <div class="mb-3">
      <label>Foto</label>
      <input type="file" name="foto" class="form-control">
      @if (!empty($ps->foto))
      <img src="{{ asset('storage/' . $ps->foto) }}" class="mt-2" style="max-width: 100px;">
      @endif
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
  </form>
</div>
@endsection
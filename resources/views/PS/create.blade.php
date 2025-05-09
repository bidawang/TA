@extends('layout')

@section('content')
<div class="container">
  <h4>Tambah PS</h4>
  <form action="{{ route('ps.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label>Model PS</label>
      <input type="text" name="model_ps" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Storage</label>
      <input type="text" name="storage" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Tipe</label>
      <input type="text" name="tipe" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Seri</label>
      <input type="text" name="seri" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Foto</label>
      <input type="file" name="foto" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </form>
</div>
@endsection

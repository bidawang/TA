@extends('layout')

@section('content')
<div class="container">
  <h4>Tambah PS</h4>
  <form action="{{ route('ps.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
    <label for="model_ps" class="form-label">Model PS</label>
    <select name="model_ps" id="model_ps" class="form-control" required>
        <option value="" disabled selected>Pilih Model PS</option>
        <option value="PlayStation">PlayStation 1</option>
        <option value="PlayStation 2">PlayStation 2</option>
        <option value="PlayStation 3">PlayStation 3</option>
        <option value="PlayStation 4">PlayStation 4</option>
        <option value="PlayStation 5">PlayStation 5</option>
    </select>
</div>

    <div class="mb-3">
      <label>Storage (GB)</label>
      <input type="number" name="storage" class="form-control" placeholder="Isi angka nya saja" required>
    </div>
    <div class="mb-3">
      <label>Seri</label>
      <input type="text" name="seri" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </form>
</div>
@endsection

@extends('layout')

@section('styles')
<!-- Select2 CSS -->
<style>
  .select2-container--bootstrap5 .select2-selection {
    min-height: 44px;
    padding: 0.375rem 0.75rem;
  }
</style>
@endsection

@section('content')
<div class="container py-4">
  <h2 class="mb-4">Edit SetRental: <strong>{{ $setRental->name }}</strong></h2>

  <form action="{{ route('setrental.update', $setRental->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="name" class="form-label">Nama SetRental <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $setRental->name) }}" required>
      <div class="invalid-feedback">Nama wajib diisi.</div>
    </div>

    <div class="mb-3">
      <label for="tv_id" class="form-label">Pilih TV <span class="text-danger">*</span></label>
      <select id="tv_id" name="tv_id" class="form-select select2" required>
        <option value="" disabled>Pilih TV</option>
        @foreach ($tvs as $tv)
          <option value="{{ $tv->id }}" {{ $tv->id == old('tv_id', $setRental->tv_id) ? 'selected' : '' }}>{{ $tv->merek }}</option>
        @endforeach
      </select>
      <div class="invalid-feedback">Silakan pilih TV.</div>
    </div>

    <div class="mb-3">
      <label for="ps_id" class="form-label">Pilih PS <span class="text-danger">*</span></label>
      <select id="ps_id" name="ps_id" class="form-select select2" required>
        <option value="" disabled>Pilih PS</option>
        @foreach ($ps as $psItem)
          <option value="{{ $psItem->id }}" {{ $psItem->id == old('ps_id', $setRental->ps_id) ? 'selected' : '' }}>{{ $psItem->model_ps }}</option>
        @endforeach
      </select>
      <div class="invalid-feedback">Silakan pilih PS.</div>
    </div>

    <div class="mb-3">
      <label for="harga_per_jam" class="form-label">Harga Per Jam (Rp) <span class="text-danger">*</span></label>
      <input type="number" class="form-control" id="harga_per_jam" name="harga_per_jam" value="{{ old('harga_per_jam', $setRental->harga_per_jam) }}" min="0" required>
      <div class="invalid-feedback">Harga harus diisi dan tidak boleh negatif.</div>
    </div>

    <div class="mb-3">
      <label for="foto" class="form-label">Foto (opsional)</label>
      <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
      @if($setRental->foto)
        <div class="mt-3">
          <p>Foto saat ini:</p>
          <img src="{{ asset('storage/' . $setRental->foto) }}" alt="Foto SetRental" class="img-thumbnail" style="max-width: 200px;">
        </div>
      @endif
    </div>

    <button type="submit" class="btn btn-success">Update</button>
  </form>
</div>


<!-- Select2 JS -->
<script>
  $(document).ready(function() {
    $('.select2').select2({
      theme: 'bootstrap-5',
      placeholder: 'Pilih salah satu',
      allowClear: true,
      width: '100%',
    });

    // Bootstrap 5 validation
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })();
  });
</script>
@endsection

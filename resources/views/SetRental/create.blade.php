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
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

  <h2 class="mb-4">Tambah SetRental</h2>

  <form action="{{ route('setrental.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf

    <div class="mb-3">
      <label for="name" class="form-label">Nama SetRental <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama set rental" required>
      <div class="invalid-feedback">Nama wajib diisi.</div>
    </div>

    <div class="mb-3">
      <label for="tv_id" class="form-label">Pilih TV <span class="text-danger">*</span></label>
      <select id="tv_id" name="tv_id" class="form-select select2" required>
        <option value="" disabled selected>Pilih TV</option>
        @foreach ($tvs as $tv)
            @if ($tv->id_rental == session('id_rental') && $tv->google_id == Auth::user()->google_id)
          <option value="{{ $tv->id }}">{{ $tv->merek }}</option>
          @endif
          @endforeach

      </select>
      <div class="invalid-feedback">Silakan pilih TV.</div>
    </div>

    <div class="mb-3">
      <label for="ps_id" class="form-label">Pilih PS <span class="text-danger">*</span></label>
      <select id="ps_id" name="ps_id" class="form-select select2" required>
        <option value="" disabled selected>Pilih PS</option>
        @foreach ($ps as $psItem)
                    @if ($psItem->id_rental == session('id_rental') && $psItem->google_id == Auth::user()->google_id)

          <option value="{{ $psItem->id }}">{{ $psItem->model_ps }}</option>
          @endif
        @endforeach
      </select>
      <div class="invalid-feedback">Silakan pilih PS.</div>
    </div>

    <div class="mb-3">
      <label for="harga_per_jam" class="form-label">Harga Per Jam (Rp) <span class="text-danger">*</span></label>
      <input type="number" class="form-control" id="harga_per_jam" name="harga_per_jam" min="0" placeholder="Masukkan harga per jam" required>
      <div class="invalid-feedback">Harga harus diisi dan tidak boleh negatif.</div>
    </div>

    <div class="mb-3">
      <label for="foto" class="form-label">Foto (opsional)</label>
      <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
    </div>

    <input type="hidden" name="rental_id" value="{{ $rental_id }}">

    <button type="submit" class="btn btn-primary">Simpan</button>
  </form>
</div>
@endsection

@section('scripts')
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

@extends('layout')

@section('content')
<h1 class="mb-3 fs-4">Daftar SetRental</h1>

@if ($errors->any())
<div class="alert alert-danger py-2 px-3 small">
  <ul class="mb-0">
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<a href="{{ route('setrental.create', ['rental_id' => $rental_id]) }}" class="btn btn-primary btn-sm mb-3">
  ➕ Tambah SetRental
</a>
<ul class="nav nav-tabs mb-3" id="statusTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="tidak-tab" data-bs-toggle="tab" data-bs-target="#tidak" type="button" role="tab">Tersedia</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="dipakai-tab" data-bs-toggle="tab" data-bs-target="#dipakai" type="button" role="tab">Dipakai</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance" type="button" role="tab">Maintenance</button>
  </li>
</ul>

<div class="tab-content" id="statusTabContent">
<div class="tab-pane fade " id="dipakai" role="tabpanel">
  <div class="row g-3">
    @foreach ($setRentals->where('status', 'dipakai') as $setRental)
      @include('SetRental.card', compact('setRental', 'rental_id', 'tripayChannels'))
    @endforeach
  </div>
</div>

<div class="tab-pane fade show active" id="tidak" role="tabpanel">
  <div class="row g-3">
    @foreach ($setRentals->where('status', 'tidak') as $setRental)
      @include('SetRental.card', compact('setRental', 'rental_id', 'tripayChannels'))
    @endforeach
  </div>
</div>

<div class="tab-pane fade" id="maintenance" role="tabpanel">
  <div class="row g-3">
    @foreach ($setRentals->where('status', 'maintenance') as $setRental)
      @include('SetRental.card', compact('setRental', 'rental_id', 'tripayChannels'))
    @endforeach
  </div>
</div>
</div> <!-- penutup .tab-content -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Script perhitungan total harga
    document.querySelectorAll('.jumlah-jam').forEach(function (input) {
      input.addEventListener('input', function () {
        const jumlahJam = parseInt(this.value) || 0;
        const hargaPerJam = parseInt(this.dataset.harga) || 0;
        const totalHarga = jumlahJam * hargaPerJam;

        const id = this.dataset.id;
        const totalHargaField = document.getElementById('totalHarga' + capitalizeFirstLetter(id));

        if (totalHargaField) {
          totalHargaField.value = 'Rp ' + totalHarga.toLocaleString('id-ID');
        }
      });
    });

    // Script untuk toggle input manual waktu
    @foreach ($setRentals as $setRental)
      const selesaiRadio{{ $setRental->id }} = document.getElementById('selesaiRadio{{ $setRental->id }}');
      const manualRadio{{ $setRental->id }} = document.getElementById('manualRadio{{ $setRental->id }}');
      const manualInputs{{ $setRental->id }} = document.getElementById('manualInputs{{ $setRental->id }}');

      if (selesaiRadio{{ $setRental->id }} && manualRadio{{ $setRental->id }} && manualInputs{{ $setRental->id }}) {
        selesaiRadio{{ $setRental->id }}.addEventListener('change', function () {
          if (this.checked) {
            manualInputs{{ $setRental->id }}.style.display = 'none';
          }
        });
        manualRadio{{ $setRental->id }}.addEventListener('change', function () {
          if (this.checked) {
            manualInputs{{ $setRental->id }}.style.display = 'flex'; // ditampilkan dalam flex row
          }
        });
      }
    @endforeach

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
  });

// Inisialisasi Select2 saat modal tampil
$(document).ready(function () {
  $('div.modal').on('shown.bs.modal', function () {
    const $select = $(this).find('select.select2-modal');
    if ($select.length && !$select.hasClass("select2-hidden-accessible")) {
      $select.select2({
        dropdownParent: $(this),
        templateResult: function (data) {
          if (!data.id) return data.text;
          return $(`<span><img src="${$(data.element).data('icon')}" style="width:20px; margin-right:8px;" /> ${data.text}</span>`);
        },
        templateSelection: function (data) {
          if (!data.id) return data.text;
          return $(`<span><img src="${$(data.element).data('icon')}" style="width:20px; margin-right:8px;" /> ${data.text}</span>`);
        }
      });
    }
  });
});
</script>
@endpush

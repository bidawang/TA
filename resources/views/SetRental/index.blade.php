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
@if(auth()->check() && (auth()->user()->role === 'developer' || auth()->user()->role === 'admin'))


<a href="{{ route('setrental.create', ['rental_id' => $rental_id]) }}" class="btn btn-primary btn-sm mb-3">
  ➕ Tambah SetRental
</a>
@endif
<ul class="nav nav-tabs mb-3" id="statusTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="tidak-tab" data-bs-toggle="tab" data-bs-target="#tidak" type="button" role="tab">Tersedia</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="dipakai-tab" data-bs-toggle="tab" data-bs-target="#dipakai" type="button" role="tab">Dipakai</button>
  </li>
  @if(auth()->user()->role != 'user')

  <li class="nav-item" role="presentation">
    <button class="nav-link" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance" type="button" role="tab">Maintenance</button>
  </li>
  @endif
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
@if(auth()->user()->role != 'user')
<div class="tab-pane fade" id="maintenance" role="tabpanel">
  <div class="row g-3">
    @foreach ($setRentals->where('status', 'maintenance') as $setRental)
      @include('SetRental.card', compact('setRental', 'rental_id', 'tripayChannels'))
    @endforeach
  </div>
</div>
@endif
</div> <!-- penutup .tab-content -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Perhitungan total harga saat jumlah jam diubah
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

  // Toggle input manual waktu
  @foreach ($setRentals as $setRental)
    const selesaiRadio{{ $setRental->id }} = document.getElementById('selesaiRadio{{ $setRental->id }}');
    const manualRadio{{ $setRental->id }} = document.getElementById('manualRadio{{ $setRental->id }}');
    const manualInputs{{ $setRental->id }} = document.getElementById('manualInputs{{ $setRental->id }}');

    if (manualInputs{{ $setRental->id }}) {
      // Jika hanya ada opsi manual, tampilkan langsung
      @if($setRental->status != 'dipakai')
        manualInputs{{ $setRental->id }}.style.display = 'flex';
      @else
        // Toggle manual/otomatis berdasarkan pilihan radio
        if (manualRadio{{ $setRental->id }}) {
          manualRadio{{ $setRental->id }}.addEventListener('change', function () {
            if (this.checked) {
              manualInputs{{ $setRental->id }}.style.display = 'flex';
            }
          });
        }

        if (selesaiRadio{{ $setRental->id }}) {
          selesaiRadio{{ $setRental->id }}.addEventListener('change', function () {
            if (this.checked) {
              manualInputs{{ $setRental->id }}.style.display = 'none';
            }
          });
        }
      @endif
    }
  @endforeach

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
});
</script>

<script>
  // Inisialisasi Select2 di dalam modal saat tampil
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

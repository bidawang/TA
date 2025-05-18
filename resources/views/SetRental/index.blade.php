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

<a href="{{ route('setrental.create', ['rental_id' => $rental_id]) }}" class="btn btn-primary mb-3 btn-sm">➕ Tambah SetRental</a>

<div class="list-group">
  @foreach ($setRentals as $setRental)
  <div class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-2 mt-2">
  
  {{-- Foto kiri --}}
  <div style="flex-shrink: 0; width: 80px; height: 80px; overflow: hidden; border-radius: .375rem;">
    <img src="{{ $setRental->foto ? asset('storage/'.$setRental->foto) : asset('images/placeholder.png') }}" 
         alt="{{ $setRental->name }}" 
         class="w-100 h-100" 
         style="object-fit: cover; object-position: center;">
  </div>

  {{-- Konten tengah (Nama, Data, Tombol edit/hapus/detail) --}}
  <div class="flex-grow-1 d-flex flex-column justify-content-between" style="min-width: 0;">
    {{-- Nama full width --}}
    <h5 class="mb-2 fs-6 text-primary">{{ $setRental->name }}</h5>
    
    {{-- Data TV, PS, Harga dan tombol edit/hapus/detail di bawah --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 0.5rem;">
      
      {{-- Data (TV, PS, Harga) --}}
      <div class="text-truncate small" style="min-width: 200px;">
        <p class="mb-1"><strong>TV:</strong> {{ $setRental->tv->merek ?? '-' }}</p>
        <p class="mb-1"><strong>PS:</strong> {{ $setRental->ps->model_ps ?? '-' }}</p>
        <p class="mb-0 fw-semibold text-success">Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}/jam</p>
      </div>
      
      {{-- Tombol Edit, Hapus, Detail sejajar horizontal --}}
      <div class="btn-group btn-group-sm" role="group" aria-label="CRUD Buttons">
      <form action="{{ route('setrental.destroy', $setRental->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="d-inline">
          @csrf
          @method('DELETE')
          <button class="btn btn-sm btn-danger" type="submit" title="Hapus 🗑️">🗑️ Hapus</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Tombol pakai dan booking kanan, vertikal --}}
  <div class="d-flex flex-column gap-1 flex-shrink-0" style="min-width: 90px;">
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#pakaiModal{{ $setRental->id }}" title="Pakai 🚀">🚀</button>
    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $setRental->id }}" title="Booking 🛒">🛒</button>
    <a href="{{ route('setrental.show', $setRental->id) }}" class="btn btn-sm btn-info" title="Detail ℹ️">ℹ️</a>
    <a href="{{ route('setrental.edit', $setRental->id) }}" class="btn btn-sm btn-warning" title="Edit ✏️">✏️</a>
        

  </div>
  
</div>

  <!-- Modal Pakai Sekarang -->
 <div class="modal fade" id="pakaiModal{{ $setRental->id }}" tabindex="-1" aria-labelledby="pakaiModalLabel{{ $setRental->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Pakai Sekarang - {{ $setRental->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Harga per jam:</strong> Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}</p>
                    <p><strong>Waktu Mulai:</strong> Sekarang</p>
 <input type="hidden" name="id_set_rental" value="{{ $setRental->id }}">
                    <input type="hidden" name="id_rental" value="{{ $rental_id }}"> <!-- Assuming $rental_id is available in your view -->

                    <div class="mb-3">
                        <label class="form-label">Jumlah Jam</label>
                        <input type="number" name="jumlah_jam" min="1" class="form-control jumlah-jam"
                               data-id="pakai{{ $setRental->id }}" data-harga="{{ $setRental->harga_per_jam }}">
                    </div>
<div class="mb-3">
              <label class="form-label">Keterangan</label>
              <textarea name="keterangan" class="form-control" rows="2"></textarea>
            </div>
                    <div class="mb-3">
                        <label class="form-label">Total Harga</label>
                        <input type="text" name="total_harga" class="form-control total-harga" id="totalHargaPakai{{ $setRental->id }}" readonly>
                    </div>


                    <!-- Hidden input untuk status -->
                    <input type="hidden" name="status" value="bukan">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="submit">Konfirmasi Pakai</button>
                </div>
            </form>
        </div>
    </div>
</div>

  <!-- Modal Booking -->
  <div class="modal fade" id="bookingModal{{ $setRental->id }}" tabindex="-1" aria-labelledby="bookingModalLabel{{ $setRental->id }}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ route('transaksi.store') }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Booking - {{ $setRental->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <input type="hidden" name="id_set_rental" value="{{ $setRental->id }}">
          <input type="hidden" name="google" value="{{ $setRental->rental->google_id }}">
          <input type="hidden" name="id_rental" value="{{ $rental_id }}">

          <div class="modal-body">
            <p><strong>Harga per jam:</strong> Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}</p>

            <div class="mb-3">
              <label class="form-label">Waktu Mulai</label>
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="start_option" id="selesaiRadio{{ $setRental->id }}" value="selesai" checked>
                  <label class="form-check-label" for="selesaiRadio{{ $setRental->id }}">
                    Setelah pemain saat ini selesai
                  </label>
                </div>
                <div class="form-check mt-1">
                  <input class="form-check-input" type="radio" name="start_option" id="manualRadio{{ $setRental->id }}" value="manual">
                  <label class="form-check-label" for="manualRadio{{ $setRental->id }}">
                    Input manual waktu mulai
                  </label>
                </div>
              </div>

              <!-- Manual date & time input -->
              <div class="row mt-2" id="manualInputs{{ $setRental->id }}" style="display: none;">
              <div class="col-6">
                <div class="row g-2 align-items-center">
                  <div class="col">
                    <select name="manual_hour" class="form-select" required>
                      @for ($i = 0; $i <= 23; $i++)
                        <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-auto">:</div>
                  <div class="col">
                    <select name="manual_minute" class="form-select" required>
                      @for ($i = 0; $i <= 55; $i+=5)
                        <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                      @endfor
                    </select>
                  </div>
                </div>
              </div>
              <small class="text-danger mt-1 d-none" id="timeWarning{{ $setRental->id }}">
                Waktu tidak boleh lebih awal dari {{ $setRental->current_end_time ?? 'jadwal saat ini' }}
              </small>
            </div>
          </div>

            <div class="mb-3">
              <label class="form-label">Jumlah Jam</label>
              <input type="number" name="jumlah_jam" min="1" class="form-control jumlah-jam"
                data-id="booking{{ $setRental->id }}" data-harga="{{ $setRental->harga_per_jam }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Keterangan</label>
              <textarea name="keterangan" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Total Harga</label>
              <input type="text" name="total_harga" class="form-control total-harga" id="totalHargaBooking{{ $setRental->id }}" readonly>
            </div>
<div class="mb-3">
  <label class="form-label">Metode Pembayaran</label>
  <select name="payment_method" class="form-select select2-modal" required>
    <option value="">Pilih metode pembayaran</option>
    @foreach ($tripayChannels as $channel)
      @if ($channel['active']) {{-- hanya tampilkan yang aktif --}}
        <option value="{{ $channel['code'] }}" data-icon="{{ $channel['icon_url'] }}">
          {{ $channel['name'] }} - {{ $channel['group'] }}
        </option>
      @endif
    @endforeach
  </select>
</div>

            <!-- Hidden input untuk status -->
            <input type="hidden" name="status" value="booking">
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-success" type="submit">Konfirmasi Booking</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @endforeach
</div>

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
  
    $(document).ready(function () {
  // Saat modal ditampilkan, inisialisasi Select2
  $('div.modal').on('shown.bs.modal', function () {
    const $select = $(this).find('select.select2-modal');

    if ($select.length && !$select.hasClass("select2-hidden-accessible")) {
      $select.select2({
        dropdownParent: $(this), // supaya dropdown tampil di dalam modal
        templateResult: function (data) {
          if (!data.id) return data.text;
          return $(`<span><img src="${$(data.element).data('icon')}" style="width: 20px; margin-right: 8px;" /> ${data.text}</span>`);
        },
        templateSelection: function (data) {
          if (!data.id) return data.text;
          return $(`<span><img src="${$(data.element).data('icon')}" style="width: 20px; margin-right: 8px;" /> ${data.text}</span>`);
        }
      });
    }
  });
});

</script>

@endpush

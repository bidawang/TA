@extends('layout')

@section('content')
<h1 class="mb-4">Daftar SetRental</h1>
@error('tripay')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<a href="{{ route('setrental.create', ['rental_id' => $rental_id]) }}" class="btn btn-primary mb-4">➕ Tambah SetRental</a>

<div class="row">
  @foreach ($setRentals as $setRental)
  <div class="col-md-4">
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h5 class="card-title">{{ $setRental->name }}</h5>
        <p class="card-text"><strong>TV:</strong> {{ $setRental->tv->merek ?? 'Tidak ada TV' }}</p>
        <p class="card-text"><strong>PS:</strong> {{ $setRental->ps->model_ps ?? 'Tidak ada PS' }}</p>
        <p class="card-text"><strong>Harga per jam:</strong> Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}</p>

        <div class="d-flex flex-wrap gap-2 mt-3">
          <a href="{{ route('ps.show', $setRental->ps_id) }}" class="btn btn-sm btn-success">🎮 Game</a>
          <a href="{{ route('setrental.show', $setRental->id) }}" class="btn btn-sm btn-info">ℹ️ Detail</a>
          <a href="{{ route('setrental.edit', $setRental->id) }}" class="btn btn-sm btn-warning">✏️ Edit</a>
          <form action="{{ route('setrental.destroy', $setRental->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">🗑️ Hapus</button>
          </form>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#pakaiModal{{ $setRental->id }}">🚀 Pakai</button>
          <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $setRental->id }}">🛒 Booking</button>
        </div>
      </div>
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

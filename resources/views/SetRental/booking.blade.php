<!-- Modal Booking -->
<div class="modal fade" id="bookingModal{{ $setRental->id }}" tabindex="-1" aria-labelledby="bookingModalLabel{{ $setRental->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('transaksi.store') }}">
        @csrf
        <input type="hidden" name="id_set_rental" value="{{ $setRental->id }}">
        <input type="hidden" name="id_rental" value="{{ $setRental->rental_id }}">
        <input type="hidden" name="google" value="{{ $setRental->rental->google_id }}">
        <input type="hidden" name="status" value="booking">

        <div class="modal-header">
          <h5 class="modal-title" id="bookingModalLabel{{ $setRental->id }}">Booking - {{ $setRental->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <p class="mb-3 fw-semibold text-success">Harga per jam: Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}</p>

          <div class="mb-3">
            @if($setRental->status == 'dipakai')
            <label class="form-label">Waktu Mulai</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="start_option" id="selesaiRadio{{ $setRental->id }}" value="selesai" >
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
            @elseif($setRental->status != 'dipakai')
            <input type="hidden" name="start_option" value="manual" id="manualRadio{{ $setRental->id }}">
            @endif

            <div class="row mt-2" id="manualInputs{{ $setRental->id }}" style="display: none;">
              <div class="col-6">
                <div class="input-group">
                  <select name="manual_hour" class="form-select" required>
                    @for ($i = 0; $i <= 23; $i++)
                      <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                    @endfor
                  </select>
                  <span class="input-group-text">:</span>
                  <select name="manual_minute" class="form-select" required>
                    @for ($i = 0; $i <= 55; $i+=5)
                      <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                    @endfor
                  </select>
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
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional"></textarea>
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
                @if ($channel['active'])
                  <option value="{{ $channel['code'] }}" data-icon="{{ $channel['icon_url'] }}">
                    {{ $channel['name'] }} - {{ $channel['group'] }}
                  </option>
                @endif
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Konfirmasi Booking</button>
        </div>
      </form>
    </div>
  </div>
</div>

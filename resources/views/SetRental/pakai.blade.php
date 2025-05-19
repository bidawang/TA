<!-- Modal Pakai Sekarang -->
<div class="modal fade" id="pakaiModal{{ $setRental->id }}" tabindex="-1" aria-labelledby="pakaiModalLabel{{ $setRental->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('transaksi.store') }}">
        @csrf
        <input type="hidden" name="id_set_rental" value="{{ $setRental->id }}">
        <input type="hidden" name="id_rental" value="{{ $rental_id }}">
        <input type="hidden" name="status" value="bukan">

        <div class="modal-header">
          <h5 class="modal-title" id="pakaiModalLabel{{ $setRental->id }}">Pakai Sekarang - {{ $setRental->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <div class="mb-2 small text-muted">Waktu mulai: <strong>Sekarang</strong></div>
          <p class="mb-3 fw-semibold text-success">Harga per jam: Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}</p>

          <div class="mb-3">
            <label class="form-label">Jumlah Jam</label>
            <input type="number" name="jumlah_jam" min="1" class="form-control jumlah-jam"
                   data-id="pakai{{ $setRental->id }}" data-harga="{{ $setRental->harga_per_jam }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Total Harga</label>
            <input type="text" name="total_harga" class="form-control total-harga" id="totalHargaPakai{{ $setRental->id }}" readonly>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Konfirmasi Pakai</button>
        </div>
      </form>
    </div>
  </div>
</div>

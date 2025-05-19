<div class="col-12 mb-3">
  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body d-flex align-items-start gap-3 p-2">
      <!-- Gambar -->
      <div class="rounded-2 overflow-hidden" style="width: 64px; height: 64px;">
        <img src="{{ $setRental->foto ? asset('storage/'.$setRental->foto) : asset('images/placeholder.png') }}"
             alt="{{ $setRental->name }}"
             class="w-100 h-100"
             style="object-fit: cover;">
      </div>

      <!-- Info Utama -->
      <div class="flex-grow-1">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="fw-semibold text-primary small mb-1">{{ $setRental->name }}</div>
            <div class="text-muted small mb-1">
  <div><strong>TV:</strong> {{ $setRental->tv->merek ?? '-' }}</div>
  <div><strong>PS:</strong> {{ $setRental->ps->model_ps ?? '-' }}</div>
 @php
    use Carbon\Carbon;

    $jam = $setRental->transaksi->jam_selesai ?? null;

    if ($jam) {
        $waktu = Carbon::parse($jam);
        $formattedTime = $waktu->format('H:i');
        $hour = (int) $waktu->format('H');

        if ($hour >= 5 && $hour < 12) {
            $label = 'pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            $label = 'siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $label = 'sore';
        } else {
            $label = 'malam';
        }
    }
@endphp

@if($setRental->status == 'dipakai' && isset($formattedTime))
    <div class="text-danger">
        <strong>Dipakai sampai:</strong> {{ $formattedTime }} <span class="text-lowercase">({{ $label }})</span>
    </div>
@elseif($setRental->status == 'dipakai')
    <div class="text-danger"><strong>Dipakai:</strong> Jam tidak tersedia</div>
@endif

</div>

            <div class="text-dark small fw-semibold">Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}/jam</div>
          </div>
          <!-- Tombol Info -->
          <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $setRental->id }}" title="Lihat detail">
            <i class="bi bi-info-circle"></i>
          </button>
        </div>
      </div>

      <!-- Aksi -->
      <div class="d-flex flex-column gap-1 text-end">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#pakaiModal{{ $setRental->id }}" title="Pakai">
          🚀 Pakai
        </button>
        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $setRental->id }}" title="Booking">
          🛒 Booking
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Pakai & Booking -->
  @include('SetRental.pakai', ['setRental' => $setRental, 'rental_id' => $rental_id])
  @include('SetRental.booking', ['setRental' => $setRental, 'rental_id' => $rental_id, 'tripayChannels' => $tripayChannels])

  <!-- Modal Detail -->
  <div class="modal fade" id="detailModal{{ $setRental->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $setRental->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-3">
        <div class="modal-header border-0">
          <h5 class="modal-title">{{ $setRental->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body text-muted small">
          <p><strong>TV:</strong> {{ $setRental->tv->merek ?? '-' }}</p>
          <p><strong>PS:</strong> {{ $setRental->ps->model_ps ?? '-' }}</p>
          <p><strong>Harga per jam:</strong> Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}</p>
        </div>
        <div class="modal-footer border-0 d-flex justify-content-between">
          <form action="{{ route('setrental.destroy', $setRental->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">🗑️ Hapus</button>
          </form>
          <div class="d-flex gap-2">
            <a href="{{ route('setrental.edit', $setRental->id) }}" class="btn btn-sm btn-outline-warning">✏️ Edit</a>
            <a href="{{ route('setrental.show', $setRental->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

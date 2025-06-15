<div class="col-12 mb-1">
  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body d-flex gap-3 p-2">
      {{-- Thumbnail --}}
      <div class="rounded-2 overflow-hidden" style="width: 64px; height: 64px;">
        <img src="{{ $setRental->foto ? asset('storage/'.$setRental->foto) : asset('images/placeholder.png') }}"
             alt="{{ $setRental->name }}"
             class="w-100 h-100 object-fit-cover">
      </div>

      {{-- Info utama --}}
      <div class="flex-grow-1">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="fw-semibold text-primary small mb-1">{{ $setRental->name }}</div>
            <div class="text-muted small">
              <div>{{ $setRental->ps->model_ps ?? '-' }}</div>
              <div class="text-dark fw-semibold">Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}/jam</div>

              @php
                use Carbon\Carbon;
                $trans = $setRental->transaksi->first();
                $formattedTime = null;
                $label = null;

                if ($trans && $trans->jam_selesai) {
                  $waktu = Carbon::parse($trans->jam_selesai);
                  $formattedTime = $waktu->format('H:i');
                  $hour = (int) $waktu->format('H');

                  $label = match(true) {
                    $hour >= 5 && $hour < 12 => 'pagi',
                    $hour >= 12 && $hour < 15 => 'siang',
                    $hour >= 15 && $hour < 18 => 'sore',
                    default => 'malam'
                  };
                }
              @endphp
            </div>
          </div>

          {{-- Tombol info --}}
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $setRental->id }}" title="Lihat detail">
              <i class="bi bi-info-circle"></i>
            </button>
        </div>
      </div>

      {{-- Aksi --}}
      <div class="d-flex flex-column gap-1 text-end">
        @if(auth()->check() && auth()->user()?->role === 'developer' || auth()->check() && auth()->user()?->role === 'admin')
          @if($setRental->status !== 'dipakai')
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#pakaiModal{{ $setRental->id }}">🚀 Pakai</button>
          @endif
          @elseif(auth()->check())
          <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $setRental->id }}">🛒 Booking</button>
          @endif
        @if(auth()->check() && auth()->user()?->role === 'developer' || auth()->check() && auth()->user()?->role === 'admin')
    <form action="{{ $setRental->status === 'maintenance' ? route('setrental.aktifkan', $setRental->id) : route('setrental.maintenance', $setRental->id) }}"
          method="POST" class="form-switch mt-2 text-end">
        @csrf
        @method('PUT')
        <input type="checkbox"
               class="form-check-input"
               onchange="this.form.submit()"
               {{ $setRental->status === 'maintenance' ? '' : 'checked' }}
               role="switch"
               title="Status {{ $setRental->status === 'maintenance' ? 'Maintenance' : 'Aktif' }}">
        <label class="form-check-label small ms-2">
{{ $setRental->status === 'maintenance' ? 'Maintenance' : 'Aktif' }}
        </label>
    </form>
@endif

      </div>
    </div>
{{-- Status Dipakai --}}
@if(auth()->check() && (auth()->user()?->role === 'developer' || auth()->user()?->role === 'admin'))
    @if($setRental->status === 'dipakai')
        <div class="text-danger p-2 small d-flex justify-content-between align-items-center">
            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#selesaiModal{{ $setRental->id }}">Selesai</button>
            <div>
                @if($formattedTime)
                    <strong>Selesai jam</strong> {{ $formattedTime }} <span class="text-lowercase">({{ $label }})</span>
                @else
                    <strong>Dipakai:</strong> Jam tidak tersedia
                @endif
            </div>
        </div>
    @endif
@endif

{{-- Daftar Game --}}
@if($setRental->games->isNotEmpty())
    <div class="accordion mt-2" id="gamesAccordion{{ $setRental->id }}">
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="headingGames{{ $setRental->id }}">
                <button class="accordion-button collapsed px-2 py-1 small" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseGames{{ $setRental->id }}"
                        aria-expanded="false" aria-controls="collapseGames{{ $setRental->id }}">
                    🎮 Daftar Game
                </button>
            </h2>
            <div id="collapseGames{{ $setRental->id }}" class="accordion-collapse collapse"
                data-bs-parent="#gamesAccordion{{ $setRental->id }}">
                <div class="accordion-body p-2">
                    <ul class="list-group list-group-flush small">
                        @foreach($setRental->games as $game)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                {{ $game->name ?? 'Game tidak diketahui' }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Jadwal User --}}
@if(auth()->check() && $setRental->transaksi->where('google_id', $userGoogleId)->isNotEmpty())
    <div class="accordion mt-2" id="transaksiAccordion{{ $setRental->id }}">
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="headingTrans{{ $setRental->id }}">
                <button class="accordion-button collapsed px-2 py-1 small" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTrans{{ $setRental->id }}" aria-expanded="false"
                        aria-controls="collapseTrans{{ $setRental->id }}">
                    📄 Jadwal Anda
                </button>
            </h2>
            <div id="collapseTrans{{ $setRental->id }}" class="accordion-collapse collapse"
                data-bs-parent="#transaksiAccordion{{ $setRental->id }}">
                <div class="accordion-body p-2">
                    @foreach($setRental->transaksi->where('google_id', $userGoogleId) as $trans)
                        <div class="border rounded p-2 mb-2 bg-light shadow-sm">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold">🕒 {{ \Carbon\Carbon::parse($trans->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($trans->jam_selesai)->format('H:i') }}</span>
                                <span>{{ $trans->jumlah_jam }} jam</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

  </div>

  {{-- Modal --}}
  @include('setrental.pakai', ['setRental' => $setRental, 'rental_id' => $rental_id])
  @include('setrental.booking', ['setRental' => $setRental, 'rental_id' => $rental_id, 'tripayChannels' => $tripayChannels])

  {{-- Detail Modal --}}
  <div class="modal fade" id="detailModal{{ $setRental->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $setRental->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-3">
        <div class="modal-header border-0">
          <h5 class="modal-title">{{ $setRental->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body text-muted small">
          <p><strong>TV:</strong> {{ $setRental->tv->merek ?? '-' }}</p>
          <p><strong>PS:</strong> {{ $setRental->ps->model_ps ?? '-' }} {{ $setRental->ps->seri ?? '' }} </p>
          <p><strong>Penyimpanan:</strong> {{ $setRental->ps->storage ?? '-' }}</p>
          <p><strong>Harga per jam:</strong> Rp {{ number_format($setRental->harga_per_jam, 0, ',', '.') }}</p>
        </div>
                  @if(auth()->check() && auth()->user()?->role === 'developer' || auth()->check() && auth()->user()?->role === 'admin')

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
                  @endif

      </div>
    </div>
  </div>
</div>

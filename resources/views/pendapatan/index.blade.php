@extends('layout')

@section('content')
<div class="container py-2">
  <h4 class="mb-4 text-center">Pendapatan Rental</h4>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3 d-flex align-items-center">
        <i class="bi bi-shop me-2"></i> {{ $rental->nama }}
      </h5>
      <p><strong>Filter:</strong> {{ $filterLabel }}</p>
      <h4 class="text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>

      {{-- Saldo Dompet --}}
      <div class="d-flex align-items-center justify-content-between mb-3">
        @if(auth()->user()->role === 'admin')

        <p class="mb-0">
          <strong>Saldo Dompet:</strong>
          @if($userWallet)
          Rp {{ number_format($userWallet->balance, 0, ',', '.') }}
          @else
          <span class="text-muted">Tidak ada data dompet</span>
          @endif
        </p>

        @if($userWallet)
        <div class="btn-group btn-group-sm" role="group" aria-label="Wallet actions">
          <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#walletLogsModal">
            Lihat Log Dompet
          </button>

          <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#walletWithdrawModal">
            Tarik Saldo
          </button>
        </div>
        @endif
      </div>

      <!-- Modal Penarikan Saldo -->
      <div class="modal fade" id="walletWithdrawModal" tabindex="-1" aria-labelledby="walletWithdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{route('wallet.withdraw')}}">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="walletWithdrawModalLabel">Penarikan Saldo Dompet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <p>Saldo tersedia: <strong>Rp {{ number_format($userWallet->balance, 0, ',', '.') }}</strong></p>
                <div class="mb-3">
                  <label for="withdraw_amount" class="form-label">Jumlah Penarikan</label>
                  <input type="number" min="1" max="{{ $userWallet->balance }}" class="form-control" id="withdraw_amount" min="0" name="amount" required>
                </div>
                <div class="mb-3">
    <label for="withdraw_method" class="form-label">Metode Penarikan</label>

    @if($withdrawMethods->isEmpty())
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <span>Tidak ada metode wallet tersedia.</span>
            <a href="{{ route('wallet.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle"></i> Tambah Wallet
            </a>
        </div>
    @else
        <select class="form-select" id="withdraw_method" name="method" required onchange="fillProviderCode(this)">
            <option value="" disabled selected>-- Pilih Metode --</option>
            @foreach($withdrawMethods as $method)
                <option value="{{ $method->provider }}" data-kode="{{ $method->kode_provider }}">
                    {{ strtoupper($method->provider) }}
                </option>
            @endforeach
        </select>
    @endif
</div>

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ajukan Penarikan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              </div>
            </div>
          </form>
        </div>
        @endif
      </div>

      <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
        <a href="{{ route('pendapatan', $rental->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1 flex-sm-grow-0 text-center">
          Hari Ini
        </a>
        <form action="{{ route('pendapatan', $rental->id) }}" method="GET" class="mb-3">
          <div class="row g-2">
            <div class="col-5">
              <input type="date" name="start" class="form-control form-control-sm" required placeholder="Tanggal Mulai">
            </div>
            <div class="col-5">
              <input type="date" name="end" class="form-control form-control-sm" required placeholder="Tanggal Selesai">
            </div>
            <div class="col-2">
              <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                <i class="bi bi-search me-1"></i>
              </button>
            </div>
          </div>
        </form>


        {{-- Tombol buka modal logs --}}

      </div>
    </div>
  </div>
  <div id="transaksi-container">

    <div class="card shadow-sm rounded-3">
      <div class="card-body">
        <h6 class="mb-3 fw-semibold border-bottom pb-2 text-center" style="font-size: 1.1rem;">Detail Transaksi</h6>

        @forelse ($transaksis as $trx)
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom py-2 px-1">
          <div>
            <strong class="text-secondary">{{ ucfirst($trx->jenis) }}</strong><br>
            <small class="text-muted">{{ $trx->created_at->format('d M Y H:i') }}</small>
          </div>
          <div class="text-success fw-semibold fs-6">
            Rp {{ number_format($trx->total, 0, ',', '.') }}
          </div>
        </div>
        @empty
        <p class="text-muted fst-italic text-center py-3" style="font-size: 0.9rem;">Tidak ada transaksi pada periode ini.</p>
        @endforelse

        {{-- Pagination --}}
        @if ($transaksis->hasPages())
        <div class="mt-3 d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted">
              {{ $transaksis->currentPage() }} / {{ $transaksis->lastPage() }}
            </small>
          </div>
          <div class="btn-group" role="group" aria-label="Pagination">
            @if ($transaksis->onFirstPage())
            {{-- Kosongkan --}}
            @else
            <a href="{{ $transaksis->previousPageUrl() }}" class="btn btn-outline-secondary btn-sm">Sebelumnya</a>
            @endif

            @if ($transaksis->hasMorePages())
            <a href="{{ $transaksis->nextPageUrl() }}" class="btn btn-outline-secondary btn-sm">Berikutnya</a>
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>


</div>
</div>

<a href="{{ route('rental.show', $rental->id) }}" class="btn btn-secondary w-100 pb-4" style="border-radius: 0; font-size: 1rem;">
  Kembali ke Detail Rental
</a>
</div>

{{-- Modal Log Dompet --}}
<div class="modal fade" id="walletLogsModal" tabindex="-1" aria-labelledby="walletLogsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content rounded-3">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="walletLogsModalLabel" style="font-size:1.25rem;">Log Transaksi Dompet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" style="font-size: 0.95rem;">

        {{-- Nav tabs --}}
        <ul class="nav nav-tabs nav-fill" id="walletLogTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="in-tab" data-bs-toggle="tab" data-bs-target="#log-in" type="button" role="tab" aria-controls="log-in" aria-selected="true" style="font-size: 0.9rem;">Pemasukan (In)</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="out-tab" data-bs-toggle="tab" data-bs-target="#log-out" type="button" role="tab" aria-controls="log-out" aria-selected="false" style="font-size: 0.9rem;">Pengeluaran (Out)</button>
          </li>
        </ul>

        {{-- Tab panes --}}
        <div class="tab-content mt-3" id="walletLogTabsContent">

          {{-- Tab In --}}
          <div class="tab-pane fade show active" id="log-in" role="tabpanel" aria-labelledby="in-tab">
            @php
            $logsIn = $walletLogs->where('type', 'in');
            @endphp

            @if($logsIn->count())
            <ul class="list-group list-group-flush">
              @foreach($logsIn as $log)
              <li class="list-group-item d-flex justify-content-between align-items-start gap-3">
                <div style="font-size: 0.9rem;">
                  <strong>{{ ucfirst($log->type) }}</strong><br>
                  <small class="text-muted">{{ $log->created_at->format('d M Y H:i') }}</small><br>
                  @if($log->note)
                  <em class="fst-italic">Catatan: {{ $log->note }}</em>
                  @endif
                </div>
                <div class="text-success fw-semibold fs-5" style="font-size: 1.05rem;">
                  Rp {{ number_format($log->amount, 0, ',', '.') }}
                </div>
              </li>
              @endforeach
            </ul>
            @else
            <p class="text-muted fst-italic text-center py-3" style="font-size: 0.9rem;">Belum ada log pemasukan.</p>
            @endif
          </div>

          {{-- Tab Out --}}
          <div class="tab-pane fade" id="log-out" role="tabpanel" aria-labelledby="out-tab">
            @php
            $logsOut = $walletLogs->where('type', 'out');
            @endphp

            @if($logsOut->count())
            <ul class="list-group list-group-flush">
              @foreach($logsOut as $log)
              <li class="list-group-item d-flex justify-content-between align-items-start gap-3">
                <div style="font-size: 0.9rem;">
                  <strong>{{ ucfirst($log->type) }}</strong><br>
                  <small class="text-muted">{{ $log->created_at->format('d M Y H:i') }}</small><br>
                  @if($log->note)
                  <em class="fst-italic">Catatan: {{ $log->note }}</em>
                  @endif
                </div>
                <div class="text-danger fw-semibold fs-5" style="font-size: 1.05rem;">
                  Rp {{ number_format($log->amount, 0, ',', '.') }}
                </div>
              </li>
              @endforeach
            </ul>
            @else
            <p class="text-muted fst-italic text-center py-3" style="font-size: 0.9rem;">Belum ada log pengeluaran.</p>
            @endif
          </div>

        </div>

      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal" style="border-radius: 0; font-size: 0.9rem;">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    function loadPage(page) {
      const params = new URLSearchParams(window.location.search);
      params.set('page', page);

      fetch(`?${params.toString()}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(res => res.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const newContent = doc.querySelector('#transaksi-container').innerHTML;
          document.querySelector('#transaksi-container').innerHTML = newContent;
        });
    }

    document.addEventListener('click', function(e) {
      if (e.target.matches('.prev-page, .next-page')) {
        e.preventDefault();
        const page = e.target.getAttribute('data-page');
        if (page) loadPage(page);
      }
    });
  });
</script>

@endsection
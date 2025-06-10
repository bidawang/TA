@extends('layout')

@section('content')
<div class="container py-3">
    <h5 class="mb-3 text-center fw-bold">📤 Penarikan Tunai</h5>

    <!-- Search -->
    <div class="mb-3">
        <input type="text" id="wallet-search" class="form-control form-control-sm" placeholder="🔍 Cari nama atau metode...">
    </div>

    @if($wallet->isEmpty())
    <p class="text-center text-secondary fst-italic">Belum ada data Penarikan Tunai.</p>
    @else
    <!-- Tabs -->
    <ul class="nav nav-tabs nav-justified small mb-3" role="tablist">
        @foreach(['pending' => '⏳ Pending', 'disetujui' => '✅ Disetujui', 'ditolak' => '❌ Ditolak'] as $statusKey => $label)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#{{ $statusKey }}" type="button">
                {{ $label }}
            </button>
        </li>
        @endforeach
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        @foreach(['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $statusKey => $label)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $statusKey }}">
            @forelse($wallet->where('status', $statusKey) as $item)
            <form action="{{ route('wallet.updateStatus', $item->id) }}" method="POST" class="wallet-item mb-2 p-3 bg-white rounded shadow-sm">
                @csrf
                @method('PUT')
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-semibold wallet-nama">{{ $item->rental->nama ?? '-' }}</div>
                            <div class="small text-muted wallet-method">{{ $item->method }}</div>
                        </div>
                        <div class="text-end text-primary fw-semibold small">
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </div>
                    </div>

                    @if($item->status === 'pending')
                    <input type="hidden" name="alasan" value="">
                    <select name="status" class="form-select form-select-sm status-select" data-form-id="form-{{ $item->id }}">
                        <option value="pending" selected>Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    @elseif($item->status === 'disetujui')
                    <div class="text-success fw-semibold">Disetujui ✅</div>
                    @elseif($item->status === 'ditolak')
                    <div class="text-danger fw-semibold">
                        <small>Alasan: <br>{{ optional($item->penolakan)->keterangan ?? 'Tidak ada keterangan' }}</small>
                    </div>
                    @endif
                </div>
            </form>
            @empty
            <p class="text-center text-muted fst-italic">Tidak ada data {{ strtolower($label) }}.</p>
            @endforelse
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Modal Konfirmasi Penolakan -->
<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h6 class="modal-title" id="modalTolakLabel">❌ Konfirmasi Penolakan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Masukkan alasan penolakan:</p>
                <input type="text" id="inputAlasan" class="form-control" placeholder="Contoh: Data tidak valid" required>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-danger" id="btnSubmitTolak">Tolak Sekarang</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Pencarian nama & metode
    document.getElementById('wallet-search').addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.wallet-item').forEach(item => {
            const nama = item.querySelector('.wallet-nama').textContent.toLowerCase();
            const method = item.querySelector('.wallet-method').textContent.toLowerCase();
            item.style.display = (nama.includes(keyword) || method.includes(keyword)) ? '' : 'none';
        });
    });

    let formToSubmit = null;

    // Status select handler (hanya di status pending)
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const selectedValue = this.value;
            const form = this.closest('form');

            if (selectedValue === 'ditolak') {
                formToSubmit = form;
                new bootstrap.Modal(document.getElementById('modalTolak')).show();
            } else {
                form.submit();
            }
        });
    });

    // Submit dari modal
    document.getElementById('btnSubmitTolak').addEventListener('click', function() {
        const alasanInput = document.getElementById('inputAlasan');
        const reason = alasanInput.value.trim();

        if (!reason) {
            alert('Alasan penolakan wajib diisi!');
            return;
        }

        if (formToSubmit) {
            formToSubmit.querySelector('input[name="alasan"]').value = reason;
            formToSubmit.submit();
        }
    });
</script>
@endsection
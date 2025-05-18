@extends('layout')

@section('content')
<div class="container py-3">
    <h3 class="mb-4 text-center fw-bold">Penarikan Tunai</h3>

    <div class="mb-3">
        <input type="text" id="wallet-search" class="form-control form-control-sm" placeholder="Cari nama rental atau metode...">
    </div>

    @if($wallet->isEmpty())
        <p class="text-center text-secondary fst-italic">Belum ada data Penarikan Tunai.</p>
    @else
        <div id="wallet-list">
            @foreach($wallet as $item)
                <form action="{{ route('wallet.updateStatus', $item->id) }}" method="POST" class="wallet-item mb-2 border rounded shadow-sm p-2">
                    @csrf
                    @method('PUT')
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="flex-grow-1">
                            <div class="fw-semibold wallet-nama">{{ $item->rental->nama ?? '-' }}</div>
                            <div class="text-muted small">Rp {{ number_format($item->amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-end wallet-method text-muted small">{{ $item->method }}</div>
                        <div>
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 110px;">
                                <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ $item->status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ $item->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    @endif
</div>

<script>
    document.getElementById('wallet-search').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        const items = document.querySelectorAll('.wallet-item');

        items.forEach(item => {
            const nama = item.querySelector('.wallet-nama').textContent.toLowerCase();
            const method = item.querySelector('.wallet-method').textContent.toLowerCase();

            item.style.display = (nama.includes(keyword) || method.includes(keyword)) ? '' : 'none';
        });
    });
</script>
@endsection

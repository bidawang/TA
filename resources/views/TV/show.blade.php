@extends('layout')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 text-center">Detail PS</h4>

    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        @if ($ps->foto)
            <img src="{{ asset('storage/' . $ps->foto) }}" class="card-img-top" alt="Foto PS" style="max-height: 300px; object-fit: contain;">
        @endif

        <div class="card-body">
            <h5 class="card-title">{{ $ps->model_ps }}</h5>
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><strong>Storage:</strong> {{ $ps->storage }}</li>
                <li class="list-group-item"><strong>Seri:</strong> {{ $ps->seri }}</li>
            </ul>

            {{-- Tambah Game --}}
            <div class="mb-4">
                <h6 class="mb-2">Tambah Game ke PS</h6>
                <form action="{{ route('gameps.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ps_id" value="{{ $ps->id }}">
                    <div class="mb-3">
                        <label for="games" class="form-label">Pilih Game</label>
                        <select name="games[]" id="games" class="form-select select2" multiple>
                            @foreach($games as $game)
                                <option value="{{ $game->id }}">{{ $game->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Game</button>
                </form>
            </div>

            {{-- Game yang Dimiliki --}}
            <div class="mt-5">
                <h6 class="mb-3">Game yang Dimiliki</h6>

                <div class="mb-2">
                    <input type="text" id="searchGame" class="form-control mb-2" placeholder="Cari game...">
                    <div class="form-check">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                        <label for="selectAll" class="form-check-label">Pilih Semua</label>
                    </div>
                </div>

                 <form action="{{ route('gameps.bulkDelete') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus game yang dipilih?')">
                    @csrf
                    <ul id="ownedGamesList" class="list-group">
                        @forelse($ownedGames as $owned)
                            <li class="list-group-item d-flex align-items-center owned-game-item">
                                <input type="checkbox" name="selected_games[]" value="{{ $owned->id }}" class="form-check-input me-2 game-checkbox">
                                <span class="game-name">{{ $owned->game->name }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada game yang ditambahkan</li>
                        @endforelse
                    </ul>

                    @if($ownedGames->count())
                        <button type="submit" class="btn btn-danger btn-sm mt-3 w-100">Hapus Game Terpilih</button>
                    @endif
                </form> 
            </div>

            <a href="{{ route('ps.index') }}" class="btn btn-secondary w-100 mt-4">Kembali</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: "Pilih game yang tersedia",
        width: '100%'
    });

    $('#searchGame').on('input', function () {
        const keyword = $(this).val().toLowerCase().trim();
        $('#ownedGamesList .owned-game-item').each(function () {
            const gameName = $(this).find('.game-name').text().toLowerCase();
            $(this).toggle(gameName.includes(keyword));
        });
    });

    $('#selectAll').on('change', function () {
        $('.game-checkbox').prop('checked', this.checked);
    });

    $(document).on('change', '.game-checkbox', function () {
        if (!$(this).is(':checked')) {
            $('#selectAll').prop('checked', false);
        }
    });
});
</script>
@endpush

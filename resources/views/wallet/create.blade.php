@extends('layout')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Tambah Metode Pembayaran</h3>

    <form action="{{ route('wallet.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
    <label for="provider" class="form-label">Provider</label>
    <select name="provider" id="provider" class="form-select @error('provider') is-invalid @enderror" required>
        <option value="">-- Pilih Provider --</option>
        @foreach($providers as $key => $label)
            <option value="{{ $key }}" {{ old('provider') == $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('provider')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


        <div class="mb-3">
            <label for="kode_provider" class="form-label">Kode Provider</label>
            <input type="text" id="kode_provider" name="kode_provider" class="form-control @error('kode_provider') is-invalid @enderror" value="{{ old('kode_provider') }}" required>
            @error('kode_provider')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- id_rental tidak ditampilkan, otomatis diambil dari backend --}}
        
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('wallet.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

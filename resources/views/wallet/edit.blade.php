@extends('layout')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Edit Metode Pembayaran</h3>

    <form action="{{ route('wallet.update', $wallet->id_wallet) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="provider" class="form-label">Provider</label>
            <select name="provider" id="provider" class="form-select @error('provider') is-invalid @enderror" required>
                <option value="">-- Pilih Provider --</option>
                @foreach ($providers as $key => $label)
                <option value="{{ $key }}" {{ $wallet->provider === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('provider')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="kode_provider" class="form-label">Kode Provider</label>
            <input type="text" id="kode_provider" name="kode_provider" class="form-control @error('kode_provider') is-invalid @enderror" value="{{ old('kode_provider', $wallet->kode_provider) }}" required>
            @error('kode_provider')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('user.profile', auth()->user()->google_id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
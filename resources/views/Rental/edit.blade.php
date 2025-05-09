@extends('layout')

@section('content')
<div class="container">
    <h1>Edit Rental dan Alamat</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan saat mengisi formulir:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rental.update', $rental->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nama dan NIK Rental -->
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Rental</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $rental->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $rental->nik) }}" required>
        </div>

        <!-- Deskripsi -->
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi">{{ old('deskripsi', $rental->deskripsi) }}</textarea>
        </div>

        <!-- Logo -->
        <div class="mb-3">
            <label for="logo" class="form-label">Logo (Opsional)</label>
            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
            @if ($rental->logo)
                <div class="mt-3">
                    <label>Logo Saat Ini</label><br>
                    <img src="{{ asset('storage/' . $rental->logo) }}" alt="Logo" style="max-width: 200px;">
                </div>
            @endif
        </div>

        <hr>

        <!-- Switch Edit Alamat -->
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="toggleAlamat" name="edit_alamat" {{ old('edit_alamat') ? 'checked' : '' }}>
            <label class="form-check-label" for="toggleAlamat">Edit Alamat</label>
        </div>

        <!-- Form Alamat -->
        <div id="formAlamat" style="display: none;">
            <h5>Alamat</h5>

            <div class="mb-3">
                <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                <input type="text" class="form-control" id="alamat_lengkap" name="alamat_lengkap" value="{{ old('alamat_lengkap', $rental->alamat->alamat_lengkap) }}">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="provinsi" class="form-label">Provinsi</label>
                    <select class="form-control select2" id="provinsi" name="provinsi">
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kota" class="form-label">Kota</label>
                    <select class="form-control select2" id="kota" name="kota">
                        <option value="">Pilih Kota</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kecamatan" class="form-label">Kecamatan</label>
                    <select class="form-control select2" id="kecamatan" name="kecamatan">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kelurahan" class="form-label">Kelurahan</label>
                    <select class="form-control select2" id="kelurahan" name="kelurahan">
                        <option value="">Pilih Kelurahan</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="rt" class="form-label">RT</label>
                    <input type="text" class="form-control" id="rt" name="rt" value="{{ old('rt', $rental->alamat->rt) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="rw" class="form-label">RW</label>
                    <input type="text" class="form-control" id="rw" name="rw" value="{{ old('rw', $rental->alamat->rw) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_pos" class="form-label">Kode Pos</label>
                    <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $rental->alamat->kode_pos) }}">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();

        // Toggle alamat
        function toggleAlamatVisibility() {
            var isChecked = document.getElementById('toggleAlamat').checked;
            document.getElementById('formAlamat').style.display = isChecked ? 'block' : 'none';
        }

        // On page load
        toggleAlamatVisibility();

        document.getElementById('toggleAlamat').addEventListener('change', toggleAlamatVisibility);

        // Load Provinsi
        $.ajax({
            url: 'https://api.goapi.io/regional/provinsi?api_key=c4ec9697-2589-5b14-d981-5c35d458',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                let options = '<option value="">Pilih Provinsi</option>';
                (data.data || []).forEach(function (provinsi) {
                    options += `<option value="${provinsi.id}|${provinsi.name}">${provinsi.name}</option>`;
                });
                $('#provinsi').html(options);
            }
        });

        // Load Kota saat Provinsi berubah
        $('#provinsi').change(function () {
            var provinsiId = $(this).val().split('|')[0];
            $('#kota').html('<option value="">Pilih Kota</option>');
            $.get(`https://api.goapi.io/regional/kota?provinsi_id=${provinsiId}&api_key=c4ec9697-2589-5b14-d981-5c35d458`, function (data) {
                let options = '<option value="">Pilih Kota</option>';
                (data.data || []).forEach(function (kota) {
                    options += `<option value="${kota.id}|${kota.name}">${kota.name}</option>`;
                });
                $('#kota').html(options);
            });
        });

        // Load Kecamatan saat Kota berubah
        $('#kota').change(function () {
            var kotaId = $(this).val().split('|')[0];
            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
            $.get(`https://api.goapi.io/regional/kecamatan?kota_id=${kotaId}&api_key=c4ec9697-2589-5b14-d981-5c35d458`, function (data) {
                let options = '<option value="">Pilih Kecamatan</option>';
                (data.data || []).forEach(function (kecamatan) {
                    options += `<option value="${kecamatan.id}|${kecamatan.name}">${kecamatan.name}</option>`;
                });
                $('#kecamatan').html(options);
            });
        });

        // Load Kelurahan saat Kecamatan berubah
        $('#kecamatan').change(function () {
            var kecamatanId = $(this).val().split('|')[0];
            $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
            $.get(`https://api.goapi.io/regional/kelurahan?kecamatan_id=${kecamatanId}&api_key=c4ec9697-2589-5b14-d981-5c35d458`, function (data) {
                let options = '<option value="">Pilih Kelurahan</option>';
                (data.data || []).forEach(function (kelurahan) {
                    options += `<option value="${kelurahan.id}|${kelurahan.name}">${kelurahan.name}</option>`;
                });
                $('#kelurahan').html(options);
            });
        });
    });
</script>
@endsection

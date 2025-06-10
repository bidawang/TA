@extends('layout')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Tambah Rental</h1>

    <form action="{{ route('rental.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Informasi Rental -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <strong>Informasi Rental</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Rental</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" pattern="^[0-9]{16}$" title="NIK harus 16 digit angka" required>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input type="number" class="form-control" id="no_hp" min="0" title="Nomor HP harus berupa angka dan tidak boleh negatif" name="no_hp" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo Rental</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <strong>Alamat Lengkap</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="alamat_lengkap" class="form-label">Alamat Jalan / Nomor Rumah</label>
                    <input type="text" class="form-control" id="alamat_lengkap" name="alamat_lengkap" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <select class="form-control select2" id="provinsi" name="provinsi" required>
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kota" class="form-label">Kota/Kabupaten</label>
                        <select class="form-control select2" id="kota" name="kota" required>
                            <option value="">Pilih Kota</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kecamatan" class="form-label">Kecamatan</label>
                        <select class="form-control select2" id="kecamatan" name="kecamatan" required>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kelurahan" class="form-label">Kelurahan</label>
                        <select class="form-control select2" id="kelurahan" name="kelurahan" required>
                            <option value="">Pilih Kelurahan</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="rt" class="form-label">RT</label>
                        <input type="text" class="form-control" id="rt" name="rt" min="0" title="RT harus berupa angka positif" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="rw" class="form-label">RW</label>
                        <input type="text" class="form-control" id="rw" name="rw" min="0" title="RW harus berupa angka positif" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kode_pos" class="form-label">Kode Pos</label>
                    <input type="text" class="form-control" id="kode_pos" min="0" name="kode_pos" required>
                </div>
            </div>
        </div>

        <!-- Data Wallet -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Data Wallet <small class="text-muted">(untuk penarikan uang)</small></strong>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-wallet">
                    <i class="bi bi-plus-circle"></i> Tambah Wallet
                </button>
            </div>
            <div class="card-body" id="wallet-container">
                <div class="wallet-entry row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Provider</label>
                        <input type="text" name="provider[]" class="form-control" placeholder="Misal: Gopay" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kode Provider</label>
                        <input type="text" name="kode_provider[]" class="form-control" placeholder="Misal: GOPAY123" required>
                    </div>
                    <div class="col-12 text-end mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-wallet">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save"></i> Simpan Data
            </button>
        </div>
    </form>
</div>

<!-- Script GoAPI + Wallet -->
<script>
    $(document).ready(function () {
        const goapiKey = "{{ env('GOAPI_KEY') }}";
        $('.select2').select2();

        function loadSelect(url, target, label, valueKey = 'id', textKey = 'name') {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    let options = `<option value="">Pilih ${label}</option>`;
                    if (Array.isArray(data?.data)) {
                        data.data.forEach(item => {
                            options += `<option value="${item[valueKey]}|${item[textKey]}">${item[textKey]}</option>`;
                        });
                    }
                    $(target).html(options);
                }
            });
        }

        loadSelect(`https://api.goapi.io/regional/provinsi?api_key=${goapiKey}`, '#provinsi', 'Provinsi');

        $('#provinsi').change(function () {
            const id = $(this).val().split('|')[0];
            $('#kota, #kecamatan, #kelurahan').html('<option value="">Memuat...</option>');
            loadSelect(`https://api.goapi.io/regional/kota?provinsi_id=${id}&api_key=${goapiKey}`, '#kota', 'Kota');
        });

        $('#kota').change(function () {
            const id = $(this).val().split('|')[0];
            $('#kecamatan, #kelurahan').html('<option value="">Memuat...</option>');
            loadSelect(`https://api.goapi.io/regional/kecamatan?kota_id=${id}&api_key=${goapiKey}`, '#kecamatan', 'Kecamatan');
        });

        $('#kecamatan').change(function () {
            const id = $(this).val().split('|')[0];
            $('#kelurahan').html('<option value="">Memuat...</option>');
            loadSelect(`https://api.goapi.io/regional/kelurahan?kecamatan_id=${id}&api_key=${goapiKey}`, '#kelurahan', 'Kelurahan');
        });

        $('#add-wallet').click(function () {
            const walletEntry = `
                <div class="wallet-entry row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Provider</label>
                        <input type="text" name="provider[]" class="form-control" placeholder="Misal: DANA" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kode Provider</label>
                        <input type="text" name="kode_provider[]" class="form-control" placeholder="Misal: DANA987" required>
                    </div>
                    <div class="col-12 text-end mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-wallet">Hapus</button>
                    </div>
                </div>`;
            $('#wallet-container').append(walletEntry);
        });

        $(document).on('click', '.remove-wallet', function () {
            $(this).closest('.wallet-entry').remove();
        });
    });
</script>
@endsection

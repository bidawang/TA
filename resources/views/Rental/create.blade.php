@extends('layout')

@section('content')
    <div class="container">
        <h1>Tambah Rental dan Alamat</h1>

        <form action="{{ route('rental.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama dan NIK Rental -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Rental</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" class="form-control" id="nik" name="nik" required>
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor HP</label>
                <input type="number" class="form-control" id="no_hp" name="no_hp" required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
            </div>

            <!-- Logo -->
            <div class="mb-3">
                <label for="logo" class="form-label">Logo Rental</label>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
            </div>

            <hr>

            <!-- Alamat -->
            <h5>Alamat</h5>

            <div class="mb-3">
                <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
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
                    <label for="kota" class="form-label">Kota</label>
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
                    <input type="text" class="form-control" id="rt" name="rt" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="rw" class="form-label">RW</label>
                    <input type="text" class="form-control" id="rw" name="rw" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_pos" class="form-label">Kode Pos</label>
                    <input type="text" class="form-control" id="kode_pos" name="kode_pos" required>
                </div>
            </div>

            <hr>
<h5 class="mt-4">Data Wallet</h5>
<div id="wallet-container">
    <div class="wallet-entry row g-2 mb-3">
        <div class="col-12">
            <input type="text" name="provider[]" class="form-control" placeholder="Nama Provider (misal: Gopay)" required>
        </div>
        <div class="col-12">
            <input type="text" name="kode_provider[]" class="form-control" placeholder="Kode Provider (misal: GOPAY123)" required>
        </div>
        <div class="col-12 text-end">
            <button type="button" class="btn btn-outline-danger btn-sm remove-wallet">Hapus</button>
        </div>
    </div>
</div>

<div class="d-grid mb-3">
    <button type="button" class="btn btn-outline-primary" id="add-wallet">
        <i class="bi bi-plus-circle"></i> Tambah Wallet
    </button>
</div>

<br><br>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
    // Inisialisasi select2
    $('.select2').select2();

    // Load Provinsi
    $.ajax({
        url: 'https://api.goapi.io/regional/provinsi?api_key=c4ec9697-2589-5b14-d981-5c35d458',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            var options = '<option value="">Pilih Provinsi</option>';
            if (Array.isArray(data)) {
                data.forEach(function(provinsi) {
                    options += '<option value="' + provinsi.id + '|' + provinsi.name + '">' + provinsi.name + '</option>';
                });
            } else if (data && Array.isArray(data.data)) {
                data.data.forEach(function(provinsi) {
                    options += '<option value="' + provinsi.id + '|' + provinsi.name + '">' + provinsi.name + '</option>';
                });
            }
            $('#provinsi').html(options);
        }
    });

    // Event handler untuk perubahan provinsi
    $('#provinsi').change(function() {
        var provinsi_data = $(this).val();
        if (provinsi_data) {
            var provinsi_parts = provinsi_data.split('|');
            var id_provinsi = provinsi_parts[0];
            var provinsi_name = provinsi_parts[1];

            $('#kota').html('<option value="">Pilih Kota</option>'); // Reset kota
            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>'); // Reset kecamatan
            $('#kelurahan').html('<option value="">Pilih Kelurahan</option>'); // Reset kelurahan

            if (id_provinsi) {
                $.ajax({
                    url: 'https://api.goapi.io/regional/kota?provinsi_id=' + id_provinsi + '&api_key=c4ec9697-2589-5b14-d981-5c35d458',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">Pilih Kota</option>';
                        if (Array.isArray(data)) {
                            data.forEach(function(kota) {
                                options += '<option value="' + kota.id + '|' + kota.name + '">' + kota.name + '</option>';
                            });
                        } else if (data && Array.isArray(data.data)) {
                            data.data.forEach(function(kota) {
                                options += '<option value="' + kota.id + '|' + kota.name + '">' + kota.name + '</option>';
                            });
                        }
                        $('#kota').html(options);
                    }
                });
            }
        }
    });

    // Event handler untuk perubahan kota
    $('#kota').change(function() {
        var kota_data = $(this).val();
        if (kota_data) {
            var kota_parts = kota_data.split('|');
            var id_kota = kota_parts[0];
            var kota_name = kota_parts[1];

            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>'); // Reset kecamatan
            $('#kelurahan').html('<option value="">Pilih Kelurahan</option>'); // Reset kelurahan

            if (id_kota) {
                $.ajax({
                    url: 'https://api.goapi.io/regional/kecamatan?kota_id=' + id_kota + '&api_key=c4ec9697-2589-5b14-d981-5c35d458',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">Pilih Kecamatan</option>';
                        if (Array.isArray(data)) {
                            data.forEach(function(kecamatan) {
                                options += '<option value="' + kecamatan.id + '|' + kecamatan.name + '">' + kecamatan.name + '</option>';
                            });
                        } else if (data && Array.isArray(data.data)) {
                            data.data.forEach(function(kecamatan) {
                                options += '<option value="' + kecamatan.id + '|' + kecamatan.name + '">' + kecamatan.name + '</option>';
                            });
                        }
                        $('#kecamatan').html(options);
                    }
                });
            }
        }
    });

    // Event handler untuk perubahan kecamatan
    $('#kecamatan').change(function() {
        var kecamatan_data = $(this).val();
        if (kecamatan_data) {
            var kecamatan_parts = kecamatan_data.split('|');
            var id_kecamatan = kecamatan_parts[0];
            var kecamatan_name = kecamatan_parts[1];

            $('#kelurahan').html('<option value="">Pilih Kelurahan</option>'); // Reset kelurahan

            if (id_kecamatan) {
                $.ajax({
                    url: 'https://api.goapi.io/regional/kelurahan?kecamatan_id=' + id_kecamatan + '&api_key=c4ec9697-2589-5b14-d981-5c35d458',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">Pilih Kelurahan</option>';
                        if (Array.isArray(data)) {
                            data.forEach(function(kelurahan) {
                                options += '<option value="' + kelurahan.id + '|' + kelurahan.name + '">' + kelurahan.name + '</option>';
                            });
                        } else if (data && Array.isArray(data.data)) {
                            data.data.forEach(function(kelurahan) {
                                options += '<option value="' + kelurahan.id + '|' + kelurahan.name + '">' + kelurahan.name + '</option>';
                            });
                        }
                        $('#kelurahan').html(options);
                    }
                });
            }
        }
    });
});

// Tambah baris wallet baru
$('#add-wallet').click(function() {
    const walletEntry = `
        <div class="wallet-entry row g-2 mb-3">
            <div class="col-12">
                <input type="text" name="provider[]" class="form-control" placeholder="Nama Provider" required>
            </div>
            <div class="col-12">
                <input type="text" name="kode_provider[]" class="form-control" placeholder="Kode Provider" required>
            </div>
            <div class="col-12 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-wallet">Hapus</button>
            </div>
        </div>`;
    $('#wallet-container').append(walletEntry);
});

$(document).on('click', '.remove-wallet', function() {
    $(this).closest('.wallet-entry').remove();
});


    </script>
@endsection

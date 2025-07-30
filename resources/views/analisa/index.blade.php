@extends('layouts.bootstrap')
@section('judul-content','Analisa')
@section('content')
<div class="container py-5">
    <div id="notification-area"></div>
    <h5 class="mb-3 fw-bold">Penghasilan Calon Nasabah</h5>

    <div class="mb-3">
        <input type="text" name="nik" id="nik" class="form-control" placeholder="Masukan NIK">
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <p class="mb-2 fw-bold" id="nama">-</p>
                    <h4 class="fw-bold"><span id="penghasilan">Rp 0</span> <small class="fw-normal">per bulan</small></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <p class="mb-2 fw-bold">Jumlah Pengajuan</p>
                    <h4 class="fw-bold"><span id="jumlah_pengajuan">Rp 0</span></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <p class="mb-2 fw-bold">Tenor</p>
                    <h4 class="fw-bold" id="tenor">-</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <label for="totalpenghasilan">Total Penghasilan</label>
            <input type="number" class="form-control" placeholder="Total Penghasilan" id="totalpenghasilan" value="0" readonly>
        </div>
        <div class="col-md-4 mb-3">
            <label for="angsuran_display">Angsuran</label>
            <input type="text" id="angsuran_display" class="form-control format-rupiah" data-target="#angsuran" placeholder="Masukkan Angsuran">
            <input type="hidden" name="angsuran" id="angsuran">
        </div>
        <div class="col-md-4 mb-3">
            <label for="angsuran_lain_display">Angsuran di Bank Lain</label>
            <input type="text" id="angsuran_lain_display" class="form-control format-rupiah" data-target="#angsuran_lain" placeholder="Masukkan Angsuran Lain">
            <input type="hidden" name="angsuran_lain" id="angsuran_lain">
        </div>
        <input type="hidden" id="id_user">
        <input type="hidden" id="id_klasifikasi">
    </div>

    <div class="mb-4">
        <button class="btn btn-success w-100" id="btnAnalisa">Analisa</button>
    </div>

    <h5 class="mb-3 fw-bold">Analisa DBR</h5>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-success">
                <tr>
                    <th>Komponen</th>
                    <th>Angsuran per Bulan</th>
                    <th>Hasil</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Penghasilan</td>
                    <td><span id="penghasilantbl"></span></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Total Angsuran</td>
                    <td><span id="angsurantbl"></span></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Total Angsuran di Bank lain</td>
                    <td><span id="angsuranlaintbl"></span></td>
                    <td>-</td>
                </tr>
                <tr class="table-success fw-bold">
                    <td>DBR</td>
                    <td colspan="2"><span id="dbrtbl"></span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="alert alert-success mt-3 fw-bold">
        Hasil DBR nasabah adalah <span id="dbr-akhir"></span>% yang berarti pengajuan pembiayaan nasabah <span id="psn"></span>.
    </div>
</div>
<script>
    document.getElementById('nik').addEventListener('input', function () {
        fetch(`/analisa/fetch/${this.value}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    console.log(data);
                    $("#nama").html(data.nama);
                    $("#id_user").val(data.id_user);
                    $("#id_klasifikasi").val(data.id_klasifikasi);
                    let format = new Intl.NumberFormat("id-ID").format(data.penghasilan);
                    $('#penghasilan').html("Rp. "+format);
                    let jml_pengajuan = new Intl.NumberFormat("id-ID").format(data.jumlah_pengajuan);
                    $('#jumlah_pengajuan').html("Rp. "+jml_pengajuan);
                    $('#totalpenghasilan').val(data.penghasilan);
                    $('#tenor').html(data.tenor+'<small class="fw-normal"> bulan</small>');
                }
            });
    });
</script>
@endsection
@section('js-ready')
    // Untuk mengambil data nasabah (menggunakan jQuery) 
    $('#nik').on('input', function () {
        let nikValue = $(this).val();
        
        // Optimasi: Hanya cari jika NIK memiliki 16 digit
        if (nikValue.length === 16) {
            $.get(`/analisa/fetch/${nikValue}`, function(data) {
                if (data) {
                    $('#nama').html(data.nama);
                    $('#id_user').val(data.id_user);
                    $('#id_klasifikasi').val(data.id_klasifikasi);
                    
                    let formatPenghasilan = new Intl.NumberFormat("id-ID").format(data.penghasilan);
                    $('#penghasilan').html("Rp. " + formatPenghasilan);
                    
                    let formatPengajuan = new Intl.NumberFormat("id-ID").format(data.jumlah_pengajuan);
                    $('#jumlah_pengajuan').html("Rp. " + formatPengajuan);
                    
                    $('#totalpenghasilan').val(data.penghasilan);
                    $('#tenor').html(data.tenor + '<small class="fw-normal"> bulan</small>');
                } else {
                    // (Opsional) Beri tahu jika NIK tidak ditemukan
                    $('#nama').html('<span class="text-danger">NIK tidak ditemukan</span>');
                }
            }).fail(function() {
                $('#nama').html('<span class="text-danger">Error saat mengambil data</span>');
            });
        }
    });

    // Untuk format angka otomatis 
    $('.format-rupiah').on('input', function(e) {
        let rawValue = $(this).val().replace(/[^0-9]/g, '');
        let target = $(this).data('target');
        $(target).val(rawValue);

        if (rawValue) {
            let formattedValue = new Intl.NumberFormat('id-ID').format(rawValue);
            $(this).val(formattedValue);
        } else {
            $(this).val('');
        }
    });

    // Utama untuk tombol Analisa 
    $("#btnAnalisa").click(function(e) {
        e.preventDefault();

        // Validasi
        let nik = $('#nik').val();
        let angsuranDisplayValue = $('#angsuran_display').val();
        let angsuranLainDisplayValue = $('#angsuran_lain_display').val();
        let totalpenghasilanValue = $('#totalpenghasilan').val();

        if (!nik || totalpenghasilanValue === '0' || totalpenghasilanValue === '') {
            Swal.fire('Data Belum Lengkap', 'Harap masukkan NIK yang valid dan pastikan data nasabah telah dimuat.', 'warning');
            return;
        }
        if (!angsuranDisplayValue || !angsuranLainDisplayValue) {
            Swal.fire('Data Belum Lengkap', 'Harap isi kolom "Angsuran" dan "Angsuran di Bank Lain".', 'warning');
            return;
        }

        // Kalkulasi
        let angsuran = parseFloat($('#angsuran').val());
        let angsuranLain = parseFloat($('#angsuran_lain').val());
        let totalpenghasilan = parseFloat(totalpenghasilanValue);
        
        // Perbaikan: Mencegah pembagian dengan nol
        if (totalpenghasilan === 0) {
            Swal.fire('Input Salah', 'Total Penghasilan tidak boleh nol.', 'error');
            return;
        }

        let dbr = ((angsuran + angsuranLain) / totalpenghasilan) * 100;
        let dbrBulat = Math.round(dbr); // Pembulatan DBR

        // Tampilkan hasil di tabel
        let penghasilanrp = new Intl.NumberFormat("id-ID").format(totalpenghasilan);
        let angsuranrp = new Intl.NumberFormat("id-ID").format(angsuran);
        let angsuranlainrp = new Intl.NumberFormat("id-ID").format(angsuranLain);
        
        $("#penghasilantbl").html('Rp. ' + penghasilanrp);
        $("#angsurantbl").html('Rp. ' + angsuranrp);
        $("#angsuranlaintbl").html('Rp. ' + angsuranlainrp);
        $("#dbrtbl").html(dbrBulat + '%');
        
        let psn = 'dapat direkomendasikan';
        if (dbrBulat >= 80) { // Gunakan dbrBulat untuk konsistensi
            psn = 'tidak direkomendasikan';
        }

        $("#dbr-akhir").html(dbrBulat);
        $("#psn").html(psn);
        
        // Kirim data ke server via AJAX
        $.ajax({
            url: "{{ route('analisa.store') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                total_penghasilan: totalpenghasilan,
                angsuran_pengajuan: angsuran,
                angsuran_lain: angsuranLain,
                dbr: dbr,
                id_user: $("#id_user").val(),
                id_klasifikasi: $("#id_klasifikasi").val(),
                nama: $("#nama").html(),
                nik: nik
            },
            success: function(res) {
                // Tampilkan notifikasi sukses dengan SweetAlert2
                Swal.fire({
                    title: 'Analisa Berhasil!',
                    html: `Data telah disimpan.<br>Hasil DBR: <b>${parseFloat(res.dbr).toFixed(2)}%</b>`,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });

                // Nonaktifkan tombol untuk mencegah submit berulang
                $('#btnAnalisa').prop('disabled', true).text('Analisa Selesai');
            },
            error: function(err) {
                // Tampilkan notifikasi error dengan SweetAlert2
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                console.log(err.responseText); // Tetap log detail error di console
            }
        });
    });
@endsection

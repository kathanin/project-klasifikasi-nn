@extends('layouts.bootstrap')
@section('judul-content','Input Data Klasifikasi')
@section('content')
<div class="container mt-4">
    <form action="{{ route('klasifikasi.hitung') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Data Pribadi Nasabah --}}
        <h5 class="mt-4">Data Pribadi Nasabah</h5>
        <div class="row mb-3">
            <div class="col">
                <label><span class="text-danger">*</span> Nomor KTP/NIK</label>
                <input type="text" name="nik" id="nik" class="form-control">
            </div>
            <div class="col">
                <label><span class="text-danger">*</span> Nama</label>
                <input type="text" id="nama" class="form-control" readonly>
            </div>
            <div class="col">
                <label><span class="text-danger">*</span> Umur</label>
                <input type="text" name="umur" id="umur" class="form-control" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label><span class="text-danger">*</span> Status Pernikahan</label>
                <select name="status_pernikahan" class="form-select" required>
                    <option value="menikah">Menikah</option>
                    <option value="belum menikah">Belum Menikah</option>
                </select>
            </div>
            <div class="col">
                <label><span class="text-danger">*</span> Jumlah Tanggungan</label>
                <select name="jumlah_tanggungan" id="jumlah_tanggungan" class="form-select" required>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </div>

        <hr>

        {{-- Data Pekerjaan --}}
        <h5>Data Pekerjaan & Penghasilan</h5>
        <div class="row mb-3">
            <div class="col">
                <label><span class="text-danger">*</span> Pekerjaan</label>
                <input type="text" id="pekerjaan" class="form-control" readonly>
            </div>
            <div class="col">
                <label><span class="text-danger">*</span> Penghasilan per bulan</label>
                <input type="text" id="penghasilan" class="form-control" readonly>
            </div>
        </div>

        <hr>

        {{-- Data Keuangan --}}
        <h5>Data Keuangan</h5>
        <div class="row mb-3">
            <div class="col">
                <label><span class="text-danger">*</span> Riwayat Kredit</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" name="riwayat_kredit" value="pernah" class="form-check-input" required> Pernah
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="riwayat_kredit" value="tidak pernah" class="form-check-input" required> Tidak Pernah
                </div>
            </div>
            <div class="col">
                <label for="jumlah_tabungan_display"><span class="text-danger">*</span> Jumlah Tabungan</label>
                <input type="text" id="jumlah_tabungan_display" class="form-control format-rupiah" data-target="#jumlah_tabungan" required>
                <input type="hidden" name="jumlah_tabungan" id="jumlah_tabungan" required>
            </div>
        </div>

        <hr>

        {{-- Data Pengajuan Kredit --}}
        <h5>Data Pengajuan Kredit</h5>
        <div class="row mb-3">
            <div class="col">
                <label><span class="text-danger">*</span> Jumlah Pengajuan</label>
                <input type="text" id="jumlah_pengajuan" class="form-control" readonly>
            </div>
            <div class="col">
                <label><span class="text-danger">*</span> Tujuan Kredit</label>
                <select name="tujuan_kredit" id="tujuan_kredit" class="form-select" required>
                    <option value="Modal Usaha">Modal Usaha</option>
                    <option value="Biaya Pendidikan">Biaya Pendidikan</option>
                    <option value="Pembelian Kendaraan">Pembelian Kendaraan</option>
                    <option value="Kebutuhan Konsumtif">Kebutuhan Konsumtif</option>
                    <option value="Biaya Pernikahan">Biaya Pernikahan</option>
                    <option value="Renovasi Rumah">Renovasi Rumah</option>
                </select>
            </div>
            <div class="col">
                <label><span class="text-danger">*</span> Tenor (bulan)</label>
                <input type="number" name="tenor" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label"><span class="text-danger">*</span> Surat Pengajuan Pinjaman (PDF)</label>

            <div id="drop-area" class="border border-dashed rounded p-5 text-center" style="cursor: pointer;">
                <p class="text-muted" id="drop-area-text">
                    Drag & Drop atau Klik untuk memilih file PDF
                </p>
                <input type="file" name="surat_pengajuan" id="fileInput" class="d-none" accept=".pdf" required>
            </div>
            <div class="mt-2">
                <span id="file-name" class="text-success"></span>
            </div>
        </div>
        <div class="d-flex">
            <a href="{{ route('klasifikasi.index') }}" class="btn btn-outline-secondary">Batal</a> &nbsp;
            <button type="submit" class="btn btn-success">Klasifikasi</button>
        </div>
    </form>
</div>

@section('js-ready')
    // Untuk mengambil data nasabah berdasarkan NIK
    $('#nik').on('input', function () {
        let nikValue = $(this).val();
        let namaField = $('#nama');

        if (nikValue.length >= 16) { // Optimasi: hanya fetch jika NIK cukup panjang
            namaField.val('Mencari data...');

            $.get(`/nasabah/fetch/${nikValue}`, function(data) {
                if (data) {
                    namaField.val(data.nama);
                    $('#umur').val(data.umur);
                    $('#pekerjaan').val(data.pekerjaan);
                    $('#penghasilan').val(data.penghasilan);
                    $('#jumlah_pengajuan').val(data.jumlah_pengajuan);
                    let formater = new Intl.NumberFormat('id-ID');
                    $('#penghasilan').val('Rp ' + formater.format(data.penghasilan));
                    $('#jumlah_pengajuan').val('Rp ' + formater.format(data.jumlah_pengajuan));
                } else {
                    namaField.val('NIK tidak ditemukan')
                }
            }).fail(function() {
                namaField.val("Gagagl mengambil data");
            });
        }
    });

    // Untuk area Drag & Drop file
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('file-name');

    if (dropArea) { // Cek jika elemen ada untuk mencegah error
        dropArea.addEventListener('click', () => fileInput.click());
        
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('bg-light');
        });

        dropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropArea.classList.remove('bg-light');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('bg-light');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                fileNameDisplay.textContent = files[0].name;
            }
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;
            }
        });
    }

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
@endsection
@endsection

@extends('layouts.bootstrap')
@section('judul-content','Data Nasabah')

@section('content')
<div class="container py-4" style="background-color: #e8f2e9; border-radius: 8px;">
    <h5 class="mb-4 fw-bold">Data Pribadi Nasabah</h5>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('nasabah.store') }}" method="POST" enctype="multipart/form-data" id="nasabahForm">
        @csrf

        <div class="row g-3">
            <div class="col-md-4">
                <label for="nama" class="form-label"><span class="text-danger">*</span> Nama</label>
                <input type="text" name="nama" id="nama" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">KTP</label>
                <div class="dropzone" id="ktpDropzone"></div>
                <input type="hidden" name="ktp" id="ktp">
            </div>

            <div class="col-md-4">
                <label class="form-label">Kartu Keluarga</label>
                <div class="dropzone" id="kkDropzone"></div>
                <input type="hidden" name="kk" id="kk">
            </div>

            <div class="col-md-4">
                <label for="nik" class="form-label"><span class="text-danger">*</span> Nomor KTP/NIK</label>
                <input type="text" name="nik" id="nik" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">NPWP</label>
                <div class="dropzone" id="npwpDropzone"></div>
                <input type="hidden" name="npwp" id="npwp">
            </div>

            <div class="col-md-4">
                <label class="form-label">Pas Foto 3x4</label>
                <div class="dropzone" id="fotoDropzone"></div>
                <input type="hidden" name="foto" id="foto">
            </div>

            <div class="col-md-4">
                <label for="tgl_lahir" class="form-label"><span class="text-danger">*</span> Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">SK Karyawan</label>
                <div class="dropzone" id="skDropzone"></div>
                <input type="hidden" name="sk_karyawan" id="sk_karyawan">
            </div>

            <div class="col-md-4">
                <label class="form-label">BPJS Ketenagakerjaan</label>
                <div class="dropzone" id="bpjsDropzone"></div>
                <input type="hidden" name="bpjs" id="bpjs">
            </div>

            <div class="col-md-4">
                <label for="umur" class="form-label"><span class="text-danger">*</span> Umur</label>
                <input type="number" name="umur" id="umur" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Rekening Gaji</label>
                <div class="dropzone" id="rekGajiDropzone"></div>
                <input type="hidden" name="rekening_gaji" id="rekening_gaji">
            </div>

            <div class="col-md-4">
                <label class="form-label">Rekening Koran</label>
                <div class="dropzone" id="rekKoranDropzone"></div>
                <input type="hidden" name="rekening_koran" id="rekening_koran">
            </div>

            <div class="col-md-4">
                <label for="no_hp" class="form-label"><span class="text-danger">*</span> Nomor HP</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Slip Gaji</label>
                <div class="dropzone" id="slipGajiDropzone"></div>
                <input type="hidden" name="slip_gaji" id="slip_gaji">
            </div>

            <div class="col-md-4">
                <label class="form-label">Data Jaminan</label>
                <div class="dropzone" id="jaminanDropzone"></div>
                <input type="hidden" name="data_jaminan" id="data_jaminan">
            </div>

            <div class="col-md-4">
                <label for="kontak_darurat" class="form-label">Kontak Darurat</label>
                <input type="text" name="kontak_darurat" id="kontak_darurat" class="form-control">
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>

            <div class="col-12">
                <label for="alamat" class="form-label"><span class="text-danger">*</span> Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control"></textarea>
            </div>

            <div class="col-md-4">
                <label for="jenis_nasabah" class="form-label"><span class="text-danger">*</span> Jenis Nasabah</label>
                <select name="jenis_nasabah" id="jenis_nasabah" class="form-select">
                    <option value="Fixed Income" selected>Fixed Income</option>
                    <option value="Non Fixed Income">Non Fixed Income</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="cabang" class="form-label"><span class="text-danger">*</span> Cabang</label>
                <input type="text" name="cabang" id="cabang" class="form-control">
            </div>

            <div class="col-md-4">
                <label for="pengajuan_display" class="form-label"><span class="text-danger">*</span> Pengajuan</label> 
                <input type="text" id="pengajuan_display" class="form-control format-rupiah" data-target="#pengajuan">
                <input type="hidden" name="pengajuan" id="pengajuan">
            </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3 fw-bold">Data Pekerjaan & Penghasilan</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="pekerjaan" class="form-label"><span class="text-danger">*</span> Pekerjaan</label>
                <select name="pekerjaan" id="pekerjaan" class="form-select">
                    <option value="UMKM">UMKM</option>
                    <option value="PNS">PNS</option>
                    <option value="BUMN">BUMN</option>
                    <option value="pensiunan">Pensiunan</option>
                    <option value="karyawan swasta">Karyawan Swasta</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="penghasilan_display" class="form-label"><span class="text-danger">*</span> Penghasilan per bulan</label>
                <input type="text" id="penghasilan_display" class="form-control format-rupiah" data-target="#penghasilan">
                <input type="hidden" name="penghasilan" id="penghasilan">
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('nasabah.index') }}" class="btn btn-outline-success me-2">Batal</a>
            <button type="submit" class="btn btn-success px-4">Simpan</button>
        </div>
    </form>
</div>
@endsection

@section('css')
    {{-- Dropzone CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
    <style>
        .dropzone {
            border: 2px dashed #28a745;
            background: #f8fbf8;
            border-radius: 8px;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #28a745;
            font-weight: bold;
        }
    </style>
@endsection

@section('js-plugin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        const dropzones = [
            { id: 'ktpDropzone', field: 'ktp', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'kkDropzone', field: 'kk', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'npwpDropzone', field: 'npwp', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'fotoDropzone', field: 'foto', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'skDropzone', field: 'sk_karyawan', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'bpjsDropzone', field: 'bpjs', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'rekGajiDropzone', field: 'rekening_gaji', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'rekKoranDropzone', field: 'rekening_koran', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'slipGajiDropzone', field: 'slip_gaji', acceptedFiles: '.pdf,.png,.jpg,.jpeg' },
            { id: 'jaminanDropzone', field: 'data_jaminan', acceptedFiles: '.pdf,.png,.jpg,.jpeg' }
        ];

        dropzones.forEach(item => {
            new Dropzone(`#${item.id}`, {
                url: "{{ route('nasabah.upload') }}",
                paramName: "file",
                maxFiles: 1,
                acceptedFiles: item.acceptedFiles,
                autoProcessQueue: true,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fas fa-upload fa-2x'></i><br>Drag & Drop or <strong>Choose file</strong>",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (file, response) {
                    if (response.success) {
                        document.getElementById(item.field).value = response.filename;
                    }
                }
            });
        });

        $('#tgl_lahir').on('change', function () {
            let birthDate = new Date($(this).val());
            let today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            let m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            $('#umur').val(age);
        });

        $('.format-rupiah').on('input', function(e) {
            // 1. Ambil nilai & bersihkan
            let rawValue = $(this).val().replace(/[^0-9]/g, '');
            // 2. Ambil target input tersembunyi dari atribut 'data-target'
            let target = $(this).data('target');
            // 3. Simpan nilai bersihnya ke input tersembunyi
            $(target).val(rawValue);
            // 4. Format dan tampilkan kembali
            if (rawValue) {
                let formattedValue = new Intl.NumberFormat('id-ID').format(rawValue);
                $(this).val(formattedValue);
            } else {
                $(this).val('');
            }
        });
    </script>
@endsection



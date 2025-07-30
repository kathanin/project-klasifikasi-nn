@extends('layouts.bootstrap')
@section('judul-content','Data Nasabah')
@section('content')
<div class="container py-4" style="background-color: #e8f2e9; border-radius: 8px;">
    <h5 class="mb-4 fw-bold">Edit Data Nasabah</h5>

    @if ($errors->any())
    <div class="alert alert-danger">
        <h6 class="fw-bold">Gagal Menyimpan Data!</h6>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('nasabah.update', $nasabah->id) }}" method="POST" enctype="multipart/form-data" id="nasabahForm">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-4">
                <label for="nama" class="form-label"><span class="text-danger">*</span> Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ $nasabah->nama }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">KTP</label>
                <div class="dropzone" id="ktpDropzone"></div>
                <input type="hidden" name="ktp" id="ktp" value="{{ $nasabah->ktp }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Kartu Keluarga</label>
                <div class="dropzone" id="kkDropzone"></div>
                <input type="hidden" name="kk" id="kk" value="{{ $nasabah->kk }}">
            </div>

            <div class="col-md-4">
                <label for="nik" class="form-label"><span class="text-danger">*</span> Nomor KTP/NIK</label>
                <input type="text" name="nik" id="nik" class="form-control" value="{{ $nasabah->nik }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">NPWP</label>
                <div class="dropzone" id="npwpDropzone"></div>
                <input type="hidden" name="npwp" id="npwp" value="{{ $nasabah->npwp }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Pas Foto 3x4</label>
                <div class="dropzone" id="fotoDropzone"></div>
                <input type="hidden" name="pas_foto" id="pas_foto" value="{{ $nasabah->pas_foto }}">
            </div>

            <div class="col-md-4">
                <label for="tgl_lahir" class="form-label"><span class="text-danger">*</span> Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" value="{{ $nasabah->tgl_lahir }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">SK Karyawan</label>
                <div class="dropzone" id="skDropzone"></div>
                <input type="hidden" name="sk_karyawan" id="sk_karyawan" value="{{ $nasabah->sk_karyawan }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">BPJS Ketenagakerjaan</label>
                <div class="dropzone" id="bpjsDropzone"></div>
                <input type="hidden" name="bpjs" id="bpjs" value="{{ $nasabah->bpjs }}">
            </div>

            <div class="col-md-4">
                <label for="umur" class="form-label"><span class="text-danger">*</span> Umur</label>
                <input type="number" name="umur" id="umur" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Rekening Gaji</label>
                <div class="dropzone" id="rekGajiDropzone"></div>
                <input type="hidden" name="rekening_gaji" id="rekening_gaji" value="{{ $nasabah->rekening_gaji }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Rekening Koran</label>
                <div class="dropzone" id="rekKoranDropzone"></div>
                <input type="hidden" name="rekening_koran" id="rekening_koran" value="{{ $nasabah->rekening_koran }}">
            </div>

            <div class="col-md-4">
                <label for="no_hp" class="form-label"><span class="text-danger">*</span> Nomor HP</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ $nasabah->no_hp }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Slip Gaji</label>
                <div class="dropzone" id="slipGajiDropzone"></div>
                <input type="hidden" name="slip_gaji" id="slip_gaji" value="{{ $nasabah->slip_gaji }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Data Jaminan</label>
                <div class="dropzone" id="jaminanDropzone"></div>
                <input type="hidden" name="jaminan" id="jaminan" value="{{ $nasabah->jaminan }}">
            </div>

            <div class="col-md-4">
                <label for="kontak_darurat" class="form-label">Kontak Darurat</label>
                <input type="text" name="kontak_darurat" id="kontak_darurat" class="form-control" value="{{ $nasabah->kontak_darurat }}">
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $nasabah->email }}">
            </div>

            <div class="col-12">
                <label for="alamat" class="form-label"><span class="text-danger">*</span> Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control">{{ $nasabah->alamat }}</textarea>
            </div>

            <div class="col-md-4">
                <label for="jenis_nasabah" class="form-label"><span class="text-danger">*</span> Jenis Nasabah</label>
                <select name="jenis_nasabah" id="jenis_nasabah" class="form-select">
                    <option value="Fixed Income" {{ $nasabah->jenis_nasabah == 'Fixed Income' ? 'selected' : '' }}>Fixed Income</option>
                    <option value="Non Fixed Income" {{ $nasabah->jenis_nasabah == 'Non Fixed Income' ? 'selected' : '' }}>Non Fixed Income</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="cabang" class="form-label"><span class="text-danger">*</span> Cabang</label>
                <input type="text" name="cabang" id="cabang" class="form-control" value="{{ $nasabah->cabang }}">
            </div>

            <div class="col-md-4">
                <label for="pengajuan_display" class="form-label"><span class="text-danger">*</span> Pengajuan</label>
                <input type="text" id="pengajuan_display" class="form-control format-rupiah" data-target="#pengajuan" value="{{ $nasabah->jumlah_pengajuan }}">
                <input type="hidden" name="pengajuan" id="pengajuan" value="{{ $nasabah->jumlah_pengajuan }}">
            </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3 fw-bold">Data Pekerjaan & Penghasilan</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="pekerjaan" class="form-label"><span class="text-danger">*</span> Pekerjaan</label>
                <select name="pekerjaan" id="pekerjaan" class="form-select">
                    @php
                    $pk=['UMKM','PNS','BUMN','pensiunan','karyawan swasta'];
                    @endphp
                    @for($x=0; $x<count($pk); $x++)
                        @if($pk[$x] == $nasabah->pekerjaan) 
                            <option value="{{$pk[$x]}}" selected>{{$pk[$x]}}</option>
                        @else
                            <option value="{{$pk[$x]}}">{{$pk[$x]}}</option>
                        @endif
                    @endfor
                </select>
            </div>

            <div class="col-md-6">
                <label for="penghasilan_display" class="form-label"><span class="text-danger">*</span> Penghasilan per bulan</label>
                <input type="text" id="penghasilan_display" class="form-control format-rupiah" data-target="#penghasilan" value="{{ $nasabah->penghasilan }}">
                <input type="hidden" name="penghasilan" id="penghasilan" value="{{ $nasabah->penghasilan }}">
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('nasabah.index') }}" class="btn btn-outline-success me-2">Batal</a>
            <button type="submit" class="btn btn-success px-4">Update</button>
        </div>
    </form>
</div>
@endsection

@section('css')
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
            { id: 'ktpDropzone', field: 'ktp', oldFile: "{{ $nasabah->ktp ? asset('storage/' . $nasabah->ktp) : '' }}", filename: "{{ $nasabah->ktp }}" },
            { id: 'kkDropzone', field: 'kk', oldFile: "{{ $nasabah->kk ? asset('storage/' . $nasabah->kk) : '' }}", filename: "{{ $nasabah->kk }}" },
            { id: 'npwpDropzone', field: 'npwp', oldFile: "{{ $nasabah->npwp ? asset('storage/' . $nasabah->npwp) : '' }}", filename: "{{ $nasabah->npwp }}" },
            { id: 'fotoDropzone', field: 'pas_foto', oldFile: "{{ $nasabah->pas_foto ? asset('storage/' . $nasabah->pas_foto) : '' }}", filename: "{{ $nasabah->pas_foto }}" },
            { id: 'skDropzone', field: 'sk_karyawan', oldFile: "{{ $nasabah->sk_karyawan ? asset('storage/' . $nasabah->sk_karyawan) : '' }}", filename: "{{ $nasabah->sk_karyawan }}" },
            { id: 'bpjsDropzone', field: 'bpjs', oldFile: "{{ $nasabah->bpjs ? asset('storage/' . $nasabah->bpjs) : '' }}", filename: "{{ $nasabah->bpjs }}" },
            { id: 'rekGajiDropzone', field: 'rekening_gaji', oldFile: "{{ $nasabah->rekening_gaji ? asset('storage/' . $nasabah->rekening_gaji) : '' }}", filename: "{{ $nasabah->rekening_gaji }}" },
            { id: 'rekKoranDropzone', field: 'rekening_koran', oldFile: "{{ $nasabah->rekening_koran ? asset('storage/' . $nasabah->rekening_koran) : '' }}", filename: "{{ $nasabah->rekening_koran }}" },
            { id: 'slipGajiDropzone', field: 'slip_gaji', oldFile: "{{ $nasabah->slip_gaji ? asset('storage/' . $nasabah->slip_gaji) : '' }}", filename: "{{ $nasabah->slip_gaji }}" },
            { id: 'jaminanDropzone', field: 'jaminan', oldFile: "{{ $nasabah->jaminan ? asset('storage/' . $nasabah->jaminan) : '' }}", filename: "{{ $nasabah->jaminan }}" }
        ];

        dropzones.forEach(item => {
            let dz = new Dropzone(`#${item.id}`, {
                url: "{{ route('nasabah.upload') }}",
                paramName: "file",
                maxFiles: 1,
                acceptedFiles: '.pdf,.png,.jpg,.jpeg',
                autoProcessQueue: true,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fas fa-upload fa-2x'></i><br>Drag & Drop atau <strong>Pilih file</strong>",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (file, response) {
                    if (response.success) {
                        // Set nilai hidden input dengan nama file baru dari server
                        document.getElementById(item.field).value = response.filename;
                        // Simpan nama file baru di objek file untuk proses penghapusan
                        file.serverFilename = response.filename;
                    }
                },
                // INI BAGIAN PENTING YANG PERLU DITAMBAHKAN
                removedfile: function(file) {
                    // Kosongkan nilai di hidden input saat file dihapus
                    document.getElementById(item.field).value = '';

                    // Hapus preview file dari tampilan
                    let _ref;
                    if (file.previewElement) {
                        if ((_ref = file.previewElement) != null) {
                            _ref.parentNode.removeChild(file.previewElement);
                        }
                    }
                    
                    // Reset Dropzone agar bisa upload file baru lagi
                    this.removeAllFiles(); 
                }
            });

            // Logika untuk menampilkan file yang sudah ada
            if (item.oldFile) {
                let mockFile = { 
                    name: item.filename.split('/').pop(), 
                    size: 12345, // Ukuran file dummy
                    accepted: true,
                    serverFilename: item.filename // Simpan nama file dari server
                };
                
                dz.emit("addedfile", mockFile);
                
                // Cek apakah file adalah gambar untuk menampilkan thumbnail
                if (/\.(jpe?g|png)$/i.test(item.filename)) {
                    dz.emit("thumbnail", mockFile, item.oldFile);
                } else {
                    dz.emit("thumbnail", mockFile, "/images/pdf-icon.png"); // Ganti dengan path icon PDF Anda
                }
                
                dz.emit("complete", mockFile);
                dz.files.push(mockFile);
            }

            // Otomatis hapus file lama jika file baru di-upload
            dz.on("addedfile", function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        });

        // Kalkulasi Umur
        function calculateAge() {
            let birthDateValue = $('#tgl_lahir').val();
            if (!birthDateValue) return;

            let birthDate = new Date(birthDateValue);
            let today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            let m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            $('#umur').val(age);
        }

        $('#tgl_lahir').on('change', calculateAge);

        // Hitung umur saat halaman dimuat
        calculateAge();

        // Format angka saat halaman pertama kali dimuat
        $('.format-rupiah').each(function() {
            let rawValue = $(this).val().replace(/[^0-9]/g, '');
            if (rawValue) {
                let formattedValue = new Intl.NumberFormat('id-ID').format(rawValue);
                $(this).val(formattedValue);
                
                // Juga inisialisasi nilai untuk input hidden pasangannya
                let target = $(this).data('target');
                $(target).val(rawValue);
            }
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

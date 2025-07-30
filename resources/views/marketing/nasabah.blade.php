@extends('layouts.bootstrap')
@section('judul-content','Data Nasabah')
@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{url('/nasabah/tambah')}}"><button class="btn btn-success me-2">
                <i class="fas fa-plus"></i>
            </button></a>
            <button id="btnEdit" class="btn btn-primary me-2">
                <i class="fas fa-pencil-alt"></i>
            </button>
            <button id="btnDelete" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <table id="nasabahTable" class="table table-striped table-hover">
        <thead class="table-success">
            <tr>
                <th>Nama Nasabah</th>
                <th>Jenis Nasabah</th>
                <th>Cabang</th>
                <th>Pengajuan</th>
                <th>Status</th>
                <th><input type="checkbox" id="checkAll"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nasabahs as $nasabah)
                <tr>
                    <td>{{ $nasabah->nama }}</td>
                    <td>{{ $nasabah->jenis_nasabah }}</td>
                    <td>{{ $nasabah->cabang }}</td>
                    <td>{{ number_format($nasabah->jumlah_pengajuan, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $ns=$nasabah->toArray();
                            $stat = 'Analisis';
                            $kolomDokumen=['ktp','kk','npwp','pas_foto','sk_karyawan','bpjs','rekening_gaji','rekening_koran','slip_gaji','jaminan'];
                            for($y=0; $y < count($kolomDokumen); $y++){
                                if($ns[$kolomDokumen[$y]] == null) $stat='Dokumen';
                            }
                            $kolomData=['nama','nik','tgl_lahir','no_hp','kontak_darurat','email','alamat','jenis_nasabah','cabang','pekerjaan','penghasilan','jumlah_pengajuan'];
                            for($x=0; $x < count($kolomData); $x++){
                                if($ns[$kolomData[$x]] === null) $stat='Verifikasi';
                            } 
                            // Dummy mapping status badge warna
                            $statuses = ['Analisis' => 'success', 'Dokumen' => 'warning', 'Verifikasi' => 'secondary'];
                            $badge = $statuses[$stat];
                        @endphp
                        <span class="badge bg-{{ $badge }}">
                            {{ $stat }}
                        </span>
                    </td>
                    <td><input type="checkbox" class="row-check" value="{{ $nasabah->id }}"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('js-plugin')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
@endsection
@section('js-ready')
    // --- FUNGSI UTAMA UNTUK MENGATUR STATUS TOMBOL ---
    function updateButtonStates() {
        // Hitung jumlah checkbox yang tercentang
        const selectedCount = $('#nasabahTable .row-check:checked').length;

        // Aturan untuk Tombol Hapus: Aktif jika 1 atau lebih data dipilih
        $('#btnDelete').prop('disabled', selectedCount === 0);

        // Aturan untuk Tombol Edit: Aktif HANYA JIKA 1 data dipilih
        $('#btnEdit').prop('disabled', selectedCount !== 1);
    }

    // --- INISIALISASI DATATABLE DAN EVENT HANDLER ---

    // Inisialisasi DataTable
    $('#nasabahTable').DataTable({
        responsive: true,
        language: { /* Opsi bahasa Anda */ },
        "initComplete": function(settings, json) {
            // Panggil fungsi untuk mengatur status awal tombol setelah tabel siap
            updateButtonStates();
        }
    });

    // Event handler untuk checkbox "Pilih Semua" (ID disesuaikan menjadi #checkAll)
    $('#checkAll').on('click', function() {
        $('#nasabahTable .row-check').prop('checked', this.checked);
        updateButtonStates();
    });

    // Event handler untuk setiap checkbox di baris tabel
    $('#nasabahTable').on('click', '.row-check', function() {
        updateButtonStates();
    });


    // --- AKSI TOMBOL ---

    // Handler untuk Tombol Edit
    $('#btnEdit').click(function() {
        // Ambil ID dari satu-satunya baris yang dipilih
        const selectedId = $('#nasabahTable .row-check:checked').val();
        // Arahkan ke halaman edit
        window.location.href = `{{ url('/nasabah') }}/${selectedId}/edit`;
    });

    // Handler untuk Tombol Hapus dengan SweetAlert2
    $('#btnDelete').click(function() {
        const selected = $('#nasabahTable .row-check:checked').map(function() {
            return $(this).val();
        }).get();

        // Tampilkan modal konfirmasi SweetAlert2
        Swal.fire({
            title: 'Anda Yakin?',
            text: `Anda akan menghapus ${selected.length} data nasabah.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, kirim request hapus
                $.post("{{ route('nasabah.hapus') }}", { // Disarankan menggunakan route()
                    id: selected,
                    _token: "{{ csrf_token() }}"
                })
                .done(function(response) {
                    location.reload(); // Muat ulang untuk notifikasi sukses
                })
                .fail(function(error) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                });
            }
        });
    });
@endsection
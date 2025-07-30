@extends('layouts.bootstrap')
@section('judul-content', 'Pengecekan Data Legal')
@section('content')
<div class="container mt-4">
    {{-- Notifikasi akan muncul di sini dari session --}}
    @if(session('success'))
        {{-- Script ini akan dijalankan jika ada notifikasi dari controller --}}
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold">Data Legal</h5>
        <div>
            <button id="btnDelete" class="btn btn-danger" disabled>
                <i class="fas fa-trash-alt"></i> 
            </button>
        </div>
    </div>

    {{-- PERBAIKAN: ID tabel diubah menjadi 'legalTable' agar cocok dengan JS --}}
    <table id="legalTable" class="table table-striped table-hover">
        <thead class="table-success">
            <tr>
                {{-- PERBAIKAN: Tambahkan checkbox "Pilih Semua" --}}
                <th><input type="checkbox" id="select-all"></th>
                <th>Nama Nasabah</th>
                <th>Tujuan Kredit</th>
                <th>Status</th>
                {{-- PERBAIKAN: Tambahkan kolom Aksi untuk tombol Review --}}
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($legals as $legal)
        <tr>
            <td><input type="checkbox" class="row-check" value="{{ $legal->id }}"></td>
            
            {{-- Akses data nasabah melalui relasi Eloquent --}}
            <td>{{ $legal->analisa->klasifikasi->nasabah->nama ?? 'Data Nasabah Dihapus' }}</td>
            <td>{{ $legal->analisa->klasifikasi->tujuan_kredit ?? '-' }}</td>
            <td>
                <span class="badge {{ $legal->status == 'Terverifikasi' ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $legal->status ?? 'Belum Diproses' }}
                </span>
            </td>
            <td>
                <a href="{{ route('legal.edit', $legal->id) }}" class="btn btn-sm btn-info">
                    <i class="bi bi-eye"></i> Review
                </a>
            </td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('js-ready')
    // Fungsi untuk mengatur status tombol Hapus
    function updateDeleteButtonState() {
        const anyChecked = $('#legalTable .row-check:checked').length > 0;
        $('#btnDelete').prop('disabled', !anyChecked);
    }

    // Inisialisasi DataTable
    $('#legalTable').DataTable({
        responsive: true,
        language: { /* Opsi bahasa Anda */ },
        "initComplete": function(settings, json) {
            updateDeleteButtonState();
        }
    });

    // Event handler untuk checkbox "Pilih Semua"
    $('#select-all').on('click', function() {
        $('#legalTable .row-check').prop('checked', this.checked);
        updateDeleteButtonState();
    });

    // Event handler untuk checkbox per baris
    $('#legalTable').on('click', '.row-check', function() {
        updateDeleteButtonState();
        if ($('#legalTable .row-check:checked').length === $('#legalTable .row-check').length) {
            $('#select-all').prop('checked', true);
        } else {
            $('#select-all').prop('checked', false);
        }
    });

    // Handler untuk Tombol Hapus dengan SweetAlert2
    $('#btnDelete').click(function() {
        let selected = $('#legalTable .row-check:checked').map(function() {
            return $(this).val();
        }).get();

        Swal.fire({
            title: 'Anda Yakin?',
            text: `Anda akan menghapus ${selected.length} data legal terpilih.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('legal.hapus') }}", {
                    id: selected,
                    _token: "{{ csrf_token() }}"
                })
                .done(function(response) {
                    location.reload();
                })
                .fail(function(error) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                });
            }
        });
    });

    // Untuk menampilkan notifikasi dari session
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
@endsection
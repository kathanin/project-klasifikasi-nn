@extends('layouts.bootstrap')
@section('judul-content','Persetujuan Pengajuan')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold">Data Persetujuan</h5>
        <div>
            <button id="btnDelete" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    {{-- PERBAIKAN: ID tabel disesuaikan agar cocok dengan JavaScript --}}
    <table id="persetujuanTable" class="table table-striped table-hover">
        <thead class="table-success">
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Nama Nasabah</th>
                <th>Tujuan Kredit</th>
                <th>Status</th>
                {{-- PERUBAHAN: Tambahkan kolom Aksi --}}
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- PERBAIKAN: Variabel disesuaikan menjadi $persetujuans agar cocok dengan data dari controller --}}
            @foreach ($persetujuans as $persetujuan)
                <tr>
                    <td><input type="checkbox" class="row-check" value="{{ $persetujuan->id }}"></td>
                    {{-- PERBAIKAN: Hapus onclick dari <td> dan akses data melalui relasi --}}
                    <td>{{ $persetujuan->analisa->klasifikasi->nasabah->nama ?? 'N/A' }}</td>
                    <td>{{ $persetujuan->analisa->klasifikasi->tujuan_kredit ?? '-' }}</td>
                    <td>
                         <span class="badge {{ $persetujuan->status_persetujuan == 'disetujui' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($persetujuan->status_persetujuan) }}
                        </span>
                    </td>
                    {{-- PERUBAHAN: Tambahkan tombol Aksi di sini --}}
                    <td>
                        <a href="{{ route('persetujuan.show', $persetujuan->id) }}" class="btn btn-sm btn-info">
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('js-plugin')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    {{-- SweetAlert2 harus sudah ada di layout utama Anda --}}
@endsection

@section('js-ready')
    // Inisialisasi DataTable
    $('#persetujuanTable').DataTable({
        responsive: true,
        language: { /* Opsi bahasa Anda */ },
        "initComplete": function(settings, json) {
            updateDeleteButtonState();
        }
    });

    // Fungsi untuk mengatur status tombol Hapus
    function updateDeleteButtonState() {
        var anyChecked = $('#persetujuanTable .row-check:checked').length > 0;
        $('#btnDelete').prop('disabled', !anyChecked);
    }

    // Event handler untuk checkbox "Pilih Semua"
    $('#select-all').on('click', function(){
        $('#persetujuanTable .row-check').prop('checked', this.checked);
        updateDeleteButtonState();
    });

    // Event handler untuk checkbox per baris
    $('#persetujuanTable').on('click', '.row-check', function() {
        updateDeleteButtonState();
    });


$('#btnDelete').click(function(){
    let selected = $('#persetujuanTable .row-check:checked').map(function(){
        return $(this).val();
    }).get();

    Swal.fire({
        title: 'Anda Yakin?',
        text: `Anda akan menghapus ${selected.length} data. Tindakan ini tidak dapat dibatalkan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // PERBAIKAN: Gunakan route() untuk URL hapus
            $.post("{{ route('persetujuan.hapus') }}", {
                id: selected,
                _token: "{{ csrf_token() }}"
            })
            .done(function(data) {
                location.reload();
            })
            .fail(function(err) {
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
            });
        }
    });
});
@endsection
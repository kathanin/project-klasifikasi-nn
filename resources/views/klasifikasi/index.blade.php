@extends('layouts.bootstrap')
@section('judul-content','Input Data Klasifikasi')
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('klasifikasi.create') }}" class="btn btn-success me-2">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
            <button class="btn btn-danger" id="btnDelete">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </div>
    </div>

        <table id="klasifikasiTable" class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Nama</th>
                    <th>Penghasilan</th>
                    <th>Pengajuan</th>
                    <th>Riwayat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($klasifikasis as $item)
                    <tr>
                        <td>
                            <input type="checkbox" class="row-check" value="{{ $item->id }}">
                        </td>
                        <td>{{ $item->nasabah->nama ?? '-' }}</td>
                        <td>{{ number_format($item->nasabah->penghasilan ?? 0, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->nasabah->jumlah_pengajuan ?? 0, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($item->riwayat_kredit) }}</td>
                        <td>
                            <span class="badge 
                                @if($item->hasil_klasifikasi == 'high') bg-danger
                                @elseif($item->hasil_klasifikasi == 'medium') bg-warning text-dark
                                @else bg-success
                                @endif">
                                {{ ucfirst($item->hasil_klasifikasi) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection
@section('js-plugin')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
@endsection
@section('js-ready')
    // Fungsi untuk memperbarui status tombol Hapus (Enable/Disable)
    function updateDeleteButtonState() {
        const anyChecked = $('.row-check:checked').length > 0;
        $('#btnDelete').prop('disabled', !anyChecked);
    }

    // Inisialisasi DataTable
    $('#klasifikasiTable').DataTable({
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ entri",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                next: "→",
                previous: "←"
            }
        },
        "initComplete": function(settings, json) {
            updateDeleteButtonState();
        }
    });

    // Event handler untuk checkbox "Pilih Semua"
    $('#select-all').on('click', function() {
        $('.row-check').prop('checked', this.checked);
        updateDeleteButtonState();
    });

    // Event handler untuk setiap checkbox di baris tabel
    $('#klasifikasiTable').on('click', '.row-check', function() {
        updateDeleteButtonState();
        if ($('.row-check:checked').length === $('.row-check').length) {
            $('#select-all').prop('checked', true);
        } else {
            $('#select-all').prop('checked', false);
        }
    });

    $('#btnDelete').click(function() {
        let selected = $('.row-check:checked').map(function() {
            return $(this).val();
        }).get();

        // Tampilkan modal konfirmasi SweetAlert2 yang modern
        Swal.fire({
            title: 'Anda Yakin?',
            text: `Anda akan menghapus ${selected.length} data. Data yang dihapus tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            // Blok ini akan berjalan setelah pengguna menekan salah satu tombol
            if (result.isConfirmed) {
                // Jika pengguna menekan "Ya, hapus!", kirim permintaan AJAX
                let hps = $.post("{{ route('klasifikasi.deleteSelected') }}", {
                    id: selected,
                    _token: "{{ csrf_token() }}"
                });

                hps.done(function(data) {
                    // Muat ulang halaman agar notifikasi sukses dari server muncul
                    location.reload();
                });

                hps.fail(function(err) {
                    // Jika gagal, tampilkan notifikasi error dengan SweetAlert2
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus data.',
                        'error'
                    );
                });
            }
        });
    });
@endsection

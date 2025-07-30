@extends('layouts.bootstrap')
@section('judul-content','Detail Pengajuan')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold">Persetujuan</h5>
        <div>
            <a href="{{ route('persetujuan.index') }}" class="btn btn-outline-success me-2 rounded-pill px-4">Kembali</a>
            <button type="button" onclick="isiLegal({{ $persetujuan->id }})" class="btn btn-success rounded-pill px-4">
                <i class="bi bi-send"></i> Drop ke Legal
            </button>
        </div>
    </div>

    {{-- Akses data nasabah melalui relasi dari objek $persetujuan --}}
    <div class="border p-3 rounded">
        <h6 class="fw-bold mb-3">Informasi Nasabah</h6>
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1 fw-semibold">{{ $persetujuan->analisa->klasifikasi->nasabah->nama }}</p>
                <p class="mb-1 text-muted">{{ $persetujuan->analisa->klasifikasi->nasabah->tgl_lahir }}</p>
                <p class="mb-1 text-muted">Nomor HP</p>
                <p class="mb-3 fw-semibold">{{ $persetujuan->analisa->klasifikasi->nasabah->no_hp }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1 text-muted fw-semibold">Alamat</p>
                <p class="mb-1">{{ $persetujuan->analisa->klasifikasi->nasabah->alamat }}</p>
                <p class="mb-1 text-muted fw-semibold">Email</p>
                <p class="fw-semibold">{{ $persetujuan->analisa->klasifikasi->nasabah->email }}</p>
            </div>
        </div>
    </div>

    <div class="border p-3 mt-4 rounded">
        <h6 class="fw-bold mb-3">Penghasilan dan Cicilan</h6>
        <div class="bg-success text-white rounded-top px-3 py-2 d-flex justify-content-between">
            <span class="fw-semibold">Penghasilan per Bulan</span>
            <span class="fw-semibold">Rp. {{ number_format($persetujuan->analisa->klasifikasi->nasabah->penghasilan, 0, ',', '.') }}</span>
        </div>
        <table class="table mb-0 border rounded-bottom">
            <thead class="table-borderless">
                <tr>
                    <th>Lembaga</th>
                    <th class="text-end">Cicilan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bank Lain</td>
                    <td class="text-end">Rp. {{ number_format($persetujuan->analisa->angsuran_lain, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pengajuan Pinjaman Ini</td>
                    <td class="text-end">Rp. {{ number_format($persetujuan->analisa->angsuran_pengajuan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="border p-3 mt-4 rounded">
        <h6 class="fw-bold mb-3">Kesimpulan & Catatan</h6>
        <div class="row mb-2">
            <div class="col-md-6 fw-semibold">Total Cicilan</div>
            <div class="col-md-6 text-end fw-semibold">Rp. {{ number_format($persetujuan->analisa->total_angsuran, 0, ',', '.') }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 fw-semibold">Hasil DBR</div>
            <div class="col-md-6 text-end fw-semibold">{{ $persetujuan->analisa->dbr }}%</div>
        </div>
        <div class="form-group mt-3">
            <label for="catatanPimpinan" class="form-label fw-semibold">Catatan untuk Legal:</label>
            <textarea id="catatanPimpinan" class="form-control" rows="3" placeholder="Tambahkan catatan jika ada..."></textarea>
        </div>

        <p class="fw-semibold text-dark mt-3">
            Hasil DBR nasabah adalah {{ $persetujuan->analisa->dbr }}% yang berarti pengajuan pembiayaan nasabah {{ $persetujuan->analisa->dbr < 30 ? 'dapat' : 'tidak dapat' }} direkomendasikan.
        </p>
    </div>
</div>
<script>
    function isiLegal(id) {
        Swal.fire({
            title: 'Anda Yakin?',
            text: "Data akan dikirim ke Legal dan dihapus dari daftar ini.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let catatan = $('#catatanPimpinan').val();

                $.ajax({
                    url: "{{ route('persetujuan.legalstore') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        catatan: catatan // <-- KIRIM CATATAN KE CONTROLLER
                    },
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data telah dikirim ke Legal.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = "{{ route('persetujuan.index') }}";
                        });
                    },
                    error: function(err) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat memproses data.', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
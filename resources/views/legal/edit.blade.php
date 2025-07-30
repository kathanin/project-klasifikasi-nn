@extends('layouts.bootstrap')
@section('judul-content', 'Verifikasi Dokumen Legal')
@section('content')
@php
    // Decode data checklist JSON dari database. Buat array kosong jika null.
    $checklist = json_decode($legal->dokumen_checklist, true) ?? [];
@endphp

<div class="container mt-5">
    <!-- Data Nasabah -->
    <div class="card mb-3" style="border-radius: 10px;">
        <div class="card-body">
            <h5 class="card-title mb-2">{{ $legal->analisa->klasifikasi->nasabah->nama }}</h5>
            <p class="card-text mb-0">{{ $legal->analisa->klasifikasi->nasabah->tgl_lahir }}</p>
            <p class="card-text">Jumlah Pinjaman Rp. {{ number_format($legal->analisa->klasifikasi->nasabah->jumlah_pengajuan, 0, ',', '.') }}</p>
        </div>
    </div>
    @if($legal->catatan)
        <div class="alert alert-info shadow-sm">
            <h6 class="alert-heading fw-bold"><i class="bi bi-info-circle-fill"></i> Catatan dari Pimpinan:</h6>
            <p class="mb-0">{{ $legal->catatan }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('legal.verifikasi') }}" id="verifikasiForm">
        @csrf
        <input type="hidden" name="id" value="{{ $legal->id }}">

        <!-- Document Review -->
        <div class="accordion" id="documentAccordion" style="border: 1px solid #198754; border-radius: 10px; padding: 15px;">
            <h6 class="mb-3">Document Review</h6>
            
            {{-- Helper function untuk membuat baris dokumen --}}
            @php
                function renderDocumentRow($name, $label, $file, $checklist, $legal) {
                    $filePath = $file ? route('legal.viewFile', ['legal' => $legal->id, 'field' => $name]) : '#';

                    $fileName = $file ? basename($file) : 'File tidak tersedia';
                    $isChecked = isset($checklist[$name]) ? 'checked' : '';
                    $disabled = !$file ? 'disabled' : '';

                    echo '
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-text-fill text-success me-2"></i>
                            <span>' . $label . ' - <a href="' . $filePath . '" target="_blank" class="'.($disabled ? 'text-muted' : '').'">' . $fileName . '</a></span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="' . $name . '" ' . $isChecked . ' ' . $disabled . '>
                        </div>
                    </div>';
                }
            @endphp

            {{-- Formulir Pengajuan Pinjaman --}}
            <div class="accordion-item border-0">
                <h2 class="accordion-header"><button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">Formulir Pengajuan Pinjaman</button></h2>
                <div id="collapseOne" class="accordion-collapse collapse show">
                    <div class="accordion-body py-2">
                        @php renderDocumentRow('surat_pengajuan', 'Surat Pengajuan', $legal->surat_pengajuan, $checklist, $legal) @endphp
                    </div>
                </div>
            </div>

            {{-- Dokumen Identitas --}}
            <div class="accordion-item border-0">
                <h2 class="accordion-header"><button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">Dokumen Identitas</button></h2>
                <div id="collapseTwo" class="accordion-collapse collapse">
                    <div class="accordion-body py-2">
                        @php renderDocumentRow('ktp', 'KTP', $legal->ktp, $checklist, $legal) @endphp
                        @php renderDocumentRow('kk', 'Kartu Keluarga', $legal->kk, $checklist, $legal) @endphp
                        @php renderDocumentRow('pas_foto', 'Pas Foto 3x4', $legal->pas_foto, $checklist, $legal) @endphp
                        @php renderDocumentRow('jaminan', 'Data Jaminan', $legal->jaminan, $checklist, $legal) @endphp
                    </div>
                </div>
            </div>
            
            <div class="accordion-item border-0">
                <h2 class="accordion-header"><button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">Bukti Penghasilan</button></h2>
                <div id="collapseThree" class="accordion-collapse collapse">
                    <div class="accordion-body py-2">
                        @php renderDocumentRow('npwp', 'NPWP', $legal->npwp, $checklist, $legal) @endphp
                        @php renderDocumentRow('sk_karyawan', 'SK Karyawan', $legal->sk_karyawan, $checklist, $legal) @endphp
                        @php renderDocumentRow('bpjs', 'BPJS Ketenagakerjaan', $legal->bpjs, $checklist, $legal) @endphp
                        @php renderDocumentRow('rekening_gaji', 'Rekening Gaji', $legal->rekening_gaji, $checklist, $legal) @endphp
                        @php renderDocumentRow('rekening_koran', 'Rekening Koran', $legal->rekening_koran, $checklist, $legal) @endphp
                        @php renderDocumentRow('slip_gaji', 'Slip Gaji', $legal->slip_gaji, $checklist, $legal) @endphp
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0">
                <h2 class="accordion-header"><button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">Laporan Kredit</button></h2>
                <div id="collapseFour" class="accordion-collapse collapse">
                    <div class="accordion-body py-2">
                        @php renderDocumentRow('hasil_analisa_dbr', 'Hasil Analisa DBR', $legal->hasil_analisa_dbr, $checklist, $legal) @endphp
                    </div>
                </div>
            </div>

        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('legal.index') }}" class="btn btn-outline-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Submit Verifikasi</button>
        </div>
    </form>
</div>
@endsection

@section('js-ready')
    $('#verifikasiForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah refresh halaman

        Swal.fire({
            title: 'Anda Yakin?',
            text: "Pastikan semua dokumen telah diverifikasi dengan benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Submit!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = this;
                let formData = $(form).serialize();

                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: "Verifikasi dokumen legal telah berhasil disimpan.",
                            icon: 'success'
                        }).then(() => {
                            window.location.href = "{{ route('legal.index') }}";
                        });
                    },
                    error: function(error) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                    }
                });
            }
        });
    });
@endsection
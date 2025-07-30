@extends('layouts.bootstrap')
@section('judul-content', 'Hasil Data Klasifikasi')
@section('content')
<div class="container mt-4 d-flex justify-content-center">
    <div class="col-lg-8">
        @if (isset($hasil))
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Hasil Klasifikasi Kredit</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Nama</div>
                        <div class="col-md-8 fw-bold">{{ $hasil['nama'] }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">NIK</div>
                        <div class="col-md-8 fw-bold">{{ $hasil['nik'] }}</div>
                    </div>

                    <hr>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4 text-muted">Status Prediksi</div>
                        <div class="col-md-8">
                            <span class="badge fs-6
                                @if(strtolower($hasil['status_teks']) == 'tidak layak') bg-danger
                                @else bg-primary
                                @endif">
                                {{ $hasil['status_teks'] }}
                            </span>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-4 text-muted">Tingkat Keyakinan</div>
                        <div class="col-md-8">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated
                                    @if($hasil['probabilitas_layak'] < 0.5) bg-danger
                                    @elseif($hasil['probabilitas_layak'] < 0.7) bg-warning
                                    @else bg-success
                                    @endif"
                                    role="progressbar" style="width: {{ $hasil['probabilitas_layak'] * 100 }}%"
                                    aria-valuenow="{{ $hasil['probabilitas_layak'] * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                    <strong class="text-dark">{{ number_format($hasil['probabilitas_layak'] * 100, 2) }}% Layak</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end bg-light">
                    <form action="{{ route('klasifikasi.store') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Hasil
                        </button>
                    </form>

                    <a href="{{ route('klasifikasi.create') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-repeat"></i> Lakukan Klasifikasi Lagi
                    </a>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center">
                Tidak ada hasil untuk ditampilkan.
            </div>
        @endif
    </div>
</div>
@endsection
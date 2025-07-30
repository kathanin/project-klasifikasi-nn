<?php

namespace App\Http\Controllers;

use App\Models\Analisa;
use App\Models\Klasifikasi;
use App\Models\Nasabah;
use App\Models\Persetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class AnalisaController extends Controller
{
    public function index()
    {
        return view('analisa.index');
    }

    public function fetchByNIK($nik)
    {
        $nasabah = Nasabah::where('nik', $nik)->first();

        if (!$nasabah) {
            return response()->json(null, 404);
        }

        $klasifikasi = Klasifikasi::where('nasabah_id', $nasabah->id)->first();
        if (!$klasifikasi) {
            // Memberi pesan error yang lebih jelas jika klasifikasi belum ada
            return response()->json(['error' => 'Data klasifikasi untuk NIK ini tidak ditemukan.'], 404);
        }

        return response()->json([
            'id_user' => $nasabah->user_id,
            'id_klasifikasi' => $klasifikasi->id,
            'tenor' => $klasifikasi->tenor,
            'nama' => $nasabah->nama,
            'nik' => $nasabah->nik,
            'umur' => Carbon::parse($nasabah->tgl_lahir)->age,
            'pekerjaan' => $nasabah->pekerjaan,
            'penghasilan' => $nasabah->penghasilan,
            'jumlah_pengajuan' => $nasabah->jumlah_pengajuan,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data di awal 
        $validatedData = $request->validate([
            'nik' => 'required|string|exists:nasabah,nik',
            'nama' => 'required|string',
            'id_klasifikasi' => 'required|integer|exists:klasifikasi,id',
            'total_penghasilan' => 'required|numeric|min:1',
            'angsuran_pengajuan' => 'required|numeric',
            'angsuran_lain' => 'required|numeric',
            'dbr' => 'required|numeric',
        ]);

        try {
            // Gunakan Transaction untuk keamanan data 
            $result = DB::transaction(function () use ($validatedData, $request) {
                // Ambil data yang sudah divalidasi
                $angsuran_pengajuan = $validatedData['angsuran_pengajuan'];
                $angsuran_lain = $validatedData['angsuran_lain'];
                $total_penghasilan = $validatedData['total_penghasilan'];
                $dbr = $validatedData['dbr'];

                // Tentukan hasil rekomendasi
                $hasil_rekomendasi = $dbr <= 80 ? "dapat direkomendasikan" : "tidak dapat direkomendasikan";

                // Data untuk membuat PDF
                $pdfData = [
                    'nik' => $validatedData['nik'],
                    'nama' => $validatedData['nama'],
                    'total_penghasilan' => $total_penghasilan,
                    'angsuran_pengajuan' => $angsuran_pengajuan,
                    'angsuran_lain' => $angsuran_lain,
                    'total_angsuran' => $angsuran_pengajuan + $angsuran_lain,
                    'dbr' => $dbr,
                    'hasil_rekomendasi' => $hasil_rekomendasi,
                ];

                // Buat dan simpan PDF
                $pdf = PDF::loadView('analisa.pdf', $pdfData);
                $fileName = 'analisa_dbr_' . Str::random(10) . '.pdf';
                $filePath = 'pdf/' . $fileName;
                Storage::disk('public')->put('pdf/' . $fileName, $pdf->output());

                // Simpan atau update data Analisa
                $analisa = Analisa::updateOrCreate(
                    ['id_klasifikasi' => $validatedData['id_klasifikasi']],
                    [
                        'id_user' => auth()->id(),
                        'angsuran_pengajuan' => $angsuran_pengajuan,
                        'angsuran_lain' => $angsuran_lain,
                        'total_angsuran' => $angsuran_pengajuan + $angsuran_lain,
                        'dbr' => $dbr,
                        'hasil_rekomendasi' => $hasil_rekomendasi,
                        'hasil_pdf' => $fileName,
                        'hasil_pdf' => $filePath,
                    ]
                );

                // Simpan atau update data Persetujuan
                Persetujuan::updateOrCreate(
                    ['analisa_id' => $analisa->id],
                    [
                        'user_id' => auth()->id(),
                        'status_persetujuan' => $hasil_rekomendasi, // Status awal sama dengan hasil rekomendasi
                        'catatan' => null,
                        'file_persetujuan' => null,
                    ]
                );
                
                // Kembalikan nilai DBR untuk respon JSON
                return $dbr;
            });

            // Respon JSON yang sesuai 
            return response()->json([
                'status' => 'success',
                'message' => 'Analisa berhasil disimpan.',
                'dbr' => $result, // $result berisi nilai DBR dari dalam transaction
            ]);

        } catch (\Exception $e) {
            // Jika terjadi error di dalam transaction, kirim respon error 500
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.',
                'error_detail' => $e->getMessage() // Hanya untuk mode debug
            ], 500);
        }
    }
}
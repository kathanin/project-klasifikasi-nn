<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Analisa;
use App\Models\Persetujuan;
use App\Models\Nasabah;
use App\Models\Legal;
use Illuminate\Support\Facades\DB;
class PersetujuanController extends Controller
{
    public function index(){
        // dd('Controller Persetujuan, method index berjalan');
        $persetujuans = Persetujuan::with('analisa.klasifikasi.nasabah')->latest()->get();
        return view('persetujuan.index', compact('persetujuans'));
    }

    public function show($id)
    {
        // PERBAIKAN: Gunakan nama variabel yang benar (huruf kecil, tanpa 's')
        $persetujuan = Persetujuan::with('analisa.klasifikasi.nasabah')->findOrFail($id);
        
        // Kirim variabel yang namanya sudah konsisten
        return view('persetujuan.detail', compact('persetujuan'));
    }

    public function hapus(Request $request){
        $ids = $request->input('id', []);

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $prs = Persetujuan::whereIn('id', $ids)->get();

        foreach ($prs as $pr) {
            $pr->delete();
            //Analisa::where('id',$pr->analisa_id)->delete();
        }

        return back()->with('success', 'Data Persetujuan berhasil dihapus.');
    }

    public function legalstore(Request $request){
    // Validasi input terlebih dahulu
    $request->validate([
        'id' => 'required|integer|exists:persetujuan,id',
        'catatan' => 'nullable|string',
    ]);

    try {
        // Gunakan transaction untuk memastikan semua proses berhasil atau semua gagal
        DB::transaction(function () use ($request) {
            
            // Ambil data menggunakan Eloquent & relasi (lebih bersih)
            $persetujuan = Persetujuan::with('analisa.klasifikasi.nasabah')->findOrFail($request->id);
            
            $analisa = $persetujuan->analisa;
            $klasifikasi = $analisa->klasifikasi;
            $nasabah = $klasifikasi->nasabah;

            // Buat atau update record di tabel Legal
            Legal::updateOrCreate(
                ['analisa_id' => $analisa->id],
                [
                    'user_id' => auth()->id(),
                    'catatan' => $request->catatan,
                    'surat_pengajuan' => $klasifikasi->surat_pengajuan,
                    'ktp' => $nasabah->ktp,
                    'kk' => $nasabah->kk,
                    'npwp' => $nasabah->npwp,
                    'pas_foto' => $nasabah->pas_foto,
                    'sk_karyawan' => $nasabah->sk_karyawan,
                    'bpjs' => $nasabah->bpjs,
                    'rekening_gaji' => $nasabah->rekening_gaji,
                    'rekening_koran' => $nasabah->rekening_koran,
                    'slip_gaji' => $nasabah->slip_gaji,
                    'jaminan' => $nasabah->jaminan,
                    'hasil_analisa_dbr' => $analisa->hasil_pdf,
                ]
            );

            $persetujuan->update(['catatan' => $request->catatan]);

            // HAPUS data lama setelah berhasil diproses
            // Menghapus dari anak ke induk untuk menghindari masalah foreign key
            $persetujuan->delete();
        });

    } catch (\Exception $e) {
        return response()->json(['message' => 'Gagal memproses data ke Legal.'], 500);
    }

    // 6. Siapkan notifikasi untuk ditampilkan setelah halaman di-reload
    session()->flash('success', 'Data berhasil dikirim ke Legal dan dihapus dari Persetujuan.');

    // 7. Kirim respon sukses ke JavaScript
    return response()->json(['message' => 'Data berhasil dikirim ke Legal.']);
    }
}

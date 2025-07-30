<?php

namespace App\Http\Controllers;

use App\Models\Legal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LegalController extends Controller
{
    /**
     * Menampilkan daftar data Legal menggunakan Eloquent.
     */
    public function index()
    {
        // Mengambil data dengan relasi untuk efisiensi (Eager Loading)
        $legals = Legal::with('analisa.klasifikasi.nasabah')->latest()->get();
        return view('legal.index', compact('legals'));
    }

    /**
     * Menampilkan halaman detail/edit untuk verifikasi.
     * Mengganti nama method 'create' menjadi 'edit' agar lebih sesuai.
     */
    public function edit($id)
    {
        // Mengambil data dengan relasi, atau gagal jika tidak ditemukan.
        $legal = Legal::with('analisa.klasifikasi.nasabah')->findOrFail($id);
        return view('legal.edit', compact('legal'));
    }

    /**
     * Menyimpan hasil verifikasi dokumen.
     */
    public function verifikasi(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:legal,id']);

        $legal = Legal::findOrFail($request->id);

        // Ambil semua input kecuali _token dan id
        $checklistData = $request->except(['_token', 'id']);

        // Simpan status centang sebagai JSON
        $legal->dokumen_checklist = json_encode($checklistData);

        // Tentukan status akhir berdasarkan kelengkapan
        $dokumenWajib = ['surat_pengajuan', 'ktp', 'kk', 'pas_foto', 'jaminan', 'sk_karyawan', 'slip_gaji', 'hasil_analisa_dbr'];
        $semuaLengkap = true;
        foreach ($dokumenWajib as $dokumen) {
            if (!isset($checklistData[$dokumen])) {
                $semuaLengkap = false;
                break;
            }
        }

        $legal->status = $semuaLengkap ? 'Terverifikasi' : 'Belum Terverifikasi';
        $legal->save();

        // Siapkan notifikasi untuk ditampilkan setelah redirect
        session()->flash('success', 'Verifikasi dokumen legal telah berhasil disimpan.');

        // Kirim respon JSON ke JavaScript
        return response()->json(['message' => 'Verifikasi berhasil disimpan.']);
    }

    /**
     * Menampilkan file dari storage secara inline di browser.
     *
     * @param Legal $legal
     * @param string $field
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function viewFile(Legal $legal, $field)
{
    $filePath = $legal->{$field};
    if (!$filePath || !Storage::disk('public')->exists($filePath)) {
        abort(404, 'File tidak ditemukan.');
    }
    $fullPath = Storage::disk('public')->path($filePath);
    return response()->file($fullPath);
}

    /**
     * Menghapus data legal yang dipilih.
     */
    public function hapus(Request $request)
    {
        $ids = $request->input('id', []);
        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 422);
        }

        $legals = Legal::whereIn('id', $ids)->get();

        // Loop ini untuk menghapus file-file dari storage
        foreach ($legals as $legal) {
            // Hapus semua file yang mungkin ada
            $filesToDelete = ['surat_pengajuan', 'ktp', 'kk', 'npwp', 'pas_foto', 'sk_karyawan', 'bpjs', 'rekening_gaji', 'rekening_koran', 'slip_gaji', 'jaminan', 'hasil_analisa_dbr'];
            foreach ($filesToDelete as $fileField) {
                if ($legal->{$fileField} && Storage::disk('public')->exists($legal->{$fileField})) {
                    Storage::disk('public')->delete($legal->{$fileField});
                }
            }
        }

        // Hapus record dari database
        $count = Legal::destroy($ids);

        session()->flash('success', $count . ' data legal berhasil dihapus.');

        return response()->json(['success' => true]);
    }
}
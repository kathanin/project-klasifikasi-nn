<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use App\Models\Nasabah;
use App\Models\Analisa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class KlasifikasiController extends Controller
{
    /**
     * Menampilkan daftar data klasifikasi.
     */
    public function index()
    {
        $klasifikasis = Klasifikasi::with('nasabah')->latest()->get();
        return view('klasifikasi.index', compact('klasifikasis'));
    }

    /**
     * Menampilkan form untuk input data klasifikasi.
     */
    public function create()
    {
        return view('klasifikasi.create');
    }

    /**
     * Memvalidasi, memanggil API, dan menampilkan halaman hasil.
     */
    public function hitung(Request $request)
    {
        // 1. Validasi data di awal
        $validatedData = $request->validate([
            'nik' => 'required|string|exists:nasabah,nik',
            'umur' => 'required|integer',
            'status_pernikahan' => 'required|string',
            'riwayat_kredit' => 'required|string',
            'jumlah_tabungan' => 'required|numeric',
            'tujuan_kredit' => 'required|string',
            'tenor' => 'required|integer',
            'jumlah_tanggungan' => 'required|integer',
            'surat_pengajuan' => 'required|file|mimes:pdf|max:2048',
        ]);

        // 2. Ambil data nasabah SETELAH validasi berhasil
        $nasabah = Nasabah::where('nik', $validatedData['nik'])->first();

        // 3. Simpan file SETELAH validasi berhasil
        $filename = $request->file('surat_pengajuan')->store('surat_pengajuan', 'public');

        // 4. Panggil API dengan data yang sudah bersih
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('api.url') . '/predict',  [
                "umur" => $validatedData['umur'],
                "status_pernikahan" => $validatedData['status_pernikahan'],
                "jumlah_pengajuan" => $nasabah->jumlah_pengajuan,
                "pekerjaan" => $nasabah->pekerjaan,
                "jumlah_penghasilan" => $nasabah->penghasilan,
                "riwayat_kredit" => $validatedData['riwayat_kredit'],
                "jumlah_tabungan" => $validatedData['jumlah_tabungan'],
                "tujuan_kredit" => $validatedData['tujuan_kredit'],
                "tenor" => $validatedData['tenor'],
                "jumlah_tanggungan" => $validatedData['jumlah_tanggungan'],
            ]);

        if ($response->failed()) {
            return back()->with('error', 'Gagal terhubung ke API klasifikasi. Silakan coba lagi.');
        }
        
        $dataApi = $response->json();

        dd($dataApi);

        // Tentukan hasil klasifikasi
        $hasil_klasifikasi = 'high';
        if ($dataApi['probabilitas_layak'] >= 0.7) $hasil_klasifikasi = 'low';
        elseif ($dataApi['probabilitas_layak'] >= 0.3) $hasil_klasifikasi = 'medium';

        // Siapkan data untuk dikirim ke view dan disimpan di session
        $dataHasil = [
            'nasabah_id' => $nasabah->id,
            'user_id' => auth()->id(), // Langsung siapkan user_id
            'status_pernikahan' => $validatedData['status_pernikahan'],
            'riwayat_kredit' => $validatedData['riwayat_kredit'],
            'jumlah_tabungan' => $validatedData['jumlah_tabungan'],
            'tujuan_kredit' => $validatedData['tujuan_kredit'],
            'tenor' => $validatedData['tenor'],
            'surat_pengajuan' => $filename,
            'hasil_klasifikasi' => $hasil_klasifikasi,
            'probabilitas_layak' => $dataApi['probabilitas_layak'],
            'status_teks' => $dataApi['status_teks'],
            'nama' => $nasabah->nama, // Untuk ditampilkan di view
            'nik' => $nasabah->nik,   // Untuk ditampilkan di view
        ];

        session()->flash('hasil_klasifikasi_data', $dataHasil);

        return view('klasifikasi.hasil', ['hasil' => $dataHasil]);
    }

    /**
     * Menyimpan data dari session ke database.
     */
    public function store(Request $request)
    {
        $dataToStore = session('hasil_klasifikasi_data');

        if (!$dataToStore) {
            return redirect()->route('klasifikasi.create')->with('error', 'Sesi hasil klasifikasi telah habis. Silakan ulangi.');
        }

        // Hapus data yang tidak perlu disimpan di tabel 'klasifikasi'
        unset($dataToStore['nama'], $dataToStore['nik']);

        Klasifikasi::create($dataToStore);

        return redirect()->route('klasifikasi.index')->with('success', 'Hasil klasifikasi berhasil disimpan!');
    }

    /**
     * Menghapus data klasifikasi yang dipilih.
     */
    public function hapus(Request $request)
    {
        $ids = $request->input('id', []);
        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 422);
        }

        // Ambil semua data klasifikasi yang akan dihapus
        $klasifikasis = Klasifikasi::with('analisa')->whereIn('id', $ids)->get();

        foreach ($klasifikasis as $klasifikasi) {
            // Hapus file dari storage
            if ($klasifikasi->surat_pengajuan && Storage::disk('public')->exists($klasifikasi->surat_pengajuan)) {
                Storage::disk('public')->delete($klasifikasi->surat_pengajuan);
            }
            
            // Hapus data turunan (analisa, persetujuan, legal)
            // Ini akan jauh lebih baik jika menggunakan onDelete('cascade') di migration
            if($klasifikasi->analisa) {
                // Hapus persetujuan dan legal yang terkait dengan analisa ini
                $klasifikasi->analisa->persetujuan()->delete();
                $klasifikasi->analisa->legal()->delete();
                $klasifikasi->analisa->delete();
            }
        }

        $count = Klasifikasi::destroy($ids);

        session()->flash('success', $count . ' data berhasil dihapus.');
        return response()->json(['success' => true]);
    }
}
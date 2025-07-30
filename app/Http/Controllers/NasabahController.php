<?php

namespace App\Http\Controllers;

use App\Models\Analisa;
use App\Models\Klasifikasi;
use App\Models\Legal;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class NasabahController extends Controller
{
    public function index()
    {
        $nasabahs = Nasabah::latest()->get();
        return view('marketing.nasabah', compact('nasabahs'));
    }

    public function create()
    {
        return view('marketing.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|digits:16|unique:nasabah,nik',
            'tgl_lahir' => 'required|date',
            'umur' => 'required|integer',
            'no_hp' => 'required|string|max:15',
            'kontak_darurat' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:nasabah,email',
            'alamat' => 'required|string',
            'jenis_nasabah' => 'required|string',
            'cabang' => 'required|string',
            'pengajuan' => 'required|numeric',
            'pekerjaan' => 'required|string',
            'penghasilan' => 'nullable|numeric',
            'ktp' => 'nullable|string',
            'kk' => 'nullable|string',
            'npwp' => 'nullable|string',
            'pas_foto' => 'nullable|string',
            'sk_karyawan' => 'nullable|string',
            'bpjs' => 'nullable|string',
            'rekening_gaji' => 'nullable|string',
            'rekening_koran' => 'nullable|string',
            'slip_gaji' => 'nullable|string',
            'jaminan' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();
        $data['jumlah_pengajuan'] = $data['pengajuan'];
        unset($data['pengajuan']);

        // Buat data nasabah baru
        $nasabah = Nasabah::create($data);

        // Hitung dan simpan statusnya
        $nasabah->status = $this->calculateStatus($nasabah);
        $nasabah->save();

        return redirect()->route('nasabah.index')->with('success', 'Data nasabah baru berhasil disimpan!');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/nasabah', $filename, 'public');
            return response()->json(['success' => true, 'filename' => $path]);
        }
        return response()->json(['success' => false]);
    }

    public function edit(Nasabah $nasabah)
    {
        return view('marketing.editnasabah', compact('nasabah'));
    }

    public function update(Request $request, Nasabah $nasabah)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|digits:16|unique:nasabah,nik,' . $nasabah->id,
            'tgl_lahir' => 'required|date',
            'no_hp' => 'required|string|max:15',
            'kontak_darurat' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:nasabah,email,' . $nasabah->id,
            'alamat' => 'required|string',
            'jenis_nasabah' => 'required|string',
            'cabang' => 'required|string',
            'pengajuan' => 'required|numeric',
            'pekerjaan' => 'required|string',
            'penghasilan' => 'nullable|numeric',
            'ktp' => 'nullable|string',
            'kk' => 'nullable|string',
            'npwp' => 'nullable|string',
            'pas_foto' => 'nullable|string',
            'sk_karyawan' => 'nullable|string',
            'bpjs' => 'nullable|string',
            'rekening_gaji' => 'nullable|string',
            'rekening_koran' => 'nullable|string',
            'slip_gaji' => 'nullable|string',
            'jaminan' => 'nullable|string',
        ]);

        $fileFields = ['ktp', 'kk', 'npwp', 'pas_foto', 'sk_karyawan', 'bpjs', 'rekening_gaji', 'rekening_koran', 'slip_gaji', 'jaminan'];
        foreach ($fileFields as $field) {
            $oldFile = $nasabah->{$field};
            $newFile = $data[$field] ?? null;
            if ($newFile !== $oldFile && $oldFile) {
                Storage::disk('public')->delete($oldFile);
            }
        }

        $data['jumlah_pengajuan'] = $data['pengajuan'];
        unset($data['pengajuan']);

        // Update data nasabah
        $nasabah->update($data);

        // Hitung ulang dan update statusnya
        $nasabah->status = $this->calculateStatus($nasabah);
        $nasabah->save();

        $klasifikasiIds = $nasabah->klasifikasi()->pluck('id');

    if ($klasifikasiIds->isNotEmpty()) {
        // Cari semua analisa yang terhubung dengan klasifikasi tersebut
        $analisaIds = Analisa::whereIn('id_klasifikasi', $klasifikasiIds)->pluck('id');

        if ($analisaIds->isNotEmpty()) {
            // Update semua data legal yang terhubung dengan analisa tersebut
            Legal::whereIn('analisa_id', $analisaIds)->update([
                'ktp' => $nasabah->ktp,
                'kk' => $nasabah->kk,
                'npwp' => $nasabah->npwp,
                'pas_foto' => $nasabah->pas_foto,
                'sk_karyawan' => $nasabah->sk_karyawan,
                'bpjs' => $nasabah->bpjs,
                'rekening_gaji' => $nasabah->rekening_gaji,
                'rekening_koran' => $nasabah->rekening_koran,
                'slip_gaji' => $nasabah->slip_gaji,
                'jaminan' => $nasabah->jaminan
            ]);
        }
    }

        return redirect()->route('nasabah.index')->with('success', 'Data nasabah berhasil diupdate!');
    }

    /**
     * FUNGSI BARU: Menghitung status nasabah berdasarkan kelengkapan data.
     */
    private function calculateStatus(Nasabah $nasabah): string
    {
        // Muat ulang data terbaru dari database
        $nasabah->refresh();

        // Daftar field data nasabah yang wajib diisi
        $dataFields = [
            'nama', 'nik', 'tgl_lahir', 'umur', 'no_hp', 'kontak_darurat',
            'email', 'alamat', 'jenis_nasabah', 'cabang', 'jumlah_pengajuan',
            'pekerjaan', 'penghasilan'
        ];

        foreach ($dataFields as $field) {
            if (empty($nasabah->{$field})) {
                return 'verifikasi'; // Jika ada data nasabah yang kosong
            }
        }

        // Daftar file yang wajib ada
        $fileFields = [
            'ktp', 'kk', 'pas_foto', 'sk_karyawan', 'slip_gaji', 'jaminan'
        ];

        foreach ($fileFields as $field) {
            if (empty($nasabah->{$field})) {
                return 'dokumen'; // Jika ada file yang kosong
            }
        }

        // Jika semua data dan file lengkap
        return 'analisa';
    }

    /**
     * FUNGSI BANTU: Mengupdate file di tabel Legal (jika perlu)
     */
    private function updateLegalFiles(Nasabah $nasabah)
    {
        $klasifikasiIds = $nasabah->klasifikasi()->pluck('id');
        if ($klasifikasiIds->isNotEmpty()) {
            $analisaIds = Analisa::whereIn('id_klasifikasi', $klasifikasiIds)->pluck('id');
            if ($analisaIds->isNotEmpty()) {
                Legal::whereIn('analisa_id', $analisaIds)->update([
                    'ktp' => $nasabah->ktp,
                    'kk' => $nasabah->kk,
                    'npwp' => $nasabah->npwp,
                    'pas_foto' => $nasabah->pas_foto,
                    'sk_karyawan' => $nasabah->sk_karyawan,
                    'bpjs' => $nasabah->bpjs,
                    'rekening_gaji' => $nasabah->rekening_gaji,
                    'rekening_koran' => $nasabah->rekening_koran,
                    'slip_gaji' => $nasabah->slip_gaji,
                    'jaminan' => $nasabah->jaminan
                ]);
            }
        }
    }

    public function hapus(Request $request)
    {
        $ids = $request->input('id', []);
        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 422);
        }

        $nasabahs = Nasabah::whereIn('id', $ids)->get();
        $fileFields = [
            'ktp', 'kk', 'npwp', 'pas_foto', 'sk_karyawan', 'bpjs', 
            'rekening_gaji', 'rekening_koran', 'slip_gaji', 'jaminan'
        ];

        foreach ($nasabahs as $nasabah) {
            foreach ($fileFields as $field) {
                if ($nasabah->{$field}) {
                    Storage::disk('public')->delete($nasabah->{$field});
                }
            }
            $nasabah->klasifikasi()->delete();
            // $nasabah->delete();
        }

        $count = Nasabah::destroy($ids);

        // return back()->with('success', 'Data nasabah yang dipilih berhasil dihapus.');

        session()->flash('success', $count . ' data nasabah berhasil dihapus.');

        return response()->json(['success' => true]);
    }

    public function fetchByNIK($nik)
    {
        $nasabah = Nasabah::where('nik', $nik)->first();
        if (!$nasabah) {
            return response()->json(null, 404);
        }
        return response()->json([
            'nama' => $nasabah->nama,
            'umur' => Carbon::parse($nasabah->tgl_lahir)->age,
            'pekerjaan' => $nasabah->pekerjaan,
            'penghasilan' => $nasabah->penghasilan,
            'jumlah_pengajuan' => $nasabah->jumlah_pengajuan,
        ]);
    }
}
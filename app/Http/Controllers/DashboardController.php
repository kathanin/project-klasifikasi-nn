<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- Import Carbon untuk manajemen tanggal

class DashboardController extends Controller
{
    public function redirect()
    {
        $role = Auth::user()->role;
        return match ($role) {
            'pimpinan', 'marketing' => redirect()->route('dashboard.index'), // Arahkan ke route dashboard
            'legal' => redirect()->route('legal.index'),
            default => abort(404),
        };
    }

    public function index()
    {
        // Menggunakan Carbon untuk mendapatkan tahun dan bulan saat ini
        $tahunIni = now()->year;
        $bulanIni = now()->month;

        // 1. Grafik Tahunan: Jumlah Pengajuan Nasabah (Eloquent)
        $dataBulanan = Nasabah::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereYear('created_at', $tahunIni)
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan');

        $nasabahTahunan = [];
        for ($i = 1; $i <= 12; $i++) {
            $nasabahTahunan[] = $dataBulanan->get($i, 0);
        }

        // 2. Grafik Bulanan: Tingkat Pengajuan per Minggu (Eloquent)
        $dataMingguan = Nasabah::select(
                DB::raw('FLOOR((DAY(created_at) - 1) / 7) + 1 AS minggu_ke'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->groupBy('minggu_ke')
            ->pluck('jumlah', 'minggu_ke');

        $nasabahMingguan = [];
        for ($i = 1; $i <= 4; $i++) {
            $nasabahMingguan[] = $dataMingguan->get($i, 0);
        }

        // 3. Grafik Donat: Presentase Klasifikasi (Eloquent)
        $dataKlasifikasi = Klasifikasi::select('hasil_klasifikasi', DB::raw('COUNT(*) as jumlah'))
            ->whereYear('created_at', $tahunIni)
            ->groupBy('hasil_klasifikasi')
            ->pluck('jumlah', 'hasil_klasifikasi');

        $hasilApi = [
            $dataKlasifikasi->get('low', 0),
            $dataKlasifikasi->get('medium', 0),
            $dataKlasifikasi->get('high', 0),
        ];

        // 4. Grafik Bulanan: Tingkat Penjualan Produk (Eloquent)
        $produkData = Klasifikasi::select('tujuan_kredit', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $bulanIni) // Filter berdasarkan bulan ini
            ->groupBy('tujuan_kredit')
            ->orderBy('total', 'desc')
            ->get();

        $produkLabels = $produkData->pluck('tujuan_kredit')->toArray();
        $produkTotals = $produkData->pluck('total')->toArray();
        // ==========================================================

        return view('pemilik.dashboard', [
            'nasabah' => $nasabahTahunan,
            'mingguan' => $nasabahMingguan,
            'bulan' => Carbon::now()->locale('id')->translatedFormat('F'), // Nama bulan dalam Bahasa Indonesia
            'hasilApi' => $hasilApi,
            'produkLabels' => $produkLabels, // Kirim data label produk
            'produkTotals' => $produkTotals, // Kirim data total produk
        ]);
    }
}
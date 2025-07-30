<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    protected $table = 'klasifikasi';

    protected $fillable = [
        'user_id',
        'nasabah_id',
        'status_pernikahan',
        'jumlah_pengajuan',
        'pekerjaan',
        'penghasilan',
        'riwayat_kredit',
        'jumlah_tabungan',
        'tujuan_kredit',
        'tenor',
        'surat_pengajuan',
        'hasil_klasifikasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    public function analisa()
    {
        return $this->hasOne(Analisa::class, 'id_klasifikasi');
    }
}

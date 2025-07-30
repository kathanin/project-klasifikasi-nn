<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analisa extends Model
{
    protected $table = 'analisa';

    protected $fillable = [
        'id_user',
        'id_klasifikasi',
        'nama',
        'nik',
        'penghasilan',
        'angsuran_pengajuan',
        'angsuran_lain',
        'total_angsuran',
        'dbr',
        'hasil_rekomendasi',
        'hasil_pdf',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'id_klasifikasi');
    }

    public function persetujuan()
    {
        return $this->hasOne(Persetujuan::class, 'analisa_id');
    }

    public function legal()
    {
        return $this->hasOne(Legal::class, 'analisa_id');
    }
}

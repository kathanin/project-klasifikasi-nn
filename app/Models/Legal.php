<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Legal extends Model
{
    protected $table = 'legal';

    protected $fillable = [
        'user_id',
        'analisa_id',
        'catatan',
        'surat_pengajuan',
        'ktp',
        'kk',
        'npwp',
        'pas_foto',
        'sk_karyawan',
        'bpjs',
        'rekening_gaji',
        'rekening_koran',
        'slip_gaji',
        'jaminan',
        'hasil_analisa_dbr',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function analisa()
    {
        return $this->belongsTo(Analisa::class, 'analisa_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    protected $table = 'nasabah';

    protected $fillable = [
        'user_id',
        'nama',
        'nik',
        'tgl_lahir',
        'umur',
        'no_hp',
        'kontak_darurat',
        'email',
        'alamat',
        'jenis_nasabah',
        'cabang',
        'pekerjaan',
        'penghasilan',
        'jumlah_pengajuan',
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
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function klasifikasi()
    // {
    //     return $this->hasOne(Klasifikasi::class);
    // }
    public function klasifikasi()
{
    return $this->hasMany(Klasifikasi::class);
}
}

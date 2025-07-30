<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    protected $table = 'persetujuan';

    protected $fillable = [
        'user_id',
        'analisa_id',
        'file_persetujuan',
        'status_persetujuan',
        'catatan',
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

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function nasabah()
    {
        return $this->hasOne(Nasabah::class);
    }

    public function klasifikasis()
    {
        return $this->hasMany(Klasifikasi::class);
    }

    public function analisases()
    {
        return $this->hasMany(Analisa::class, 'id_user');
    }

    public function persetujuans()
    {
        return $this->hasMany(Persetujuan::class);
    }

    public function legals()
    {
        return $this->hasMany(Legal::class);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nasabahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_ktp');
            $table->date('tgl_lahir');
            $table->string('tlp')->nullable();
            $table->string('kontak_darurat')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jenis_nasabah');
            $table->string('cabang');
            $table->string('pengajuan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->decimal('penghasilan', 15, 2)->nullable();
            $table->string('ktp')->nullable();
            $table->string('kk')->nullable();
            $table->string('npwp')->nullable();
            $table->string('foto')->nullable();
            $table->string('sk')->nullable();
            $table->string('bpjs')->nullable();
            $table->string('rek_gaji')->nullable();
            $table->string('rek_koran')->nullable();
            $table->string('slip_gaji')->nullable();
            $table->string('data_jaminan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nasabahs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('legal', function (Blueprint $table) {
        // Kolom untuk menyimpan status centang setiap dokumen dalam format JSON
        $table->json('dokumen_checklist')->nullable()->after('status');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal', function (Blueprint $table) {
            //
        });
    }
};

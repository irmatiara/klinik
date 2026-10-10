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
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
            $table->string('no_antrian')->nullable();
            $table->dateTime('tanggal_kunjungan');
            $table->enum('status', ['antri_triage', 'antri_dokter', 'periksa', 'kasir', 'apotek', 'selesai', 'batal'])->default('antri_triage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};

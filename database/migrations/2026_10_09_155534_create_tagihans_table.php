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
        Schema::create('tagihan', function (Blueprint $table) {         
            $table->id();                                                
            $table->foreignId('kunjungan_id')->constrained('kunjungan')->onDelete('cascade');                                              
            $table->integer('biaya_layanan')->default(0);                
            $table->integer('biaya_obat')->default(0);                   
            $table->integer('total_tagihan')->default(0);                
            $table->enum('status_bayar', ['belum_lunas', 'lunas'])->default('belum_lunas');                                           
            $table->timestamps();                                        
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};

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
        Schema::create('rekam_medis', function (Blueprint $table) {      
            $table->id();                                                
            $table->foreignId('kunjungan_id')->constrained('kunjungan')->onDelete('cascade');                                              
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // ID Dokter          
            $table->decimal('suhu', 4, 1)->nullable();                   
            $table->string('tekanan_darah')->nullable();                 
            $table->decimal('berat_badan', 5, 2)->nullable();            
            $table->text('keluhan')->nullable();                         
            $table->text('diagnosa')->nullable();                        
            $table->timestamps();                                        
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};

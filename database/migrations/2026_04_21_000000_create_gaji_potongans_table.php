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
        Schema::create('gaji_potongans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gaji_id')->constrained('gajis')->cascadeOnDelete();
            $table->foreignId('presensi_id')->nullable()->constrained('presensis')->nullOnDelete();
            $table->date('tanggal');
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->string('jenis', 50);
            $table->string('keterangan')->nullable();
            $table->decimal('jumlah_potongan', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_potongans');
    }
};

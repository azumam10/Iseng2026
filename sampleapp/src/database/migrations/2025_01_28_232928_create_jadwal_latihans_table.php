<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_latihans', function (Blueprint $table) {
    $table->id();

    $table->foreignId('atlet_id')
        ->constrained()
        ->onDelete('cascade');

    $table->dateTime('tanggal');
    $table->integer('durasi');

    $table->enum('jenis_latihan', [
        'Fisik',
        'Teknik',
        'Taktik',
        'Simulasi',
        'Recovery'
    ]);

    $table->foreignId('pelatih_id')
        ->constrained('pelatih')
        ->onDelete('cascade');

    $table->text('catatan')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_latihans');
    }
};

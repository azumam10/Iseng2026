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
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atlet_id')->constrained()->cascadeOnDelete();
            $table->string('kejuaraan');
            $table->string('kategori');
            $table->string('medali');
            $table->date('tanggal');
            $table->string('deskripsi');
            $table->string('dokumentasi_foto');//foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};

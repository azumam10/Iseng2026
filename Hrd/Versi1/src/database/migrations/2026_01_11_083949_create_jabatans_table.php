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
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jabatan');  // "P1,Q2,Q3"
            $table->string('nama')->unique();  // "Software Engineer", "HR Manager", "Staff Admin"
            $table->decimal('gaji_pokok_min', 15, 2)->nullable();
            $table->decimal('gaji_pokok_max', 15, 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->enum('gender', ['pria', 'perempuan']);

            $table->foreignId('dapartement_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('jabatan_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->string('alamat');
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_masuk');
            $table->integer('quota_cuti')->default(10);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};

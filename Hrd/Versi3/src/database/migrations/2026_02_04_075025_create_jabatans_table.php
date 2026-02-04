<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->foreignId('dapartement_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->decimal('gaji_pokok_min', 15, 2)->nullable();
            $table->decimal('gaji_pokok_max', 15, 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};

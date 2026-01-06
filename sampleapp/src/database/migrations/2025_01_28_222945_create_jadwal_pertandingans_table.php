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
        Schema::create('jadwal_pertandingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atlet_id')->constrained('atlets')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('lokasi');
            $table->string('status')->default('terjadwal');
            $table->string('hasil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pertandingans');
    }
};

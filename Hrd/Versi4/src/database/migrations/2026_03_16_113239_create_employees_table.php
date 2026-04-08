<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('no_ktp')->nullable();
            $table->string('agama')->nullable();
            $table->string('status_karyawan'); // PKWTT, PKWT, HARIAN
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('awal_kontrak')->nullable();
            $table->date('akhir_kontrak')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jabatan')->nullable();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->string('bagian_text')->nullable(); // backup dari Excel
            $table->foreignId('kepala_bagian_id')->nullable()->constrained('employees'); // atasan langsung
            $table->foreignId('user_id')->nullable()->constrained()->unique(); // untuk akun login kepala bagian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

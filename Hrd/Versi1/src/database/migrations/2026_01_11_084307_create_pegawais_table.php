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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();  // Nomor Induk Pegawai, unik
            $table->string('nama_lengkap');
            $table->enum('gender', ['pria', 'perempuan']);
            $table->string('email')->unique()->nullable();
            $table->string('no_hp')->nullable();
            $table->longtext('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('generasi', ['boomer','gen_x','milenial','gen_z','gen_alpha'])->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();  // untuk resign
            $table->enum('pendidikan', ['SMA','D3','S1','S2','S3'])->nullable();
            $table->string('status')->default('aktif');
            $table->decimal('kinerja_score',5,2)->nullable();  // skor kinerja karyawan
            $table->enum('kinerja', ['low', 'medium', 'high'])->nullable();
            $table->string('foto')->nullable();  // path foto pegawai
            $table->string('ktp')->nullable();   // path file KTP
            $table->string('npwp')->nullable();
            $table->string('gdrive_folder_link')->nullable();
            
            // Relasi
            $table->foreignId('departemen_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();
            
            $table->foreignId('jabatan_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

            
            $table->softDeletes();  // Hapus soft untuk resign (tidak benar-benar hapus data)
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};

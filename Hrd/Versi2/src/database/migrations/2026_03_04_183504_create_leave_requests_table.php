<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            
            // === TAMBAHKAN KOLOM BARU (Kabag, HRD, Reject) ===
            if (!$table->hasColumn('kabag_approved_by')) {
                $table->foreignId('kabag_approved_by')->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }

            if (!$table->hasColumn('hrd_approved_by')) {
                $table->foreignId('hrd_approved_by')->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }

            if (!$table->hasColumn('rejected_by')) {
                $table->foreignId('rejected_by')->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }

            // === HAPUS KOLOM LAMA approved_by_user_id ===
            if ($table->hasColumn('approved_by_user_id')) {
                // Hapus foreign key dulu
                $table->dropForeign(['approved_by_user_id']);
                // Baru hapus kolomnya
                $table->dropColumn('approved_by_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // Kembalikan kolom lama
            if (!$table->hasColumn('approved_by_user_id')) {
                $table->foreignId('approved_by_user_id')->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }

            // Hapus kolom baru
            $table->dropForeign(['kabag_approved_by']);
            $table->dropForeign(['hrd_approved_by']);
            $table->dropForeign(['rejected_by']);

            $table->dropColumn(['kabag_approved_by', 'hrd_approved_by', 'rejected_by']);
        });
    }
};
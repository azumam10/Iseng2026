<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('reviewer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('review_date');

            // Format: '2025-Q1'. Dipisah juga ke year + quarter
            // agar query filter per tahun/triwulan lebih mudah.
            $table->string('period');                       // '2025-Q1'
            $table->unsignedSmallInteger('period_year');    // 2025
            $table->unsignedTinyInteger('period_quarter');  // 1 / 2 / 3 / 4

            // Skor total dihitung otomatis dari weighted average detail
            $table->decimal('score', 5, 2)->nullable();

            $table->text('notes')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            // Satu karyawan hanya boleh punya satu review per periode per reviewer.
            // Saat pending boleh dihapus/diedit, tapi tidak boleh duplikat.
            $table->unique(['employee_id', 'reviewer_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};

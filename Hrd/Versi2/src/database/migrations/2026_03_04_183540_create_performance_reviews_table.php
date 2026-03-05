<?php

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
    Schema::create('performance_reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->foreignId('reviewer_id')->constrained('users'); // kepala bagian
        $table->date('review_date');
        $table->string('period'); // misal '2025-Q1' (triwulan)
        $table->decimal('score', 5, 2);
        $table->text('notes')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->foreignId('approved_by')->nullable()->constrained('users'); // HRGA
        $table->timestamp('approved_at')->nullable();
        $table->text('rejection_reason')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};

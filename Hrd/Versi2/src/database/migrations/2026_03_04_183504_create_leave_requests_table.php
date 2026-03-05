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
    Schema::create('leave_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->foreignId('leave_type_id')->constrained();
        $table->date('start_date');
        $table->date('end_date');
        $table->text('reason')->nullable();
        $table->enum('status', ['pending', 'kabag_approved', 'approved', 'rejected'])->default('pending');
        $table->foreignId('kabag_approved_by')->nullable()->constrained('users');
        $table->timestamp('kabag_approved_at')->nullable();
        $table->foreignId('hrd_approved_by')->nullable()->constrained('users');
        $table->timestamp('hrd_approved_at')->nullable();
        $table->foreignId('rejected_by')->nullable()->constrained('users');
        $table->timestamp('rejected_at')->nullable();
        $table->text('rejection_reason')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};

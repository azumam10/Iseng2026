<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            
            // siapa yang input (kepala bagian)
            $table->foreignId('requested_by_user_id')->constrained('users')->cascadeOnDelete();
            
            // approval HRD
            $table->foreignId('hrd_approved_by')->nullable()->constrained('users')->nullOnDelete();
            
            // reject
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->date('start_date');
            $table->date('end_date');
            
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'rejected'])
            ->default('pending');
            $table->timestamps();

});
    }
  
};
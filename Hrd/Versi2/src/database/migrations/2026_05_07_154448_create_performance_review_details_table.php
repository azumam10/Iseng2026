<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_review_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('performance_review_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('criteria_id')
                ->constrained('performance_criteria')
                ->cascadeOnDelete();

            $table->decimal('score', 5, 2);

            $table->timestamps();

            $table->unique(
                ['performance_review_id', 'criteria_id'],
                'prd_review_criteria_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_review_details');
    }
};
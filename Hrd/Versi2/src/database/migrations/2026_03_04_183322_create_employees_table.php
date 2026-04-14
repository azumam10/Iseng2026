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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('id_number')->unique(); // ID.No
        $table->string('name');
        $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('section_id')->nullable()->constrained()->nullOnDelete();
        $table->enum('employment_status', ['PKWTT', 'PKWT', 'HARIAN', 'DIREKTUR'])->default('PKWT');
        $table->enum('gender', ['L', 'P']);
        $table->date('birth_date');
        $table->integer('age')->virtualAs('TIMESTAMPDIFF(YEAR, birth_date, CURDATE())'); // MySQL
        $table->enum('generation', ['Gen Z', 'Milenial', 'Gen X', 'Baby Boomers'])->nullable();
        $table->date('hire_date');
        $table->string('education')->nullable(); // SD, SLTP, SMA, SMK, D3, S1
        $table->decimal('performance_score', 5, 2)->nullable(); // dari penilaian terakhir
        $table->enum('performance_category', ['Low', 'Med', 'High'])->nullable();
        $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
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

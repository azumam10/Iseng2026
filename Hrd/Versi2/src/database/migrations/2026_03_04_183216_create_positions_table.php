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
    Schema::create('positions', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // contoh: Operator, Staff, Kepala Bagian, Manajer
        $table->string('level')->nullable(); // contoh: OPERATOR, STAFF, MANAGER
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};

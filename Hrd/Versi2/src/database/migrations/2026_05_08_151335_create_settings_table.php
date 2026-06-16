<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('company_name')->nullable();
            $table->string('company_tagline')->nullable();

            $table->text('company_description')->nullable();

            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();

            $table->string('logo')->nullable();
            $table->string('hero_image')->nullable();

            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

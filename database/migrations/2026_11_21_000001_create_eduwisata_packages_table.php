<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eduwisata_packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('short_title');
            $table->string('tag')->default('Sekolah & Komunitas');
            $table->unsignedInteger('price')->default(0);
            $table->string('quota')->default('100–120 peserta/sesi');
            $table->string('subtitle')->nullable();
            $table->text('summary')->nullable();
            $table->string('image')->default('images/edukasi2.webp');
            $table->json('highlights')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eduwisata_packages');
    }
};
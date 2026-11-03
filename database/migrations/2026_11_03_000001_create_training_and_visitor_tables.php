<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_classes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('title');
            $table->decimal('price', 12, 2)->default(0);
            $table->string('duration');
            $table->string('schedule');
            $table->unsignedInteger('capacity')->default(0);
            $table->string('status')->default('Tersedia');
            $table->string('image')->nullable();
            $table->json('highlights')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->json('materials')->nullable();
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('training_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_class_id')->nullable()->constrained('training_classes')->nullOnDelete();
            $table->string('class_title');
            $table->string('name');
            $table->string('whatsapp', 30);
            $table->string('email');
            $table->string('institution')->nullable();
            $table->unsignedInteger('participants')->default(1);
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('eduwisata_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name');
            $table->string('group_name');
            $table->string('package_name');
            $table->string('whatsapp', 30);
            $table->date('visit_date');
            $table->string('session');
            $table->unsignedInteger('participants');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('eduwisata_reservations');
        Schema::dropIfExists('training_registrations');
        Schema::dropIfExists('training_classes');
    }
};

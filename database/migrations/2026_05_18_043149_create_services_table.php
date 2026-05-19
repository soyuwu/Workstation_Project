<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 100);
            $table->string('icon', 50);
            $table->string('badge', 50);
            $table->string('tagline', 255);
            $table->string('headline', 255);
            $table->text('description');
            $table->text('description_2')->nullable();
            $table->string('price', 50);
            $table->string('price_unit', 20);
            $table->string('capacity', 50);
            $table->enum('booking_type', ['hourly', 'monthly']);
            $table->string('booking_desc', 255)->nullable();
            $table->string('hero_image', 500);
            $table->string('detail_image', 500);
            $table->string('audience_image', 500);
            $table->json('features');
            $table->json('target_audience');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

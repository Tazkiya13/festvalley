<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Basic Information
            $table->string('stage_name');
            $table->string('real_name')->nullable();
            $table->text('bio')->nullable();
            $table->string('genre')->nullable();
            // $table->decimal('price_per_hour', 10, 2)->nullable();

            // Location Information
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('maps_link')->nullable(); // Google Maps link

            // Contact Information
            $table->string('phone')->nullable();
            $table->json('social_media')->nullable(); // {instagram, facebook, youtube, tiktok, etc}

            // Availability & Media
            $table->boolean('is_available')->default(true);
            $table->string('profile_photo')->nullable();
            $table->string('cover_photo')->nullable();

            // Professional Information
            $table->integer('years_experience')->nullable();
            $table->json('languages')->nullable(); // [Indonesian, English, Mandarin, etc]
            $table->json('equipment_owned')->nullable(); // [Guitar, Microphone, Sound System, etc]

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

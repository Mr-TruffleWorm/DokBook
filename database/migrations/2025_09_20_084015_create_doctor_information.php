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
        Schema::create('doctor_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('license_number')->unique();

            // Address
            $table->string('street_address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country', 5);

            // Professional Info
            $table->unsignedTinyInteger('years_experience')->nullable();
            $table->string('medical_school')->nullable();
            $table->string('hospital_affiliation')->nullable();

            // Specializations (JSON for multiple values)
            $table->json('specializations')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_information');
    }
};

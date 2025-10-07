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
         Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctor_information')->onDelete('cascade');
            
            // Date fields
            $table->year('year');
            $table->tinyInteger('month')->unsigned();
            $table->date('schedule_date');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            
            // Time fields
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration')->default(30);
            
            // Slot management
            $table->integer('max_slots_per_hour')->default(20);
            $table->integer('total_available_slots')->default(0);
            $table->integer('booked_slots')->default(0);
            
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Use a custom shorter name for the unique constraint
            $table->unique(['doctor_id', 'schedule_date', 'start_time', 'end_time'], 'doc_schedule_unique');
            $table->index(['doctor_id', 'schedule_date'], 'doc_schedule_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};

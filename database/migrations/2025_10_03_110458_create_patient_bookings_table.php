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
        Schema::create('patient_bookings', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys - linking to doctors and schedules
            $table->foreignId('doctor_id')->constrained('doctor_information')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('doctor_schedules')->onDelete('cascade');
            
            // Patient Personal Information
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('email', 150);
            $table->string('phone', 20);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            
            // Medical Information
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();
            $table->string('blood_type', 10)->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            
            // Appointment Details
            $table->string('specialization', 100);
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('chief_complaint', 255);
            $table->text('symptoms_description');
            $table->enum('urgency_level', ['routine', 'urgent', 'emergency'])->default('routine');
            
            // Appointment Status
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])
                  ->default('pending');
            
            // Additional fields
            $table->text('doctor_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['doctor_id', 'appointment_date'], 'booking_doctor_date_index');
            $table->index(['email', 'appointment_date'], 'booking_patient_date_index');
            $table->index('status', 'booking_status_index');
            $table->index('appointment_date', 'booking_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_bookings');
    }
};

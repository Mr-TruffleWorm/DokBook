<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Doctor extends Model
{   use HasFactory;
    protected $table = 'doctor_information';
    
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'license_number',
        'street_address',
        'city',
        'state',
        'postal_code',
        'country',
        'years_experience',
        'medical_school',
        'hospital_affiliation',
        'specializations',
    ];

    protected $casts = [
        'specializations' => 'array',
        'years_experience' => 'integer',
    ];
      /**
     * Get the user that owns this doctor information
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all schedules for this doctor
     */
    public function schedules()
    {
        return $this->hasMany(DoctorSchedules::class, 'doctor_id');
    }

    /**
     * Get active schedules
     */
    public function activeSchedules()
    {
        return $this->hasMany(DoctorSchedules::class, 'doctor_id')
            ->where('is_active', true)
            ->where('schedule_date', '>=', now()->toDateString());
    }

    /**
     * Get all appointments for this doctor
     */
    public function bookings()
    {
        return $this->hasMany(PatientBookings::class, 'doctor_id');
    }

    /**
     * Get today's appointments
     */
    public function todaysAppointments()
    {
        return $this->hasMany(PatientBookings::class, 'doctor_id')
            ->whereDate('appointment_date', now()->toDateString())
            ->orderBy('appointment_time');
    }

    /**
     * Get upcoming appointments
     */
    public function upcomingAppointments()
    {
        return $this->hasMany(PatientBookings::class, 'doctor_id')
            ->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope to filter by specialization
     */
    public function scopeWithSpecialization($query, $specialization)
    {
        return $query->whereJsonContains('specializations', $specialization);
    }

    /**
     * Scope to search doctors
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('license_number', 'like', "%{$search}%");
        });
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get doctor name with title
     */
    public function getDoctorNameAttribute(): string
    {
        return 'Dr. ' . $this->full_name;
    }

    /**
     * Get full address attribute
     */
    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->street_address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]));
    }

    /**
     * Get formatted specializations
     */
    public function getSpecializationsListAttribute(): string
    {
        if (!$this->specializations) {
            return 'N/A';
        }

        return implode(', ', $this->specializations);
    }

    // ============================================
    // METHODS
    // ============================================

    /**
     * Check if doctor has available slots for a specific date
     */
    public function hasAvailableSlots($date): bool
    {
        $schedule = $this->schedules()
            ->where('schedule_date', $date)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return false;
        }

        return $schedule->available_slots > 0;
    }

    /**
     * Get available dates for booking (next 30 days)
     */
    public function getAvailableDates($days = 30): array
    {
        return $this->schedules()
            ->where('is_active', true)
            ->where('schedule_date', '>=', now()->toDateString())
            ->where('schedule_date', '<=', now()->addDays($days)->toDateString())
            ->where('booked_slots', '<', DB::raw('total_available_slots'))
            ->pluck('schedule_date')
            ->toArray();
    }

    /**
     * Get statistics for this doctor
     */
    public function getStatistics(): array
    {
        return [
            'total_appointments' => $this->appointments()->count(),
            'pending_appointments' => $this->appointments()->where('status', 'pending')->count(),
            'confirmed_appointments' => $this->appointments()->where('status', 'confirmed')->count(),
            'completed_appointments' => $this->appointments()->where('status', 'completed')->count(),
            'cancelled_appointments' => $this->appointments()->where('status', 'cancelled')->count(),
            'today_appointments' => $this->todaysAppointments()->count(),
            'upcoming_appointments' => $this->upcomingAppointments()->count(),
        ];
    }
}
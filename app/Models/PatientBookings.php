<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PatientBookings extends Model
{
    use HasFactory;

    protected $table = 'patient_bookings';

    protected $fillable = [
        'doctor_id',
        'schedule_id',
        // Patient info
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        // Medical info
        'allergies',
        'current_medications',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        // Appointment details
        'specialization',
        'appointment_date',
        'appointment_time',
        'chief_complaint',
        'symptoms_description',
        'urgency_level',
        'status',
        // Additional
        'doctor_notes',
        'cancellation_reason',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i:s',
        'date_of_birth' => 'date',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function schedule()
    {
        return $this->belongsTo(DoctorSchedules::class, 'schedule_id');
    }

    /**
     * Accessors
     */
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    public function getFormattedAppointmentDateAttribute()
    {
        return Carbon::parse($this->appointment_date)->format('F j, Y');
    }

    public function getFormattedAppointmentTimeAttribute()
    {
        return Carbon::parse($this->appointment_time)->format('g:i A');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', Carbon::today())
                     ->whereIn('status', ['pending', 'confirmed'])
                     ->orderBy('appointment_date')
                     ->orderBy('appointment_time');
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('appointment_date', $date);
    }

    /**
     * Methods
     */
    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function cancel($reason = null)
    {
        // Decrement the booked slots in the schedule
        if ($this->schedule) {
            $this->schedule->decrement('booked_slots');
        }

        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
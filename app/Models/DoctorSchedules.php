<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DoctorSchedules extends Model
{
   use HasFactory;

    protected $fillable = [
        'doctor_id',
        'year',
        'month',
        'schedule_date',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration',
        'max_slots_per_hour',
        'total_available_slots',
        'booked_slots',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'year' => 'integer',
        'month' => 'integer',
        'slot_duration' => 'integer',
        'max_slots_per_hour' => 'integer',
        'total_available_slots' => 'integer',
        'booked_slots' => 'integer',
        'specializations' => 'array',
    ];

    protected $attributes = [
        'slot_duration' => 30,
        'max_slots_per_hour' => 20,
        'total_available_slots' => 0,
        'booked_slots' => 0,
        'is_active' => true,
    ];
    

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(PatientBookings::class, 'schedule_id');
    }

    // Accessors
    public function getAvailableSlotsAttribute()
    {
        // Prevent negative numbers
        $available = $this->total_available_slots - $this->booked_slots;
        return max($available, 0);
    }

    public function getIsFullyBookedAttribute()
    {
        return $this->booked_slots >= $this->total_available_slots;
    }

    public function getUtilizationRateAttribute()
    {
        if ($this->total_available_slots <= 0) {
            return 0;
        }

        $used = $this->booked_slots;
        return round(($used / $this->total_available_slots) * 100);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('schedule_date', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('schedule_date', '>=', now()->toDateString())
                    ->orderBy('schedule_date')
                    ->orderBy('start_time');
    }

    public function scopePast($query)
    {
        return $query->where('schedule_date', '<', now()->toDateString())
                    ->orderBy('schedule_date', 'desc')
                    ->orderBy('start_time', 'desc');
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)
                    ->where('month', $month);
    }

    public function scopeAvailableSlots($query)
    {
        return $query->whereRaw('booked_slots < total_available_slots');
    }

    // Methods
    public function calculateTotalSlots()
    {
        try {
            $startTime = Carbon::parse($this->start_time);
            $endTime = Carbon::parse($this->end_time);

            if ($endTime->lessThanOrEqualTo($startTime)) {
                return 0; // avoid negative intervals
            }

            // Total minutes available
            $totalMinutes = $endTime->diffInMinutes($startTime);

            // How many slots can fit based on slot duration
            $slotsByDuration = floor($totalMinutes / $this->slot_duration);

            // How many slots allowed based on max per hour
            $totalHours = $totalMinutes / 60;
            $slotsByMaxPerHour = floor($totalHours * $this->max_slots_per_hour);

            return max(min($slotsByDuration, $slotsByMaxPerHour), 0); // prevent negative
        } catch (\Exception $e) {
            return 0;
        }
    }
    public function canBookSlot()
    {
        return $this->is_active 
            && $this->booked_slots < $this->total_available_slots
            && $this->schedule_date >= now()->toDateString();
    }

    public function incrementBookedSlots()
    {
        $this->increment('booked_slots');
    }

    public function decrementBookedSlots()
    {
        if ($this->booked_slots > 0) {
            $this->decrement('booked_slots');
        }
    }

    // Boot method for automatic field population
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($schedule) {
            // Auto-populate year, month, and day_of_week from schedule_date
            if ($schedule->schedule_date) {
                $date = Carbon::parse($schedule->schedule_date);
                $schedule->year = $date->year;
                $schedule->month = $date->month;
                $schedule->day_of_week = strtolower($date->format('l'));
            }

            // Auto-calculate total available slots if not set
            if ($schedule->total_available_slots == 0) {
                $schedule->total_available_slots = $schedule->calculateTotalSlots();
            }
        });

        static::updating(function ($schedule) {
            // Update year, month, and day_of_week if schedule_date changes
            if ($schedule->isDirty('schedule_date')) {
                $date = Carbon::parse($schedule->schedule_date);
                $schedule->year = $date->year;
                $schedule->month = $date->month;
                $schedule->day_of_week = strtolower($date->format('l'));
            }

            // Recalculate total slots if time or slot configuration changes
            if ($schedule->isDirty(['start_time', 'end_time', 'max_slots_per_hour'])) {
                $schedule->total_available_slots = $schedule->calculateTotalSlots();
            }
        });
    }
}
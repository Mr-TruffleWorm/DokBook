<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function doctor_information()
    {
        return $this->hasOne(Doctor::class, 'user_id'); // or whatever your foreign key is
    }
    public function schedules()
    {
        return $this->hasManyThrough(
            DoctorSchedules::class, // final model
            Doctor::class,          // intermediate model
            'user_id',              // FK on doctor_information
            'doctor_id',            // FK on doctor_schedules
            'id',                   // PK on users
            'id'                    // PK on doctor_information
        );
    }

    // Optional: Add a scope for doctors only
    public function scopeDoctors($query)
    {
        return $query->where('role', 'doctor');
    }
}

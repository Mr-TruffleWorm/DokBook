<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{   
    protected $table = 'doctor_information';
    protected $fillable = [
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
    ];
}
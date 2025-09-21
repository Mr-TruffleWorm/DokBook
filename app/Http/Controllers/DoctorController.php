<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function appointment_table()
    {
        return view('doctors.appointments');
    }
    public function schedule()
    {
        return view('doctors.set-appointment-sched');
    }
    public function patients_table()
    {
        return view('doctors.patients');
    }
    public function reports()
    {
        return view('doctors.reports');
    }





    public function bySpecialty($specialty)
    {
        $doctors = Doctor::whereHas('specialization', function($query) use ($specialty) {
            $query->where('slug', $specialty);
        })->get();
        
        return view('doctors.by-specialty', compact('doctors', 'specialty'));
    }
    public function byCondition($condition)
    {
        // You might want to create a conditions table and relate it to doctors
        $doctors = Doctor::whereHas('treatedConditions', function($query) use ($condition) {
            $query->where('slug', $condition);
        })->get();
        
        return view('doctors.by-condition', compact('doctors', 'condition'));
    }
}

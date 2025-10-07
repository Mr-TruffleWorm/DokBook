<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class HomepageController extends Controller
{
    public function index()
    {
        // Get all doctors from the database
        $doctors = Doctor::all();
        
        // Pass doctors to the view
        return view('welcome', compact('doctors'));
    }
}

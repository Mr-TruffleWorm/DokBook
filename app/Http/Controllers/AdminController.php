<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{   

    public function index()
    {
        return view('admin.admin-dashboard');
    }
    public function createDoctor()
    {
        return view('admin.add-doctor');
    }

    public function add_doctor_info(Request $request)
{
    $validated = $request->validate([
        // Doctor info
        'first_name'           => 'required|string|max:100',
        'last_name'            => 'required|string|max:100',
        'phone'                => 'required|string|max:20',
        'license_number'       => 'required|string|max:50|unique:doctor_information,license_number',
        'street_address'       => 'required|string|max:255',
        'city'                 => 'required|string|max:100',
        'state'                => 'required|string|max:100',
        'postal_code'          => 'required|string|max:20',
        'country'              => 'required|string|max:5',
        'years_experience'     => 'nullable|integer|min:0|max:50',
        'medical_school'       => 'nullable|string|max:255',
        'hospital_affiliation' => 'nullable|string|max:255',
        'specializations'      => 'nullable|array',
        'specializations.*'    => 'string|max:100',

        // Account info
        'name'                 => 'required|string|max:255',
        'email'                => 'required|email|unique:users,email',
        'password'             => 'required|string|min:6|confirmed',
    ]);

    // 1. Create User account
    $user = User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role'     => 'doctor',
    ]);

    // 2. Create Doctor info (link to User if needed)
    Doctor::create([
        'first_name'           => $validated['first_name'],
        'last_name'            => $validated['last_name'],
        'phone'                => $validated['phone'],
        'license_number'       => $validated['license_number'],
        'street_address'       => $validated['street_address'],
        'city'                 => $validated['city'],
        'state'                => $validated['state'],
        'postal_code'          => $validated['postal_code'],
        'country'              => $validated['country'],
        'years_experience'     => $validated['years_experience'],
        'medical_school'       => $validated['medical_school'],
        'hospital_affiliation' => $validated['hospital_affiliation'],
        'specializations'      => $validated['specializations'] ?? [],
        // optionally add: 'user_id' => $user->id, if you have that column
    ]);

    return redirect()->route('admin.list-doctors')
                     ->with('success', 'Doctor account and information saved successfully!');
}

    public function list_of_doctors()
    {
        $doctors = Doctor::all();
        return view('admin.view-doctors', compact('doctors'));
    }


    public function createService()
    {
        return view('admin.add-new-service');
    }
    public function storeDoctor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Create doctor user
        $doctor = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'usertype' => 'doctor', // Make sure you have this column in `users`
        ]);

        // Optionally store specialization in a separate doctors table if you want
        // Doctor::create([...]);

        return redirect()->route('admin.dashboard')->with('success', 'Doctor added successfully.');
    }

}

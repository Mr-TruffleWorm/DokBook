<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{   

    public function admin_dashboard()
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

            // 2. Create Doctor info (MUST link to user!)
            Doctor::create([
                'user_id'              => $user->id, 
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
            ]);

        return redirect()->route('admin.list-doctors')
                        ->with('success', 'Doctor account and information saved successfully!');
    }

    
    /**
     * Display list of all doctors
     */
    public function list_of_doctors()
    {
        // Get all doctors with their user accounts
        $doctors = Doctor::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.view-doctors', compact('doctors'));
    }
     public function editDoctor($id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);
        
        return view('admin.edit-doctor', compact('doctor'));
    }

    /**
     * Update doctor information
     */
    public function updateDoctor(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        // Validate with unique exception for current doctor
        $validated = $request->validate([
            // Personal Information
            'first_name'           => 'required|string|max:100',
            'last_name'            => 'required|string|max:100',
            'phone'                => 'required|string|max:20',
            'license_number'       => 'required|string|max:50|unique:doctor_information,license_number,' . $id,
            
            // Address Information
            'street_address'       => 'required|string|max:255',
            'city'                 => 'required|string|max:100',
            'state'                => 'required|string|max:100',
            'postal_code'          => 'required|string|max:20',
            'country'              => 'required|string|max:5',
            
            // Professional Information
            'years_experience'     => 'nullable|integer|min:0|max:50',
            'medical_school'       => 'nullable|string|max:255',
            'hospital_affiliation' => 'nullable|string|max:255',
            'specializations'      => 'nullable|array',
            'specializations.*'    => 'string|max:100',

            // Account Information (optional password update)
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255|unique:users,email,' . $doctor->user_id,
            'password'             => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // Update doctor information
            $doctor->update([
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
            ]);

            // Update user account
            $userUpdateData = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $userUpdateData['password'] = Hash::make($validated['password']);
            }

            $doctor->user->update($userUpdateData);

            DB::commit();

            return redirect()
                ->route('admin.list-doctors')
                ->with('success', "Doctor {$doctor->full_name} has been successfully updated!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update doctor: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update doctor information. Please try again.');
        }
    }

    /**
     * Delete doctor
     */
    public function deleteDoctor($id)
    {
        DB::beginTransaction();

        try {
            $doctor = Doctor::findOrFail($id);
            $doctorName = $doctor->full_name;
            
            // Check if doctor has appointments
            $hasAppointments = $doctor->appointments()->exists();
            
            if ($hasAppointments) {
                return redirect()
                    ->back()
                    ->with('error', "Cannot delete Dr. {$doctorName} because they have existing appointments.");
            }

            // Delete user account (will cascade delete doctor info)
            $doctor->user->delete();

            DB::commit();

            return redirect()
                ->route('admin.list-doctors')
                ->with('success', "Dr. {$doctorName} has been successfully deleted.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete doctor: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to delete doctor. Please try again.');
        }
    }


    public function createService()
    {
        return view('admin.add-new-service');
    }

}

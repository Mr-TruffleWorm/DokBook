<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\PatientBookings;
use App\Models\Doctor;
use App\Models\DoctorSchedules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{
    public function showBookingPage($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        // Get active schedules with available slots
        $schedules = DoctorSchedules::where('doctor_id', $doctorId)
            ->whereDate('schedule_date', '>=', Carbon::today())
            ->where('is_active', true)
            ->whereRaw('booked_slots < total_available_slots')
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('schedule_date');

        // servicesBySpecialization mapping
        $servicesBySpecialization = [
            'General Medicine / Family Medicine' => [
                'General Check-up', 'Fever / Cough / Cold', 'Hypertension / Diabetes Management', 'Follow-up Consultation'
            ],
            'Pediatrics (Child Care)' => [
                'Well-Baby Check-up', 'Vaccination', 'Growth & Development Monitoring', 'Pediatric Illness Consultation'
            ],
            'Obstetrics & Gynecology (Women\'s Health)' => [
                'Prenatal Consultation', 'Family Planning', 'Gynecological Concerns', 'Menstrual Health Consultation'
            ],
            'Internal Medicine' => [
                'Diabetes Consultation', 'Hypertension / Heart Disease', 'Chronic Illness Management', 'General Adult Health Check'
            ],
            'Dermatology (Skin)' => [
                'Acne Treatment', 'Skin Allergy Consultation', 'Mole / Wart Removal', 'General Skin Check-up'
            ],
            'Ophthalmology (Eye)' => [
                'Eye Check-up', 'Vision Test (Glasses)', 'Eye Infection Consultation', 'Cataract / Glaucoma Screening'
            ],
            'ENT / Otolaryngology (Ear, Nose, Throat)' => [
                'Ear Pain / Hearing Issues', 'Throat Infection', 'Sinus / Nasal Problems', 'Tonsillitis Consultation'
            ],
            'Cardiology (Heart)' => [
                'Heart Check-up', 'Chest Pain Evaluation', 'ECG Consultation', 'Hypertension Management'
            ],
            'Orthopedics (Bones & Joints)' => [
                'Fracture Check-up', 'Joint Pain / Arthritis', 'Back Pain Consultation', 'Sports Injury Evaluation'
            ],
            'Psychiatry / Mental Health' => [
                'Depression / Anxiety Consultation', 'Stress Management', 'Psychiatric Evaluation', 'Counseling Session'
            ],
        ];

        // Doctor's specializations are JSON, auto-cast to array in the model
        $doctorSpecs = $doctor->specializations ?? [];

        // Build list of complaints for this doctor based on their specs
        $complaints = [];
        foreach ($doctorSpecs as $spec) {
            if (isset($servicesBySpecialization[$spec])) {
                $complaints = array_merge($complaints, $servicesBySpecialization[$spec]);
            }
        }
        $complaints = array_values(array_unique($complaints));

        // Default if no specialization matched
        if (empty($complaints)) {
            $complaints = ['General Consultation'];
        }

        return view('booking_page', compact(
            'schedules',
            'doctorId',
            'doctor',
            'doctorSpecs',
            'complaints',
            'servicesBySpecialization'
        ));
    }

    /**
     * Send verification code to email
     */
    public function sendVerificationCode(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            
            $email = $request->email;
            
            // Check if there's a recent code sent (rate limiting)
            $recentlySent = Cache::get("email_code_sent_at_{$email}");
            if ($recentlySent && now()->diffInSeconds($recentlySent) < 60) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please wait before requesting another code.'
                ], 429);
            }
            
            // Generate 6-digit code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store code in cache for 10 minutes
            Cache::put("email_verification_code_{$email}", $code, now()->addMinutes(10));
            Cache::put("email_verification_attempts_{$email}", 0, now()->addMinutes(10));
            Cache::put("email_code_sent_at_{$email}", now(), now()->addMinutes(2));
            
            // Send email
            Mail::raw(
                "Your verification code for the appointment booking is: {$code}\n\n" .
                "This code will expire in 10 minutes.\n\n" .
                "If you did not request this code, please ignore this email.\n\n" .
                "Thank you!",
                function($message) use ($email) {
                    $message->to($email)
                            ->subject('Appointment Booking - Email Verification Code');
                }
            );
            
            Log::info("Verification code sent to {$email}");
            
            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email. Please check your inbox.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send verification code', [
                'email' => $request->email ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify email code
     */
    public function verifyEmailCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'code' => 'required|string|size:6'
            ]);
            
            $email = $request->email;
            $code = $request->code;
            
            // Get stored code
            $storedCode = Cache::get("email_verification_code_{$email}");
            
            if (!$storedCode) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Verification code expired. Please request a new one.'
                ]);
            }
            
            // Check attempts (prevent brute force)
            $attempts = Cache::get("email_verification_attempts_{$email}", 0);
            if ($attempts >= 5) {
                Cache::forget("email_verification_code_{$email}");
                Cache::forget("email_verification_attempts_{$email}");
                
                Log::warning("Too many verification attempts for email: {$email}");
                
                return response()->json([
                    'valid' => false,
                    'message' => 'Too many failed attempts. Please request a new code.'
                ]);
            }
            
            // Verify code
            if ($storedCode !== $code) {
                Cache::put("email_verification_attempts_{$email}", $attempts + 1, now()->addMinutes(10));
                
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid verification code. Please try again.'
                ]);
            }
            
            // Mark email as verified (valid for 1 hour)
            Cache::put("email_verified_{$email}", true, now()->addHour());
            
            // Clean up verification data
            Cache::forget("email_verification_code_{$email}");
            Cache::forget("email_verification_attempts_{$email}");
            Cache::forget("email_code_sent_at_{$email}");
            
            Log::info("Email verified successfully: {$email}");
            
            return response()->json([
                'valid' => true,
                'message' => 'Email verified successfully!'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid input. Please check your email and code.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Email verification failed', [
                'email' => $request->email ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'valid' => false,
                'message' => 'Verification failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Book appointment
     */
    public function bookAppointment(Request $request, $doctorId)
    {
        try {
            Log::info('Booking appointment request', $request->all());

            // CHECK IF EMAIL IS VERIFIED
            $emailVerified = Cache::get("email_verified_{$request->email}");
            if (!$emailVerified) {
                Log::warning('Booking attempt with unverified email', [
                    'email' => $request->email
                ]);
                
                return back()
                    ->with('error', 'Please verify your email address before booking.')
                    ->withInput();
            }

            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email|max:150',
                'phone' => 'required|string|max:20',
                'specialization' => 'required|string|max:100',
                'chief_complaint' => 'required|string|max:255',
                'symptoms_description' => 'required|string',
                'urgency_level' => 'required|in:routine,urgent,emergency',
                'schedule_id' => 'required|exists:doctor_schedules,id',

                'middle_name' => 'nullable|string|max:100',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female',
                'allergies' => 'nullable|string',
                'current_medications' => 'nullable|string',
                'blood_type' => 'nullable|string|max:10',
                'emergency_contact_name' => 'nullable|string|max:100',
                'emergency_contact_phone' => 'nullable|string|max:20',
            ]);

            DB::beginTransaction();

            $schedule = DoctorSchedules::lockForUpdate()->findOrFail($validated['schedule_id']);

            if ($schedule->booked_slots >= $schedule->total_available_slots) {
                DB::rollBack();
                return back()->with('error', 'Sorry, this time slot is now fully booked.')->withInput();
            }

            $appointmentDate = $schedule->schedule_date;
            $appointmentTime = $schedule->start_time;

            $existingBooking = PatientBookings::where('email', $validated['email'])
                ->where('appointment_date', $appointmentDate)
                ->where('appointment_time', $appointmentTime)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if ($existingBooking) {
                DB::rollBack();
                return back()->with('error', 'You already have a booking at this date and time.')->withInput();
            }

            $booking = PatientBookings::create([
                'doctor_id' => $doctorId,
                'schedule_id' => $validated['schedule_id'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'allergies' => $validated['allergies'] ?? null,
                'current_medications' => $validated['current_medications'] ?? null,
                'blood_type' => $validated['blood_type'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'specialization' => $validated['specialization'],
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
                'chief_complaint' => $validated['chief_complaint'],
                'symptoms_description' => $validated['symptoms_description'],
                'urgency_level' => $validated['urgency_level'],
                'status' => 'pending',
            ]);

            $schedule->increment('booked_slots');

            DB::commit();

            // Clear email verification after successful booking
            Cache::forget("email_verified_{$validated['email']}");

            // Load the doctor relationship to access the doctor_name accessor
            $booking->load('doctor');

            // Send confirmation email
            $this->sendBookingConfirmationEmail($booking);

            $appointmentData = [
                'id' => $booking->id,
                'appointment_date' => $booking->formatted_appointment_date,
                'appointment_time' => $booking->formatted_appointment_time,
                'patient_name' => $booking->full_name,
                'doctor_name' => $booking->doctor_name,
                'status' => $booking->status,
            ];

            Log::info('Appointment booked successfully', [
                'booking_id' => $booking->id,
                'email' => $validated['email']
            ]);

            return redirect()
                ->route('home', $doctorId)
                ->with('success', 'Appointment booked successfully! Check your email for confirmation.')
                ->with('appointment', $appointmentData);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Booking failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Send booking confirmation email
     */
    private function sendBookingConfirmationEmail($booking)
    {
        try {
            $doctor = $booking->doctor;
            
            $emailBody = "Dear {$booking->full_name},\n\n";
            $emailBody .= "Your appointment has been booked successfully!\n\n";
            $emailBody .= "Appointment Details:\n";
            $emailBody .= "-------------------\n";
            $emailBody .= "Doctor: Dr. {$doctor->first_name} {$doctor->last_name}\n";
            $emailBody .= "Specialization: {$booking->specialization}\n";
            $emailBody .= "Date: {$booking->formatted_appointment_date}\n";
            $emailBody .= "Time: {$booking->formatted_appointment_time}\n";
            $emailBody .= "Purpose: {$booking->chief_complaint}\n";
            $emailBody .= "Status: {$booking->status}\n\n";
            $emailBody .= "Please arrive 15 minutes before your scheduled time.\n\n";
            $emailBody .= "If you need to cancel or reschedule, please contact us as soon as possible.\n\n";
            $emailBody .= "Thank you!";

            Mail::raw($emailBody, function($message) use ($booking) {
                $message->to($booking->email)
                        ->subject('Appointment Confirmation - Booking #' . $booking->id);
            });

            Log::info('Confirmation email sent', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            // Don't fail the booking if email fails
        }
    }

    /**
     * Confirm booking
     */
    public function confirmBooking($id)
    {
        try {
            $booking = PatientBookings::findOrFail($id);
            $booking->confirm(); // Using the model method
            
            Log::info('Appointment confirmed', ['booking_id' => $id]);
            
            return back()->with('success', 'Appointment confirmed successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to confirm appointment', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to confirm appointment.');
        }
    }

    /**
     * Cancel booking
     */
    public function cancelBooking($id)
    {
        try {
            $booking = PatientBookings::findOrFail($id);
            $booking->cancel(); // Using the model method
            
            Log::info('Appointment cancelled', ['booking_id' => $id]);
            
            return back()->with('success', 'Appointment cancelled.');
        } catch (\Exception $e) {
            Log::error('Failed to cancel appointment', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to cancel appointment.');
        }
    }

    /**
     * Complete booking
     */
    public function completeBooking($id)
    {
        try {
            $booking = PatientBookings::findOrFail($id);
            $booking->complete(); // Using the model method
            
            Log::info('Appointment completed', ['booking_id' => $id]);
            
            return back()->with('success', 'Appointment marked as completed.');
        } catch (\Exception $e) {
            Log::error('Failed to complete appointment', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to complete appointment.');
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSchedules;
use App\Models\PatientBookings;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorController extends Controller
{   
    /**
     * Get the authenticated doctor's ID with proper error handling
     */
    private function getDoctorId()
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Unauthenticated');
        }
        
        if (!$user->doctor_information) {
            abort(403, 'No doctor profile found. Please contact administrator.');
        }
        
        return $user->doctor_information->id;
    }

    public function doctor_dashboard()
    {
        return view('doctors.doctor-dashboard');
    }

public function appointment_table(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user || $user->role !== 'doctor') {
            return redirect()->route('login')
                ->withErrors(['error' => 'Unable to access doctor information. Please login again.']);
        }

        // Get the doctor profile linked to this user
        $doctor = $user->doctor_information;

        if (!$doctor) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Doctor profile not found.']);
        }
    } catch (\Exception $e) {
        return redirect()->route('login')
            ->withErrors(['error' => 'Unable to access doctor information. Please login again.']);
    }

    // Get current month and year, or from request
    $currentMonth = $request->get('month', now()->month);
    $currentYear  = $request->get('year', now()->year);

    // Fetch schedules through doctor model
    $schedules = $doctor->schedules()
        ->whereDate('schedule_date', '>=', now()->toDateString())
        ->orderBy('schedule_date')
        ->orderBy('start_time')
        ->get()
        ->groupBy('schedule_date');

    $monthName = Carbon::create()->month($currentMonth)->format('F');

    // ✅ Fetch all patient bookings for this doctor
    $bookings = PatientBookings::where('doctor_id', $doctor->id)
        ->orderByDesc('appointment_date')
        ->get();

    // Pass both schedules and bookings to the view
    return view('doctors.available-schedules', compact('schedules', 'bookings', 'currentMonth', 'currentYear', 'monthName'));
}
    /**
     * Show the form for creating a new schedule.
     */
    public function create(): View
    {
        // Verify doctor exists
        $this->getDoctorId();
        
        $slotDurations = [
            15 => '15 minutes',
            30 => '30 minutes',
            45 => '45 minutes',
            60 => '1 hour'
        ];

        return view('doctors.set-appointment-sched', compact('slotDurations'));
    }

    public function set_schedule(Request $request): RedirectResponse
    {   
        $doctorId = $this->getDoctorId();

        $validated = $request->validate([
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i|before:end_time',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'required|integer|in:15,30,45,60',
            'max_slots_per_hour' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Validate time range is within 8 AM - 4 PM
        $startHour = (int) Carbon::parse($validated['start_time'])->format('H');
        $endHour = (int) Carbon::parse($validated['end_time'])->format('H');
        
        if ($startHour < 8 || $endHour > 16 || ($endHour === 16 && Carbon::parse($validated['end_time'])->format('i') !== '00')) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Schedule must be between 8:00 AM and 4:00 PM.']);
        }

        // Check for overlapping schedules
        $overlapping = DoctorSchedules::forDoctor($doctorId)
            ->forDate($validated['schedule_date'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return back()
                ->withInput()
                ->withErrors(['schedule_date' => 'A schedule already exists for this date and time range.']);
        }

        try {
            DB::beginTransaction();

            $schedule = new DoctorSchedules($validated);
            $schedule->doctor_id = $doctorId;
            $schedule->save();

            DB::commit();

            return redirect()->route('doctor.schedule')
                ->with('success', 'Schedule added successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create schedule. Please try again.']);
        }
    }

    public function edit(DoctorSchedules $schedule): View
    {
        $doctorId = $this->getDoctorId();
        
        if ($schedule->doctor_id !== $doctorId) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent editing past schedules
        if ($schedule->schedule_date < now()->toDateString()) {
            $error = 'Cannot edit past schedules.';
            $slotDurations = [
                15 => '15 minutes',
                30 => '30 minutes',
                45 => '45 minutes',
                60 => '1 hour'
            ];
            return view('doctors.set-appointment-sched', compact('schedule', 'slotDurations', 'error'));
        }
        
        // Prevent editing if there are booked appointments
        if ($schedule->booked_slots > 0) {
            $error = 'Cannot edit schedule with existing appointments. You can only toggle active status.';
            $slotDurations = [
                15 => '15 minutes',
                30 => '30 minutes',
                45 => '45 minutes',
                60 => '1 hour'
            ];
            return view('doctors.set-appointment-sched', compact('schedule', 'slotDurations', 'error'));
        }

        $slotDurations = [
            15 => '15 minutes',
            30 => '30 minutes',
            45 => '45 minutes',
            60 => '1 hour'
        ];

        return view('doctors.set-appointment-sched', compact('schedule', 'slotDurations'));
    }

    public function update(Request $request, DoctorSchedules $schedule): RedirectResponse
    {
        $doctorId = $this->getDoctorId();
        
        if ($schedule->doctor_id !== $doctorId) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent updating past schedules
        if ($schedule->schedule_date < now()->toDateString()) {
            return redirect()
                ->route('doctor.schedule')
                ->withErrors(['error' => 'Cannot update past schedules.']);
        }

        $validated = $request->validate([
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i|before:end_time',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'required|integer|in:15,30,45,60',
            'max_slots_per_hour' => 'required|integer|min:1|max:20',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        // If there are booked slots, only allow updating is_active and notes
        if ($schedule->booked_slots > 0) {
            $schedule->update([
                'is_active' => $request->boolean('is_active'),
                'notes' => $validated['notes'] ?? $schedule->notes,
            ]);
            
            return redirect()
                ->route('doctor.schedule')
                ->with('success', 'Schedule status updated successfully!');
        }

        // Validate time range is within 8 AM - 4 PM
        $startHour = (int) Carbon::parse($validated['start_time'])->format('H');
        $endHour = (int) Carbon::parse($validated['end_time'])->format('H');
        
        if ($startHour < 8 || $endHour > 16 || ($endHour === 16 && Carbon::parse($validated['end_time'])->format('i') !== '00')) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Schedule must be between 8:00 AM and 4:00 PM.']);
        }

        // Check for overlapping schedules (exclude current schedule)
        $overlapping = DoctorSchedules::forDoctor($doctorId)
            ->where('id', '!=', $schedule->id)
            ->forDate($validated['schedule_date'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return back()
                ->withInput()
                ->withErrors(['schedule_date' => 'A schedule already exists for this date and time range.']);
        }

        try {
            DB::beginTransaction();

            $schedule->fill($validated);
            $schedule->is_active = $request->boolean('is_active', true);
            $schedule->save();

            DB::commit();

            return redirect()
                ->route('doctor.schedule')
                ->with('success', 'Schedule updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update schedule. Please try again.']);
        }
    }

    public function destroy(DoctorSchedules $schedule): RedirectResponse
    {
        $doctorId = $this->getDoctorId();
        
        if ($schedule->doctor_id !== $doctorId) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deleting schedules with booked appointments
        if ($schedule->booked_slots > 0) {
            return redirect()
                ->route('doctor.schedule')
                ->withErrors(['error' => 'Cannot delete schedule with existing appointments.']);
        }

        try {
            $schedule->delete();

            return redirect()
                ->route('doctor.schedule')
                ->with('success', 'Schedule deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('doctor.schedule')
                ->withErrors(['error' => 'Failed to delete schedule. Please try again.']);
        }
    }

    public function toggle(DoctorSchedules $schedule): RedirectResponse
    {
        $doctorId = $this->getDoctorId();
        
        if ($schedule->doctor_id !== $doctorId) {
            abort(403, 'Unauthorized action.');
        }

        $schedule->is_active = !$schedule->is_active;
        $schedule->save();

        $status = $schedule->is_active ? 'activated' : 'deactivated';
        
        return redirect()
            ->route('doctor.schedule')
            ->with('success', "Schedule {$status} successfully!");
    }

    /**
     * Get schedules for a specific month/year via AJAX
     */
    public function getSchedulesByMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:' . (date('Y') - 1) . '|max:' . (date('Y') + 2)
        ]);

        $doctorId = $this->getDoctorId();
        
        $schedules = DoctorSchedules::forDoctor($doctorId)
            ->forMonth($request->year, $request->month)
            ->active()
            ->upcoming()
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();

        return response()->json($schedules);
    }

    /**
     * Get available time slots for a specific schedule (AJAX endpoint)
     */
    public function getAvailableSlots(DoctorSchedules $schedule)
    {
        $doctorId = $this->getDoctorId();
        
        if ($schedule->doctor_id !== $doctorId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }
        
        if (!$schedule->canBookSlot()) {
            return response()->json([
                'success' => false,
                'message' => 'No available slots for this schedule.',
            ], 400);
        }
        
        $slots = $this->generateTimeSlots($schedule);
        
        return response()->json([
            'success' => true,
            'slots' => $slots,
            'available_count' => $schedule->available_slots,
            'total_slots' => $schedule->total_available_slots,
            'booked_slots' => $schedule->booked_slots,
        ]);
    }

    /**
     * Generate time slots based on schedule configuration.
     */
    private function generateTimeSlots(DoctorSchedules $schedule)
    {
        $slots = [];
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        $duration = $schedule->slot_duration;
        
        $currentTime = $startTime->copy();
        
        while ($currentTime->lt($endTime)) {
            $slotEndTime = $currentTime->copy()->addMinutes($duration);
            
            if ($slotEndTime->lte($endTime)) {
                $slots[] = [
                    'start' => $currentTime->format('H:i'),
                    'end' => $slotEndTime->format('H:i'),
                    'display' => $currentTime->format('g:i A') . ' - ' . $slotEndTime->format('g:i A'),
                ];
            }
            
            $currentTime->addMinutes($duration);
        }
        
        return $slots;
    }

    public function patients_table()
    {
        return view('doctors.patients');
    }

    public function reports()
    {
        return view('doctors.reports');
    }
}
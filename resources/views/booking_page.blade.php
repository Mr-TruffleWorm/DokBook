@extends('layouts.main')
 
@section('maincontent')
<main class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Book an Appointment</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Doctor Info --}}
    <div class="border rounded-lg p-4 mb-6 bg-gradient-to-r from-blue-50 to-indigo-50">
        <h2 class="text-xl font-semibold text-gray-800">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h2>
        <p class="text-gray-600 mt-1">{{ implode(', ', $doctor->specializations ?? []) }}</p>
    </div>

    {{-- Progress Steps --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex items-center">
                <div id="step1-indicator" class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">
                    1
                </div>
                <div class="flex-1 h-1 bg-blue-500 mx-2"></div>
            </div>
            <div class="flex-1 flex items-center">
                <div id="step2-indicator" class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">
                    2
                </div>
                <div id="step2-line" class="flex-1 h-1 bg-gray-300 mx-2"></div>
            </div>
            <div class="flex-1 flex items-center">
                <div id="step3-indicator" class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">
                    3
                </div>
            </div>
        </div>
        <div class="flex justify-between mt-2 text-sm">
            <span class="text-blue-500 font-medium">Patient Info</span>
            <span id="step2-label" class="text-gray-500">Appointment</span>
            <span id="step3-label" class="text-gray-500">Contact Details</span>
        </div>
    </div>

    <form method="POST" action="{{ route('book.appointment', $doctorId) }}" id="bookingForm">
        @csrf
        <input type="hidden" name="schedule_id" id="selected_schedule_id">

        {{-- STEP 1: Patient Information --}}
        <div id="step1" class="step-content">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Patient Information</h3>
            
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" 
                           class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" 
                           class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" 
                           class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" 
                           max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                           class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select id="gender" name="gender" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="male" @if(old('gender')==='male') selected @endif>Male</option>
                        <option value="female" @if(old('gender')==='female') selected @endif>Female</option>
                    </select>
                </div>
                <div>
                    <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                    <input type="text" id="blood_type" name="blood_type" value="{{ old('blood_type') }}" 
                           placeholder="e.g., O+, A-, B+"
                           class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <h4 class="text-lg font-semibold mb-3 text-gray-800">Medical Information</h4>
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="allergies" class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                    <textarea id="allergies" name="allergies" rows="2" 
                              placeholder="List any known allergies"
                              class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('allergies') }}</textarea>
                </div>
                <div>
                    <label for="current_medications" class="block text-sm font-medium text-gray-700 mb-1">Current Medications</label>
                    <textarea id="current_medications" name="current_medications" rows="2" 
                              placeholder="List any medications you're taking"
                              class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('current_medications') }}</textarea>
                </div>
                <div>
                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Name</label>
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" 
                           class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Phone</label>
                    <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" 
                           class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="goToStep(2)" 
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    Next: Choose Appointment
                </button>
            </div>
        </div>

        {{-- STEP 2: Appointment Details with Calendar --}}
        <div id="step2" class="step-content hidden">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Select Appointment Date & Time</h3>
            
            {{-- Calendar View --}}
            <div class="mb-6">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Available Dates</label>
                        <div id="calendar-dates" class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach($schedules as $date => $dateSchedules)
                                <button type="button" onclick="selectDate('{{ $date }}')" 
                                        data-date="{{ $date }}"
                                        class="date-btn p-3 border border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition text-center">
                                    <div class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($date)->format('D') }}</div>
                                    <div class="text-lg font-semibold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('M j') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($date)->format('Y') }}</div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div id="time-slots-container" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots</label>
                        <div id="time-slots" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            {{-- Time slots will be populated by JavaScript --}}
                        </div>
                    </div>

                    <div id="selected-slot-info" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Selected Appointment:</p>
                                <p class="text-lg font-semibold text-gray-800" id="selected-slot-text"></p>
                            </div>
                            <button type="button" onclick="clearSelection()" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                Change
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Appointment Purpose --}}
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-3 text-gray-800">Appointment Details</h4>
                <div class="mb-4">
                    <label for="chief_complaint" class="block text-sm font-medium text-gray-700 mb-1">Purpose of Visit *</label>
                    <select name="chief_complaint" id="chief_complaint" 
                            class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Select Purpose --</option>
                        @foreach($complaints as $complaint)
                            <option value="{{ $complaint }}" {{ old('chief_complaint') == $complaint ? 'selected' : '' }}>
                                {{ $complaint }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="symptoms_description" class="block text-sm font-medium text-gray-700 mb-1">Describe Your Symptoms *</label>
                    <textarea name="symptoms_description" id="symptoms_description" rows="4" 
                              placeholder="Please describe your symptoms in detail..."
                              class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>{{ old('symptoms_description') }}</textarea>
                </div>

                <div>
                    <label for="urgency_level" class="block text-sm font-medium text-gray-700 mb-1">Urgency Level *</label>
                    <select name="urgency_level" id="urgency_level" 
                            class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="routine" @if(old('urgency_level')==='routine') selected @endif>Routine</option>
                        <option value="urgent" @if(old('urgency_level')==='urgent') selected @endif>Urgent</option>
                        <option value="emergency" @if(old('urgency_level')==='emergency') selected @endif>Emergency</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="specialization" value="{{ implode(', ', $doctor->specializations ?? []) }}">

            <div class="flex justify-between">
                <button type="button" onclick="goToStep(1)" 
                        class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                    Back
                </button>
                <button type="button" onclick="validateStep2()" 
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    Next: Contact Information
                </button>
            </div>
        </div>

        {{-- STEP 3: Contact Information --}}
        <div id="step3" class="step-content hidden">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Contact Information</h3>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-700">
                    <strong>Note:</strong> We'll send your appointment confirmation and reminders to the email provided below. 
                    <strong class="text-blue-700">You must verify your email address before booking.</strong>
                </p>
            </div>

            <div class="space-y-4 mb-6">
                {{-- Email with Verification --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                    <div class="flex gap-2">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                            placeholder="your.email@example.com"
                            class="flex-1 border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            required>
                        <button type="button" id="sendCodeBtn" onclick="sendVerificationCode()" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition whitespace-nowrap">
                            Send Code
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">We'll send a verification code to this email</p>
                </div>

                {{-- Verification Code Input (Initially Hidden) --}}
                <div id="verification-section" class="hidden">
                    <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-1">Verification Code *</label>
                    <div class="flex gap-2">
                        <input type="text" id="verification_code" 
                            placeholder="Enter 6-digit code"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            class="flex-1 border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="button" onclick="verifyEmailCode()" 
                                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            Verify
                        </button>
                    </div>
                    <div id="verification-message" class="text-xs mt-1"></div>
                    <button type="button" onclick="resendCode()" class="text-xs text-blue-600 hover:text-blue-800 mt-2 underline">
                        Resend Code
                    </button>
                </div>

                {{-- Verification Status --}}
                <div id="verification-success" class="hidden bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm text-green-700 font-medium">Email verified successfully!</span>
                    </div>
                </div>

                {{-- Phone Number --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                        placeholder="+63 912 345 6789"
                        pattern="[0-9+\-\s()]+"
                        class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <p class="text-xs text-gray-500 mt-1">We'll use this number for appointment reminders</p>
                </div>
            </div>

            {{-- Summary (same as before) --}}
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-800 mb-3">Appointment Summary</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Doctor:</span>
                        <span class="font-medium">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Patient:</span>
                        <span class="font-medium" id="summary-patient-name">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Appointment:</span>
                        <span class="font-medium" id="summary-appointment">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Purpose:</span>
                        <span class="font-medium" id="summary-purpose">-</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="button" onclick="goToStep(2)" 
                        class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                    Back
                </button>
                <button type="submit" id="submitBtn"
                        class="bg-green-500 text-white px-8 py-2 rounded-lg hover:bg-green-600 transition font-semibold disabled:bg-gray-300 disabled:cursor-not-allowed"
                        disabled>
                    Confirm & Book Appointment
                </button>
            </div>
        </div>
    </form>
</main>

<script>
// Schedule data from Laravel
const scheduleData = @json($schedules);
let selectedScheduleId = null;
let selectedDate = null;
</script>
<script src="{{ asset('js/booking-form.js') }}"></script>
<script src="{{ asset('js/email-confirmation.js') }}"></script>

<style>
.step-content {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
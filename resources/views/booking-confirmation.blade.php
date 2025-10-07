@extends('layouts.main')

@section('maincontent')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg p-8 text-center">
        <!-- Success Icon -->
        <div class="w-16 h-16 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Appointment Booked Successfully!</h1>
        <p class="text-gray-600 mb-8">
            Thank you for scheduling your appointment. Below are the details of your booking.
        </p>

        <!-- Appointment Summary -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6 text-left">
            <dl class="space-y-4">
                <div>
                    <dt class="font-medium text-gray-700">Doctor</dt>
                    <dd class="text-gray-900">
                        Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                    </dd>
                </div>

                <div>
                    <dt class="font-medium text-gray-700">Service / Illness</dt>
                    <dd class="text-gray-900">{{ $appointment->illness }}</dd>
                </div>

                <div>
                    <dt class="font-medium text-gray-700">Patient</dt>
                    <dd class="text-gray-900">
                        {{ $appointment->first_name }}
                        @if($appointment->middle_name) {{ $appointment->middle_name }} @endif
                        {{ $appointment->last_name }}
                    </dd>
                </div>

                <div>
                    <dt class="font-medium text-gray-700">Contact</dt>
                    <dd class="text-gray-900">
                        {{ $appointment->contact_number }} <br>
                        {{ $appointment->email }}
                    </dd>
                </div>

                <div>
                    <dt class="font-medium text-gray-700">Schedule</dt>
                    <dd class="text-gray-900">
                        {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('F j, Y') }}
                        at {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('g:i A') }}
                    </dd>
                </div>

                <div>
                    <dt class="font-medium text-gray-700">Address</dt>
                    <dd class="text-gray-900">{{ $appointment->address }}</dd>
                </div>
            </dl>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ url('/') }}" 
               class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                Back to Home
            </a>
            <a href="javascript:window.print()" 
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                Print Confirmation
            </a>
        </div>
    </div>
</div>
@endsection

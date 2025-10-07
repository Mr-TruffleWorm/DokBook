@extends('layouts.doctor-page.doctor-dashboard')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            My Appointment Schedules - {{ $monthName }} {{ $currentYear }}
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- LEFT COLUMN: Doctor Schedules --}}
        <div>
            @if($schedules->isEmpty())
                <div class="bg-yellow-100 text-yellow-700 p-4 rounded-md">
                    No schedules found for {{ $monthName }} {{ $currentYear }}.
                </div>
            @else
                @foreach($schedules as $date => $daySchedules)
                    <h2 class="text-lg font-semibold mt-6 mb-2">
                        {{ \Carbon\Carbon::parse($date)->format('F j, Y (l)') }}
                    </h2>

                    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slot Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Slots</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booked</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($daySchedules as $schedule)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->time_range }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->slot_duration }} mins</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->total_available_slots }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->booked_slots }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->available_slots }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($schedule->is_active)
                                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right space-x-2">
                                            <a href="{{ route('doctor.schedule.edit', $schedule->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                            <form action="{{ route('doctor.schedule.destroy', $schedule->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                            <form action="{{ route('doctor.schedule.toggle', $schedule->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-gray-600 hover:underline">
                                                    {{ $schedule->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- RIGHT COLUMN: Appointment Requests --}}
        <div>
            <h2 class="text-xl font-semibold mb-4">Patient Appointment Requests</h2>

            @if($bookings->isEmpty())
                <div class="bg-gray-100 text-gray-600 p-4 rounded-md">
                    No appointment requests at this time.
                </div>
            @else
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                        {{ $booking->first_name }} {{ $booking->last_name }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($booking->appointment_date)->format('M j, Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($booking->appointment_time)->format('g:i A') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        @php
                                            $colors = [
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'confirmed' => 'bg-green-100 text-green-700',
                                                'completed' => 'bg-blue-100 text-blue-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                'no_show' => 'bg-gray-200 text-gray-700'
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $colors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-right space-x-2">
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('doctor.booking.confirm', $booking->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:underline">Confirm</button>
                                            </form>
                                            <form action="{{ route('doctor.booking.cancel', $booking->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:underline">Cancel</button>
                                            </form>
                                        @elseif($booking->status === 'confirmed')
                                            <form action="{{ route('doctor.booking.complete', $booking->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:underline">Mark Completed</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

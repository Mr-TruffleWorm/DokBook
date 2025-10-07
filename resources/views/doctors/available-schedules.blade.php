@extends('layouts.doctor-page.doctor-dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Schedules & Appointments</h1>
                    <p class="text-lg text-gray-600 mt-1">{{ $monthName }} {{ $currentYear }}</p>
                </div>
                <a href="{{ route('doctor.schedule.create') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Schedule
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <strong class="text-red-800 font-medium">Error:</strong>
                        <ul class="mt-2 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-red-700">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('doctor.schedule') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select name="month" id="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="flex-1">
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select name="year" id="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @for($y = now()->year - 1; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors duration-200">
                    Apply Filter
                </button>
            </form>
        </div>

        <!-- Schedules Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">My Schedules</h2>
            
            @if($schedules->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Schedules Found</h3>
                    <p class="text-gray-600 mb-6">You haven't created any schedules for {{ $monthName }} {{ $currentYear }} yet.</p>
                    <a href="{{ route('doctor.schedule.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        Create Your First Schedule
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($schedules as $date => $dateSchedules)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                </h3>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slots</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($dateSchedules as $schedule)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                                                    <span class="text-gray-500">to</span>
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $schedule->slot_duration }} min
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex flex-col">
                                                        <span class="font-medium text-gray-900">
                                                            {{ $schedule->available_slots }} / {{ $schedule->total_available_slots }}
                                                        </span>
                                                        @if($schedule->booked_slots > 0)
                                                            <span class="text-xs text-gray-500">({{ $schedule->booked_slots }} booked)</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($schedule->is_active)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Active
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Inactive
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex flex-wrap gap-2">
                                                        <form action="{{ route('doctor.schedule.toggle', $schedule->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="px-3 py-1 {{ $schedule->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-xs font-medium rounded transition-colors duration-200">
                                                                {{ $schedule->is_active ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </form>
                                                        
                                                        <a href="{{ route('doctor.schedule.edit', $schedule->id) }}" 
                                                           class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                                            Edit
                                                        </a>
                                                        
                                                        @if($schedule->booked_slots == 0)
                                                            <button onclick="confirmDelete({{ $schedule->id }})" 
                                                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                                                Delete
                                                            </button>
                                                        @else
                                                            <button disabled 
                                                                    class="px-3 py-1 bg-gray-300 text-gray-500 text-xs font-medium rounded cursor-not-allowed">
                                                                Delete
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Appointments Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                Appointments 
                <span class="text-lg font-normal text-gray-600">({{ $bookings->count() }})</span>
            </h2>

            @if($bookings->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-600">No appointment requests</p>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($bookings as $booking)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $booking->first_name }} {{ $booking->last_name }}
                                    </h3>
                                    <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($booking->appointment_date)->format('M j, Y') }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($booking->appointment_time)->format('g:i A') }}</span>
                                </div>

                                <div class="pt-3 border-t border-gray-100">
                                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Specialization</p>
                                    <p class="text-sm text-gray-900">{{ $booking->specialization }}</p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Chief Complaint</p>
                                    <p class="text-sm text-gray-900">{{ $booking->chief_complaint }}</p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Symptoms</p>
                                    <p class="text-sm text-gray-700">{{ $booking->symptoms_description }}</p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Urgency Level</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $booking->urgency_level === 'high' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $booking->urgency_level === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $booking->urgency_level === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($booking->urgency_level) }}
                                    </span>
                                </div>
                            </div>

                            @if($booking->status === 'pending')
                                <div class="flex gap-2">
                                    <form action="{{ route('doctor.booking.confirm', $booking->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded transition-colors duration-200">
                                            Confirm
                                        </button>
                                    </form>
                                    <form action="{{ route('doctor.booking.cancel', $booking->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure you want to cancel this appointment?')" 
                                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors duration-200">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            @elseif($booking->status === 'confirmed')
                                <form action="{{ route('doctor.booking.complete', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors duration-200">
                                        Mark as Completed
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>
        
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 pt-5 pb-4 bg-white">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Delete Schedule</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to delete this schedule? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 bg-gray-50 sm:flex sm:flex-row-reverse gap-3">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                </form>
                <button onclick="closeDeleteModal()" type="button" class="w-full inline-flex justify-center px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(scheduleId) {
    document.getElementById('deleteForm').action = '/doctor/schedule/' + scheduleId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
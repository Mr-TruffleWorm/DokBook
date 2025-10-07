@extends('layouts.doctor-page.doctor-dashboard')
@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('doctor.schedule') }}" class="mr-4 text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ isset($schedule) ? 'Edit Schedule' : 'Create Schedule' }}
            </h1>
        </div>
        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800" id="info-display">
                <strong>Hours:</strong> 8:00 AM - 4:00 PM | <strong>Max:</strong> 20 slots/hour | <strong>Durations:</strong> 15, 30, 45, 60 min
            </p>
        </div>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            @foreach($errors->all() as $error)
                <div class="text-sm">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">Schedule Configuration</h2>
        </div>

        <form id="schedule-form" action="{{ isset($schedule) ? route('doctor.schedule.update', $schedule) : route('doctor.schedule.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @if(isset($schedule)) @method('PUT') @endif

            {{-- Date --}}
            <div>
                <label for="schedule_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Schedule Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="schedule_date" id="schedule_date" 
                       value="{{ old('schedule_date', isset($schedule) ? $schedule->schedule_date->format('Y-m-d') : '') }}"
                       min="{{ date('Y-m-d') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Day Display --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Day of Week</label>
                <div id="day-display" class="w-full px-4 py-3 bg-gray-50 border rounded-lg text-gray-600">
                    {{ isset($schedule) ? ucfirst($schedule->day_of_week) : 'Select date' }}
                </div>
            </div>

            {{-- Time Range --}}
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Start Time <span class="text-red-500">*</span>
                    </label>
                    <select name="start_time" id="start_time" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select time</option>
                        @for($h = 8; $h < 16; $h++)
                            @foreach([0, 30] as $m)
                                @php 
                                    $time = sprintf('%02d:%02d', $h, $m);
                                    $display = date('g:i A', strtotime($time));
                                    $selected = old('start_time', isset($schedule) ? $schedule->start_time->format('H:i') : '') === $time;
                                @endphp
                                <option value="{{ $time }}" {{ $selected ? 'selected' : '' }}>{{ $display }}</option>
                            @endforeach
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                        End Time <span class="text-red-500">*</span>
                    </label>
                    <select name="end_time" id="end_time" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select time</option>
                        @for($h = 8; $h <= 16; $h++)
                            @foreach([0, 30] as $m)
                                @php 
                                    $time = sprintf('%02d:%02d', $h, $m);
                                    $display = date('g:i A', strtotime($time));
                                    $selected = old('end_time', isset($schedule) ? $schedule->end_time->format('H:i') : '') === $time;
                                @endphp
                                <option value="{{ $time }}" {{ $selected ? 'selected' : '' }}>{{ $display }}</option>
                            @endforeach
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Slot Config --}}
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="slot_duration" class="block text-sm font-medium text-gray-700 mb-2">
                        Slot Duration <span class="text-red-500">*</span>
                    </label>
                    <select name="slot_duration" id="slot_duration" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select duration</option>
                        @foreach($slotDurations as $min => $label)
                            <option value="{{ $min }}" {{ (old('slot_duration', $schedule->slot_duration ?? '') == $min) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="max_slots_per_hour" class="block text-sm font-medium text-gray-700 mb-2">
                        Max Slots/Hour <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="max_slots_per_hour" id="max_slots_per_hour" 
                           value="{{ old('max_slots_per_hour', $schedule->max_slots_per_hour ?? 20) }}"
                           min="1" max="20" required
                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Optional notes...">{{ old('notes', $schedule->notes ?? '') }}</textarea>
            </div>

            {{-- Active Status --}}
            @if(isset($schedule))
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active</label>
                </div>
            @endif

            {{-- Preview --}}
            <div id="preview" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-900 mb-3">Schedule Preview</h3>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600" id="total-slots">0</div>
                        <div class="text-xs text-gray-600">Total Slots</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" id="duration">0</div>
                        <div class="text-xs text-gray-600">Hours</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600" id="avg-slots">0</div>
                        <div class="text-xs text-gray-600">Avg/Hr</div>
                    </div>
                </div>
                <div id="slots-grid" class="grid grid-cols-4 gap-2 max-h-32 overflow-y-auto"></div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-between pt-6 border-t">
                <button type="button" id="preview-btn" 
                        class="px-4 py-2 text-blue-600 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition-colors">
                    Preview Schedule
                </button>
                <div class="space-x-4">
                    <a href="{{ route('doctor.schedule') }}" 
                       class="px-6 py-3 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                        {{ isset($schedule) ? 'Update Schedule' : 'Create Schedule' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    // Schedule Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('schedule-form');
    const dateInput = document.getElementById('schedule_date');
    const dayDisplay = document.getElementById('day-display');
    const startTimeSelect = document.getElementById('start_time');
    const endTimeSelect = document.getElementById('end_time');
    const slotDurationSelect = document.getElementById('slot_duration');
    const maxSlotsInput = document.getElementById('max_slots_per_hour');
    const previewBtn = document.getElementById('preview-btn');
    const previewSection = document.getElementById('preview');

    // Update day of week when date changes
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value + 'T00:00:00');
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = days[selectedDate.getDay()];
            dayDisplay.textContent = dayName;
        });

        // Trigger on page load if date is already selected
        if (dateInput.value) {
            dateInput.dispatchEvent(new Event('change'));
        }
    }

    // Validate end time is after start time
    function validateTimeRange() {
        if (startTimeSelect.value && endTimeSelect.value) {
            const start = convertTimeToMinutes(startTimeSelect.value);
            const end = convertTimeToMinutes(endTimeSelect.value);
            
            if (end <= start) {
                endTimeSelect.setCustomValidity('End time must be after start time');
                return false;
            } else {
                endTimeSelect.setCustomValidity('');
                return true;
            }
        }
        return true;
    }

    function convertTimeToMinutes(time) {
        const [hours, minutes] = time.split(':').map(Number);
        return hours * 60 + minutes;
    }

    startTimeSelect.addEventListener('change', validateTimeRange);
    endTimeSelect.addEventListener('change', validateTimeRange);

    // Preview functionality
    if (previewBtn) {
        previewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const startTime = startTimeSelect.value;
            const endTime = endTimeSelect.value;
            const slotDuration = parseInt(slotDurationSelect.value);
            const maxSlots = parseInt(maxSlotsInput.value);

            if (!startTime || !endTime || !slotDuration || !maxSlots) {
                alert('Please fill in all required fields to preview the schedule.');
                return;
            }

            if (!validateTimeRange()) {
                alert('Please ensure end time is after start time.');
                return;
            }

            generatePreview(startTime, endTime, slotDuration, maxSlots);
        });
    }

    function generatePreview(startTime, endTime, slotDuration, maxSlotsPerHour) {
        const start = convertTimeToMinutes(startTime);
        const end = convertTimeToMinutes(endTime);
        const durationInMinutes = end - start;
        const hours = durationInMinutes / 60;
        
        // Calculate total slots based on max slots per hour
        const totalSlots = Math.floor(hours * maxSlotsPerHour);
        
        // Calculate average slots per hour
        const avgSlots = (totalSlots / hours).toFixed(1);

        // Update preview statistics
        document.getElementById('total-slots').textContent = totalSlots;
        document.getElementById('duration').textContent = hours.toFixed(1);
        document.getElementById('avg-slots').textContent = avgSlots;

        // Generate slot grid
        const slotsGrid = document.getElementById('slots-grid');
        slotsGrid.innerHTML = '';

        const slots = generateTimeSlots(startTime, endTime, slotDuration, totalSlots);
        
        slots.forEach((slot, index) => {
            const slotElement = document.createElement('div');
            slotElement.className = 'text-xs p-2 bg-white border border-blue-300 rounded text-center text-blue-700';
            slotElement.textContent = slot;
            slotsGrid.appendChild(slotElement);
        });

        // Show preview section
        previewSection.classList.remove('hidden');
        previewSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function generateTimeSlots(startTime, endTime, duration, maxSlots) {
        const slots = [];
        let currentMinutes = convertTimeToMinutes(startTime);
        const endMinutes = convertTimeToMinutes(endTime);
        let slotCount = 0;

        while (currentMinutes < endMinutes && slotCount < maxSlots) {
            const nextMinutes = currentMinutes + duration;
            
            if (nextMinutes <= endMinutes) {
                const startFormatted = formatTime(currentMinutes);
                const endFormatted = formatTime(nextMinutes);
                slots.push(`${startFormatted} - ${endFormatted}`);
                slotCount++;
            }
            
            currentMinutes = nextMinutes;
        }

        // If we haven't reached maxSlots, evenly distribute remaining slots
        if (slotCount < maxSlots) {
            const remainingSlots = maxSlots - slotCount;
            for (let i = 0; i < remainingSlots; i++) {
                slots.push(`Slot ${slotCount + i + 1}`);
            }
        }

        return slots;
    }

    function formatTime(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        const period = hours >= 12 ? 'PM' : 'AM';
        const displayHours = hours > 12 ? hours - 12 : (hours === 0 ? 12 : hours);
        return `${displayHours}:${mins.toString().padStart(2, '0')} ${period}`;
    }

    // Form validation before submit
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateTimeRange()) {
                e.preventDefault();
                alert('Please correct the time range before submitting.');
                return false;
            }

            // Additional validation for time within 8 AM - 4 PM
            const startHour = parseInt(startTimeSelect.value.split(':')[0]);
            const endTime = endTimeSelect.value.split(':');
            const endHour = parseInt(endTime[0]);
            const endMinute = parseInt(endTime[1]);

            if (startHour < 8) {
                e.preventDefault();
                alert('Start time must be at or after 8:00 AM');
                return false;
            }

            if (endHour > 16 || (endHour === 16 && endMinute > 0)) {
                e.preventDefault();
                alert('End time must be at or before 4:00 PM');
                return false;
            }

            return true;
        });
    }

    // Auto-calculate and show quick preview when all fields are filled
    const fieldsToWatch = [startTimeSelect, endTimeSelect, slotDurationSelect, maxSlotsInput];
    fieldsToWatch.forEach(field => {
        if (field) {
            field.addEventListener('change', function() {
                const allFilled = fieldsToWatch.every(f => f && f.value);
                if (allFilled && validateTimeRange()) {
                    // Auto-generate preview
                    setTimeout(() => {
                        previewBtn.click();
                    }, 300);
                }
            });
        }
    });
});
</script>
@endsection
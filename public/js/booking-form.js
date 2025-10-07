// Helper: Show error under a field
function showError(input, message) {
    clearError(input);
    const errorMsg = document.createElement('p');
    errorMsg.className = 'text-red-600 text-sm mt-1 error-message';
    errorMsg.textContent = message;
    input.classList.add('border-red-500', 'focus:ring-red-500');
    input.insertAdjacentElement('afterend', errorMsg);
}

// Helper: Clear previous errors
function clearError(input) {
    input.classList.remove('border-red-500', 'focus:ring-red-500');
    const next = input.nextElementSibling;
    if (next && next.classList.contains('error-message')) {
        next.remove();
    }
}

// Helper: Clear all errors in a step
function clearStepErrors(stepId) {
    document.querySelectorAll(`#${stepId} .error-message`).forEach(el => el.remove());
    document.querySelectorAll(`#${stepId} input, #${stepId} select, #${stepId} textarea`)
        .forEach(el => el.classList.remove('border-red-500', 'focus:ring-red-500'));
}

// Step navigation
function goToStep(step) {
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('step' + step).classList.remove('hidden');
    updateProgressIndicators(step);

    if (step === 3) updateSummary();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Progress indicator logic (unchanged)
function updateProgressIndicators(activeStep) {
    for (let i = 1; i <= 3; i++) {
        const indicator = document.getElementById('step' + i + '-indicator');
        const label = document.getElementById('step' + i + '-label');

        if (i < activeStep) {
            indicator.classList.remove('bg-gray-300', 'text-gray-600', 'bg-blue-500');
            indicator.classList.add('bg-green-500', 'text-white');
            if (label) label.classList.add('text-green-500');
        } else if (i === activeStep) {
            indicator.classList.remove('bg-gray-300', 'bg-green-500');
            indicator.classList.add('bg-blue-500', 'text-white');
            if (label) label.classList.add('text-blue-500', 'font-medium');
        } else {
            indicator.classList.remove('bg-blue-500', 'bg-green-500');
            indicator.classList.add('bg-gray-300', 'text-gray-600');
            if (label) label.classList.remove('text-blue-500', 'text-green-500');
        }
    }

    const step2Line = document.getElementById('step2-line');
    if (activeStep >= 2) {
        step2Line.classList.add('bg-blue-500');
        step2Line.classList.remove('bg-gray-300');
    } else {
        step2Line.classList.add('bg-gray-300');
        step2Line.classList.remove('bg-blue-500');
    }
}

// Step 1 validation
function validateStep1() {
    clearStepErrors('step1');
    let valid = true;

    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');

    if (!firstName.value.trim()) {
        showError(firstName, 'First name is required');
        valid = false;
    }

    if (!lastName.value.trim()) {
        showError(lastName, 'Last name is required');
        valid = false;
    }

    if (valid) goToStep(2);
    else window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Date selection (unchanged)
function selectDate(date) {
    selectedDate = date;
    document.querySelectorAll('.date-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'bg-blue-100');
        btn.classList.add('border-gray-300');
    });

    const selectedBtn = document.querySelector(`[data-date="${date}"]`);
    selectedBtn.classList.add('border-blue-500', 'bg-blue-100');
    displayTimeSlots(date);
}

function displayTimeSlots(date) {
    const timeSlotsContainer = document.getElementById('time-slots-container');
    const timeSlotsDiv = document.getElementById('time-slots');
    const schedules = scheduleData[date];

    if (!schedules || schedules.length === 0) {
        timeSlotsDiv.innerHTML = '<p class="text-gray-500 text-center py-4">No available slots for this date</p>';
        timeSlotsContainer.classList.remove('hidden');
        return;
    }

    timeSlotsDiv.innerHTML = '';
    schedules.forEach(schedule => {
        const availableSlots = schedule.total_available_slots - schedule.booked_slots;
        const isAvailable = availableSlots > 0;

        const startTime = formatTime(schedule.start_time);
        const endTime = formatTime(schedule.end_time);

        const slotBtn = document.createElement('button');
        slotBtn.type = 'button';
        slotBtn.className = `p-4 border rounded-lg text-left transition ${isAvailable
            ? 'border-gray-300 hover:border-blue-500 hover:bg-blue-50 cursor-pointer'
            : 'border-gray-200 bg-gray-100 cursor-not-allowed opacity-60'
        }`;
        slotBtn.disabled = !isAvailable;

        slotBtn.innerHTML = `
            <div class="flex justify-between items-start mb-2">
                <div class="font-semibold text-gray-800">${startTime} - ${endTime}</div>
                <div class="text-xs ${isAvailable ? 'text-green-600' : 'text-red-600'} font-medium">
                    ${availableSlots} slot${availableSlots !== 1 ? 's' : ''}
                </div>
            </div>
            <div class="text-xs text-gray-500">${isAvailable ? 'Click to select' : 'Fully booked'}</div>
        `;

        if (isAvailable) {
            slotBtn.onclick = () => selectTimeSlot(schedule.id, date, startTime, endTime);
        }

        timeSlotsDiv.appendChild(slotBtn);
    });

    timeSlotsContainer.classList.remove('hidden');
}

function selectTimeSlot(scheduleId, date, startTime, endTime) {
    selectedScheduleId = scheduleId;
    document.getElementById('selected_schedule_id').value = scheduleId;

    const dateObj = new Date(date);
    const dateFormatted = dateObj.toLocaleDateString('en-US', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });

    const displayText = `${dateFormatted} at ${startTime} - ${endTime}`;
    document.getElementById('selected-slot-text').textContent = displayText;
    document.getElementById('selected-slot-info').classList.remove('hidden');

    document.querySelectorAll('#time-slots button').forEach(btn => {
        btn.classList.remove('border-blue-500', 'bg-blue-100');
    });
    event.target.closest('button').classList.add('border-blue-500', 'bg-blue-100');
}

function clearSelection() {
    selectedScheduleId = null;
    selectedDate = null;
    document.getElementById('selected_schedule_id').value = '';
    document.getElementById('selected-slot-info').classList.add('hidden');
    document.getElementById('time-slots-container').classList.add('hidden');
    document.querySelectorAll('.date-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'bg-blue-100');
        btn.classList.add('border-gray-300');
    });
}

function formatTime(timeString) {
    const time = new Date('2000-01-01 ' + timeString);
    return time.toLocaleTimeString('en-US', {
        hour: 'numeric', minute: '2-digit', hour12: true
    });
}

// Step 2 validation
function validateStep2() {
    clearStepErrors('step2');
    let valid = true;

    if (!selectedScheduleId) {
        const container = document.getElementById('calendar-dates');
        const msg = document.createElement('p');
        msg.className = 'text-red-600 text-sm mt-2';
        msg.textContent = 'Please select a date and time slot.';
        container.insertAdjacentElement('afterend', msg);
        valid = false;
    }

    const chiefComplaint = document.getElementById('chief_complaint');
    const symptomsDescription = document.getElementById('symptoms_description');

    if (!chiefComplaint.value.trim()) {
        showError(chiefComplaint, 'Please select the purpose of your visit');
        valid = false;
    }

    if (!symptomsDescription.value.trim()) {
        showError(symptomsDescription, 'Please describe your symptoms');
        valid = false;
    }

    if (valid) goToStep(3);
}

// Step 3 summary + form submit check
function updateSummary() {
    const firstName = document.getElementById('first_name').value;
    const lastName = document.getElementById('last_name').value;
    const patientName = `${firstName} ${lastName}`.trim() || '-';
    const selectedSlotText = document.getElementById('selected-slot-text').textContent || '-';
    const chiefComplaint = document.getElementById('chief_complaint').value || '-';

    document.getElementById('summary-patient-name').textContent = patientName;
    document.getElementById('summary-appointment').textContent = selectedSlotText;
    document.getElementById('summary-purpose').textContent = chiefComplaint;
}

// Final form submission validation
document.getElementById('bookingForm').addEventListener('submit', function (e) {
    clearStepErrors('step3');
    let valid = true;

    const email = document.getElementById('email');
    const phone = document.getElementById('phone');

    if (!selectedScheduleId) {
        valid = false;
        goToStep(2);
        alert('Please select a date and time slot before submitting.');
        return;
    }

    if (!email.value.trim()) {
        showError(email, 'Email address is required');
        valid = false;
    } else if (!/^\S+@\S+\.\S+$/.test(email.value)) {
        showError(email, 'Enter a valid email address');
        valid = false;
    }

    if (!phone.value.trim()) {
        showError(phone, 'Phone number is required');
        valid = false;
    }

    if (!valid) e.preventDefault();
});

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    updateProgressIndicators(1);
    // Attach event for next button of step 1
    const nextBtnStep1 = document.querySelector('#step1 button[onclick="goToStep(2)"]');
    if (nextBtnStep1) nextBtnStep1.setAttribute('onclick', 'validateStep1()');
});

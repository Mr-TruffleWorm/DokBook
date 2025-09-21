@extends('admin.admin-dashboard')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8">
    <!-- Progress Indicator -->
    <div class="mb-8 flex items-center justify-center">
        <div class="flex items-center w-full max-w-md">
            <div class="flex items-center flex-1">
                <div id="step1-indicator" class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium">1</div>
                <div class="ml-2 text-sm font-medium text-blue-600">Personal Info</div>
            </div>
            <div class="flex-1 h-1 mx-4 bg-gray-200 rounded">
                <div id="progress-line" class="h-full bg-blue-600 rounded transition-all duration-500 w-0"></div>
            </div>
            <div class="flex items-center flex-1 justify-end">
                <div class="mr-2 text-sm font-medium text-gray-400">Account Setup</div>
                <div id="step2-indicator" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">2</div>
            </div>
        </div>
    </div>

    <form id="doctorWizardForm" action="{{ route('admin.add-doctor') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Step 1: Personal Information -->
        <div id="step1">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Doctor Personal Information</h2>
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @foreach([
                    ['first_name', 'First Name', 'text', true],
                    ['last_name', 'Last Name', 'text', true],
                    ['phone', 'Phone Number', 'tel', true],
                    ['license_number', 'Medical License Number', 'text', true]
                ] as [$name, $label, $type, $required])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }} @if($required)*@endif</label>
                        <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name) }}" @if($required)required @endif
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error($name) border-red-500 @else border-gray-300 @enderror">
                        @error($name)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                @endforeach
            </div>

            <!-- Address Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Address Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                        <input type="text" name="street_address" value="{{ old('street_address') }}" required
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('street_address') border-red-500 @else border-gray-300 @enderror">
                        @error('street_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    @foreach([
                        ['city', 'City'],
                        ['state', 'State/Province'],
                        ['postal_code', 'ZIP/Postal Code']
                    ] as [$name, $label])
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }} *</label>
                            <input type="text" name="{{ $name }}" value="{{ old($name) }}" required
                                class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error($name) border-red-500 @else border-gray-300 @enderror">
                            @error($name)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                        <select name="country" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @else border-gray-300 @enderror">
                            <option value="">Select Country</option>
                            @foreach(['PH' => 'Philippines'] as $code => $name)
                                <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Professional Info -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Professional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                        <input type="number" name="years_experience" value="{{ old('years_experience') }}" min="0" max="50"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('years_experience') border-red-500 @else border-gray-300 @enderror">
                        @error('years_experience')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Medical School</label>
                        <input type="text" name="medical_school" value="{{ old('medical_school') }}"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('medical_school') border-red-500 @else border-gray-300 @enderror">
                        @error('medical_school')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hospital/Clinic Affiliation</label>
                        <input type="text" name="hospital_affiliation" value="{{ old('hospital_affiliation') }}"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('hospital_affiliation') border-red-500 @else border-gray-300 @enderror">
                        @error('hospital_affiliation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Specializations Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Medical Specializations</h3>
                <div id="selectedSpecializations" class="mb-4 min-h-[50px] p-3 border-2 border-dashed border-gray-300 rounded-lg">
                    <div class="text-sm text-gray-500 mb-2">Selected specializations:</div>
                    <div id="specializationTags" class="flex flex-wrap gap-2"></div>
                </div>
                <div class="relative">
                    <input type="text" id="specializationInput" placeholder="Type to search specializations..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" autocomplete="off">
                    <div id="specializationSuggestions" class="absolute z-10 w-full bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto hidden"></div>
                </div>
                <div id="specializationHiddenInputs"></div>
                @error('specializations')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end">
                <button type="button" id="nextToStep2" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors">
                    Next: Account Setup
                </button>
            </div>
        </div>

       <!-- Step 2: Account Setup -->
        <div id="step2" class="hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Account Setup</h2>

            @include('auth.register_fields')

            <div class="flex justify-between mt-8">
                <button type="button" id="backToStep1"
                    class="px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-400 transition-colors">
                    Back: Personal Info
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors">
                    Create Doctor Account
                </button>
            </div>
        </div>
    </form>
</div>

<script>
const specializations = [
    'Anesthesiology', 'Cardiology', 'Dermatology', 'Emergency Medicine', 'Endocrinology',
    'Family Medicine', 'Gastroenterology', 'General Surgery', 'Geriatrics', 'Hematology',
    'Infectious Disease', 'Internal Medicine', 'Nephrology', 'Neurology', 'Neurosurgery',
    'Obstetrics and Gynecology', 'Oncology', 'Ophthalmology', 'Orthopedics', 'Otolaryngology',
    'Pathology', 'Pediatrics', 'Physical Medicine', 'Plastic Surgery', 'Psychiatry',
    'Pulmonology', 'Radiology', 'Rheumatology', 'Urology', 'Vascular Surgery'
];

const elements = {
    step1: document.getElementById('step1'),
    step2: document.getElementById('step2'),
    step1Indicator: document.getElementById('step1-indicator'),
    step2Indicator: document.getElementById('step2-indicator'),
    progressLine: document.getElementById('progress-line'),
    specializationInput: document.getElementById('specializationInput'),
    suggestions: document.getElementById('specializationSuggestions'),
    tags: document.getElementById('specializationTags'),
    hiddenInputs: document.getElementById('specializationHiddenInputs')
};

let selectedSpecializations = @json(old('specializations', []));

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    if (selectedSpecializations.length) renderTags();
    @if($errors->any())
        const step2Fields = ['email', 'password'];
        if (@json($errors->keys()).some(e => step2Fields.some(f => e.includes(f)))) {
            goToStep(2);
        }
    @endif
});

// Step navigation
document.getElementById('nextToStep2').onclick = () => validateStep1() && goToStep(2);
document.getElementById('backToStep1').onclick = () => goToStep(1);

function goToStep(step) {
    elements.step1.classList.toggle('hidden', step !== 1);
    elements.step2.classList.toggle('hidden', step !== 2);
    
    if (step === 1) {
        elements.step1Indicator.className = 'w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium';
        elements.step2Indicator.className = 'w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium';
        elements.step1Indicator.textContent = '1';
        elements.progressLine.style.width = '0%';
    } else {
        elements.step1Indicator.className = 'w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-medium';
        elements.step2Indicator.className = 'w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium';
        elements.step1Indicator.textContent = '✓';
        elements.progressLine.style.width = '100%';
    }
}

function validateStep1() {
    const required = ['first_name', 'last_name', 'phone', 'license_number', 'street_address', 'city', 'state', 'postal_code', 'country'];
    for (const field of required) {
        const input = document.querySelector(`[name="${field}"]`);
        if (!input.value.trim()) {
            input.focus();
            input.classList.add('border-red-500');
            setTimeout(() => input.classList.remove('border-red-500'), 3000);
            return false;
        }
    }
    return true;
}

// Specialization functionality
elements.specializationInput.oninput = function() {
    const query = this.value.toLowerCase();
    if (!query) return elements.suggestions.classList.add('hidden');
    
    const filtered = specializations.filter(s => s.toLowerCase().includes(query) && !selectedSpecializations.includes(s));
    elements.suggestions.innerHTML = filtered.length ? 
        filtered.map(s => `<div class="p-3 cursor-pointer hover:bg-gray-50 border-b" data-spec="${s}">${s}</div>`).join('') :
        `<div class="p-3 text-gray-500">Press Enter to add "${this.value}"</div>`;
    elements.suggestions.classList.remove('hidden');
};

elements.specializationInput.onkeydown = e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = e.target.value.trim();
        if (value && !selectedSpecializations.includes(value)) addSpecialization(value);
    }
};

elements.suggestions.onclick = e => {
    const spec = e.target.dataset.spec;
    if (spec) addSpecialization(spec);
};

document.onclick = e => {
    if (!elements.specializationInput.contains(e.target) && !elements.suggestions.contains(e.target)) {
        elements.suggestions.classList.add('hidden');
    }
};

function addSpecialization(spec) {
    selectedSpecializations.push(spec);
    renderTags();
    elements.specializationInput.value = '';
    elements.suggestions.classList.add('hidden');
}

function removeSpecialization(spec) {
    selectedSpecializations = selectedSpecializations.filter(s => s !== spec);
    renderTags();
}

function renderTags() {
    elements.tags.innerHTML = selectedSpecializations.map(s => 
        `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
            ${s}
            <button type="button" class="ml-2 text-blue-600 hover:text-blue-800" onclick="removeSpecialization('${s}')">×</button>
        </span>`
    ).join('');
    
    elements.hiddenInputs.innerHTML = selectedSpecializations.map((s, i) => 
        `<input type="hidden" name="specializations[${i}]" value="${s}">`
    ).join('');
}
</script>
@endsection
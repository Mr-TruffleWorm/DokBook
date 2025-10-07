<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Information
            'first_name'           => ['required', 'string', 'max:100'],
            'last_name'            => ['required', 'string', 'max:100'],
            'phone'                => ['required', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'license_number'       => ['required', 'string', 'max:50', 'unique:doctor_information,license_number'],
            
            // Address Information
            'street_address'       => ['required', 'string', 'max:255'],
            'city'                 => ['required', 'string', 'max:100'],
            'state'                => ['required', 'string', 'max:100'],
            'postal_code'          => ['required', 'string', 'max:20'],
            'country'              => ['required', 'string', 'max:5'],
            
            // Professional Information
            'years_experience'     => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_school'       => ['nullable', 'string', 'max:255'],
            'hospital_affiliation' => ['nullable', 'string', 'max:255'],
            'specializations'      => ['nullable', 'array', 'min:1'],
            'specializations.*'    => ['string', 'max:100'],

            // Account Information
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'             => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Personal Information
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'license_number.required' => 'Medical license number is required.',
            'license_number.unique' => 'This medical license number is already registered.',
            
            // Address
            'street_address.required' => 'Street address is required.',
            'city.required' => 'City is required.',
            'state.required' => 'State/Province is required.',
            'postal_code.required' => 'ZIP/Postal code is required.',
            'country.required' => 'Country is required.',
            
            // Professional
            'years_experience.integer' => 'Years of experience must be a number.',
            'years_experience.min' => 'Years of experience cannot be negative.',
            'years_experience.max' => 'Years of experience cannot exceed 50 years.',
            'specializations.min' => 'Please select at least one specialization.',
            
            // Account
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'phone' => 'phone number',
            'license_number' => 'medical license number',
            'street_address' => 'street address',
            'postal_code' => 'ZIP/postal code',
            'years_experience' => 'years of experience',
            'medical_school' => 'medical school',
            'hospital_affiliation' => 'hospital affiliation',
            'specializations' => 'specializations',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean phone number
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^0-9+]/', '', $this->phone)
            ]);
        }

        // Clean postal code
        if ($this->has('postal_code')) {
            $this->merge([
                'postal_code' => strtoupper(trim($this->postal_code))
            ]);
        }
    }
}

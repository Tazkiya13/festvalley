<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'package_id' => 'required|exists:packages,id',
            'available_date_id' => 'required|exists:available_dates,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'event_type' => 'required|string|max:100',
            'event_date' => 'required|date|after:today',
            'event_time' => 'nullable|string|max:10',
            'event_location' => 'required|string|max:500',
            'venue_address' => 'nullable|string|max:1000',
            'event_description' => 'nullable|string|max:1000',
            'special_requirements' => 'nullable|string|max:1000',
            'technical_requirements' => 'nullable|string|max:1000',
            'guest_count' => 'nullable|integer|min:1|max:10000',
            'event_duration' => 'nullable|string|max:50',
            'budget_range' => 'nullable|string|max:50',
            'customer_company' => 'nullable|string|max:255',
        ];

        // For non-authenticated users, password is required
        if (!Auth::check()) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'package_id.required' => 'Package selection is required.',
            'package_id.exists' => 'Selected package does not exist.',
            'available_date_id.required' => 'Please select an available date.',
            'available_date_id.exists' => 'Selected date is not available.',
            'customer_name.required' => 'Your name is required.',
            'customer_name.max' => 'Name cannot exceed 255 characters.',
            'customer_email.required' => 'Email address is required.',
            'customer_email.email' => 'Please enter a valid email address.',
            'customer_email.max' => 'Email cannot exceed 255 characters.',
            'customer_phone.max' => 'Phone number cannot exceed 20 characters.',
            'event_type.required' => 'Event type is required.',
            'event_type.max' => 'Event type cannot exceed 100 characters.',
            'event_date.required' => 'Event date is required.',
            'event_date.date' => 'Please enter a valid date.',
            'event_date.after' => 'Event date must be in the future.',
            'event_location.required' => 'Event location is required.',
            'event_location.max' => 'Event location cannot exceed 500 characters.',
            'event_description.max' => 'Event description cannot exceed 1000 characters.',
            'special_requirements.max' => 'Special requirements cannot exceed 1000 characters.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'package_id' => 'package',
            'available_date_id' => 'available date',
            'customer_name' => 'name',
            'customer_email' => 'email',
            'customer_phone' => 'phone number',
            'event_type' => 'event type',
            'event_date' => 'event date',
            'event_location' => 'event location',
            'event_description' => 'event description',
            'special_requirements' => 'special requirements',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // set to true if no extra authorization needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|string|max:50|unique:students,student_id',
            'section_id' => 'nullable|exists:sections,id',
            'name_bn' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_occupation' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string',
            'present_address' => 'nullable|string',
            'dob' => 'nullable|date',
            'birth_certificate_no' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:50',
            'gender' => 'nullable|in:Male,Female,Others',
            'class_applied' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'fee_waiver' => 'required|between:1,127',
            'roll' => 'nullable|max:50',
            'shift' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string|max:20|unique:students,phone',
            'transport_type' => 'nullable|in:Self,School Van',
            'monthly_fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:0,1',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Custom messages (optional)
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'Student ID is required.',
            'student_id.unique' => 'This student ID already exists.',
            'name_bn.required' => 'Bangla name is required.',
            'name_en.required' => 'English name is required.',
            'dob.required' => 'Date of birth is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already used.',
            'phone.unique' => 'This phone number is already used.',
            'profile_image.image' => 'Profile image must be a valid picture.',
        ];
    }
}

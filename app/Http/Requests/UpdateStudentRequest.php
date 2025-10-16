<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest {
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('student'); // assumes {student} in route model binding

        return [
            // 'student_id' => [
            //     'required',
            //     'string',
            //     'max:50',
            //     Rule::unique('students', 'student_id')->ignore($studentId),
            // ],
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
            'email' => [
                'nullable',
                'email',
                Rule::unique('students', 'email')->ignore($studentId),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('students', 'phone')->ignore($studentId),
            ],
            'transport_type' => 'nullable|in:Self,School Van',
            'monthly_fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:0,1',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}

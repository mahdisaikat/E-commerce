<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest {
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
        return [
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:10',
        ];
    }
}

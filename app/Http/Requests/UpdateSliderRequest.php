<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
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
        return [
            'title' => ['nullable', 'string', 'max:6'],
            'link' => ['nullable', 'url', 'max:255'],
            'header' => ['nullable', 'string', 'max:12'],
            'details' => ['nullable', 'string', 'max:24'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:' . env('MAX_FILE_UPLOAD_SIZE')],
        ];
    }
}

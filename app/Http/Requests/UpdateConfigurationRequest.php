<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigurationRequest extends FormRequest
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
        $valueRules = match ($this->type) {
            'color' => ['string', 'max:20'],
            'file' => ['mimes:jpeg,png,jpg,gif', 'max:2048'],
            'number' => ['numeric'],
            'boolean' => ['boolean'],
            'json' => ['json'],
            default => ['string', 'max:1000'],
        };

        return [
            'type' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'value' => array_merge(['required'], $valueRules),
        ];
    }

}

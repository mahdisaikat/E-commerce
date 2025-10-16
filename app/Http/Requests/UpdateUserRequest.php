<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest {
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
        $userId = $this->route('user')?->id ?? $this->id; // Adjust based on how you're binding the user

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'mobile' => [
                'nullable',
                'numeric',
                Rule::unique('users', 'mobile')->ignore($userId),
            ],
            'username' => [
                'nullable',
                'string',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'password' => [
                'nullable', // Allow empty password if not updating it
                'string',
                'min:6',
                'confirmed',
            ],
            'role' => 'required|exists:roles,name',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

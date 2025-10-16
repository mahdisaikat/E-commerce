<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $permissionId = $this->route('permission'); 
        // This assumes your route parameter is {permission}

        return [
            'display_name' => ['required', 'string', 'max:255'],
            'module_name'  => ['required', 'string', 'max:255'],
            'name'         => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($permissionId)
            ],
        ];
    }
}

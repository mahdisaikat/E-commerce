<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'product_category' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:' . env('MAX_FILE_UPLOAD_SIZE')],
            'price' => ['nullable', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'stock_quantity' => ['nullable', 'integer'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}

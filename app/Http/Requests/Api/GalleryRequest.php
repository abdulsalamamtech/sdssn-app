<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
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
            // [1MB 1024, 2MB 2048, 5MB 5120, 10MB 10240]
            'banner' => ['required','image','mimes:jpeg,png,jpg,gif,svg', 'max:5120'], // 5MB limit
            'title' => ['required','string', 'max:500'],
            'description' => ['nullable','string'],
        ];
    }
}

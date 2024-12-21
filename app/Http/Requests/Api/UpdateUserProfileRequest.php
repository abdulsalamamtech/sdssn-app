<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'security_question' => ['nullable', 'string'],
            'answer' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string'],
            'gender' => ['nullable', 'string'],
            'dob' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],

            'profession' => ['nullable', 'string'],
            // Organization
            'organization' => ['nullable', 'string'],
            'organization_category' => ['nullable', 'string'],
            'organization_role' => ['nullable', 'string'],


            'state' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],

        ];
    }
}

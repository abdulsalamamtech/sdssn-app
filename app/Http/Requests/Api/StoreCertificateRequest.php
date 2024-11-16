<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
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
            // 'added_by' => ['required', 'integer'],
            // 'asset_id' => ['required', 'string'],
            'belong_to' => ['required', 'integer', 'exists:users,id'],
            'certificate' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'course' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }
}

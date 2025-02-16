<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'banner' => ['nullable','image', 'max:5120'],
            'title' => ['nullable','string', 'max:500'],
            'description' => ['nullable','string'],
            'tags' => ['nullable','string'],
            'category' => ['nullable','string', 'in:map,discussion,link'], 
            'status' => ['nullable','string', 'in:public,private,draft'],
            // 'approved_by' => ['nullable','string'],
            // 'views' => ['nullable','integer'],
            // 'likes' => ['nullable','integer']
            // 'shares' => ['nullable','integer'],
            // 'deleted_by' => ['required','integer']
        ];
    }
}

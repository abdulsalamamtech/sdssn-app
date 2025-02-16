<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'banner' => ['required','image', 'max:5048'], // 5MB limit
            'title' => ['required','string', 'max:500'],
            'description' => ['required','string'],
            'tags' => ['required','string'],
            'category' => ['required','string', 'in:map,discussion,link'], 
            'status' => ['required','string', 'in:public,private,draft'],
            // 'approved_by' => ['required','string'],
            // 'views' => ['required','integer'],
            // 'likes' => ['required','integer'],
            // 'shares' => ['required','integer'],
            // 'deleted_by' => ['required','integer']
        ];
    }
}
